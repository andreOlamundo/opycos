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
      <div class="row">
        <div class="col-md-12">
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
    
      <div class="row">  
          <form method="POST" action="{{ route('carrinho.adicionar') }}">
            {{ csrf_field() }}    
 
               @forelse ($pedidos as $pedido)  
               <div class="col-md-5">       
           
               <div class="input-field">        
                <select id="clientes" name="id_cliente">       
                 <option></option>
                 <option value="{{$pedido->Cliente->id}}" title="doc:{{$pedido->Cliente->cnpj}}{{$pedido->Cliente->cpf}}">{{$pedido->Cliente->id}}. {{$pedido->Cliente->name}}. cel:{{$pedido->Cliente->celInput}}</option>        
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
            <div class="input-field" style="margin-top: -10px;">  
              <section>
              <label style="font-size: 15px;">Escolha um cliente</label> 
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
                              $.ajax({
                                      type:'get',
                                      url:'{!!URL::to('findRequisitionsName') !!}',
                                      data:{'id':id_cliente},
                                      success:function(data){
                                     //console.log('success');
                                       // console.log(data);
                                        //console.log(data.length);
                                        op+='<option value="0" selected disabled>Escolha uma requisição</option>';
                                        for(var i=0;i<data.length;i++){
                                          op+='<option value="'+data[i].id+'">'+data[i].req_desc+'</option>';
                                        }
                                        section.find('.req_desc').html(" ");
                                        section.find('.req_desc').append(op);
                                      },
                                      erro:function(){

                                      }

                                     }); 
                                     });
            </script>

          <label style="font-size: 15px;">Escolha a requisição</label> 
       <select class="form-control req_desc"  name="req_desc" style=" position: relative;" id="">
        <option value="0" disabled="true" selected="true">---Selecione a requisição---</option>
        @foreach($list_requisitions as $request)        
        <option value="{{$request->id}}"> {{$request->req_desc}}</option>     
        @endforeach    
      </select>    
             </section>
                    
               </div> 
