@extends('layouts.app')

{{--  Editar Gastos   --}}

 

@section('content')

<?php
// mostrar imagen si esta disponible
$imgExists = FALSE;

if (isset($gastos->expencePic)){
	 $imgExists = Storage::disk('public')->exists('gastosgastos/' . $gastos->expencePic);
}



if(!$imgExists){
	// no se agregó imagen 
	

}





?>





<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-10">
    <h2>@lang('common.edit')  Gasto   <!--  @lang('common.product')  -->  </h2>
    <ol class="breadcrumb">
      <li>
        <a href="{{url('')}}">@lang('common.home') </a>
      </li>
      <li>
        <a href="{{   route('expenses.index')  }}" id="linkExpences"> Gastos </a>
      </li>
      <li class="active">
        <strong>@lang('common.edit')</strong>
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
          <h5>  Editar  </h5>
          <!-- h5>  Agrega un nuevo Gasto  </h5 -->
          <div class="ibox-tools">
            <!-- a class="collapse-link">
              <i class="fa fa-chevron-up"></i>
            </a -->
          </div>
        </div>
        <div class="ibox-content">
        	              <!-- form action="  {   { r  o u  te('from.store') }  }   " method="post" -->

          <form action="{{ url('products22') }}" class="form-horizontal" method="POST" 
          		id="formGastosAjax" name="formGastosAjax" 	enctype='multipart/form-data'>
            {{ csrf_field() }}
            
            
            <div class="form-group">
              <label class="col-sm-2 control-label" alt=""  ></label>
              <div class="col-sm-10">
                <input type="hidden" class="" id="_id" name="_id" value="{{ $gastos->id }}"   />
              </div>
            </div>
            <!--    -->
            
            
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
							<input id="price" name="price" type="text" class="form-control" value="{{ format_numero( $gastos->expense_amount ) }}"   disabled="true" />
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
                	placeholder="Escriba una descripci�n breve. Ejem: Pan"  value="{{ $gastos->description }}"   required  disabled="true" />	
              </div>
            </div>
            {{--  Opcional a agregar...  --}}
            <!-- div class="form-group">
              <label class="col-sm-2 control-label">@lang('common.description') Larga</label>
              <div class="col-sm-10">
                <textarea class="form-control" id="motive" name="motive"></textarea>
              </div>
            </div -->
            
            
            <div class="hr-line-dashed"></div>
            {{--  Adicionales_  --}}
            <div class="form-group">
              <label class="col-sm-2 control-label">Unidad de Venta</label>
              <div class="col-sm-10">
                <!-- -->
                <select class="form-control" id="unidadV" name="unidadV"     disabled="true" >
											{{-- <option value="-1"> - Seleccione - </option> --}}
											@foreach($unitList as $unit)
											<!-- option value="{ { $cat->id} }">  { { $cat->name}} </option -->
											<option value="{{$unit->clave}}" <?php 
											
											if ($unit->clave === $gastos->unit_value) {
												echo " selected "; 
											}
											
											?> >  {{$unit->nombre}} </option>
											@endforeach
										</select>
                <!-- -->
              </div>
            </div>
            
            {{--  Este va a ser dinámico según unidad de Venta.. ejem, piezas  
            
            Especifico: se incia la unidad va a ase
            1 Kg de tortillas
            100 mg de Jam�n
            1 botella de agua de Lt
            
            
            --}}
            <!-- div class="form-group">
              <label class="col-sm-2 control-label">Cantidad: </label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="_dUnit" name="_dUnit" value="0">
              </div>
            </div -->
            
            <div class="hr-line-dashed"></div>
            
            <!-- 
						{  {  route('gastos.displayImage')  }  }   -- fails
						<br />
						{{  route('gastos.displayImage', $gastos->expencePic)  }}
						<br />
						{{  route('gastos.displayImage', '')  }}
						
			-->
			                    

            <div class="form-group">
              <div class="col-sm-8 col-sm-offset-2">
                <a class="btn btn-white" href="{{ route('expenses.index') }}"> Regresar </a>
				@permission('edit_expense')
                <a class="btn btn-primary" href="{{ route('expenses.edit', $gastos->id) }}"> Editar </a>
				@endpermission
                
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
                        <!-- img id="image_source" class=" otros" src="{{  route('gastos.displayImage', $gastos->expencePic)  }}"  alt="" / -->
                        
                    <?php
// mostrar imagen si esta disponible


// $imgExists = Storage::disk('public')->exists('gastosgastos/' . $gastos->expencePic);

if(!$imgExists){
	// no se agreg� imagen 
	//echo 'xyz';
?>			<img id="image_source" class=" otros" src=""  alt="" />
<?php 
} else {

?>
			<img id="image_source" class=" otros" src="{{  route('gastos.displayImage', $gastos->expencePic)  }}"  alt="" />
<?php
}
 
