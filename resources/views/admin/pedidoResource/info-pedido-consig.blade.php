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
						<h2>Detalhes do Pedido</h2> 
						  
					<div class="divider" style="margin-bottom: 2px; margin-top: -8px;" >
						
					</div>
					</div>
					</div>	

					<div class="row" style="height: 80px; width: 1170px; position: fixed; z-index: 1001; top: 100px; ">
						<div class="col-md-12">         

					<!--<ol class="breadcrumb" style="margin-bottom: 5px;">
						<li><a href="{{route('pedido.compras')}}" id="btn" style="text-decoration: none"><b>Pedidos</b></a></li>
						<li><a href="{{route('index')}}" id="btn" style="text-decoration: none"><b>Novo Pedido</b></a></li>
						<li class="active">Detalhes</li>
					</ol>-->


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

					<div class="card-panel" style="height: 190px; padding: 15px 10px;">    
						            
							<div class="row" style="margin-bottom: -10px;">
								<form method="POST" action="{{ route('carrinho.adicionarEdit', $pedidos->id) }}">
									{{ csrf_field() }} 
									 


				
				<div class="col-md-6">
            <div class="input-field"  style="margin-top:-8px;">
  <section>      
                    <label style="font-size: 12px;">Vendedor</label>
                    <select id="vendedor" name="vendedor" class="form-control" title="{{$pedidos->Vendedor->name}} &hybull; @if (isset($pedidos->Vendedor->cnpj))cnpj:{{$pedidos->Vendedor->cnpj}}@else cpf:{{$pedidos->Vendedor->cpf}} @endif">       
                      <option></option>
                      <option value="{{$pedidos->Vendedor->id}}" title="{{$pedidos->Vendedor->name}} &hybull; @if (isset($pedidos->Vendedor->cnpj))cnpj:{{$pedidos->Vendedor->cnpj}}@else cpf:{{$pedidos->Vendedor->cpf}} @endif">{{$pedidos->Vendedor->id}} &hybull; {{$pedidos->Vendedor->name}} &hybull; {{$pedidos->Vendedor->cnpj}} {{$pedidos->Vendedor->cpf}} &hybull; {{$pedidos->Vendedor->cel}}</option>        
                    </select>


                    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
                    <script type="text/javascript">
                      $("#vendedor").select2({
                        placeholder:' {{$pedidos->Vendedor->id}} - {{$pedidos->Vendedor->name}} - {{$pedidos->Vendedor->cpf}} {{$pedidos->Vendedor->cnpj}} - {{$pedidos->Vendedor->cel}}'
                      });
                    </script> 
                    <input type="hidden" name="vendedor_id" value="{{$pedidos->vendedor_id}}">
  </div>

          
         
            <div class="input-field" style="margin-top: 30px;">          
              <select id="produtos" name="id"> <!--onchange="location = this.value;"-->
                @foreach($registros as $registro)
                <option></option>
                <option value="{{ $registro->id }}" title="Pre??o R$ {{number_format($registro->prod_preco_padrao, 2,',','.')}}">{{$registro->prod_cod}} &hybull; {{$registro->prod_desc}} &hybull; R$ {{number_format($registro->prod_preco_padrao, 2,',','.')}} 
                </option>
                @endforeach     
              </select>
              <label style="font-size: 12px; margin-top: -30px;">PRODUTOS</label>
            </div>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>      
            <script type="text/javascript">
              $("#produtos").select2({
                placeholder:'---Selecione o Produto---'
              });
            </script>  
        </div>
          <div class="col-md-6">
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

                        $('.phone').mask(maskBehavior, options);
                        $('.money').mask('000.000.000.000.000,00', {reverse: true}).attr('maxlength','6'); 
                        $('.cep').mask('00000-000');
                        $('.percent').mask('00%', {reverse: true}).attr('maxlength','3');
                        
                      });
</script>
<div class="col">
	 
     	<div class="col-md-3">          
           <label style="font-size: 12px;">Pedido N??mero:<input type="text" readonly value="{{ $pedidos->id }}"> 
           </div></label>
         <div class="col-md-3">  	 
           	 <label style="font-size: 12px;">Data do pedido<input type="text" readonly value="{{ $pedidos->created_at->format('d/m/Y') }}"  title="{{ $pedidos->created_at->format('d/m/Y H:i') }}"> </label>      
          
              </div>
              <!--<div class="col-md-5"> 

              

        </div>-->
           </div>
           <!--<div class="col">
             <div class="col-md-3">
             	 <label style="font-size: 12px;">Desconto
 			<input type="text" name="desconto_request" class="percent" title="Desconto de 1% ?? 50%" placeholder="0%"> </label> 
			</div>
            
             <div class="col-md-3">
                <label style="font-size: 12px;">Qtd
                <input type="number" placeholder="1" min="1" max="50" name="quantidade_request"></label>
          
            </div>

            <button type="submit" class="btn btn btn-primary float-right" style="margin-top: 15px;" data-position="top" data-delay="50" data-toggle="tooltip" disabled data-placement="bottom" title="Adicionar Requisi????o"><i class="fa fa-plus"></i>
             Adicionar
           </button>  

         </div>-->
                  	<div class="col">
     
          <div class="col-md-3">           
             <label style="font-size: 12px;">Desconto
 			<input type="text" name="desconto_produto" class="percent" title="Desconto de 1% ?? 50%" placeholder="0%"> </label> 
			</div>
            <div class="col-md-3">
                <label style="font-size: 12px;">Qtd
                <input type="number" placeholder="1" max="50" min="1" name="quantidade_produto"></label>
          
            </div>
        
            <button type="submit" class="btn btn btn-primary float-right" style="margin-top: 15px;" data-position="top" data-delay="50" data-toggle="tooltip" disabled data-placement="bottom" title="Adicionar Produto"><i class="fa fa-plus"></i>
             Adicionar
             </button>
     

      </div>
  </div>

