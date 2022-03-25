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
  <div class="row">
    <div class="col-md-12">   
      <h2><b>Pedidos Cadastrados</b></h2><hr>
  </div>
</div>
<div class="row">
            <div class="col-md-12">
<ol class="breadcrumb">

              <li><a href="{{route('index')}}" id="btn" style="text-decoration: none"><b>Novo Pedido</b></a></li>           
    <li class="active"><b>Pedidos</b></li>                  
    
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


    <div class="row">
    	<div class="col-md-12">
        <div class="col-md-3">
             <h4 title="Ultimos pedidos lançados em ordem decrescente" style="text-align: center; color:white; background-color: green; border-radius: 3px;">Últimos pedidos cadastrados</h4>
        </div>
        <div class="col-md-12">

           @forelse ($compras as $pedido)
            <h5 class="col l6 s12 m6"> Pedido: {{ $pedido->id }} </h5>
            <h5 class="col l6 s12 m6"> Criado em: {{ $pedido->created_at->format('d/m/Y H:i') }} </h5>
            <form method="POST" action="{{ route('carrinho.cancelar') }}">
                {{ csrf_field() }}
                <input type="hidden" name="pedido_id" value="{{ $pedido->id }}"/>


              
                  <table class="table table-striped table-bordered table-condensed table-hover" >
                    <thead>
                      <tr class="warning"> 

                        <th id="center">Código</th>
                        <th id="center">Cancelar</th>
                        <th id="center">Produto</th>
                        <th id="center">Valor</th>            
                        <th id="center">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $total_pedido = 0;
                    @endphp
                    @foreach ($pedido->pedido_produtos_itens as $pedido_produto)
                    @php
                    $total_produto =$pedido_produto->prod_preco_padrao;
                    $total_pedido += $total_produto;
                    @endphp
                    <tr>

                      <td id="center">
                          {{ $pedido->pedido_cod }}
                      </td>


                      <td id="center">
                        @if($pedido_produto->status == 'PA')
                        <p>
<label for="item-{{ $pedido_produto->id }}">
                            <input type="checkbox" id="item-{{ $pedido_produto->id }}" name="id[]" value="{{ $pedido_produto->id }}"/>
                          <span>Selecionar</span>
                       </label>
                        </p>
                        @else
                        <strong style="color:red;">CANCELADO</strong>
                        @endif
                    </td>

                    <td id="center">{{ $pedido_produto->product->prod_desc }}</td>
                    <td>R$ {{ number_format($pedido_produto->prod_preco_padrao, 2, ',', '.') }}</td>

                    <td>R$ {{ number_format($total_produto, 2, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3"></td>
                    <td><strong>Total do pedido</strong></td>
                    <td>R$ {{ number_format($total_pedido, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td colspan="2">
                        <button type="submit" class="btn btn-danger" data-position="top" data-delay="50" data-toggle="tooltip" data-placement="top" title="Cancelar itens selecionados">
                            Cancelar
                        </button>   
                    </td>
                    <td colspan="3"></td>
                </tr>
            </tfoot>
        </table>
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


<div class="row">
    <div class="col-md-12">
        <hr>
         <div class="col-md-3">
        <h4 style="text-align: center; color:white; background-color: red; border-radius: 3px;">Últimos pedidos cancelados.</h4>
    </div>
 <div class="col-md-12">
        @forelse ($cancelados as $pedido)
        <h5 class="col l6 s12 m6"> Pedido: {{ $pedido->id }} </h5>
        <h5 class="col l6 s12 m6"> Criado em: {{ $pedido->created_at->format('d/m/Y H:i') }} </h5>
        <h5 class="col l6 s12 m6"> Cancelado em: {{ $pedido->updated_at->format('d/m/Y H:i') }} </h5>
  
          <table class="table table-striped table-bordered table-condensed table-hover" >
            <thead>
              <tr class="warning"> 
                <th>Código</th>
                <th>Produto</th>
                <th>Valor</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @php
            $total_pedido = 0;
            @endphp
            @foreach ($pedido->pedido_produtos_itens as $pedido_produto)
            @php
            $total_produto = $pedido_produto->prod_preco_padrao;
            $total_pedido += $total_produto;

            @endphp
            <tr>
                <td>{{ $pedido_produto->product->prod_cod }}</td>

                <td>{{ $pedido_produto->product->prod_desc }}</td>
                <td>R$ {{ number_format($pedido_produto->prod_preco_padrao, 2, ',', '.') }}</td>


                <td>R$ {{ number_format($total_produto, 2, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2"></td>
                <td><strong>Total do pedido</strong></td>
                <td>R$ {{ number_format($total_pedido, 2, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

@empty
<div class="col-md-12">
<h5 id="center">Nenhum pedido foi cancelado ainda.</h5>
</div>
@endforelse
</div>
</div>
</div>
</div>
</div>
</div>

@endsection