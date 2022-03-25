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
      <h2><b>Novo Pedido</b></h2><hr>
    </div>
  </div>
  
  <ol class="breadcrumb">                         
    <li><a href="{{route('pedidos.index')}}">Pedidos</a></li>                  
    <li class="active">Cadastro</li>
  </ol>           
  

 <!-- RF10_Previsão_De_Desembolso_Do_Projeto -->
 <div class="col-md-12">
  <div class="row">
  <form action="{{route('pedidos.addProduct')}}" method="POST">
      {{ csrf_field() }}
    <div class="col-md-4">
      <!-- Textarea RF8_Decricao_Justificativa -->
      <div class="form-group">
        <div class="input-group">
          <span class="input-group-addon"><b>Observação</b></span>
          <textarea name="obs_pedido" class="form-control" placeholder="Observações gerais do pedido" title="Breve descrição das observações do pedidos" rows="1" maxlength="100" required="required"></textarea>
        </div>
      </div>   

        <input type="hidden" name="status" value="new">
      
    </div>

    <div class="col-md-2">
      <div class="input-group"> 
        <span class="input-group-addon"><b>PedidoNº</b></span>
        <input type="text" name="pedido_id" id="numero_pedido" placeholder="Numero do Pedido" class="numero_pedido" maxlength="5" title="Numero do pedido" onkeypress="mascara( this, mnum );" required="required">

         
            
      </div><br>
   
       <input name="user_id" type='hidden' value="1">
       <input name="cliente_id" type='hidden' value="1">
       <input name="vendedor_id" type='hidden' value="1">
       <input name="valor_total_pedido" type='hidden' value="10">
      <input name="prod_preco_padrao" type='hidden' value="10">
      <input name="grup_categ_cod" type='hidden' value="1">
      <!--<input name="prod_desc" type='hidden' value="descrição">-->
      <input name="prod_preco_prof" type='hidden' value="10">
      <input name="prod_preco_balcao" type='hidden' value="10">

     </div>

        <div class="col-md-3">
      <div class="input-group">
       <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i><b> Data do pedido</b></span>
       <input name="data_pedido" type='text' class="form-control" value='<?php echo date("d/m/Y H:i"); ?>' readonly>
     </div>
     </div>
</div>
   <hr>

<div class="col-md-4">  
    <span><b>Selecione o grupo do Produto</b></span>
     <select class="grup_desc" id="grup_cod" name="grup_cod" style="width: 400px;"><!--name="grup_categ_cod"-->
      <option value="0" disabled="true" selected="true">---Selecione o Grupo---</option>
      @foreach($dadosGroupsProduct as $dgp)
      
      <option value="{{$dgp->grup_cod}}">{{$dgp->grup_desc}}</option>
      @endforeach
      </select> <br><br>
    
 
  <!--<form method="POST" action="{{route('ProdutoPedido.search')}}" id="search"> (id="produtos")->tag select -->
   <!-- {{ csrf_field() }}  -->  

      <select class="prod_desc"  name="prod_desc" style="width: 400px;" id="">
        <option value="0" disabled="true" selected="true">---Selecione o Produto---</option>
        @foreach($products as $dp)        
        <option value="{{$dp->prod_desc}}">{{$dp->prod_desc}}</option>
        @endforeach    

      </select><br><br>
        
        <input type='text' name="prod_cod" value="23780" style="width: 200px;" placeholder="--Código do produto--" class="prod_cod" readonly="readonly">

        <input type="hidden" name="status" value="new">


     
    

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script type="text/javascript">
  
  $(document).ready(function(){
    $(document).on('change','.grup_desc',function(){
     //console.log("humm its change");
      var grup_cod=$(this).val();
     // console.log(cod_id);
     var div=$(this).parent();
var op=" ";

     $.ajax({
      type:'get',
      url:'{!!URL::to('findProductName') !!}',
      data:{'id':grup_cod},
      success:function(data){
     // console.log('success');
       // console.log(data);
        //console.log(data.length);
        op+='<option value="0" selected disabled>Escolha um produto</option>';
        for(var i=0;i<data.length;i++){
          op+='<option value="'+data[i].id+'">'+data[i].prod_desc+'</option>';
        }
        div.find('.prod_desc').html(" ");
        div.find('.prod_desc').append(op);
      },
      erro:function(){

      }

     });
    });

    $(document).on('change','.prod_desc',function(){
      var prod_id=$(this).val();
      var a=$(this).parent();
      console.log(prod_id);
      var op="";

           $.ajax({
      type:'get',
      url:'{!!URL::to('findProductCod') !!}',
      data:{'id':prod_id},
      dataType:'json',
      success:function(data){
        console.log("prod_cod");
        console.log(data.prod_cod);
        a.find('.prod_cod').val(data.prod_cod);

      },
      erro:function(){

      }

     });

    });


        $(document).on('change','.numero_pedido',function(){
      var numero_pedido=$(this).val();
      var b=$(this).parent();
      console.log(numero_pedido);
      var op="";

           $.ajax({
      type:'get',
      url:'{!!URL::to('findItensPedido') !!}',
      itenspedido:{'id':numero_pedido}, 
      //dataType:'json',     
      success:function(itenspedido){
        console.log("numero_pedido");
       console.log(itenspedido.numero_pedido);
        a.find('.numero_pedido').val(itenspedido.numero_pedido);

      },
      erro:function(){

      }

     });

    });

  });