</form>


	<form method="POST" action="{{ route('alter.statusEditConsig', $pedidos->id) }}">
		{{ csrf_field() }} 
			<div class="col-md-2">
              <div class="input-field"> 
              <label for="status" style="font-size: 15px; margin-top: -35px;">Alterar Status</label>  
                <select onchange="submit()" name="status" class="form-control" style="height: 29px;" title="@if ($pedidos->status == 'AP') Aguardando Pagamento @elseif ($pedidos->status == 'EL') Encaminhado ao Lab. @elseif ($pedidos->status == 'CA') Cancelado @elseif ($pedidos->status == 'EC') Enviado @else ($pedidos->status == 'FI') Finalizado @endif">                                 
                
               
                 <option title="@if ($pedidos->status == 'AP') Aguardando Pagamento @elseif ($pedidos->status == 'EL') Encaminhado ao Lab. @elseif ($pedidos->status == 'CA') Cancelado @elseif ($pedidos->status == 'EC') Enviado @else ($pedidos->status == 'FI') Finalizado @endif" style="background-color: #e0e2eb;" value="">@if ($pedidos->status == 'AP') Aguardando Pagamento @elseif ($pedidos->status == 'EL') Encaminhado ao Lab. @elseif ($pedidos->status == 'CA') Cancelado @elseif ($pedidos->status == 'EC') Enviado @else ($pedidos->status == 'FI') Finalizado @endif</option> 

                 @if ($pedidos->status == 'FI')

                 @else
                 <option value="FI" title="Finalizar o Pedido">Finalizar</option>
                 @endif

                 @if ($pedidos->status == 'CA')

                 @else
                 <option value="CA" title="Cancelar o Pedido">Cancelar</option> 
                 @endif

                 @if ($pedidos->status == 'RE')

                 @else
                 <option value="RE" title="Alterar o Pedido">Reservar (Alterar)</option>
                 @endif
                 <!--<option value="GE" title="Pedidos Em Aberto">Em Aberto </option>-->
          
                 </select>
             </div>
         </div>
     </form>



									<p>Clique para obter outras informa????es do Pedido<i class="material-icons">redo</i>	<button  onclick="myFunction();" title="Outras informa????es do Pedido" class="btn-floating btn-medium waves-effect waves-light  amber">
			<i class="material-icons" style="font-size: 30px;">info_outline</i>   
			<script type="text/javascript">
				function myFunction() {
					document.getElementById("myCheck").click();
				}

			</script>
		</button> </p>

							</div>

						</div>

					</div>
				</div>

				<div class="row" style="margin-top: 250px;">
   					<div class="col-md-12"> 	

				<div class="table-responsive">
					<table class="table table-striped table-bordered table-condensed table-hover" >
						<thead>
							<tr style="color: #4db6ac;  background-color: #fcf8e3;"><th id="center" colspan="7"> PRODUTOS</th></tr>
							<tr class="warning">                    
								<th id="center">C??digo</th>
								<th id="center">Produto</th>
								<th id="center">Qtd</th>              
								<th id="center" title="Pre??o unit??rio">Pre??o Unit??rio</th>
								<th id="center" title="Desconto unit??rio">Desconto</th>
								<th id="center">Total</th>
								<th id="center">A????es</th>
							</tr>
						</thead> 

						<tbody> 
							@php
							$total_pedido =0;
							@endphp

							@foreach ($pedidos->itens_pedido as $item_pedido)
							<tr> 

								<td id="center">
									<span class="chip"> {{ $item_pedido->product->prod_cod }}</span>
								</td> 
								<td  id="center">
									{{ $item_pedido->product->prod_desc}}
								</td>
								<td  id="center">
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


								<td title="R$ {{ number_format($preco_com_desconto, 2, ',', '.')}} por unidade com desconto" >
									<b style="text-decoration: line-through;">
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

              <td style="width:120px;">  
    @if($desconto > 0)       
      <input type="text" name="desconto_produto" class="percent" title="{{ round($percentual_desconto)}}% de desconto por Unidade | Altere o desconto de 1 ?? 50%" value="" placeholder="R$ {{ number_format($total_prod_desconto, 2, ',', '.')}}">
      <input type="hidden" name="idproduto" value="{{$item_pedido->product->id}}">
      <input type="hidden" name="idpedido" value="{{$pedidos->id}}">

      @else
       
      <input type="text" name="desconto_produto" class="percent" title="Altere o desconto de 1 ?? 50%" value="" placeholder="R$ 0,00">
      <input type="hidden" name="idproduto" value="{{$item_pedido->product->id}}">
      <input type="hidden" name="idpedido" value="{{$pedidos->id}}">
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
								<td  id="center">
									@if ($desconto > 0)

      R$ {{ number_format($total_produto_com_desconto, 2, ',', '.')}}

     
   @else
     R$ {{ number_format($total_produto, 2, ',', '.') }}
     @endif

								</td>
								  <td  id="center" style="width:100px;" > 

  

       <button onclick="alert('N??o ?? poss??vel aplicar desconto ao produto. Verifique o status do pedido.')"  title="Aplicar desconto" class="btn btn-small waves-effect btn-default" style="padding: 0px 0px;"><i class="material-icons" style="font-size: 20px;">
money_off
</i></button>

       <a href="#" onclick="alert('N??o ?? poss??vel remover o produto. Verifique o status do pedido.')" title="Remover Produto" class="btn btn-small waves-effect btn-Tiny" style="padding: 0px 0px;"><i class="material-icons" style="font-size: 20px; padding: 0px 0px;">
