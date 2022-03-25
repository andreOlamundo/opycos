<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>Pedidos Opycos</title>
 
        <!--Custon CSS (está em /public/assets/site/css/certificate.css)-->
        <!--<link rel="stylesheet" href="{{ url('assets/site/css/certificate.css') }}">-->
       
		
		


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



<table class="table-sm table-borderless" style="width:100%">
  <tbody>
  
  	   <tr>   
  	   <th colspan="2"><img src="{{ asset('/img/logo-opycos.png') }}" width="180" height="45" alt="Opycos" title="Opycos"></th> 	   
      
      <th style="width: 200px; word-wrap: break-word;">Pedido Número: {{ $pedidos->id }} <br>Data: {{ $pedidos->created_at->format('d/m/Y') }}</th>
    </tr>
    
  	</tbody>
  </table>	

  <hr style="margin-bottom: 3px; margin-top: 5px;">

  <table class="table-sm table-bordered" style="width:100%; border: hidden;">
  <tbody>
    <tr>    	
  	<th style="border-right: hidden;" >Cliente: {{$pedidos->Cliente->name}} <br> @if (isset($pedidos->Cliente->cnpj)) CNPJ: {{$pedidos->Cliente->cnpj}}@else CPF: {{$pedidos->Cliente->cpf}} @endif <br>Celular: {{$pedidos->Cliente->celInput}} <br>E-mail: {{($pedidos->Cliente->email)}}</th>     
      <th style="width: 220px; word-wrap: break-word; border-left: hidden;">Pagamento: {{ $pedidos->pagamento == 'D' ? 'Dinheiro' : '' }}{{ $pedidos->pagamento == 'CC' ? 'Cartão de Crédito' : '' }}{{ $pedidos->pagamento == 'CD' ? 'Cartão de Débito' : '' }}{{ $pedidos->pagamento == 'BB' ? 'Boleto Bancário' : '' }}{{ $pedidos->pagamento == 'DB' ? 'Depósito Bancário' : '' }} <br> Vendedor: {{$pedidos->Vendedor->name}}</th>  
   
    </tr>

     <!-- <tr>
     <th colspan="2" >Endereço: {{($pedidos->Cliente->endereço)}} {{($pedidos->Cliente->numero)}} Bairro: {{($pedidos->Cliente->bairro)}} Compl.: {{($pedidos->Frete->complemento)}} Cidade: {{($pedidos->Cliente->cidade)}} UF: {{($pedidos->Cliente->estado)}} CEP: {{($pedidos->Cliente->cep)}}</th> 


    
    </tr>-->

  </tbody>
</table>
  <hr style="margin-bottom: 3px; margin-top: 5px;">

		@if ((isset($pedidos->produto_id)) && (empty($pedidos->request_id)))
					<table class="table-sm table-bordered" style="width:100%">
						<thead>	
							<tr>                    
								<th>Código do Produto</th>
								<th>Descrição do Produto</th>
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
									{{ $item_pedido->product->prod_cod }}
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
						$unitario = $item_pedido->total / $item_pedido->quantidade;
						@endphp

					
					
						
					
						<td>
							
							R$ {{ number_format($unitario, 2, ',', '.')}}

						</td>  
						

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

<!--<tr><td colspan="2"><b></b></td>
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
		<td><b>R$ {{ number_format($total_pedido, 2, ',', '.') }}</b></td>



</tr>-->

</tbody>    
</table>

  <hr style="margin-bottom: 3px; margin-top: 5px;">

@elseif ((isset($pedidos->request_id)) && (empty($pedidos->produto_id)))
	<table class="table-sm table-bordered" style="width:100%">

		<thead>
	
			<tr>                    
				<th>Código da Requisição</th>
				<th>Descrição da Requisição</th>
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
				 {{ $item_pedido->request->request_cod}}
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
				$unitario = $item_pedido->total / $item_pedido->quantidade;
				@endphp

			
					<td>
						R$ {{ number_format($unitario, 2, ',', '.')}}
					</td>  
				 
				
			  



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

<!--<tr><td colspan="2"><b></b></td>
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
</tr>-->



</tbody>    
</table>
  <hr style="margin-bottom: 3px; margin-top: 5px;">
@else((isset($pedidos->request_id)) && (isset($pedidos->produto_id)))

					<table class="table-sm table-bordered" style="width:100%">
						<thead>	

							<tr>                    
								<th>Código do Produto</th>
								<th>Descrição do Produto</th>
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
						$unitario = $item_pedido->total / $item_pedido->quantidade;

						@endphp

				
						<td>
					  R$ {{ number_format($unitario, 2, ',', '.')}}

						</td>  
					
					


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

<!--<tr><td colspan="4"><b></b></td>
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



</tr>-->

</tbody>    
</table>
  <hr style="margin-bottom: 3px; margin-top: 5px;">

	<table class="table-sm table-bordered" style="width:100%">

		<thead>
	
			<tr>                    
				<th>Código da Requisição</th>
				<th>Descrição da Requisição</th>
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
				$unitario = $item_pedido->total / $item_pedido->quantidade;
				@endphp

			
				<td>
				R$ {{ number_format($unitario, 2, ',', '.')}}


				</td>  
				


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

