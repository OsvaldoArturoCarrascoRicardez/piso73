@extends('layouts.app')

@section('content')
<?php $input['date_range'] = !empty($input['date_range']) ? $input['date_range'] : null; 
$currency =  setting_by_key("currency");
?>



<?php 
    $cant_notas = 0;
    $cant_subtotal = 0.0;
    $cant_efectivo = 0.0;
    $cant_tarjeta = 0.0;
    $cant_puntos = 0.0;

    $cant_descuento = 0.0;
    //-$suma_notas = 0.0;









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



{{--  Temp Turnos  --}}		
@forelse ($ventas2 as $key => $vendedor)
                        	
                        	 
                        	<br />
							
							
<?php 

    $sum_sub_total = 0;
    $sum_cobro_efectivo = 0;
    $sum_cobro_tarjeta = 0;
    $sum_cobro_puntos = 0;

    $suma_descuentos = 0;


?> 

<div class="row">
	<div class="col-lg-8">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h5>@lang('reports.sales') 
				
				<?php
				
				if ($key == 30) {
					echo 'Matutino';
				} 
				if ($key == 31) {
					echo 'Vespertino';
				}
				
				
				
				?>
				
				</h5>
				
			</div>
        <div class="ibox-content">
        
                        <table class="table">
                            <thead>
                           <tr>
                            <th>Nota</th>
                            <!-- th>Fecha</th -->
                            <th>Hora Venta</th>
							<th>SubTotal</th>
                            <th>@lang('reports.discount')</th>
                            <th>Com. Banc</th>
                            <th>Total</th>
                            <th></th>
                            <th></th>
                        </tr>
                            </thead>
                            <tbody>
<?php 
    $total_discount=0;
    $total_amount = 0; 
    $total_com_bank = 0.0;
							
