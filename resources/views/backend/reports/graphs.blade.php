@extends('layouts.app')

@section('content')
<?php $currency =  setting_by_key("currency"); 
$Days = array("Domingo","Lunes","Martes","Miércoles",
                  "Jueves","Viernes","Sábado");
$Months =array(1=>"Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio",
             "Agosto","Septiembre","Octubre","Noviembre","Diciembre");



?>

<div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
			 <div class="col-lg-12 col-md-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                       
                    </div>

            <div class="panel panel-white">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="visitors-chart">
                            <div class="panel-heading pink text-right">
                                <a class="graph_by" data-id="7">@lang('dashboard.7_days')</a> &nbsp; | &nbsp; 
                                 <a class="graph_by" data-id="30">@lang('dashboard.30_days')</a>  &nbsp;| &nbsp;
                                <a class="graph_by" data-id="365">@lang('dashboard.12_month')</a>
                               
                            </div>
                            <div class="panel-body">
                                <div id="flotchart1" style="display: block; width: 100%; max-width: 1100px; height: 415px; margin: 0 auto"></div>
                                <div id="flotchart2" style="display: none; width: 100%; max-width: 1100px; height: 415px; margin: 0 auto"></div>
                                <div id="flotchart3" style="display: none; width: 100%; max-width: 1100px; height: 415px; margin: 0 auto"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        </div>
		
		
		<script src="//code.highcharts.com/highcharts.js"></script>
	<script src="//code.highcharts.com/modules/exporting.js"></script>

		
		<script> 
		// $(function() {
   // var barOptions = {
        // series: {
            // lines: {
                // show: true,
                // lineWidth: 2,
                // fill: true,
                // fillColor: {
                    // colors: [{
                        // opacity: 0.0
                    // }, {
                        // opacity: 0.0
                    // }]
                // }
            // }
        // },
        // xaxis: {
            // tickDecimals: 0
        // },
        // colors: ["#1ab394"],
        // grid: {
            // color: "#999999",
            // hoverable: true,
            // clickable: true,
            // tickColor: "#D4D4D4",
            // borderWidth:0
        // },
        // legend: {
            // show: false
        // },
        // tooltip: true,
        // tooltipOpts: {
            // content: "x: %x, y: %y"
        // }
    // };
    // var barData = {
        // label: "bar",
        // data: [
            // [1, 34],
            // [2, 25],
            // [3, 19],
            // [4, 34],
            // [5, 32],
            // [6, 44]
        // ]
    // };
    // $.plot($("#flot-line-chart"), [barData], barOptions);
    

// });


// //Flot Pie Chart
// $(function() {
// var myArray = ['#617EBB', '#48C2A9' , '#2c2ccc' , '#2ccc47','#48C2A9','#e5dd42'];   
    // var data = [
	// <?php if(!empty($sales_by_product)) { foreach($sales_by_product as $sale) { ?>
            
			// {
        // label: "<?php echo $sale->product_name; ?>",
        // data: <?php echo $sale->total_sales; ?>,
        // color: myArray[Math.floor(Math.random() * myArray.length)],
    // },<?php } } ?> ];

    // var plotObj = $.plot($("#flot-pie-chart"), data, {
        // series: {
            // pie: {
                // show: true
            // }
        // },
        // grid: {
            // hoverable: true
        // },
        // tooltip: true,
        // tooltipOpts: {
            // content: "%p.0%, %s", // show percentages, rounding to 2 decimal places
            // shifts: {
                // x: 20,
                // y: 0
            // },
            // defaultTheme: false
        // }
    // });

// });

</script>

