@extends('templates.vendedor-login')

@section('css-view')
@endsection

@section('js-view')
@endsection

@section('templates.menu-superior-vendedor')
@endsection

@section('conteudo-view')  
<div id="line-one">
  <div id="line-one">
    <div class="container">
      <h2>Novo Pedido</h2> 
      
      <div class="divider" style="margin-bottom: 3px; margin-top: -8px;" ></div>

      <div class="row">
        <div class="col-md-12">  

          <ol class="breadcrumb" style="margin-bottom: 5px;">                       
            <li><a href="{{route('pedidointer.compras')}}" id="btn" style="text-decoration: none"><b>Pedidos</b></a></li>
            <li class="active"><b>Novo Pedido</b></li>
          </ol> 
          

          @if (Session::has('mensagem-sucesso'))
          <div class="alert alert-success alert-dismissible fade in" style="margin-bottom: 1px;">
            <strong>{{ Session::get('mensagem-sucesso') }}</strong>
            <a href="#" class="close" 
            data-dismiss="alert"
            aria-label="close">&times;</a>
          </div>
          @endif
          @if (Session::has('mensagem-falha'))
          <div class="alert alert-danger alert-dismissible fade in" style="margin-bottom: 1px;">
            <strong>{{ Session::get('mensagem-falha') }}</strong>
            <a href="#" class="close" 
            data-dismiss="alert"
            aria-label="close">&times;</a>
          </div>
          @endif



          <div class="card-panel">
           @forelse ($pedidos as $pedido)
           <p class="lead" id="black" style="color: #4db6ac; margin-bottom: 5px; margin-top: -10px;">Pedido: <span class="chip" style=" margin-bottom: 5px; margin-top: -10px;">{{ $pedido->id }}</span>Data: {{ $pedido->created_at->format('d/m/Y') }}</p>
           <!--<div class="divider" style="margin-top: 5px;"></div>-->
           @empty
           @endforelse                  
           <div class="row" style="margin-bottom: -10px;">
            <form method="POST" action="{{ route('carrinhointer.adicionar') }}">
              {{ csrf_field() }}
              @forelse ($pedidos as $pedido)   

              <div class="col-md-5">               
                <section style="margin-top: -12px;">      
                  <label style="font-size: 12px; margin-top: -10px;">CLIENTE</label>
                  <select id="clientes" name="id_cliente" class="form-control id_clientesearch" >       
                   <option></option>
                   <option value="{{$pedido->Cliente->id}}" title="doc:{{$pedido->Cliente->cnpj}}{{$pedido->Cliente->cpf}}">{{$pedido->Cliente->id}}. {{$pedido->Cliente->name}}. cel:{{$pedido->Cliente->celInput}}</option>        
                 </select>


                 <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
                 <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
                 <script type="text/javascript">
                  $("#clientes").select2({
                    placeholder:' {{$pedido->Cliente->cpf}} {{$pedido->Cliente->cnpj}} {{$pedido->Cliente->name}}'
                  });
                </script>
                <input type="hidden" name="id_cliente" value="{{$pedido->Cliente->id}}">


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
                    url:'{!!URL::to('findRequisitionsNameInt') !!}',
                    data:{'id':id_cliente},                                     
                    success:function(data){
                                      //console.log('success');
                                       // console.log(data);
                                        //console.log(data.length);
                                        op+='<option value="0" selected disabled>Requisição:</option>';
                                        for(var i=0;i<data.length;i++){
                                          op+='<option value="'+data[i].id+'">'+data[i].request_cod+' '+data[i].request_desc+'</option>';
                                        }

                                        section.find('.request_cod').html(" ");
                                        section.find('.request_cod').append(op);

                                      },
                                      erro:function(){

                                      }

                                    }); 
                 });
               </script>

               <label style="font-size: 12px; margin-top: 20px;">REQUISIÇÕES</label> 
               <select class="form-control request_cod"  name="request_cod" style=" position: relative;" id="">
                <option value="0" disabled="true" selected="true">---Selecione a Requisição ---</option>
                @foreach($list_requisitions as $request)        
                <option title="{{ $request->request_desc }}" value="{{$request->id}}">{{ $request->status == 'CA' ? 'CANCELADA:' : '' }} {{ $request->status == 'FI' ? 'FINALIZADA:' : '' }} {{ $request->status == 'AP' ? 'AGUARDANDO PRODUÇÃO:' : '' }} {{ $request->status == 'RE' ? 'Atrelada ao pedido' : '' }} {{$request->request_cod}} {{$request->request_desc}} 
                </option>     
                @endforeach    
              </select>  
            </section>
            
          </div>



          <div class="col-md-4"><br>
            <div class="input-field">          
              <select id="produtos" name="id"> <!--onchange="location = this.value;"-->
                @foreach($registros as $registro)
                <option></option>
                <option value="{{ $registro->id }}" title="PreçoPadrão R$: {{number_format($registro->prod_preco_padrao, 2,',','.')}}">{{$registro->prod_cod}} &hybull; {{$registro->prod_desc}}   
                </option>
                @endforeach     
              </select>
              <label style="font-size: 12px; margin-top: -30px;">PRODUTOS</label>
            </div>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>      
            <script type="text/javascript">
              $("#produtos").select2({
                placeholder:'---Selecione o Produto---'
              });
            </script>  
            <div class="col-sm-3">
              <div class="input-field"> 
                <label style="font-size: 15px; margin-top: 5px;">Qtd</label>
                <input type="number" placeholder="1" max="50" name="quantidade">
              </div>
            </div>

            <button type="submit" class="btn btn-small waves-effect waves-light  blue darken-2" data-position="top" data-delay="50" data-toggle="tooltip" data-placement="bottom" title="Adicionar">
             <b>Adicionar</b>
           </button>  

         </div>



         @empty

         <div class="col-md-5">
          <div class="input-field" style="margin-top: -8px;">  
            <section>
              <label style="font-size: 12px; ">ESCOLHA UM CLIENTE</label> 
              <select id="id_cliente" class="form-control id_clientesearch"  name="id_cliente" required="required" >   
                <option value="0" disabled="true" selected="true">---Selecione o cliente---</option>           
                @foreach($dadosClientes as $dc)

                <option value="{{$dc->id}}" title="doc:{{$dc->cnpj}}{{$dc->cpf}}">{{$dc->id}}. {{$dc->name}}. Cel:{{$dc->celInput}}  Doc.{{$dc->cnpj}}{{$dc->cpf}} </option>
                @endforeach     
              </select>      
              <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
              <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
              <script type="text/javascript">
                $("#id_cliente").select2({
                  placeholder:' {{ $dc->cpf}} {{$dc->cnpj}} {{$dc->name}}'
                });
                $(document).on('change','.id_clientesearch',function(){
                   //console.log("humm its change");
                   var id_cliente=$(this).val();


                   // console.log(cod_id);
                   var section=$(this).parent();
                   var op=" ";
                   //var status = 'FI';
                   $.ajax({
                    type:'get',
                    url:'{!!URL::to('findRequisitionsNameInt') !!}',
                    data:{'id':id_cliente},                                     
                    success:function(data){
                                      //console.log('success');
                                       // console.log(data);
                                        //console.log(data.length);
                                        op+='<option value="0" selected disabled>Requisição:</option>';
                                        for(var i=0;i<data.length;i++){
                                          op+='<option value="'+data[i].id+'">'+data[i].request_cod+' '+data[i].request_desc+'</option>';
                                        }

                                        section.find('.request_cod').html(" ");
                                        section.find('.request_cod').append(op);

                                      },
                                      erro:function(){

                                      }

                                    }); 
                 });
               </script>

               <label style="font-size: 12px; margin-top: 20px;">REQUISIÇÕES</label> 
               <select class="form-control request_cod"  name="request_cod" style=" position: relative;" id="">
                <option value="0" disabled="true" selected="true">---Selecione a Requisição ---</option>
                @foreach($list_requisitions as $request)        
                <option title="{{$request->request_desc}}" value="{{$request->id}}">{{ $request->status == 'CA' ? 'CANCELADA:' : '' }} {{ $request->status == 'FI' ? 'FINALIZADA:' : '' }} {{ $request->status == 'AP' ? 'AGUARDANDO PRODUÇÃO:' : '' }} {{ $request->status == 'RE' ? 'Atrelada ao pedido' : '' }} {{$request->request_cod}} {{$request->request_desc}} </option>     
                @endforeach    
              </select>    
            </section>

          </div>
        </div>

        <br>
        <div class="col-md-5">  
          <div class="input-field">          
            <select id="produtos" name="id"> <!--onchange="location = this.value;"-->
              @foreach($registros as $registro)
              <option></option>
              <option value="{{ $registro->id }}" title="PreçoPadrão R$: {{number_format($registro->prod_preco_padrao, 2,',','.')}}">{{$registro->prod_cod}} &hybull; {{$registro->prod_desc}}   
              </option>
              @endforeach     
            </select>
            <label style="font-size: 12px; margin-top: -30px;">PRODUTOS</label>
          </div>
          <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
          <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>      
          <script type="text/javascript">
            $("#produtos").select2({
              placeholder:'---Selecione o Produto---'
            });
          </script>  

          <div class="col-md-3 col-sm-3">
            <div class="input-field"> 
             <label style="font-size: 15px; margin-top: 5px;">Qtd</label>
             <input type="number" placeholder="1" max="50" name="quantidade">
           </div>
         </div>




         <button type="submit" class="btn btn-small waves-effect waves-light  blue darken-2" data-position="top" data-delay="50" data-toggle="tooltip" data-placement="bottom" title="Adicionar">
           <b>Adicionar</b>
         </button>  
       </div> 

       @endforelse

       <div class="col-md-12"><div class="divider" style="margin-top: 5px;"></div><br></div>

       @forelse ($pedidos as $pedido)          

       <div class="col-md-6">         
         <div class="input-field">              
           <input type="text" name="obs_pedido" class="obspedido" obspedidoDetalhes='obspedidoDetalhes' title="Informe as observações gerais do pedido" placeholder="Observações gerais do pedido." value="{{$pedido->obs_pedido}}" />
           <label for="obs_pedido" style="font-size: 15px;">OBSERVAÇÕES</label>
         </div> 
       </div>

     </div>

   </div>


   @empty
       <!--<div class="col-md-2">
             <div class="input-field">        
        <input type="text" class="form-control" name="pedido_cod" maxlength="5" title="Cupom de descontos" onkeypress="mascara( this, mnum );">       
            <label for="pedido_cod" style="font-size: 15px;">Código Promocional</label>
      </div>
    </div>-->


    <div class="col-md-9">           
     <div class="input-field">              
      <input type="text" name="obs_pedido" title="Informe as observações gerais do pedido" value="{{ old('obs_pedido') }}" placeholder="Observações gerais do pedido" maxlength="255" />
      <label for="obs_pedido" style="font-size: 15px;">OBSERVAÇÕES</label>
      <input type="hidden" name="boolean" value="Y"/>
    </div>
  </div>


  @endforelse
