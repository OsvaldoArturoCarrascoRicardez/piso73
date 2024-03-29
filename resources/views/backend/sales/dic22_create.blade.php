@extends( 'layouts.app' )
@section( 'content' )
<?php $currency =  setting_by_key("currency");
//ALTER TABLE `customers`  ADD `neighborhood` VARCHAR(255) NULL;
//ALTER TABLE `customers` ADD `comments` VARCHAR(255) NULL;
 ?>
 <?php
 /*
 fix
 2022-02-28-   ya se agregó la propina, y parte de los recibos, 
 añadir es, 
 
 
 
 */
 
 ?>
 
<link href="{{url('assets/css/plugins/sweetalert/sweetalert.css')}}" rel="stylesheet">
<link href="{{url('assets/css/plugins/toastr/toastr.min.css')}}" rel="stylesheet">
<script src="{{url('assets/js/plugins/toastr/toastr.min.js')}}"></script>
<script src="{{url('assets/js/plugins/sweetalert/sweetalert.min.js')}}"></script>
<script>
	// total
	var total_en_pantalla/*_efe*/ = 0.00;
	var global_comision_card = 3;
	var porcentaje_tarjeta = 1.00;
	var total_en_pantalla_tarj = 0.00;
	
	// agregar  bill, tipvalue  y tiptotal_
	// agregar  bill, tipvalue  y tiptotal_
	
	
	
	
	
	// improvisado_
	var agregar_propina = 0.00;
	var total_propinas = 0.00; // en pantalla_
	//var agregar_propina = 0.00;
	
	{{--  /* MAnejo de Propinas  */  --}}
	var tipo_propina = "ninguna"; // minus
	var tipResult = 0.0;
	
	
	
	var total_importes_pantalla = 0.0;
	var pre_total_pantalla = 0.0;
	
	
	function calcule_tipping(monto = 0, tipo = "ninguna"){
		if (typeof tipo == 'undefined'){
			return monto;
		}
		
		//  10per   15per  20per
		var __propina = 0.0;
		  switch( tipo ) {
			  case "10per":
				__propina = monto * 0.10;
			  break;
			  case "15per":
				__propina = monto * 0.15;
			  break;
			  case "20per":
				__propina = monto * 0.20;
			  break;
			  case "manual":
				__propina = monto;
			  break;
			  case "ninguna":
				__propina = 0.0;
			  break;
			default:
			  __propina = 0.0;
		  }
		  return __propina;
	}
	
	
	{{--   Currency Name: Mexican Peso (MXN)  --}}
	function formatCurrency(valor){
		valor || (valor = 0);
		const formatter = new Intl.NumberFormat('es-MX', {
		  style: 'currency',
		  currency: 'MXN',
		  minimumFractionDigits: 2
		});
		
		return formatter.format(valor);
	}
	
	
	//---- temporal
	//-- se usa
	//   var agregar_propina = 0.00;
	//var agregar_propina = 0.00;
	//   var tipo_propina = "ninguna"; //
	function add_Propina(){
		
	}
	
	
	{{-- Add function valid  --}}
	  //const isEmpty = (str) => !str || !str.trim();
	function  isEmpty_String(str){
		//return !str || !str.trim();
		return  (str !== null && str.trim().length == 0);
		//??????
		//return  (str !== null && str.trim().length == 0);
	} 
	
	
	{{--  Agregado Octubre 2022, Otros Methods   --}}




	{{--  sucess, done    --}}
	/*success: */

	var resp_data = {};
	var resp_error = {};
	function pagina_en_linea (data, status, xhr) {
        // $('p').append('status: ' + status + ', data: ' + data);
		resp_data = data;
		//console.log('status: ' + status + ', data: ' + data);
		console.log('status: ok');

    }
	{{--  error    --}}
	/*error: */
	function metodo_fail(jqXhr, textStatus, errorMessage) {
            //_$('p').append('Error' + errorMessage);
			resp_error = jqXhr;
			console.log('Error' + errorMessage);
    }

	{{--  complete    --}}
	{{--  orden  ajax    --}}
	function monitor_ventana_activa(){
		$.ajax({
				type: 'POST',
				headers: {
					'X-CSRF-TOKEN': $( 'meta[name="csrf-token"]' ).attr( 'content' )
				},
				//_url: '<?php echo url("sale/hold_order"); ?>',
				// salev2/hold_order
				// url: '<?php echo route("nextStep"); ?>',
				url: '{{ route('nextStep') }}',
				//data: {},
				success: pagina_en_linea /*(data, status, xhr)*/,
				error: metodo_fail /*(jqXhr, textStatus, errorMessage) */
		        //---
			});


		//---	
	}
	{{--  time out    --}}
	var monitor_ventana = null;
	//	myInterval = setInterval(function, milliseconds);

	$(function() {
		{{--  Inicia el interval   --}}
		monitor_ventana = setInterval(monitor_ventana_activa, 1000 * 60 * 5);
	});







	
	
	
	 
	
	
</script>

<div class="wrapper wrapper-content animated fadeInRight">

	<div class="row">
		<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 pull-right">
			<div class="row">
				<div class="col-sm-12">

					<div class="ibox" style="margin-bottom: 0px;">
						<div class="ibox-title">
							<h5>Fecha: &nbsp;&nbsp;&nbsp;     
							 <input type="date"  id="porfecha" name="porfecha" placeholder="" /> </h5>
						</div>
						<div class="ibox-title">
							<h5>Folio: &nbsp;&nbsp;&nbsp;     
							 <input type="text"  id="numComanda" name="numComanda" placeholder="Ejem: N-5454" /> </h5>
						</div>




						<div class="ibox-title">
							<h5>@lang('pos.cart_items') <span id="TableNo"> </span></h5>
						</div>


						<div class="ibox-content" id="car_items" style="padding: 5px;">
							<div class="cart-table-wrap">

								<table width="100%" border="0" style="border-spacing: 5px; border-collapse: separate;" class="">

									<tbody id="CartHTML">

									</tbody>

								</table>
							</div>
							<hr>
							<table width="100%" border="0" style="border-spacing: 5px; border-collapse: separate;" class="">

								<tbody>

									<tr>{{-- Sección de Subtotals --}}

										<td>

											<h4>@lang('pos.sub_total')</h4>

										</td>

										<td class="text-right">

											<h4 id="p_subtotal">0.00</h4>

										</td>
									</tr>
									{{--    hidden --}}
									{{--   Manejar descuento segun el caso --}}
									<tr style="display: none;">
										<td>
											<h4>@lang('pos.discount')</h4>
										</td>
										<td class="text-right">
											<h4 id="p_discount">0.00</h4>
										</td>
									</tr>
									
									<tr style="display: none;">
										<td>
											<h4>@lang('pos.tax')(<?php echo setting_by_key("vat"); ?>%)</h4>
										</td>
										<td class="text-right">
											<h4 id="p_hst">0.00</h4>
										</td>
									</tr>
									
									
									<tr style="display: none;">
										<td>
											<h4>Agregar</h4>
										</td>
										<td class="text-right">
											
										</td>
									</tr>
									<tr id="filaPropinas" style="display: none;">
										<td>
											<h4>Propinas</h4>
										</td>
										<td class="text-right">
											<h4 id="p_propinas">0.00</h4>
										</td>
									</tr>
									{{--  ___ --}}
									
									
									<tr></tr>
									<tr></tr>
									
									<tr>
										<td colspan=2>
										<select id="OrderType" class="form-control"> 
											<option value="pos">@lang('online_orders.order_store')</option>
											<?php /* <option value="order">@lang('online_orders.order_home')</option> */?>
										</select>
										</td>
									</tr>
									<input type="hidden" id="OrderType" value="pos">
									<input type="hidden" id="VatInclude" value="No">
									<?php /*
									<tr>
										<td> <strong> Incluye IVA </strong></td>
										<td>
										<select id="VatInclude" class="form-control"> 
											<option value="Yes">Yes</option>
											<option value="No">No</option>
										</select>
										</td>
									</tr> */?>
									

								</tbody>

							</table>

						</div>
						<div class="panel-footer green-bg">
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tbody>
									<tr>
										<td>

											<h4><strong>@lang('pos.total')</strong></h4>

										</td>
										<td class="text-right ">

											<h3 class="TotalAmount">0</h3>

										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>

					<div class="ibox-content" style="padding-bottom: 0px;  display: none;" id="filaCobro" >
						<div class="row">							
						<div class="col-sm-6 col-md-12 col-lg-6">
								<div class="form-group">
									<button type="button" id="Checkout" class="btn btn-primary btn-block text-center">@lang('pos.checkout')</button>		 
								</div>
						</div>
							<div class="col-sm-6 col-md-12 col-lg-6">
								<div class="form-group">
									<?php //button type="button" id="ClearCart" class="btn btn-danger btn-block text-center">@lang('pos.clear_cart')</button ?>
									<button type="button" id="ClearCart" class="btn btn-danger btn-block text-center">Cancelar Venta</button>
								</div>
							</div>
						</div>

						<!-- div class="row">							
							<div class="col-sm-6 col-md-12 col-lg-12">
									<div class="form-group">
										<button type="button" id="holdOrders" class="btn btn-success btn-block text-center">@lang('pos.hold_tables')</button>
									</div>
							</div>
							
						</div -->
						<div class="row">							
							<div class="col-sm-6 col-md-12 col-lg-6">
									<div class="form-group">
										<!--  button type="button" id="holdOrders" class="btn btn-success btn-block text-center">@lang('pos.hold_tables')</button  -->    
										<!-- button type="button" id="orderToSave"    class="btn btn-success btn-block text-center">Mantener Orden</button -->    
									</div>
							</div>
							<div class="col-sm-6 col-md-12 col-lg-6">
								<div class="form-group">
									{{-- <button type="button" id="manageTipping" class="btn btn-primary btn-block text-center">+ Propinas</button>	--}} 
								</div>
							</div>
						</div>
					</div>
					
					
					<!-- -->
					<div class="ibox-content" style="padding-bottom: 0px;  display: none;" id="filaEspera_444" >
						<div class="row">							
							<div class="col-sm-6 col-md-12 col-lg-6">
								<div class="form-group">
									<button type="button" id="holdOrders" class="btn btn-success btn-block text-center">@lang('pos.hold_tables')</button>    
								</div>
							</div>
							<div class="col-sm-6 col-md-12 col-lg-6">
								<div class="form-group">
									<!-- button type="button" id="nanagetpi_" class="btn btn-primary btn-block text-center">+ Propinas</button -->	
								</div>
							</div>
						</div>
					</div>
					<!-- -->
					

				</div>
				
			</div>
		</div>
		<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
			<div class="ibox float-e-margins">
				<div class="ibox-title" style="margin-bottom: 10px;">
					<div class="toolbar mb2 mt2">
						<button class="btn fil-cat" href="" data-rel="all">@lang('common.all')</button> @foreach($categories as $category)
						<button class="btn fil-cat" data-rel="{{$category->id}}">{{ $category->name }}</button> @endforeach

					</div>				
				</div>
				<!--	<div class="ibox-content m-b-sm border-bottom">

                <div class="row">

                    <div class="col-sm-12">

                        <div class="form-group">

                            <input type="text" id="product_name" name="product_name" value="" placeholder="Search" class="form-control">

                        </div>

                    </div>
                </div>
            </div> -->

				<div class="row" id="portfolio">

					@foreach($products as $product)

					<div class="col-xs-12 col-sm-4 col-md-6 col-lg-3 {{$product->category_id}} all">
						<div class="widget white-bg text-center product_list h-100">
							@if(file_exists('uploads/products/' . $product->id . '.jpg'))
							<img width="100px" alt="image" class="img-circle" src="{{url('uploads/products/thumb/' . $product->id . '.jpg')}}">
							<h2 class="m-xs heading-size_image">{{$product->name}}</h2> @else
							<img width="100px" alt="image" class="img-circle" src="{{url('herbs/noimage.jpg')}}">
							<h2 style="padding-left:5px; text-align:left" class="m-xs heading-size_image">{{$product->name}}</h2> @endif
							<?php $prices = json_decode($product->prices); $titles = json_decode($product->titles);?> 
							@foreach($titles as $key=>$t)
							<button data-price="{{$prices[$key]}}" data-id="{{$product->id}}" data-key="{{$key}}" data-size="{{$t}}" data-name="{{$product->name}} ({{$t}})" type="button" class="btn btn-sm btn-primary m-r-sm AddToCart tag-margin tag-btn">{{ $t }}</button> 
							@endforeach						
							</div>
					</div>
					@endforeach
					<!--   manual product type open  -->
					<div class="col-xs-12 col-sm-4 col-md-6 col-lg-3 599999 all">
						<div class="widget white-bg text-center product_list h-100">
							<img width="100px" alt="image" class="img-circle" src="{{url('herbs/noimage.jpg')}}">
							<h2 style="padding-left:5px; text-align:left" class="m-xs heading-size_image"> Open Product_ </h2> 
							
							<?php $prices = json_decode($product->prices); $titles = json_decode($product->titles);?> 
							
								<button data-price="0.00" data-id="999" data-key="400" data-size="0" data-name="Producto Editable _Editar_" type="button" class="btn btn-sm btn-primary m-r-sm  tag-margin tag-btn  AddOpenToCart">_Editar_</button> 
							
							</div>
					</div>
					<!--  -->
					
					
					
					
					
				</div>			</div>
		</div>
	</div>
