@extends('layouts.app')

@section('content')
<?php $input['date_range'] = !empty($input['date_range']) ? $input['date_range'] : null; 
$currency =  setting_by_key("currency");
?>
<link href="{{url('assets/css/plugins/datapicker/datepicker3.css')}}" rel="stylesheet">

 <link href="{{url('assets/css/plugins/daterangepicker/daterangepicker-bs3.css')}}" rel="stylesheet">

<div class="wrapper wrapper-content animated fadeInRight">



		<div class="ibox-content m-b-sm border-bottom">
                <div class="row">
                     
                    <form action="{{ route('reportes.cortes') }}" class="form-horizontal"  
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
                            <h3>  Corte Diario </h3>
							
                        </div>
                    </div> 
					
							
                </div>

            </div>



<style>
</style>





{{--  <!-- Sec Ventas por Producto -->  --}}
@php
    $suma_total_venta_product = 0.0;
    $suma_total_venta_open_product = 0.0;
    $value_cant_x_precio = 0.0;
    $open_cant_x_precio = 0.0;

    $cant_prod = 0;
    $cant_open_prod = 0;


    $tmpCantV = 0;
    $tmpTotV = 0;




@endphp

<div class="row">
    <div class="col-lg-9">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>Productos Vendidos</h5>
            </div>
            <div class="ibox-content">


                <table class="table">
                    <!--  thead>
                        <tr>
                            <th>N. Producto</th>
                            <th>Nombre</th>
                            <th>Presentación</th>
                            <th>Cantidad Vendida</th>
                            <th>Precio de Venta</th>
                            <th>&nbsp;</th>
                            <th>Total</th>
                            <th></th>
                        </tr>
                    </thead -->
                    <!-- tbody -->
                    <tbody>

{{-- Recorrer las categorias --}}
@forelse ($listaCategorias as  $key_cat => $categoria)

<!-- tr>
   <th colspan='8' style='text-align: center;'>**** {{ $categoria->name }} **** </th>     
</tr -->    

<tr>
   <th colspan='8' >
   <div style="width: 100%; height: 16px; border-bottom: 1px solid black; text-align: center">
        <span style="font-size: 18px; background-color: #FFFFFF; padding: 0 10px;">
        {{ $categoria->name }} <!--Padding is optional-->
        </span>
   </div> 
    </th>     
</tr>    

<?php 

// suma de articulos vendidos por categoria
$suma_avxc = 0; 
//  total vendido por categoria
$suma_tvxc = 0;

?>

<tr>

<td colspan='8'>&nbsp;


<?php 

    /// echo json_encode( $listaPorItems[27]   );

    $temp_array = [];
    if ( !empty($listaPorItems[ $categoria->id ] )) {
        //done__echo json_encode( $listaPorItems[$categoria->id]   );
        $temp_array = $listaPorItems[$categoria->id];
    }

?>
    
    {{--  Init iteración  $temp_array  --}}
    @if (!empty($temp_array) )

    @forelse ($temp_array as $llave => $value )

        <!-- 
        Clave prod ->{ { json_encode($llave) }} c
        <br />
        Nombre:{ { json_encode($value['nombre']) }}
        <br />
        { { json_encode($value['data']) }} -- ok  -->
<?php
    $tempClave =  $llave;
    $tempNombre =  $value['nombre'];

?>        


<?php 

// preliminar

$value_cant_x_precio = 0;
//$suma_total_venta_product = 0;
//$cant_prod = 0;
$tmpCantV = 0;

$tmpTotV = 0;

$filas_itemss = 0; 
 

