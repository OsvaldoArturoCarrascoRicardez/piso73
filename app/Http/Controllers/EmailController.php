<?php

namespace App\Http\Controllers;

use App\Mail\ReportsEmail;
use Illuminate\Support\Facades\DB;
use Mail;
use App\User;
use Response;
use PDF;

class EmailController extends Controller
{
    public function index()
    {

        $query = DB::table("sales");
        $query->whereDay('sales.created_at', '=', date('d'));

        $sales = $query->select("*", DB::raw('SUM(amount) as total_amount'))->groupBy("cashier_id")->get();
        foreach ($sales as $sale) {
            $sale->user = User::find($sale->cashier_id);
        }

        $data['sales'] = $sales;

        if (!empty($_GET['pdf'])) {
            $pdf = $_GET['pdf'];
        }
        if ($pdf == "yes") {
            $data['title'] = "Staff Sold Report";
            //return view("backend.reports.staff_sold_pdf" , $data);
            $pdf = PDF::loadView('backend.reports.staff_sold_pdf', $data);
            return $pdf->download('staff_sold.pdf');
        }


        $headers = array(
            'Content-Type' => 'application/vnd.ms-excel; charset=utf-8',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Content-Disposition' => 'attachment; filename=abc.csv',
            'Expires' => '0',
            'Pragma' => 'public',
        );
        $name = "staff_sold";
        $filename = "staff_sold.csv";

        $handle = fopen($filename, 'w');
        if (count($sales) > 0) {
            fputcsv(
                $handle, [
                    "#", "Name", "Amount"
                ]
            );

            foreach ($sales as $key => $sale) {
                fputcsv(
                    $handle, [
                        $key + 1,
                        isset($sale->user->name) ? $sale->user->name : "Unknown",
                        "$" . $sale->total_amount,
                    ]
                );
            }
            fclose($handle);

            //return Response::download($filename, "staff_sold.csv", $headers);

        }
        $content = array(
            "subject" => "Daily Staff Sales Report ",
            "message" => "",
            "sales" => $sales,
            "file" => $filename,
        );
        Mail::to("arfan67@gmail.com")->send(new ReportsEmail($content));
    }
}
