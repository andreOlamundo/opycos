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
  <h2><b>Novo Pedido</b></h2> 
   <div class="row">
    <div class="col-md-12">
      <ol class="breadcrumb" style="margin-bottom: 10px;">                         
                      
    <li class="active"><b>Novo Pedido</b></li>
    <li><a href="{{route('pedido.compras')}}" id="btn" style="text-decoration: none"><b>Pedidos</b></a></li><!--{{route('index')}}-->
  </ol>   

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

<form method="POST" action="{{ route('carrinho.adicionar') }}">
                {{ csrf_field() }}
  <!-- RF10_Previsão_De_Desembolso_Do_Projeto-->
  <div class="card-panel">

  <div class="row">
  

      <div class="col-md-4">


     @forelse ($pedidos as $pedido)
                         <div class="input-field">
        
      <select id="clientes" name="id_cliente">
       
        <option></option>
        <option value="{{$pedido->Cliente->id}}">
         {{$pedido->Cliente->cpf}} {{$pedido->Cliente->cnpj}} &hybull;
         {{$pedido->Cliente->name}} </option>
        
      </select>
      <label style="font-size: 15px; margin-top: -30px;">Escolha um cliente</label>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
         <script type="text/javascript">
          $("#clientes").select2({
            placeholder:' {{$pedido->Cliente->cpf}} {{$pedido->Cliente->cnpj}} {{$pedido->Cliente->name}}'
          });
        </script>
              @empty
                    <div class="input-field">
        
      <select id="clientes" name="id_cliente" required="required">
        @foreach($dadosClientes as $dc)
        <option></option>
        <option value="{{$dc->id}}">
         {{$dc->cpf}} {{$dc->cnpj}} &hybull;
         {{$dc->name}} </option>
        @endforeach     
      </select>
      <label style="font-size: 15px; margin-top: -30px;">Escolha um cliente</label>
    </div>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
      <script type="text/javascript">
          $("#clientes").select2({
            placeholder:' ---Selecione o cliente--- '
          });
        </script>
        @endforelse
</div>

 @forelse ($pedidos as $pedido)
      <div class="col-md-2">
       
      <div class="input-field">        
        <input type="text" class="form-control" name="pedido_cod" value="{{$pedido->pedido_cod}}" maxlength="5" title="Cupom de descontos" onkeypress="mascara( this, mnum );" >       
            <label for="pedido_cod" style="font-size: 15px;">Código Promocional</label>
      </div>
</div>
         <div class="col-md-6">           
           <div class="input-field">              
          <input type="text" name="obs_pedido" class="form-control" value="{{$pedido->obs_pedido}}" title="Observações gerais do pedido" required="required" autofocus/>
      <label for="obs_pedido" style="font-size: 15px;">Observações</label>
      </div>
     </div>
       @empty
       <div class="col-md-2">
             <div class="input-field">        
        <input type="text" class="form-control" name="pedido_cod" maxlength="5" title="Cupom de descontos" onkeypress="mascara( this, mnum );">       
            <label for="pedido_cod" style="font-size: 15px;">Código Promocional</label>
      </div>
                </div>

         <div class="col-md-6">           
           <div class="input-field">              
          <input type="text" name="obs_pedido" class="form-control" title="Observações gerais do pedido" required="required" autofocus/>
      <label for="obs_pedido" style="font-size: 15px;">Observações</label>
      </div>
     </div> 

      @endforelse
</div>

<div class="row">

    
      <div class="col-md-5">  
        <div class="input-field">          
          <select id="produtos" name="id" required="required"> <!--onchange="location = this.value;"-->
            @foreach($registros as $registro)
            <option></option>
            <option value="{{ $registro->id }}" >{{$registro->prod_desc}} &hybull; Codigo:{{$registro->prod_cod}} &hybull; 
            R$ {{number_format($registro->prod_preco_padrao, 2,',','.')}}</option>
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
    @forelse ($pedidos as $pedido)
    <p class="lead" id="black" style="margin-left: 15px;"> Pedido: {{ $pedido->id }}
     Gerado em: {{ $pedido->created_at->format('d/m/Y H:i') }} </p>
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
    

          <a href="#" onclick="carrinhoRemoverProduto({{ $pedido->id}}, {{ $item_pedido->produto_id }}, 1)"
            data-toggle="tooltip" 
            data-placement="top"
            title="Remover" class="btn-floating btn-small waves-effect waves-light red"><i class="material-icons">remove_circle_outline</i>
          </a> &nbsp; 

         

          <a href="#" onclick="carrinhoAdicionarProduto({{ $item_pedido->produto_id }})" 
            data-toggle="tooltip" 
            data-placement="top"
            title="Adicionar" class="btn-floating btn-small waves-effect waves-light btn-primary"><i class="material-icons">add_circle_outline</i></a>
            
    

          <a href="#" onclick="carrinhoRemoverProduto({{ $pedido->id}}, {{ $item_pedido->produto_id }}, 0)" class="waves-effect btn-Tiny secondary-content">Remover Item</a>

        </td>
 </tr>
    


      
      @endforeach

          <!--   <tr class="warning">

     <td colspan="6" valign="top">
      <strong class="col offset-l6 offset-m6 offset-s6 l4 m4 s4 right-align"><b >Total do pedido: </b></strong>
      <span class="col l2 m2 s2">R$ {{ number_format($total_pedido, 2, ',', '.') }}</span>
     </td>
   </tr>-->
    </tbody>    
  </table>
</div>
<div class="divider"></div>
  <h4>Total: R$ {{ number_format($total_pedido, 2, ',', '.') }}</h4>
<div class="divider" style="margin-top: -10px;"></div>
<br>
  <form method="POST" action="{{ route('pedido.concluir') }}">
    {{ csrf_field() }}
    <input type="hidden" name="pedido_id" value="{{ $pedido->id }}">
    <button type="submit" class="btn waves-effect waves-light  blue darken-2">
     <strong>Salvar</strong>
    </button>   
  </form>


@empty
<p class="lead" id="black" style="margin-left: 10px;">Não há nenhum produto adicionado</p>
@endforelse

</div>
</div>


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