// extras
$row_span = false; 
 


            ?> 



        <table class="table">
            <thead>
                        <tr>
                            <th>Clave Producto</th>
                            <th>Nombre</th>
                            <th>Presentación</th>
                            <th>Cantidad</th>
                            <th>Precio de Venta</th>
                            <th>&nbsp;</th>
                            <th>Total</th>
                            <th></th>
                        </tr>
                    </thead -->
                    <tbody -->

            <tbody>

            <?php 
            
            $filas_itemss = count($value['data']) ; 
            $row_span = true;  


            
            ?>
 

            {{--  iteracion $itemVend    --}}
            @forelse ($value['data'] as $indice => $itemVend)

            <?php 
                
                $value_cant_x_precio = $itemVend->cant_vend * $itemVend->precio;
                $suma_total_venta_product += $value_cant_x_precio;
                $cant_prod += $itemVend->cant_vend;
                $tmpCantV += $itemVend->cant_vend;

                $tmpTotV += $value_cant_x_precio; 



                //$suma_avxc += $tmpCantV;
                // use_  $cant_prod += $itemVend->cant_vend;
                $suma_avxc += $itemVend->cant_vend;

                // $suma_tvxc  += $tmpTotV;
                //use $tmpTotV += $value_cant_x_precio; 
                $suma_tvxc += $value_cant_x_precio; 



            ?>            
 
                <!-- br />
                <span class='label'>
                    {  { json_encode($itemVend) }}  elementos ontenidos ok, 
                </span -->
 
                <tr>
             
                        <!-- td> { { $tempClave }}</td>
                        <td> { { $tempNombre }}</td -->

                        
                        @if($row_span)
                            <td  rowspan=" {{$filas_itemss + 1 }}" > {{ $tempClave }}  </td>
                            <td  rowspan=" {{$filas_itemss + 1 }}" > {{ $tempNombre }}</td>
                            <?php   $row_span = false;  ?>
                        @endif


 
                    <td>{{ $itemVend->size }}</td>
                    <td class='alcentro'>{{ $itemVend->cant_vend }}</td>
                    <td class='alcentro'> $ {{ format_numero($itemVend->precio) }}</td>
                    <td></td>
                    <td class='alcentro'> $ {{ format_numero($value_cant_x_precio) }} </td>
                    <td></td> 
                </tr> 
            @empty


            @endforelse

            <tr>
                <!-- td colspan=2></td  corres ponde al rowspan   -->
    
                <th>   Cantidad Vendida</th>
                <td class='alcentro'> {{     $tmpCantV }}</td>
                <th>   Venta Total</th>
                <td></td>
                <th> $ {{ format_numero($tmpTotV) }} </th>
                <td></td>

            </tr>  

 
            {{--  End iteracion $itemVend    --}}

            </tbody>
        </table> 
        
        <hr  />

    @empty    
    <!--  p></p -->

    @endforelse

    @else
        <!-- p>sin Venta</p -->
    @endif
    
    {{--  Init iteración  $temp_array  --}}
 
</td>     
<!-- end big td>  -->
 
</tr>
<!--  End big row -->

<!--tr style="border-bottom: 1px solid #000;">
   <th colspan='8'>&nbsp;</th>     
</tr -->    

<tr>
            
<td> &nbsp; </td>
<td> &nbsp; </td>

<td>&nbsp; </td>
            <td>Cantidad Vendida</td>
            <th class='alcentro'>{{ $suma_avxc }}</th>
            <td>Total venta</td>
            <td></td>
            <th class='alcentro'> $ {{ format_numero( $suma_tvxc  ) }} </th>
           
</tr>   

<tr style="border-bottom: 1px solid #000;">
   <th colspan='8'>&nbsp;</th>     
</tr>    




 

@empty
-- No hay ventas de esta categoria -- 
<?php 
$suma_avxc = 0; 
//  total vendido por categoria
$suma_tvxc = 0;
?>


@endforelse
{{-- End Recorrer las categorias --}}


  
 

        <tr>
            <td colspan=2></td>
  
            <!-- th>Productos Vendidos</th -->
            <th>Alimentos, Bebidas y Productos Vendidos </th>
            

            <td class='alcentro'> {{     $cant_prod }}</td>
            <th> Venta Total</th>
            <td></td>
            <th> $ {{ format_numero($suma_total_venta_product) }} </th>
            <td></td>

        </tr>  
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- div class="panel panel-default" -->
    </div>
</div>
{{--  <!-- Sec Ventas por Producto  End -->  --}}

<?php /*

echo   ya se sabe eñ recorrido de la matriz
{{ json_encode( $listaPorItems )  }}

*/
?>


