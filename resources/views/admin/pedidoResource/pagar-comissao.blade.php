@extends('templates.admin-login')

@section('css-view')
@endsection

@section('js-view')
@endsection

@section('templates.menu-superior-admin')
@endsection

@section('conteudo-view')  
<div id="line-one">
	<div id="line-one">

		<div class="container">

      <div class="row" style="height: 80px; width: 1170px; position: fixed; background-color: white; z-index: 1001; top: 50px; margin-bottom: 80px;">
        <div class="col-md-12">
          <h2>Pagamento de Comissão</h2>
          <div class="divider" style="margin-bottom: 2px; margin-top: -8px;" ></div>
        </div>
      </div>	

      <div class="row" style="height: 80px; width: 1170px; position: fixed; z-index: 1001; top: 100px; ">
        <div class="col-md-12">   
         @if (Session::has('mensagem-sucesso'))
         <div class="alert alert-success alert-dismissible fade in" style="margin-bottom: 1px;">
          <strong>{{ Session::get('mensagem-sucesso') }}</strong>
          <a href="#" class="close" 
          data-dismiss="alert"
          aria-label="close">&times;</a>
        </div>
        <script type="text/javascript">
          $(".alert-dismissible").fadeTo(2000, 500).slideUp(500, function(){
            $(".alert-dismissible").alert('close');
          });
        </script>
        @endif
        @if (Session::has('mensagem-falha'))
        <div class="alert alert-danger alert-dismissible fade in" style="margin-bottom: 1px;">
          <strong>{{ Session::get('mensagem-falha') }}</strong>
          <a href="#" class="close" 
          data-dismiss="alert"
          aria-label="close">&times;</a>
        </div>
        <script type="text/javascript">
          $(".alert-dismissible").fadeTo(2000, 500).slideUp(500, function(){
            $(".alert-dismissible").alert('close');
          });
        </script>
        @endif

        <div class="card-panel" style="height: 80px; padding: 15px 10px;">    

         <div class="row" style="margin-bottom: -10px;">
          <form method="POST" action="{{ route('calcular.comissao', $pedidos->id) }}">
           {{ csrf_field() }} 

           @php
$total_geral =0;

@endphp

@foreach ($pedidos->itens_pedido as $item_pedido)
@php
$desconto = $item_pedido->totalDesconto;
@endphp
@if ($desconto > 0)
@php

$total_geral += $item_pedido->total;
$total_geral -= $item_pedido->totalDesconto
@endphp
@else
@php
$total_geral += $item_pedido->total;
@endphp
@endif

@endforeach

@foreach($pedidos->itens_pedido_request as $item_pedido)
@php
$desconto = $item_pedido->totalDesconto;
@endphp
@if ($desconto > 0)

@php
$total_geral += $item_pedido->total;
$total_geral -= $item_pedido->totalDesconto
@endphp

@else
@php
$total_geral += $item_pedido->total;
@endphp
@endif


@endforeach



           <div class="col-md-5"> 
             <div class="col-md-2">          
               <label style="font-size: 12px;">Pedido
                <input type="text" readonly name="pedido_id" value="{{ $pedidos->id }}"> </label>
              </div> 
              <div class="col-md-3">    
               <label style="font-size: 12px;">Data
                <input type="text" readonly value="{{ $pedidos->created_at->format('d/m/Y') }}"  title="{{ $pedidos->created_at->format('d/m/Y H:i') }}"> </label>
              </div>             			               
              <label style="font-size: 12px;">Cliente                      
                <input type="text" readonly value="{{$pedidos->Cliente->name}}"></label>
                <input type="hidden" name="cliente_id"  value="{{$pedidos->Cliente->id}}">
              </div> 
              <div class="col-md-7">                 
               <input type="hidden" name="valor_pedido" value="{{ ($total_geral) }}">
               <div class="col-md-5"> 
                 <label style="font-size: 12px;">Vendedor
                   <input type="text" title="Vendedor" readonly value="{{$pedidos->Vendedor->name}}"></label>
                   <input type="hidden" name="vendedor_id"  value="{{$pedidos->Vendedor->id}}">      
                 </div>
                 <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			var maskBehavior = function (val) {
				return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
			},
			options = {onKeyPress: function(val, e, field, options) {
				field.mask(maskBehavior.apply({}, arguments), options);
			}
		};

		/*$('.phone').mask(maskBehavior, options);
		$('.money').mask('000.000.000.000.000,00', {reverse: true}).attr('maxlength','6'); 
		$('.cep').mask('00000-000');*/
		$('.percent').mask('000%', {reverse: true}).attr('maxlength','4');

	});
