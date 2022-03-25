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
      <h2>Novo Pedido</h2> 
      <div class="divider" style="margin-bottom: 5px; border-top: 2px solid black;" ></div>

      <div class="row">
        <div class="col-md-12">

          @forelse ($pedidos as $pedido)
          <p class="lead" id="black" style="margin-bottom: -2px; margin-top: 5px; color: #4db6ac;"> Pedido: {{ $pedido->id }}
           Gerado: {{ $pedido->created_at->format('d/m/Y H:i') }} </p>
           <div class="divider" style="margin-bottom: 10px; margin-top: 5px;"></div>
           @empty
           @endforelse
           <div class="card-panel">
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


            <div class="row" style="margin-bottom: -10px;">
              <form method="POST" action="{{ route('carrinho.adicionar') }}">
                {{ csrf_field() }}
                @forelse ($pedidos as $pedido)   

                <div class="col-md-5">               
                  <section style="margin-top: -12px;">      
                    <label style="font-size: 15px; margin-top: -10px;">Cliente:</label>
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
                    url:'{!!URL::to('findRequisitionsName') !!}',
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

               <label style="font-size: 15px;">Requisições:</label> 
               <select class="form-control request_cod"  name="request_cod" style=" position: relative;" id="">
                <option value="0" disabled="true" selected="true">---Selecione a Requisição ---</option>
                @foreach($list_requisitions as $request)        
                <option title="{{$request->request_desc}}" value="{{$request->id}}">{{ $request->status == 'CA' ? 'CANCELADA:' : '' }} {{ $request->status == 'FI' ? 'FINALIZADA:' : '' }} {{ $request->status == 'AP' ? 'AGUARDANDO PRODUÇÃO:' : '' }} {{ $request->status == 'RE' ? 'Atrelada ao pedido' : '' }} {{$request->request_cod}} {{$request->request_desc}} </option>     
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
              <label style="font-size: 15px; margin-top: -35px;">Produto:</label>
            </div>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>      
            <script type="text/javascript">
              $("#produtos").select2({
                placeholder:'---Selecione o Produto---'
              });
            </script>  
            <!--<input type="number" name="quantidade">-->
          
            <button type="submit" class="btn btn-small waves-effect waves-light  blue darken-2" data-position="top" data-delay="50" data-toggle="tooltip" data-placement="bottom" title="Adicionar">
             <b>Adicionar</b>
           </button>  

         </div>



         @empty

         <div class="col-md-5">
          <div class="input-field" style="margin-top: -8px;">  
            <section>
              <label style="font-size: 15px; ">Escolha um cliente</label> 
              <select id="id_cliente" class="form-control id_clientesearch"  name="id_cliente" required="required">   
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
                    url:'{!!URL::to('findRequisitionsName') !!}',
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

               <label style="font-size: 15px;">Seleção de requisições</label> 
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
            <label style="font-size: 15px; margin-top: -30px;">Escolha um Produto</label>
          </div>
          <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
          <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>      
          <script type="text/javascript">
            $("#produtos").select2({
              placeholder:'---Selecione o Produto---'
            });
          </script>  



          <button type="submit" class="btn btn-small waves-effect waves-light  blue darken-2" data-position="top" data-delay="50" data-toggle="tooltip" data-placement="bottom" title="Adicionar">
           <b>Adicionar</b>
         </button>  
       </div> 

       @endforelse

       <div class="col-md-12"><div class="divider" style="margin-top: 5px;"></div><br></div>

       @forelse ($pedidos as $pedido)          

       <div class="col-md-6">         
         <div class="input-field">              
           <input type="text" name="obs_pedido" title="Informe as observações gerais do pedido" placeholder="Observações gerais do pedido" value="{{$pedido->obs_pedido}}" placeholder="{{$pedido->obs_pedido}}" />
           <label for="obs_pedido" style="font-size: 19px;">Observações</label>
         </div> 
       </div>
       <div class="col-md-12"><div class="divider"></div><br></div>



       <div class="col-md-6 right">
        <p style="color: #9e9e9e; font-size: 15px; margin-left: 4px; margin-top: 5px;"><b>Formas de Pagamento</b></p>
        <label>
          <input type='radio' name='pagamento' class='pagamento' pagamentoIsset='pagamentoIsset' value="D">
          <span style="font-size: 12px;" >Dinheiro</span> <!--Dinheiro-->
        </label>
        <label>
          <input type="radio" name="pagamento" class="pagamento" pagamentoIsset='pagamentoIsset' value="CC">
          <span style="font-size: 12px;" >Cartão de Crédito</span>
        </label>
        <label>
          <input type="radio" name="pagamento" class="pagamento" pagamentoIsset='pagamentoIsset' value="CD">
          <span style="font-size: 12px;" >Cartão de Débito</span>
        </label>
        <label>
          <input type="radio" name="pagamento" class="pagamento" pagamentoIsset='pagamentoIsset' value="BB">
          <span style="font-size: 12px;" >Boleto Bancário</span>                         
        </label>
        <div class="input-field col-md-5">
          <input type='text' name="desconto" maxlength='4' id="descontoPedido" pattern='([0-9]{1,3}\.)?[0-9]{1,3},[0-9]{2}$' data-mask="00,00" title="Informe o desconto" placeholder="R$ 0,00" style=" margin-left: -8px;"> 
          <label for="desconto" style=" margin-left: 5px; font-size: 15px;">Desconto</label>
          <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.js"></script>
          <script type="text/javascript">
            $(document).ready(function(){
             
          $('.money').mask('000.000.000.000.000,00', {reverse: true});
              
              });
          </script>
        </div>
      </div>

      <div class="col-md-6">
       <p style="color: #9e9e9e; font-size: 15px; margin-left: 4px; margin-top: 5px;"><b>Detalhes do frete</b></p> 
       <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
       <label>
        <input type="checkbox" name="balcao" id="isAgeSelected" checked="checked" value="Y" /><span style="color: #4db6ac; font-size: 12px;  margin-left: 4px; margin-top: 2px;">RETIRADA BALCÃO</span>

      </label>  


      <div name="op2" style="display:none" class="col-md-5">
        <label>
          <input type="radio" name="entrega" value="{{'B' or old('entrega') }}" />
          <span style="font-size: 12px;  margin-left: -15px;" >ENTREGA MOTOBOY</span><!--color: #4dd0e1;  -->
        </label>   
        <label>
          <input type="radio" name="entrega" value="C" />
          <span style="font-size: 12px;  margin-left: -15px; ">ENTREGA CORREIOS</span><!--color: #ffd54f;  -->
        </label> 
    
        <div class="input-field">
          <input type='text' name="valor" id="custoFrete" style="display:none; margin-left: -10px; " maxlength='6' pattern='([0-9]{1,3}\.)?[0-9]{1,3},[0-9]{2}$' title="Informe o custo do frete" placeholder="R$ 0,00" onkeypress='mascara( this, mvalor );'>
          <label for="valor" style=" margin-left: -10px;  font-size: 18px;" >Custo do frete</label>
           <!-- <a href="{{route('pedidos.edit', $pedido->id)}}" 
                   data-toggle="tooltip" 
                   data-placement="top"
                   title="Alterar" class="btn waves-effect amber">Soma frete</a>-->
                 </div>           

                 <script type="text/javascript">

                  $('[name="balcao"]').change(function() 
                  {
                    //$('[name="op1"]').toggle(200);
                    $('[name="op2"]').toggle(200);
                    $('[name="valor"]').toggle(200);
                    $('[name="freteIsset"]').toggle(200);  
                    $('input[name=entrega]').attr('checked', false);
                    //$('input[name=entrega]').hide();
                    $('input[name=local]').attr('checked', false);
                    //$('input[name=entrega]').hide(); 
                  });

                  function myFunction() {
                    document.getElementById("myCheck").click();
                  }
                </script> 
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
      <label for="obs_pedido" style="font-size: 19px;">Observações</label>
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