</div>

											{{--  	tabindex="-1"     --}}
<div class="modal inmodal" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">

	<div class="modal-dialog">

		<div class="modal-content animated bounceInRight confirm-modal">

			<div class="modal-header">

				<?php /*?><button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><?php */?>
				<h4 style="float:left; color:red" id="TableNoCart"></h4>Cobrar
				<h4 class="modal-title" id="total_amount_model">0.00</h4>
			</div>

			<div class="modal-body clearfix">

				<input type="hidden" id="cashier_id" class="form-control" value="{{Auth::user()->id}}">
				<input type="hidden" id="vat" class="form-control" value="0.00">
				<input type="hidden" id="delivery_cost" class="form-control" value="0">
				<input type="hidden" id="total_amount" class="form-control" value="0">
				<input type="hidden" id="customer_id" class="form-control" value="">

				<input type="hidden" id="payment_type" class="form-control" value="Cash">

		
				<div class="col-sm-12">

					<p class="text-center">@lang('pos.how_would_you_pay')</p>

				</div>

				<div class="col-sm-3 col-sm-offset-3">

					<div class="form-group text-center">

						<div data-id="Cash" class="payment-option-icon text-success" id="opcionCash" >

							<i class="fa fa-money fa-4x"></i>

						</div>

					</div>

				</div>

				<div class="col-sm-3">

					<div class="form-group text-center">

						<div data-id="Card" class="payment-option-icon">

							<i class="fa fa-credit-card fa-4x"></i>

						</div>

					</div>

				</div>
				<div class="clearfix"></div>

				<div class="col-sm-6">
				{{--  No deberia haber textos aquí, pero de manera provisional  --}}
					<div class="form-group">Su Pago
						<input type="text" id="total_given" placeholder="@lang('pos.total_paid')" class="form-control numberPad"   autocomplete="off">
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">Cambio
						<input type="text" id="change" readonly placeholder="@lang('pos.change')" class="form-control">
					</div>
				</div>
				{{--  No deberia haber textos aquí, pero de manera provisional  --}}
				<div class="col-sm-6">
					<div class="form-group">
						 <select class="form-control" id="table_id">
						 {{--  <option value="0">-- Seleccione --</option> --}}
							 @foreach ($tables as $table)
								<option value="{{$table->id}}">{{$table->table_name}}</option>
							 @endforeach
							
						 </select>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<textarea id="comments" placeholder="@lang('pos.comment')" class="form-control" autocomplete="off"></textarea>
					</div>
				</div>

				<div class="col-sm-12 text-right">
					<!-- button type="button" class="btn btn-warning"  id="holdOrder" >@lang('pos.hold_order')</button -->
					<button type="button" class="btn btn-white" data-dismiss="modal">@lang('pos.close')</button>
				<input type="hidden" value="" id="id" />
				<button type="button" id="completeOrder" class="btn btn-primary">@lang('pos.complete_order')</button>
				</div>
				
				<div class="col-sm-12 text-right">
					<h4 style="float:left; color:red" id="TableNoCart_cancelado"></h4>
					<h4 class="modal-title" id="total_amount_model_nototal">{{-- 0.00 --}}</h4>
					{{-- Esto es un comentario --}}
				</div>
				
				

			</div>

		</div>

	</div>

</div>






{{--   Modal Window Tipping  --}}
<div class="modal inmodal" id="modalTipping" tabindex="-1" role="dialog" aria-hidden="true">

	<div class="modal-dialog">

		<div class="modal-content animated bounceInRight confirm-modal">

			<div class="modal-header">

				<?php /*?><button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><?php */?>
				<h4 style="float:left; color:red" id="TableNoCartxxx"></h4>Agregado
				<h4 class="modal-title" id="h4TipTotalPago">$0.00</h4>
				<p class="text-center">El porcentaje se Calcula sobre el subtotal</p>
			</div>

			<div class="modal-body clearfix">
			
			
			<fieldset>

				<input type="hidden" id="xxcashier_id" class="form-control" value="{{Auth::user()->id}}">
				<input type="hidden" id="xxvat" class="form-control" value="0.00">
				<input type="hidden" id="xxdelivery_cost" class="form-control" value="0">
				<input type="hidden" id="xxtotal_amount" class="form-control" value="0">
				<input type="hidden" id="xxcustomer_id" class="form-control" value="">

				<input type="hidden" id="xxpayment_type" class="form-control" value="Cash">
				
				
				<input type="hidden" id="tipoPropina" class="form-control" value="ninguna" />
				<input type="hidden" id="valorPropinaManual" class="form-control" value="0" />
				

		
				<div class="col-sm-12">
					<h3 class="text-center" id="h3Total_plus_tip">$0.00</h3>

					<p class="text-center">Agregar propinas_</p>

				</div>

				<div class="col-sm-3 col-sm-offset-3">
 

				</div>

				<div class="col-sm-3"> 
				</div>
				<div class="clearfix"></div>
				
				<div class="col-sm-6">
					 
					  <div class="form-group">
						<label for="radioTip" class="control-label col-xs-4">{{--  Radio Buttons  --}}</label> 
						<div class="col-xs-8">
						  <div class="checkbox">
							<label class="checkbox">
							  <input type="radio" name="radioTip" value="ninguna" id="rbtNoAgregar" checked />
								  Sin Propina
							</label>
						  </div>
						  <div class="checkbox">
							<label class="checkbox">
							  <input type="radio" name="radioTip" value="10per"  />
								  10%
							</label>
						  </div>
						  <div class="checkbox">
							<label class="checkbox">
							  <input type="radio" name="radioTip" value="15per" />
								  15%
							</label>
						  </div>
						  <div class="checkbox">
							<label class="checkbox">
							  <input type="radio" name="radioTip" value="20per" />
								  20%
							</label>
						  </div>
						  <div class="checkbox">
							<label class="checkbox">
							  <input type="radio" name="radioTip" value="manual" />
								  Agregado...
							</label>
						  </div>
						</div>
					  </div>				
				</div>
				 
				<div class="clearfix"></div>
				
				
				<div class="col-sm-12">
				
					<div class="form-group">
					<label class="control-label col-xs-4" for="text1">Ingrese el Monto</label> 
						<div class="col-xs-8">
						  <div class="input-group">
							<div class="input-group-addon">
							  <i class="fa fa-dollar"></i>
							</div> 
							<input id="txtManualTip" name="txtManualTip" type="number" class="form-control">
						  </div>
						</div>
					</div>
				 
				</div>
				
				<div class="col-sm-6">
					 
				</div>
				<div class="col-sm-6">
					@php 
						//echo "invocado desde arroba  php";
					@endphp
					 
				</div>

				<!-- div class="col-sm-12 text-right">
					<button type="button" class="btn btn-warning"  id="xxholdOrder" >@lang('pos.hold_order')</button>
					<button type="button" class="btn btn-white" data-dismiss="modal">@lang('pos.close')</button>
				<input type="hidden" value="" id="idxxxxx" />
				<button type="button" id="completeOrderxxx" class="btn btn-primary">@lang('pos.complete_order')</button>
				</div -->
				<div class="col-sm-12">

					<p class="text-center">&nbsp;</p>

				</div>
				
				
				<div class="col-sm-12 text-right">
					<!-- button type="button" class="btn btn-warning"  id="xxholdOrder" >Descartar</button --> 
					<button type="button" id="btnAddTipping" class="btn btn-primary">Agregar</button>
					<button type="button" class="btn btn-white" data-dismiss="modal">@lang('pos.close')</button>
					
					
				</div>
				
				
				
				<div class="col-sm-12 text-right">
					<h4 style="float:left; color:red" id="TableNoCart_canceladoxxxx"></h4>
					<h4 class="modal-title" id="total_amount_model_nototalxxx">{{-- 0.00 --}}</h4>
					{{-- Esto es un comentario --}}
				</div>
				
			</fieldset>	

			</div>

		</div>

	</div>

