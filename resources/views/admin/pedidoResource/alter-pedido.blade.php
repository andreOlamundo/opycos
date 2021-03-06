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

      <div class="row" style="height: 80px; width: 1170px; position: fixed; background-color: white; z-index: 1001; top: 50px; margin-bottom: 80px;">
        <div class="col-md-12">
          <h2>Alteração do Pedido</h2> 
           <form method="POST" action="{{ route('alter.statusEdit', $pedidos->id) }}">
      {{ csrf_field() }} 
      <div class="col-md-3 pull-right" style="margin-top: -65px; margin-right: 290px; width: 150px; height: 25px; padding: 2px 1px;">
        <div class="input-field">
              <label for="status">Alterar Status do pedido
          <select onchange="submit()" name="status" class="form-control" style="height: 28px;" title="@if ($pedidos->status == 'AP') Aguardando Pagamento @elseif ($pedidos->status == 'EL') Encaminhado ao Lab. @elseif ($pedidos->status == 'CA') Cancelado @elseif ($pedidos->status == 'EC') Enviado @elseif ($pedidos->status == 'RE') Reservado @else ($pedidos->status == 'FI') Finalizado @endif">                                 


           <option title="@if ($pedidos->status == 'AP') Aguardando Pagamento @elseif ($pedidos->status == 'EL') Encaminhado ao Lab. @elseif ($pedidos->status == 'CA') Cancelado @elseif ($pedidos->status == 'EC') Enviado @elseif ($pedidos->status == 'RE') Reservado @else ($pedidos->status == 'FI') Finalizado @endif" style="background-color: #e0e2eb;" value="">@if ($pedidos->status == 'AP') Aguardando Pagamento @elseif ($pedidos->status == 'EL') Encaminhado ao Lab. @elseif ($pedidos->status == 'CA') Cancelado @elseif ($pedidos->status == 'EC') Enviado @elseif ($pedidos->status == 'RE') Reservado @else ($pedidos->status == 'FI') Finalizado @endif</option>

           @if ($pedidos->status == 'AP')

           @else
           <option value="AP" title="Aguardando Pagamento">Aguardando Pagamento</option>
           @endif

           @if ($pedidos->status == 'EL')

           @else

           <option value="EL" title="Pedido Encaminhado ao Laboratório">Encaminhado ao Lab.</option>
           @endif

           @if ($pedidos->status == 'CA')

           @else

           <option value="CA" title="Pedido Cancelado">Cancelado</option>
           @endif

           @if ($pedidos->status == 'EC')

           @else

           @if ($pedidos->Frete->status == 'AR')                 
           <option value="EC" disabled title="Pedido enviado ao cliente">Enviado</option>
           @else
           <option value="EC" title="Pedido enviado ao cliente">Enviado</option>
           @endif

           @endif

           @if ($pedidos->status == 'RE')

           @else

           <option value="RE" disabled title="Pedido Reservado">Reservado</option>
           @endif



           @if ($pedidos->status == 'FI')

           @else

           <option value="FI" title="Pedido Finalizado">Finalizado</option>
           @endif
           <!--<option value="GE" title="Pedidos Em Aberto">Em Aberto </option>-->

         </select>
       </label> 

       </div>
     </div>
   </form>

      
     <button  onclick="myFunction();" title="Outras informações do Pedido" class="btn btn-small waves-effect waves-light amber pull-right" style="margin-top: -29px; width: 280px; height: 27px; padding: 2px 1px;">
       <b>Outras informações do Pedido</b></button>  
       <script type="text/javascript">
        function myFunction() {
         document.getElementById("myCheck").click();
       }

     </script>



         
        </div>
      </div>	

      <div class="row" style="height: 80px; width: 1170px; position: fixed; z-index: 1001; top: 105px; ">
        <div class="col-md-12">         

					<!--<ol class="breadcrumb" style="margin-bottom: 5px;">
						<li><a href="{{route('pedido.compras')}}" id="btn" style="text-decoration: none"><b>Pedidos</b></a></li>
						<li><a href="{{route('index')}}" id="btn" style="text-decoration: none"><b>Novo Pedido</b></a></li>
						<li class="active">Detalhes</li>
					</ol>-->


					@if (Session::has('mensagem-sucesso'))
					<div class="alert alert-success alert-dismissible fade in" style="margin-bottom: 1px;">
						<strong>{{ Session::get('mensagem-sucesso') }}</strong>
						<a href="#" class="close" 
						data-dismiss="alert"
						aria-label="close">&times;</a>
					</div>
          <script type="text/javascript">
            $(".alert-dismissible").fadeTo(2000, 500).slideUp(500, function(){
              $(".alert-dismissible").alert('close');
            });
          </script>
          @endif
          @if (Session::has('mensagem-falha'))
          <div class="alert alert-danger alert-dismissible fade in" style="margin-bottom: 1px;">
            <strong>{{ Session::get('mensagem-falha') }}</strong>
            <a href="#" class="close" 
            data-dismiss="alert"
            aria-label="close">&times;</a>
          </div>
          <script type="text/javascript">
            $(".alert-dismissible").fadeTo(2000, 500).slideUp(500, function(){
              $(".alert-dismissible").alert('close');
            });
          </script>
          @endif

          <div class="card-panel" style="height: 200px; padding: 15px 10px;">    

           <div class="row" style="margin-bottom: -10px;">
            <form method="POST" action="{{ route('carrinho.adicionarEdit', $pedidos->id) }}">
             {{ csrf_field() }} 




             <div class="col-md-6"> 
              <div class="input-field" style="margin-top: -8px;">				               
                <section>      
                  <label style="font-size: 12px;">Cliente</label>
                  <select id="clientes" name="id_cliente" class="form-control id_clientesearch" title="{{$pedidos->Cliente->name}} &hybull; @if (isset($pedidos->Cliente->cnpj))cnpj:{{$pedidos->Cliente->cnpj}}@else cpf:{{$pedidos->Cliente->cpf}} @endif">       
                   <option></option>
                   <option value="{{$pedidos->Cliente->id}}" title="{{$pedidos->Cliente->name}} &hybull; @if (isset($pedidos->Cliente->cnpj))cnpj:{{$pedidos->Cliente->cnpj}}@else cpf:{{$pedidos->Cliente->cpf}} @endif">{{$pedidos->Cliente->id}} &hybull; {{$pedidos->Cliente->name}} &hybull; {{$pedidos->Cliente->cnpj}} {{$pedidos->Cliente->cpf}} &hybull; {{$pedidos->Cliente->celInput}}</option>        
                 </select>


                 <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
                 <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
                 <script type="text/javascript">
                  $("#clientes").select2({
                    placeholder:' {{$pedidos->Cliente->id}} - {{$pedidos->Cliente->name}} - {{$pedidos->Cliente->cpf}} {{$pedidos->Cliente->cnpj}} - {{$pedidos->Cliente->celInput}}'
                  });
                </script>
                <input type="hidden" name="id_cliente" value="{{$pedidos->Cliente->id}}">
                <!-- <input type="hidden" name="vendedor_id" value="{{$pedidos->Cliente->vendedor_id}}">-->


                <script type="text/javascript">

                  $(document).on('change','.id_clientesearch',function(){
                   //console.log("humm its change");
                   var id_cliente=$(this).val();

                   // console.log(cod_id);
                   var section=$(this).parent();
                   var op=" ";
                   //var status = 'FI';
                   $.ajax({
                    type:'get',
                    url:'{!!URL::to('findRequisitionsName') !!}',
                    data:{'id':id_cliente},                                     
                    success:function(data){
                                      //console.log('success');
                                       // console.log(data);
                                        //console.log(data.length);
                                        op+='<option value="0" selected disabled>Requisição:</option>';
                                        for(var i=0;i<data.length;i++){
                                          op+='<option value="'+data[i].id+'">'+data[i].request_cod+' - '+data[i].request_desc+' - R$ '+data[i].request_valor+'</option>';
                                        }

                                        section.find('.request_cod').html(" ");
                                        section.find('.request_cod').append(op);

                                      },
                                      erro:function(){

                                      }

                                    }); 
                 });
               </script>

               <label style="font-size: 12px; margin-top: 14px;">Requisições</label> 
               <select class="form-control request_cod"  name="request_cod" style=" position: relative; height: 30px;" id="">
                <option value="0" disabled="true" selected="true">---Selecione a Requisição ---</option>
                @foreach($list_requisitions as $request)        
                <option title="Status: {{ $request->status == 'CA' ? 'Cancelada:' : '' }} {{ $request->status == 'FI' ? 'Finalizada:' : '' }} {{ $request->status == 'AP' ? 'Aguardando Produção:' : '' }} {{ $request->status == 'RE' ? 'Atrelada ao pedido' : '' }} - {{$request->request_cod}} - {{ $request->request_desc }}" value="{{$request->id}}">{{$request->request_cod}} - {{$request->request_desc}} - R$ {{$request->request_valor}}  
                </option>     
                @endforeach    
              </select>  
            </section>
          </div>            


          

          <div class="input-field" style="margin-top: 30px;">          
            <select id="produtos" name="id"> <!--onchange="location = this.value;"-->
              @foreach($registros as $registro)
              <option></option>
              <option value="{{ $registro->id }}" title="Preço R$ {{number_format($registro->prod_preco_padrao, 2,',','.')}}">{{$registro->prod_cod}} &hybull; {{$registro->prod_desc}} &hybull; R$ {{number_format($registro->prod_preco_padrao, 2,',','.')}} 
              </option>
              @endforeach     
            </select>
            <label style="font-size: 12px; margin-top: -30px;">Produtos</label>
          </div>
          <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
          <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>      
          <script type="text/javascript">
            $("#produtos").select2({
              placeholder:'---Selecione o Produto---'
            });
          </script> 
        </div> 
        <div class="col-md-6">
         <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
         <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.js"></script>
         <script type="text/javascript">
          $(document).ready(function(){
            var maskBehavior = function (val) {
              return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
            },
            options = {onKeyPress: function(val, e, field, options) {
              field.mask(maskBehavior.apply({}, arguments), options);
            }
          };

          $('.phone').mask(maskBehavior, options);
          $('.money').mask('000.000.000.000.000,00', {reverse: true}).attr('maxlength','6'); 
          $('.cep').mask('00000-000');
          $('.percent').mask('000%', {reverse: true}).attr('maxlength','4');

        });
      </script>
      <div class="col">
        <div class="col-md-3">          
         <label style="font-size: 12px;">Pedido Número:
          <input type="text" readonly value="{{ $pedidos->id }}"> </label>
        </div>
        <div class="col-md-3">  	 
         <label style="font-size: 12px;">Data do pedido<input type="text" readonly value="{{ $pedidos->created_at->format('d/m/Y') }}"  title="{{ $pedidos->created_at->format('d/m/Y H:i') }}"> </label>      

       </div>
             @php
            
            
            $t_comissao = 0;
            $contador = 0;
            while($conte > $contador)
            {
            $v_comissao = 
            ($p_comissao[$contador]/100) * (($v_produto[$contador]) - ($d_produto[$contador]));
            $t_comissao += $v_comissao;
            $contador++;
            
            }
            $t_comissaoR = 0;
            $conta = 0;
            while($conteR > $conta)
            {
            $v_comissaoR = 
            ($r_comissao[$conta]/100) * (($v_request[$conta]) - ($d_request[$conta]));
            $t_comissaoR += $v_comissaoR;
            $conta++;
            
            }

           
          
          
          
            @endphp
       <div class="col-md-6"> 
       <label style="font-size: 12px;">Vendedor
         <input type="text" title="Vendedor" readonly value="{{$pedidos->Vendedor->name}}"></label>
         <input type="hidden" name="comissao" title="Vendedor" readonly value="{{$pedidos->Vendedor->comissao}}">
         
      </div>

       </div>
       <div class="col">
         <div class="col-md-3">
        <label style="font-size: 12px;">Desconto
          <input type="text" name="desconto_request_reais" onclick="AlterDescontoReqReais();" class="money" pattern='([0-9]{1,3}\.)?[0-9]{1,3},[0-9]{2}$' id="moneyRequest"  title="Desconto em Reais 'R$0,00'" placeholder="R$ 0,00"> </label> 
        </div>

    <div class="col-md-3">
      <label style="font-size: 12px;">Desconto
        <input type="text" name="desconto_request" onclick="AlterDescontoReqPercent();" class="percent" id="percentRequest"  title="Desconto de 1% à 100%" placeholder="0%"> </label> 
      </div>

          <div class="col-md-3">
            <label style="font-size: 12px;">Qtd
              <input type="number" placeholder="1" max="50" min="1" name="quantidade_request">
            </label>

          </div>

          <button type="submit" class="btn btn-small btn-primary float-right" style="margin-top: 20px; width: 120px; height: 25px; padding: 2px 1px;" data-position="top" data-delay="50" data-toggle="tooltip" data-placement="bottom" title="Adicionar Requisição"><i class="fa fa-plus"></i>
            Adicionar
          </button>  

        </div>

        <div class="col">
        <div class="col-md-3">
        <label style="font-size: 12px;">Desconto
          <input type="text" name="desconto_produto_reais" onclick="AlterDescontoProdReais();" class="money" pattern='([0-9]{1,3}\.)?[0-9]{1,3},[0-9]{2}$' id="moneyProd"  title="Desconto em Reais 'R$0,00'" placeholder="R$ 0,00"> </label> 
        </div>

          <div class="col-md-3">           
            <label style="font-size: 12px;">Desconto
              <input type="text" name="desconto_produto" onclick="AlterDescontoProdPercent();" class="percent" id="percentProd"  title="Desconto de 1% à 100%" placeholder="0%"> </label> 
            </div>

          <div class="col-md-3">
            <label style="font-size: 12px;">Qtd
              <input type="number" placeholder="1" max="50" min="1" name="quantidade_produto">
            </label>

          </div>

          <button type="submit" class="btn btn-small btn-primary float-right" style="margin-top: 20px; width: 120px; height: 25px; padding: 2px 1px;" data-position="top" data-delay="50" data-toggle="tooltip" data-placement="bottom" title="Adicionar Requisição"><i class="fa fa-plus"></i>
            Adicionar
          </button>  

        </div>
         <script type="text/javascript">
            function AlterDescontoProdReais()
            {
            $("#percentProd").val(null);
            }
            </script>

            <script type="text/javascript">
            function AlterDescontoProdPercent()
            {
            $("#moneyProd").val(null);
            }
            </script>

                       <script type="text/javascript">
            function AlterDescontoReqReais()
            {
            $("#percentRequest").val(null);
            }
            </script>

            <script type="text/javascript">
            function AlterDescontoReqPercent()
            {
            $("#moneyRequest").val(null);
            }
            </script>
      </div>
    </form>  


