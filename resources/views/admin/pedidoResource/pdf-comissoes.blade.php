<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>Pedidos Opycos</title>
 
        <!--Custon CSS (está em /public/assets/site/css/certificate.css)-->
        <!--<link rel="stylesheet" href="{{ url('assets/site/css/certificate.css') }}">
		<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/>
        -->
       
		
	


    </head>
    <style type="text/css">
    	html, body {

background-color: white;

}


.footer {
   position: fixed;
   left: 0;
   bottom: 0;
   width: 100%;
   color: #743C2f;   
   text-align: center;
}


    </style>
    <body>

    	<img src="{{ asset('/img/logo-opycos.png') }}" class="img-responsive"  width="180" height="45" alt="Opycos" title="Opycos">
    	<hr style="margin-bottom: 0px; margin-top: 5px;">


@php
  $total_geral =0;

  @endphp
  @foreach($pedidos->itens_pedido as $item_pedido)
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

 @if (isset($pedidos->Frete->valor))
@php
$total_geral += $pedidos->Frete->valor;
@endphp
@else
@endif

<table class="table-sm table-bordered" style="width:100%">
  <tbody>
  	<tr style="background-color: #fcf8e3;"><th colspan="3" style="text-align: center;">Comissão</th></tr>
  	   <tr>    	
  	<th>Pedido: {{ $pedidos->id }}</th>     
      <th>Finalização: {{ $pedidos->updated_at->format('d/m/Y') }}</th>       
   	   
   	   @if ($pedidos->comissao == 'PE')
       <th>Total pago: R$ 0,00</th>
       @else ($pedidos->comissao == 'PA')
       <th>Total pago: R$ {{($pedidos->Comissao->valor_comissao)}}</th>
       @endif    
      
    </tr>
    <tr>
    	@if ($pedidos->comissao == 'PE')
    	<td colspan="2">
    		OBSERVAÇÕES:
    	</td>
    	@else ($pedidos->comissao == 'PA')
    		<td colspan="2">
    		OBSERVAÇÕES: {{$pedidos->Comissao->obs_comissao}}
    	</td>
    	@endif

    	<td colspan="1">
    	@if ($pedidos->comissao == 'PE')
       Status: <span style="color: red;">Pendente</span>
        @else ($pedidos->comissao == 'PA')
        Status: <span style="color: blue;">Pago</span> {{ $pedidos->Comissao->updated_at->format('d/m/Y') }}
        @endif
    	</td>
    </tr>
  	</tbody>
  </table>



<table class="table-sm table-bordered" style="width:100%">
  <tbody>
  	<tr style="background-color: #fcf8e3;"><th colspan="4" style="text-align: center;">Dados do Pedido</th></tr>
  	   <tr>    	
  	<th>Nº: {{ $pedidos->id }}</th>     
      <th>Data: {{ $pedidos->updated_at->format('d/m/Y') }}</th>       
   
      <th>Pagamento: {{$pedidos->pagamento}} </th>
      <th>Total: R$ {{ number_format($total_geral, 2, ',', '.') }}</th>
    </tr>
    <tr>
    	<td colspan="3">
    		OBSERVAÇÕES: {{$pedidos->obs_pedido}}
    	</td>
    	<td colspan="1">
    	Status: @if ($pedidos->status == 'AP')Aguardando Pagamento @elseif ($pedidos->status == 'EL')Encaminhado ao Lab. @elseif ($pedidos->status == 'CA')Cancelado @elseif ($pedidos->status == 'EC')Enviado @elseif ($pedidos->status == 'RE')Reservado @else ($pedidos->status == 'FI')Finalizado @endif
    	</td>
    </tr>
  	</tbody>
  </table>


	
			<div class="divider" style="margin-bottom: 0px; margin-top: 5px; border: 1px solid grey opacity 0.5px; text-align: center; background-color: #fcf8e3;" ><strong>Itens do Pedido</strong></div>              