</script>

                 <div class="col-md-3">
                   <label style="font-size: 12px;">Comissão(R$)
                    <input type="text" readonly name="valor_comissao" value="@if($pedidos->calculo_comissao == 'S')R$ {{ number_format($pedidos->Comissao->valor_comissao, 2, ',', '.')}} @else R$ {{ number_format($pedidos->Comissao->valor_comissao, 2, ',', '.')}} @endif"></label> 
                  </div> 
                   <div class="col-md-2"> 
                  <label style="font-size: 12px;">Comissão(%)
                    <input type="text" name="percent_comissao" class="percent" title="Comissão de 1% à 100%" placeholder="{{ $pedidos->percentual_comissao }}%"  required></label>        
              </div>
                         <button type="submit" class="btn btn-small btn-primary" style="margin-top: 15px; font-size: 12px;" data-position="top" data-delay="50" data-toggle="tooltip" data-placement="top" title="Calcular comissão sobre o valor total do pedido." disabled="disabled"><!--<i class="fa fa-money"></i>-->
                    <b>Calcular</b>
                  </button> 

                    </div>
                 <!--<div class="col-md-6"> 
                 <div class="col-md-5"> 
                   <label style="font-size: 12px;">Observações                      
                     <input type="text" name="obs_comissao"  placeholder="Obs."></label>
                   </div>
                   <div class="col-md-3">
                     <label style="font-size: 12px;">Comissão(R$)
                      <input type="text" name="valor_comissao" readonly placeholder="0,00"></label> 
                    </div>

                        <button type="submit" class="btn btn btn-primary float-right" style="margin-top: 15px;" data-position="top" data-delay="50" data-toggle="tooltip" data-placement="bottom" title="Comissão sobre o valor total do pedido!"><i class="fa fa-money"></i>
                    <b>Pagar</b>
                  </button> 

              
                

                  </div> -->

                </form>



              </div>
            </div>


          </div>

        </div>


        <div class="row" style="margin-top: 130px;">
          <div class="col-md-12"> 	
            @if ((isset($pedidos->produto_id)) && (empty($pedidos->request_id)))
            <div class="table-responsive">
             <table class="table-sm table-bordered">
              <thead>

               <tr style="background-color: #fcf8e3;">                    
                <th >Código</th>
                <th >Produto</th>
                <th >Qtd</th>              
                <th  title="Preço unitário">Preço Unitário</th>
                <th  title="Desconto unitário">Desconto</th>
                <th >Total</th>

              </tr>
            </thead> 

            <tbody> 
             @php
             $total_pedido =0;
             @endphp

             @foreach ($pedidos->itens_pedido as $item_pedido)
             <tr> 

              <td >
                {{ $item_pedido->product->prod_cod }}
              </td> 
              <td  >
               {{ $item_pedido->product->prod_desc}}
             </td>
             <td  >
               {{ $item_pedido->quantidade}}
             </td>


             @php  
             $desconto = $item_pedido->totalDesconto;
             $desconto_ratiado = ($desconto /$item_pedido->quantidade);     

             @endphp

             @if ($desconto > 0)
             @php 
             $preco_com_desconto = $item_pedido->product->prod_preco_padrao - $desconto_ratiado;
             $percentual_total = ($desconto/$item_pedido->product->prod_preco_padrao)*100;
             $percentual_desconto = $percentual_total/$item_pedido->quantidade;
             @endphp


             <td title="R$ {{ number_format($preco_com_desconto, 2, ',', '.')}}  por unidade com desconto" ><b style="text-decoration: line-through;">
               R$ {{ number_format($item_pedido->product->prod_preco_padrao, 2, ',', '.')}}</b>
               <br>
               R$ {{ number_format($preco_com_desconto, 2, ',', '.')}}
             </td>
             @else 
             <td>
              R$ {{ number_format($item_pedido->product->prod_preco_padrao, 2, ',', '.')}}
            </td>  
            @endif       


            @php  
            $total_prod_desconto = $item_pedido->totalDesconto;   
            @endphp


            <td> 
             @if($desconto > 0)   
             R$ {{ number_format($total_prod_desconto, 2, ',', '.')}}     

             @else
             R$ 0,00

             @endif 



           </td> 
           @if ($desconto > 0)

           @php

           $total_produto = $item_pedido->total;
           $total_desconto = $item_pedido->totalDesconto;

           $total_pedido += $total_produto;
           $total_pedido -= $total_desconto;
           $total_produto_com_desconto = $total_produto - $total_desconto;
           @endphp

           @else
           @php
           $total_produto = $item_pedido->total;
           $total_pedido += $total_produto;          
           @endphp  


           @endif

           <td>
             @if ($desconto > 0)

             R$ {{ number_format($total_produto_com_desconto, 2, ',', '.')}}


             @else
             R$ {{ number_format($total_produto, 2, ',', '.') }}
             @endif

           </td>


         </tr>
         @endforeach

         <tr><td colspan="4" style="text-align: center;"><b></b></td>
          @php
          $total_desconto_pedido = 0;
          @endphp

          @foreach ($pedidos->itens_pedido as $item_pedido)
          @php    
          $total_desc_geral = $item_pedido->totalDesconto;
          $total_desconto_pedido -= $total_desc_geral;

          @endphp
          @endforeach
          <td><b style="color: red;">R$ {{ number_format($total_desconto_pedido, 2, ',', '.') }}</td>
            <td><b>R$ {{ number_format($total_pedido, 2, ',', '.') }}</b></td>
          </tr>

        </tbody>    
      </table>
    </div>
    <div style="margin-bottom: 5px; margin-top: 3px;"></div>



    @elseif ((isset($pedidos->request_id)) && (empty($pedidos->produto_id)))
    <div class="table-responsive">
     <table class="table-sm table-bordered" >

      <thead>

       <tr style="background-color: #fcf8e3;">                    
        <th >Código</th>
        <th >Requisição</th>
        <th >Qtd</th>              
        <th  title="Preço unitário">Preço Unitário</th>
        <th  title="Desconto unitário">Desconto</th>
        <th >Total</th>


      </tr>
    </thead> 

    <tbody> 


     @php
     $total_pedido =0;
     @endphp

     @foreach ($pedidos->itens_pedido_request as $item_pedido)
     <tr> 

      <td >
       {{ $item_pedido->request->request_cod}}
     </td> 
     <td  >
       {{ $item_pedido->request->request_desc}}
     </td>
     <td  >
       {{ $item_pedido->quantidade}}

     </td>
     @php  
     $desconto = $item_pedido->totalDesconto;
     $desconto_ratiado = ($desconto /$item_pedido->quantidade);

     @endphp

     @if ($desconto > 0)

     @php 
     $preco_com_desconto = $item_pedido->request->request_valor - $desconto_ratiado;
     $percentual_total = ($desconto/$item_pedido->request->request_valor)*100;
     $percentual_desconto = $percentual_total/$item_pedido->quantidade;
     @endphp
     <td  title="R$ {{ number_format($preco_com_desconto, 2, ',', '.')}} por unidade com desconto">
       <b style="text-decoration: line-through;"> R$ {{ number_format($item_pedido->request->request_valor, 2, ',', '.')}}</b>
       <br>
       R$ {{ number_format($preco_com_desconto, 2, ',', '.')}}
     </td> 
     @else 
     <td>  
      R$ {{ number_format($item_pedido->request->request_valor, 2, ',', '.')}}   
    </td>
    @endif

    @php  
    $total_request_desconto = $item_pedido->totalDesconto;
    @endphp

    <td> 
      @if($desconto > 0)  
      R$ {{ number_format($total_request_desconto, 2, ',', '.')}}

      @else
      R$ 0,00
      @endif        

    </td>


    @if ($desconto > 0)

    @php

    $total_produto = $item_pedido->total;
    $total_desconto = $item_pedido->totalDesconto;

    $total_pedido += $total_produto;
    $total_pedido -= $total_desconto;
    $total_produto_com_desconto = $total_produto - $total_desconto;
    @endphp

    @else
    @php
    $total_produto = $item_pedido->total;
    $total_pedido += $total_produto;          
    @endphp  


    @endif

    <td>
     @if ($desconto > 0)

     R$ {{ number_format($total_produto_com_desconto, 2, ',', '.')}}

     
     @else
     R$ {{ number_format($total_produto, 2, ',', '.') }}
     @endif
   </td>
 </tr>



 @endforeach

 <tr><td colspan="4"  style="text-align: center;"><b></b></td>

  @php
  $total_desconto_pedido = 0;
  @endphp

  @foreach ($pedidos->itens_pedido_request as $item_pedido)
  @php

  $total_desc_geral = $item_pedido->totalDesconto;
  $total_desconto_pedido -= $total_desc_geral;

  @endphp
  @endforeach


  <td>
    <b style="color: red;">
      R$ {{ number_format($total_desconto_pedido, 2, ',', '.') }}
    </b>
  </td>
  <td>
    <b>
      R$ {{ number_format($total_pedido, 2, ',', '.') }}
    </b>
  </td>

