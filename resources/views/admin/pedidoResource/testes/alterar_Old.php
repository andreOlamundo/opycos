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
      <h2><b>Alterar Pedido</b></h2>     
      <div class="row">
        <div class="col-md-12">
          <ol class="breadcrumb"> 
          <li><a href="{{route('index')}}" id="btn" style="text-decoration: none"><b>Novo Pedido</b></a></li>                        
            <li><a href="{{route('pedido.compras')}}"  id="btn" style="text-decoration: none"><b> Pedidos</b></a></li>                  
            <li class="active">Alterar</li>
          </ol>  

          <form method="post" 
          action="{{route('pedidos.update', $pedidos->id)}}" enctype="multipart/form-data">
          {!! method_field('put') !!}
          {{ csrf_field() }}
          <div class="card-panel">
           <div class="row">      
             <div class="col-md-4">
              <div class="input-field">        
                <select id="clientes" name="id_cliente">
                  <option></option>
                  <option value="{{$pedidos->Cliente->id}}">{{$pedidos->Cliente->cpf}} {{$pedidos->Cliente->cnpj}} &hybull; {{$pedidos->Cliente->name}} </option>
                 </select>
                 <label style="font-size: 15px; margin-top: -30px;">Escolha um cliente</label>
               </div>
               <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
               <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
               <script type="text/javascript">
                $("#clientes").select2({
                  placeholder:' {{$pedidos->Cliente->cpf}} {{$pedidos->Cliente->cnpj}} {{$pedidos->Cliente->name}}'
                });
              </script>
            </div>

            <div class="col-md-6">           
             <div class="input-field">              
              <input type="text" name="obs_pedido" class="form-control" value="{{$pedidos->obs_pedido}}" title="{{$pedidos->obs_pedido}}" required="required" autofocus/>
              <label for="obs_pedido" style="font-size: 15px;">Observações</label>
            </div>
          </div>

          <form method="POST" action="{{ route('carrinho.adicionar') }}">
            {{ csrf_field() }}

           
              <div class="col-md-5">  
                <div class="input-field">          
                  <select id="produtos" name="id" required="required">
                    @foreach($registros as $registro)
                    <option></option>
                    <option value="{{ $registro->id }}" >{{$registro->prod_desc}} &hybull; Codigo:{{$registro->prod_cod}} &hybull; R$ {{number_format($registro->prod_preco_padrao, 2,',','.')}}</option>
                      @endforeach     
                    </select>
                    <label style="font-size: 15px; margin-top: -30px;">Escolha um Produto</label>
                  </div>
                  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
                  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>      
                  <script type="text/javascript">
                    $("#produtos").select2({
                      placeholder:'---Selecione o Produto---'
                    });
                  </script>                 
                </div>

                <div class="col-md-5">
                  <button type="submit" class="btn waves-effect waves-light  blue darken-2" data-position="top" data-delay="50" data-toggle="tooltip" data-placement="bottom" title="Adicionar" style="margin-top: 10px">
                   <b>Adicionar</b>
                 </button>
               </div>

             </form>
          
         </div>
       </div>

         <div class="row"> 

          <p class="lead" id="black" style="margin-left: 15px;"> Pedido: {{ $pedidos->id }}
           Gerado em: {{ $pedidos->created_at->format('d/m/Y H:i') }} </p>
           <div class="col-md-12">

            <div class="table-responsive">
              <table class="table table-striped table-bordered table-condensed table-hover" >
                <thead>
                  <tr class="warning">                    
                   <th id="center">Código</th>
                   <th id="center">Produto</th>
                   <th id="center">Qtd</th>
                   <th id="center">Preço Padrão Unitário</th>
                   <th id="center">Total</th>
                   <th id="center" style=" width: 100px;" >Acão</th>
                 </tr>
               </thead> 
               <tbody> 
                @php
                $total_pedido =0;
                @endphp
                @foreach ($pedidos->itens_pedido as $item_pedido)
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

                </td>
              </tr>
@endforeach
 </tbody>    
</table>
</div>
<div class="divider"></div>
<h4>Total: R$ {{ number_format($total_pedido, 2, ',', '.') }}</h4>
<div class="divider" style="margin-top: -10px;"></div>
<br>
<form method="POST" action="{{ route('pedido.concluir') }}">
  {{ csrf_field() }}
  <input type="hidden" name="pedido_id" value="{{ $pedidos->id }}">
  <button type="submit" class="btn waves-effect waves-light  blue darken-2">
   <strong>Salvar</strong>
 </button>   
</form>


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


</div>
</div>
</div>

</div>
</div>

@endsection