@if ((isset($pedidos->produto_id)) && (empty($pedidos->request_id)))
					<table class="table-sm table-bordered" style="width:100%">
						<thead>	

							<tr>                    
								<th>Código</th>
								<th>Produto</th>
								<th>Qtd</th>              
								<th>Preço Unitário</th>
								<th>Desconto</th>
								<th>Total</th>								
							</tr>
						</thead> 

						<tbody> 
							@php
							$total_pedido =0;
							@endphp

							@foreach ($pedidos->itens_pedido as $item_pedido)
							<tr> 

								<td>
									<span> {{ $item_pedido->product->prod_cod }}</span>
								</td> 
								<td>
									{{ $item_pedido->product->prod_desc}}
								</td>
								<td>
									{{ $item_pedido->quantidade}}
								</td>
						@php  
						$desconto = $item_pedido->totalDesconto;
						$desconto_ratiado = ($desconto /$item_pedido->quantidade);
						@endphp

						@if ($desconto > 0)
						@php 
						$preco_com_desconto = $item_pedido->product->prod_preco_padrao - $desconto_ratiado;
						@endphp
						<td><b style="text-decoration: line-through;"> R$ {{ number_format($item_pedido->product->prod_preco_padrao, 2, ',', '.')}}</b><br>
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

<tr><td colspan="4"><b>Totais consolidados:</b></td>
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

<div class="divider" style="margin-bottom: 3px; margin-top: 5px;"></div>

@elseif ((isset($pedidos->request_id)) && (empty($pedidos->produto_id)))
	<table class="table-sm table-bordered" style="width:100%">

		<thead>
	
			<tr>                    
				<th>Código</th>
				<th>Requisição</th>
				<th>Qtd</th>              
				<th>Preço Unitário</th>
				<th>Desconto</th>
				<th>Total</th>
			

			</tr>
		</thead> 

		<tbody> 


			@php
			$total_pedido =0;
			@endphp

			@foreach ($pedidos->itens_pedido_request as $item_pedido)
			<tr> 

				<td>
					<span> {{ $item_pedido->request->request_cod}}</span>
				</td> 
				<td>
					{{ $item_pedido->request->request_desc}}
				</td>
				<td>
					{{ $item_pedido->quantidade}}

				</td>

				@php  
				$desconto = $item_pedido->totalDesconto;
				$desconto_ratiado = ($desconto /$item_pedido->quantidade);
				@endphp

				@if ($desconto > 0)
				@php 
				$preco_com_desconto = $item_pedido->request->request_valor - $desconto_ratiado;
				@endphp
					<td>
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

<tr><td colspan="4"><b>Totais consolidados:</b></td>
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
<div class="divider" style="margin-bottom: 3px; margin-top: 5px;"></div>
@else((isset($pedidos->request_id)) && (isset($pedidos->produto_id)))

					<table class="table-sm table-bordered" style="width:100%">
						<thead>	

							<tr>                    
								<th>Código</th>
								<th>Produto</th>
								<th>Qtd</th>              
								<th>Preço Unitário</th>
								<th>Desconto</th>
								<th>Total</th>								
							</tr>
						</thead> 

						<tbody> 
							@php
							$total_pedido =0;
							@endphp

							@foreach ($pedidos->itens_pedido as $item_pedido)
							<tr> 

								<td>
									<span> {{ $item_pedido->product->prod_cod }}</span>
								</td> 
								<td>
									{{ $item_pedido->product->prod_desc}}
								</td>
								<td>
									{{ $item_pedido->quantidade}}
								</td>
						@php  
						$desconto = $item_pedido->totalDesconto;
						$desconto_ratiado = ($desconto /$item_pedido->quantidade);
						@endphp

						@if ($desconto > 0)
						@php 
						$preco_com_desconto = $item_pedido->product->prod_preco_padrao - $desconto_ratiado;
						@endphp
						<td><b style="text-decoration: line-through;"> R$ {{ number_format($item_pedido->product->prod_preco_padrao, 2, ',', '.')}}</b><br>
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

<tr><td colspan="4"><b>Totais consolidados:</b></td>
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