<!--<tr><td colspan="4"><b></b></td>
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
</tr>-->



</tbody>    
</table>
  <hr style="margin-bottom: 3px; margin-top: 5px;">
@endif



<table class="table-sm table-bordered" style="width:100%">
  <tbody>
 @if (isset($pedidos->Frete->local))


    <tr>
      <th colspan="4" >Local de entrega: {{($pedidos->Frete->endereço)}} {{($pedidos->Frete->numero)}} Bairro: {{($pedidos->Frete->bairro)}} Compl.: {{($pedidos->Frete->complemento)}}<br> Cidade: {{($pedidos->Frete->cidade)}} UF: {{($pedidos->Frete->estado)}} CEP: {{($pedidos->Frete->cep)}}</th>

    </tr>  
    <tr>
    	<td>Portador:  {{ $pedidos->Frete->entrega == 'B' ? 'Moto Boy' : '' }}{{ $pedidos->Frete->entrega == 'C' ? 'Correios' : '' }}{{ $pedidos->Frete->entrega == 'R' ? 'Ritirada Balcão' : '' }}</td>
    	<td>Prazo de Entrega: @if (isset($pedidos->Frete->prazo_entrega)  && ($pedidos->Frete->prazo_entrega >= 1) ) {{ ($pedidos->Frete->prazo_entrega) }} {{ $pedidos->Frete->prazo_entrega > '1' ? 'Dias' : '' }} {{ $pedidos->Frete->prazo_entrega == '1' ? 'Dia' : '' }} @else Ausente @endif</td>      
    	<td>Serviço Correio: @if ($pedidos->Frete->serviço_correio == '04014' && $pedidos->Frete->entrega == 'C') SEDEX @elseif ($pedidos->Frete->serviço_correio == '04510' && $pedidos->Frete->entrega == 'C') PAC à vista @elseif ($pedidos->Frete->serviço_correio == '04782' && $pedidos->Frete->entrega == 'C') SEDEX 12 @elseif ($pedidos->Frete->serviço_correio == '04790' && $pedidos->Frete->entrega == 'C') SEDEX 10 @elseif ($pedidos->Frete->serviço_correio == '04804 ' && $pedidos->Frete->entrega == 'C') SEDEX Hoje @else Ausente  @endif</td>
    	<th style="text-align: right;">(+) Valor: R$ {{ number_format($pedidos->Frete->valor, 2, ',', '.') }}</th>
    </tr>

    	
    @elseif (isset($pedidos->Frete->balcao))
  
     <tr>
      <th>Retirada Balcão</th>      
    </tr> 


    @else

    <tr>
      <th colspan="4">Local de entrega: {{($pedidos->Cliente->endereço)}} {{($pedidos->Cliente->numero)}} Bairro: {{($pedidos->Cliente->bairro)}} Compl.: {{($pedidos->Frete->complemento)}}
      	{{($pedidos->Cliente->complemento)}}<br> Cidade: {{($pedidos->Cliente->cidade)}} UF: {{($pedidos->Cliente->estado)}} CEP: {{($pedidos->Cliente->cep)}}</th>      
    </tr> 
    <tr>
    	<td>Portador: @if ($pedidos->Frete->entrega == 'B') Moto Boy @elseif ($pedidos->Frete->entrega == 'C') Correios @else Retidara Balcão @endif </td>
    	<td>Prazo de Entrega: @if (isset($pedidos->Frete->prazo_entrega) && ($pedidos->Frete->prazo_entrega >= 1) ) {{ ($pedidos->Frete->prazo_entrega) }} {{ $pedidos->Frete->prazo_entrega > '1' ? 'Dias' : '' }} {{ $pedidos->Frete->prazo_entrega == '1' ? 'Dia' : '' }} @else Ausente @endif</td>      
    	<td>Serviço Correio: @if ($pedidos->Frete->serviço_correio == '04014') SEDEX @elseif ($pedidos->Frete->serviço_correio == '04510') PAC à vista @elseif ($pedidos->Frete->serviço_correio == '04782') SEDEX 12 @elseif ($pedidos->Frete->serviço_correio == '04790') SEDEX 10 @elseif ($pedidos->Frete->serviço_correio == '04804 ') SEDEX Hoje @else Ausente @endif</td>
    	<th  style="text-align: right;">(+)Valor: R$ {{ number_format($pedidos->Frete->valor, 2, ',', '.') }}</th>
    
    </tr>
@endif 

  </tbody>
</table>
  <hr style="margin-bottom: 3px; margin-top: 5px;">

<table class="table-sm table-bordered" style="width:100%">
  <tbody>
    <tr><th style="text-align: right;">Total: R$ {{ number_format($total_geral, 2, ',', '.') }}</th></tr>
     <tr><th>OBSERVAÇÕES: {{$pedidos->obs_pedido}}</th></tr>

    
     
  </tbody>
</table>



  
    

 <footer class="footer"> 
    <b>© Opycos <?php echo date("Y"); ?>.</b>
        </footer>
        


</body>
</html>