<?php if(count($get_orders_365) > 0) { ?>



<?php 
// data para filas siguientes
$year_name = array(date('F', strtotime(' 0 month')), date('F', strtotime(' -1 month')), date('F', strtotime(' -2 month')), date('F', strtotime(' -3 month')), date('F', strtotime(' -4 month')), date('F', strtotime(' -5 month')), date('F', strtotime(' -6 month')), date('F', strtotime(' -7 month')), date('F', strtotime(' -8 month')), date('F', strtotime(' -9 month')), date('F', strtotime(' -10 month')), date('F', strtotime(' -11 month')));

?>
 
<script type="text/javascript">
    $('.graph_by').on('click', function () {
        var id = $(this).attr("data-id");
        if (id == 7) {
            $('#flotchart1').show();
            $('#flotchart2').hide();
            $('#flotchart3').hide();
        }
        if (id == 30) {
            $('#flotchart1').hide();
            $('#flotchart2').show();
            $('#flotchart3').hide();
        }
        if (id == 365) {
            $('#flotchart1').hide();
            $('#flotchart2').hide();
            $('#flotchart3').show();
        }
    });
            $('.graph_by').eq(0).addClass('selected');
        $(document).on('click','.graph_by',function(){
            $(this).addClass('selected').siblings().removeClass('selected');
        });
		
    $(function () {
        $('#flotchart3').highcharts({
            chart: {
                type: 'line'
            },
            title: {
                text: "@lang('graph.last_12_month')"
            },
            subtitle: {
                text: "@lang('graph.online_and_pos')"
            },
            credits: {
                enabled: false
            },
            xAxis: {
                categories: [
				'<?php echo implode("', '", getListMonthYearDesc() );   /*Los Días son con ''*/ ?>'
                 ] /*'<?php echo implode("', '", getListMonthYearDesc() );   /*Los Días son con ''*/ ?>' */
            }, /*   */
            yAxis: {
                min: 0,
                title: {
                    text: 'Ventas'    /*text: 'Sale'*/
                }
            },
            plotOptions: {
                line: {
                    dataLabels: {
                        enabled: true
                    },
                    enableMouseTracking: false
                }
            },
            series: [
			{
                    color: '#22BAA0',
                    name: 'Otros Pedidos',   /*  Online Orders  Otros Pedidos*/
                    data: [
                        <?php
                        $array_year1 = [];
                        foreach ($get_orders_365_online as $keym => $valuem) {
                            $array_year1[$valuem->mon] = $valuem->amount;
                        }
                        foreach ($year_name as $key => $value) {
                            if (array_key_exists($value, $array_year1)) {
                                echo round($array_year1[$value], 2) . ', ';
                            } else {
                                echo '0.00, ';
                            }
                        }
                        ?>
                    ]
                },
				{
                    color: '#7a6fbe',
                    name: 'POS',
                    data: [
                    <?php
                    foreach ($get_orders_365 as $keym => $valuem) {
                        $array_year2[$valuem->mon] = $valuem->amount;
                    }

                    foreach ($year_name as $key => $value) {
                        if (array_key_exists($value, $array_year2)) {
                            echo round($array_year2[$value], 2) . ', ';
                        } else {
                            echo '0.00, ';
                        }
                    }
                    ?>
                                        ]
                }]
        });


{{--  February 2022, We move the process here and the ouput data in his t}	 place --}}
{{--  Sales last 30 days  --}}
<?php
// Datos Categorias
$listaDiasAnte = array();
for ($i = 0; $i <= 30; $i++) {
    //echo "'" . date('d', strtotime('- ' . $i . ' day')) . "', ";
	$listaDiasAnte[] = date('d', strtotime('- ' . $i . ' day')); 
}

//--- Meses Otros
$array_mon1 = [];
foreach ($transections_30_days_online as $key => $value) {
    $array_mon1[$value->dat] = $value->amount;
}

$listaVenta30DiasOtros = array ();
for ($i = 0; $i < 30; $i++) {
    $date = date('d', strtotime('- ' . $i . ' day'));
    if (array_key_exists($date, $array_mon1)) {
		$listaVenta30DiasOtros [] = round($array_mon1[$date], 2);
        //_echo round($array_mon1[$date], 2) . ', ';
    } else {
		$listaVenta30DiasOtros [] = round(0.00, 2);
        //_echo '0.00, ';
    }
}


//--  Ventas POS Mensuales
$array_mon2  = array();
foreach ($transections_30_days as $key => $value) {
    $array_mon2[$value->dat] = $value->amount;
}

$listaVenta30Dias = [];
for ($i = 0; $i < 30; $i++) {
    $date2 = date('d', strtotime('- ' . $i . ' day'));
    if (array_key_exists($date2, $array_mon2)) {
        ///echo round($array_mon2[$date2], 2) . ', ';
		$listaVenta30Dias[] = round($array_mon2[$date2], 2);
    } else {
        //echo '0.00, ';
		$listaVenta30Dias[] = round(0.00, 2);
    }
}




echo "\n";
echo "/*";

//echo var_dump($transections_30_days);
//echo json_encode($listaDiasAnte, JSON_PRETTY_PRINT);
echo json_encode($transections_30_days, JSON_PRETTY_PRINT);
echo "\n";
echo json_encode($array_mon2, JSON_PRETTY_PRINT);


echo "*/";


?>

        /*last_30_days*/
		$('#flotchart2').highcharts({
            chart: {
                type: 'line'
            },
            title: {
                text: "@lang('graph.last_30_days')"
            },
            subtitle: {
                text: "@lang('graph.online_and_pos')"
            },
            credits: {
                enabled: false
            },
            xAxis: {
                categories: [
					'<?php  echo implode("', '", $listaDiasAnte );   /*Los Días son con ''*/ ?>'
                ]
            },
            yAxis: {
                min: 0,
                title: {
                    text: '' /*Sale   Ventas */
                }
            },
            plotOptions: {
                line: {
                    dataLabels: {
                        enabled: true
                    },
                    enableMouseTracking: false
                }
            },
            series: [
			{
                    color: '#22BAA0',
                    name: 'Otros Pedidos',  /* Online Orders  || Otros Pedidos*/
                    data: [
						<?php  echo implode(", ", $listaVenta30DiasOtros ); ?>  
                    ]  /* xyz '<?php  echo implode(", ", $listaVenta30DiasOtros ); ?>' */
                }, 
				{
                    color: '#7a6fbe',
                    name: 'POS',
                    data: [
						<?php  echo implode(", ", $listaVenta30Dias ); ?>  
                    ]
                }]
        });

        
		{{--  February 2022, We move the process here and the ouput data in his respective place --}}
		{{--  Sales last 7 days  --}}

<?php
$day_name = array(date('l', strtotime(' 0 day')), date('l', strtotime(' -1 day')), date('l', strtotime(' -2 day')), date('l', strtotime(' -3 day')), date('l', strtotime(' -4 day')), date('l', strtotime(' -5 day')), date('l', strtotime(' -6 day')));

/*echo '"' . $Days[date('w', strtotime(' 0 day'))] . '", ';
echo '"' . $Days[date('w', strtotime(' -1 day'))] . '", ';
echo '"' . $Days[date('w', strtotime(' -2 day'))] . '", ';
echo '"' . $Days[date('w', strtotime(' -3 day'))] . '", ';
echo '"' . $Days[date('w', strtotime(' -4 day'))] . '", ';
echo '"' . $Days[date('w', strtotime(' -5 day'))] . '", ';
echo '"' . $Days[date('w', strtotime(' -6 day'))] . '", ';
*/

?>
<?php
// pedidos en linea

                            $array_day1 = array();
                            foreach ($transections_7_days_online as $keym => $valuem)
                            {
                                $array_day1[$valuem->day] = $valuem->amount;
                            }
													

                            $tmpArraySalidaOtros = array();
							foreach ($day_name as $key => $value)
                            {
                                if (array_key_exists($value, $array_day1))
                                {
                                    //echo round($array_day1[$value], 2) . ', ';
									$tmpArraySalidaOtros[] = round($array_day1[$value], 2);
                                }
                                else
                                {
                                    //echo '0.00, ';
									$tmpArraySalidaOtros[] = round(0, 2);
                                }
                            }
							
							//$__temp_string22 = implode(", ", $tmpArraySalida);	
                            ?>

<?php
// Pedidos  POS

	$array_day2 = array();
	foreach ($transections_7_days as $key => $value)
	{
		$array_day2[$value->day] = $value->amount;
	}
	
	$tmpArraySalidaPOS = array();

	foreach ($day_name as $key => $value)
	{
		if (array_key_exists($value, $array_day2))
		{
			//echo round($array_day2[$value], 2) . ', ';
			$tmpArraySalidaPOS[] = round($array_day2[$value], 2);
		}
		else
		{
			//echo '0.00, ';
			$tmpArraySalidaPOS[] = round(0.00, 2);
		}
	}
	
?>
 
		/*last_7_days*/
		$('#flotchart1').highcharts({
            chart: {
                type: 'line'
            },
            title: {
                text: "@lang('graph.last_7_days')"
            },
            subtitle: {
                text: "@lang('graph.online_and_pos')"
            },
            credits: {
                enabled: false
            },
            xAxis: {
                categories: [
'<?php  echo implode("', '", getListNameDaysDesc() );   /*Los Días son con ''*/ ?>'
                ]
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Ingresos'/*'	'   Vertical__*/
                }
            },
            plotOptions: {
                line: {
                    dataLabels: {
                        enabled: true
                    },
                    enableMouseTracking: false
                }
            },
            series: [
			{
                    color: '#22BAA0',
                    name: 'Otros pedidos', /*Online Orders*/
                    data: [
						{{ implode(", ", $tmpArraySalidaOtros) }}
                    ]},
                    {
                    color: '#7a6fbe',
                    name: 'POS',
                    data: [
						{{  implode(", ", $tmpArraySalidaPOS)   }}
                    ] /* {{  implode(", ", $tmpArraySalidaPOS)   }} */
                }]
            });
    });
</script>

		<?php } ?>
		
		
@endsection
