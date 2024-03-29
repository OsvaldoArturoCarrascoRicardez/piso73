@extends('layouts.app')

@section('content')
<?php 
$currency =  setting_by_key("currency");


use Carbon\Carbon;
$currentDateTime = Carbon::now();
$newDateTime = Carbon::now()->subDays(5);


$carbonAhora= Carbon::now();
$menosUnaSemana = Carbon::now()->subDays(8);



?>
 <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Ventas</h5>
                        
                    </div>
                    <div class="ibox-content">

                        <table class="table">
                    <thead>
                        <tr>
                            <th>Nota:</th>
                            <th>Cliente</th>
                            <th>Fecha venta</th>
                            <?php /*<th>Descuento</th> */ ?>
                            <th>Total</th>
                            <th>Status</th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @if (!empty($sales))
                        @forelse ($sales as $key => $sale)
                            <tr>
                                {{-- < td>{ { $key + 1 } }</td >  --}}
                                <td>{{ $sale->id }}</td>
                                <td> {{--  ver que pex con los clientes --}} </td>
                                <!-- td>{ { $sale->customer['name'] } }</td -->
                                <?php  //_____td>{{ $sale->created_at->format('d F Y') }}</td ?>
								<?php  //td>{{ var_dump($sale->created_at->timestamp) }}</td ?>
								<td>
                                    @if ( isset($sale->created_at) )    
                                    {{ formatFechaDMESA($sale->created_at->timestamp) }}

                                    @endif
                                    {{--  Extraña incidencia en un registro  --}}



                                </td>
								<?php /*<td>{{$currency}} {{ $sale->discount }}</td>   */ ?>
                                
                                <td class="text-center"> $ {{ format_numero($sale->amount) }} </td>
									@if($sale->status == 1)  
								<td>
                                    <a href="javascript:void(0)" class="btn btn-primary btn-xs ">Completo</a>
                                </td>
									@else
								<td>
                                    <a href="javascript:void(0)" class="btn btn-danger btn-xs">Cancelado</a>
                                </td>
									@endif
								
								
                                

                                <td>
								    
								{{--   Revizar Cancelación...  --}}
                                    
                                    <!--  a target="_blank" href="{{ url('sales/receipt/' . $sale->id) }}" class="btn btn-primary btn-xs "> Agregar Comanda </a -->
                                    &nbsp;
                                    <a target="_blank" href="{{ url('sales/receipt/' . $sale->id) }}" class="btn btn-primary btn-xs pull-right">@lang('common.show')</a>
                                    
                                </td>
                                <td>
                                    @if ( isset($sale->created_at) )    
                                        @if( $sale->created_at->gt($menosUnaSemana)  &&  $sale->status == 1)
                                            <a href="{{ url('sales/cancel/' . $sale->id) }}" class="btn btn-danger btn-xs pull-right">Cancelar</a>
                                        @endif 
                                    @endif 

                                    {{--   @if( $sale->created_at->gt($menosUnaSemana)  &&  $sale->status == 1)
                                        <a href="{{ url('sales/cancel/' . $sale->id) }}" class="btn btn-danger btn-xs pull-right">Cancelar</a>
                                    @endif  --}}   
                                </td>
                                <td></td>



                            </tr>
                        @empty
                           <tr> 
						  <td colspan="5">
								 @lang('common.no_record_found')
									
                                </td>
								</tr>
                        @endforelse
                    @endif
                    </tbody>
                </table>
				{!! $sales->render() !!}

                    </div>
                </div>
            </div>
        </div>
		
		
    </div>


@endsection