<?php


$suma_total = 0;
$descuento = 0;


?>



<br />
<p align="center"><input type="button" id="pr2" value="Imprimir Ticket" onclick="printpage()" class="btn btn-success noprint" /> </p>
<p align="center"><a style="text-align:center" href="{{ route('newsale.create') }}"  class="noprint"> @lang('slip.back') </a> </p>

<div class="body">

<?php $currency =  setting_by_key("currency"); ?>
     <div class="page" >
      <br>
      <br>

<table width="90%" cellpadding="10" class="tableS" cellspacing="10" style="font-family: 'Montserrat', sans-serif; font-size: 12px !important;margin-left:20px">
 <tbody><tr>
    <td colspan="2" style="text-align:center" class="noborder"><img src="{{url('uploads/logo.jpg')}}" width="220" alt="PRA"></td>
    
 </tr>

</table>

<table width="90%" cellpadding="10" class="tableS" cellspacing="10" style="font-family: 'Montserrat', sans-serif; font-size: 15px !important;margin-left:2px; width: 415px;" >
    <thead>
 
 <tr>
    <td colspan="2" class="noborder">@lang('slip.date'):
 <?php   
	echo  $sale->created_at->format('d/m/Y');
	?>
        
    </td>
     <td colspan="3"  class="noborder" align="right"> @lang('slip.time'): 
	 <?php 
	 /*echo date('h:i A');  */
	 echo  $sale->created_at->format('h:i A');
	 
	 
	 ?> </td>
 </tr>
 <tr>
    	<td class="noborder" colspan="5">Nota: {{ $sale->invoice_no }}</td>

 </tr>
 <!-- tr>
         <td colspan="5" class="noborder">&nbsp;</td>
 </tr -->
 <tr>
         <td colspan="5" class="noborder">
			<hr class="style9"/>
			
			
			
		 </td>
 </tr>
 
 <tr>
    <td width="15"><strong>@lang('slip.s_no')</strong></td>
    <td width="150"><strong>Desc.</strong></td>
    <td id="kitchenph" width="100"><strong>P.U.</strong></td>
	<td width="15"><strong>Cant.</strong></td>
    <td id="kitchentotalh" width="60"><strong>Importe</strong></td>
 </tr>
</thead>


        


    <?php $i=1; ?>
                    @foreach($sale->items as $item)
                       
                            <?php
                            if($i%35==0) {
                                //$page_break = "page-break-after: always;";
                                ?>
                           
   <tr height colspan="5" class="tableStyle">
  
       <td>
                               <table width="90%" cellpadding="10" class="tableS" cellspacing="10" style="margin-left:20px">
                                <tr>

                                    
  
 
                                </tr>

                                </table>
 
       </td>
   </tr>
                         
                                <!-- $page_break = "page-break-after: always;"; -->
                                <?php
                            }

                            ?>
                      <tr>
                            <td width="15">0<?php echo $i; ?></td>
                            							     
							<td width="100">
							@if( $item->isOpen == true )
								{{ $item->nombreOpenProducto }}
							@else
								{{ $item->nombreLargo }}
							@endif
							  
							<td class="kitchen" width="50"><?php echo $currency; ?>{{$item->price}}</td>
							<td width="15">{{ $item->quantity }}</td>
                            
                            <td class="kitchen" width="50" style="text-align: right;"><?php echo $currency; ?>{{ number_format($item->quantity * $item->price,2) }}</td>
                        </tr>
                        <?php $i++;  ?>
                       @endforeach
					   <tr>
							<td class="kitchen" width="20" colspan="5">
								<hr class="style3"  />
							</td>
					   
					   </tr>
						
						<tr>
							<td colspan="4">Subtotal:</td>
							<td class="kitchen" width="50" style="text-align: right;">{{format_peso($sale->amount)}}</th>
						</tr>

                        @if(!empty($sale->discount) && $sale->discount > 0)
                        <?php $descuento = $sale->discount; ?>
                        <tr>
                            <td colspan="4">Descuento:</td>
                            <td class="kitchen" width="50" style="text-align: right;">{{format_peso($descuento)}}</td>
                        </tr>
                        @endif
						
						
						<?php  
							/* $sale->value_tip = 0.0;   */
						?>
						
						@if(!empty($sale->value_tip) && $sale->value_tip > 0)
						<tr>
							<td colspan="4">Propina Opc:</td>
							<td class="kitchen" width="50" style="text-align: right;">{{format_peso($sale->value_tip)}}</td>
						</tr>
						@endif
						
						
						@if("card" ===  $sale->payment_with)
						<tr>
							<td colspan="4">Cargo Tarjeta:</td>
							<td class="kitchen" width="50" style="text-align: right;">{{format_peso($sale->tax_card)}}</td>
						</tr>
						@endif
						
						
						
						<tr>
							<td class="kitchen" width="20" colspan="3">
								&nbsp;
							</td>
							<td class="kitchen" width="20" colspan="2">
								<hr class="style3"  />
							</td>
						</tr>
						<!-- Total a Pagar   -->
						<?php 

                        //    $suma_total = $sale->amount - $descuento;





						$final_total = $sale->amount - $descuento;
						if (!empty($sale->value_tip) && $sale->value_tip > 0){
							$final_total = $sale->amount + $sale->value_tip ;


						}
						
						if ("card" ===  $sale->payment_with){
							$final_total  = $final_total + $sale->tax_card;

						}
							
						?>
						<tr style=" font: 190% monospace;  font-variant-numeric: slashed-zero;  ">
							<td class="kitchen" width="20" colspan="3" style="padding-left- 1px">

								&nbsp;  Total a Pagar
							</td>
							<td style="font-weight: bold;"  width="20" colspan="2">
								<p>{{format_peso($final_total)}}</p>
							</td>
						</tr>

						  
   