<div class="divider" style="margin-bottom: 3px; margin-top: 5px;"></div>

	<table class="table-sm table-bordered" style="width:100%">

		<thead>
	
			<tr>                    
				<th>Código</th>
				<th>Requisição</th>
				<th>Qtd</th>              
				<th>Preço Unitário</th>
				<th>Desconto</th>
				<th>Total</th>
			

			</tr>
		</thead> 

		<tbody> 


			@php
			$total_pedido =0;
			@endphp

			@foreach ($pedidos->itens_pedido_request as $item_pedido)
			<tr> 

				<td>
					<span> {{ $item_pedido->request->request_cod}}</span>
				</td> 
				<td>
					{{ $item_pedido->request->request_desc}}
				</td>
				<td>
					{{ $item_pedido->quantidade}}

				</td>

				@php  
				$desconto = $item_pedido->totalDesconto;
				$desconto_ratiado = ($desconto /$item_pedido->quantidade);
				@endphp

				@if ($desconto > 0)
				@php 
				$preco_com_desconto = $item_pedido->request->request_valor - $desconto_ratiado;
				@endphp
					<td>
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

<tr><td colspan="4"><b>Totais consolidados:</b></td>
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
<div class="divider" style="margin-bottom: 3px; margin-top: 5px;"></div>
@endif

<table class="table-sm table-bordered" style="width:100%">
  <tbody>
  	<tr style="background-color: #fcf8e3;"><th colspan="3" style="text-align: center;">Dados do Vendedor</th></tr>
    <tr>    	
  	<th colspan="2">Nome: {{$pedidos->Vendedor->name}}</th>     
      <th>Cel: {{$pedidos->Vendedor->cel}}</th>     
    </tr>
    <tr>
	  <th>@if (isset($pedidos->Vendedor->cnpj))CNPJ: {{$pedidos->Vendedor->cnpj}}@else CPF: {{$pedidos->Vendedor->cpf}} @endif</th>
      <th>E-mail: {{($pedidos->Vendedor->email)}}</th>
      <th>Tel: {{$pedidos->Vendedor->tel}}</th>
    </tr>          
  </tbody>
</table>

<div class="divider" style="margin-bottom: 3px; margin-top: 5px;"></div>

<table class="table-sm table-bordered" style="width:100%">
  <tbody>
  	<tr style="background-color: #fcf8e3;"><th colspan="4" style="text-align: center;">Dados do Cliente</th></tr>
    <tr>    	
  	<th colspan="3">Nome: {{$pedidos->Cliente->name}}</th>     
      <th>Cel: {{$pedidos->Cliente->celInput}}</th>     
    </tr>
    <tr>
	  <th colspan="2">@if (isset($pedidos->Cliente->cnpj))CNPJ: {{$pedidos->Cliente->cnpj}}@else CPF: {{$pedidos->Cliente->cpf}} @endif</th>
      <th colspan="2">E-mail: {{($pedidos->Cliente->email)}}</th>
    </tr>
     <tr>
      <th colspan="2">Endereço: {{($pedidos->Cliente->endereço)}} {{($pedidos->Cliente->numero)}}</th>
      <th colspan="1">Compl.: {{($pedidos->Frete->complemento)}}</th>
      <th colspan="1">Bairro: {{($pedidos->Cliente->bairro)}}</th>
    </tr>   
    <tr>   
      <th>Cidade: {{($pedidos->Cliente->cidade)}} </th>
      <th>UF: {{($pedidos->Cliente->estado)}}</th> 
      <th>CEP: {{($pedidos->Cliente->cep)}}</th>
      <th>Tel: {{$pedidos->Cliente->tel}}</th>
    </tr>
    
  </tbody>
</table>

<div class="divider" style="margin-bottom: 3px; margin-top: 5px;"></div>


