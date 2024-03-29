@extends('layouts.app')

@section('content')

    <!-- link href="assets/css/plugins/dataTables/datatables.min.css" rel="stylesheet" / -->
    <link href="{{ url('assets/css/plugins/dataTables/datatables.min.css') }}" rel="stylesheet"/>


    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>@lang('common.products') </h2>
            <ol class="breadcrumb">
                <li>
                    <a href="{{url('')}}">@lang('common.home')</a>
                </li>

                <li class="active">
                    <strong>@lang('common.products')</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-2">

        </div>
    </div>
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>@lang('common.products') </h5>
                        <div class="ibox-tools">
                            @permission('add_product')
                            <a href="{{ url('products/create') }}"
                               class="btn btn-primary btn-xs">@lang('common.add_new')</a>
                            @endpermission

                            <!-- a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a -->
                        </div>
                    </div>
                    <div class="ibox-content">

                        <div class="table-responsive">
                            {{--  <table class="table table-striped table-bordered table-hover" > --}}
                            <table class="table table-striped table-bordered table-hover dataTables-example">

                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>@lang('common.name')</th>
                                    <th>Foto</th>

                                    <th>Precio(s)</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $fila = 1;
                                ?>

                                @forelse ($products as $key => $product)
                                    <!-- tr class="gradeX" -->
                                    <tr>
                                            <?php /* td>{{ $products->firstItem() + $key }}</td  */ ?>
                                        <td>{{  $fila++ }}</td>
                                        <td>{{ $product->name }}</td>

                                        <td class="text-center">
                                            <!-- img width="100px" src="{{url('uploads/products/thumb/' .$product->id . '.jpg')}}" / -->
                                            <img id="canvas_{{ $fila }}"
                                                 src="{{ url('uploads/products/thumb/' .$product->id . '.jpg') }}"
                                                 onerror="this.src='{{ url('uploads/gallery/_default.jpg') }}'" alt=""
                                                 width="100px"/>
                                        </td>
                                        <!-- td>{{ $product->prices }}</td -->
                                        <td>
                                                <?php $prices = json_decode($product->prices); $titles = json_decode($product->titles); ?>
                                            @foreach($titles as $key=>$t)
                                                <!-- button data-price="{{$prices[$key]}}" data-id="{{$product->id}}" data-key="{{$key}}" data-size="{{$t}}" data-name="{{$product->name}} ({{$t}})" type="button" class="btn btn-sm btn-primary m-r-sm AddToCart tag-margin tag-btn">{{ $t }}</button -->
                                                <!-- span class="btn btn-sm btn-primary m-r-sm AddToCart tag-margin tag-btn"> {{$t}}  --_>  ${{$prices[$key]}} </span>
									<br / -->
                                                @empty($t)
                                                    &nbsp;
                                                @else
                                                    <span class="btn btn-sm btn-primary m-r-sm AddToCart tag-margin tag-btn"> {{$t}}  -->  ${{$prices[$key]}} </span>
                                                    <br/>
                                                @endempty
                                            @endforeach


                                        </td>
                                        <td> {{--
                                <form id="delete-product" action="{{ url('products/' . $product->id) }}" method="POST" class="form-inline">
                                    <input type="hidden" name="_method" value="delete">
                                    {{ csrf_field() }}
                                    <input type="submit" value="delete__" class="btn btn-danger btn-xs pull-right btn-delete">
                                </form> --}}
                                            <div class="btn-group">
                                                {{--<!-- button type="button" class="btn btn-default">Editar</button -->
                                                    @permission('edit_products')
                                                    <a href="#" class="btn btn-default" role="button">Editar</a>
                                                    @endpermission

                                                    --}}
                                                @permission('edit_products')
                                                <a href="{{ url('products/' . $product->id . '/edit') }}"
                                                   class="btn btn-default	" role="button">Editar</a>
                                                @endpermission
                                                <button type="button" class="btn btn-default dropdown-toggle"
                                                        data-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false"><span class="caret"></span> <span
                                                            class="sr-only">Toggle Dropdown</span></button>
                                                <ul class="dropdown-menu">

                                                    <li role="separator" class="divider"></li>

                                                    <li>
                                                        <form id="delete-product"
                                                              action="{{ url('products/' . $product->id) }}"
                                                              method="POST" class="form-inline">
                                                            <input type="hidden" name="_method" value="delete">
                                                            {{ csrf_field() }}
                                                            @permission('delete_products')
                                                            <input type="submit" value="@lang('common.delete')"
                                                                   class="btn btn-default"/>
                                                            @endpermission
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>

                                        </td>
                                    </tr>
                                @empty
                                    <!-- tr>
							<td colspan="5">
								  @lang('common.no_record_found')

                                    </td>
                                </tr -->
                                @endforelse

                                </tbody>


                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>


    {{--  Scrips  --}}
    <script src="{{ url('assets/js/plugins/dataTables/datatables.min.js') }}"></script>



    <script>
        $(document).ready(function () {
            $('.dataTables-example').DataTable({
                language: {
                    url: "{{ asset('assets/mx/es-mx.json') }}"
                },
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [],

                "order": [[1, "asc"]]
            });
        });
    </script>

@endsection