</div>



<?php  // __  modalOpenProduct  ?>
<div class="modal inmodal" id="modalOpenProduct" tabindex="-1" role="dialog" aria-hidden="true">

	<div class="modal-dialog">

		<div class="modal-content animated bounceInRight confirm-modal">

			<div class="modal-header">

				<?php /*?><button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><?php */?>
				<h4 style="float:left; color:red" id="TdsdsdableNoCart"></h4>
				<h4 class="modal-title" id="tyjtytotal_amount_modelwf">Nuevo Producto</h4>
			</div>

			<div class="modal-body clearfix">

				<input type="hidden" id="rwrwrcashier_id" class="form-control" value="{{Auth::user()->id}}">
				<input type="hidden" id="erwrwvat" class="form-control" value="0.00">
				<input type="hidden" id="werewrwdelivery_cost" class="form-control" value="0"> 

		
				<div class="col-sm-12">

					<p class="text-center"> LLena los sig. Campos para el nuevo Producto. </p>

				</div>

				<!-- div class="col-sm-3 col-sm-offset-3">

					<div class="form-group text-center">

						<div data-id="Cash" class="payment-option-icon text-success" id="oprerefcionCash" >

							<i class="fa fa-money fa-4x"></i>

						</div>

					</div>

				</div -->

				<!-- div class="col-sm-3">

					<div class="form-group text-center">

						<div data-id="Card" class="payment-option-icon">

							<i class="fa fa-credit-card fa-4x"></i>

						</div>

					</div>

				</div -->
				
				<div class="clearfix"></div>

				{{--  Sección data   --}}
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label " for="txtConcepto">Concepto</label> 
						<input type="text" id="txtConcepto" placeholder="Ejemplo: Desayuno, " class="form-control numberPad"  autocomplete="off">
					</div>
					<!-- div class="form-group">
					<label class="control-label col-xs-4" for="txtNewNameProduct">Nombre Para producto</label> 
						<div class="col-xs-8">
						  <div class="input-group">
							 
							<input id="txtNewNameProduct" name="txtNewNameProduct"  class="form-control">
						  </div>
						</div>
					</div -->
					
					
					
					
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label " for="txtTipo">Tipo</label> 
						<input type="text" id="txtTipo"  placeholder="Ej: Vaso, A Granel, Pza" class="form-control">
					</div>
				</div>
				
				<!-- div class="clearfix"></div  -->
				<div class="col-sm-6">
					<div class="form-group">
						<!-- input type="text" id="total_givfen" placeholder="@lang('pos.total_paid')" class="form-control " -->
						<label class="control-label " for="txtPrecio">Precio</label> 
						<div class="input-group">
							<div class="input-group-addon">
							  <i class="fa fa-dollar"></i>
							</div> 
							<input id="txtPrecio" name="txtPrecio" type="number" class="form-control" autocomplete="off">
						  </div>
					</div>
				  
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label class="control-label " for="txtCantidad">Cantidad</label> 
						<!-- input type="text" id="change"  placeholder="@lang('pos.change')" class="form-control" -->
						<input id="txtCantidad" name="txtCantidad" type="number" class="form-control" placeholder="Ejem: 3" autocomplete="off">
					</div>
				</div>
				{{--  otra fila No deberia haber textos aquí, pero de manera provisional  --}}
				
				
				
				
				
				<!-- div class="col-sm-6">
					<div class="form-group">
						 <select class="form-control" id="table_id">
						 {{--  <option value="0">-- Seleccione --</option> --}}
							 @foreach ($tables as $table)
								<option value="{{$table->id}}">{{$table->table_name}}</option>
							 @endforeach
							
						 </select>
					</div>
				</div -->
				<!-- div class="col-sm-6">
					<div class="form-group">
						<textarea id="commentfferfs" placeholder="@lang('pos.comment')" class="form-control"></textarea>
					</div>
				</div -->

				<div class="col-sm-12 text-right">
					<!-- button type="button" class="btn btn-warning"  id="holdOrrfeder" >@lang('pos.hold_order')</button -->
					<button type="button" class="btn btn-white" data-dismiss="modal">@lang('pos.close')</button>
					<!-- input type="hidden" value="" id="id" / -->
					<button type="button" id="addCustomProd" class="btn btn-primary">Agregar Producto.</button>
				</div>
				
				<div class="col-sm-12 text-right">
					<h4 style="float:left; color:red" id="lblErrorCustoP"></h4>
					<h4 class="modal-title" id="sdfsftotal_amount_model_nototal">{{-- 0.00 --}}</h4>
					{{-- Esto es un comentario --}}
				</div>
				
				

			</div>

		</div>

	</div>

</div>







<script>


function showTippingModal(){
	// si no hay propina, desea agregar de todos modos, 
	// en pub, solo si hay un producto.
	
	// suma importes todos
	// subtotal 2 = suma importes - descuento
	//  o total =  suma importes - descuento
	// tiping = total + agregadado dinámico_
	
 
	
	if ( cart.length < 1 ) {

		swal( "Sin productos", "Agrega un producto para Realizar esta operación.", "error" );

		return false;
	}
	<?php  /*	*/  ?>

//- calculo de proprinas
//   propina manual, hidden

			if ("ninguna" === tipo_propina){
				//--
				total_propinas = 0.0;
			} else if (tipo_propina === "manual") {
				//__propinas = agregar_propina;
				// total_propinas = se toma el valor que se asigno_
				// set manual tipping and show
			} else {
				// select percent and show
				total_propinas = calcule_tipping(pre_total_pantalla, tipo_propina);
			}

		//  usar pre_total_pantalla
		// usar propina calculada en updateCart__
		let __suma_mas_propina = pre_total_pantalla + total_propinas;
 
	//$( "#h4TipTotalPago" ).html( formatCurrency( total_en_pantalla ) );
	$( "#h4TipTotalPago" ).html( formatCurrency( __suma_mas_propina ) );
	
	
	$( "#h3Total_plus_tip" ).html( " " + formatCurrency( pre_total_pantalla ) + " + " +  formatCurrency( total_propinas ) );
	
	  
	$("#modalTipping").modal("show");
	
	
}


{{--  /*Mostar Modal de Propinas */--}}
$( "body" ).on( "click", "#manageTipping", showTippingModal);



$('input[type=radio][name=radioTip]').change(function() {
    /*if (this.value == 'allot') {
        alert("Allot Thai Gayo Bhai");
    }
    else if (this.value == 'transfer') {
        alert("Transfer Thai Gayo");
    }
	*/
	update_Modal_Tipping();
	
});



function resetModalTipping(){
	{{-- /*Valores inciales */  --}}
	$( "#txtManualTip" ).val("");
	//$("input[name='radioTip']:checked").val();
	//$("input[name=radioTip][value=ninguna]").prop('checked', true);
	$("input[name=''][value='ninguna']").prop('checked', true);
	//--
	tipo_propina = "ninguna";
	tipResult =  parseFloat(0.0);
}



/**//*handle_radio_tipping*/
function update_Modal_Tipping(){
	
	//const __txtTip = $( "#txtManualTip" ).val();
	
	// cambiar por const
	//var radioValue = $("input[name='radioTip']:checked").val();
	{{--  Variables  --}}
	let totalTip = 0.0;
	
	{{--  Constantes  --}}
	const radioValue = $("input[name='radioTip']:checked").val();
	//  agregar
	const $txtTip = $( "#txtManualTip" ).val();
	const manual_propina = Number($txtTip);
	//tipResult = !isNaN(manualPropina) ? parseFloat(manualPropina) : 0.0;
	
	
	
	switch( radioValue ) {
			  case "10per":
				//totalTip = monto * 0.10;
				totalTip = pre_total_pantalla * 0.10;
			  break;
			  case "15per":
				totalTip = pre_total_pantalla * 0.150;
			  break;
			  case "20per":
				totalTip = pre_total_pantalla * 0.20;
			  break;
			  case "manual":
				totalTip = !isNaN(manual_propina) ? parseFloat(manual_propina) : 0.0;
			  break;
			  
			default:
			  totalTip = 0.0;
	}
	
	
	
	let tmpSumaProp = pre_total_pantalla + totalTip;
	
	$( "#h4TipTotalPago" ).html( formatCurrency( tmpSumaProp ) );
			$( "#h3Total_plus_tip" ).html( " " + formatCurrency( pre_total_pantalla ) + " + " +  formatCurrency( totalTip ) + " de propina " );
	
	/*
	if ("agregar" === radioValue){
		let manualPropina = Number(__txtTip);
		let tmpSumaProp = pre_total_pantalla + manualPropina;
		
		//$( "#h4TipTotalPago" ).html( formatCurrency( tmpSumaProp ) );
		
		if (isNaN(tmpSumaProp)){
			$( "#h4TipTotalPago" ).html( formatCurrency( pre_total_pantalla ) );
			$( "#h3Total_plus_tip" ).html( " " + formatCurrency( pre_total_pantalla ) + " + " +  formatCurrency( 0.0 ) + " de propina " );
		} else {
			$( "#h4TipTotalPago" ).html( formatCurrency( tmpSumaProp ) );
			$( "#h3Total_plus_tip" ).html( " " + formatCurrency( pre_total_pantalla ) + " + " +  formatCurrency( manualPropina ) + " de propina " );
		}
		
	
	}*/
	 
	//console.log(radioValue);
	
	//let __suma_mas_propina = pre_total_pantalla + total_propinas;
	 
}//---