?>

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


<!-- 

lista

<?php 

if ( !isset($unitList) ){
	$unitList = [];
	
}

?>


{{ json_encode($unitList, true)  }}
<?php

echo json_encode($unitList, true);



?>


--

 @json($gastos, JSON_PRETTY_PRINT);


  -->





<script>
	var now = new Date();

	var day = ("0" + now.getDate()).slice(-2);
	var month = ("0" + (now.getMonth() + 1)).slice(-2);

	var today = now.getFullYear()+"-"+(month)+"-"+(day) ;
	
	var attachFile = false;
	
	
	
	function filePreview(input) {
		
		$('#uploadDiv + img').remove();
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function (e) {
				$("#image_source").attr('src', e.target.result );
				$("#image_source").show();
				attachFile = true;
				labelCambiarImg();
				
			};
			reader.readAsDataURL(input.files[0]);
			console.log('img__');
		} else {
			$('#uploadDiv').after('<img src="//via.placeholder.com/100x100" width="20" height="20" />');
			//$('#uploadDiv').after('<img src="//via.placeholder.com/100x100" width="20" height="20" />');
			    //<img id="image_upload_preview" src="http://placehold.it/100x100" alt="your image" />
				
				$("#image_source").attr('src', '//via.placeholder.com/100x100' );
				$("#image_source").hide();	
				attachFile = false;
				labelAgregarImg();

		}
	}
	
	
	function labelAgregarImg(){
		$("#imgLabel").html('<div class="pic-placeholder"><span class="upload_button"> <i class="fa fa-picture-o"></i>  Agrega una Imagen </span></div>');
	}
	
	function labelCambiarImg(){
		$("#imgLabel").html('<div class="pic-placeholder"><span class="upload_button"> <i class="fa fa-picture-o"></i>  Cambiar Imagen </span></div>');
	}
	
	






	//---
	function _submit(e){
	
		e.preventDefault();

		
		var fdata = new FormData();
		// var files = $ ('#mFile' )[0].files[0];
		
		//fdata.append( 'archivo', files );
		//fdata.append( 'imagen', files );
		//___fdata.append( 'nombreCat', $("#nombreCat").val() );
		//_id
		fdata.append( '_id', $("#_id").val() );
		fdata.append( 'expense_amount', $("#price").val() );
		fdata.append( 'description', $("#description").val() );
		fdata.append( 'unidadV', $("#unidadV").val() );
		
		
		fdata.append( '_method', 'put' );
		
		//_fdata.append( 'price', $("#price").val() );
		if (attachFile){
			var __file = $ ('#mFile' )[0].files[0];
			fdata.append( 'imagen', __file );
		}
		
		 
		
		$.ajax( {
				type: 'POST',
				headers: {
					'X-CSRF-TOKEN': $( 'meta[name="csrf-token"]' ).attr( 'content' )
				},
				//__  url: '{{ route('expenses.update', $gastos->id) }}',				gastos.update
				url: '{{ route('gastos.update') }}',				
				
				data: fdata/*form_data*/,
				
				processData: false,  // tell jQuery not to process the data
				contentType: false,   // tell jQuery not to set contentType
				success: function ( msg ) {
				
					{{--  ya hay json response, pero reload page  --}}
					//location.reload();
					
					console.log(msg);
					
					
					//$("#linkExpences").trigger("click");
					//77$("#linkExpences").click();
					// descomentar cuando este echo_
					//__$('#linkExpences')[0].click();
					{{--
					
					
					proccess    output: {"status":true,"__id":8}
					--}}

					//-$("#myModal").modal("hide");
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
		            
		            ///-
//		            swal("Oops!", "Something went wrong!", "error");
					swal("Oops!", "Verifica La información", "error");


		            
		            
		        }
		        //---
			});
		//---	
		
		 
		//---
	}





$(function(){
	{{-- Pre Loading  --}}
	
	//$("#_fecha").val(today);
	
	
	
	
	
	// hide
	//$("#image_source").hide();
<?php
	if(!$imgExists){
	
?>
	$("#image_source").hide();
	labelAgregarImg();
	
<?php 	
} else {

?>
		labelCambiarImg(); 
<?php
}
 
?>
	
	
	
	
	
		
	
	
	//   $("#basic-addon1")
	/*$("#categAgregaImg").click(function(){
		$("#mFile").click();

	});
	*/
	
	//btnEnviar
	/*$("#btnEnviar").click(function(){
		$("#mFile").click();

	}); */
	
	$("#formGastosAjax").on("submit", function(e){
	    //e.preventDefault();
	    //var f = $(this);
	
	    // ... resto del código de mi ejercicio
	    _submit(e);
	});
	
	
	$("#mFile").change(function () {
		filePreview(this);
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