</form>








</div>
</div>

@forelse ($pedidos as $pedido)

@empty

<p class="lead" id="black" style="margin-bottom: -2px; margin-top: 5px; color: #4db6ac;">Não há nenhum item no pedido.</p>
@endforelse


@forelse ($pedidos_produto as $pedido)





<div class="table-responsive" style="margin-top: -15px;">
  <table class="table table-striped table-bordered table-condensed table-hover" >

    <thead>
      <tr style="color: #4db6ac; background-color: #f5f5f5;"><th id="center" colspan="6"> PRODUTOS</th></tr>
      <tr class="warning">                    
       <th id="center">Código</th>
       <th id="center">Produto</th>
       <th id="center">Qtd</th>              
       <th id="center" title="Preço PADRÃO">Preço Unitário</th>
       <th id="center">Total</th>
       <th id="center" style=" width: 100px;" >Acão</th>

       <!--<th id="center" title="Preço Profissionais Unitário">Profissionais Preço Unitário</th>-->

     </tr>
   </thead> 

   <tbody> 


    @php
    $total_pedido =0;
    @endphp


    


    @foreach ($pedido->itens_pedido as $item_pedido)
    <tr> 

     <td id="center">
      <span class="chip"> {{ $item_pedido->product->prod_cod }}</span>
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


    <a href="#" onclick="carrinhointerRemoverProduto({{ $pedido->id}}, {{ $item_pedido->produto_id }}, 1)"
      data-toggle="tooltip" 
      data-placement="top"
      title="Remover" class="btn-floating btn-small waves-effect waves-light red"><i class="material-icons">remove_circle_outline</i>
    </a> &nbsp; 



    <a href="#" onclick="carrinhointerAdicionarProduto({{ $item_pedido->produto_id }})" 
      data-toggle="tooltip" 
      data-placement="top"
      title="Adicionar" class="btn-floating btn-small waves-effect waves-light btn-primary"><i class="material-icons">add_circle_outline</i></a>



      <a href="#" onclick="carrinhointerRemoverProduto({{ $pedido->id}}, {{ $item_pedido->produto_id }}, 0)" class="waves-effect btn-Tiny secondary-content">Remover Item</a>

    </td>
  </tr>





  @endforeach

  <tr><td colspan="7"><b>Total: R$ {{ number_format($total_pedido, 2, ',', '.') }}</b></td></tr>