remove_shopping_cart
</i></a>

  </td>
  <!-- <td title="A????es" id="center" > 


    <a href="#" onclick="carrinhoRemoverProduto({{ $pedidos->id}}, {{ $item_pedido->produto_id }}, 1)"
      data-toggle="tooltip" 
      data-placement="top"
      title="Remover" class="btn-floating btn-small waves-effect waves-light red"><i class="material-icons">remove_circle_outline</i>
    </a> &nbsp; 



    <a href="#" onclick="carrinhoAdicionarProduto({{ $item_pedido->produto_id }})" 
      data-toggle="tooltip" 
      data-placement="top"
      title="Adicionar" class="btn-floating btn-small waves-effect waves-light btn-primary"><i class="material-icons">add_circle_outline</i></a>



      <a href="#" onclick="carrinhoRemoverProduto({{ $pedidos->id}}, {{ $item_pedido->produto_id }}, 0)" class="waves-effect btn-Tiny secondary-content">Remover Item</a>

  </td>-->
</tr>


@endforeach

  <tr><td colspan="2" style="text-align: center;"><b>Totais Consolidados:</b></td>
    @php
    $total_desconto_pedido = 0;
    $total_produtos = 0;
    $total_quantidade = 0;
    @endphp

    @foreach ($pedidos->itens_pedido as $item_pedido)
    @php
    $total_quantidade += $item_pedido->quantidade;  
    $total_produtos += $item_pedido->total;  
    $total_desc_geral = $item_pedido->totalDesconto;
    $total_desconto_pedido -= $total_desc_geral;

    @endphp
    @endforeach

    <td id="center">{{($total_quantidade) }}</td>
      <td> R$ {{ number_format($total_produtos, 2, ',', '.') }}</td>
    <td><b style="color: red;">R$ {{ number_format($total_desconto_pedido, 2, ',', '.') }}</td>
      <td id="center"><b>R$ {{ number_format($total_pedido, 2, ',', '.') }}</b></td>
      <td></td>
    </tr>

</tbody>    
</table>
</div>




<div class="table-responsive">
	<table class="table table-striped table-bordered table-condensed table-hover" >

		<thead>
			<tr><th id="center" colspan="7" style="color: #4db6ac;  background-color: #fcf8e3;"> REQUISI????ES</th></tr>
			<tr class="warning">                    
				<th id="center">C??digo</th>
				<th id="center">Requisi????o</th>
				<th id="center">Qtd</th>              
				<th id="center" title="Pre??o unit??rio">Pre??o Unit??rio</th>
				 <th id="center" title="Desconto unit??rio">Desconto</th>
				<th id="center">Total</th>
				<th id="center">A????es</th>

			</tr>
		</thead> 

		<tbody> 


			@php
			$total_pedido =0;
			@endphp

			@foreach ($pedidos->itens_pedido_request as $item_pedido)
			<tr> 

				<td id="center">
					<span class="chip"> {{ $item_pedido->request->request_cod}}</span>
				</td> 
				<td  id="center">
					{{ $item_pedido->request->request_desc}}
				</td>
				<td  id="center">
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

    <td style="width:120px;">  
       
 @if($desconto > 0)       
      <input type="text" name="desconto_request" class="percent" title="{{ round($percentual_desconto)}}% de desconto por Unidade | Altere o desconto de 1 ?? 50%" value="" placeholder="R$ {{ number_format($total_request_desconto, 2, ',', '.')}}">
      <input type="hidden" name="idrequest" value="{{$item_pedido->request->id}}">
      <input type="hidden" name="idpedido" value="{{$pedidos->id}}">

      @else
       
      <input type="text" name="desconto_request" class="percent" title="Altere o desconto de 1 ?? 50%" value="" placeholder="R$ 0,00">
      <input type="hidden" name="idrequest" value="{{$item_pedido->request->id}}">
      <input type="hidden" name="idpedido" value="{{$pedidos->id}}">
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

				<td  id="center">
			 @if ($desconto > 0)

      R$ {{ number_format($total_produto_com_desconto, 2, ',', '.')}}

     
   @else
     R$ {{ number_format($total_produto, 2, ',', '.') }}
     @endif
				</td>

   <!--<td title="A????es" id="center"> 
    <a href="#" onclick="carrinhoRemoverRequest({{ $pedidos->id}}, {{ $item_pedido->request_id }}, 1)"
      data-toggle="tooltip" 
      data-placement="top"
      title="Remover" class="btn-floating btn-small waves-effect waves-light red"><i class="material-icons">remove_circle_outline</i>
    </a> &nbsp; 



    <a href="#" onclick="carrinhoAdicionarRequest({{ $item_pedido->request_id }})" 
      data-toggle="tooltip" 
      data-placement="top"
      title="Adicionar" class="btn-floating btn-small waves-effect waves-light btn-primary"><i class="material-icons">add_circle_outline</i></a>

      <a href="#" onclick="carrinhoRemoverRequest({{ $pedidos->id}}, {{ $item_pedido->request_id }}, 0)" class="waves-effect btn-Tiny secondary-content">Remover Item</a>

        </td>-->
 <td style="width:100px;" id="center"> 
   
   <button onclick="alert('N??o ?? poss??vel aplicar desconto a requisi????o. Verifique o status do pedido.')"  title="Aplicar desconto" class="btn btn-small waves-effect btn-default" style="padding: 0px 0px;"><i class="material-icons" style="font-size: 20px;">
money_off
</i></button>

       <a href="#" onclick="alert('N??o ?? poss??vel remover a requisi????o. Verifique o status do pedido.')" title="Remover Produto" class="btn btn-small waves-effect btn-Tiny" style="padding: 0px 0px;"><i class="material-icons" style="font-size: 20px; padding: 0px 0px;">