function calculate_tipping_percent___(){
	
	const __txtTip = $( "#txtManualTip" ).val();
	
	// cambiar por const
	var radioValue = $("input[name='radioTip']:checked").val();
	//  agregar
	
	
	if ("agregar" === radioValue){
		let manualPropina = Number(__txtTip);
		let tmpSumaProp = pre_total_pantalla + manualPropina;
		
		//$( "#h4TipTotalPago" ).html( formatCurrency( tmpSumaProp ) );
		
		if (isNaN(tmpSumaProp)){
			$( "#h4TipTotalPago" ).html( formatCurrency( pre_total_pantalla ) );
			$( "#h3Total_plus_tip" ).html( " " + formatCurrency( pre_total_pantalla ) + " + " +  formatCurrency( 0.0 ) + " de propina " );
		} else {
			$( "#h4TipTotalPago" ).html( formatCurrency( tmpSumaProp ) );
			$( "#h3Total_plus_tip" ).html( " " + formatCurrency( pre_total_pantalla ) + " + " +  formatCurrency( manualPropina ) + " de propina " );
		}
		
	
	}
	 
	//console.log(radioValue);
	
	//let __suma_mas_propina = pre_total_pantalla + total_propinas;
	 
}




function addTippingToOrder(){
	 
	// cambiar por const
	//var radioValue = $("input[name='radioTip']:checked").val();
	{{--  Variables  --}}
	let totalTip = 0.0;
	
	{{--  Constantes  --}}
	const radioValue = $("input[name='radioTip']:checked").val();
	const $txtTip = $( "#txtManualTip" ).val().trim();
	let manualPropina = Number($txtTip);
	//  agregar
	
	if (radioValue === null || radioValue === "" ){
		toastr.error( 'Debes seleccionar una opción.' );
		return false;
	}
	
	{{--  Setear valores --}} 
	
	if (radioValue === "manual"){
		//toastr.info( 'Se agregó Propina a la cuenta.' );
		if ($txtTip === ""){
			toastr.error( 'Se debe ingresar un monto.' );
			return false;
		}
		
		
		if (isNaN(manualPropina)){
			toastr.error( 'El valor ingresado no es válido.' );
			return false;
		}
		
		if (manualPropina == 0){
			toastr.error( 'El valor ingresado no puede ser Igual a cero.' );
			return false;
		}
		
		
		
		
	}
	
	
	 
	tipResult = 0.0;
	switch( radioValue ) {
			  case "10per":
				tipo_propina = "10per";
			  break;
			  case "15per":
				tipo_propina = "15per";
			  break;
			  case "20per":
				tipo_propina = "20per";
			  break;
			  case "manual":
				tipo_propina = "manual";
				tipResult = !isNaN(manualPropina) ? parseFloat(manualPropina) : 0.0;
			  break;
			 /* case "ninguna":
				tipo_propina = "ninguna";
				tipResult =  parseFloat(0.0);
			  break;*/
			default:
			  tipo_propina = "ninguna";
			  tipResult =  parseFloat(0.0);
	}
	
	
	 
	 
	//---
	show_cart();
	$("#modalTipping").modal("hide");
	
	if (tipo_propina !== "ninguna"){
		toastr.info( 'Se agregó Propina a la cuenta.' );
	}

}//---



$( "body" ).on( "click", "#btnAddTipping", addTippingToOrder);





function calculate_txt_tipping(/*_any*/){
	
	const __txtTip = $( "#txtManualTip" ).val();
	
	// cambiar por const
	var radioValue = $("input[name='radioTip']:checked").val();
	//  agregar
	
	
	if ("manual" === radioValue){
		let manualPropina = Number(__txtTip);
		let tmpSumaProp = pre_total_pantalla + manualPropina;
		
		//$( "#h4TipTotalPago" ).html( formatCurrency( tmpSumaProp ) );
		
		if (isNaN(tmpSumaProp)){
			$( "#h4TipTotalPago" ).html( formatCurrency( pre_total_pantalla ) );
			$( "#h3Total_plus_tip" ).html( " " + formatCurrency( pre_total_pantalla ) + " + " +  formatCurrency( 0.0 ) + " de propina " );
		} else {
			$( "#h4TipTotalPago" ).html( formatCurrency( tmpSumaProp ) );
			$( "#h3Total_plus_tip" ).html( " " + formatCurrency( pre_total_pantalla ) + " + " +  formatCurrency( manualPropina ) + " de propina " );
		}
		
	
	}
	 
	//console.log(radioValue);
	
	//let __suma_mas_propina = pre_total_pantalla + total_propinas;
	 
}

	// txtManualTip
	$( "body" ).on( "keyup", "#txtManualTip", calculate_txt_tipping/*(event)*/);






	$( "body" ).on( "keyup", "#total_given_otro", function () {
		{{--    Funcionando, pero se Cambia la manera de Cobro y Cambio
		var total_amount = $( "#total_amount" ).val();
		var total_given = $( this ).val();
		//---modif
		
		var change = Number( total_given ) - Number( total_amount );
		$( "#change" ).val( change.toFixed( 2 ) );
		--}}
		
		const __payment_with = $( "#payment_type" ).val();
		if ("Card" === __payment_with){
			$( "#change" ).val( 0.00 );
		} else {
			var total_amount = total_en_pantalla/*$( "#total_amount" ).val()*/;
			var total_given = $( this ).val();
			//---modif
			
			var change = Number( total_given ) - Number( total_amount );
			$( "#change" ).val( change.toFixed( 2 ) );
		}
		//---
	} );


 

$("body").on("click",".deleteHoldOrderfunction22", function() {
				$(this).parent(".holdt").remove();
				
				var form_dataa = {
					id:$(this).attr("data-id")
				}
				$.ajax( {
					type: 'POST',
					headers: {
						'X-CSRF-TOKEN': $( 'meta[name="csrf-token"]' ).attr( 'content' )
					},
					url: '<?php echo url("sale/hold_order_remove"); ?>',
					data: form_dataa,
					success: function ( msg ) {
						
					}
				});
				
});


</script>



{{--                                                                                        --}}


<div class="modal inmodal" id="myHoldOrderModal" tabindex="-1" role="dialog" aria-hidden="true">

	<div class="modal-dialog">

		<div class="modal-content animated bounceInRight confirm-modal">

			<div class="modal-header">
				<h4 class="modal-title" id="total_amount_model">@lang('pos.hold_tables')</h4>
			</div>
			<input type="hidden" id="holdOrderID">
			<div class="modal-body clearfix">
				<div id="HoldOrdersList"> </div>
			</div>

		</div>

	</div>

</div>



 

<script type="text/javascript"> 


$( "body" ).on( "click", "#holdOrders", function () {
			var form_data = {
				id:""
			};
			$.ajax({
				type: 'POST',
				headers: {
					'X-CSRF-TOKEN': $( 'meta[name="csrf-token"]' ).attr( 'content' )
				},
				url: '<?php echo url("sale/hold_orders"); ?>',
				data: form_data,
				success: function ( msg ) {
					
					var obj = JSON.parse(msg);
					var html = "";
					$.each(obj , function(key,value) { 
						html += '<div class="holdt"> <a style="color:red" href="javascript:void(0)" data-id= "' +  value.id + '" class="deleteHoldOrder"><i class="fa fa-trash"> </i></a> <a href="javascript:void(0)" class="ViewHoldOrder" data-table="' + value.table + '" data-table_id="' + value.table_id + '" data-id= "' +  value.id + '">@lang('pos.order_no'):: ' +  value.id + "</a> <span style='padding-left:30px'>@lang('pos.held_by'):  " + value.username + "<span style='padding-left:30px'>@lang('pos.Table No'):  " +  value.table + "</span></div>";
					});
					if(html == "") { 
						//html = "No Hold Table Found";
						html = "Sin ordenes en espera";
					}
					$("#HoldOrdersList").html(html);
					$("#myHoldOrderModal").modal("show");
				}
			});
		});

		$( "body" ).on( "click", ".ViewHoldOrder", function () {
			var form_data = {
				id:$(this).attr("data-id")
			}

			$("#holdOrderID").val($(this).attr("data-id"));
			$("#TableNo").text(" (" + $(this).attr("data-table") + ")");
			$("#TableNoCart").text(" (" + $(this).attr("data-table") + ")");
			$("#table_id").val($(this).attr("data-table_id"));
			$.ajax( {
				type: 'POST',
				headers: {
					'X-CSRF-TOKEN': $( 'meta[name="csrf-token"]' ).attr( 'content' )
				},
				url: '<?php echo url("sale/view_hold_order"); ?>',
				data: form_data,
				success: function ( msg ) {
					//----
					/*  id
create:919 product_id
create:919 price
create:919 size
create:919 name
create:919 quantity
create:919 p_qty  */
					
					//console.log("receibe");
					//console.log(msg);
					//cart = JSON.parse(msg);
					
					{{--   quantity   p_qty  --}}
					{{--  No tomaba bien las cantidades.  --}}
					cart = JSON.parse(msg, function (key, value) {
						if (key == "quantity") {
							return parseInt(value);
						} else {
							return value;
						}
					});
					/*let otro = JSON.parse(msg, function (key, value) {
						if (key == "quantity") {
							return parseInt(value);
						} else {
							return value;
						}
					});*/
					
					//console.log("nuevo arreglo");
					//console.log(JSON.stringify(otro));
					
					//---
					show_cart();
					$("#myHoldOrderModal").modal("hide");
				}
			});


		});
		