</table>

<table style="page-break-inside:avoid;font-family: Times New Roman; font-size: 16.5px !important;margin-left:20px" width="90%" cellpadding="5" class="tableS kitchen" cellspacing="5" id="kitchen">


    <?php /*<tr>
    <td></td>
    <td></td>
    <td><strong>SERVICE CHARGRES</strong></td>
    <td><strong><?php echo $invoince->service_charge_per; ?> %</strong></td>
    <td><strong><?php echo trim(str_replace(" ","",$invoince->service_charge_amt)); ?></strong></td>
    </tr>  
    <tr>
    <td></td>
    <td></td>
    <td><strong>PST</strong></td>
    <td><strong>16 %</strong></td>
    <td><strong><?php echo trim(str_replace(" ","",$invoince->inv_invoice_tax_amount)); ?></strong></td>
    </tr> */ ?>  
 
 
 <tr>
    <td colspan="5">
		<hr />
	</td>
 </tr>
 
 
 
 <?php /*
 <tr>
    <td colspan="3"><strong>@lang('slip.tax'):</strong></td>
    <td><strong></strong></td>
    <td class="grandtotalFont"><strong><?php echo $currency; ?>{{number_format($sale->vat,2)}}</strong></td>
 </tr> 
 */ ?>
 
 {{--   if($sale->discount > 0 and !empty($sale->discount)   --}}
 @if(!empty($sale->discount and $sale->discount > 0 ))
  <!--   Comentado   tr>
    <td colspan="3"><strong>@lang('slip.discount'):</strong></td>
    <td><strong></strong></td>
    <td class="grandtotalFont"><strong><?php echo $currency; ?>{{number_format($sale->discount,2)}}</strong></td>
 </tr   -->  
 @endif
 
  
 
  
 
  <tr>
    <td colspan="2"><strong>@lang('slip.payment_with'):</strong></td>
    <td class="grandtotalFont"><strong><?php if($sale->payment_with == "cash") { echo "Efectivo"; } else { echo "Tarjeta"; } ?></strong></td>
    <td class="grandtotalFont" style="text-align:right"><strong>{{format_peso($sale->total_given)}}</strong></td>
	<td> &nbsp; </td>
 </tr>
 
 <tr>
    <td colspan="2"></td>
    <td class="grandtotalFont"><strong>Cambio: </td>
    <td class="grandtotalFont" style="text-align:right"><strong>{{format_peso($sale->change)}}</strong></td>
	<td> &nbsp; </td>
 </tr>
 
 
 
 
 <?php 
	//  {{-- Se Cambia el manejo del total --}}
	// $sale->total_given;
	$print_total = 0.00;
	if($sale->payment_with == "card"){
		$print_total = $sale->total_given;
	} else {
		$print_total = $sale->subtotal - $sale->discount;
	}
 
 ?>
 
 
 
 
 
 <!-- tr>
    <td colspan="3"><strong>@lang('slip.grand_total'):</strong></td>
    <td><strong></strong></td>
    <?php //td class="grandtotalFont"><strong><?php echo $currency; ?  >{{number_format($sale->subtotal - $sale->discount + $sale->vat,2)}}</strong></td ?>
	<td class="grandtotalFont"><strong><?php echo $currency; ?>{{number_format($print_total, 2)}}</strong></td>
 </tr --> 


 <!--  tr>
    <td colspan="3"><strong>Cambio:</strong></td>
    <td><strong></strong></td>
    <td class="grandtotalFont"><strong><?php echo $currency; ?>{{number_format($sale->change,2)}}</strong></td>
 </tr -->

  <tr>
  <td class="removeborder"></td>
    <td class="removeborder">&nbsp;</td>
    <td class="removeborder"></td>
    <td class="removeborder"></td>
    <td class="removeborder"></td>
 </tr> 
 <tr>
  <td class="removeborder"></td>
    <td class="removeborder">&nbsp;</td>
    <td class="removeborder"></td>
    <td class="removeborder"></td>
    <td class="removeborder"></td>
 </tr> 
 

  <tr>
    <td colspan="5" align="center">
    <strong>@lang('slip.thanks_visting') <br /> 
	
	
	<p>
		 <?php  /*echo json_encode ($sale);   works  NumComanda  */ 
	if ($sale->folio !== NULL && $sale->folio !== '')  { ?>
		Folio:  {{ $sale->folio }}
	<?php  } else  { ?>
		
	<?php  }  ?>
	
	
	</p>
	
	<br />
	{{setting_by_key('leyendaTicked')}} <br /> {{setting_by_key('address')}}.
	<br/>
	<br /> Tel(s). 	{{setting_by_key('phone')}},  {{setting_by_key('phone02')}}</strong>
	<br />
	</td>
    
 </tr>  
 <tr />
 <tr />
 <tr />

 
 
</table>
    

</div>

</div>

<p align="center"><input type="button" id="pr" value="Imprimir" onclick="printpage()" class="btn btn-success noprint" /> </p>
<p align="center"><a style="text-align:center" href="{{ route('newsale.create') }}"  class="noprint"> @lang('slip.back') </a> </p>


 
    


<script type="text/javascript">
    function printpage() {
        //Get the print button and put it into a variable
        var printButton = document.getElementById("pr");
        var printButton2 = document.getElementById("pr2");
       // var printButtonk = document.getElementById("prK");
        //Set the print button visibility to 'hidden' 
       // printButton.style.visibility = 'hidden';
       // printButtonk.style.visibility = 'hidden';
       //printButton.style.display = 'none';
       printButton2.style.display = 'none';


        document.title = "";
        document.URL   = "";
        
        //Print the page content
        window.print()
        //Set the print button to 'visible' again 
        //[Delete this line if you want it to stay hidden after printing]
        //printButton.style.visibility = 'visible';
        //printButton.style.display = 'none';
        printButton2.style.display = 'block';

       // printButtonk.style.visibility = 'visible';
        
        
    }
</script>

<script type="text/javascript">
    function printpageK() {
        //Get the print button and put it into a variable
        var printButton = document.getElementById("pr");
        //var printButtonk = document.getElementById("prK");
        var kitchen = document.getElementsByClassName("kitchen");
        //Set the print button visibility to 'hidden' 
        for(var i = 0; i < kitchen.length; i++){
            kitchen[i].style.visibility = "hidden"; 
        }
        //printButton.style.visibility = 'hidden';
        //printButtonk.style.visibility = 'hidden';
        
        document.title = "";
        document.URL   = "";
        
        //Print the page content
        window.print()
        //Set the print button to 'visible' again 
        //[Delete this line if you want it to stay hidden after printing]
        printButton.style.visibility = 'visible';
       // printButtonk.style.visibility = 'visible';
        for(var i = 0; i < kitchen.length; i++){
            kitchen[i].style.visibility = "visible"; 
        }
        
    }
</script>


<script type="text/javascript">
    //function changePageTitle() {
        newPageTitle = 'Nota: {{ $sale->invoice_no }} - Piso 73';
        document.title = newPageTitle;
    //}
</script>




<style>
hr.style3 {
	border-top: 2px dashed #050000;
}

hr.style9 {
	border-top: 2px dashed #070000;
	border-bottom: 2px dashed #fff;
}






.tableS { margin-left: 20px; margin-top:10px; font-family:'Verdana, Geneva, sans-serif'; }
    .tableS tr td  {  padding:2px; font-family:Verdana, Geneva, sans-serif; }
    .tableS tr td.noborder { border:none;  }

.removeborder {border:none !important; }
    body {
        width: 100%;
        height: 100%;
        margin: 0;
        padding: 0;
        background-color: #FAFAFA;
        font-size: 12pt;
font-family:'monserrat';

    }
    * {
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        
        font-family: 'Montserrat', sans-serif;
    }
    .page {
     
       width: 12cm;
            height: auto; 
     
        margin: 10mm auto;
       
      
        background: white;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);

        font-weight: normal;
              font-size: 20px !important;
              font-family: Calibri,  sans-serif;
              /*font-family:Verdana, Geneva, sans-serif; */

             
    }
    .font_size
    {
      font-size: 8em "tahoma";
      font-family:Verdana, Geneva, sans-serif;
    }
    .subpage {
        padding: 1cm;
        width: 15cm;
        height: 15.8cm;
        font-family: calibri, Lucida Sans,  sans-serif;
        /*font-family:Verdana, Geneva, sans-serif;*/
       
    }
    
    .grandtotalFont { 
        /*font-size:10em "tahoma";*/ 
        
        font-family: 'Montserrat', sans-serif;
    }
    
    @page {
        size: auto;
        margin:0;
        margin-top: 0;
        font-family:Verdana, Geneva, sans-serif;
    }


 
 
    
    
    @media print {
        html, body {
            /*width: 10cm; */
            width: 11cm;
            height: auto; 
            /*font-size: 13px;   */
           margin: 0 auto;  
           /*font-family:Verdana, Geneva, sans-serif; */
       
        }
    


    table {
            -fs-table-paginate: paginate;
/*             font-family:Verdana, Geneva, sans-serif; */
        }


        .page {
            margin: 0;
            border: initial;
            border-radius: initial;
            width: initial;
            min-height: initial;
            box-shadow: initial;
            background: initial;
            font-family:Verdana, Geneva, sans-serif;
        }
.removeborder {border:none; }


       .form-horizontal,label{
              font-weight: normal;
              font-size: 9px !important;
              font-family:Verdana, Geneva, sans-serif;
              
        }
        .testing {
             display: block;
             font-family:Verdana, Geneva, sans-serif;
           /* page-break-after: always !important;*/
        }
        .tableStyle {
            
            page-break-after: always !important;
            font-family:Verdana, Geneva, sans-serif;

        }
        .tableStyle:last-child {
     page-break-after: none;
     font-family:Verdana, Geneva, sans-serif;
}

.page table tr td  {   padding:2px; font-family:Verdana, Geneva, sans-serif; }
       .form-horizontal,label{
              font-weight: normal;
              font-size: 9px !important;
              font-family:Verdana, Geneva, sans-serif;
              
        }
        
        .grandtotalFont { font-size:10em "tahoma"; }

    }
	
	
	@media print {
               .noprint {
                  visibility: hidden;
               }
            }
	
</style>