</div>

</div>

</div>
</div>

<div class="row" style="margin-top: 260px;">
  <div class="col-md-12"> 	
    @if (isset($itenspedidoP))
    <div class="table-responsive">
     <table class="table-sm table-bordered" style="width: 100%">
      <thead>
       <!--<tr style="color: #4db6ac;  background-color: #fcf8e3;"><th id="center" colspan="7"> PRODUTOS</th></tr>-->
       <tr style="background-color: #fcf8e3;">                    
        <th id="center">Código</th>
        <th id="center">Produto</th>
        <th id="center" title="Preço unitário">Uni</th>
         <th id="center">Desconto</th>
        <th id="center">Total</th>
        <th id="center">Comissão</th>
        <th id="center">Ações</th>
      </tr>
    </thead> 

    <tbody> 
     @php
     $total_pedido =0;
     @endphp

     @foreach ($pedidos->itens_pedido_uni as $item_pedido)
     <tr>  
    <form method="POST"  action="{{ route('desconto.pedido', $pedidos->id) }}">
     {{ csrf_field() }}

      <td>
      {{ $item_pedido->product->prod_cod }}
     </td> 
     <td>
       {{ $item_pedido->product->prod_desc}}
     </td>
 
    
    @php
    $desconto = $item_pedido->prod_desconto;
    if($desconto > 0)
    {
    $percentual_desconto = ($desconto/$item_pedido->prod_preco_padrao)*100;
    }
    else
    {
    $percentual_desconto = 0;
    }
   
    @endphp
     <td id="center">  R$ {{ number_format($item_pedido->prod_preco_padrao, 2, ',', '.')}}</td>

    
     <td id="center">  
      @if($desconto > 0) 
       <label>
      <input type="text" name="desconto_produto" onclick="AlterDescProdPercent();" id="percent" class="percent" title="{{ round($percentual_desconto)}}% de Desconto | Altere o Desconto em percentual de 1 à 100%" value="" size="4" placeholder="{{ round($percentual_desconto)}}%"></label>&nbsp;<b>ou</b>&nbsp;
    <label><input type="text" name="desconto_produto_reais" onclick="AlterDescProdReais();" class="money" pattern='([0-9]{1,3}\.)?[0-9]{1,3},[0-9]{2}$' id="money"  title="R${{ number_format($desconto, 2, ',', '.')}} de Desconto | Altere o desconto em R$" placeholder="{{ number_format($desconto, 2, ',', '.')}}" value="" size="4"></label>

      <input type="hidden" name="idproduto" value="{{$item_pedido->product->id}}">
      <input type="hidden" name="iditem" value="{{$item_pedido->id}}">
      <input type="hidden" name="idpedido" value="{{$pedidos->id}}">
      


      @else

      <label>
      <input type="text" name="desconto_produto" onclick="AlterDescProdPercent();" id="percent" class="percent" title="Altere o Desconto em percentual de 1 à 100%" placeholder="0%" value="" size="4"></label>&nbsp;<b>ou</b>&nbsp;
      <label>
       <input type="text" name="desconto_produto_reais" onclick="AlterDescProdReais();" class="money" pattern='([0-9]{1,3}\.)?[0-9]{1,3},[0-9]{2}$' id="money"  title="Altere o Desconto em R$" placeholder="R$0,00" value="" size="4"></label>
      <input type="hidden" name="idproduto" value="{{$item_pedido->product->id}}">
      <input type="hidden" name="iditem" value="{{$item_pedido->id}}">
      <input type="hidden" name="idpedido" value="{{$pedidos->id}}">    
                   

      @endif 

            <script type="text/javascript">
            function AlterDescProdReais()
            {
           $('input[name="desconto_produto"]').val(null);
           
            }
            </script>

            <script type="text/javascript">
            function AlterDescProdPercent()
            {
            $('input[name="desconto_produto_reais"]').val(null);
          
            }
            </script>
   

    </td> 
 
    @php
    $total = $item_pedido->prod_preco_padrao - $item_pedido->prod_desconto;
    @endphp
 
    <td id="center">R$ {{ number_format($total, 2, ',', '.')}}</td>
    <td id="center">{{$item_pedido->comissao}}%</td>  
    <td id="center">  
    <button type="submit" title="Aplicar desconto" style="padding: 0px 0px;" class="btn btn-link"><i class="material-icons">