//----------

		
		function muestra_cart(){
			console.log(cart);
		}
				
		
		
		$( "body" ).on( "click", "#holdOrder", function () {
			var form_data = {
				id: $("#holdOrderID").val(),
				table_id: $("#table_id").val(),
				comment: $("#comments").val(),
				cart:cart,
				pre_total: total_en_pantalla,
			};
			
			
			console.log("------");
			console.log(form_data);
			
			// before save, check if table is already in use (max in two) total tree

			
			$.ajax( {
				type: 'POST',
				headers: {
					'X-CSRF-TOKEN': $( 'meta[name="csrf-token"]' ).attr( 'content' )
				},
				//_url: '<?php echo url("sale/hold_order"); ?>',
				// salev2/hold_order
				url: '<?php echo url("salev2/hold_order"); ?>',
				data: form_data,
				success: function ( msg ) {
					{{--  ya hay json response, pero reload page  --}}
					//location.reload();
					
					console.log(msg);
					
					$("#myModal").modal("hide");
				},
				error: function(data) {
					// data.responseJSON
					//		{"message":"The given data was invalid.","errors":{"total":["El campo total es obligatorio."]}}    
					// data.responseText
				
		            
		            console.log(data);
		            var _otro = data.responseJSON;
		            if (_otro && _otro.message){
		            	console.log(_otro.message);
		            }
		            
		            
		        }
		        //---
			});
		});



$("body").on("click","#Checkout", function() {
	var OrderType = $("#OrderType").val();
	<?php  //edit 2022-01-24   
		// no se manda niguna Ventana Modal Hasta que tenga productos a Pagar
	
	?>
	
	// $("#customer_id").val(obj['id']);
	$("#total_given").val("");
	$("#change").val("");
	
	
	
	
	
	
	if ( cart.length < 1 ) {

		//$( "#myModal" ).modal( "hide" );

		//swal( "", "Sin productos <br/>  Agrege un producto Para concretar la venta.", "error" );
		//__ 			swal( "!Sin productos¡", "Agrega un producto para concretar la venta.", "error" );

		swal( "Sin productos", "Agrega un producto para concretar la venta.", "error" );

		return false;
	}



	//--- 
	// Las comandas de fechas anteriores solo se guardan con el folio.
	let str_fecha = $("#porfecha").val();
		let _numFol = $('#numComanda').val().trim();
		
		if ( str_fecha !== __today && _numFol.length == 0 ){

			swal( "Folio Sin asignar", "Para procesar comandas de otras fechas, se debe agregar el folio de tu comanda escrita.", "error" );
			return false;
		}
	//--------------------------------------

	<?php /* al momento del checkout, enviar totales a session para confirmar valores, en 
desarrollo posterior	*/  ?>
	
	$(".payment-option-icon").removeClass("text-success");
	$("#opcionCash").addClass("text-success");
	//$( "#total_amount_model" ).html("<?php echo $currency; ?>" + total_en_pantalla.toFixed( 2 ) );
	$( "#total_amount_model" ).html( formatCurrency( total_en_pantalla ) );

	
		//$(this).addClass("text-success");


	total_en_pantalla_tarj = total_en_pantalla * porcentaje_tarjeta;
	
	if(OrderType == "order") { 
		$("#myModalHome").modal("show");
	} else { 
		$("#myModal").modal("show");
	}
});

$("body").on("keyup" , "#mobile_number", function(e) {
	var phone = $("#mobile_number").val();
	if(phone.length < 7) { 
		return false;
	}
	
  $.getJSON("findcustomer?phone=" + $("#mobile_number").val(),
        function(data) {
			if(data) { 
				$("#full_name").val(data['name']);
				//$("#phone").val(data['mobile_number']);
				$("#address_c").val(data['address']);
				$("#neighborhood").val(data['neighborhood']);
				$("#comments_c").val(data['comments']);
				$("#id").val(data['id']);
				$("#Client").html("@lang('pos.is_former_client')");
			} else { 
				$("#Client").html("@lang('pos.is_new_client')");
				$("#id").val("");
			}
			
        });
});



$("body").on("click",".deleteHoldOrder", function() {
				$(this).parent(".holdt").remove();
				
				var form_dataa = {
					id:$(this).attr("data-id")
				}
				$.ajax( {
					type: 'POST',
					headers: {
						'X-CSRF-TOKEN': $( 'meta[name="csrf-token"]' ).attr( 'content' )
					},
					url: '<?php echo url("sale/hold_order_remove"); ?>',
					data: form_dataa,
					success: function ( msg ) {
						
					}
				});
				
});


</script>

<div class="modal inmodal" id="myModalHome" tabindex="-1" role="dialog" aria-hidden="true">

	<div class="modal-dialog">

		<div class="modal-content animated bounceInRight confirm-modal">

			<div class="modal-header">
				<h4 class="modal-title" id="total_amount_model">@lang('pos.customer_information')</h4>
			</div>

			<div class="modal-body clearfix">
			
				<div class="col-sm-12">
					<div class="form-group">
						<input type="text" id="mobile_number" placeholder="@lang('pos.mobile_number')" class="form-control numberPad">
					</div>
				</div>
				
				<div class="col-sm-12">
					<div class="form-group">
						<?php /*h3 id="Client" style="text-align:center">Is a new client/is a former client</h3 */ ?>
						<h3 id="Client" style="text-align:center">Es cliente nuevo o existente.</h3>
					</div>
				</div>
				
				
				<div class="col-sm-6">
					<div class="form-group">
						<input type="text" id="full_name" placeholder="@lang('pos.full_name')" class="form-control ">
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<input type="text" id="address_c" placeholder="@lang('pos.address')" class="form-control">
					</div>
				</div>
				
				<div class="col-sm-12">
					<div class="form-group">
						<input type="text" id="comments_c" placeholder="@lang('pos.comment')" class="form-control">
					</div>
				</div>
				
				
				
	             <div class="col-sm-12 ">
					<span id="errorMessage" style="color:red"> </span>
				</div>

				<div class="col-sm-12 text-right">
					<button type="button" id="ClearForm" class="btn btn-white" >@lang('pos.close')</button>

					<button type="button" id="CustomerNext" class="btn btn-primary">@lang('pos.Next')</button>
					<span id="errorMessage" style="color:red"> </span>
				</div>
				
				

			</div>

		</div>

	</div>

</div>

<script type="text/javascript"> 
$("body").on("click","#ClearForm", function() {
	$("#full_name").val("");
	$("#neighborhood").val("");
	$("#address_c").val("");
	$("#comments_c").val("");
	$("#id").val("");
	$("#mobile_number").val("");
	$("#myModalHome").modal("hide");
});
$("body").on("click","#CustomerNext", function() {
	var form_data = {
		name:$("#full_name").val(),
		phone:$("#mobile_number").val(),
		neighborhood:$("#neighborhood").val(),
		address:$("#address_c").val(),
		comments:$("#comments_c").val(),
		id:$("#id").val()
	}
	
	if($("#mobile_number").val() == "" || $("#full_name").val() == "") { 
		$("#errorMessage").html("@lang('pos.required')");
		return false;
	}
	
	
					$.ajax({
						type: 'POST',
						headers: {
							'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						},
						url: '<?php echo url("sales/store_customer"); ?>',
						data: form_data,
						success: function (msg) {
							var obj = $.parseJSON(msg);
							if(obj['message'] == "OK") { 
								$("#myModalHome").modal("hide");
								$("#myModal").modal("show");
								$("#customer_id").val(obj['id']);
							} else { 
								
							}
						}
					});
					
});
</script>



<link rel="stylesheet" href="{{url('assets/numpad/jquery.numpad.css')}}">

<script src="{{url('assets/js/lodash.min.js')}}"></script>

<script src="{{url('assets/numpad/jquery.numpad.js')}}"></script>

<style type="text/css">

	.nmpd-grid {

		border: none;

		padding: 20px;

	}

	

	.nmpd-grid>tbody>tr>td {

		border: none;

	}

	/* Some custom styling for Bootstrap */

	

	.qtyInput {

		display: block;

		width: 100%;

		padding: 6px 12px;

		color: #555;

		background-color: white;

		border: 1px solid #ccc;

		border-radius: 4px;

		-webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);

		box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);

		-webkit-transition: border-color ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;

		-o-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;

		transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;

	}

</style>

<form action="{{ route('newsale.create') }}" 
 method="GET" 
id="formVentas" name="formVentas" >
</form>



<script>