<div style="margin-bottom: -15px;"></div>


<div class="table-responsive">
  <table class="table table-striped table-bordered table-condensed table-hover" >

    <thead>
      <tr><th id="center" colspan="6" style="color: #4db6ac; border-color: #4db6ac;"> PRODUTOS</th></tr>
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
      <tr><th id="center" colspan="6" style="color: #4db6ac; border-color: #4db6ac;"> REQUISIÇÕES</th></tr>
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
    <a href="#" onclick="carrinhoRemoverRequest({{ $pedido->id}}, {{ $item_pedido->request_id }}, 1)"
      data-toggle="tooltip" 
      data-placement="top"
      title="Remover" class="btn-floating btn-small waves-effect waves-light red"><i class="material-icons">remove_circle_outline</i>
    </a> &nbsp; 



    <a href="#" onclick="carrinhoAdicionarRequest({{ $item_pedido->request_id }})" 
      data-toggle="tooltip" 
      data-placement="top"
      title="Adicionar" class="btn-floating btn-small waves-effect waves-light btn-primary"><i class="material-icons">add_circle_outline</i></a>

      <a href="#" onclick="carrinhoRemoverRequest({{ $pedido->id}}, {{ $item_pedido->request_id }}, 0)" class="waves-effect btn-Tiny secondary-content">Remover Item</a>
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

