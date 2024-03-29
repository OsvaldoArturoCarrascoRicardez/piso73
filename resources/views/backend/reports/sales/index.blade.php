@extends('layouts.app')

@section('content')
<?php $input['date_range'] = !empty($input['date_range']) ? $input['date_range'] : null; 
$currency =  setting_by_key("currency");
?>
<link href="{{url('assets/css/plugins/datapicker/datepicker3.css')}}" rel="stylesheet">

 <link href="{{url('assets/css/plugins/daterangepicker/daterangepicker-bs3.css')}}" rel="stylesheet">

<div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-8">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>@lang('reports.sales')</h5>
                        
                    </div>
                    <div class="ibox-content">

                        <table class="table">
                            <thead>
                           <tr>
                            <th>Nota</th>
                            <th>Fecha</th>
							<th>@lang('reports.amount')</th>
                            <th>@lang('reports.discount')</th>
							<th>prop</th>
                            <th>Com. Banc</th>
                            <th>@lang('reports.total_amount')</th>
                            <th></th>
                        </tr>
                            </thead>
                            <tbody>
							<?php $total_discount=0;$total_amount = 0; 
							
							$total_com_bank = 0.0;
							
							
							
							$suma_tipp = 0.0;
							
							?>
                    @if (!empty($sales))
						<?php 
					//---
					//echo var_dump($sales);
					
					?>
                        @forelse ($sales as $key => $sale)
                            <tr>
                                {{--
                                < td>
								@ if (!empty($sale->NumComanda))
									{{ $sale->NumComanda}}
								@ else
									{{ $key + 1 }}
								@ endif
								< /td>
								--}}
								<td>{{ $sale->id  }}</td>
								
								
								
								{{-- <td>{{ date('d F Y' , strtotime($sale->created_at)) }}</td> --}}
								<td>{{ formatFecha_d_Mes_a(strtotime($sale->created_at)) }}</td>
								<?php //_td>{{ formatFechaDMESA($sale->created_at->timestamp) Fails!!!}}</td ?>
							<td>{{$currency}} {{ $sale->amount}}</td>
                                <td>{{$currency}} {{ $sale->discount }}</td>
								<td>{{$currency}} {{ $sale->value_tip }}</td>
                                <!-- td>{{$currency}} {{ format_numero($sale->tax_card) }}</td -->
                                
                                
                                <td>
                                	@if ($sale->payment_with === 'card')
	                                	{{$currency}} {{ format_numero($sale->tax_card) }}
                                	@endif
                                </td>
                                <!-- td class='alnright'>{{$currency}} {{ $sale->amount }}</td -->
                                <td class='alnright'>
                                	{{--  Total + iva si aplica  --}}
                                	@if ($sale->payment_with === 'card')
	                                	{{$currency}} {{ format_numero( $sale->amount + $sale->tax_card) }}
	                                @else  	
	                                	{{$currency}} {{ format_numero( $sale->amount) }}
                                	@endif                                
                                </td>
                                <td>
                                    <a href="{{ url('reports/sales/' . $sale->id) }}" class="btn btn-primary btn-xs pull-right">@lang('common.show')</a>
                                </td>
                            </tr>
							
							<?php $total_amount += $sale->amount; ?>
							<?php $total_discount += $sale->discount; ?>
							
							<?php $total_com_bank += $sale->tax_card; ?>
							
							
							<?php $suma_tipp += $sale->value_tip; ?>
							
                        @empty
                            @include('backend.partials.table-blank-slate', ['colspan' => 5])
                        @endforelse
                    @endif
                    </tbody>
					
						<tr>
                                <th>{{count($sales)}}</th>
                                <td></td>
                                
							    <!-- th>{{$currency}} {{ $total_amount }}</th -->
							    <th>{{$currency}} {{ format_numero($total_amount) }}</th>
                                <th>{{$currency}} {{ $total_discount }}</th>
                                <th>{{$currency}} {{ format_numero($suma_tipp) }}</th>	
                                <th>{{$currency}} {{ format_numero($total_com_bank) }}</th>
                                <!-- th>{{$currency}} {{ format_numero($total_amount - $total_discount) }}</th -->
                                <!-- th class='alnright'>{{$currency}} {{ format_numero($total_amount - $total_discount + $total_com_bank) }}</th -->
								<th class='alnright'>{{$currency}} {{ format_numero( ($total_amount + $suma_tipp) - $total_discount + $total_com_bank) }}</th>
                                <th>  </th>
                                <!-- th> Total  : </th -->
                                <!-- th>{{$currency}} {{ format_numero($total_amount - $total_discount) }}</th -->
                               
                            </tr>
							
							
                        </table>

                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
            <div class="panel panel-default">
                 <div class="ibox-title">
                        <h5>@lang('reports.date_range')</h5>
                        
                    </div>
                 <div class="ibox-content">    
                    <form id="formSubmit" action="{{ url('reports/sales') }}" method="GET">
                        <div class="form-group">
                            <label for="price">@lang('reports.date_range')</label>
                            <select class="form-control" id="date-range" name="date_range">
                                <option>@lang('reports.select_date_range')</option>
                                <option value="today" {{ ($input['date_range'] == 'today') ? 'selected="selected"' : '' }}>@lang('reports.today')</option>
                                <option value="current_week" {{ ($input['date_range'] == 'current_week') ? 'selected="selected"' : '' }}>@lang('reports.this_week')</option>
                                <option value="current_month" {{ ($input['date_range'] == 'current_month') ? 'selected="selected"' : '' }}>@lang('reports.this_month')</option>
                                <option value="custom_date" {{ ($input['date_range'] == 'custom_date') ? 'selected="selected"' : '' }}>@lang('reports.custom_date')</option>
								
								
                            </select>
                        </div>
						
						<div class="form-group" id="data_5">
                                <label class="font-noraml">@lang('reports.range_select')</label>
                                <div class="input-daterange input-group" id="datepicker">
                                    <input type="text" class="input-sm form-control" name="start" value="{{(!empty($input['start']) and $input['start'] !=  '') ? $input['start'] : '' }}"  autocomplete="off"  />
                                    <span class="input-group-addon">to</span>
                                    <input type="text" class="input-sm form-control" name="end" value="{{ (!empty($input['end']) and $input['end'] !=  '') ? $input['end'] : '' }}" autocomplete="off"  />
                                </div>
                            </div>

                        

                        <div class="form-group">
						<input type="hidden" name="pdf" id="pdf" value="" />
                            <button type="submit" class="btn btn-primary">@lang('reports.search')</button>
							<a href="javascript:void(0);" class="btn btn-warning" id="DownloadPDF"> @lang('reports.download_pdf') </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
    </div>
</div>

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
	
			<script> 
			$('#data_5 .input-daterange').datepicker({
                keyboardNavigation: false,
                forceParse: false,
                autoclose: true
            });
			</script>
			
			
<style>
    .alnright { text-align: right; }
</style>


@endsection
