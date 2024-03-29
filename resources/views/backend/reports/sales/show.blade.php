@extends('layouts.app')

@section('content')
<?php $currency =  setting_by_key("currency"); ?>
<div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2> </h2> {{-- Sale Invoice --}
                    <ol class="breadcrumb">
                        <li>
                            <a href="{{url('')}}">@lang('common.home')</a>
                        </li>
                     
                        <li class="active">
                            <strong>  </strong> {{--  Invoice   --}}
                        </li>
                    </ol>
                </div>
                <div class="col-lg-2">

                </div>
            </div>
			

<div class="wrapper wrapper-content animated fadeInRight">
                    <div class="ibox-content p-xl">
					<div class="row">
                                <div class="col-sm-6">
                                    <h5>...</h5>
                                    <address>
                                        <strong></strong>
										<br>
                                        <br>
                                        <abbr title="Phone">T:</abbr> {{$sale->phone}}<br>
					<abbr title="Email">{{--  E: --}}</abbr> 
                                    </address>
                                </div>

                                <div class="col-sm-6 text-right">
                                    <h4>Nota.</h4>
                                    <h4 class="text-navy"> {{ $sale->invoice_no }}</h4>
                                    @if (!empty($sale->NumComanda))
									    <p>Comanda Física: {{ $sale->NumComanda}}</p>
									    
								    @endif
                                    
                                    
                                    <p>
                                        <span><strong>Fecha :</strong> {{ $sale->created_at->format('d/m/Y') }}  {{ $sale->created_at->format('h:i A') }}  </span><br/>


                                    </p>
                                </div>
                            </div>
							
                            <div class="table-responsive m-t">
                                <table class="table invoice-table">
                                    <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>Cantidad</th>
                                        <th>Precio</th>
                                        <th>Importe </th>
                                       
                                    </tr>
                                    </thead>
                                    <tbody>
									@foreach($sale->items as $k=>$item)
                                    <tr>
									    <td>
										

                                        @if( $item->isOpen == true )
                                            {{ $item->nombreOpenProducto }}
                                        @else
                                            {{ $item->nombreLargo }}
                                        @endif



										
										</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{$currency}}{{$item->price}}</td>
                                        <td>{{$currency}}{{$item->quantity * $item->price}}</td>
                                    </tr>
									@endforeach
                                    
                                    </tbody>
                                </table>
                            </div><!-- /table-responsive -->

                            <table class="table invoice-total">
                                <tbody>
                                <tr>
                                    <td><strong>Sub Total :</strong></td>
                                    <td>{{$currency}}{{$sale->subtotal}}</td>
                                </tr>
								{{--
								 <tr>
                                    <td><strong>TAX :</strong></td>
                                    <td>{{$currency}}{{$sale->vat}}</td>
                                </tr>
								
								<tr>
                                    <td><strong>Delivery Cost :</strong></td>
                                    <td>{{$currency}}{{$sale->delivery_cost}}</td>
                                </tr>
								--}}
								
                                <tr>
                                    <td><strong>Descuento :</strong></td>
                                    <td>{{$currency}}{{$sale->discount}}</td>
                                </tr>
                                <tr>
                                    <td><strong>TOTAL :</strong></td>
                                    <td>{{$currency}}{{$sale->subtotal + $sale->vat + $sale->delivery_cost}}</td>
                                </tr>
                                </tbody>
                            </table>
							
                            <div class="well m-t"><strong>Comentarios</strong>
                               {{$sale->comment}}
                            </div>
                        </div>
                </div>
@endsection