</tbody>    
</table>
</div>



<!--<form method="POST" action="{{ route('pedido.concluir') }}">
  {{ csrf_field() }}
  <input type="hidden" name="pedido_id" value="{{ $pedido->id }}">
  <input type="hidden" name="vendedor_id" value="{{$pedido->Cliente->vendedor_id}}">
  <input type="hidden" name="pagamentoIsset" id="pagamentoIsset">

  <button type="submit" class="btn waves-effect waves-light  blue darken-2">
   <strong>Salvar</strong>   
   <script type="text/javascript">


     $(document).ready(function(){
      $(".pagamento").on("input", function(){
        var textoDigitado = $(this).val();
        var inputCusto = $(this).attr("pagamentoIsset");
        $("#"+ inputCusto).val(textoDigitado);
      });
    });



//var a = document.getElementById('pagamento');
//var b = document.getElementById('pagamentoIsset');

//b.value = a.value;




</script>
</button>   
</form>-->



@empty




@endforelse


@forelse ($pedidos_request as $pedido)  


<div class="table-responsive">
  <table class="table table-striped table-bordered table-condensed table-hover" >

    <thead>
      <tr><th id="center" colspan="6" style="color: #4db6ac; background-color: #f5f5f5;"> REQUISIÇÕES</th></tr>
      <tr class="warning">                    
       <th id="center">Código</th>
       <th id="center">Requisição</th>
       <th id="center">Qtd</th>              
       <th id="center" title="Preço PADRÃO">Preço Unitário</th>
       <th id="center">Total</th>
       <th id="center" style=" width: 100px;" >Acão</th>

       <!--<th id="center" title="Preço Profissionais Unitário">Profissionais Preço Unitário</th>-->

     </tr>
   </thead> 

   <tbody> 


    @php
    $total_pedido =0;
    @endphp




    @foreach ($pedido->itens_pedido_request as $item_pedido)
    <tr> 

     <td id="center">
      <span class="chip"> {{ $item_pedido->request->request_cod}}</span>
    </td> 
    <td  id="center">
      {{ $item_pedido->request->request_desc}}
    </td>
    <td  id="center">
      {{ $item_pedido->quantidade}}

    </td>
    <td id="center">
      R$ {{ number_format($item_pedido->request->request_valor, 2, ',', '.')}}
    </td>       



    @php
    $total_produto = $item_pedido->total;
    $total_pedido += $total_produto;          
    @endphp

    <td  id="center">
     R$ {{ number_format($total_produto, 2, ',', '.') }}
   </td>

   <td title="Ações" id="center"> 
    <a href="#" onclick="carrinhointerRemoverRequest({{ $pedido->id}}, {{ $item_pedido->request_id }}, 1)"
      data-toggle="tooltip" 
      data-placement="top"
      title="Remover" class="btn-floating btn-small waves-effect waves-light red"><i class="material-icons">remove_circle_outline</i>
    </a> &nbsp; 



    <a href="#" onclick="carrinhointerAdicionarRequest({{ $item_pedido->request_id }})" 
      data-toggle="tooltip" 
      data-placement="top"
      title="Adicionar" class="btn-floating btn-small waves-effect waves-light btn-primary"><i class="material-icons">add_circle_outline</i></a>

      <a href="#" onclick="carrinhointerRemoverRequest({{ $pedido->id}}, {{ $item_pedido->request_id }}, 0)" class="waves-effect btn-Tiny secondary-content">Remover Item</a>
    </td>

  </tr>



  @endforeach

  <tr><td colspan="7"><b>Total: R$ {{ number_format($total_pedido, 2, ',', '.') }}</b></td></tr>



