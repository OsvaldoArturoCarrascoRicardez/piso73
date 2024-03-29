@extends('layouts.app')



@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-10">
    <h2>Ver  Gasto   <!--  @lang('common.product')  -->  </h2>
    <ol class="breadcrumb">
      <li>
        <a href="{{url('')}}">@lang('common.home') </a>
      </li>
      <li>
        <a href="{{url('expenses')}}" id="linkExpences"> Gastos </a>
      </li>
      <li class="active">
        <strong> Visualizar </strong>
      </li>
    </ol>
  </div>
  <div class="col-lg-2"></div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
  <div class="row">
    <div class="col-lg-8">
      <div class="ibox float-e-margins">
        <div class="ibox-title">
          <!-- h5>@lang('common.add_new')    </h5 -->
          <h5>  Detalle  </h5>
          <div class="ibox-tools">
            <!-- a class="collapse-link">
              <i class="fa fa-chevron-up"></i>
            </a -->
          </div>
        </div>
        <div class="ibox-content">
        	              <!-- form action="  {   { r  o u  te('from.store') }  }   " method="post" -->

          <form action="/" class="form-horizontal" method="POST" 
          		id="formGastosAjax" name="formGastosAjax" 	enctype='multipart/form-data'>
            {{ csrf_field() }}
            
            <div class="form-group">
              <label class="col-sm-2 control-label" alt="Fecha de hoy."  >Fecha:  </label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="_fecha" name="_fecha" value="{{ $gastos->created_at }}"  disabled="true" />
              </div>
            </div>
            
            
            <!--   -->
            <div class="form-group">
					<label class="control-label col-sm-2" for="price">Gasto: </label> 
						<div class="col-sm-10">
						  <div class="input-group">
							<div class="input-group-addon">
							  <i class="fa fa-dollar"></i>
							</div> 
							<input id="price" name="price" type="text" class="form-control" value="{{ format_numero( $gastos->expense_amount ) }}"  disabled="true" />
						  </div>
						</div>
					</div>
            
            <!-- div class="form-group">
              <label class="col-sm-2 control-label">@lang('common.category')</label>
              <div class="col-sm-10">
                <select class="form-control" id="category_id" name="category_id">
                  <option value="-1"> - Sin Categoria - </option> 
                  
                </select>
              </div>
            </div -->
            <div class="form-group">
              <label class="col-sm-2 control-label">@lang('common.description')</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="description" name="description"  
                	placeholder="Escriba una descripción breve. Ejem: Pan"  value="{{ $gastos->description }}"   disabled="true" />
              </div>
            </div>
            {{--  Opcional a agregar...  --}}
            <!-- div class="form-group">
              <label class="col-sm-2 control-label">@lang('common.description') Larga</label>
              <div class="col-sm-10">
                <textarea class="form-control" id="motive" name="motive"></textarea>
              </div>
            </div -->
            
            
            {{--  use a for for select a value_   --}}
            <div class="hr-line-dashed"></div>
            {{--  Adicionales_  --}}
            <div class="form-group">
              <label class="col-sm-2 control-label">Unidad de Venta</label>
              <div class="col-sm-10">
              	<input type="text" class="form-control" id="unidadV" name="unidadV"  
                	 value="Venta en general / Sin Especificar."   disabled="true" />
                
              </div>
            </div>
            
            {{--  Este va a ser dinámico según unidad de Venta.. ejem, piezas  
            
            Especifico: s incia la unidad va a ase
            1 Kg de tortillas
            100 mg de Jamón
            1 botella de agua de Lt
            
            
            --}}
            <!-- div class="form-group">
              <label class="col-sm-2 control-label">Cantidad: </label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="_dUnit" name="_dUnit" value="0">
              </div>
            </div -->
            
            <div class="hr-line-dashed"></div>
            
                                

            <div class="form-group">
              <div class="col-sm-4 col-sm-offset-2">
                <a class="btn btn-white" href="{{ url('products') }}"> Regresar </a>
                <button class="btn btn-primary" type="button"   > Editar </button>
                <!-- button class="btn btn-primary" type="submit"  disabled="true" >@lang('common.save')</button -->
              </div>
            </div>
          </form>
          <br />
          
          <form style="display: none;"  action="{{ route('expenses.create') }}" class="form-horizontal" method="POST" 
          		id="arriesta514515415" name="arriesta514515415" 	enctype='multipart/form-data'>
            {{ csrf_field() }}
          </form>
          
        </div>
      </div>
    </div>
    
    
    <!--   -->
    <div class="col-lg-4">
                    <form id="uploadimage" action="" method="post" enctype="multipart/form-data">
					<!--  input type="hidden" name="cropped_value" id="cropped_value" value="">
					<label title="Upload image file" for="inputImage" class=""  -->
                
                    <div class="upload-pic " style="">
                        <img id="image_source" class=" otros" src="" alt="">
                    </div>
                    <div class="upload-pic-new btn btn-primary text-center"    >
                        <!-- input type="file"  name="file" id="cropper" style="display:none" / -->
						<input type="file" name="mFile" {{-- class="custom-file-input custom-file" --}} 
									id="mFile"   style="display: none;"    accept="image/*" />
                        <label for="mFile">
                        <div class="pic-placeholder">
							<span class="upload_button"> <i class="fa fa-picture-o"></i>
                            Agrega una Imagen </span>
                        </div>
                        </label>
                    </div>               
                                    
					</form>
                </div>
     <!--   -->

    
     
  </div>
</div>


<style>
 
 
 .upload-pic {
  height: 200px;
  width: 200px;
  background: #ccc;
  margin: 10px;
 }

 .upload_button {
  margin-top: 10px;
 }

 .otros {
  /*width: 200px;
height: 121px;*/
  width: 200px;
  height: 200px;
 }
</style>





<script>
	var now = new Date();

	var day = ("0" + now.getDate()).slice(-2);
	var month = ("0" + (now.getMonth() + 1)).slice(-2);

	var today = now.getFullYear()+"-"+(month)+"-"+(day) ;
	
	var attachFile = false;
	
 

$(function(){
	{{-- Pre Loading  --}}
 
	
	
	
	// hide
	$("#image_source").hide();	
	
	
	//   $("#basic-addon1")
	$("#categAgregaImg").click(function(){
		$("#mFile").click();

	});
	
 
	 

	
 	
//---
});




/// another  page
// carga de la página_
window.onload = function(){
  setTimeout(function(){
    var t = performance.timing;
    console.log(t.loadEventEnd - t.responseEnd);
  }, 0);
}


	



</script>



<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>



@endsection