remove_shopping_cart
</i></a>
</td>

</tr>



@endforeach

  <tr><td colspan="2" style="text-align: center;"><b>Totais Consolidados:</b></td>
    @php
    $total_desconto_pedido = 0;
    $total_produtos = 0;
    $total_quantidade = 0;
    @endphp

    @foreach ($pedidos->itens_pedido_request as $item_pedido)
    @php
    $total_quantidade += $item_pedido->quantidade;  
    $total_produtos += $item_pedido->total;  
    $total_desc_geral = $item_pedido->totalDesconto;
    $total_desconto_pedido -= $total_desc_geral;

    @endphp
    @endforeach

    <td id="center">{{($total_quantidade) }}</td>
      <td> R$ {{ number_format($total_produtos, 2, ',', '.') }}</td>
    <td><b style="color: red;">R$ {{ number_format($total_desconto_pedido, 2, ',', '.') }}</td>
      <td id="center"><b>R$ {{ number_format($total_pedido, 2, ',', '.') }}</b></td>
      <td></td>
    </tr>




</tbody>    
</table>
</div>



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

<div class="divider" style="margin-bottom: 5px;"></div>

<p class="lead" id="black">Total Geral: R$ {{ number_format($total_geral, 2, ',', '.') }}</p>

<div class="divider" style="margin-bottom: 10px; margin-top: -15px;"></div>



		<a href="{{route('pedido.consignado')}}" class="btn btn-default"><b>VOLTAR</b></a>
 





				@if (Session::has('message'))



				<script type="text/javascript">
					$(function() {
						$('#myCheck').click();
					});
				</script>

				<button type="button" style="display:none" id="myCheck" name="op1"  data-toggle="modal" data-target="#myModal2"></button> 

				@endif

				<button type="button" style="display:none" id="myCheck" name="op1"  data-toggle="modal" data-target="#myModal2"></button>  

			</div>
		</div>
		<!-- Modal -->
		<div class="modal fade" id="myModal2" role="dialog">

			<div class="modal-dialog">

				<form method="POST" action="{{route('pedidos.detalhesEditConsig', $pedidos->id)}}"> 
					{{ csrf_field() }} 
					<!--<input type="hidden" name="dataAtual" value="@php echo date('d/m/y') @endphp">  
					<input type="hidden" name="dataRegistro" value="{{ $pedidos->created_at->format('d/m/Y') }}">-->

					<input type="hidden" name="id_cliente" value="{{$pedidos->id_cliente}}">
					<input type="hidden" name="user_id" value="{{$pedidos->user_id}}">
					<input type="hidden" name="vendedor_id" value="{{$pedidos->vendedor_id}}">
					<input type="hidden" name="obs_pedido"  id="obspedidoDetalhes" value="{{$pedidos->obs_pedido}}">
					<!-- Modal content-->   

					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2 class="modal-title">Outras informa????es do Pedido</h2>

					</div>

					<div class="modal-body">

						@if (Session::has('message'))
						<div class="alert alert-danger alert-dismissible">
							<strong>{{ Session::get('message') }}</strong>
							<a href="#" class="close" 
							data-dismiss="alert"
							aria-label="close">&times;</a>
						</div>
						@endif
						<script src="https://code.jquery.com/jquery-3.2.1.min.js"
						integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
						crossorigin="anonymous"></script>

						<script type="text/javascript">                 

							$(document).ready(function() {

								function limpa_formul??rio_cep() {
                            // Limpa valores do formul??rio de cep.

                            $("#endere??o").val("");
                            $("#bairro").val("");
                            $("#cidade").val("");
                            $("#estado").val(""); 
                            


                        }

                        //Quando o campo cep perde o foco.
                        $("#cep").blur(function() {

                            //Nova vari??vel "cep" somente com d??gitos.
                            var cep = $(this).val().replace(/\D/g, '');

                            //Verifica se campo cep possui valor informado.
                            if (cep != "") {

                                //Express??o regular para validar o CEP.
                                var validacep = /^[0-9]{8}$/;

                                //Valida o formato do CEP.
                                if(validacep.test(cep)) {

                                    //Preenche os campos com "..." enquanto consulta webservice.
                                    $("#endere??o").val("Pesquisando...");
                                    $("#bairro").val("Pesquisando...");
                                    $("#cidade").val("Pesquisando...");
                                    $("#estado").val("Pesquisando...");


                                    //Consulta o webservice viacep.com.br/
                                    $.getJSON("https://viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {

                                    	if (!("erro" in dados)) {
                                            //Atualiza os campos com os valores da consulta.
                                            $("#endere??o").val(dados.logradouro);
                                            $("#bairro").val(dados.bairro);
                                            $("#cidade").val(dados.localidade);
                                            $("#estado").val(dados.uf);                                
                                        } //end if.
                                        else {
                                            //CEP pesquisado n??o foi encontrado.
                                            limpa_formul??rio_cep();
                                            alert("CEP n??o encontrado.");
                                        }
                                    });
                                } //end if.
                                else {
                                    //cep ?? inv??lido.
                                    limpa_formul??rio_cep();
                                    alert("Formato de CEP inv??lido.");
                                }
                            } //end if.
                            else {
                                //cep sem valor, limpa formul??rio.
                                limpa_formul??rio_cep();
                            }
                        });
                    });

                </script>


               
                	<p style="color: #9e9e9e; font-size: 12px; margin-top: 5px;"><b>DETALHES DO FRETE</b></p> 

                	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script><!-- -->
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

                		$('.phone').mask(maskBehavior, options);
                		$('.money').mask('000.000.000.000.000,00', {reverse: true}).attr('maxlength','6'); 


                	});
                </script>

                <label for="balcao">
                	<input type="checkbox" name="balcao" id="balcao" value="Y" {{ $pedidos->Frete->balcao == 'Y' ? 'checked' : '' }} /><span style="font-size: 13px; margin-top: 2px;">Retirada Balc??o</span>
                </label>  


                <div name="op2" style="display:none">


                	<label for="valor" id="valor_frete_label"  style="font-size: 13px;" class="col-md-4" >Custo Frete
                		<input type='text' name="valor" class="money" style="display:none;" value="{{ number_format($pedidos->Frete->valor, 2, ',', '.') }}"  id="valor_frete" maxlength="6" pattern='([0-9]{1,3}\.)?[0-9]{1,3},[0-9]{2}$' title="Informe o custo do frete R$ 0,00" placeholder="{{ number_format($pedidos->Frete->valor, 2, ',', '.')}}"></label>

                		    <label for="prazo_entrega" id="prazo_entrega_label" style="font-size: 13px;" class="col-md-4" >Prazo de entrega
                        <input type='text' name="prazo_entrega" style="display:none;"  value="@if($pedidos->Frete->prazo_entrega != ''){{$pedidos->Frete->prazo_entrega}} Dias @else  @endif" id="prazo_entrega"  title="Prazo de entrega" placeholder="n?? Dias">
                      </label>


                	<label>
                		<input type="radio" name="entrega" class="with-gap" id="disable" value="B" {{ $pedidos->Frete->entrega == 'B' ? 'checked' : '' }} />
                		<span style="font-size: 13px;" >Entrega: MOTO BOY</span><!--color: #4dd0e1;  -->
                	</label>   
                	<label>
                		<input type="radio" name="entrega" class="with-gap"  id="enable" value="C" {{ $pedidos->Frete->entrega == 'C' ? 'checked' : '' }} />
                		<span style="font-size: 13px;">Entrega: CORREIOS</span><!--color: #ffd54f;  -->
                	</label> 

                	 <button type="button" onclick="LoadFrete();" id="textbox" class="btn btn-small waves-effect amber pull-right" style="margin-top: 13px;"{{ $pedidos->Frete->entrega == 'C' ? 'enabled' : '' }} {{ $pedidos->Frete->entrega == 'B' ? 'disabled' : '' }}>Calcular frete</button>
					
					<div class="col-md-2 pull-right">
                 <div class="input-field">   
                     <select name="cdservico" class="form-control" id="textbox1"title="Escolha o Servi??o" style=" width: 170px;  float: right;  height: 32px;" {{ $pedidos->Frete->entrega == 'C' ? 'enabled' : '' }} {{ $pedidos->Frete->entrega == 'B' ? 'disabled' : '' }}>

                     @if ($pedidos->Frete->servi??o_correio != NULL)
                     <option value="{{$pedidos->Frete->servi??o_correio == '04014' ? '04014' : ''}}{{$pedidos->Frete->servi??o_correio == '04510' ? '04510' : ''}}{{$pedidos->Frete->servi??o_correio == '04782' ? '04782' : ''}}{{$pedidos->Frete->servi??o_correio == '04790' ? '04790' : ''}}{{$pedidos->Frete->servi??o_correio == '04804' ? '04804' : ''}}
                  	" style="background: #d7d7db;">
                     
                     	{{$pedidos->Frete->servi??o_correio == '04014' ? 'SEDEX ?? vista' : ''}} 
                     	{{$pedidos->Frete->servi??o_correio == '04510' ? 'PAC ?? vista' : ''}} 
                     	{{$pedidos->Frete->servi??o_correio == '04782' ? 'SEDEX 12 ( ?? vista)' : ''}}
                    	{{$pedidos->Frete->servi??o_correio == '04790' ? 'SEDEX 10 (?? vista)' : ''}}
                  		{{$pedidos->Frete->servi??o_correio == '04804' ? 'SEDEX Hoje ?? vista' : ''}}
                  	
                  		</option>
                  	 <option value="04014">SEDEX ?? vista</option>
                     <option  value="04510">PAC ?? vista</option>
                     <option  value="04782">SEDEX 12 ( ?? vista)</option>
                     <option  value="04790">SEDEX 10 (?? vista)</option>
                     <option  value="04804">SEDEX Hoje ?? vista</option>
                     @else
                  	 <option value="" >Servi??os Correios</option>
                  	 <option value="04014">SEDEX ?? vista</option>
                     <option  value="04510">PAC ?? vista</option>
                     <option  value="04782">SEDEX 12 ( ?? vista)</option>
                     <option  value="04790">SEDEX 10 (?? vista)</option>
                     <option  value="04804">SEDEX Hoje ?? vista</option>
                     @endif
                    
                     
                   
                    
                     </select>

                

                               
                    
           
                           
                    </div>

                      <input type="hidden" name="pedido_id_load" id="pedido_id_load" value="{{$pedidos->id}}">


   </div>

     





                		<script type="text/javascript">