?>

						@forelse ($vendedor as $key2 => $venta)
                            <tr>
                                <td>{{ $venta->id  }}</td>
								
								
								{{-- <td>{{ date('d F Y' , strtotime($sale->created_at)) }}</td> --}}
								{{-- <td>{{ formatFecha_d_Mes_a(strtotime($venta->created_at)) }}</td --}}
                                <td>{{-- formatFechaCortaM3(strtotime($venta->created_at)) --}}   
                                    {{   date("g:i a", strtotime($venta->created_at) )   }}

                                </td>
								<?php //_td>{{ formatFechaDMESA($sale->created_at->timestamp) Fails!!!}}</td ?>
							    <td>{{$currency}} {{ $venta->amount}}</td>
                                <td>{{$currency}} {{ $venta->discount }}</td>
                                <!-- td>{{$currency}} { _{ format_numero($sale->tax_card) } _ }</td -->
                                
                                
                                <td>
                                	@if ($venta->payment_with === 'card')
	                                	{{$currency}} {{ format_numero($venta->tax_card) }}
                                	@endif
                                </td>
                                <!-- td class='alnright'>{{$currency}} {_{ $sale->amount }_}</td -->
                                <td class='alnright'>
                                	{{--  Total + iva si aplica  --}}
                                	@if ($venta->payment_with === 'card')
                                        {{--  Temp  --}}
	                               

                                        {{$currency}} 
                                            @if ($venta->discount > 0)
                                                {{ format_numero( ($venta->amount - $venta->discount)  + $venta->tax_card) }}
                                            @else   
                                                {{ format_numero( $venta->amount) }}
                                            @endif

	                                @else  	
	                                	{{$currency}} {{-- format_numero( $venta->amount) --}}
                                            @if ($venta->discount > 0)
                                                {{ format_numero( $venta->amount - $venta->discount) }}
                                            @else   
                                                {{ format_numero( $venta->amount) }}
                                            @endif
                                	@endif                                
                                </td>
                                <td>
                                    <!-- a href="{{ url('reports/sales/' . $venta->id) }}" class="btn btn-primary btn-xs pull-right">@lang('common.show')</a -->
                                </td>
                                <td>
                                    <a target="_blank" href="{{ url('sales/receipt/' . $venta->id) }}" class="btn btn-primary btn-xs pull-right">@lang('common.show')</a>
                                </td>
                            </tr>
							
							<?php $total_amount += $venta->amount; ?>
							<?php $total_discount += $venta->discount; ?>
							
							<?php $total_com_bank += $venta->tax_card; ?>


                            <?php 
                                 $sum_sub_total += $venta->amount;

                                 if ($venta->payment_with === 'card'){
                                    //$sum_cobro_tarjeta  += $venta->amount;
                                    $sum_cobro_tarjeta  += ($venta->amount - $venta->discount);
                                 }
                                 if ($venta->payment_with === 'cash'){
                                    $sum_cobro_efectivo += ($venta->amount - $venta->discount);
                                 }  
                                 if ($venta->payment_with === 'puntos'){
                                    $sum_cobro_puntos += $venta->amount;
                                 }  
                                 
                                 // $venta->payment_with === 'card')
                                 // 


                            ?>

 
							
                        @empty
                            @include('backend.partials.table-blank-slate', ['colspan' => 5])
                        @endforelse
                    
                    </tbody>
					
						<tr>
                                <!-- th>{_{_count($sales)_}_}</th -->
								<th>{{count($vendedor)}}</th>
                                <td></td>
                                
							    <!-- th>{{$currency}} {{ $total_amount }}</th -->
							    <!-- th>{{$currency}} {{ format_numero($total_amount) }}</th -->
							    <th>{{$currency}} {{ format_numero($sum_sub_total) }}</th>
                                <th>{{$currency}} {{ $total_discount }}</th>
                                <th>{{$currency}} {{ format_numero($total_com_bank) }}</th>
                                <!-- th>{{$currency}} {{ format_numero($total_amount - $total_discount) }}</th -->
                                <th class='alnright'>{{$currency}} {{ format_numero($total_amount - $total_discount + $total_com_bank) }}</th>
                                <th>  </th>
                                <!-- th> Total  : </th -->
                                <!-- th>{{$currency}} {{ format_numero($total_amount - $total_discount) }}</th -->
                               
                            </tr>
							
							
                        </table>
                        <br />
                        <p>
                            Suma Efectivo = {{$currency}} {{ format_numero($sum_cobro_efectivo) }}
                        </p>
                        <p>
                            Suma Tarjeta = {{$currency}} {{ format_numero($sum_cobro_tarjeta) }}
                        </p>

                    </div>
                </div>
            </div>
            
           
        
    </div>


<?php 

// suma Notas
$cant_notas = $cant_notas + count($vendedor);

// suma subtotal
$cant_subtotal = $cant_subtotal + $sum_sub_total;



// suma Efectivo
$cant_efectivo = $cant_efectivo + $sum_cobro_efectivo;
// Suma Tarjeta
$cant_tarjeta = $cant_tarjeta  + $sum_cobro_tarjeta;
// suma Puntos
$cant_puntos = $cant_puntos + $sum_cobro_puntos;


// suma descuentos
//$suma_descuentos = $suma_descuentos + $total_discount;
$cant_descuento = $cant_descuento + $total_discount;


?>    							
{{--  ventas2 --}}
@empty
	@include('backend.partials.table-blank-slate', ['colspan' => 5])
