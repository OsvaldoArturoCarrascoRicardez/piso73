@extends('layouts.app')

@section('content')
<?php $currency =  setting_by_key("currency"); ?>
@php
    //$counter = 1;
    $counter = 0;
@endphp




 <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>@lang('common.expenses')</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="{{url('/')}}">@lang('common.home')</a>
                        </li>
                     
                        <li class="active">
                            <strong>@lang('common.expenses')</strong>
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
                        <h5>@lang('common.expenses')	</h5>
                        <div class="ibox-tools">
                        <!-- a class="add_new btn btn-primary pull-right" href="javascript:void(0)" data-toggle="modal" data-target="#myModal" style="margin-bottom:5px"><i class="fa fa-plus"> </i> @lang('common.add')</a -->
                        @permission('add_expense')
                        <a class="add_new btn btn-primary pull-right" href="{{ route('expenses.create') }}"> <i class="fa fa-plus"> </i> Agregar </a>
                        @endpermission
                           
                        </div>
                    </div>
                    

<div class="ibox-content">
           
            <div class="table-responsive">
            <table class="table table-striped">
            <thead>
                <tr>

                    <th>&nbsp; &nbsp; &nbsp;</th>
                    <th>Descripción</th>
                    <th>Importe </th>
                    <th>U. Medida</th>
                    <th>Fecha</th>
                    <!-- th>Elaboró</th -->
                                        

                    <th>@lang('common.options')</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($expenses)) { 
                	foreach ($expenses as $row) { ?>
                    <tr id="fila_{{ $row->id }}">
                    	<td> 
                    		<?php
                    			if(!empty($row->expencePic)){
                    				//echo $row->expencePic;
                    				
                    				?>
                    				<span class="glyphicon glyphicon-picture" aria-hidden="true"></span>
                    				<?php
                    			} 
                    		?>
                    	
                    	
                    	</td>
                    	<td> {{ $row->description }} </td>

                         
                         <td  style="text-align: right;" > $ {{ format_numero( $row->expense_amount ) }} </td>
                         @php
						    //$counter = 1;
						    $counter += $row->expense_amount;
						@endphp
                         <td> {{ $row->nombre }} </td>
                         <td> {{ $row->created_at }} </td>
                         <td> 
                            <a data-toggle="tooltip" title="Ver [{{ $row->description }}]" href="{{ route('expenses.show', $row->id) }}"> 
                            	<span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> </a>
                            &nbsp;&nbsp;&nbsp;
                            @permission('edit_expense')
                            <a data-toggle="tooltip" title="Editar [{{ $row->description }}]" href="{{ route('expenses.edit', $row->id) }}"> 
                            	<span class="glyphicon glyphicon-edit" aria-hidden="true"></span> </a>	
                            @endpermission
                            
                            
                            <!-- a data-id="{ {$row->id}}" class="delete" href="javascript:void(0)" > <i class="fa fa-trash-o "> </i> </a --> 
                        </td>
                    </tr>
                <?php } 
                ?>
                	<tr id="fila_{{ $row->id }}">
                    	<td> 
                    		
                    	</td>
                    	<td></td>

                         
                         <td  style="text-align: right;" ><!-- $  --> {{-- format_numero( $counter ) --}} </td>
                         <td> </td>
                         <td>  </td>
                         <td> 
                             
                        </td>
                    </tr>
                
                
                <?php
                } else {  ?>
                <tr>
                    <td rowspan="5">@lang('common.no_record_found') </td> 
                </tr>
<?php } ?>

            </tbody>
        </table>
		{{  $expenses->render() }}
    </div>
    </table-responsive>
</div>
</div>
</div>
</div>
</div>

<?php

function clean($string) 
{
    $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
    return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}
?>




<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Agregar</h4>
            </div>
            <form role="form" action="<?php echo url("expenses/save"); ?>" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    {!! csrf_field() !!} 

                    <div class="form-group">
                        <label> Nombre </label>
                        <input class="form-control" required type="text" id="title" name="title">
                        <input class="form-control" type="hidden" id="id" name="id">
                    </div>
                    
					
					<div class="form-group">
                        <label> Descripción </label>
                        <textarea class="form-control" id="description" name="description"></textarea>
                    </div>
                    
					<div class="form-group">
                        <label> Costo </label>
                        <?php /*input class="form-control" type="number" id="price" name="price" */ ?>
						<input class="form-control" type="number" id="price" name="price"  step="0.01"  / required>
                    </div>
                  


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script type="text/javascript">
    $("body").on("click", ".add_new___", function () {
        $("#name").val("");  
		$("#title").val("");  /* ~~~~ */
        $("#description").val("");
        $("#price").val("");
        $("#id").val("");
    });
    $("body").on("click", ".edit_AAEEIIOOUUU", function () {
        var id = $(this).attr("data-id");
        var form_data = {
            id: id
        };
        $.ajax({
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '<?php echo url("expenses/get"); ?>',
            data: form_data,
            success: function (msg) {
                var obj = $.parseJSON(msg);
                $("#title").val(obj['title']);
                $("#description").val(obj['description']);
                $("#price").val(obj['price']);
                $("#id").val(obj['id']);
            }
        });

    });


    $("body").on("click", ".delete_aaaaeeeeiiioouuu", function () {
        var id = $(this).attr("data-id");
        var form_data = {
            id: id
        };
        $.ajax({
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '<?php echo url("expenses/delete"); ?>',
            data: form_data,
            success: function (msg) {
                $("#" + id).hide(1);
            }
        });
    });

</script>



@endsection