</script>         
<button type="submit" class="btn btn btn-success btn-xs"><span class="glyphicon glyphicon-plus"></span><b>Adicionar</b></button>

</div>

 <div class="col-md-2">  
    <div class="input-group">
      <span class="input-group-addon"><b>Quantidade</b></span>
      <input type="text" name="quantidade" class="form-control" maxlength="10" title="Quantidade do produto" onkeypress="mascara( this, mnum );" required="required">  
    </div>
  </div>           



<div class="col-md-12">
  <div class="row">
     @if (Session::has('mensagem-sucesso'))
            <div class="card-panel green">
                <strong>{{ Session::get('mensagem-sucesso') }}</strong>
            </div>
        @endif
        @if (Session::has('mensagem-falha'))
            <div class="card-panel red">
                <strong>{{ Session::get('mensagem-falha') }}</strong>
            </div>
        @endif
   @forelse($pedidos as $pedido) 

  
    <h5><b>Pedido N° {{ $pedido->id}}</b></h5>
    <h5><b>Gerado em: {{  $pedido->created_at->format('d/m/y H:i') }}</b></h5>

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
        {{ $item_pedido->product->prod_cod}}
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
    $total_produto = $item_pedido->product->prod_preco_padrao;
    $total_pedido += $total_produto;

    @endphp

    <td  id="center">
     R$ {{ number_format($total_produto, 2, ',', '.') }}

   </td>
          <td title="Ações" id="center" > 
        <div class="center-align">

          <a class="col 14 m4 s4" href="#" 
          data-toggle="tooltip" 
          data-placement="top"
          title="Remover"><i class="material-icons small">remove_circle_outline</i>
        </a>
       
        <span class="col 14 m4 s4"> {{ $item_pedido->quantidade }}</span>

        <a class="col 14 m4 s4" href="#" 
        data-toggle="tooltip" 
        data-placement="top"
        title="Adicionar"><i class="material-icons small">add_circle_outline</i></a>
        

      </div>

      <a href="#" class="tooltipped" data-position="right" data-delay="50" data-toggle="Remover itens do pedido?"  title="Remover Itens">Remover Itens</a>

    </td>
   

 </tr>
 @endforeach
</tbody>    
</table>
</div>


  <h3><b>Total do Pedido: </b>R$ {{ number_format($total_pedido, 2, ',', '.') }}</h3>

    
    <!--<a class="btn-large tooltipped col l4 s4 m4 offset-s8 offset-m8" data-position="top" data-delay="50" data-tooltip="Voltar a pagina inicial para continuar com o pedido?" href="{{ route('pedidos.index') }}">Continuar com pedido</a>-->
  

</div>
</div>
@empty
<div class="col-md-12"><br>
<div class="row">
<div class="table-responsive">
    <table class="table table-striped table-bordered table-condensed table-hover" id="tbl" >
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
     </table>
   </div>

@endforelse







</div>
</div>
</div>
</div>
@endsection







