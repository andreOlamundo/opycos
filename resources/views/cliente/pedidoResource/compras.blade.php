@extends('templates.cliente-login')

@section('css-view')
@endsection

@section('js-view')
@endsection

@section('templates.menu-superior-cliente')
@endsection

@section('conteudo-view')  
<div id="line-one">
  <div id="line-one">
<div class="container">   
 
      <h2><b>Meus pedidos</b></h2>
      <hr>
 
<div class="row">
            <div class="col-md-12">


@if (Session::has('mensagem-sucesso'))
<div class="alert alert-success alert-dismissible">
    <strong>{{ Session::get('mensagem-sucesso') }}</strong>
    <a href="#" class="close" 
    data-dismiss="alert"
    aria-label="close">&times;</a>
</div>
@endif
@if (Session::has('mensagem-falha'))
<div class="alert alert-danger alert-dismissible">
    <strong>{{ Session::get('mensagem-falha') }}</strong>
    <a href="#" class="close" 
    data-dismiss="alert"
    aria-label="close">&times;</a>
</div>
@endif




    <div class="row">
    	<!--<div class="col-md-12">
         <div class="col-md-3">
             <h4 title="Ultimos pedidos lançados em ordem decrescente" style="text-align: center; color:white; background-color: green; border-radius: 3px;">Últimos pedidos cadastrados</h4>
        </div>-->
        <div class="col-md-12">

        
        
            
            <form method="POST" action="{{ route('carrinho.cancelar') }}">
                {{ csrf_field() }}
             


              
                  <table class="table table-striped table-bordered table-condensed table-hover" >
                    <thead>
                      <tr class="warning"> 

                        <th>Código</th>
                        <th>Cliente</th>
                        <th>Data</th> 
                       <th> status </th>                         
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                   @foreach ($pedidos as $compra)
                    <tr>                    
                    <td>{{ $compra->id }}</td>
                    <td>{{ ($compra->Cliente->name) }}</td>
                    <td>{{ $compra->created_at->format('d/m/Y H:i') }}</td>                
                   <td  title="{{ $compra->status == 'RE' ? 'Reservado' : '' }}{{ $compra->status == 'AP' ? 'Aguardando Pagamento' : '' }}{{ $compra->status == 'EL' ? 'Encaminhado ao Laboratorio' : '' }}{{ $compra->status == 'EC' ? 'Enviado ao Cliente' : '' }}{{ $compra->status == 'FI' ? 'Finalizado' : '' }}{{ $compra->status == 'GE' ? 'Em Aberto' : '' }}{{ $compra->status == 'CA' ? 'Cancelado' : '' }} {{ $compra->updated_at->format('d/m/Y H:i') }}">{{ $compra->status == 'RE' ? 'Reservado' : '' }}{{ $compra->status == 'GE' ? 'Em aberto' : '' }}{{ $compra->status == 'AP' ? 'Ag. Pag.' : '' }}{{ $compra->status == 'EL' ? 'Enc.ao Lab' : '' }}{{ $compra->status == 'EC' ? 'Enviado ao Cliente' : '' }}{{ $compra->status == 'FI' ? 'Finalizado' : '' }}{{ $compra->status == 'CA' ? 'Cancelado' : '' }}</td>               
                   @php
                   $total_pedido = 0;
                   @endphp

                   @foreach ($compra->pedido_produtos_itens as $pedido_produto)

                 
                   @if ($compra->Frete->status == 'AR' && $compra->Frete->balcao == 'Y')
                   @php
                   $total_produto = $compra->pedido_produtos_itens->sum('prod_preco_padrao'); 
                   $total_desc_produto = $compra->pedido_produtos_itens->sum('prod_desconto');
                   $total_desc_request = $compra->pedido_produtos_itens->sum('request_desconto');
                   $total_desconto = $total_desc_request + $total_desc_produto;
                   $total_pedido = $total_produto - $total_desconto;
                   @endphp           


                   @elseif ($compra->Frete->status == 'EMB' && $compra->Frete->entrega == 'B')
                  
                   @php
                   $total_produto = $compra->pedido_produtos_itens->sum('prod_preco_padrao');
                   $total_pedido = $compra->Frete->valor;
                   $total_desc_produto = $compra->pedido_produtos_itens->sum('prod_desconto');
                   $total_desc_request = $compra->pedido_produtos_itens->sum('request_desconto');
                   $total_desconto = $total_desc_request + $total_desc_produto;
                   $total_pedido += $total_produto - $total_desconto;              
                   @endphp
                 

                   @elseif ($compra->Frete->status == 'EC' && $compra->Frete->entrega == 'C')
                 
                   @php                 
                   $total_produto = $compra->pedido_produtos_itens->sum('prod_preco_padrao');                
                   $total_pedido = $compra->Frete->valor;
                   $total_desc_produto = $compra->pedido_produtos_itens->sum('prod_desconto');
                   $total_desc_request = $compra->pedido_produtos_itens->sum('request_desconto');
                   $total_desconto = $total_desc_request + $total_desc_produto;
                   $total_pedido += $total_produto - $total_desconto;
                   @endphp
                

                   @elseif ($compra->Frete->status == 'EMB' && $compra->Frete->entrega == 'CA')
                  
                   @php
                   $total_produto = $compra->pedido_produtos_itens->sum('prod_preco_padrao');
                   $total_pedido = $compra->Frete->valor;
                   $total_desc_produto = $compra->pedido_produtos_itens->sum('prod_desconto');
                   $total_desc_request = $compra->pedido_produtos_itens->sum('request_desconto');
                   $total_desconto = $total_desc_request + $total_desc_produto;
                   $total_pedido += $total_produto - $total_desconto;
                   @endphp
                  

                   @elseif ($compra->Frete->status == 'EC' && $compra->Frete->entrega == 'CA')
                 
                   @php
                   $total_produto = $compra->pedido_produtos_itens->sum('prod_preco_padrao');
                   $total_pedido = $compra->Frete->valor;
                   $total_desc_produto = $compra->pedido_produtos_itens->sum('prod_desconto');
                   $total_desc_request = $compra->pedido_produtos_itens->sum('request_desconto');
                   $total_desconto = $total_desc_request + $total_desc_produto;
                   $total_pedido += $total_produto - $total_desconto;              
                   @endphp
                 

                   @elseif ($compra->Frete->status == 'EMB' && $compra->Frete->entrega == 'FI')
                 
                   @php
                   $total_produto = $compra->pedido_produtos_itens->sum('prod_preco_padrao');
                   $total_pedido = $compra->Frete->valor;
                   $total_desc_produto = $compra->pedido_produtos_itens->sum('prod_desconto');
                   $total_desc_request = $compra->pedido_produtos_itens->sum('request_desconto');
                   $total_desconto = $total_desc_request + $total_desc_produto;
                   $total_pedido += $total_produto - $total_desconto;              
                   @endphp


                   @else ($compra->Frete->status == 'EC' && $compra->Frete->entrega == 'FI')
                  
                   @php
                   $total_produto = $compra->pedido_produtos_itens->sum('prod_preco_padrao');
                   $total_pedido = $compra->Frete->valor;
                   $total_desc_produto = $compra->pedido_produtos_itens->sum('prod_desconto');
                   $total_desc_request = $compra->pedido_produtos_itens->sum('request_desconto');
                   $total_desconto = $total_desc_request + $total_desc_produto;
                   $total_pedido += $total_produto - $total_desconto;              
                   @endphp
                 

                   @endif
                   

                   @endforeach
                 
     


              
                <td>R$ {{ number_format($total_pedido, 2, ',', '.') }}</td>
                @endforeach
                 </tr>
            </tbody>
            <tfoot>
               
                    
                  
                    
                
         
            </tfoot>
        </table>
@forelse ($compras as $pedido)
<input type="hidden" name="pedido_id" value="{{ $pedido->id }}"/>
    </form>


@empty
<h5 id="center">
    @if ($cancelados->count() > 0)
    Neste momento não há nenhum pedido concluído.
    @else
    Você ainda não fez nenhum pedido.
    @endif
</h5>
@endforelse
</div>
</div>


  @if (isset($dataForm))
  <div class="row">
  <div class="col-sm-5 pull-left">
    <p class="text-cinza"> Mostrando 1 até 10 de {{$total}} Registros</p>
  </div>
  <div class="pull-right">
    
    {!! $pedidos->appends($dataForm)->links() !!}
    @else
    {!! $pedidos->links() !!}
    
      </div>
</div>
    @endif




</div>
</div>
</div>
</div>
</div>
@endsection