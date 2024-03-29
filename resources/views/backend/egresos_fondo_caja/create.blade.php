@extends('layouts.app')



@section('content')


<?php 

$usrPermiso = Auth::user();


?>




<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-10">
    <h2>@lang('common.add')  Gasto   <!--  @lang('common.product')  -->  </h2>
    <ol class="breadcrumb">
      <li>
        <a href="{{url('')}}">@lang('common.home') </a>
      </li>
      <li>
        <a href="{{ route('fondocaja.index') }}" id="linkExpences"> Gastos </a>
      </li>
      <li class="active">
        <strong>@lang('common.add_new')</strong>
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
          <h5>  Agrega un nuevo Gasto  </h5>
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
              <label class="col-sm-2 control-label" alt="Fecha de hoy."  >Fecha:  </label>
              <div class="col-sm-3">
                <!-- i nput type="text" class="form-control" id="_fecha" name="_fecha" value=""  disabled="true" -->
                <input type="date" class="form-control" id="porfecha" name="porfecha" placeholder="" 
                  @if($usrPermiso != null )
                    @if( !$usrPermiso->can('expence_date_insert') )
                      readonly
                    @endif
                  @else 
                    readonly 
                  @endif
                />
              </div>
            </div>
            
            <!--   -->
            <div class="form-group">
			<label class="control-label col-sm-2" for="importe">Importe: </label> 
				<div class="col-sm-10">
				  <div class="input-group">
					<div class="input-group-addon">
					  <i class="fa fa-dollar"></i>
					</div> 
					<!-- input id="price" name="price" type="number" class="form-control"   required      step="00000000.01"  {{-- step="0.01" --}} / -->
					<input id="importe" name="importe" type="text" class="form-control"  required    step="0.01"  />	
					<span id="importe_error" class="text-danger  dis-none"></span>
				  </div>
				</div>
			</div>
{{--  https://www.w3schools.com/howto/howto_css_hide_arrow_number.asp  --}}
<style>
/* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

{{-- 'd-none' taken of boostra4 , using in 3 to prevent incompa  --}}
.dis-none {display:none!important} 

/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
}
</style>
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
                	placeholder="Escriba una descripción breve. Ejem: Pan" required  />
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
              <!-- label class="col-sm-2 control-label">Unidad de Venta</label -->
              <label class="col-sm-2 control-label">Presentación / Unidad de Venta</label>
              <div class="col-sm-10">
                <select class="form-control" id="unidadV" name="unidadV">
                  <!-- option value="0000"> - Venta en general / Sin Especificar. - </option -->
                  {{-- <option value="-1"> - Seleccione - </option> --}}
					@foreach($unitList as $unit)
					{{-- option value="{ { $cat->id} }">  { { $cat->name}} </option --}}
					<option value="{{$unit->clave}}"
						<?php				
						if ($unit->clave === "0000") {
							echo " selected "; 
						}
						?> 
					> {{$unit->nombre}} </option>
					@endforeach
                </select>
              </div>
            </div>
            
            {{--  Este va a ser dinámico según unidad de Venta.. ejem, piezas  
            
            Especifico: s incia la unidad va a ase
            1 Kg de tortillas
            100 mg de Jamón
            1 botella de agua de Lt
            
            
            --}}
            <div class="form-group">
              <!-- label class="col-sm-2 control-label">Cantidad: </label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="_dUnit" name="_dUnit" value="0">
              </div -->
            </div>

			
			<div class="form-group"  id='filaCantidades'  style="display: none;"  >
              <label class="col-sm-2 control-label45">&nbsp;</label>
              <div class="col-sm-10">
			  	<div class="form-inline">
				  <label class="my-1 mr-2" for="lblCabtidad">Cantidad:</label>
					<div class="input-group col-sm-2">
						<input id="numCantidad" name="numCantidad" type="text" class="form-control"
						   step="0.01" placeholder="Ejem: 1">
						<!-- input id="price" name="price" type="text" class="form-control"  
						required  value="{   { $ga  stos->expense_amount }  }"  step="0.01"  / -->	
						<span id="cantidad_error" class="text-danger  dis-none"></span>
					</div>

				</div{{-- End inline --}}>
                
              </div{{-- End col-sm-10 --}}>
            </div{{-- End From group --}}>
 
            
            <div class="hr-line-dashed"></div>
            <div class="form-group   errSEct">
              <label class="col-sm-2 control-label"></label>
              <div class="col-sm-10">
                <!--  label class="col-sm-2 control-label" id="errLabel"  ></label -->
                <div class="alert alert-danger" role="alert"  id="errLabel" ></div>
              </div>
            </div>
            <div class="hr-line-dashed    errSEct"></div>
            
                                

            <div class="form-group">
              <div class="col-sm-4 col-sm-offset-2">
                <a class="btn btn-white" href="{{ route('fondocaja.index') }}"> @lang('common.cancel')</a>
                @permission('add_expense')
                <button class="btn btn-primary" type="submit"   >@lang('common.save')</button>
                @endpermission 
                <!-- button class="btn btn-primary" type="submit"  disabled="true" >@lang('common.save')</button -->
              </div>
            </div>
          </form>
          <br />
          
          <form style="display: none;"  action="{{ route('fondocaja.create') }}" class="form-horizontal" method="POST" 
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
                        <label for="mFile" id="">
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


<!-- 

lista

{{ json_encode($unitList, true)  }}
<?php

echo json_encode($unitList, true);

?>


  -->

{{--  Methods js  --}}

<script>
	function reset_errors(){
		$("#importe_error").hide();
		$("#cantidad_error").hide();

	}

	function valida_importe(){
		let es_valido = false;
		let numero = cleavePrecio.getRawValue();
		//  cleavePrecio.getRawValue()
		// '#importe

		if (numero.length === 0 ) {
			$("#importe_error").html("Ingresa un Valor");
			$("#importe_error").show();
			$("#importe_error").attr('class', "text-warning");
			es_valido = false;
		} else if (numero < 0.00 ) {
			$("#importe_error").html("Verifica el valor ingresado.");
			$("#importe_error").show();
			$("#importe_error").attr('class', "text-warning");
			es_valido = false;
		} else {
			$("#importe_error").hide();
			es_valido = true;
		}

		/*var password = $("#password").val().length;
		
		if(password < 5){
			$("#password_error").html("Password too short");
			$("#password_error").show();
			$("#password_error").attr('class', "text-warning");
		}else if(password > 12){
			$("#password_error").html("Password too long");
			$("#password_error").show();
			$("#password_error").attr('class', "text-danger");
		}else{
			$("#password_error").hide();
		}*/

		return es_valido;

	}
	

	function valida_cantidad(){
		let es_valido = false;
		let numero = cleaveCantidad.getRawValue();
		let num = Number(numero);
		//  cleavePrecio.getRawValue()
		// '#importe

		if (numero.length === 0 ) {
			$("#cantidad_error").html("Ingresa un Valor");
			$("#cantidad_error").show();
			$("#cantidad_error").attr('class', "text-warning");
			es_valido = false;
		} else if (num < 0.00 ) {
			$("#cantidad_error").html("Verifica el valor ingresado.");
			$("#cantidad_error").show();
			$("#cantidad_error").attr('class', "text-warning");
			es_valido = false;
		} else if (num === 0.00 ) {
			$("#cantidad_error").html("No puedes ingresar valor Cero.");
			$("#cantidad_error").show();
			$("#cantidad_error").attr('class', "text-warning");
			es_valido = false;
		} else {
			$("#cantidad_error").hide();
			es_valido = true;
		} 
		return es_valido;

	}


	function es_unimed_pred(){
		let valorSelect_ = $("#unidadV").val();
		return  ('0000'  === valorSelect_);

	}



	



</script>







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
	
	var attachFile = false;
	
	
	
	function filePreview(input) {
		
		$('#uploadDiv + img').remove();
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function (e) {
				$("#image_source").attr('src', e.target.result );
				$("#image_source").show();
				attachFile = true;
				
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

		}
	}






	//---
	function _submit(e){
	
		e.preventDefault();
		
		
		$(".errSEct").hide();
		$("#errLabel").html("");

		
		var fdata = new FormData();
		// var files = $ ('#mFile' )[0].files[0];
		
		//fdata.append( 'archivo', files );
		//fdata.append( 'imagen', files );
		//___fdata.append( 'nombreCat', $("#nombreCat").val() );

    //_fdata.append( 'fecha', $("#_fecha").val() );
    fdata.append( 'fecha', $("#porfecha").val() );
    
		// fdata.append( 'expense_amount', $("#price").val() );
		fdata.append( 'expense_amount', cleavePrecio.getRawValue() );
		
		fdata.append( 'description', $("#description").val() );
		fdata.append( 'unidadV', $("#unidadV").val() );



    fdata.append( 'cantidad', cleaveCantidad.getRawValue() );


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
				// url: '{{ url('expenses') }}',
				url: '{{ route('fondocaja.store') }}',
				
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
					 $('#linkExpences')[0].click();
					 

					{{--
					
					
					proccess    output: {"status":true,"__id":8}
					--}}

					//-$("#myModal").modal("hide");
				},
				error: function(data) {
					// data.responseJSON
					//		{"message":"The given data was invalid.","errors":{"total":["El campo total es obligatorio."]}}    
					// data.responseText
				
		            {{--   errLabel   --}}
		            
		            //console.log(data);
		            
		            var _otro = data.responseJSON;
		            if (_otro && _otro.message){
		            	console.log(_otro.message);
		            }
		            
		            
		            let _errLabel = "";
		            //   if (_otro && _otro.message && _otro.message.errors && _otro.message.errors.imagen){
		            if (_otro &&  _otro.errors && _otro.errors.imagen){
		            	_errLabel = "La imagen a subir no es del formato: jpg, png, gif  o   Es Mayor a 2 MB.  Puede subir otra imagen o agregarla después.";
		            	//$("#errLabel").val(_errLabel);
		            	$("#errLabel").html(_errLabel);
		            	$(".errSEct").show();
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
	
	//_$("#_fecha").val(today);
  $('#porfecha').attr('value', today);
	
	
	
	
	
	// hide
	$("#image_source").hide();	
	
	
	//   $("#basic-addon1")
	$("#categAgregaImg").click(function(){
		$("#mFile").click();

	});
	
	//btnEnviar
	/*$("#btnEnviar").click(function(){
		$("#mFile").click();

	}); */
	
	$("#formGastosAjax").on("submit", function(e){
	    // Before Update{{--  No realizar Submit antes de guardar   --}}
		e.preventDefault();


		reset_errors();

		if (!valida_importe() ){
			return false;
		}

		if ( !es_unimed_pred() && !valida_cantidad() ){
			return false;
		}

 
	    
	    _submit(e);
		
	});
	
	
	$("#mFile").change(function () {
		filePreview(this);
	});
	
	
	$(".errSEct").hide();
	$('#filaCantidades').hide();

	//  Unidad de Médida
	$("#unidadV").on("change", function(ev) {
        console.log($("#unidadV").val());


		//
		let valorSelect_ = $("#unidadV").val()
		if (valorSelect_ == '0000'){
			$('#filaCantidades').hide();
			cleaveCantidad.setRawValue(1);
			$("#numCantidad").removeAttr('required');
		} else {
			$('#filaCantidades').show();
			cleaveCantidad.setRawValue(1);
			$("#numCantidad").prop('required',true);
		} 

    });


	// ocultar_
	//$('#filaCantidades').hide();
 	
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
{{--  time to load this page in miliseconds_	/*   Add a data_ */ --}}


	



</script>



<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>


<script src="{{ url('assets/web_dav/libs/cleave.js/1.6.0/cleave.min.js') }}?a={{ rand(10, 99) }}"></script>

<script>

// 		var cleaveNumeral = new Cleave('#price', {
var cleavePrecio = new Cleave('#importe', {
    numeral: true,
    numeralThousandsGroupStyle: 'thousand', 


	//numeralDecimalScale: 2

});

//numCantidad
var cleaveCantidad = new Cleave('#numCantidad', {
    numeral: true,
    numeralThousandsGroupStyle: 'thousand', 

	numeralDecimalScale: 4

});



</script>





@endsection