money_off
</i>
    </button>&nbsp; 
 

       <!--<a href="#" onclick="carrinhoRemoverProduto({{ $pedidos->id}}, {{ $item_pedido->produto_id }}, 0)"  title="Remover Produto" class="btn btn-small waves-effect btn-Tiny" style="width: 20px; height: 20px; padding: 0px 0px;"><i class="material-icons">
remove_shopping_cart
</i></a>-->


<a href="#" onclick="carrinhoRemoverProduto({{ $pedidos->id}}, {{ $item_pedido->produto_id }}, 1)"      
      title="Subtrair" style="color: red; text-decoration: none;"><i class="fa fa-minus-square" aria-hidden="true"></i>
    </a> &nbsp; 

    <a href="#" onclick="carrinhoAdicionarProduto({{ $item_pedido->produto_id }})" 
      data-toggle="tooltip" 
      data-placement="top"
      title="Incluir"  style="color: blue; text-decoration: none;"><i class="fa fa-plus-square" aria-hidden="true"></i></a>&nbsp; 

     
      <a href="#" onclick="carrinhoRemoverProduto({{ $pedidos->id}}, {{ $item_pedido->produto_id }}, 0)"  title="Remover" style="color: red; text-decoration: none;"><i class="fa fa-ban" aria-hidden="true"></i></a>

  </td>
  </form>
  </tr>

  @endforeach
  </tbody>
   <tfoot>
  <tr>
    <th></th>
    <th></th>

    
        <th id="center">R$ {{ number_format($soma_produtos, 2, ',', '.')}}</th>
         <th id="center"><b style="color: red;">R$ {{ number_format($desconto_produtos, 2, ',', '.') }}</th>
     
       @php
              $total_pedido = $soma_produtos - $desconto_produtos;
              @endphp  
        <th id="center">R$ {{ number_format($total_pedido, 2, ',', '.') }}</th>

      <th id="center">R$ {{ number_format($t_comissao, 2, ',', '.')}}</td>
      <th></th>
    </tr>
  </tfoot>    