$('#balcao').click(function() {
   $('input[name=entrega]').attr('checked', false);
 //  document.getElementById('textbox')
 //       .setAttribute('disabled', 'disabled');  
  //      document.getElementById('textbox1')
  //      .setAttribute('disabled', 'disabled');
   //     document.getElementById('prazo_entrega')
    //    .setAttribute('disabled', 'disabled');
    // document.getElementById('local')
    //    .setAttribute('disabled', 'disabled');
      $('input[name=prazo_entrega]').css('display', 'none');
      $('label[id=prazo_entrega_label]').css('display', 'none');
      $('[id=valor_frete]').css('display', 'none');
      $('[id=valor_frete]').val("");

      $('label[id=valor_frete_label]').css('display', 'none');
      $('[id=textbox1]').css('display', 'none');
      $('[id=textbox]').css('display', 'none');
      $('[id=local]').css('display', 'none');
      $('label[id=local_label]').css('display', 'none');
      if($('input[name="balcao"]').prop('checked')){
					$("#balcao").val("Y");
					//console.log();
					 //var balc = $('#balcao').val();
					// console.log(balc);
					//$('[name="localisset"]').show();

				} else 

				{
					$("#balcao").val("");
				} 

         
});

$('#enable').click(function() {
   $('input[name=balcao]').attr('checked', false);
    $('input[name=prazo_entrega]').css('display', 'block');
      $('label[id=prazo_entrega_label]').css('display', 'block');
      $('[id=valor_frete]').css('display', 'block');
      $('label[id=valor_frete_label]').css('display', 'block');
      $('[id=local]').css('display', 'block');
      $('label[id=local_label]').css('display', 'block');
      $('[name="alterlocal"]').css('display', 'block');
       $('[id=textbox1]').css('display', 'block');
      $('[id=textbox]').css('display', 'block');
      $('[name="balcaoempty"]').css('display', 'block');
         document.getElementById('textbox')
        .removeAttribute('disabled');  
        document.getElementById('textbox1')
        .removeAttribute('disabled');
});