</tr>

</tbody>    
</table>
</div>
<div class="divider" style="margin-bottom: 3px; margin-top: 5px;"></div>

@else((isset($pedidos->request_id)) && (isset($pedidos->produto_id)))

<div class="table-responsive">
 <table class="table-sm table-bordered">
  <thead>

   <tr style="background-color: #fcf8e3;">                    
    <th >Código</th>
    <th >Produto</th>
    <th >Qtd</th>              
    <th  title="Preço unitário">Preço Unitário</th>
    <th  title="Desconto unitário">Desconto</th>
    <th >Total</th>

  </tr>
</thead> 

<tbody> 
 @php
 $total_pedido =0;
 @endphp

 @foreach ($pedidos->itens_pedido as $item_pedido)
 <tr> 

  <td >
    {{ $item_pedido->product->prod_cod }}
  </td> 
  <td  >
   {{ $item_pedido->product->prod_desc}}
 </td>
 <td  >
   {{ $item_pedido->quantidade}}
 </td>


 @php  
 $desconto = $item_pedido->totalDesconto;
 $desconto_ratiado = ($desconto /$item_pedido->quantidade);     

 @endphp

 @if ($desconto > 0)
 @php 
 $preco_com_desconto = $item_pedido->product->prod_preco_padrao - $desconto_ratiado;
 $percentual_total = ($desconto/$item_pedido->product->prod_preco_padrao)*100;
 $percentual_desconto = $percentual_total/$item_pedido->quantidade;
 @endphp


 <td title="R$ {{ number_format($preco_com_desconto, 2, ',', '.')}}  por unidade com desconto" ><b style="text-decoration: line-through;">
   R$ {{ number_format($item_pedido->product->prod_preco_padrao, 2, ',', '.')}}</b>
   <br>
   R$ {{ number_format($preco_com_desconto, 2, ',', '.')}}
 </td>
 @else 
 <td>
  R$ {{ number_format($item_pedido->product->prod_preco_padrao, 2, ',', '.')}}
