<?php 

$usrPermiso = Auth::user();


?>

<nav class="navbar-default navbar-static-side" role="navigation">
        <div class="sidebar-collapse">
            <ul class="nav metismenu" id="side-menu">
                <li class="nav-header">
                    <div class="dropdown profile-element"> <span>
                            <img alt="image" width="110" class="" src="{{url('uploads/logo.jpg?')}}" /> <br>
                             </span>
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold">{{Auth::user()->name}}</strong>
                             </span> <span class="text-muted text-xs block">{{Auth::user()->role->display_name}} <b class="caret"></b></span> </span> </a>
                        <ul class="dropdown-menu animated fadeInRight m-t-xs">
                            <li><a href="{{url('settings/profile')}}">@lang('menu.profile')</a></li>
                            
                            <li><a href="{{ url('/logout') }}">@lang('menu.logout')</a></li>
                        </ul>
                    </div>
                    <div class="logo-element">
                        
                    </div>
                </li>
				{{--  @permission('dashboard')  --}} 
				 <li @if(Request::segment(1) == "admin" or Request::segment(1) == "dashboard") class="active" @endif><a href="{{ url('dashboard') }}"><i class="fa fa-th-large"></i> <span class="nav-label">@lang('menu.dashboard')<span></a></li>
				{{--   @endpermission  --}} 
                 @permission('add_sale')
				 <!-- li @if(Request::segment(1) == "sales" and Request::segment(2) == "create") class="active" @endif><a href="{{ url('sales/create') }}"><i class="fa fa-diamond"></i> <span class="nav-label">@lang('menu.point_of_sale')<span></a></li -->
				 @endpermission
				 {{-- temp --}}
				 @permission('add_sale')
				 <li @if(Request::segment(1) == "sales" and Request::segment(2) == "create") class="active" @endif><a href="{{ url('tmpsales/create') }}"><i class="fa fa-barcode"></i> <span class="nav-label">Nueva Venta<span></a></li>
				 @endpermission
				 
				 {{--  --}}
                 @permission('view_expense')
				 <!-- li @if(Request::segment(1) == "expenses") class="active" @endif><a href="{{ url('expenses') }}"><i class="fa fa-diamond"></i> <span class="nav-label">@lang('menu.expenses')<span></a></li -->
				 {{-- New gastos, at 2022-06-08  --}}
				 <li @if(Request::segment(1) == "fondocaja") class="active" @endif><a href="{{ route('fondocaja.index') }}"><i class="fa fa-calendar"></i> <span class="nav-label"> Fondo de Caja <span></a></li>


                 <li @if(Request::segment(1) == "ctrlgastos") class="active" @endif><a href="{{ route('ctrlgastos.index') }}"><i class="fa fa-calculator"></i> <span class="nav-label"> Gastos en General <span></a></li>
				
				  
				<?php /*  <li @if(Request::segment(1) == "online-orders") class="active" @endif><a href="{{ url('online-orders') }}"><i class="fa fa-list"></i> <span class="nav-label">@lang('menu.online_orders')<span></a></li>
				  */ ?>
				  
				 @endpermission
                 @permission('view_sale')
				  <li  @if((Request::segment(1) == "orders" or Request::segment(1) == "sales") and Request::segment(2) == "") class="active" @endif>
                    <a href="#"><i class="fa fa-th-large"></i> <span class="nav-label">@lang('menu.sales')</span> <span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse">
                    	{{--   Registro de ventas,  En tienda y a domicilio   --}}
                        <li @if(Request::segment(1) == "sales" and Request::segment(2) == "") class="active" @endif><a href="{{ url('sales') }}">@lang('menu.pos_sales')</a></li>
                        <?php /*li @if(Request::segment(1) == 
                        "orders" ) class="active" @endif><a href="{{ url('orders') }}">@lang('menu.order_sales')</a></li> */ ?>
                        {{--   Registro de ventas,  en General, En tienda y a domicilio , pickup, comandas Canceladas  --}}
                        
                       
                    </ul>
                </li>
				 @endpermission

 


                {{--  @_permission('view_products')  --}}
                @if($usrPermiso != null &&
                    ($usrPermiso->can('view_categorys') || $usrPermiso->can('view_products')   )
                )
 
                    <?php /* <li><a href="{{ url('customers') }}"> <i class="fa fa-users"></i> <span class="nav-label">Customers <span></a></li>
                    <li><a href="{{ url('suppliers') }}"> <i class="fa fa-users"></i> <span class="nav-label">Suppliers <span></a></li> */ ?>
					
                <li @if((Request::segment(1) == "categories" or Request::segment(1) == "products") and Request::segment(2) == "") class="active" @endif>
                    <a href="#"><i class="fa fa-th-large"></i> <span class="nav-label">@lang('menu.products')</span> <span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse">
                    @if( $usrPermiso->can('view_categorys') )
                    <li @if(Request::segment(1) == "categories" and Request::segment(2) == "") class="active" @endif><a href="{{ url('categories') }}">@lang('menu.categories')</a></li>
                    @endif
                    @if( $usrPermiso->can('view_products') )
                    <li @if(Request::segment(1) == "products" and Request::segment(2) == "") class="active" @endif><a href="{{ url('products') }}">@lang('menu.products')</a></li>
                    @endif
                       
                    </ul>
                </li>
                
                @endif
				{{--   @_endpermission  --}}
                
                   
   
                
                <?php 
                    $class_active_report = false;
                    if (Request::segment(1) == "reports"  || Request::segment(1) == "reporte" ){
                        $class_active_report = TRUE;
                    }
                ?>
                {{--    @permission('reports')     --}}
                @if($usrPermiso != null &&
                    ( $usrPermiso->can('report_view_sales') || $usrPermiso->can('report_sales_expences') || $usrPermiso->can('report_corte_diario') || $usrPermiso->can('report_top_sales') || $usrPermiso->can('report_sales_graph') || $usrPermiso->can('report_view_expences')  )
                )

                <li @if( $class_active_report )  class="active";  @endif    >
                    <a href="#"><i class="fa fa-th-large"></i> <span class="nav-label">@lang('menu.reporting')</span> <span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse">
                        <!-- li @if((Request::segment(1) == "reports" and Request::segment(2) == "sales")) class="active" @endif><a href="{{ url('reports/sales') }}">@lang('menu.sales_report')</a></li -->
                        @if( $usrPermiso->can('report_view_sales') )
                        {{-- <li @if((Request::segment(1) == "reports" and Request::segment(2) == "sales")) class="active" @endif><a href="{{ url('reports/sales') }}">Lista Ventas</a></li> --}}
                        @endif

						@if( $usrPermiso->can('report_sales_expences') )
                        <li @if((Request::segment(1) == "reporte" and Request::segment(2) == "diario")) class="active" @endif><a href="{{ route('reportes.diario') }}" >Ventas y Gastos</a></li> 
						@endif

                        @if( $usrPermiso->can('report_corte_diario') )
                        <li @if((Request::segment(1) == "reporte" and Request::segment(2) == "corte")) class="active" @endif><a href="{{ route('reportes.cortes') }}" > Corte diario </a></li>
                        @endif

                        @if( $usrPermiso->can('report_top_sales') )
                        {{-- <li @if((Request::segment(1) == "reports" and Request::segment(2) == "sales_by_products")) class="active" @endif><a href="{{ url('reports/sales_by_products') }}">@lang('menu.product_by_sales')</a></li> --}} 
                        @endif 

                        @if( $usrPermiso->can('report_sales_graph') )
                        <li @if((Request::segment(1) == "reports" and Request::segment(2) == "graphs")) class="active" @endif><a href="{{ url('reports/graphs') }}">@lang('menu.graphs')</a></li>
                        @endif

                        @if( $usrPermiso->can('report_view_expences') )
                        {{-- <li @if((Request::segment(1) == "reporte" and Request::segment(2) == "gastos")) class="active" @endif><a href="{{ route('reportes.gastos') }}">Lista Gastos</a></li> --}}
                        @endif
                     
                       
                    </ul>
                </li>
                @endif
				{{--  @endpermission    --}}
                
                @permission('setting')
				 <li @if(Request::segment(2) == "general") class="active" @endif>
                    <a href="{{ url('settings/general') }}"><i class="fa fa-gear"></i> <span class="nav-label"> @lang('menu.settings')</span></a>
                </li>
				
              
                @endpermission

                
                
                @permission('view_tables')
                <li @if(Request::segment(1) == "tables") class="active" @endif>
                    <a href="{{ url('tables') }}"><i class="fa fa-list"></i> <span class="nav-label"> @lang('menu.tables')</span></a>
                </li>
                @endpermission



                @permission('users')
				
                
                <li @if(Request::segment(1) == "users") class="active" @endif>
                    <a href="{{ url('users') }}"><i class="fa fa-users"></i> <span class="nav-label"> @lang('menu.users')</span></a>
                </li>
				@endpermission
                 @permission('roles')
				
				<li @if(Request::segment(1) == "roles") class="active" @endif>
                    <a href="{{ url('roles') }}"><i class="fa fa-users"></i> <span class="nav-label"> @lang('menu.roles')</span></a>
                </li>
				@endpermission
                 
                
            @permission('Profile')
				<li @if((Request::segment(2) == "profile" )) class="active" @endif>
                    <a href="{{url('settings/profile')}}"><i class="fa fa-user"></i> <span class="nav-label"> @lang('menu.profile') </span></a>
                </li>
				@endpermission
                <li>
                    <a href="{{ url('logout') }}"><i class="fa fa-sign-out"></i> <span class="nav-label"> @lang('menu.logout') </span></a>
                </li>
                
            </ul>

        </div>
    </nav>