</table>
</div>
<br>
@else
@endif

@if (isset($itenspedidoR))
<div class="table-responsive">
	<table class="table-sm table-bordered" style="width: 100%">
		<thead>
			<tr style="background-color: #fcf8e3;">                    
				<th id="center">Código</th>
				<th id="center">Requisição</th>
				<th id="center" title="Preço unitário">Uni</th>
       <th id="center" title="Desconto unitário">Desconto</th>
       <th id="center">Total</th>
       <th id="center">Comissão</th>
       <th id="center">Ações</th>
     </tr>
   </thead> 
   <tbody> 
     @php
     $total_pedido =0;
     @endphp
     @foreach ($pedidos->itens_pedido_u as $item_pedido)
       <form method="POST" action="{{ route('desconto.pedido', $pedidos->id) }}">
     {{ csrf_field() }}
     <tr> 
      <td>
      {{ $item_pedido->request->request_cod}}
     </td> 
     <td>
       {{ $item_pedido->request->request_desc}}
     </td>
   
    
     <td id="center" >
    
        R${{ number_format($item_pedido->prod_preco_padrao, 2, ',', '.')}}
      
      </td> 
    
    @php
    $desconto = $item_pedido->request_desconto;
    if($desconto > 0)
    {
    $percentual_desconto = ($desconto/$item_pedido->prod_preco_padrao)*100;
    }
    else
    {
    $percentual_desconto = 0;
    }
   
    @endphp
   

    <td id="center"> 
   
      @if($desconto > 0) 
      
      <label>
      <input type="text" name="desconto_request" onclick="AlterDescReqPercent();"  id="percentReq" class="percent" title="{{ round($percentual_desconto)}}% de Desconto| Altere o desconto em percentual de 1 à 100%" value="" size="4" placeholder="{{ round($percentual_desconto)}}%"></label>&nbsp;<b>ou</b>&nbsp;
      <label>
      <input type="text" name="desconto_request_reais" onclick="AlterDescReqReais();" class="money" pattern='([0-9]{1,3}\.)?[0-9]{1,3},[0-9]{2}$' id="moneyReq"  title="R$ {{ number_format($item_pedido->request_desconto, 2, ',', '.')}} de Desconto | Altere o desconto em R$" size="4" value="" placeholder="R${{ number_format($item_pedido->request_desconto, 2, ',', '.')}}"></label>&nbsp;

      <input type="hidden" name="idrequest" value="{{$item_pedido->request->id}}">
      <input type="hidden" name="idpedido" value="{{$pedidos->id}}">

      @else

      <label>
      <input type="text" name="desconto_request" onclick="AlterDescReqPercent();" id="percentReq" class="percent" title="Altere o Desconto em percentual de 1 à 100%" value="" size="4" placeholder="0%"></label>&nbsp;<b>ou</b>&nbsp;
     <label>
       <input type="text" name="desconto_request_reais" onclick="AlterDescReqReais();" id="moneyReq" class="money" title="Altere o Desconto em R$" value="" size="4" placeholder="R$ 0,00"></label> &nbsp;
     </td>

      <input type="hidden" name="idrequest" value="{{$item_pedido->request->id}}">
      <input type="hidden" name="idpedido" value="{{$pedidos->id}}">
      @endif   

        <script type="text/javascript">
            function AlterDescReqReais()
            {
            $('input[name="desconto_request"]').val(null);
            
            }
            </script>

            <script type="text/javascript">
            function AlterDescReqPercent()
            {
            $('input[name="desconto_request_reais"]').val(null);
            }
            </script>     

    </td>


    @php
    $total = $item_pedido->prod_preco_padrao - $item_pedido->request_desconto;
    @endphp

    <td  id="center">
    R$ {{ number_format($total, 2, ',', '.')}}
   </td>
<td id="center">{{$item_pedido->comissao}}%</td>
   
    <td id="center"> 

    <button type="submit"  title="Aplicar desconto" class="btn btn-small waves-effect btn-default" style="width: 20px; height: 20px; padding: 0px 0px;"><i class="material-icons">
money_off
</i></button>

       <a href="#" onclick="carrinhoRemoverRequest({{ $pedidos->id}}, {{ $item_pedido->request->id }}, 0)"  title="Remover Requisição" class="btn btn-small waves-effect btn-Tiny" style="width: 20px; height: 20px; padding: 0px 0px;"><i class="material-icons">remove_shopping_cart</i></a>

</td>
</form>
  </tr>




  @endforeach
    </tbody>
   <tfoot>
  <tr>
    <th></th>
    <th></th>

    
        <th id="center">R$ {{ number_format($soma_request, 2, ',', '.')}}</th>
         <th id="center"><b style="color: red;">R$ {{ number_format($desconto_request, 2, ',', '.') }}</th>
     
       @php
              $total_pedido = $soma_request - $desconto_request;
              @endphp  
        <th id="center">R$ {{ number_format($total_pedido, 2, ',', '.') }}</th>

      <th id="center">R$ {{ number_format($t_comissaoR, 2, ',', '.')}}</td>
      <th></th>
    </tr>
  </tfoot>    
   
</table>
</div>
@else
@endif



@php
$total_geral =0;

@endphp

@foreach ($pedidos->itens_pedido as $item_pedido)
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
@php
$t_comissao = $t_comissao + $t_comissaoR;
@endphp

<div class="divider" style="margin-bottom: 5px;"></div>

<p id="black" class="lead">Total R$ {{ number_format($total_geral, 2, ',', '.') }}</p>

<!--| Comissão R$ {{ number_format($t_comissao, 2, ',', '.')}}-->

<div class="divider" style="margin-bottom: 10px; margin-top: -15px;"></div>




