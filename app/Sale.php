<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Sale extends Model
{
    public $invoice_prefix = '';

    public $tax_percentage = 10;

    public static $rules = [];

    /**
     * setup variable mass assignment.
     *
     * @var array
     */
    protected $fillable = [
        'customer_id',
        'cashier_id',
        'name',
        'email',
        'phone',
        'address',
        'comment',
        'type',
        'status',
        'amount',
        'discount',
        'vat',
        'total_given',
        'change',
        'payment_with',
        'delivery_cost',
        'type_of_tip', /*Added*/
        'value_tip', /*Added*/
        'folio', /*Added*/
        'tax_card',  /**/
        'NumComanda',  /**/
        'c_meta06', /*added_*/
        'comments',
        'created_at', /* algunas Fechas Anteriores */
    ];

    protected $appends = [
        'invoice_no',
    ];

    const ACTIVE = 1;

    public function items()
    {
        return $this->hasMany('App\SaleItem', 'Sale_id');
    }

    public function cashier()
    {
        return $this->belongsTo('App\User', 'cashier_id');
    }

    public function customer()
    {
        return $this->belongsTo('App\Customer');
    }

    public static function search($params = [])
    {
        return self::when(
            !empty($params), function ($query) use ($params) {
            switch ($params['date_range']) {
                case 'today':
                    $query->whereDay('created_at', '=', date('d'));
                    break;
                case 'current_week':
                    $query->where('created_at', '>=', date('Y-m-d h:i:s', strtotime("-7 days")));
                    break;
                case 'current_month':
                    $query->whereMonth('created_at', '=', date('m'));
                    break;
                default:

                    break;
            }

            return $query;
        }
        )->select("*", "sales.id as id")->leftJoin("sale_items as s", "s.sale_id", '=', "sales.id")->orderBy('sales.created_at', 'DESC');
    }

    public static function createAll($input_form)
    {

        $tmp_custom_fecha = null;
        if (array_key_exists('newfecha', $input_form)) {
            // Nueva fecha si no es de hoy
            $tmp_custom_fecha = $input_form['newfecha'];
            $input_form['created_at'] = $tmp_custom_fecha . ' ' . date("H:i:s");;
        }

        return DB::transaction(
            function () use ($input_form, $tmp_custom_fecha) {
                // create object item
                $items = collect($input_form['items'])->map(
                    function ($item) {
                        $nuevoItem = new SaleItem($item);
                        if (array_key_exists('is_customize', $item)) {
                            if ($item['is_customize'] !== null
                                && "open" === $item['is_customize']) {

                                $nuevoItem->c_meta1 = $item['cnombre']; //$item->cnombre;
                                $nuevoItem->c_meta2 = $item['size']; // $item->size;
                            }
                        }
                        return $nuevoItem;
                    }
                );

                if ($tmp_custom_fecha !== NULL) {
                    // Nueva fecha si no es de hoy
                    foreach ($items as $item_) {
                        $item_->created_at = $tmp_custom_fecha . ' ' . date("H:i:s");

                    }
                }

                $sales = self::create($input_form);
                $sales->items()->saveMany($items);

                return $sales;
            }
        );
    }

    public function getInvoiceNoAttribute()
    {
        return $this->invoice_prefix . str_pad($this->attributes['id'], 6, 0, STR_PAD_LEFT);
    }

    public function getSubtotalAttribute()
    {
        $subtotal = $this->items->map(
            function ($item) {
                return $item->price * $item->quantity;
            }
        );

        return $subtotal->sum();
    }

    public function getTaxAttribute()
    {
        return $this->subtotal * ($this->tax_percentage / 100);
    }

}