</td>  
@endif       


@php  
$total_prod_desconto = $item_pedido->totalDesconto;   
@endphp


<td> 
 @if($desconto > 0)   
 R$ {{ number_format($total_prod_desconto, 2, ',', '.')}}     

 @else
 R$ 0,00

 @endif 



</td> 
@if ($desconto > 0)

@php

$total_produto = $item_pedido->total;
$total_desconto = $item_pedido->totalDesconto;

$total_pedido += $total_produto;
$total_pedido -= $total_desconto;
$total_produto_com_desconto = $total_produto - $total_desconto;
@endphp

@else
@php
$total_produto = $item_pedido->total;
$total_pedido += $total_produto;          
@endphp  


@endif

<td>
 @if ($desconto > 0)

 R$ {{ number_format($total_produto_com_desconto, 2, ',', '.')}}


 @else
 R$ {{ number_format($total_produto, 2, ',', '.') }}
 @endif

</td>


</tr>
@endforeach

<tr><td colspan="4" style="text-align: center;"><b></b></td>
  @php
  $total_desconto_pedido = 0;
  @endphp

  @foreach ($pedidos->itens_pedido as $item_pedido)
  @php    
  $total_desc_geral = $item_pedido->totalDesconto;
  $total_desconto_pedido -= $total_desc_geral;

  @endphp
  @endforeach
  <td><b style="color: red;">R$ {{ number_format($total_desconto_pedido, 2, ',', '.') }}</td>
    <td><b>R$ {{ number_format($total_pedido, 2, ',', '.') }}</b></td>
  </tr>