<a href="{{route('pedido.compras')}}" class="btn btn-default"><b>VOLTAR</b></a>
				@if (Session::has('message'))
				<script type="text/javascript">
					$(function() {
						$('#myCheck').click();
					});
				</script>
				<button type="button" style="display:none" id="myCheck" name="op1"  data-toggle="modal" data-target="#myModal2"></button> 
				@endif
				<button type="button" style="display:none" id="myCheck" name="op1"  data-toggle="modal" data-target="#myModal2"></button>  
			</div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="myModal2" role="dialog">
     <div class="modal-dialog">
      <form method="POST" action="{{route('pedidos.detalhesEdit', $pedidos->id)}}">
       {{ csrf_field() }} 
		      <input type="hidden" name="id_cliente" value="{{$pedidos->id_cliente}}">
           <input type="hidden" name="user_id" value="{{$pedidos->user_id}}">
           <input type="hidden" name="vendedor_id" value="{{$pedidos->vendedor_id}}">
           <input type="hidden" name="obs_pedido"  id="obspedidoDetalhes" value="{{$pedidos->obs_pedido}}">
           <!-- Modal content-->   

           <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h2 class="modal-title">Outras informações do Pedido</h2>

          </div>

          <div class="modal-body">

            @if (Session::has('message'))
            <div class="alert alert-danger alert-dismissible">
             <strong>{{ Session::get('message') }}</strong>
             <a href="#" class="close" 
             data-dismiss="alert"
             aria-label="close">&times;</a>
           </div>
           @endif
           <script src="https://code.jquery.com/jquery-3.2.1.min.js"
           integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
           crossorigin="anonymous"></script>

           <script type="text/javascript">                 

             $(document).ready(function() {

              function limpa_formulário_cep() {
                            // Limpa valores do formulário de cep.

                            $("#endereço").val("");
                            $("#bairro").val("");
                            $("#cidade").val("");
                            $("#estado").val(""); 
                            


                          }

                        //Quando o campo cep perde o foco.
                        $("#cep").blur(function() {

                            //Nova variável "cep" somente com dígitos.
                            var cep = $(this).val().replace(/\D/g, '');

                            //Verifica se campo cep possui valor informado.
                            if (cep != "") {

                                //Expressão regular para validar o CEP.
                                var validacep = /^[0-9]{8}$/;

                                //Valida o formato do CEP.
                                if(validacep.test(cep)) {

                                    //Preenche os campos com "..." enquanto consulta webservice.
                                    $("#endereço").val("Pesquisando...");
                                    $("#bairro").val("Pesquisando...");
                                    $("#cidade").val("Pesquisando...");
                                    $("#estado").val("Pesquisando...");


                                    //Consulta o webservice viacep.com.br/
                                    $.getJSON("https://viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {

                                    	if (!("erro" in dados)) {
                                            //Atualiza os campos com os valores da consulta.
                                            $("#endereço").val(dados.logradouro);
                                            $("#bairro").val(dados.bairro);
                                            $("#cidade").val(dados.localidade);
                                            $("#estado").val(dados.uf);                                
                                        } //end if.
                                        else {
                                            //CEP pesquisado não foi encontrado.
                                            limpa_formulário_cep();
                                            alert("CEP não encontrado.");
                                          }
                                        });
                                } //end if.
                                else {
                                    //cep é inválido.
                                    limpa_formulário_cep();
                                    alert("Formato de CEP inválido.");
                                  }
                            } //end if.
                            else {
                                //cep sem valor, limpa formulário.
                                limpa_formulário_cep();
                              }
                            });
                      });

                    </script>



                    <p style="color: #9e9e9e; font-size: 12px; margin-top: 5px;"><b>DETALHES DO FRETE</b></p> 

                    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script><!-- -->
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.js"></script>
                    <script type="text/javascript">
                      $(document).ready(function(){
                       var maskBehavior = function (val) {
                        return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
                      },
                      options = {onKeyPress: function(val, e, field, options) {
                        field.mask(maskBehavior.apply({}, arguments), options);
                      }
                    };

                    $('.phone').mask(maskBehavior, options);
                    $('.money').mask('000.000.000.000.000,00', {reverse: true}).attr('maxlength','6'); 
                    $('.cep').mask('00000-000');


                  });
                </script>

                <label for="balcao">
                	<input type="checkbox" name="balcao" id="balcao" value="Y" {{ $pedidos->Frete->balcao == 'Y' ? 'checked' : '' }} /><span style="font-size: 13px; margin-top: 2px;">Retirada Balcão</span>
                </label>  


                <div name="op2" style="display:none">


                	<label for="valor" id="valor_frete_label"  style="font-size: 13px;" class="col-md-4" >Custo Frete
                		<input type='text' name="valor" class="money" style="display:none;" value="{{ number_format($pedidos->Frete->valor, 2, ',', '.') }}"  id="valor_frete" maxlength="6" pattern='([0-9]{1,3}\.)?[0-9]{1,3},[0-9]{2}$' title="Informe o custo do frete R$ 0,00" placeholder="{{ number_format($pedidos->Frete->valor, 2, ',', '.')}}"></label>

                    <label for="prazo_entrega" id="prazo_entrega_label" style="font-size: 13px;" class="col-md-4" >Prazo de entrega
                      <input type='text' name="prazo_entrega" style="display:none;"  value="@if($pedidos->Frete->prazo_entrega != ''){{$pedidos->Frete->prazo_entrega}} Dias @else @endif" id="prazo_entrega"  title="Prazo de entrega" placeholder="nº Dias">
                    </label>


                    <label>
                      <input type="radio" name="entrega" class="with-gap" id="disable" value="B" {{ $pedidos->Frete->entrega == 'B' ? 'checked' : '' }} />
                      <span style="font-size: 13px;" >Entrega: MOTO BOY</span><!--color: #4dd0e1;  -->
                    </label>   
                    <label>
                      <input type="radio" name="entrega" class="with-gap"  id="enable" value="C" {{ $pedidos->Frete->entrega == 'C' ? 'checked' : '' }} />
                      <span style="font-size: 13px;">Entrega: CORREIOS</span><!--color: #ffd54f;  -->
                    </label> 

                    <button type="button" onclick="LoadFrete();" id="textbox" class="btn btn-small waves-effect amber pull-right" style="margin-top: 13px;"{{ $pedidos->Frete->entrega == 'C' ? 'enabled' : '' }} {{ $pedidos->Frete->entrega == 'B' ? 'disabled' : '' }}>Calcular frete</button>

                    <div class="col-md-2 pull-right">
                     <div class="input-field">   
                       <select name="cdservico" class="form-control" id="textbox1" title="Escolha o Serviço" style=" width: 170px;  float: right; height: 32px;" {{ $pedidos->Frete->entrega == 'C' ? 'enabled' : '' }} {{ $pedidos->Frete->entrega == 'B' ? 'disabled' : '' }}><!--onclick="calcularFrete();"-->

                         @if ($pedidos->Frete->serviço_correio != NULL)
                         <option value="{{$pedidos->Frete->serviço_correio == '4162' ? '4162' : ''}} {{$pedidos->Frete->serviço_correio == '04014' ? '4162' : ''}} {{$pedidos->Frete->serviço_correio == '4669' ? '4669' : ''}} {{$pedidos->Frete->serviço_correio == '04510' ? '4669' : ''}} {{$pedidos->Frete->serviço_correio == '04782' ? '04782' : ''}}{{$pedidos->Frete->serviço_correio == '04790' ? '04790' : ''}}{{$pedidos->Frete->serviço_correio == '04804' ? '04804' : ''}}
                           " style="background: #d7d7db;">

                           {{$pedidos->Frete->serviço_correio == '4162' ? 'SEDEX à vista' : ''}} 
                           {{$pedidos->Frete->serviço_correio == '04014' ? 'SEDEX à vista' : ''}}
                           {{$pedidos->Frete->serviço_correio == '4669' ? 'PAC à vista' : ''}} 
                           {{$pedidos->Frete->serviço_correio == '04510' ? 'PAC à vista' : ''}} 
                           {{$pedidos->Frete->serviço_correio == '04782' ? 'SEDEX 12 ( à vista)' : ''}}
                           {{$pedidos->Frete->serviço_correio == '04790' ? 'SEDEX 10 (à vista)' : ''}}
                           {{$pedidos->Frete->serviço_correio == '04804' ? 'SEDEX Hoje à vista' : ''}}

                         </option>
                         <option value="4162">SEDEX à vista</option>
                         <option  value="4669">PAC à vista</option>
                         <option  value="04782">SEDEX 12 ( à vista)</option>
                         <option  value="04790">SEDEX 10 (à vista)</option>
                         <option  value="04804">SEDEX Hoje à vista</option>
                         @else
                         <option value="" >Serviços Correios</option>
                         <option value="4162">SEDEX à vista</option>
                         <option  value="4669">PAC à vista</option>
                         <option  value="04782">SEDEX 12 ( à vista)</option>
                         <option  value="04790">SEDEX 10 (à vista)</option>
                         <option  value="04804">SEDEX Hoje à vista</option>
                         @endif




                       </select>







                     </div>

                     <input type="hidden" name="pedido_id_load" id="pedido_id_load" value="{{$pedidos->id}}">


                   </div>







                   <script type="text/javascript">

                    $('#balcao').click(function() {
                     $('input[name=entrega]').attr('checked', false);
                       
 //  document.getElementById('textbox')
 //       .setAttribute('disabled', 'disabled');  
  //      document.getElementById('textbox1')
  //      .setAttribute('disabled', 'disabled');
   //     document.getElementById('prazo_entrega')
    //    .setAttribute('disabled', 'disabled');
    // document.getElementById('local')
    //    .setAttribute('disabled', 'disabled');
    $('input[name=prazo_entrega]').css('display', 'none');
    $('label[id=prazo_entrega_label]').css('display', 'none');
    $('[id=valor_frete]').css('display', 'none');
    $('[id=valor_frete]').val("");

    $('label[id=valor_frete_label]').css('display', 'none');
    $('[id=textbox1]').css('display', 'none');
    $('[id=textbox]').css('display', 'none');
    $('[id=local]').css('display', 'none');
    $('label[id=local_label]').css('display', 'none');
    if($('input[name="balcao"]').prop('checked')){
     $("#balcao").val("Y");
					//console.log();
					 //var balc = $('#balcao').val();
					// console.log(balc);
					//$('[name="localisset"]').show();

				} else 

				{
					$("#balcao").val("");
				} 


      });

                    $('#enable').click(function() {
                     $('input[name=balcao]').attr('checked', false);
                     $('input[name=prazo_entrega]').css('display', 'block').attr('disabled', false);
                     $('label[id=prazo_entrega_label]').css('display', 'block');
                     $('[id=valor_frete]').css('display', 'block');
                     $('label[id=valor_frete_label]').css('display', 'block');
                     $('[id=local]').css('display', 'block');
                     $('label[id=local_label]').css('display', 'block');
                     $('[name="alterlocal"]').css('display', 'block');
                     $('[id=textbox1]').css('display', 'block');
                     $('[id=textbox]').css('display', 'block');
                     $('[name="balcaoempty"]').css('display', 'block');
                     document.getElementById('textbox')
                     .removeAttribute('disabled');  
                     document.getElementById('textbox1')
                     .removeAttribute('disabled');
                   });

                    $('#disable').click(function() {
                     $('input[name=balcao]').attr('checked', false);
                     $('[id=valor_frete]').css('display', 'block');
                     $('label[id=valor_frete_label]').css('display', 'block');
                     $('input[name=valor]').val("");
                     $('input[name=prazo_entrega]').val("").attr('disabled', true);
                     $('[name="balcaoempty"]').css('display', 'block');
                     $('[name="alterlocal"]').css('display', 'block');

                     $('[id=local]').css('display', 'block');
                     $('label[id=local_label]').css('display', 'block');
                     
                     
                     $('[id=textbox1]').css('display', 'block').attr('disabled', true);
                  $('[id=textbox]').css('display', 'block').attr('disabled', true);
                     if($('[id="disable"]').prop('checked')){
                       $("#disable").val("B");
					//console.log();
					 //var balc = $('#balcao').val();
					// console.log(balc);
					//$('[name="localisset"]').show();

				} else 

				{
					$("#disable").val("");
				} 

      });