<br>
    <div class="input-field">          
      <select id="produtos" name="id" required="required"> 
        @foreach($registros as $registro)
        <option></option>
        <option value="{{ $registro->id }}" title="PreçoBalcão R$: {{number_format($registro->prod_preco_balcao, 2,',','.')}} &nbsp; PreçoPadrão R$: {{number_format($registro->prod_preco_padrao, 2,',','.')}} &nbsp; PreçoProfissionais: R$ {{number_format($registro->prod_preco_prof, 2,',','.')}}">{{$registro->prod_cod}} &hybull; {{$registro->prod_desc}}   
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

              


                  
</div>

          @endforelse
            
               </div>
        @forelse ($pedidos as $pedido)            

                          <div class="col-md-6">           
                           <div class="input-field">              
                             <input type="text" name="obs_pedido" title="Informe as observações gerais do pedido" placeholder="Observações gerais do pedido" value="{{$pedido->obs_pedido}}" placeholder="{{$pedido->obs_pedido}}" autofocus/>
                             <label for="obs_pedido" style="font-size: 19px;">Observações</label>
                           </div> 
                         </div>
                         <div class="col-md-12"><div class="divider"></div></div>
                        
                           @forelse ($retiradaBalcPF as $retirada)
                            <div class="col-md-5">
                           <p style="color: #9e9e9e; font-size: 15px; margin-left: 4px; margin-top: 5px;"><b>Detalhes do frete</b></p> 
                           <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
                           <label>
                            <input type="checkbox" name="balcao" id="isAgeSelected" disabled readonly value="{{$retirada->balcao}}" {{ $retirada->balcao == 'YPF' ? 'checked' : '' }} /><span style="color: #4db6ac; font-size: 12px;  margin-left: 4px; margin-top: -10px;">RETIRADA BALCÃO - PF</span>
                          </label>
                        <input type="hidden" name="balcao" value="{{$retirada->balcao}}" />
                        </div>
                        <div class="col-md-7">
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
                        </div>

                        <div class="col-md-12"></div>
                        @empty
                        @forelse ($retiradaBalcPJ as $retirada)
                                <div class="col-md-5">
                           <p style="color: #9e9e9e; font-size: 15px; margin-left: 4px; margin-top: 5px;"><b>Detalhes do frete</b></p> 
                           <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
                         <label>
                            <input type="checkbox" name="balcao" id="isAgeSelected" disabled readonly value="{{$retirada->balcao}}" {{ $retirada->balcao == 'YPJ' ? 'checked' : '' }} />
                            <!-- Frete É preciso escolher um tipo de frete-->
                         
                            <span style="color: #4db6ac; font-size: 12px;  margin-left: 4px; margin-top: -10px;">RETIRADA BALCÃO - PJ</span>
                          </label>
                           <input type="hidden" name="balcao" value="{{$retirada->balcao}}" />
                        </div>                   
                         
                   
                        <!-- DIV ROW -->
                        <div class="col-md-7">
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
                   
                        </div>

                        <div class="col-md-12"></div>
                     
                        @empty
  </div>
  <div class="row">
  	<div class="col-md-12">

                        @forelse($freteB_PF as $freteBoy)
                          
                         <div class="col-md-7 right">
                         <p style="color: #9e9e9e; font-size: 15px; margin-left: 4px; margin-top: -10px "><b>Formas de Pagamento</b></p>
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
                        </div>
                        <div name="op2" class="col-md-5"> 
                         <p style="color: #9e9e9e; font-size: 15px; margin-left: -10px; margin-top: -10px"><b>Detalhes do frete</b></p>       
                          <label>
                            <input type="radio" name="entrega" value="{{$freteBoy->entrega}}" {{ $freteBoy->entrega == 'BPF' ? 'checked' : '' }} />
                            <span style="font-size: 12px;  margin-left: -15px;" >ENTREGA MOTOBOY - PF</span>
                          </label><!--color: #4dd0e1;  -->
                          <div class="input-field">
                            <input type='text' name="valor" style="margin-left: -10px;" value="{{ number_format($freteBoy->valor, 2, ',', '.') }}" maxlength='6' pattern='([0-9]{1,3}\.)?[0-9]{1,3},[0-9]{2}$' title="{{ number_format($freteBoy->valor, 2, ',', '.') }}" placeholder="R$ 0,00" onkeypress='mascara( this, mvalor );' readonly="readonly"/> 
                        <label for="valor" style="margin-left: -10px; font-size: 15px;">Custo do frete</label>
                       </div>
                         

                        
         
                        <div class="col-md-12"></div>
                        @empty
                        @forelse($freteB_PJ as $freteBoy)
                                       <div class="col-md-7 right">
                         <p style="color: #9e9e9e; font-size: 15px; margin-left: 4px; margin-top: -10px "><b>Formas de Pagamento</b></p>
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
                        </div>
                         <div name="op2" class="col-md-5"> 
                         <p style="color: #9e9e9e; font-size: 15px; margin-left: -10px; margin-top: -10px"><b>Detalhes do frete</b></p>       
                          <label>
                            <input type="radio" name="entrega" value="{{$freteBoy->entrega}}" {{ $freteBoy->entrega == 'BPJ' ? 'checked' : '' }} />
                            <span style="font-size: 12px;  margin-left: -15px;" >ENTREGA MOTOBOY - PJ</span>
                          </label><!--color: #4dd0e1;  -->
                         <div class="input-field">
                            <input type='text' name="valor" style="margin-left: -10px;" value="{{ number_format($freteBoy->valor, 2, ',', '.') }}" maxlength='6' pattern='([0-9]{1,3}\.)?[0-9]{1,3},[0-9]{2}$' title="{{ number_format($freteBoy->valor, 2, ',', '.') }}" placeholder="R$ Custo do frete" onkeypress='mascara( this, mvalor );' readonly="readonly"/> 
                          <label for="valor" style="margin-left: -10px; font-size: 15px;">Custo do frete</label></div>
                          <label>
                          

                        <div class="col-md-12">

                        </div>
                        @empty
                        @forelse ($freteC_PF as $freteCorreios)  
                             <div class="col-md-7 right">
                         <p style="color: #9e9e9e; font-size: 15px; margin-left: 4px; margin-top: -10px "><b>Formas de Pagamento</b></p>
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
                        </div>

                        <div name="op2" class="col-md-5"> 
                        	<p style="color: #9e9e9e; font-size: 15px; margin-left: -10px; margin-top: -10px"><b>Detalhes do frete</b></p>

                         <label>
                          <input type="radio" name="entrega" {{ $freteCorreios->entrega == 'CPF' ? 'checked' : '' }} />
                          <span style="font-size: 12px; margin-left: -15px;">ENTREGA CORREIOS - PF</span><!--color: #ffd54f;  -->       
                        </label> 
                        <div class="input-field">
                          <input type='text' name="valor" style="margin-left: -10px;" value="{{ number_format($freteCorreios->valor, 2, ',', '.') }}" maxlength='6' pattern='([0-9]{1,3}\.)?[0-9]{1,3},[0-9]{2}$' title="{{ number_format($freteCorreios->valor, 2, ',', '.') }}" placeholder="R$ Custo do frete" onkeypress='mascara( this, mvalor );' readonly="readonly"/> 
                        <label for="valor" style="margin-left: -10px; font-size: 15px;">Custo do frete</label>
                        </div>
                       
                        

                        <div class="col-md-12">

                        </div>
                        @empty                       
                  
                      <h4>Erro Nenhum frete localizado</h4>
                      @endforelse
                      @endforelse
                      @endforelse
                      @endforelse
                    </div>
                  </div>