</tbody>    
</table>
</div>



@empty


@endforelse

@forelse($pedidos as $pedido)

@if (($pedido->produto_id != NULL) && ($pedido->request_id == NULL))

@else

@foreach ($pedido->itens_pedido as $item_pedido)

@php
$total_produto = $item_pedido->total;
$total_pedido += $total_produto;          
@endphp
@endforeach

@endif




<!--<div class="col-md-6">
 <div class="table-responsive">
      <table class="table table-striped table-bordered table-condensed table-hover">
    
        <thead>
         
            <th style=" width: 100px; font-size: 15px;" id="black" valign="middle">Total Pedido: R$ {{ number_format($total_pedido, 2, ',', '.') }} +</th>
           
           <th style=" width: 60%;">Custo Frete: R$ <input type='text' name="freteIsset" id="freteIsset" style="display:none;" maxlength='6' size="1" pattern='([0-9]{1,3}\.)?[0-9]{1,3},[0-9]{2}$' title="Informe o custo do frete" placeholder=" 0,00" readonly="readonly"></th>
                     
        </thead> 
          
       <tbody> 

          <tr> 
            <td id="black"> {{ number_format($total_pedido, 2, ',', '.') }}</td>
             <td> <input type='text' name="valor" style="display:none; margin-top: 5px;" maxlength='6' pattern='([0-9]{1,3}\.)?[0-9]{1,3},[0-9]{2}$' title="Informe o custo do frete" placeholder="0,00" onkeypress='mascara( this, mvalor );'></td>
          </tr>