/*
    $('#enable').click(function() {
   document.getElementById('textbox')
        .removeAttribute('disabled');  
        document.getElementById('textbox1')
        .removeAttribute('disabled');
        document.getElementById('prazo_entrega')
        .removeAttribute('disabled');
        document.getElementById('local')
        .removeAttribute('disabled');
   //return false;  
});

$('#disable').click(function() {
   document.getElementById('textbox')
        .setAttribute('disabled', 'disabled');  
        document.getElementById('textbox1')
        .setAttribute('disabled', 'disabled');
        document.getElementById('prazo_entrega')
        .setAttribute('disabled', 'disabled');
        document.getElementById('local')
        .removeAttribute('disabled');
  // return false; 
});*/



                /*			function myFunction() {
                				document.getElementById("myCheck").click();
                			}*/


                   // var a = document.getElementById('obspedido');

                   // var b = document.getElementById('obspedidoDetalhes');
                   // b.value = a.value;

                   

                 </script> 

                 <script type="text/javascript">



                  function LoadFrete(){
                    $("#valor_frete").val("Pesquisando...");
                    
                   //console.log("humm its change");               
                   var pedido_id_load = $('#pedido_id_load').val();
                   var cep_alter = $('.cep').val();
                   var cdservico_alt = $('#textbox1').val();
                   //var status = 'FI';
                   $.ajax({
                    type:'get',
                    url:'{!!URL::to('infoFrete') !!}',
                    datatype:'html',
                    cache: false,
                    data:{pedido_id_load:pedido_id_load, cep_alter:cep_alter, cdservico_alt:cdservico_alt},                                     
                    success:function(data){
                                      //console.log('success');
                                      console.log(pedido_id_load);
                                      console.log(cep_alter);
                                      console.log(cdservico_alt);
                                      $('#valor_frete').val(data);

                                        //console.log(data.length);                                   
                                      }, beforeSend: function(){


                                      },
                                      erro:function(){
                                        console.log('Erro!');
                                      }

                                    }); 
                 };


                 $(document).on('click','#textbox',function(){


                   $("#prazo_entrega").val("Pesquisando...");

                   var pedido_id_load = $('#pedido_id_load').val();
                   var cep_alter = $('.cep').val();
                   var cdservico_alt = $('#textbox1').val();

                   $.ajax({
                    type:'get',
                    url:'{!!URL::to('infoFretePrazoEntrega') !!}',
                    data:{pedido_id_load:pedido_id_load, cep_alter:cep_alter, cdservico_alt:cdservico_alt},
                    dataType:'html',
                    cache: false,
                    success:function(data){

                      $('#prazo_entrega').val(data);

                    }, beforeSend: function(){

		//$('#textbox').attr('checked', true);

  }, erro:function(){
    console.log('Erro!');
  }

});

                 });
               </script>

               <script type="text/javascript">

               		// var a = document.getElementById('obspedido');
                   // var b = document.getElementById('obspedidoDetalhes');
                   // b.value = a.value;    
                   //$('input[name=entrega]').hide(); 
                    //$('input[name=entrega]').hide(); 


                    if($('input[name="balcao"]').prop('checked')){
                $(function() 
                 {
                    	$('[name="alterlocal"]').css('display', 'none');
                    });


             /*   		$(function() 
                			{

                	$('[name="op2"]').hide(200);
                    $('[name="valor"]').hide(200);
                    $('[name="prazo_entrega"]').hide(200);                    
                    $('input[name=entrega]').attr('checked', false);
                    $('input[name=local]').attr('checked', false);
                    $('[name="local"]').hide(200);
                    $('[name=localisset]').hide();

                  });*/

                  $('[name="balcao"]').change(function() 
                  {

                    //$('[name="op1"]').toggle(200);
                    $('[name="op2"]').toggle(200);
                    $('[name="valor"]').toggle(200);
                    $('[name="prazo_entrega"]').toggle(200);  
                    $('[name="alterlocal"]').toggle(200);


                    //$('input[name=balcao]').attr('checked', false);
                   // $('input[name=local]').attr('checked', false);
                   
                  
                   $('[name="local"]').hide();
                   $('[name=localisset]').hide();

                   // $('[name="balcaoempty"]').toggle(200);



                   
                 });




                }
                else 
                {
                 $(function() 
                 {

                   $('[name="op2"]').show(200);
                   $('[name="valor"]').show(200);




                   if($('input[name="entrega"]').val() == 'B'){
                     $('[id="prazo_entrega_label"]').hide();
                     
                     $('[name="prazo_entrega"]').hide();

                     $('input[name=prazo_entrega]').attr('disabled', true);



                   } else ($('input[name="entrega"]').val() == 'C')
                   {
                     $('[id="prazo_entrega_label"]').toggle(200);
                     $('[name="prazo_entrega"]').toggle(200);

 					$('input[name=prazo_entrega]').attr('disabled', false);


                   }

                   $('[name="local"]').show(200);
                   $('[name=localisset]').hide();
                 });

                 




              		$('[name="balcao"]').change(function() 
                			{
                  $('[name="alterlocal"]').css('display', 'none');
                   // $('[name="balcaoempty"]').toggle(200);

                    

                   
                 });



              			         //$('[name="op1"]').toggle(200);
              			   //$('[name=localisset]').toggle(200);   
              		  //$('input[name=entrega]').attr('checked', false);
              		 //$('input[name=local]').attr('checked', false);              	         			     
                 	//$('[name="balcaoempty"]').toggle(200);	
                  	//$('input[name=entrega]').hide(); 






                  }


                  $('[name="cdservico"]').change(function() 
            {

              $('[id=textbox]').click();                      

            });  



                </script> 

                <script type="text/javascript">
                  $(document).ready(function(){
                   $(".obspedido").on("input", function(){
                    var textoDigitado = $(this).val();
                    var inputCusto = $(this).attr("obspedidoDetalhes");
                    $("#"+ inputCusto).val(textoDigitado);
                  });
                 });
               </script>


             </div><br>




             <div name="balcaoempty">                             
              <label for="local" name="local" id="local_label" style="display:none">
               <input type="checkbox" name="local" onclick="cepIsset();"  id="local"  value="Y" {{ $pedidos->Frete->local == 'Y' ? 'checked' : '' }}   /><span style="font-size: 13px;" title="Caso o endereço de cadastro seja diferente do endereço de entrega">Alterar Local da Entrega</span>
             </label>
            </div>

             <script type="text/javascript">
       		/*function myFunctionCorreios() {
					document.getElementById("textbox").click();
					document.getElementById("textbox1").click();
					//document.getElementById("local").click();
					//document.getElementById("cep").val("");
				}*/

			/* function resetPrazo(){	
				if($('input[name="balcao"]').prop('checked'))
			 	$("#balcao").val("Y");
      };*/

         if($('input[name="local"]').prop('checked')){
          $(function() 
                 {
                      $('[name="alterlocal"]').css('display', 'none');
                      $('[name="localisset"]').show();
                    });
        };

      function cepIsset(){
        if($('input[name="local"]').prop('checked')){
         $("#local").val("Y");
         $('[name="localisset"]').show();
         $('[name="alterlocal"]').hide();

       } else 

       {
         $('[name="localisset"]').hide();
         $("#local").val("");
         $('[name="alterlocal"]').show();
       } 




       $("#cep").val("");
      


       /*$("#cep").blur(function() {
        document.getElementById("textbox").click();
        document.getElementById("textbox1").click();

      });*/
     };

     function calcularFrete() {
       document.getElementById("textbox").click();

     }



   </script>

   <div name="localisset" style="display:none">

    <div class="input-field">
     <input type="text" class="cep" pattern="[0-9]{5}-[0-9]{3}" id="cep" value="" title="Informe o CEP. Consulta automática Ex:00000-000" maxlength="9" name="cep" placeholder="{{$pedidos->Frete->cep}}"/>
     <label for="cep" style="font-size: 15px;">Cep</label>
   </div>
   <div class="input-field">

     <input type="text" id="endereço" title="Informe o Endereço" maxlength="191" placeholder="Forneça o endereço" name="endereço"  value="{{$pedidos->Frete->endereço}}"/>
     <label for="endereço" style="font-size: 15px;">Endereço</label>
   </div>
   <div class="input-field">
     <input type="text" title="Informe o Número" maxlength="8" name="numero" value="{{$pedidos->Frete->numero}}" placeholder="Forneça o número" />
     <label for="numero" style="font-size: 15px;">Número</label>
   </div>
   <div class="input-field">
     <input type="text"  id="bairro" title="Informe o Bairro" placeholder="Forneça o Bairro" maxlength="191" name="bairro" value="{{$pedidos->Frete->bairro}}" />
     <label for="bairro" style="font-size: 15px;">Bairro</label>
   </div>
   <div class="input-field">
     <input type="text" title="Informe o Complemento" placeholder="Forneça o Complemento" maxlength="191" name="complemento"  value="{{$pedidos->Frete->complemento}}" />
     <label for="complemento" style="font-size: 15px;">Complemento</label>
   </div>

   <div class="input-field">
     <input type="text" id="cidade" title="Informe a Cidade" maxlength="191" name="cidade"  placeholder="Forneça o nome da Cidade"  value="{{$pedidos->Frete->cidade}}" />
     <label for="cidade" style="font-size: 15px;">Cidade</label> 
   </div>    


   <select name="estado" class="form-control" id="estado" title="Informe o Estado" style=" width: 200px;  float: left;" disabled="disabled">
     <option value="{{$pedidos->Frete->estado}}">{{$pedidos->Frete->estado}}</option>
     <option value="AC">AC</option>
     <option value="AL">AL</option>
     <option value="AP">AP</option>
     <option value="AM">AM</option>
     <option value="BA">BA</option>
     <option value="CE">CE</option>
     <option value="DF">DF</option>
     <option value="ES">ES</option>
     <option value="GO">GO</option>
     <option value="MA">MA</option>
     <option value="MT">MT</option>
     <option value="MS">MS</option>
     <option value="MG">MG</option>
     <option value="PA">PA</option>
     <option value="PB">PB</option>
     <option value="PR">PR</option>
     <option value="PE">PE</option>
     <option value="PI">PI</option>
     <option value="RJ">RJ</option>
     <option value="RN">RN</option>
     <option value="RS">RS</option>
     <option value="RO">RO</option>
     <option value="RR">RR</option>
     <option value="SC">SC</option>
     <option value="SP">SP</option>
     <option value="SE">SE</option>
     <option value="TO">TO</option>
   </select><br><br><br>

 </div>
     <div name="alterlocal" style="text-align: left; margin-bottom: 8px;">
        <label style="font-size: 13px;">Local de Entrega:</label><br> {{$pedidos->Cliente->endereço}}  {{$pedidos->Cliente->numero}}
        {{$pedidos->Cliente->bairro}} | compl:
        {{$pedidos->Cliente->complemento}}
        {{$pedidos->Cliente->cidade}}-{{$pedidos->Cliente->estado}} {{$pedidos->Cliente->cep}}    
       </div>

 <div class="divider"></div>
 <br>

 <p style="color: #9e9e9e; font-size: 12px; margin-left: 4px; margin-top: 5px;"><b>FORMAS DE PAGAMENTO</b></p>

 <label>
   <input type="radio" name="pagamento" value="CC" {{ $pedidos->pagamento == 'CC' ? 'checked' : '' }}>
   <span style="font-size: 12px;" >Cartão de Crédito</span>
 </label>
 <label>
   <input type="radio" name="pagamento" value="CD" {{ $pedidos->pagamento == 'CD' ? 'checked' : '' }}>
   <span style="font-size: 12px;" >Cartão de Débito</span>
 </label><br>
  <label>
   <input type='radio' name='pagamento' value="D" {{ $pedidos->pagamento == 'D' ? 'checked' : '' }}>
   <span style="font-size: 12px;">Dinheiro</span> <!--Dinheiro-->
 </label>
 <label>
   <input type="radio" name="pagamento" value="BB" {{ $pedidos->pagamento == 'BB' ? 'checked' : '' }}>
   <span style="font-size: 12px;" >Boleto Bancário</span>                         
 </label>
 <label>
   <input type="radio" name="pagamento" value="DB" {{ $pedidos->pagamento == 'DB' ? 'checked' : '' }}>
   <span style="font-size: 12px;" >Depósito Bancário</span>                         
 </label>
 <div class="divider"></div>
 <br>
 <p style="color: #9e9e9e; font-size: 12px; margin-left: 4px; margin-top: 5px; margin-bottom: 2px;"><b>OBSERVAÇÕES</b></p>         
 <input type="text" name="obs_pedido" style="margin-left: 4px;" class="obspedido" title="Informe as observações gerais do pedido" value="" placeholder="{{$pedidos->obs_pedido}}"  />