@endforelse

		 

            <div class="row">
                <div class="col-lg-8">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>@lang('reports.sales') Totales</h5>
                        
                    </div>
                    <div class="ibox-content">
                    
                     
                        <table class="table">
                            <thead>
                           <tr>
                            <th>Notas</th>
                            <th>&nbsp;</th>
							<th>@lang('reports.amount')</th>
                            <th>@lang('reports.discount')</th>
                            <th>Com. Banc</th>
                            <th>@lang('reports.total_amount')</th>
                            <th></th>
                        </tr>
                            </thead>
                            <tbody>
							<?php $total_discount=0;$total_amount = 0; 
							
							$total_com_bank = 0.0;
							
							?>
                    @if (!empty($ventas2))
						<?php 
					//---
					//echo var_dump($sales);
					
					?>
                        
                        
                        
                           
							<?php /// old  <tr> ?>
							
							
							<?php $total_amount = 0;//+= $sale->amount; ?>
							
                            <?php $total_discount = 0;// += $sale->discount; ?>
							
							<?php $total_com_bank = 0;//+= $sale->tax_card; ?>
							 
                    @endif
                    </tbody>
					
						<tr>
                                <th>{{ $cant_notas }}</th>
                                <td></td>
                                
							    <!-- th>{{$currency}} {{ $total_amount }}</th -->
							    <th>{{$currency}} {{ format_numero($cant_subtotal) }}</th>
                                <th>{{$currency}} {{ format_numero($cant_descuento) }}</th>
                                <th>{{$currency}} {{ format_numero($total_com_bank) }}</th>
                                <!-- th>{{$currency}} { { format_numero($total_amount - $total_discount) } }</th -->
                                <th class='alnright'>{{$currency}} {{ format_numero($cant_subtotal - $cant_descuento + $total_com_bank) }}</th>
                                <th>  </th>
                                <!-- th> Total  : </th -->
                                <!-- th>  { { format_ numero($total_amount - $total_discount) } }</ th -->
                               
                            </tr>
							
							
                        </table>

                        <br />
                        <p>
                            Suma Efectivo = {{$currency}} {{ format_numero($cant_efectivo) }}
                        </p>
                        <p>
                            Suma Tarjeta = {{$currency}} {{ format_numero($cant_tarjeta) }}
                        </p>

                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
            
        </div>
        
    </div>
</div>

{{--  Sec  gastos  --}}



@php
    $suma_gastos = 0;
    $cant = 0;
@endphp

<div class="ibox-content">

	<h3> Gastos </h3>
	<br />	
           
            <div class="table-responsive">
            <table class="table table-striped">
            <thead>
                <tr>

                    <th>&nbsp; &nbsp; &nbsp;</th>
                    <th>Descripción</th>
                    <th>Cantidad</th>
                    <th>U. Medida</th>
                    <th>Importe </th>
                    
                    <th>H. Registro</th>
                    <!-- th>Elaboró</th -->
                                        

                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($listaGastos)) { 
                	foreach ($listaGastos as $row) { ?>
                    <tr id="fila_{{ $row->id }}">
                    	<td> 
                    		<?php
                    			if(!empty($row->expencePic)){
                   				
                    				?>
                    				<span class="glyphicon glyphicon-picture" aria-hidden="true"></span>
                    				<?php
                    			} 
                    		?>
                    	
                    	</td>
                    	<td> {{ $row->description }} </td>
                        <td  style="text-align: right;" >  {{ format_numero( $row->quantity ) }} </td>
                        <td> {{ $row->nombre }} </td>
                         
                        <td  style="text-align: right;" > $ {{ format_numero( $row->expense_amount ) }} </td>
                         <!--  td> { { $row->created_at } } </td -->
                         <td> {{   date("g:i a", strtotime($row->created_at) )   }} </td>
                         
                         <td> 
                            
                        </td>
                    </tr>

                    @php
						    
                        $suma_gastos += $row->expense_amount;
                        $cant++;
					@endphp
                    
                <?php } 
                ?>
                	<tr id="fila_{{ $row->id }}">
                    	<td> 
                    		
                    	</td>
                    	<td> {{ $cant }} </td>

                        <td> </td>
                         <td>  </td>
                         
                         <td  style="text-align: right;" > <b>  $ {{ format_numero( $suma_gastos ) }} </b> </td>
                         <td></td>
                         <td></td>
                    </tr>
                
                
                <?php
                } else {  ?>
                <tr>
                    <td colspan="7">@lang('common.no_record_found') </td> 
                </tr>
<?php } ?>

            </tbody>
        </table>


		<!--     {  ! ! $expenses->render() ! ! }   --
    </div>
    <!-- /.table-responsive -->
</div>
</div>

{{--    Sec  gastos  --}}








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
    $('#porfecha').attr('value', '{{ $cFecha }}');
    
    
    
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