{{--  <!-- Sec Ventas por Otros Productos  -->  --}}

<div class="row">
    <div class="col-lg-9">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>Venta de Ingredientes Extras y Otros productos</h5>
            </div>
            <div class="ibox-content">


                <table class="table">
                    <thead>
                        <tr>
                            <th> <!--N. Producto --> </th>
                            <th>Nombre</th>
                            <th>Presentación</th>
                            <th>Cantidad Vendida</th>
                            <th>Precio de Venta</th>
                            <th>&nbsp;</th>
                            <th>Total</th>
                            <th></th>
                        </tr>
                    </thead>
                    <!-- tbody -->
                    <tbody>
 
        @php 
            $filas_items = count($listaOtrosItems);
        @endphp
        
        {{--   $listaOtrosItems  --}}
        {{--   ($item_value as $key4 => $datos --}}
        @forelse ($listaOtrosItems as $key6 => $item)
<!--        { { json_encode($datos) }}

        { { $datos->nom_prod }}
        { { $datos->size }}
-->
        
<?php 
                
                $value_cant_x_precio = $item->cant_vend * $item->precio;
                $suma_total_venta_open_product += $value_cant_x_precio;

                $cant_open_prod += $item->cant_vend;
            ?>
        <tr>
           
    


            <td>  <!--   {{$item->product_id }}   --> </td>
            <td>{{$item->c_meta1 }}</td>
            <td>{{$item->c_meta2 }}</td>
            <td class='alcentro'>{{$item->cant_vend }}</td>
            <td class='alcentro'>$ {{ format_numero($item->precio) }}</td>
            <td></td>
            <td class='alcentro'>$ {{ format_numero($value_cant_x_precio) }} </td>
            <td></td>
        </tr>


        @empty
            <!--   sin datos  -->
        @endforelse
 

        <tr>
            <td colspan=2></td>
  
            <th>Productos Vendidos</th>
            <td class='alcentro'> {{     $cant_open_prod }}</td>
            <th> Venta Total</th>
            <td></td>
            <th> $ {{ format_numero($suma_total_venta_open_product) }} </th>
            <td></td>

        </tr>  
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- div class="panel panel-default" -->
    </div>
</div>
{{--  <!-- Sec Ventas por Otros Productos End -->  --}}







<!-- -->
<!-- -->

<div class="row">
            <div class="col-lg-8">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>@lang('reports.sales') Totales</h5>
                        
                    </div>
                    <div class="ibox-content">

        <div class="table-responsive">
            <table class="table table-striped">
            <thead>
                <tr>

                    <th>&nbsp; &nbsp; &nbsp;</th>
                    <th>&nbsp; </th>
                    <th>&nbsp; </th>
                    <th>&nbsp; </th>
                </tr>
            </thead>
            <tbody>
                    <tr id="4r4">
                    	<td>  Productos de la tienda </td>
                    	<th> {{ $cant_prod}} </th>
                        <td></td>

                        <td> Total Vendido </td>
                        <th class='alnright'>$ {{ format_numero( $suma_total_venta_product)  }} </th>
                        <td></td>

                    </tr>


                    <?php       if ($cant_open_prod > 0) {   ?>
                    <tr id="t44">
                    	<td>  Otros Productos  </td>
                    	<th> {{ $cant_open_prod}} </th>
                        <td></td>

                        <td> Total Vendido </td>
                        <th class='alnright'>$ {{ format_numero( $suma_total_venta_open_product )  }} </th>
                        <td></td>

                    </tr>

                    <?php      }  ?>
                    <tr id="4y4">
                    	<td>  Cantidad de Productos Vendidos  </td>
                    	<th> {{ $cant_prod + $cant_open_prod}} </th>
                        <td></td>

                        <td> Venta en Total  </td>
                        <th class='alnright'>$ {{ format_numero( $suma_total_venta_product + $suma_total_venta_open_product )  }} </th>
                        <td></td>

                    </tr>

 
               
                	<tr id="77 ">
                    	<td> 
                    		
                    	</td>
                    	<td></td>

                         
                         <td  style="text-align: right;" > <b>   </b> </td>
                         <td> </td>
                         <td>  </td>
                         <td> 
                             
                        </td>
                    </tr>
                
                

            </tbody>
        </table>
		
    </div><!-- end class="table-responsive" -->  
                    

                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
            
            </div>
        
      </div>




<!-- -->
<!-- -->
 








<?php 
$numComandas = 0;


$notasEfe = 0;
$notasTarjeta = 0;
$notasRecompensa = 0;
$sumaEfectivo = 0.0;
$sumaTarjeta = 0.0;
$sumaPuntos = 0.0;



$com_mat = 0;
$com_vesp = 0;



?>

<!---   partes reporte ---->
                        	
                        	 
<br />
							
							
							
<div class="row">
	<div class="col-lg-9">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h5>    Comandas 
				
				
				</h5>
				
			</div>
        <div class="ibox-content">
        
                        <table class="table">
                            <thead>
                           <tr>
                            <th>Nota</th>
                            <th>Hora de Compra</th>
							<th>Tipo de Pago </th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                            </thead>
                            <tbody>
							<?php $total_discount=0;$total_amount = 0; 
							
							$total_com_bank = 0.0;
							
							?>
                    
						<?php 
					//---
					//echo var_dump($sales);
					
					?>
                        
                         
                        
                        
                        <?php /*@ for el  se ($sales as $key => $sa  le)   */ ?>
                        <?php /*@ for el  se ($sales as $key => $sa  le)  

$sale
						*/ ?>
						
                        @forelse ($listaComandas as $key2 => $venta)
                            <tr>
                                <td> <strong>  {{ $venta->id  }}  </strong> </td>
								<td>  {{ $venta->created_at->format('g:i  a') }}</td>
								
							    <!-- td>{{$currency}} {{ $venta->amount}}</td -->
                                <td>
                                <?php 
                                    if($venta->payment_with == "cash") { echo "Efectivo"; }
                                    if($venta->payment_with == "card") { echo "Tarjeta"; }  /* else { echo "Tarjeta"; } */
                                    if($venta->payment_with == "puntos") { echo "puntos"; }
                                    if($venta->payment_with == "wallet") { echo "Wallet"; }
                                ?>
                                </td>
                                

                                <td></td>
                                <!-- td>{{$currency}} { _{ format_numero($sale->tax_card) } _ }</td -->
                                
                                
                                <td>
                                	
                                </td>
                                <!-- td class='alnright'>{{$currency}} {_{ $sale->amount }_}</td -->
                                <td class='alnright'>
                                	{{--  Total + iva si aplica  --}}
                                	@if ($venta->payment_with === 'card')
	                                	{{$currency}} {{ format_numero( $venta->amount + $venta->tax_card) }}
	                                @else  	
	                                	{{$currency}} {{ format_numero( $venta->amount) }}
                                	@endif
                                    
                                    
            
                                    
                                    {{--    $currency}} {{ format_numero( $venta->amount)   --}}

                                </td>
                                <td>
                                    <!-- a href="{{ url('reports/sales/' . $venta->id) }}" class="btn btn-primary btn-xs pull-right">@lang('common.show')</a -->
                                </td>
                            </tr>
<tr>
                <td colspan='1'></td>

                <td colspan='6'>
<div class="table-responsive">
    <table class="table table-striped">
            <thead>
                <tr>
                    <th>&nbsp; &nbsp; &nbsp;</th>
                    <th>Descripción</th>
                    <th>P. U.  </th>
                    <th>Cantidad</th>
                    <th>Importe</th>
                    <!-- th>Elaboró</th -->
                                        

                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    if(!empty($venta->items)) { 
                	// foreach ($listaGastos as $row) {
                    /*    foreach($comanda->items as $item){
                            // echo '<br />' . var_dump($item);
                
                    }*/
                    $sumTotRows = 0.0;   
                    foreach ($venta->items as $row) { 
                         ?>
                    <tr id="fila_{{ $row->id }}">
                    	<td> &nbsp; </td>
                    	<td> @if( $row->isOpen == true )
								{{ $row->nombreOpenProducto }}
							@else
								{{ $row->nombreLargo }}
							@endif </td>


                        <td>  ${{ number_format( $row->price) }}</td>
                        <td style="text-align: center;">    {{ $row->quantity}}</td>
                        <td class="kitchen" width="50" style="text-align: right;"><strong><?php echo $currency; ?>{{ number_format($row->quantity * $row->price,2) }}</strong></td>
                        <?php $sumTotRows = $sumTotRows + ($row->quantity * $row->price); ?>
                        <td></td>
                    </tr>
                <?php } 
                ?>
                @if ($venta->payment_with === 'card')
                    <tr id="fila_{{ $row->id }}">
                    	<td> 
                    		
                    	</td>
                    	<td></td>

                         
                         
                         <td> </td>
                         <td> Com. Tarjeta: </td>
                         
                        <td  style="text-align: right;" > <b>  $ {{ number_format( $venta->tax_card , 2) }} </b> </td>
                        <td></td>
                    </tr>
                    <?php  $sumTotRows = $sumTotRows +  $venta->tax_card;  ?>
                @endif    
                    <tr id="fila_{{ $row->id }}">
                    	<td> 
                    		
                    	</td>
                    	<td></td>

                         
                         
                         <td> </td>
                         <td> Total: </td>
                         
                        <td  style="text-align: right;" > <b>  $ {{ number_format( $sumTotRows , 2) }} </b> </td>
                        <td></td>
                    </tr>
                
                
                <?php
                } else {  ?>
                <tr>
                    <td rowspan="5">@lang('common.no_record_found') </td> 
                </tr>
<?php } ?>

            </tbody>
        </table>
		<!--     {  ! ! $expenses->render() ! ! }   -->
    </div>

                </td>
</tr>

<tr>
    <td colspan='7'></td>
</tr>








							
							<?php $total_amount += $venta->amount; ?>
							<?php $total_discount += $venta->discount; ?>
							
							<?php $total_com_bank += $venta->tax_card; ?>
							
                        @empty
                            @include('backend.partials.table-blank-slate', ['colspan' => 5])
                        @endforelse
                    
                    </tbody>
					
						<tr>
                                <!-- th>{_{_count($sales)_}_}</th -->
								<th></th>
                                <td></td>
                                <th></th>
                                <th></th>
                                <th></th>
                                <!-- th>{{$currency}} {{ format_numero($total_amount - $total_discount) }}</th -->
                                <!-- th class='alnright'>{{$currency}} {{ format_numero($total_amount - $total_discount + $total_com_bank) }}</th -->
                                <th> Venta en General </th>
                                <th> $ {{ format_numero($total_amount ) }}</th>
                                <!-- th> Total  : </th -->
                                <!-- th>{{$currency}} {{ format_numero($total_amount - $total_discount) }}</th -->
                               
                        </tr>

                        <tr>
                                <!-- th>{_{_count($sales)_}_}</th -->
								<th></th>
                                <td></td>
                                <th></th>
                                <th></th>
                                <th></th>
                                <!-- th>{{$currency}} {{ format_numero($total_amount - $total_discount) }}</th -->
                                <!-- th class='alnright'>{{$currency}} {{ format_numero($total_amount - $total_discount + $total_com_bank) }}</th -->
                                <th> Cargos por Comisión Bancaria </th>
                                <th> $ {{ format_numero($total_com_bank ) }}</th>
                                <!-- th> Total  : </th -->
                                <!-- th>{{$currency}} {{ format_numero($total_amount - $total_discount) }}</th -->
                               
                        </tr>



                        <tr>
                                <!-- th>{_{_count($sales)_}_}</th -->
								<th>{{ count($listaComandas) }}</th>
                                <td>Comanda(s)</td>
                                <th></th>
                                <th></th>
                                <th></th>
                                <!-- th>{{$currency}} {{ format_numero($total_amount - $total_discount) }}</th -->
                                <!-- th class='alnright'>{{$currency}} {{ format_numero($total_amount - $total_discount + $total_com_bank) }}</th -->
                                <th> Total de ventas </th>
                                <th> $ {{ format_numero($total_amount + $total_com_bank ) }}</th>
                                <!-- th> Total  : </th -->
                                <!-- th>{{$currency}} {{ format_numero($total_amount - $total_discount) }}</th -->
                               
                        </tr>





							
							
                        </table>

                    </div>
                </div>
            </div>
            
           
        
    <!-- /div -->
							
				

<!---   partes reporte ---->




<?php 


// echo var_dump($listaComandas);

$com_efe = 0;
$com_tarje = 0;
$com_tarje = 0;





foreach($listaComandas as $key => $comanda){

	//  echo '<br/> '. json_encode($comanda);


	//@__if("card" ===  $sale->payment_with
	if("card" ===  $comanda->payment_with){
		$notasTarjeta = $notasTarjeta + 1;
		
        
        $sumaTarjeta = $sumaTarjeta + ($comanda->amount + $comanda->tax_card);


        $com_tarje = 0;


	}

	if("cash" ===  $comanda->payment_with){
		$notasEfe = $notasEfe + 1;
		$sumaEfectivo = $sumaEfectivo + $comanda->amount;

        $com_efe++;


	}


	echo '<br />';
}

$total_amount = $sumaTarjeta + $sumaEfectivo;
$total_comandas = $notasTarjeta + $notasEfe;




?>




<!-- -->

 


</div>
		 

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
                            <th>Tipo</th>
                            <th>Monto</th>


							<th></th>
                            <th></th>
                        </tr>
                            </thead>
                            <tbody>
							<?php $total_discount=0;
                            /*
                            $total_amount = 0; 
							*/
							$total_com_bank = 0.0;
							
							?>
                    </tbody>
					
						    
                    
                        @if($notasEfe > 0) 
                    
						    <tr>
                                <th>{{ $notasEfe }}</th>
                                <td></td>
                                
							    <!-- th>{{$currency}} {{ $total_amount }}</th -->
							    <th> Efectivo </th>
                                <th style="text-align: right;" >{{$currency}} {{ format_numero($sumaEfectivo) }}</th>
							    <th></th>
                                <th>  </th>
                                <th>  </th>
                                <th>  </th>
                                <!-- th> Total  : </th -->
                                <!-- th>{{$currency}} {{ format_numero($total_amount - $total_discount) }}</th -->
                               
                            </tr>
                        @endif    

                        @if($notasTarjeta > 0) 
                    
						    <tr>
                                <th>{{ $notasTarjeta }}</th>
                                <td></td>
                                
							    <!-- th>{{$currency}} {{ $total_amount }}</th -->
							    <th> Tarjeta </th>
							    <th style="text-align: right;" >{{$currency}} {{ format_numero($sumaTarjeta) }}</th>
							    <th></th>
                                <th>  </th>
                                <th>  </th>
                                <th>  </th>
                                <!-- th> Total  : </th -->
                                <!-- th>{{$currency}} {{ format_numero($total_amount - $total_discount) }}</th -->
                               
                            </tr>
                        @endif  
                    
                    
                            <tr>
                                <th> {{ $total_comandas }} </th>
                                <td></td>
                                
							    <!-- th>{{$currency}} {{ $total_amount }}</th -->
							    <th>  </th>
							    
							    <th style="text-align: right;" >{{$currency}} {{ format_numero($total_amount) }}</th>
                                <th></th>
                                <th>  </th>
                                <th>  </th>
                                <th>  </th>
                                <!-- th> Total  : </th -->
                                <!-- th>{{$currency}} {{ format_numero($total_amount - $total_discount) }}</th -->
                               
                            </tr>
							
							
                        </table>

                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
            
        </div>
        
    </div>
</div>



<br />
<br />
<br />
<br />
<br />
<br />
<br />





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
	

var _url = "{{ route('reportes.cortes') }}";


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


<style>
    .alcentro { text-align: center; }
</style>

<!--   alter_ -->



<script>

var listaCatItems = <?= json_encode($listaPorItems, JSON_PRETTY_PRINT); ?>  ;


</script>



@endsection