</tbody>    
</table>
</div>
<div class="divider" style="margin-bottom: 3px; margin-top: 5px;"></div>

<div class="table-responsive">
  <table class="table-sm table-bordered" >

    <thead>

      <tr style="background-color: #fcf8e3;">                    
        <th >Código</th>
        <th >Requisição</th>
        <th >Qtd</th>              
        <th  title="Preço unitário">Preço Unitário</th>
        <th  title="Desconto unitário">Desconto</th>
        <th >Total</th>


      </tr>
    </thead> 

    <tbody> 


     @php
     $total_pedido =0;
     @endphp

     @foreach ($pedidos->itens_pedido_request as $item_pedido)
     <tr> 

      <td >
      {{ $item_pedido->request->request_cod}}
     </td> 
     <td  >
       {{ $item_pedido->request->request_desc}}
     </td>
     <td  >
       {{ $item_pedido->quantidade}}

     </td>
     @php  
     $desconto = $item_pedido->totalDesconto;
     $desconto_ratiado = ($desconto /$item_pedido->quantidade);

     @endphp

     @if ($desconto > 0)

     @php 
     $preco_com_desconto = $item_pedido->request->request_valor - $desconto_ratiado;
     $percentual_total = ($desconto/$item_pedido->request->request_valor)*100;
     $percentual_desconto = $percentual_total/$item_pedido->quantidade;
     @endphp
     <td  title="R$ {{ number_format($preco_com_desconto, 2, ',', '.')}} por unidade com desconto">
       <b style="text-decoration: line-through;"> R$ {{ number_format($item_pedido->request->request_valor, 2, ',', '.')}}</b>
       <br>
       R$ {{ number_format($preco_com_desconto, 2, ',', '.')}}
     </td> 
     @else 
     <td>  
      R$ {{ number_format($item_pedido->request->request_valor, 2, ',', '.')}}   
    </td>
    @endif

    @php  
    $total_request_desconto = $item_pedido->totalDesconto;
    @endphp

    <td> 
      @if($desconto > 0)  
      R$ {{ number_format($total_request_desconto, 2, ',', '.')}}

      @else
      R$ 0,00
      @endif        

    </td>


    @if ($desconto > 0)

    @php

    $total_produto = $item_pedido->total;
    $total_desconto = $item_pedido->totalDesconto;

    $total_pedido += $total_produto;
    $total_pedido -= $total_desconto;
    $total_produto_com_desconto = $total_produto - $total_desconto;
    @endphp

    @else
    @php
    $total_produto = $item_pedido->total;
    $total_pedido += $total_produto;          
    @endphp  


    @endif

    <td>
     @if ($desconto > 0)

     R$ {{ number_format($total_produto_com_desconto, 2, ',', '.')}}

     
     @else
     R$ {{ number_format($total_produto, 2, ',', '.') }}
     @endif
   </td>
 </tr>



 @endforeach

 <tr><td colspan="4"  style="text-align: center;"><b></b></td>

  @php
  $total_desconto_pedido = 0;
  @endphp

  @foreach ($pedidos->itens_pedido_request as $item_pedido)
  @php

  $total_desc_geral = $item_pedido->totalDesconto;
  $total_desconto_pedido -= $total_desc_geral;

  @endphp
  @endforeach


  <td>
    <b style="color: red;">
      R$ {{ number_format($total_desconto_pedido, 2, ',', '.') }}
    </b>
  </td>
  <td>
    <b>
      R$ {{ number_format($total_pedido, 2, ',', '.') }}
    </b>
  </td>