</div>
                  @endforelse
           
                 
                  @empty



    <div class="col-md-6">           
     <div class="input-field">              
      <input type="text" name="obs_pedido" title="Informe as observações gerais do pedido" value="{{ old('obs_pedido') }}" placeholder="Observações gerais do pedido" maxlength="255" autofocus/>
      <label for="obs_pedido" style="font-size: 19px;">Observações</label>
      <input type="hidden" name="tipo" value="Y"/>
    </div>
  </div>


  <div class="col-md-12"><div class="divider"></div></div> 

  <div class="col-md-10">
   <p style="color: #9e9e9e; font-size: 15px; margin-left: 4px; margin-top: 5px;"><b>Detalhes do frete</b></p> 
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
   <label>
    <input type="checkbox" name="balcao" id="isAgeSelected" checked="checked" value="Y" /><span style="color: #4db6ac; font-size: 12px;  margin-left: 4px; margin-top: 2px;">RETIRADA BALCÃO</span>
    
  </label>  


  <div name="op2" style="display:none" class="col-md-5">
    <label>
      <input type="radio" name="entrega" value="B"/>
      <span style="font-size: 12px;  margin-left: -15px;" >ENTREGA MOTOBOY</span><!--color: #4dd0e1;  -->
    </label>   
    <label>
      <input type="radio" name="entrega" value="C" />
      <span style="font-size: 12px;">ENTREGA CORREIOS</span><!--color: #ffd54f;  -->
    </label> 
    <div class="input-field">
      <input type='text' name="valor" style="display:none; margin-left: -7px; " maxlength='6' pattern='([0-9]{1,3}\.)?[0-9]{1,3},[0-9]{2}$' title="Informe o custo do frete" placeholder="R$ 0,00" onkeypress='mascara( this, mvalor );'> 
     <label for="valor" style="margin-left: -5px; font-size: 15px;">Custo do frete</label>
    </div> 

    <script type="text/javascript">

      $('[name="balcao"]').change(function() {
  //$('[name="op1"]').toggle(200);
  $('[name="op2"]').toggle(200);
  $('[name="valor"]').toggle(200);
  $('input[name=entrega]').attr('checked', false);
  //$('input[name=entrega]').hide();
  $('input[name=local]').attr('checked', false);
  //$('input[name=entrega]').hide(); 
});

      function myFunction() {
        document.getElementById("myCheck").click();
      }
    </script> 



  

@endforelse
</div>

<hr style="margin-top: -8px;">
<div class="row">    



 </form>