</tbody>
</table>


</div>
</div>-->

<div class="divider" style="margin-bottom: 5px;"></div>






  <!--<p class="lead" id="black" style="margin-top: 5px; margin-left: -190px;  display:inline-block;">Total de Itens: R$ {{ number_format($total_pedido, 2, ',', '.') }} + Custo Frete: ( R$
  </p>

 

  
  <div class="col-md-2"  style="display:inline-block;">
  <input type='text' name="valor" style="display:none; margin-left: 420px; margin-top: 5px;" maxlength='6' pattern='([0-9]{1,3}\.)?[0-9]{1,3},[0-9]{2}$' title="Informe o custo do frete" placeholder="0,00" onkeypress='mascara( this, mvalor );'>
  </div>

  <p class="lead" id="black" style="margin-top: 5px; margin-left: 160px;  display:inline-block;">) Total Pedido: R$ {{ number_format($total_pedido, 2, ',', '.') }}
  </p>-->



  <p class="lead" id="black">Total Geral: R$ {{ number_format($total_pedido, 2, ',', '.') }}</p>



  <div class="divider" style="margin-bottom: 10px; margin-top: -15px;"></div>
  @empty
  @endforelse



  @forelse($pedidos as $pedido)

  <div class="row">
    <div class="col-md-2">
      <button  onclick="myFunction()" class="btn waves-effect amber">
       <strong>Concluir</strong>   
       <script type="text/javascript">
         function myFunction() {
          document.getElementById("myCheck").click();
        }

      </script>
    </button>  

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

    <form method="POST" action="{{route('pedidosint.detalhes', $pedido->id)}}"> 
      {{ csrf_field() }} 

      <input type="hidden" name="id_cliente" value="{{$pedido->id_cliente}}">
      <input type="hidden" name="user_id" value="{{$pedido->user_id}}">
      <input type="hidden" name="vendedor_id" value="{{$pedido->vendedor_id}}">
      <input type="hidden" name="obs_pedido"  id="obspedidoDetalhes" value="{{$pedido->obs_pedido}}">
      <!-- Modal content-->   

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h2 class="modal-title">Detalhes do Pedido</h2>

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


                    <div class="col-md-12">
                      <p style="color: #9e9e9e; font-size: 12px; margin-top: 20px;"><b>DETALHES DO FRETE</b></p> 

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

                    <label>
                      <input type="checkbox" name="balcao" id="balcao" checked="checked" value="Y" /><span style="font-size: 13px; margin-top: 2px;">Retirada Balcão</span>
                    </label>  


                    <div name="op2" style="display:none">

                      <label>
                        <input type="radio" name="entrega" value="B" />
                        <span style="font-size: 13px;" >Entrega: MOTO BOY</span><!--color: #4dd0e1;  -->
                      </label>   
                      <label>
                        <input type="radio" name="entrega" value="C" />
                        <span style="font-size: 13px;">Entrega: CORREIOS</span><!--color: #ffd54f;  -->
                      </label> 


                      <label for="valor" style="font-size: 13px;" class="col-md-4" >Custo Frete
                        <input type='text' name="valor" class="money" value="{{ old('valor')}}" style="display:none;" maxlength="6" pattern='([0-9]{1,3}\.)?[0-9]{1,3},[0-9]{2}$' title="Informe o custo do frete R$ 0,00" placeholder="R$ 0,00"></label>

         

                   <script type="text/javascript">

                    $('[name="balcao"]').change(function() 
                    {
                      
                    //$('[name="op1"]').toggle(200);
                    $('[name="op2"]').toggle(200);
                    $('[name="valor"]').toggle(200);
                    
                    $('input[name=entrega]').attr('checked', false);
                    $('input[name=local]').attr('checked', false);
                    $('[name=localisset]').hide();
                    $('[name="balcaoempty"]').toggle(200);

                    $('[name="local"]').on( "click", function()
                    {

                     $('[name="localisset"]').toggle(200);

                   });

                    //$('input[name=entrega]').hide(); 
                  });

                    

                    


                    function myFunction() {
                      document.getElementById("myCheck").click();
                    }


                   // var a = document.getElementById('obspedido');

                   // var b = document.getElementById('obspedidoDetalhes');
                   // b.value = a.value;

                   

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


              </div>
            </div>



            <div name="balcaoempty" style="display:none" class="col-md-12">                                
              <label>
                <input type="checkbox" name="local" value="Y"  /><span style="font-size: 13px;" title="Caso o endereço de cadastro seja diferente do endereço de entrega">Alterar Local da Entrega</span>
              </label>
            </div>

            <div name="localisset" style="display:none" class="col-md-12">

              <div class="input-field">
                <input type="text" pattern="[0-9]{5}-[0-9]{3}" id="cep" value="{{ old('cep') }}" class="cep" title="Informe o CEP. Consulta automática Ex:00000-000" maxlength="9" name="cep" placeholder="Forneça o CEP" />
                <label for="cep" style="font-size: 15px;">Cep</label>
              </div>
              <div class="input-field">

                <input type="text" onkeypress='mascara( this, soLetras );' id="endereço" title="Informe o Endereço" maxlength="191" placeholder="Forneça o endereço" name="endereço" value="{{ old('endereço') }}"/>
                <label for="endereço" style="font-size: 15px;">Endereço</label>
              </div>
              <div class="input-field">
                <input type="text" onkeypress='mascara( this, mnum );' title="Informe o Número" maxlength="8" name="numero" value="{{ old('numero') }}" placeholder="Forneça o número"/>
                <label for="numero" style="font-size: 15px;">Número</label>
              </div>
              <div class="input-field">
                <input type="text"  id="bairro" title="Informe o Bairro" placeholder="Forneça o Bairro" maxlength="191" name="bairro" value="{{ old('bairro') }}"/>
                <label for="bairro" style="font-size: 15px;">Bairro</label>
              </div>
              <div class="input-field">
                <input type="text" title="Informe o Complemento" placeholder="Forneça o Complemento" maxlength="191" name="complemento"  value="{{ old('complemento') }}" />
                <label for="complemento" style="font-size: 15px;">Complemento</label>
              </div>

              <div class="input-field">
                <input type="text" id="cidade" title="Informe a Cidade" maxlength="191" name="cidade"  placeholder="Forneça o nome da Cidade"  value="{{ old('cidade') }}"/>
                <label for="cidade" style="font-size: 15px;">Cidade</label> 
              </div>    


              <select name="estado" class="form-control" id="estado" title="Informe o Estado" style=" width: 200px;  float: left;">
                <option value="{{ old('estado') }}">{{old('estado') }} ---Selecione o Estado ---</option>
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
              </select><br>

            </div>

            <div class="col-md-12">
             <br>
             <p style="color: #9e9e9e; font-size: 12px; margin-left: 4px; margin-top: 5px;"><b>FORMAS DE PAGAMENTO</b></p>
             <label>
              <input type='radio' name='pagamento' value="D">
              <span style="font-size: 12px;">Dinheiro</span> <!--Dinheiro-->
            </label>
            <label>
              <input type="radio" name="pagamento" value="CC">
              <span style="font-size: 12px;" >Cartão de Crédito</span>
            </label>
            <label>
              <input type="radio" name="pagamento" value="CD">
              <span style="font-size: 12px;" >Cartão de Débito</span>
            </label>
            <label>
              <input type="radio" name="pagamento" value="BB">
              <span style="font-size: 12px;" >Boleto Bancário</span>                         
            </label>
            
          </div>
        </div>


        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Voltar</button>            
          <button type="submit"  class="btn waves-effect waves-light  blue darken-2"><span class="glyphicon glyphicon-floppy-disk"></span><b> Salvar</b></button>
        </div>

      </form>



    </div> 
  </div>





  @empty
  @endforelse



</div>


</div>
</div>
</div>


</div>


<form id="form-remover-produto" method="POST" action="{{ route('carrinhointer.remover') }}">
  {{ csrf_field() }}
  {{ method_field('DELETE') }}
  {!! method_field('put') !!}
  <input type="hidden" name="pedido_id">
  <input type="hidden" name="produto_id">
  <input type="hidden" name="request_cod">
  <input type="hidden" name="item">
</form>



<form id="form-adicionar-produto" method="POST" action="{{ route('carrinhointer.adicionar') }}">
  {{ csrf_field() }}
  <input type="hidden" name="id">
  <input type="hidden" name="request_cod">
  @forelse($pedidos as $pedido)
  <input type="hidden" name="obs_pedido" value="{{$pedido->obs_pedido}}">
  <input type="hidden" name="id_cliente" value="{{$pedido->id_cliente}}">
  @empty

  @endforelse

</form>



@push('scripts')
<script type="text/javascript" src="/js/carrinhointer.js"></script>
@endpush

@endsection