</tr>

</tbody>    
</table>
</div>
<div class="divider" style="margin-bottom: 3px; margin-top: 5px;"></div>

@endif

@php
$total_geral =0;

@endphp

@foreach ($pedidos->itens_pedido as $item_pedido)
@php
$desconto = $item_pedido->totalDesconto;
@endphp
@if ($desconto > 0)
@php

$total_geral += $item_pedido->total;
$total_geral -= $item_pedido->totalDesconto
@endphp
@else
@php
$total_geral += $item_pedido->total;
@endphp
@endif

@endforeach

@foreach($pedidos->itens_pedido_request as $item_pedido)
@php
$desconto = $item_pedido->totalDesconto;
@endphp
@if ($desconto > 0)

@php
$total_geral += $item_pedido->total;
$total_geral -= $item_pedido->totalDesconto
@endphp

@else
@php
$total_geral += $item_pedido->total;
@endphp
@endif


@endforeach

<div class="divider" style="margin-bottom: 0px;"></div>
<p class="lead" id="black" style="text-align: right;">Total R$ {{ number_format($total_geral, 2, ',', '.') }}</p>
<div class="divider" style="margin-bottom: 10px; margin-top: -20px;"></div>




<a href="{{route('pedido.comissoes')}}" class="btn btn-default"><b>VOLTAR</b></a>
@if ($pedidos->calculo_comissao == 'S')
  <button  onclick="myFunction()" title="Realizar pagamento" class="btn waves-effect amber">
  	<strong>Pagar Comissão</strong>   
  	<script type="text/javascript">
  		function myFunction() {
  			document.getElementById("myCheck").click();
  		}
  	</script>
  </button> 
  @else
  @endif 
  <button type="button" style="display:none" id="myCheck" name="op1"  data-toggle="modal" data-target="#myModal2"></button>
  <div class="modal fade" id="myModal2" role="dialog">

	<div class="modal-dialog">
		<form method="POST" action="{{route('concluir.comissao', $pedidos->id)}}"> 
			{{ csrf_field() }} 	
			<!-- Modal content-->  
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h2 class="modal-title">Detalhes do Pagamento</h2>
			</div>
			<div class="modal-body">			
			
			 <label style="font-size: 12px;">Observações                      
                <input type="text" name="obs_comissao" required placeholder="Obs:" ></label>
                <input type="hidden" name="pedido_id" value="{{ $pedidos->id }}">
				<input type="hidden" name="vendedor_id"  value="{{$pedidos->Vendedor->id}}">
				
			</div>
			 <div class="modal-footer">
   	<button type="button" class="btn btn-default" data-dismiss="modal" style="margin-top: 11px;">Voltar</button>            
   	<button type="submit"  class="btn waves-effect waves-light  blue darken-2"><span class="glyphicon glyphicon-floppy-disk"></span><b> Salvar</b></button>
   </div>
</form>
</div>
</div>
</div>
</div>
</div>

@endsection