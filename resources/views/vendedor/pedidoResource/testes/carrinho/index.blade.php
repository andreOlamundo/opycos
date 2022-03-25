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
  
      <h2><b>Meu Pedido</b></h2>
    
<div class="row">
            <div class="col-md-12">
    <ol class="breadcrumb">                         
    <li class="active" style="text-decoration: none"><b>Meu Pedido</b></li>                  
    <li><a href="{{route('index')}}" id="btn" style="text-decoration: none"><b>Novo Pedido</b></a></li>
    <li><a href="{{route('carrinho.compras')}}"  id="btn" style="text-decoration: none"><b>Pedidos Concluídos</b></a></li>
  </ol> 
  </div>
  </div> 

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

  
<div class="jumbotron">
   
    @forelse ($pedidos as $pedido)
    <p class="lead" id="black"> Pedido: {{ $pedido->id }}
     Gerado em: {{ $pedido->created_at->format('d/m/Y H:i') }} </p>


 
</div>

   <div class="col-md-12">
    <div class="row">
    <div class="table-responsive">
      <table class="table table-striped table-bordered table-condensed table-hover" >
        <thead>
          <tr class="warning">                    
           <th id="center">Código</th>
           <th id="center">Produto</th>
           <th id="center">Qtd</th>
           <th id="center">Preço Padrão Unitário</th>
           <th id="center">Total</th>
           <th id="center" class="col-xs-1">Acão</th>


         </tr>
       </thead> 
       <tbody> 
        @php
        $total_pedido =0;
        @endphp
        @foreach ($pedido->itens_pedido as $item_pedido)
        <tr> 

         <td id="center">
          <span class="chip"> {{ $item_pedido->product->prod_cod}}</span>
        </td> 
        <td  id="center">
          {{ $item_pedido->product->prod_desc}}
        </td>
        <td  id="center">
          {{ $item_pedido->quantidade}}
        </td>
        <td id="center">
          R$ {{ number_format($item_pedido->product->prod_preco_padrao, 2, ',', '.')}}
        </td>

        @php
        $total_produto = $item_pedido->total;
        $total_pedido += $total_produto;

        @endphp

        <td  id="center">
         R$ {{ number_format($total_produto, 2, ',', '.') }}

       </td>
       <td title="Ações" id="center" > 
        <div class="center-align">

          <a class="col 14 m4 s4" href="#" onclick="carrinhoRemoverProduto({{ $pedido->id}}, {{ $item_pedido->produto_id }}, 1)"
            data-toggle="tooltip" 
            data-placement="top"
            title="Remover"><i class="material-icons small">remove_circle_outline</i>
          </a>

          <span class="col 14 m4 s4"> {{ $item_pedido->quantidade }}</span>

          <a class="col 14 m4 s4" href="#" onclick="carrinhoAdicionarProduto({{ $item_pedido->produto_id }})" 
            data-toggle="tooltip" 
            data-placement="top"
            title="Adicionar"><i class="material-icons small">add_circle_outline</i></a>


          </div>

          <a href="#" onclick="carrinhoRemoverProduto({{ $pedido->id}}, {{ $item_pedido->produto_id }}, 0)" class="tooltipped" data-toggle="tooltip" data-placement="left" data-delay="50"   title="Remover item do pedido?">Remover Item</a>

        </td>
 </tr>
    


      
      @endforeach

             <tr class="warning">

     <td colspan="6" valign="top">
      <strong class="col offset-l6 offset-m6 offset-s6 l4 m4 s4 right-align"><b >Total do pedido: </b></strong>
      <span class="col l2 m2 s2">R$ {{ number_format($total_pedido, 2, ',', '.') }}</span>
     </td>
   </tr>
    </tbody>    
  </table>
</div>



<hr>
 
  <form method="POST" action="{{ route('carrinho.concluir') }}">
    {{ csrf_field() }}
    <input type="hidden" name="pedido_id" value="{{ $pedido->id }}">
    <button type="submit" class="btn btn-primary" data-position="top" data-delay="50" data-toggle="tooltip" data-placement="bottom" title="Concluir Pedido?">
     <b>Cadastrar Pedido</b>
    </button>   
  </form>

@empty
<h5 id="black"><b>Não há nenhum pedido gerado</b></h5>
@endforelse
</div>
</div>
</div>
</div>
</div>

<form id="form-remover-produto" method="POST" action="{{ route('carrinho.remover') }}">
  {{ csrf_field() }}
  {{ method_field('DELETE') }}
  <input type="hidden" name="pedido_id">
  <input type="hidden" name="produto_id">
  <input type="hidden" name="item">
</form>
<form id="form-adicionar-produto" method="POST" action="{{ route('carrinho.adicionar') }}">
  {{ csrf_field() }}
  <input type="hidden" name="id">
</form>

@push('scripts')
<script type="text/javascript" src="/js/carrinho.js"></script>
@endpush

@endsection