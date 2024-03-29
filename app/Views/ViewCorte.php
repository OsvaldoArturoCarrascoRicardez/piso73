<?php

namespace App\Views;

use Illuminate\Database\Eloquent\Model;
use App\Sale;
use Illuminate\Support\Facades\DB;

class ViewCorte extends Model
{

    protected $table = 'products';

    public function scopeGetListaCatProductos($query, $fechaIni, $fechaFin)
    {

        // Prepared Statement
        $ps = "
        SELECT  prod.category_id as categ, items.product_id as id_prod,  prod.name as nom  
        
        FROM  products prod INNER JOIN sale_items items  on items.product_id = prod.id 
        INNER JOIN sales vent on items.sale_id = vent.id  
        
        
        WHERE (items .is_customize =\"original\" or items .is_customize IS NULL) 
        AND vent.status = ? AND items .created_at >= ? and items .created_at < ?  
        
        
        GROUP by items.product_id
        ORDER BY prod.id  ASC
        ";

        $results = DB::select($ps, array(Sale::ACTIVE, $fechaIni, $fechaFin));

        return $results;
    }

    public function scopeGetProductosVendidos($query, $fechaIni, $fechaFin)
    {

        // Prepared Statement
        $ps = 'SELECT p.category_id as id_cat,    p.name as nom_prod, items.product_id, items.price as precio, ' .
            'SUM(items.price * items.quantity) as suma_1, SUM(items.quantity) as cant_vend, items.size, items.is_customize, ' .
            'items.c_meta1, items.c_meta2, items.created_at, items.updated_at ' .
            'FROM sale_items items INNER JOIN products p on p.id = items.product_id ' .
            'INNER JOIN sales vent on items.sale_id = vent.id ' .
            'WHERE (items.is_customize ="original" or items.is_customize IS NULL) ' .
            ' AND vent.status = ? ' .
            ' AND items.created_at >= ? and items.created_at < ?   ' .
            ' GROUP by items.product_id, items.size ORDER BY `items`.`product_id`, id_cat  ASC ';

        $results = DB::select($ps, array(Sale::ACTIVE, $fechaIni, $fechaFin));

        return $results;
    }

    public function scopeGetOtrosProductosVendidos($query, $fechaIni, $fechaFin)
    {

        // Prepared Statement
        $ps = 'SELECT items.product_id, items.price as precio, items.quantity as cant_vend, ' .
            'items.size, items.is_customize, items.c_meta1, items.c_meta2, items.created_at, items.updated_at ' .
            'FROM sale_items items ' .
            'INNER JOIN sales vent on items.sale_id = vent.id ' .
            'WHERE items.is_customize ="open" ' .
            'AND vent.status = ? ' .
            'AND items.created_at >= ?  and items.created_at < ?   ' .
            'ORDER BY `items`.`product_id` ASC';

        $results = DB::select($ps, array(Sale::ACTIVE, $fechaIni, $fechaFin));

        return $results;
    }

}
