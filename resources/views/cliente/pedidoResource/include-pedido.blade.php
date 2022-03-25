@extends('templates.admin-login')

@section('css-view')
@endsection

@section('js-view')
@endsection

@section('templates.menu-superior-admin')
@endsection

@section('conteudo-view')   


<div class="container">
  <div class="row">
    <div class="col-md-12">          
      <h2><b>Cadastro de Pedidos</b></h2>      
    </div>
  </div>
  
  <ol class="breadcrumb">                         
    <li><a href="#">Pedidos</a></li>                  
    <li class="active">Cadastro</li>
  </ol>              
  

    

 <!-- RF10_Previsão_De_Desembolso_Do_Projeto -->
 <div class="col-md-12"><hr>

  <div class="row">
   @forelse($pedidos as $pedido) 

   <div class="input-group"> 
    <span class="col l6 s12 m6">Pedido N° {{ $pedido->id}}</span>
    <span class="col l6 s12 m6">Gerado em: {{  $pedido->created_at->format('d/m/y H:i') }}</span>
  </div>

  <div class="table-responsive">
    <table class="table table-striped table-bordered table-condensed table-hover" id="tbl" >
      <thead>
        <tr class="warning">                    
         <th id="center">Código</th>
         <th id="center">Qtd</th>
         <th id="center">Produto</th>
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

       <td>
        

       </td> 

       <td title="Ações" id="center"> 
        <div class="center-align">

          <a class="col 14 m4 s4" href="#" 
          data-toggle="tooltip" 
          data-placement="top"
          title="Remover"><i class="material-icons small">remove_circle_outline</i>
        </a>
        &nbsp;
        <span class="col 14 m4 s4"> {{ $item_pedido->quantidade }}</span>

        <a class="col 14 m4 s4" href="#" 
        data-toggle="tooltip" 
        data-placement="top"
        title="Adicionar"><i class="material-icons small">add_circle_outline</i></a>
        &nbsp;

      </div>

      <a href="#" class="tooltipped" data-position="right" data-delay="50" data-toggle="Remover itens do pedido?"  title="Remover Itens">Remover Itens</a>

    </td>
    <td>{{ $item_pedido->product->prod_desc}}</td>
    <td>R$ {{ number_format($item_pedido->product->preco_valor_padrao, 2, ',', '.')}}</td>

    @php
    $total_produto = $item_pedido->valores;
    $total_pedido += $total_produto;

    @endphp

    <td>
     R$ {{ number_format($total_produto, 2, ',', '.') }}

   </td>
   

 </tr>
 @endforeach
</tbody>    
</table>

<div class="row">
  <strong class="col offiset-l6 offset-s6 l4 m4 s4 right-align">Total do Pedido </strong>
  <span class="col 12 l2 m2 s2">R$ {{ number_format($total_pedido, 2, ',', '.') }}</span>

  </div>

  <div class="row">

    <a class="btn-large tooltipped col l4 s4 m4 offset-s8 offset-m8" data-position="top" data-delay="50" data-tooltip="Voltar a pagina inicial para continuar com o pedido?" href="{{ route('pedidos') }}">Continuar com pedido</a>
  </div>


@empty
<h5>Não Há nenhum pedido</h5>

@endforelse






</div>
</div>
</div>
</div>
@endsection







