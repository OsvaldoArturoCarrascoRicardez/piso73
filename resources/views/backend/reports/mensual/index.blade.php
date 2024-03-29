@extends('layouts.app')

@section('content')
<?php $input['date_range'] = !empty($input['date_range']) ? $input['date_range'] : null; 
$currency =  setting_by_key("currency");
?>
<?php


$conteo_ventas = 0;
$conteo_egresos = 0;
$conteo_egnral = 0;




$tot_gastos_gnral = 0.0;
$tot_egresos = 0.0;
$tot_ventas = 0.0;












?>









<link href="{{url('assets/css/plugins/datapicker/datepicker3.css')}}" rel="stylesheet">

 <link href="{{url('assets/css/plugins/daterangepicker/daterangepicker-bs3.css')}}" rel="stylesheet">

<div class="wrapper wrapper-content animated fadeInRight">



		<div class="ibox-content m-b-sm border-bottom">
                <div class="row">
                     
                    <form action="{{ route('reportes.diario') }}" class="form-horizontal"  
                    	method="GET" enctype='multipart/form-data' name="formProd"  id="formProd"  >
					<div class="col-sm-2">
                        <div class="form-group">
                            <label class="col-form-label" for="price">Fecha</label>
                            <input type="date" id="porfecha" name="porfecha" placeholder="" class="form-control" />
							
                        </div>
                    </div> 
                    </form>
					<!--  form -->
					
					<div class="col-sm-2">
                        <div class="form-group">
                            <h3>   </h3>
							
                        </div>
                    </div>
					<div class="col-sm-2">
                        <div class="form-group">
                            <h3>  Ventas y Gastos </h3>
							
                        </div>
                    </div> 
					
							
                </div>

            </div>


 

		 

            <div class="row">
                <div class=""  {{--  col-lg-9  --}}>
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>@lang('reports.sales') Totales</h5>
                        
                    </div>
                    <div class="ibox-content">
<!--   -->


<div class="container">
  <h2></h2>
  <p></p>
  <ul class="nav nav-pills">
    <li class="active"><a data-toggle="pill" href="#home">Home</a></li>
    <li><a data-toggle="pill" href="#menu1">Total Ventas</a></li>
    <li><a data-toggle="pill" href="#menu2">Egresos</a></li>
    <li><a data-toggle="pill" href="#menu3">Gastos En General</a></li>
    <li><a data-toggle="pill" href="#menu4">Diferencia</a></li>

  </ul>
  
  <div class="tab-content">
    <div id="home" class="tab-pane fade in active">
      <h3>Inicio</h3>
      <p></p>
    </div>
    
    
    <div id="menu1" class="tab-pane fade">
      <h3>Detalle</h3>
       

      <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>&nbsp</th>
                            <th>Fecha</th>
                            <th>Ventas Efe</th>
                            <th>Suma Efe</th>
                            <th>Ventas <br/> Tarjeta</th>
                            <th>Suma Tarjeta</th>
							<th>N. Ventas</th>
                            <th>Total Venta <br/> del día</th>
							<th>&nbsp</th>
                        </tr>
                    </thead>
                    <tbody>

                    @if (!empty($totVentas))

@forelse ($totVentas as $key => $venta)
<tr>
    <td>&nbsp</td>
    <td>{{  $venta->dia }}</td>
    <td>{{  $venta->con_ventas_efe }}</td>
    <td>{{  $venta->sum_ventas_efe }}</td>
    <td>{{  $venta->con_ventas_tarj }}</td>
    <td>{{  $venta->sum_ventas_tarj }}</td>
    <td>{{  $venta->conteo }}</td>
    <td>{{  $venta->suma }}</td>
    <td>&nbsp</td>
</tr>




<?php 
    $conteo_ventas = $conteo_ventas + $venta->conteo;
    $tot_ventas = $tot_ventas + $venta->suma;
?>
@empty
    @include('backend.partials.table-blank-slate', ['colspan' => 5])
@endforelse





                    @endif {{-- totVentas  --}}




</tbody>
<tfoot>
    <tr>
        <th>&nbsp</th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th>{{  $conteo_ventas }}</th>
        <th>
            {{$currency}} {{ format_numero( $tot_ventas ) }}
        </th>
        <td></td>
        
    </tr>
</tfoot>
</table{{--  Tabla end --}}>




    </div{{-- menu1 --}}>


    <div id="menu2" class="tab-pane fade">
      <h3>Detalle</h3>
      

      <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>&nbsp</th>
                            <th>Fecha</th>
                            <th>N. Compras</th>
                            <th>Suma Compras</th>
							<th>&nbsp</th>
                        </tr>
                    </thead>
                    <tbody>

                    @if (!empty($gastos_dos))

@forelse ($gastos_dos as $key => $egreso)
<tr>
    <td>&nbsp</td>
    <td>{{  $egreso->dia }}</td> 
    <td>{{  $egreso->conteo }}</td> 
    <td>{{  $egreso->suma }}</td> 

    <td>&nbsp</td>
</tr>




<?php 
    $conteo_egresos = $conteo_egresos + $egreso->conteo;
    $tot_egresos = $tot_egresos + $egreso->suma ;
