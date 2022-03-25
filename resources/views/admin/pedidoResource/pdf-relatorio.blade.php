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

    	<table class="table-sm table-bordered" style="width:100%">
  <tbody>
  	<tr style="background-color: #fcf8e3;"><th colspan="2" style="text-align: center;">Dados do Vendedor</th></tr>
    <tr>    	
  	<th colspan="1">Nome: {{$pedidos->Vendedor->name}}</th>     
      <th>Cel: {{$pedidos->Vendedor->cel}}</th>     
    </tr>
    <tr>
	  <!--<th>@if (isset($pedidos->Vendedor->cnpj))CNPJ: {{$pedidos->Vendedor->cnpj}}@else CPF: {{$pedidos->Vendedor->cpf}} @endif</th>-->
      <th>E-mail: {{($pedidos->Vendedor->email)}}</th>
      <th>Tel: {{$pedidos->Vendedor->tel}}</th>
    </tr>          
  </tbody>
</table>



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
  	<th> Pedidos:@foreach ($comissoes as $comissao)
    	{{ $comissao->pedido_id }}
		@endforeach
  		
  		
  	
  	</th>     
      <th>Finalização: {{ $pedidos->updated_at->format('d/m/Y') }}</th>       
   	   
   	   @if ($pedidos->comissao == 'PE')
       <th>Total pago: R$ 0,00</th>
       @else ($pedidos->comissao == 'PA')
       <th>Total pago: R$ {{ number_format($comissao_total, 2, ',', '.') }}</th>
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
        Status: <span style="color: blue;">Pago em:</span> {{ $pedidos->Comissao->updated_at->format('d/m/Y') }}
        @endif
    	</td>
    </tr>
  	</tbody>
  </table>



<table class="table-sm table-bordered" style="width:100%">
  <tbody>
  	<tr style="background-color: #fcf8e3;"><th colspan="4" style="text-align: center;">Dados do Pedido</th></tr>
  	   <tr>    	
  	<th>Pedidos: @foreach ($comissoes as $comissao)
    	{{ $comissao->pedido_id }}
		@endforeach</th>     
      <th>Data: {{ $pedidos->updated_at->format('d/m/Y') }}</th>       
   
      <th>Pagamento: {{$pedidos->pagamento}} </th>
      <th>

      Total: R$ {{ number_format($soma_pedidos, 2, ',', '.') }}</th>
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