<table class="table-sm table-bordered" style="width:100%">
  <tbody>
 @if (isset($pedidos->Frete->local))

   <tr style="background-color: #fcf8e3;"><th colspan="4" style="text-align: center;">Local de entrega</th></tr>
    <tr>
      <th colspan="2">Endereço: {{($pedidos->Frete->endereço)}} {{($pedidos->Frete->numero)}}</th>
      <th colspan="1">Compl.: {{($pedidos->Frete->complemento)}}</th>
      <th colspan="1">Bairro: {{($pedidos->Frete->bairro)}}</th>
    </tr>   
    <tr>   
      <th>Cidade: {{($pedidos->Frete->cidade)}} </th>
      <th>UF: {{($pedidos->Frete->estado)}}</th> 
      <th>CEP: {{($pedidos->Frete->cep)}}</th>
      <th>Tel: {{$pedidos->Cliente->tel}}</th>
    </tr>

    <tr>
    	<td>Portador: @if ($pedidos->Frete->entrega == 'B') Moto Boy @elseif ($pedidos->Frete->entrega == 'C') Correios @else Retidara Balcão @endif </td> 
    	<td>P.Entrega: @if (isset($pedidos->Frete->prazo_entrega)) {{ ($pedidos->Frete->prazo_entrega) }} dias @else @endif</td>      
    	<td>S.Correio: @if ($pedidos->Frete->serviço_correio == '04014') SEDEX @elseif ($pedidos->Frete->serviço_correio == '04510') PAC à vista @elseif ($pedidos->Frete->serviço_correio == '04782') SEDEX 12 @elseif ($pedidos->Frete->serviço_correio == '04790') SEDEX 10 @elseif ($pedidos->Frete->serviço_correio == '04804 ') SEDEX Hoje @else Não @endif</td>
    	<th>Custo: R$ {{ number_format($pedidos->Frete->valor, 2, ',', '.') }}</th>
    </tr>

    	
    @elseif (isset($pedidos->Frete->balcao))
    <tr style="background-color: #fcf8e3;"><th style="text-align: center;">Local de entrega</th></tr>
     <tr>
      <th>Retirada Balcão</th>      
    </tr> 


    @else
    <tr style="background-color: #fcf8e3;"><th colspan="4" style="text-align: center;">Local de entrega</th></tr>
    <tr>
      <th colspan="4">O Mesmo informado nos dados do cliente.</th>      
    </tr> 
    <tr>
    	<td>Portador: @if ($pedidos->Frete->entrega == 'B') Moto Boy @elseif ($pedidos->Frete->entrega == 'C') Correios @else Retidara Balcão @endif </td> 
    	<td>P.Entrega: @if (isset($pedidos->Frete->prazo_entrega)) {{ ($pedidos->Frete->prazo_entrega) }} dias @else @endif</td>      
    	<td>S.Correio: @if ($pedidos->Frete->serviço_correio == '04014') SEDEX @elseif ($pedidos->Frete->serviço_correio == '04510') PAC à vista @elseif ($pedidos->Frete->serviço_correio == '04782') SEDEX 12 @elseif ($pedidos->Frete->serviço_correio == '04790') SEDEX 10 @elseif ($pedidos->Frete->serviço_correio == '04804 ') SEDEX Hoje @else N @endif</td>
    	<th>Custo: R$ {{ number_format($pedidos->Frete->valor, 2, ',', '.') }}</th>
    
    </tr>
@endif 

  </tbody>
</table>
<div class="divider" style="margin-bottom: 3px; margin-top: 5px;"></div>

<table  class="table-sm" style="width:100%" >
	<tbody>
	<tr>
      <th>Nome do Responsável:</th> 
        <th>Nome do Vendedor: {{$pedidos->Vendedor->name}}</th>
    </tr>
	<tr>
      <th>Doc:</th>
              <th>@if (isset($pedidos->Vendedor->cnpj))CNPJ: {{$pedidos->Vendedor->cnpj}}@else CPF: {{$pedidos->Vendedor->cpf}} @endif</th>
    </tr>
    	<tr>
      <th>Assinatura:__________________</th>             
       <th>Assinatura:__________________</th> 
    </tr>		
	</tbody>
</table>
	 <footer class="footer"> 
    <b>© Opycos <?php echo date("Y"); ?>.</b>
        </footer>

</body>


</html>