?>
@empty
    @include('backend.partials.table-blank-slate', ['colspan' => 5])
@endforelse





                    @endif {{-- totVentas  --}}




</tbody>
<tfoot>
    <tr>
        <th>&nbsp</th>
        
        <th></th>
        <th>{{  $tot_egresos }}</th>
        <th>
            {{$currency}} {{ format_numero( $tot_egresos ) }}
        </th>
        <td></td>
        
    </tr>
</tfoot>
</table{{--  Tabla end --}}>


 

    </div{{--  menu2  --}}>


    <div id="menu3" class="tab-pane fade">
      <h3>Detalle</h3> 



      <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>&nbsp</th>
                            <th>Fecha</th>
                            <th>N. Compras</th>
                            <th>Suma Compras</th>
							<th>&nbsp</th>
                        </tr>
                    </thead>
                    <tbody>

                    @if (!empty($gastos_uno))

@forelse ($gastos_uno as $key => $gasto)
<tr>
    <td>&nbsp</td>
    <td>{{  $gasto->dia }}</td> 
    <td>{{  $gasto->conteo }}</td> 
    <td>{{  $gasto->suma }}</td> 

    <td>&nbsp</td>
</tr>




<?php 
    $conteo_egnral = $conteo_egnral + $gasto->conteo;
    $tot_gastos_gnral = $tot_gastos_gnral + $gasto->suma ;
?>
@empty
    @include('backend.partials.table-blank-slate', ['colspan' => 5])
@endforelse





                    @endif {{-- totVentas  --}}




</tbody>
<tfoot>
    <tr>
        <th>&nbsp</th>
        
        <th></th>
        <th>{{  $conteo_egnral }}</th>
        <th>
            {{$currency}} {{ format_numero( $tot_gastos_gnral ) }}
        </th>
        <td></td>
        
    </tr>
</tfoot>
</table{{--  Tabla end --}}>

      
 


      
    </div{{--  menu3 --}}>



    <div id="menu4" class="tab-pane fade">
       

    <p>{{ format_numero( $tot_ventas ) }}</p>
    <p>{{ format_numero( $tot_egresos ) }}</p>
    <p>{{ format_numero( $tot_gastos_gnral ) }}</p>
    <p>&nbsp;</p>
    <p>{{ format_numero( $tot_ventas - ($tot_egresos + $tot_gastos_gnral) ) }}</p>








      </div{{--  menu4 --}}>

 

  </div{{--  tab-content --}}>
</div>




                    
                      
<!--   -->
                    </div{{-- ibox-content  --}}>
                </div>
            </div>
            
            <div class="col-md-4">
            
        </div>
        
    </div>
</div>




<!--  
    $data['gastos_uno'] = $lsGastos1;
        $data['gastos_dos'] = $lsGastos_dos;
        $data['gastos_dos'] = $lsVentas;


{{ print_r(json_encode($gastos_uno, JSON_PRETTY_PRINT)) }}


{{ print_r (json_encode($gastos_dos, JSON_PRETTY_PRINT))  }}



{{ print_r (json_encode($totVentas, JSON_PRETTY_PRINT) )}}




-->







<script> 
	$("body").on("click" , "#SubmitSearch" , function() {
		$("#pdf").val("");
		$("#formSubmit").submit();
	});
	
	$("body").on("click" , "#DownloadPDF" , function() {
		$("#pdf").val("yes");
		$("#formSubmit").submit();
	});
</script>


	<!-- @lang('reports.date_range') use moment.js same as full calendar plugin -->
	
	 <!-- Data picker -->
   <script src="{{url('assets/js/plugins/datapicker/bootstrap-datepicker.js')}}"></script>

    <script src="{{url('assets/js/plugins/fullcalendar/moment.min.js')}}"></script>
    <script src="{{url('assets/js/plugins/daterangepicker/daterangepicker.js')}}"></script>
	
			 
			
{{--  comentario  --}}



<script>

$(function(){
    var dtToday = new Date();
 
    var month = dtToday.getMonth() + 1;
    var day = dtToday.getDate();
    var year = dtToday.getFullYear();
    if(month < 10)
        month = '0' + month.toString();
    if(day < 10)
     day = '0' + day.toString();
    var maxDate = year + '-' + month + '-' + day;
    $('#porfecha').attr('max', maxDate);
    
     
    
    //$('#inputdate').attr('value', maxDate);
    $('#porfecha').attr('value', '{{ $fecha1 }}');
    
    
    
});
	

var _url = "{{ route('reportes.porProductos') }}";


// __ $('#inputdate').attr('value', maxDate);
$("#porfecha").on('change ', function () {
	
	//$("#formProd").submit();
	
//	var _action = _url  +  '/' + $('#inputdate').attr('value');
	//var _action = _url  +  '/' + $('#inputdate').val();
	
	// console.log(_action);
	
	
	//$('#formProd').attr('action', _action).submit();
	
	// viernes_
	$("#formProd").submit();

    
});	
	

</script>


			
			
			
<style>
    .alnright { text-align: right; }
</style>


@endsection