</div>
</div>
</div>
</div>
<div class="row"> 
  @forelse ($pedidos as $pedido)
  <p class="lead" id="black" style="margin-left: 15px; margin-top: -10px"> Pedido: {{ $pedido->id }}
   Gerado em: {{ $pedido->created_at->format('d/m/Y H:i') }} </p>
   <div class="col-md-12">

    <div class="table-responsive">
      <table class="table table-striped table-bordered table-condensed table-hover" >
        <thead>
          <tr class="warning">                    
           <th id="center">Código</th>
           <th id="center">Produto</th>
           <th id="center">Qtd</th>         
           @forelse ($retiradaBalcPF as $retirada)
           <th id="center" title="Preço BALCÃO. - PF"><p style="color: #4db6ac; font-size: 10px;">BALCÃO PF</p> Preço Unitário</th>
           <th id="center">Total</th>
           <th id="center" style=" width: 100px;" >Acão</th>
           @empty
           @forelse ($retiradaBalcPJ as $retirada)
           <th id="center" title="Preço BALCÃO. - PJ"><p style="color: #4db6ac; font-size: 10px;">BALCÃO PJ</p> Preço Unitário</th>
           <th id="center">Total</th>
           <th id="center" style=" width: 100px;" >Acão</th>
           @empty
           @forelse ($freteB_PF as $freteBoy)
           
           <th id="center" title="Preço PADRÃO. - PF"><p style="color: #4db6ac; font-size: 10px;">PADRÃO. - PF</p>Preço Unitário</th>
           <th id="center">Total</th>
           <th id="center" style=" width: 100px;" >Acão</th>
           @empty
           @forelse ($freteB_PJ as $freteBoy)
           <th id="center" title="Preço PROF. - PJ"><p style="color: #4db6ac; font-size: 10px;">PROF. - PJ</p> Preço Unitário</th>
           <th id="center">Total</th>
           <th id="center" style=" width: 100px;" >Acão</th>
           @empty
           @forelse ($freteC_PF as $freteCorreios)
           <th id="center" title="Preço PADRÃO. - PF"><p style="color: #4db6ac; font-size: 10px;">PADRÃO. - PF</p>Preço Unitário</th>
           <th id="center">Total</th>
           <th id="center" style=" width: 100px;" >Acão</th>
           @empty
           @forelse ($freteC_PJ as $freteCorreios)
            <th id="center" title="Preço PADRÃO. - PJ"><p style="color: #4db6ac; font-size: 10px;">PROF. - PJ</p>Preço Unitário</th>
           <th id="center">Total</th>
           <th id="center" style=" width: 100px;" >Acão</th>
           @empty

           <!--<th id="center" title="Preço Profissionais Unitário">Profissionais Preço Unitário</th>-->
           @endforelse
           @endforelse
           @endforelse
           @endforelse
           @endforelse
           @endforelse
         </tr>

       </thead> 
       <tbody> 
        @forelse ($retiradaBalcPF as $retirada)

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
            R$ {{ number_format($item_pedido->product->prod_preco_balcao, 2, ',', '.')}}
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

        @php
        $total_pedido += $pedido->valorReq; 
        @endphp

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
</form>
  @empty
  @forelse ($retiradaBalcPJ as $retirada)
         @php
         $total_pedido =0;
         @endphp
         @foreach ($pedido->item_pedido_PJ as $item_pedido)
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
            R$ {{ number_format($item_pedido->product->prod_preco_prof, 2, ',', '.')}}
          </td>       



          @php
          $total_produto = $item_pedido->total;
          $total_pedido += $total_produto;

          @endphp

          <td  id="center">
           R$ {{ number_format($total_produto, 2, ',', '.') }}

         </td>
         <td title="Remover Item" id="center" > 


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
   <input type="hidden" name="pagamentoIsset" id="pagamentoIsset">
    <input type="hidden" name="vendedor_id" value="{{$pedido->Cliente->vendedor_id}}">
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
</form>
@empty
@forelse ($freteB_PF as $freteBoy)

  @php
  $total_pedido =0;
  @endphp
  @foreach ($pedido->itens_pedido_BPF as $item_pedido)
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

  @foreach($valorFrete as $total_frete)
  @php
  $total_produto = $item_pedido->total;  
  $total_pedido += $total_produto; 
  $custo_frete = $total_frete->valor;
  
  @endphp
  @endforeach

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

</tbody>    
</table>
</div>
<div class="divider"></div> 
@php
$total_pedido +=$custo_frete;
@endphp
<h4>Total Geral: R$ {{ number_format($total_pedido, 2, ',', '.') }}</h4>


<div class="divider" style="margin-top: -10px;"></div>
<br>

  <form method="POST" action="{{ route('pedido.concluir') }}">
  {{ csrf_field() }}
  <input type="hidden" name="pedido_id" value="{{ $pedido->id }}">
   <input type="hidden" name="pagamentoIsset" id="pagamentoIsset">
   <input type="hidden" name="localIsset" id="localIsset">
    <input type="hidden" name="vendedor_id" value="{{$pedido->Cliente->vendedor_id}}">

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
 <script type="text/javascript">

 $(document).click(function(){     

        $(".local").on("input", function(){
            var textoDigitado = $(this).val();
            var inputCusto = $(this).attr("localIsset");
            $("#"+ inputCusto).val(textoDigitado);
          
        });
      
    });

 