$("body").on("click",".payment-option-icon", function() {
		$(".payment-option-icon").removeClass("text-success");
		$(this).addClass("text-success");
		//$("#payment_type").val($(this).attr("data-id")); ///   comentada, edited
		{{-- Modificar --}}
		
		const __payment_with = $(this).attr("data-id");
		$("#payment_type").val(__payment_with);
		
		if ("Card" === __payment_with){
			//__console.log("La Comisión es de 3% sobre el total de la venta.");
			//var sub_tot_con_tarjeta = total_en_pantalla * porcentaje_tarjeta;
//			$( "#total_amount_model" ).html("<?php echo $currency; ?>" + sub_tot_con_tarjeta.toFixed( 2 ) );			
			//$( "#total_amount_model" ).html("<?php echo $currency; ?>" + total_en_pantalla_tarj.toFixed( 2 ) );
			$( "#total_amount_model" ).html("" + formatCurrency( total_en_pantalla_tarj.toFixed( 2 ) ) );

{{--  temporal  --}}
			$("#total_given").val(total_en_pantalla_tarj.toFixed( 2 ));
			$("#change").val(0.00);
			
		} 
		// temporal
		else {
			$( "#total_amount_model" ).html("" + formatCurrency( total_en_pantalla.toFixed( 2 ) ) ); 

			{{--  temporal  --}}
			$("#total_given").val(total_en_pantalla_tarj.toFixed( 2 ));
			$("#change").val(0.00);

		}
		
		{{--  --}}
		//total_en_pantalla = total_amount;
			//$( "#total_amount_model" ).html("<?php echo $currency; ?>" + total_amount.toFixed( 2 ) );			
//---		
	});
	
	
	$( function () {

		$( ".navbar-minimalize" ).click();

	} );

	$.fn.numpad.defaults.gridTpl = '<table class="table modal-content"></table>';

	$.fn.numpad.defaults.backgroundTpl = '<div class="modal-backdrop in"></div>';

	$.fn.numpad.defaults.displayTpl = '<input type="text" class="form-control" />';

	$.fn.numpad.defaults.buttonNumberTpl = '<button type="button" class="btn btn-default"></button>';

	$.fn.numpad.defaults.buttonFunctionTpl = '<button type="button" class="btn" style="width: 100%;"></button>';

	$.fn.numpad.defaults.onKeypadCreate = function () {

		$( this ).find( '.done' ).addClass( 'btn-primary' );

	};
	$( document ).ready( function () {
		//$( '.numberPadkk' ).numpad();
		$( '.numberPad22' ).numpad();
	} );

	$( "body" ).on( "keyup", "#total_given", function () {
		{{--    Funcionando, pero se Cambia la manera de Cobro y Cambio
		var total_amount = $( "#total_amount" ).val();
		var total_given = $( this ).val();
		//---modif
		
		var change = Number( total_given ) - Number( total_amount );
		$( "#change" ).val( change.toFixed( 2 ) );
		--}}
		
		const __payment_with = $( "#payment_type" ).val();
		if ("Card" === __payment_with){
			$( "#change" ).val( 0.00 );
		} else {
			var total_amount = total_en_pantalla/*$( "#total_amount" ).val()*/;
			var total_given = $( this ).val();
			//---modif
			
			var change = Number( total_given ) - Number( total_amount );
			$( "#change" ).val( change.toFixed( 2 ) );
		}
		//---
	} );
	
	
	toastr.options = {
		"closeButton": true,
		"debug": false,
		"progressBar": true,
		"preventDuplicates": false,
		"positionClass": "toast-top-right",
		"onclick": null,
		"showDuration": "400",
		"hideDuration": "1000",
		"timeOut": "2000",
		"extendedTimeOut": "1000",
		"showEasing": "swing",
		"hideEasing": "linear",
		"showMethod": "fadeIn",
		"hideMethod": "fadeOut"
	}

	var products = new Array();

	var count_items = 0;
	var cart = new Array();
	
	
	{{--  New code   --}}
	/* on( "click", ".AddToCart", function () { */
	$( "body" ).on( "click", ".AddOpenToCart", function () {
	    
	    
	    $( "#txtConcepto" ).val("");
		$( "#txtTipo" ).val("");
		$( "#txtPrecio" ).val("");
		$( "#txtCantidad" ).val("");
	    
	    

		$("#modalOpenProduct").modal("show");
		//AddToCart
		//AddOpenToCart
		
		
		/*count_items++;

		var ids = _.map( cart, 'id' );

		var item = {
			id: $( this ).attr( "data-id" ) + $( this ).attr( "data-key" ),
			product_id: $( this ).attr( "data-id" ),
			price: $( this ).attr( "data-price" ),
			size: $( this ).attr( "data-size" ),
			name: $( this ).attr( "data-name" )
		};

		if ( !_.includes( ids, item.id ) ) {
			item.quantity = 1;
			item.p_qty = 1;
			cart.push( item );
		} else {
			var index = _.findIndex( cart, item );
			cart[ index ].quantity = cart[ index ].quantity + 1
		}*/

		/*toastr.success( 'Successfully Added to Cart' )*/
		//toastr.success( 'Agregar producto Personalizado...' );
		toastr.info( 'Configura tu producto Para la Venta.' );
		
		//show_cart();
		//---
	});
	
	
	function generateRandomInt(max){
		return Math.floor(Math.random() * max);
	}
	
	
	//** tempnam
	/*var returnVal = true;
		 
		 
		let valConcepto = $( "#txtConcepto" ).val().trim();
		let valTipo = $( "#txtTipo" ).val().trim();
		let valPrecio = $( "#txtPrecio" ).val().trim();
		let valCantidad = $( "#txtCantidad" ).val().trim();
	*/	
		
	function validateAddProducto(){
		var returnVal = true;
		 
		 
		const valConcepto = $( "#txtConcepto" ).val().trim();
		const valTipo = $( "#txtTipo" ).val().trim();
		const valPrecio = $( "#txtPrecio" ).val().trim();
		const valCantidad = $( "#txtCantidad" ).val().trim();
		
		
		/*
		valConcepto = $( "#txtConcepto" ).val().trim();
		 valTipo = $( "#txtTipo" ).val().trim();
		 valPrecio = $( "#txtPrecio" ).val().trim();
		 valCantidad = $( "#txtCantidad" ).val().trim();
		*/ 
		
		
		// alter fast valid 
		
		//console.log(isEmpty(valConcepto) + " valConcepto " + valConcepto);
		//console.log(isEmpty(valCantidad) + " valCantidad " + valCantidad);
		
		
		//cons
		
		
		//returnVal = false;
		/*returnVal = (!isEmpty(valConcepto) && !isEmpty(valTipo) 
		&& !isEmpty(valPrecio) && !isEmpty(valCantidad));
		*/
		
		returnVal = !(isEmpty_String(valConcepto) && isEmpty_String(valTipo) 
		&& isEmpty_String(valPrecio) && isEmpty_String(valCantidad));
		// ( isEmpty_String("") && isEmpty_String("") 		&& isEmpty_String("") && isEmpty_String(""))__ok
		
		
		// $("#lblErrorCustoP").html('<p>Error, verifica los siguientes campos</p>'); 
		
		if (!returnVal){
			$("#lblErrorCustoP").html('<p>Error, verifica los siguientes campos</p>'); 
			console.log ("si hay campos vacios...");
			return returnVal;
		}
		
		
		let textosP = "";
		
		if (valConcepto.length <3){
			textosP += "<p>El Nombre del Producto es Corto.</p>";
		}
		
		if (valTipo.length <3){
			textosP += "<p>El Nombre de tipo es Corto.</p>";
		}
		//--
		
		
		if (valPrecio.length > 0){
			if (isNaN(valPrecio)){
				textosP += "<p>Verifica el precio. </p>";
			} else if (valPrecio <= 0){
				textosP += "<p>Verifica el precio. Debe ser mayor a Cero.</p>";
			}			
		} else {
			textosP += "<p>Agrega el precio.</p>";
		}
		
		if (valCantidad.length > 0){
			if (isNaN(valCantidad)){
				textosP += "<p>Verifica la Cantidad. Valores Numéricos.</p>";
			} else if (valCantidad <= 0){
				textosP += "<p>Verifica la Cantidad. Debe ser mayor a Cero.</p>";
			}			
		} else {
			textosP += "<p>Agrega la Cantidad.</p>";
		}
		
		
		
		
		//--
		$("#lblErrorCustoP").html(textosP);
		
		returnVal = isEmpty_String(textosP);
		console.log ("Errores.");
		
		
		return returnVal;

		//---
	}
	
	
	function addCustomProdToCart(){
//		 console.log("addCustomProdToCart");

		//validateAddProducto();
		$("#lblErrorCustoP").html(''); 

		
		if (!validateAddProducto()){
			return false;
		}
		
		//--- Tomar Valores
		let valConcepto = $( "#txtConcepto" ).val().trim();
		let valTipo = $( "#txtTipo" ).val().trim();
		let valPrecio = $( "#txtPrecio" ).val().trim();
		let valCantidad = $( "#txtCantidad" ).val().trim();
		
		// random producto:
		
		let _product_id = "" + (generateRandomInt(100) + 999);
		
		
		var ids = _.map( cart, 'id' );

		var item = {
			//_id: $( this ).attr( "data-id" ) + $( this ).attr( "data-key" ),
			//id: "459" + "88",
			id: _product_id  + "" + generateRandomInt(99) ,
			//product_id: "459",
			product_id: _product_id,
			// new property
			customize: 'open', 
			
			price: parseFloat(valPrecio),
			size: valTipo,
			name: valConcepto
		};

		// confirm when add same product or distint_
		if ( !_.includes( ids, item.id ) ) {
			//item.quantity = valCantidad;    _ prevent string + integer
			item.quantity = parseInt(valCantidad);
			item.p_qty = 1;
			cart.push( item );
		} else {
			var index = _.findIndex( cart, item );
			cart[ index ].quantity = cart[ index ].quantity + 1
		}
		
		
		toastr.info( 'Producto Creado y Agregado para la Venta.' );
		
		show_cart();
		
		$("#modalOpenProduct").modal("hide");
		
		//---
	}
	
		
	$( "body" ).on( "click", "#addCustomProd", addCustomProdToCart);
	
	
	{{--  debug the cart   --}}
	
	function muestraCart(){
		
	}
	
	
	
	
	
	
	{{--  estandar code --}}
	$( "body" ).on( "click", ".AddToCart", function () {

		count_items++;

		var ids = _.map( cart, 'id' );

		var item = {
			// tmp:
			customize: 'original',
			id: $( this ).attr( "data-id" ) + $( this ).attr( "data-key" ),
			product_id: $( this ).attr( "data-id" ),
			price: $( this ).attr( "data-price" ),
			size: $( this ).attr( "data-size" ),
			name: $( this ).attr( "data-name" )
		};

		if ( !_.includes( ids, item.id ) ) {
			item.quantity = 1;
			item.p_qty = 1;
			cart.push( item );
		} else {
			var index = _.findIndex( cart, item );
			cart[ index ].quantity = cart[ index ].quantity + 1;
		}

		/*toastr.success( 'Successfully Added to Cart' )*/
		toastr.success( 'Agregado al Pedido' );
		
		show_cart();	
		});
		
		
		//---
		function cancelarVenta(argumento){
			//   argumento  cancel
			if (argumento === true){
				$("#TableNo").text("");
				$("#TableNoCart").text("");
					
				//var cart = [];   // ambito variable mal aplicado_
				cart = [];
				$( ".TotalAmount" ).html( 0 );

				$( "#CartHTML" ).html( "" );
			
				$( "#p_subtotal" ).html( "0.00" );

				$( "#p_hst" ).html( "0.00" );

				$( "#p_discount" ).html( "0.00" );
				
				//tipo_propina = "ninguna";
				// tipResult = 0.00;
				resetModalTipping();
				
				$( "#filaPropinas" ).hide();
				$( "#p_propinas" ).html( "0.00" );
				
				 total_en_pantalla/*_efe*/ = 0.00;
				 porcentaje_tarjeta = 1.00;
				 total_en_pantalla_tarj = 0.0;
				 
				 
				 //--- agregado
				 $( "#filaCobro" ).hide();
				 $( "#filaEspera" ).show();
				
			}
		}
		
		
		//---
		$( "body" ).on( "click", "#ClearCart", function () {
			
			if ( cart.length < 1 ) {
				swal( "!Sin productos¡", "No hay nada para cancelar.", "warning" );
				return false;
			}
			
			//---
			swal({
			  title: 'Se va a Borrar el pedido',
			  text: "¿Desea cancelar esta venta.?", {{--  No se podra revertir,  --}}
			  type: 'warning',
			  showCancelButton: true,
			  confirmButtonColor: '#3085d6',
			  cancelButtonColor: '#d33',
			  confirmButtonText: 'Si, Cancelar Venta',
			  cancelButtonText: 'No, Seguir Vendiendo!',
			  confirmButtonClass: 'btn btn-success',
			  cancelButtonClass: 'btn btn-danger',
			  /* buttonsStyling: false*/
			}).then(function (_any) {
				//console.log(_any);
				
				cancelarVenta(_any);
				 
			}, function (dismiss) {
			  // dismiss can be 'cancel', 'overlay',
			  // 'close', and 'timer'
			  /*if (dismiss === 'cancel') {
				swal(
				  'Cancelled',
				  'Your imaginary file is safe :)',
				  'error'
				)
			  }*/
			}); 
 
			
		} );
	
	
	$( "body" ).on( "click", ".DecreaseToCart", function () {
		var item = {
			id: $( this ).attr( "data-id" )
		};
		var index = _.findIndex( cart, item );

		if ( cart[ index ].quantity == 1 ) {
			deleteItemFromCart( item );
		} else {
			cart[ index ].quantity = cart[ index ].quantity - 1;
		}
		//console.log(cart[index].quantity);
		//toastr.success('Successfully Updated')       
		show_cart();

	} );

	$( "body" ).on( "click", ".IncreaseToCart", function () {
		var item = {
			id: $( this ).attr( "data-id" )
		};
		var index = _.findIndex( cart, item );
		cart[ index ].quantity = cart[ index ].quantity + 1;
		show_cart();

	} );

	$( "body" ).on( "click", ".DeleteItem", function () {
		var item = {
			id: $( this ).attr( "data-id" )
		};

		deleteItemFromCart( item );
	} );

	$( "body" ).on( "click", ".DiscountItem", function () {

	} );

	function deleteItemFromCart( item ) {
		var index = _.findIndex( cart, item );
		cart.splice( index, 1 );
		show_cart();
	}

	$( "body" ).on( "click", "#completeOrder", function () {
		<?php  //edit 2022-01-27
		// En si, la sección de Checkout no deberia de mandar aquí hasta haber productos.
	
	?>	
		if ( cart.length < 1 ) {

			$( "#myModal" ).modal( "hide" );
			swal( "!Sin productos¡", "Agrega un producto para concretar la venta.", "error" );
			return false;
		}







		//---   segundo filtro por si el otro falla.
		let str_fecha = $("#porfecha").val();
		let _numFol = $('#numComanda').val().trim();
		
		if ( str_fecha !== __today && _numFol.length == 0 ){

			$( "#myModal" ).modal( "hide" );
			swal( "Folio Sin asignar", "Para procesar comandas de otras fechas, se debe agregar el folio de tu comanda escrita.", "error" );
			return false;
		}
	
		// verify 
		// reemplasazo por total_en_pantalla  const __total_amount = Number($( "#total_amount" ).val());
		const txt__total_given = $( "#total_given" ).val().trim();
		
		// cambiado a var
		//const __total_given = Number($( "#total_given" ).val());
		var __total_given = Number($( "#total_given" ).val());
		var __give_Change = $( "#change" ).val();
		 
		
		const __payment_with = $( "#payment_type" ).val();
		//--  change.toFixed( 2 ) 
		var  totalCardPay = __total_given * 1.00;
		
		<?php //Según el pago, verificar Pago segun el tipo. ?>
		
		///  payment_with: $( "#payment_type" ).val(),   "Cash"    "Card"
		if ("Cash" === __payment_with){
			if (txt__total_given.length == 0){
				toastr.error( 'No se ha ingresado la cantidad a cobrar' );
				return false;
			}
			
			// __total_given >= __total_amount
			if (__total_given >= total_en_pantalla){
				// -- se sigue el proceso.
			} else {
				toastr.error( 'La Cantidad ingresada es menor al Total.' );
				return false;
			}
		}
		
		//let puntos_acumulados = __total_amount * 0.10;
		let puntos_acumulados = total_en_pantalla * 0.10;
		 
		//   porcenatje Tarjeta
		var __comisionTarjeta = 0.0;
		if ("Card" === __payment_with){
			//---  Momentaneo
			if (txt__total_given.length == 0){
				toastr.error( 'No se ha ingresado la cantidad a cobrar' );
				return false;
			}
			
			// var pre_tot_card = total_en_pantalla * 1.00;
			
			//let __cobre_con_tarjeta = total_en_pantalla_tarj.toFixed(2);
			let __cobre_con_tarjeta = parseFloat(total_en_pantalla_tarj.toFixed(2));
			
			
			//if (__total_given >= total_en_pantalla_tarj){
			if (__total_given >= __cobre_con_tarjeta){
				console.log("given: " + __total_given + " ,  se cobró: " + __cobre_con_tarjeta );
				// -- se sigue el proceso.
				/// se ignra total give, 
				// porque el total debe  ser igual al de l atarjeta.
				
			} else {
				toastr.error( 'La Cantidad ingresada es menor al Total.' );
				return false;
			}
			
			__comisionTarjeta = 1.00;
			
			///pendiente
			//return false;
			// console.log();
			//toastr.info( 'Se Realiza un cobro de comisión de 3% sobre el Total.' );
			// toastr.info( 'Se Realiza un cobro de comisión de 3% sobre el Total.' );
			
			// Nuevo total 
			//__total_given = totalCardPay.toFixed( 2 );
			__give_Change = 0.00;
			
		}
		
		
		//---
		
		// payment_with: $( "#payment_type" ).val(),
		 
		
		$("#TableNo").text("");
		
		var status = 1;
		// if($("#OrderType").val() == "order") { 
		// status = 2;
		// }
		var form_data = {

			newfecha: $("#porfecha").val(),
			/*NumComanda: $("#numComanda").val(),*/
			NumComanda: $("#numComanda").val().trim(),




			comments: $( "#comments" ).val(),
			customer_id: $( "#customer_id" ).val(),
			//???
			/*discount: $( "#discount" ).val(),*/
			discount: 0.00,
			cashier_id: $( "#cashier_id" ).val(),
			//_payment_with: $( "#payment_type" ).val(),
			payment_with: __payment_with, 
			type: $( "#OrderType" ).val(),
			/*agregado*/
			//__comisionTarjeta
			comisionTarjeta: __comisionTarjeta,
			status:status,
			/*total_given: $( "#total_given" ).val(),*/
			total_given: __total_given, 
			/*change: $( "#change" ).val(),*/
			change: __give_Change,
			operacion: Date.now(),
			puntosAcumulados: puntos_acumulados,
			//---
			type_of_tip: tipo_propina,
			value_tip: tipResult, 
			table_id: $("#table_id").val(),

			//---
			//comTDC: co
			
			
			vat: $( "#vat" ).val(),
			delivery_cost: $( "#delivery_cost" ).val(),
			customer_id: $( "#customer_id" ).val(),
			items: _.map( cart, function ( cart ) {
				return {
					//
					is_customize: cart.customize,
					cnombre: cart.name,
					//
					product_id: cart.product_id,
					size: cart.size,
					quantity: cart.quantity,
					price: cart.price
				}
			} )
		};
		
		//---
		console.log("Ticked con Total " + 	total_en_pantalla );
		console.log("Ticked con __total_given  " + 	__total_given );
		
		console.log(form_data);
		{{-- para debug  --}}
		/*if (true){
			
			return false;
		}*/
		
		
		var total_amount = Number( localStorage.getItem( "total_amount" ) );
		_.map( cart, function ( cart ) {
			localStorage.setItem( "total_amount", total_amount + ( cart.quantity * cart.price ) );
		} );

		$( "#completeOrder" ).html( '<i class="fa fa-spinner fa-spin" style="font-size:18px"></i>' );
		$( "#completeOrder" ).prop( "disabled", true );

		$.ajax( {
			type: 'POST',
			headers: {
				'X-CSRF-TOKEN': $( 'meta[name="csrf-token"]' ).attr( 'content' )
			},
			url: '<?php echo url("sales/complete_sale"); ?>',
			data: form_data,
			success: function ( msg ) {
				
				console.log(msg);
				
				//$( "#completeOrder" ).prop( "disabled", false );
				$( "#completeOrder" ).html( '@lang('pos.complete_order')' );
				$( "#completeOrder" ).prop( "disabled", false );
				
				/*
				if (true) {
					return false;
				}
				*/
			
			
			
			
				{{--  Continue code --}}
				$( "#myModal" ).modal( "hide" );
				cart = [];
				$( "#total_given" ).val( "" );
				$( "#change" ).val( "" );
				$( "#comments" ).val( "" );
				$( "#total_amount_model" ).html( "0.00" );
				$( "#completeOrder" ).html( '@lang('pos.complete_order')' );
				$( "#completeOrder" ).prop( "disabled", false );
				
				$("#full_name").val("");
				$("#address_c").val("");
				$("#neighborhood").val("");
				$("#comments_c").val("");
				$("#id").val("");

				var form_dataa = {
					id:$("#holdOrderID").val()
				}

				$("#holdOrderID").val("");
				$.ajax( {
					type: 'POST',
					headers: {
						'X-CSRF-TOKEN': $( 'meta[name="csrf-token"]' ).attr( 'content' )
					},
					url: '<?php echo url("sale/hold_order_remove"); ?>',
					data: form_dataa,
					success: function ( msg ) {
						
					}
				});




// parte alert
swal({
		title: 'Venta Finalizada.',
		text: "¿Desea Visualizar el ticket " + msg.numTicket + " para imprimir?", 
		type: 'info',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Si, imprimir',
		cancelButtonText: 'No, Seguir Vendiendo!',
		confirmButtonClass: 'btn btn-success',
		cancelButtonClass: 'btn btn-danger',
		/* buttonsStyling: false*/
	}).then(function (_any) {

		$("#formVentas").attr('action', msg.url);


		$("#formVentas").submit();
			
	}, function (dismiss) {
		// dismiss can be 'cancel', 'overlay',
		// 'close', and 'timer'
		$("#formVentas").submit(); 
}); 
// end _parte alert

 
				// $("#formVentas").submit();
				{{--   Solo por si no se mueve a otra pantalla --}}
				/* setTimeout( function () {
					$("#formVentas").submit();
				}, 1500 );

				*/
				
				
 


				$( "#p_subtotal" ).html( "0.00" );

				$( "#p_hst" ).html( "0.00" );

				$( "#p_discount" ).html( "0.00" );
				
				resetModalTipping();
				
				
				
				show_cart();
			}
		} );
	} );

	$("body").on("change" , "#VatInclude" , function() { 
		show_cart();
	});
	
	{{--  Master  --}}
	function show_cart() {
		if ( cart.length > 0 ) {
			//--
			var __propinas = 0;
			//---
			
			var qty = 0;
			var total = 0;
			var cart_html = "";
			var obj = cart;
			$.each( obj, function ( key, value ) {
				cart_html += '<tr>';
				cart_html += '<td width="10" valign="top"><a href="javascript:void(0)" class="text-danger DeleteItem" data-id=' + value.id + '><i class="fa fa-trash"></i></a></td>';
				cart_html += '<td><h4 style="margin:0px;">' + value.name + '</h4></td>';
				cart_html += '<td width="80"><span class="btn btn-primary btn-sm text-center IncreaseToCart" data-id=' + value.id + '>+</span> ' + value.quantity + ' <span  class="btn btn-primary btn-sm DecreaseToCart" data-id=' + value.id + '>-</span> </td>';
				cart_html += '<td width="15%" class="text-right"><h4 style="margin:0px;"> <?php echo $currency; ?>' + value.price + '</h4> </td>';
				cart_html += '</tr>';
				qty = Number( value.quantity );
				total = Number( total ) + Number( value.price * qty );
			} );

			var VatInclude = $("#VatInclude").val();
			var vat = 0;
			if(VatInclude == "Yes") { 
				vat = ( Number( total ) * <?php echo setting_by_key("vat"); ?> ) / 100;
			}
			

			//$( "#p_subtotal" ).html( "<?php echo $currency; ?>" + total.toFixed( 2 ) );formatCurrency
			$( "#p_subtotal" ).html( formatCurrency( total ) );

			$( "#p_hst" ).html( "<?php echo $currency; ?>" + vat.toFixed( 2 ) );
			//// Discount 

			
			
			
			var discount = 0;

			// if ( Number( count_items ) >= 2 ) {

				// discount = <?php echo setting_by_key('discount'); ?>;

			// }
			$( "#discount" ).val( discount );

			// $( "#p_discount" ).html( "<?php echo $currency; ?>" + discount.toFixed( 2 ) );
			// cart_html += '<div class="panel-footer"> Total Items' ;
			// cart_html += '<span class="pull-right"> ' + qty ;
			// cart_html += '</span></div>' ;
			
			// pretotal
			
			
			pre_total_pantalla = total + 0;   //- el cero es de un descuneto.
			
			
			
			
			
			//- calculo de proprinas
			//agregar_propina = 
			//  arirba var __propinas = 0;
			//---tipResult = 0.0;
			$( "#filaPropinas" ).hide();
			if ("ninguna" === tipo_propina){
				tipResult = 0.0;
			} else if (tipo_propina === "manual") {
				//tipResult = 0.0;  ya agregada
				//__propinas = agregar_propina;
				$( "#filaPropinas" ).show(500);
			} else {
				//__propinas = calcule_tipping(total, tipo_propina);
				tipResult = calcule_tipping(total, tipo_propina);
				$( "#filaPropinas" ).show(500);
			}
			
				
			

			//---var total_amount = Number( total ) + vat;
			var total_amount = Number( total ) + Number( tipResult.toFixed(2) );
			//--
			
			
			
			$( "#total_amount" ).val( formatCurrency(total_amount) /*total_amount */);
			total_en_pantalla = total_amount;
			$( "#total_amount_model" ).html("<?php echo $currency; ?>" + total_amount.toFixed( 2 ) );			
			$( "#vat" ).val( vat );
			
			//$( "#p_propinas" ).html( "<?php echo $currency; ?>" + __propinas.toFixed( 2 ) );
			$( "#p_propinas" ).html("" + formatCurrency(tipResult.toFixed( 2 )) );
			
			//$( "#p_subtotal" ).html( "<?php echo $currency; ?>" + total.toFixed( 2 ) );

			//$( ".TotalAmount" ).html( "<?php echo $currency; ?>" + total_amount.toFixed( 2 ) );
			$( ".TotalAmount" ).html( "" + formatCurrency(total_amount.toFixed( 2 )) );
			
			$( "#CartHTML" ).html( "" );
			$( "#CartHTML" ).html( cart_html );
			
			
			//--- added 
			$( "#filaCobro" ).show();
			$( "#filaEspera" ).hide();
			
		} else {
				$( ".TotalAmount" ).html( 0 );
				total_en_pantalla = 0.00;
				$( "#p_subtotal" ).html( "0.00" );
				//---
				tipo_propina = "ninguna";
				tipResult = 0.00;
				$( "#filaPropinas" ).hide();
				$( "#p_propinas" ).html( "0.00" );
				
				
				$( "#total_amount_model" ).html( "0.00" );
				$( "#p_hst" ).html( "0.00" );
				$( "#CartHTML" ).html( "" );
				
				//---
				$( "#filaCobro" ).hide();
				$( "#filaEspera" ).show();
		}
		
		

	}

