@extends('layouts.app')

@section('content')

<link href="assets/css/plugins/dataTables/datatables.min.css" rel="stylesheet">

 <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>@lang('common.categories') </h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="{{url('')}}">@lang('common.home')</a>
                        </li>
                     
                        <li class="active">
                            <strong>@lang('common.categories')</strong>
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
                        <h5>@lang('common.categories')  </h5>
                        <div class="ibox-tools">
                        @permission('add_category') 
						<a href="{{ url('categories/create') }}" class="btn btn-primary btn-xs">@lang('common.add_new')</a>
                        @endpermission
						
                            <!-- a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a -->
							 
                           
                        </div>
                    </div>
                    <div class="ibox-content">

                        <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" >
					
					 <thead>
                        <tr>
                             <th>#</th>
                            <th>Imagen</th>
                            <th>@lang('common.name')</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    	$ruta = '';
                    
                    
      				?>
                    @forelse ($categories as $key => $category)
                        <tr class="gradeX">
                             <td>{{ $categories->firstItem() + $key }}</td>
                              
                            <td>
                            	<?php 
                            		//_$exists_storage = Storage::disk('public')->exists('file.jpg');
                            		//  !Storage::exists('/path/to/your/directory')
                            		//$exists_storage = Storage::disk('public')->exists('file.jpg');
                            	
                            	
                            		//   @if (Storage::exists( $category->categoryPic ))
                            	?>
                            	@if ( $category->categoryPic !== null && Storage::disk('public')->exists('categorias/' . $category->categoryPic) )
                            		{{--  no se debe usar url, sino route( )  con parametros __  --}}
                            		<img width="70px" id="image_source"  src="<?php echo url("imagenes/" . $category->categoryPic . "?rand=".rand(0, 100)); ?>" />
                            	@else
                            		@if( file_exists('uploads/category/thumb/' . $category->id . '.jpg') )
	                            		<img width="70px" id="image_source"  src="{{ url('uploads/category/thumb/' . $category->id . '.jpg') }}?qa={{ rand(0, 100) }}" />
	                            	@else
	                            		<!-- img width="70px" alt="image"   src="{{url('herbs/noimage.jpg')}}" / -->
	                            		<img width="65px" alt="image" class="img-circle" src="{{url('herbs/noimage.jpg')}}" />
	                            		<!-- img width="100px" alt="image" class="img-circle" src="{{url('herbs/noimage.jpg')}}" / -->
	                            	@endif
                            	@endif
                            		
                            
                            	<!-- -- -->
                            	<!-- img width="70" id="image_source"  src="< ? php echo url("uploads/category/thumb/" . $category->id . ".jpg?rand=".rand(0, 100)); ?>" -->
                            
                            
                            </td>
                            <td>{{ $category->name }}</td>
                           
                            <td>
                            	&nbsp;&nbsp;&nbsp;&nbsp;
                                
                                <div class="dropdown">
								    <button class="btn btn-default dropdown-toggle" {{-- id="menu1" --}} type="button" data-toggle="dropdown"> Opciones 
								    <span class="caret"></span></button>
								    <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
								      <!-- li role="presentation"><a role="menuitem" tabindex="-1" href="#">HTML</a></li -->
								      
                                      @permission('edit_categorys') 
                                      <li role="presentation">
								      	<a role="menuitem" tabindex="-1"  href="{{ url('categories/' . $category->id . '/edit') }}" >Editar</a>
								      </li>
                                      @endpermission
								      
								      <li role="presentation" class="divider"></li>
								      
								      @permission('delete_categorys') 
                                      <li role="presentation">
	  <form id="delete-customer" action="{{ url('categories/' . $category->id) }}" method="POST" class="form-inline">
          <input type="hidden" name="_method" value="delete">
          {{ csrf_field() }}
  <!-- <input type="submit" value="Delete" class="btn btn-danger btn-xs pull-right btn-delete">-->
          <input  role="menuitem" tabindex="-1" type="submit" value="Borrar" class="btn ">
      </form>
								      </li>
                                      @endpermission
								    </ul>
								  </div>
                                
                                
                                &nbsp;&nbsp;&nbsp;&nbsp;
                            </td>
                        </tr>
                    @empty
                       <tr> 
						  <td colspan="5">@lang('common.no_record_found') </td></tr>
                    @endforelse
						<tr> 
						  <td colspan="5">
						{!! $categories->render() !!}
						</td>
								</tr>
                    </tbody>
            
                    
                    </table>
                        </div>

                    </div>
                </div>
            </div>
            </div>
           
        </div>
       
      
    

@endsection