</script>

 </button>   
</form>
@empty
@forelse($freteB_PJ as $freteBoy)

  @php
  $total_pedido =0;
  @endphp
  @foreach ($pedido->itens_pedido_BPJ as $item_pedido)
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
    R$ {{ number_format($item_pedido->product->prod_preco_prof, 2, ',', '.')}}
  </td>

  @foreach($valorFrete as $total_frete)
  @php
  $total_produto = $item_pedido->total;  
  $total_pedido += $total_produto; 
  $custo_frete = $total_frete->valor;
  @endphp
  @endforeach

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

</tbody>    
</table>
</div>
<div class="divider"></div> 
@php
$total_pedido +=$custo_frete;
@endphp
<h4>Total Geral: R$ {{ number_format($total_pedido, 2, ',', '.') }}</h4>

<div class="divider" style="margin-top: -10px;"></div>
<br>

  <form method="POST" action="{{ route('pedido.concluir') }}">
  {{ csrf_field() }}
  <input type="hidden" name="pedido_id" value="{{ $pedido->id }}">
   <input type="hidden" name="pagamentoIsset" id="pagamentoIsset">
    <input type="hidden" name="vendedor_id" value="{{$pedido->Cliente->vendedor_id}}">
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
</form>

@empty

@forelse ($freteC_PF as $freteCorreios)
@php
$total_pedido =0;
@endphp
@foreach ($pedido->itens_pedido_CPF as $item_pedido)
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



@foreach($valorFreteC as $total_frete)
@php
  $total_produto = $item_pedido->total;  
  $total_pedido += $total_produto; 
  $custo_frete = $total_frete->valor;
@endphp
@endforeach

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

</tbody>    
</table>
</div>
@php
$total_pedido +=$custo_frete;
@endphp
<div class="divider"></div>
<h4>Total Geral: R$ {{ number_format($total_pedido, 2, ',', '.') }}</h4>
<div class="divider" style="margin-top: -10px;"></div><br>

  <form method="POST" action="{{ route('pedido.concluir') }}">
  {{ csrf_field() }}
  <input type="hidden" name="pedido_id" value="{{ $pedido->id }}">
   <input type="hidden" name="pagamentoIsset" id="pagamentoIsset">
    <input type="hidden" name="vendedor_id" value="{{$pedido->Cliente->vendedor_id}}">
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
</form>
@empty
@forelse ($freteC_PJ as $freteCorreios)
@php
$total_pedido =0;
@endphp
@foreach ($pedido->itens_pedido_CPJ as $item_pedido)
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
  R$ {{ number_format($item_pedido->product->prod_preco_prof, 2, ',', '.')}}
</td>



@foreach($valorFreteC as $total_frete)
@php
  $total_produto = $item_pedido->total;  
  $total_pedido += $total_produto; 
  $custo_frete = $total_frete->valor;
@endphp
@endforeach

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

</tbody>    
</table>
</div>
@php
$total_pedido +=$custo_frete;
@endphp
<div class="divider"></div>
<h4>Total Geral: R$ {{ number_format($total_pedido, 2, ',', '.') }}</h4>
<div class="divider" style="margin-top: -10px;"></div><br>

  <form method="POST" action="{{ route('pedido.concluir') }}">
  {{ csrf_field() }}
  <input type="hidden" name="pedido_id" value="{{ $pedido->id }}">
   <input type="hidden" name="pagamentoIsset" id="pagamentoIsset">
    <input type="hidden" name="vendedor_id" value="{{$pedido->Cliente->vendedor_id}}">
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
</form>
@empty
<h4>Erro Nenhum frete localizado</h4>
@endforelse

@endforelse

@endforelse

@endforelse

@endforelse

@endforelse


@empty
<p class="lead" id="black" style="margin-left: 15px; margin-top: -20px;">Não há nenhum produto adicionado</p>
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
  @forelse($pedidos as $pedido)
  <input type="hidden" name="obs_pedido" value="{{$pedido->obs_pedido}}">
  <input type="hidden" name="balcao" value="{{$pedido->Frete->balcao}}">
  <input type="hidden" name="entrega" value="{{$pedido->Frete->entrega}}">

</form>
  @empty

  @endforelse


@push('scripts')
<script type="text/javascript" src="/js/carrinho.js"></script>
@endpush



@endsection