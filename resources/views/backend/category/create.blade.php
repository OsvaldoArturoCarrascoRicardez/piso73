@extends('layouts.app')

@section('content')

@include("backend.category/cropper")


<div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>@lang('common.add') @lang('common.category')</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="{{url('')}}">>@lang('common.home')<</a>
                        </li>
                        <li>
                             <a href="{{url('categories')}}">@lang('common.categories')</a>
                        </li>
                        <li class="active">
                            <strong>@lang('common.add_new')</strong>
                        </li>
                    </ol>
                </div>
                <div class="col-lg-2">

                </div>
            </div>
			
			<div class="wrapper wrapper-content animated fadeInRight">
			<div class="row">
                <div class="col-lg-8">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Nueva Categoria</h5>
                            <div class="ibox-tools">
                                <a class="collapse-link">
                                    <i class="fa fa-chevron-up"></i>
                                </a>
                                
                                
                            </div>
                        </div>
						<div class="ibox-content">
							<form action="{{ url('categories') }}" class="form-horizontal" 
								id="formuploadajax" method="POST" enctype='multipart/form-data'>
								{{ csrf_field() }}
								<div class="form-group"><label class="col-sm-2 control-label">@lang('common.name')</label>
                                    <div class="col-sm-10"><input type="text" class="form-control" id="nombreCat" name="nombreCat" value="{{ old('name') }}"></div>
                                </div>
								<div class="hr-line-dashed"></div>
								
								{{--
								<div class="form-group">
									<label class="col-sm-2 control-label">text2</label>
                                    <div class="col-sm-10">
										<input type="text" class="form-control" id="text2" name="text2" value="{{ old('name') }}"></div>
                                </div>
								<div class="hr-line-dashed"></div>
								--}}
								
								<div class="form-group">
                                    <div class="col-sm-4 col-sm-offset-2">
										<a class="btn btn-white" href="{{ url('categories') }}">@lang('common.cancel')</a>

                                        @permission('add_category') 
										<button class="btn btn-primary" id="btnEnviar" type="submit">@lang('common.save')</button>
										@endpermission
                                    </div>
                                </div>
								
								<div class="hr-line-dashed"></div>
								 
								
                            </form>
                        </div>
                    </div>
                </div>
                 <div class="col-lg-4">
                    <form id="uploadimage" action="" method="post" enctype="multipart/form-data">
					<input type="hidden" name="cropped_value" id="cropped_value" value="">
					<label title="Upload image file" for="inputImage" class="">
                
                    <div class="upload-pic img-circle" style="">
                        <img id="image_source" class="img-circle otros" src="" alt="">
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

            </div>
			</div>	

            	<style> 
.cropper-container.cropper-bg {
  background: #fff !important;
  background-image:none !important;
}

.cropper-modal {
    opacity: .5;
    background-color: #fff;
}

.upload-pic { 
	height:200px;
	width:200px; 
	background:#ccc;
	margin:10px;
}

.upload_button { 
	margin-top:10px;
}


.otros {
  /*width: 200px;
  height: 121px;*/
  
  width: 200px;
  height: 200px;
  
}


</style>

<?php /*!-- @ i n c l  u d e("backend.category/  otro " )*/   ?>




<script>


	function filePreview(input) {
		
		//$('#uploadForm + img').remove();
		$('#uploadDiv + img').remove();
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function (e) {
				//$('#uploadForm + img').remove();
				//$('#uploadForm').after('<img src="'+e.target.result+'" width="450" height="300"/>');
				//$('#uploadForm + embed').remove();
				// $('#uploadForm').after('<embed src="'+e.target.result+'" width="450" height="300">');
				///$('#uploadDiv').after('<img src="'+e.target.result+'" width="450" height="300" />');
				///****
				
				$("#image_source").attr('src', e.target.result );
				$("#image_source").show();
				
			};
			reader.readAsDataURL(input.files[0]);
		} else {
			$('#uploadDiv').after('<img src="//via.placeholder.com/100x100" width="20" height="20" />');
			//$('#uploadDiv').after('<img src="//via.placeholder.com/100x100" width="20" height="20" />');
			    //<img id="image_upload_preview" src="http://placehold.it/100x100" alt="your image" />
				
				$("#image_source").attr('src', '//via.placeholder.com/100x100' );
				$("#image_source").hide();	

		}
	}
	
	
	//---
	function _submit(e){
	
		e.preventDefault();

		
		var fdata = new FormData();
		var files = $ ('#mFile' )[0].files[0];
		
		//fdata.append( 'archivo', files );
		fdata.append( 'imagen', files );
		fdata.append( 'nombreCat', $("#nombreCat").val() );
		
		
		$.ajax( {
				type: 'POST',
				headers: {
					'X-CSRF-TOKEN': $( 'meta[name="csrf-token"]' ).attr( 'content' )
				},
				//_url: '<?php echo url("sale/hold_order"); ?>',
				// salev2/hold_order
				url: '{{ url('categories') }}',
				//url: '<?php echo url("salev2/hold_order"); ?>',
				
				
				data: fdata/*form_data*/,
				
				processData: false,  // tell jQuery not to process the data
  contentType: false,   // tell jQuery not to set contentType
				success: function ( msg ) {
				
					{{--  ya hay json response, pero reload page  --}}
					//location.reload();
					
					console.log(msg);
					
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
					swal("Oops!", "Verifica La informaci�n", "error");


		            
		            
		        }
		        //---
			});
		//---	
		
		 
		//---
	}
	
	
	


$(function(){


 	$("#shareCbox").change(function(){
 		if(!this.checked)
 		{
 			$("#shareCboxLb").text("Members Only");
 		}
 		else
 		{
 			$("#shareCboxLb").text("Anyone can Access");
 		}
 	});
	
	
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
	
	$("#formuploadajax").on("submit", function(e){
	    //e.preventDefault();
	    //var f = $(this);
	
	    // ... resto del c�digo de mi ejercicio
	    _submit(e);
	});
	
	
	
	
	
	
	
	
	
	
	// uploadMsg
	 //let ctlFile = $("#mFile");
	 /*ctlFile.change(function(){
		//document.getElementById("weblink").value="";
		{{-- $("#uploadMsg").text(this.files.length + " image select.");   ok, working --}}
		let input = this.files[0];
		let text;
		if (input) {
			//process input
			//text = imageUpload.value.replace("C: \\fakepath\\", "");
			text = ctlFile.value; //.replace("C: \\fakepath\\", "");
			console.log(input);
		} else {
			text = "Please select a file";
		}
		$("#uploadMsg").text(text);	
	 });*/
	 
	$("#mFile").change(function () {
		filePreview(this);
	}); 
	
	
 	
//---
});



</script>


<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

	
@endsection