$('#disable').click(function() {
   $('input[name=balcao]').attr('checked', false);
     $('[id=valor_frete]').css('display', 'block');
      $('label[id=valor_frete_label]').css('display', 'block');
         $('input[name=valor]').val("");
                     $('input[name=prazo_entrega]').val("").attr('disabled', true);
                     $('[name="balcaoempty"]').css('display', 'block');
                     $('[name="alterlocal"]').css('display', 'block');
 		$('[id=local]').css('display', 'block');
 		$('label[id=local_label]').css('display', 'block');
    $('[id=textbox1]').css('display', 'block').attr('disabled', true);
     $('[id=textbox]').css('display', 'block').attr('disabled', true);
     
      if($('[id="disable"]').prop('checked')){
					$("#disable").val("B");
					//console.log();
					 //var balc = $('#balcao').val();
					// console.log(balc);
					//$('[name="localisset"]').show();

				} else 

				{
					$("#disable").val("");
				} 

});

/*
    $('#enable').click(function() {
   document.getElementById('textbox')
        .removeAttribute('disabled');  
        document.getElementById('textbox1')
        .removeAttribute('disabled');
        document.getElementById('prazo_entrega')
        .removeAttribute('disabled');
        document.getElementById('local')
        .removeAttribute('disabled');
   //return false;  
});

$('#disable').click(function() {
   document.getElementById('textbox')
        .setAttribute('disabled', 'disabled');  
        document.getElementById('textbox1')
        .setAttribute('disabled', 'disabled');
        document.getElementById('prazo_entrega')
        .setAttribute('disabled', 'disabled');
        document.getElementById('local')
        .removeAttribute('disabled');
  // return false; 
});*/



                /*			function myFunction() {
                				document.getElementById("myCheck").click();
                			}*/


                   // var a = document.getElementById('obspedido');

                   // var b = document.getElementById('obspedidoDetalhes');
                   // b.value = a.value;

                   

               </script> 

                             <script type="text/javascript">



                  function LoadFrete(){
                  	 $("#valor_frete").val("Pesquisando...");
                    
                   //console.log("humm its change");               
                  var pedido_id_load = $('#pedido_id_load').val();
                  var cep_alter = $('.cep').val();
                  var cdservico_alt = $('#textbox1').val();
                   //var status = 'FI';
                   $.ajax({
                    type:'get',
                    url:'{!!URL::to('infoFrete') !!}',
                    datatype:'html',
                    cache: false,
                    data:{pedido_id_load:pedido_id_load, cep_alter:cep_alter, cdservico_alt:cdservico_alt},                                     
                    success:function(data){
                                      //console.log('success');
                                      console.log(pedido_id_load);
                                      console.log(cep_alter);
                                      console.log(cdservico_alt);
                                       $('#valor_frete').val(data);
                                       
                                        //console.log(data.length);                                   
                                      }, beforeSend: function(){


                                      },
                                      erro:function(){
                                        console.log('Erro!');
                                      }

                                    }); 
                 };


                  $(document).on('click','#textbox',function(){


                     $("#prazo_entrega").val("Pesquisando...");

                  var pedido_id_load = $('#pedido_id_load').val();
                  var cep_alter = $('.cep').val();
                  var cdservico_alt = $('#textbox1').val();

           $.ajax({
      type:'get',
      url:'{!!URL::to('infoFretePrazoEntrega') !!}',
      data:{pedido_id_load:pedido_id_load, cep_alter:cep_alter, cdservico_alt:cdservico_alt},
      dataType:'html',
      cache: false,
      success:function(data){
       
        $('#prazo_entrega').val(data);

      }, beforeSend: function(){

		//$('#textbox').attr('checked', true);

      }, erro:function(){
        console.log('Erro!');
      }

     });

    });
               </script>

                 <script type="text/javascript">
       
               		// var a = document.getElementById('obspedido');
                   // var b = document.getElementById('obspedidoDetalhes');
                   // b.value = a.value;    
                   //$('input[name=entrega]').hide(); 
                    //$('input[name=entrega]').hide(); 
                			

                	if($('input[name="balcao"]').prop('checked')){

                     $(function() 
                 {
                      $('[name="alterlocal"]').css('display', 'none');
                    });

             /*   		$(function() 
                			{

                	$('[name="op2"]').hide(200);
                    $('[name="valor"]').hide(200);
                    $('[name="prazo_entrega"]').hide(200);                    
                    $('input[name=entrega]').attr('checked', false);
                    $('input[name=local]').attr('checked', false);
                    $('[name="local"]').hide(200);
                    $('[name=localisset]').hide();

                });*/

                	$('[name="balcao"]').change(function() 
                			{
                    //$('[name="op1"]').toggle(200);
                    $('[name="op2"]').toggle(200);
                    $('[name="valor"]').toggle(200);
                    $('[name="prazo_entrega"]').toggle(200);       
                    $('[name="alterlocal"]').toggle(200);             
                    //$('input[name=entrega]').attr('checked', false);
                   // $('input[name=local]').attr('checked', false);
                    $('[name="local"]').hide(200);
                    $('[name=localisset]').hide();
                   // $('[name="balcaoempty"]').toggle(200);

                    

                   
                });


                	
                		
                		}
                		else 
                		{
                			$(function() 
                			{

                	$('[name="op2"]').show(200);
                    $('[name="valor"]').show(200);
       				 if($('input[name="entrega"]').val() == 'B'){
                    	$('[id="prazo_entrega_label"]').hide();
                    	 $('[name="prazo_entrega"]').hide();
                       $('input[name=prazo_entrega]').attr('disabled', true);


                    } else ($('input[name="entrega"]').val() == 'C')
                    {
                    	$('[id="prazo_entrega_label"]').toggle(200);
                    	 $('[name="prazo_entrega"]').toggle(200);
                       $('input[name=prazo_entrega]').attr('disabled', false);
                    }

                    $('[name="local"]').show(200);
                    $('[name=localisset]').hide();
                });

                            $('[name="balcao"]').change(function() 
                      {
                  $('[name="alterlocal"]').css('display', 'none');
                   // $('[name="balcaoempty"]').toggle(200);

                    

                   
                 });

                		
              			      
              			     

              		/*$('[name="balcao"]').change(function() 
                			{
                    //$('[name="op1"]').toggle(200);
                    $('[name="op2"]').hide(200);
                    $('[name="valor"]').hide(200);
                    $('[name="prazo_entrega"]').hide(200);                    
                    $('input[name=entrega]').attr('checked', false);
                    $('input[name=local]').attr('checked', false);
                    $('[name="local"]').hide(200);
                    $('[name=localisset]').hide();
                   // $('[name="balcaoempty"]').toggle(200);

                    

                   
                });*/



              			         //$('[name="op1"]').toggle(200);
              			   //$('[name=localisset]').toggle(200);   
              		  //$('input[name=entrega]').attr('checked', false);
              		 //$('input[name=local]').attr('checked', false);              	         			     
                 	//$('[name="balcaoempty"]').toggle(200);	
                  	//$('input[name=entrega]').hide(); 
         			    
                		

                	


                		}

                    $('[name="cdservico"]').change(function() 
            {

              $('[id=textbox]').click();                      

            });  
                   

               </script> 

               <script type="text/javascript">
               	$(document).ready(function(){
               		$(".obspedido").on("input", function(){
               			var textoDigitado = $(this).val();
               			var inputCusto = $(this).attr("obspedidoDetalhes");
               			$("#"+ inputCusto).val(textoDigitado);
               		});
               	});
               </script>


           </div><br>
      



      <div name="balcaoempty">                              
       	<label for="local" name="local" id="local_label" style="display:none">
       		<input type="checkbox" name="local" onclick="cepIsset();"  id="local"  value="Y" {{ $pedidos->Frete->local == 'Y' ? 'checked' : '' }}   /><span style="font-size: 13px;" title="Caso o endere??o de cadastro seja diferente do endere??o de entrega">Alterar Local da Entrega</span>
       	</label>
      </div>

       	<script type="text/javascript">
       		/*function myFunctionCorreios() {
					document.getElementById("textbox").click();
					document.getElementById("textbox1").click();
					//document.getElementById("local").click();
					//document.getElementById("cep").val("");
				}*/

			/* function resetPrazo(){	
				if($('input[name="balcao"]').prop('checked'))
			 	$("#balcao").val("Y");
			 };*/

         if($('input[name="local"]').prop('checked')){
          $(function() 
                 {
                      $('[name="alterlocal"]').css('display', 'none');
                      $('[name="localisset"]').show();
                    });
        };

      function cepIsset(){
        if($('input[name="local"]').prop('checked')){
         $("#local").val("Y");
         $('[name="localisset"]').show();
         $('[name="alterlocal"]').hide();

       } else 

       {
         $('[name="localisset"]').hide();
         $("#local").val("");
         $('[name="alterlocal"]').show();
       } 




       $("#cep").val("");
      


       /*$("#cep").blur(function() {
        document.getElementById("textbox").click();
        document.getElementById("textbox1").click();

      });*/
     };

     function calcularFrete() {
       document.getElementById("textbox").click();

     }

				      


       	</script>

       <div name="localisset" style="display:none">

       	<div class="input-field">
       		<input type="text" class="cep" pattern="[0-9]{5}-[0-9]{3}" id="cep" value="" title="Informe o CEP. Consulta autom??tica Ex:00000-000" maxlength="9" name="cep" placeholder="{{$pedidos->Frete->cep}}"/>
       		<label for="cep" style="font-size: 15px;">Cep</label>
       	</div>
       	<div class="input-field">

       		<input type="text" id="endere??o" title="Informe o Endere??o" maxlength="191" placeholder="Forne??a o endere??o" name="endere??o"  value="{{$pedidos->Frete->endere??o}}"/>
       		<label for="endere??o" style="font-size: 15px;">Endere??o</label>
       	</div>
       	<div class="input-field">
       		<input type="text" title="Informe o N??mero" maxlength="8" name="numero" value="{{$pedidos->Frete->numero}}" placeholder="Forne??a o n??mero" />
       		<label for="numero" style="font-size: 15px;">N??mero</label>
       	</div>
       	<div class="input-field">
       		<input type="text"  id="bairro" title="Informe o Bairro" placeholder="Forne??a o Bairro" maxlength="191" name="bairro" value="{{$pedidos->Frete->bairro}}" />
       		<label for="bairro" style="font-size: 15px;">Bairro</label>
       	</div>
       	<div class="input-field">
       		<input type="text" title="Informe o Complemento" placeholder="Forne??a o Complemento" maxlength="191" name="complemento"  value="{{$pedidos->Frete->complemento}}" />
       		<label for="complemento" style="font-size: 15px;">Complemento</label>
       	</div>

       	<div class="input-field">
       		<input type="text" id="cidade" title="Informe a Cidade" maxlength="191" name="cidade"  placeholder="Forne??a o nome da Cidade"  value="{{$pedidos->Frete->cidade}}" />
       		<label for="cidade" style="font-size: 15px;">Cidade</label> 
       	</div>    


       	<select name="estado" class="form-control" id="estado" title="Informe o Estado" style=" width: 200px;  float: left;" disabled="disabled">
       		<option value="{{$pedidos->Frete->estado}}">{{$pedidos->Frete->estado}}</option>
       		<option value="AC">AC</option>
       		<option value="AL">AL</option>
       		<option value="AP">AP</option>
       		<option value="AM">AM</option>
       		<option value="BA">BA</option>
       		<option value="CE">CE</option>
       		<option value="DF">DF</option>
       		<option value="ES">ES</option>
       		<option value="GO">GO</option>
       		<option value="MA">MA</option>
       		<option value="MT">MT</option>
       		<option value="MS">MS</option>
       		<option value="MG">MG</option>
       		<option value="PA">PA</option>
       		<option value="PB">PB</option>
       		<option value="PR">PR</option>
       		<option value="PE">PE</option>
       		<option value="PI">PI</option>
       		<option value="RJ">RJ</option>
       		<option value="RN">RN</option>
       		<option value="RS">RS</option>
       		<option value="RO">RO</option>
       		<option value="RR">RR</option>
       		<option value="SC">SC</option>
       		<option value="SP">SP</option>
       		<option value="SE">SE</option>
       		<option value="TO">TO</option>
       	</select><br><br><br>

       </div>
            <div name="alterlocal" style="text-align: left; margin-bottom: 8px;">
        <label style="font-size: 13px;">Local de Entrega:</label>
       </div>

    <div class="divider"></div>
              <br>
       
       	<p style="color: #9e9e9e; font-size: 12px; margin-left: 4px; margin-top: 5px;"><b>FORMAS DE PAGAMENTO</b></p>
       <label>
   <input type="radio" name="pagamento" value="CC" {{ $pedidos->pagamento == 'CC' ? 'checked' : '' }}>
   <span style="font-size: 12px;" >Cart??o de Cr??dito</span>
 </label>
 <label>
   <input type="radio" name="pagamento" value="CD" {{ $pedidos->pagamento == 'CD' ? 'checked' : '' }}>
   <span style="font-size: 12px;" >Cart??o de D??bito</span>
 </label><br>
  <label>
   <input type='radio' name='pagamento' value="D" {{ $pedidos->pagamento == 'D' ? 'checked' : '' }}>
   <span style="font-size: 12px;">Dinheiro</span> <!--Dinheiro-->
 </label>
 <label>
   <input type="radio" name="pagamento" value="BB" {{ $pedidos->pagamento == 'BB' ? 'checked' : '' }}>
   <span style="font-size: 12px;" >Boleto Banc??rio</span>                         
 </label>
 <label>
   <input type="radio" name="pagamento" value="DB" {{ $pedidos->pagamento == 'DB' ? 'checked' : '' }}>
   <span style="font-size: 12px;" >Dep??sito Banc??rio</span>                         
 </label>
       	<div class="divider"></div>
  <br>
 <p style="color: #9e9e9e; font-size: 12px; margin-left: 4px; margin-top: 5px; margin-bottom: 2px;"><b>OBSERVA????ES</b></p>         
           <input type="text" name="obs_pedido" style="margin-left: 4px;" class="obspedido" title="Informe as observa????es gerais do pedido" value="{{$pedidos->obs_pedido}}" placeholder="Observa????es gerais do pedido."  />

      
   </div>


       <div class="modal-footer">
       	
            <button type="button" class="btn btn-default" data-dismiss="modal">Voltar</button> 
             <button type="submit" disabled  class="btn waves-effect waves-light  blue darken-2" style="margin-top: 1px;"><span class="glyphicon glyphicon-floppy-disk"></span><b> Salvar</b></button>     
               
        
        </div>

    </form>



</div> 
</div>



</div>


</div>
</div>
</div>


</div>


<form id="form-remover-produto" method="POST" action="{{ route('carrinho.removerConsig', $pedidos->id) }}">
	{{ csrf_field() }}
	{{ method_field('DELETE') }}
	{!! method_field('put') !!}
	<input type="hidden" name="pedido_id">
	<input type="hidden" name="produto_id">
	<input type="hidden" name="request_cod">
	<input type="hidden" name="item">
</form>



<form id="form-adicionar-produto" method="POST" action="{{ route('carrinho.adicionarConsig') }}">
	{{ csrf_field() }}
	<input type="hidden" name="id">
	<input type="hidden" name="request_cod">

	<input type="hidden" name="obs_pedido" value="{{$pedidos->obs_pedido}}">
	<input type="hidden" name="id_cliente" value="{{$pedidos->id_cliente}}">


</form>



@push('scripts')
<script type="text/javascript" src="/js/carrinho.js"></script>
@endpush

@endsection