</script>

<style>

	.cart-item {

		max-height: 160px;

		overflow-y: scroll;

	}

	

	.scale-anm {

		transform: scale(1);

	}

	

	.tile {

		-webkit-transform: scale(0);

		transform: scale(0);

		-webkit-transition: all 350ms ease;

		transition: all 350ms ease;

	}

	

	.tile:hover {}

	

	.product_list {

		min-height: 240px !important;

		margin-top: 0px;

	}

	.product_list h2 {

		padding: 2px 8px;

		margin-bottom: 8px !important;

		text-align: left;
	}


	{{-- nu,ber  --}}
	/* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
}


</style>

<script>

	$( "body" ).on( "click", ".close", function () {
		alert( "close" );
	} );
	$( function () {
		var selectedClass = "";
		$( ".fil-cat" ).click( function () {
			selectedClass = $( this ).attr( "data-rel" );
			$( "#portfolio" ).fadeTo( 100, 0.1 );
			$( "#portfolio > div" ).not( "." + selectedClass ).fadeOut().removeClass( 'scale-anm' );
			setTimeout( function () {
				$( "." + selectedClass ).fadeIn().addClass( 'scale-anm' );
				$( "#portfolio" ).fadeTo( 300, 1 );
			}, 300 );

		} );
	} );
	
	/**   **/
	 
	$(document).ready(function(){
		$( "#filaCobro" ).hide();
		$( "#filaEspera" ).show();
		
		
		console.log('este');
		//---
	});
	

</script>

{{-- Sección Fecha del calendar    --}}


<script>
/*  Limit dates    */
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
    //$('#porfecha').attr('value', '{ { $fecha1 }}');

    /*  Limit dates    */



	var now = new Date();

	var day = ("0" + now.getDate()).slice(-2);
	var month = ("0" + (now.getMonth() + 1)).slice(-2);

	var today = now.getFullYear()+"-"+(month)+"-"+(day) ;
	var __today = now.getFullYear()+"-"+(month)+"-"+(day) ;
	
	var attachFile = false;
	

	$(function(){
	{{-- Pre Loading  --}}
	
	//_$("#_fecha").val(today);
  		$('#porfecha').attr('value', today);
	
	});


</script>





@endsection