</div>


<div class="modal-footer">

  <button type="button" class="btn btn-default" data-dismiss="modal" >Voltar</button> 
  <button type="submit"  class="btn btn-medium waves-effect waves-light  blue darken-2" style="margin-top: 1px;"><span class="glyphicon glyphicon-floppy-disk"></span><b> Salvar</b></button>     


</div>

</form>



</div> 
</div>



</div>


</div>
</div>
</div>


</div>


<form id="form-remover-produto" method="POST" action="{{ route('carrinho.removerEdit', $pedidos->id) }}">
	{{ csrf_field() }}
	{{ method_field('DELETE') }}
	{!! method_field('put') !!}
	<input type="hidden" name="pedido_id">
	<input type="hidden" name="produto_id">
	<input type="hidden" name="request_cod">
 	<input type="hidden" name="item">
</form>



<form id="form-adicionar-produto" method="POST" action="{{ route('carrinho.adicionarEdit', $pedidos->id) }}">
	{{ csrf_field() }}
	<input type="hidden" name="id">
	<input type="hidden" name="request_cod">
	<input type="hidden" name="obs_pedido" value="{{$pedidos->obs_pedido}}">
	<input type="hidden" name="id_cliente" value="{{$pedidos->id_cliente}}">

  <input type="hidden" name="vendedor_id" value="{{$pedidos->vendedor_id}}">
  <input type="hidden" name="comissao" value="{{$pedidos->Vendedor->comissao}}">
</form>



@push('scripts')
<script type="text/javascript" src="/js/carrinho.js"></script>
@endpush

@endsection