<div class="divider" ></div>






  <!--<p class="lead" id="black" style="margin-top: 5px; margin-left: -190px;  display:inline-block;">Total de Itens: R$ {{ number_format($total_pedido, 2, ',', '.') }} + Custo Frete: ( R$
  </p>

 

  
  <div class="col-md-2"  style="display:inline-block;">
  <input type='text' name="valor" style="display:none; margin-left: 420px; margin-top: 5px;" maxlength='6' pattern='([0-9]{1,3}\.)?[0-9]{1,3},[0-9]{2}$' title="Informe o custo do frete" placeholder="0,00" onkeypress='mascara( this, mvalor );'>
  </div>

  <p class="lead" id="black" style="margin-top: 5px; margin-left: 160px;  display:inline-block;">) Total Pedido: R$ {{ number_format($total_pedido, 2, ',', '.') }}
  </p>-->


<div class="row"  style="margin-bottom: -20px; ">
<div class="col-md-4">
  <p class="lead" id="black" style="margin-top: 5px; margin-bottom: -29px;">Total Geral:</p>

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js" type="text/javascript"></script>
  <script src="jquery.maskMoney.js" type="text/javascript"></script>


    <input type="text" class="input_css" placeholder="R$ {{ number_format($total_pedido, 2, ',', '.') }}" id="totalValorAdicional" value="" readonly="readonly" >
 


  <style type="text/css">

    #totalValorAdicional::-webkit-input-placeholder
    {
      color:black;
      font-size: 22px;
      text-transform: uppercase;
      font-style: bold;
      text-align: center;
    }

    .input_css
    {
      color:black;
      font-size: 22px;
      text-transform: uppercase;
      font-style: bold;
      text-align: center;



    }

  </style>





  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

  <script type="text/javascript">
   $(document).ready(function() {
    $("#totalItensPedido,#custoFrete,#descontoPedido").on('keyup', function() {

      var pedido = parseFloat($('#totalItensPedido').val().replace(/\./g,'').replace(/\,/g,'.')) || 0;
      var frete = parseFloat($('#custoFrete').val().replace(/\,/g,'.')) || 0;
      var desconto = parseFloat($('#descontoPedido').val().replace(/\,/g,'.')) || 0;


      var freteIsset = frete;
      $('#freteIsset').val(freteIsset.toFixed(2));
      var totalValorAdicionalDX = pedido + frete - desconto;
      $('#totalValorAdicional').css('font-size', '24px').val('R$ '+ totalValorAdicionalDX.toFixed(2));

    
    });
  }); 



 /*  $('input').css('margin-bottom', '5px').on('change', function() {
  
  var v = 0;  
  
  $('input').each(function(i,e) {   
    
    if ($(e).val()) {
      
      var i = $(e).val().replace(/\,/g,'.');
      
      if (isNaN(i)) { $(e).val(''); return; }
          
      v += parseFloat(i);
   
      $('div').text('Total: ' + v.toFixed(2));

    
    }
    
    });
      
  });*/

</script>

<input type="hidden" id="totalItensPedido" value="{{ number_format($total_pedido, 2, ',', '.') }} ">

</div>
</div>
<hr>
@empty
@endforelse



@forelse($pedidos as $pedido)
<div class="row">
<div class="col-md-2">
<form method="POST" action="{{ route('pedido.concluir') }}">
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



  </script>
</button>   
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


</div>


<form id="form-remover-produto" method="POST" action="{{ route('carrinho.remover') }}">
  {{ csrf_field() }}
  {{ method_field('DELETE') }}
  {!! method_field('put') !!}
  <input type="hidden" name="pedido_id">
  <input type="hidden" name="produto_id">
  <input type="hidden" name="request_cod">
  <input type="hidden" name="item">
</form>



<form id="form-adicionar-produto" method="POST" action="{{ route('carrinho.adicionar') }}">
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
<script type="text/javascript" src="/js/carrinho.js"></script>
@endpush

@endsection