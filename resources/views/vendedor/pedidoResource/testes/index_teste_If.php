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
         <ol class="breadcrumb" style="margin-bottom: 10px;">                     
           <li><a href="{{route('pedido.compras')}}" id="btn" style="text-decoration: none"><b>Pedidos</b></a></li>
           <li class="active">Cadastro</li>
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
           <div class="card-panel">
              <form method="POST" action="{{ route('carrinho.adicionar') }}">
                {{ csrf_field() }}
               <div class="row">
                  <div class="col-md-5">
                   @forelse ($pedidos as $pedido)
                    <div class="input-field">        
                      <select id="clientes" name="id_cliente">       
                       <option></option>
                        <option value="{{$pedido->Cliente->id}}">{{$pedido->Cliente->id}}. {{$pedido->Cliente->name}}. cel:{{$pedido->Cliente->cel}}</option>        
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
                      <option value="{{$dc->id}}">{{$dc->id}}. {{$dc->name}}. cel:{{$dc->cel}}</option>
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
                    <!--<div class="col-md-2">
                           <div class="input-field">        
                            <input type="text" class="form-control" name="pedido_cod" value="{{$pedido->pedido_cod}}" maxlength="5" title="Cupom de descontos" onkeypress="mascara( this, mnum );" >       
                                <label for="pedido_cod" style="font-size: 15px;">Código Promocional</label>
                            </div>
                        </div>-->

                     <div class="col-md-6">           
                     <div class="input-field">              
                     <input type="text" name="obs_pedido" title="Informe as observações gerais do pedido" placeholder="Observações gerais do pedido" value="{{$pedido->obs_pedido}}" placeholder="{{$pedido->obs_pedido}}" autofocus/>
                     <label for="obs_pedido" style="font-size: 15px;">Observações</label>
                     </div> 
                    </div>
                    <div class="col-md-12"><div class="divider"></div></div>
    <div class="col-md-10">
       <p style="color: #9e9e9e; font-size: 15px; margin-left: 4px; margin-top: 5px;"><b>Detalhes do frete</b></p> 
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
      @forelse ($retiradaBalc as $retirada)
<label>
    <input type="checkbox" name="balcao" id="isAgeSelected" value="{{$retirada->balcao}}" {{ $retirada->balcao == 'Y' ? 'checked' : '' }} /><span style="color: #4db6ac; font-size: 12px;  margin-left: 4px; margin-top: -10px;">RETIRADA BALCÃO</span>
</label>
</div>
  <div class="col-md-12"></div>
@empty

@forelse ($freteB as $freteBoy)
      <div name="op2" class="col-md-5">
        <label>
        <input type="radio" name="entrega" value="{{$freteBoy->entrega}}" {{ $freteBoy->entrega == 'B' ? 'checked' : '' }} />
        <span style="font-size: 12px;  margin-left: -15px;" >ENTREGA MOTOBOY</span><!--color: #4dd0e1;  --></label>
            <label>
        <input type='text' name="valor" style="margin-left: -5px;" value="{{ number_format($freteBoy->valor, 2, ',', '.') }}" axlength='15' pattern='([0-9]{1,3}\.)?[0-9]{1,3},[0-9]{2}$' title="{{ number_format($freteBoy->valor, 2, ',', '.') }}" placeholder="R$ Custo do frete" onkeypress='mascara( this, mvalor );' readonly="readonly"/> 
        </label>
              <label>
        <input type="checkbox" onclick="myFunction()" name="local" value="Y" /><span style="font-size: 12px;  margin-left: -8px; " title="Alterar endereço de entrega">ALTERAR LOCAL DA ENTREGA</span>
      </label>
     <button type="button" style="display:none" id="myCheck" name="op1"  data-toggle="modal" data-target="#myModal2"></button> 
        @empty
        @forelse ($freteC as $freteCorreios)
       <label>
        <input type="radio" name="entrega" value="{{$freteCorreios->entrega}}" {{ $freteCorreios->entrega == 'C' ? 'checked' : '' }} />
        <span style="font-size: 12px;">ENTREGA CORREIOS</span><!--color: #ffd54f;  -->
      </label> 
         <label>
        <input type='text' name="valor" style="display:none; margin-left: -6px; " maxlength='15' pattern='([0-9]{1,3}\.)?[0-9]{1,3},[0-9]{2}$' title="Informe o custo do frete" placeholder="R$ Custo do frete" onkeypress='mascara( this, mvalor );'> 
        </label> 
      <label>
        <input type="checkbox" onclick="myFunction()" name="local" value="Y" /><span style="font-size: 12px;  margin-left: -8px; " title="Alterar endereço de entrega">ALTERAR LOCAL DA ENTREGA</span>
</label>

 
<button type="button" style="display:none" id="myCheck" name="op1"  data-toggle="modal" data-target="#myModal2"></button>
@empty
<h4>Erro Nenhum frete localizado</h4>
@endforelse
@endforelse
</div>
</div>

@endforelse
       @empty
       <!--<div class="col-md-2">
             <div class="input-field">        
        <input type="text" class="form-control" name="pedido_cod" maxlength="5" title="Cupom de descontos" onkeypress="mascara( this, mnum );">       
            <label for="pedido_cod" style="font-size: 15px;">Código Promocional</label>
      </div>
                </div>-->

      
         <div class="col-md-6">           
           <div class="input-field">              
          <input type="text" name="obs_pedido" title="Informe as observações gerais do pedido" placeholder="Observações gerais do pedido" maxlength="255" autofocus/>
      <label for="obs_pedido" style="font-size: 19px;">Observações</label>
      </div>
    </div>
  <div class="col-md-12"><div class="divider"></div></div>
    <div class="col-md-10">
       <p style="color: #9e9e9e; font-size: 15px; margin-left: 4px; margin-top: 5px;"><b>Detalhes do frete</b></p> 
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<label>
    <input type="checkbox" name="balcao" id="isAgeSelected" checked="checked" value="Y" /><span style="color: #4db6ac; font-size: 12px;  margin-left: 4px; margin-top: -10px;">RETIRADA BALCÃO</span>
</label>     
      <div name="op2" style="display:none" class="col-md-5">
        <label>
        <input type="radio" name="entrega" value="B" />
        <span style="font-size: 12px;  margin-left: -15px;" >ENTREGA MOTOBOY</span><!--color: #4dd0e1;  -->
      </label>   
       <label>
        <input type="radio" name="entrega" value="C" />
        <span style="font-size: 12px;">ENTREGA CORREIOS</span><!--color: #ffd54f;  -->
      </label> 
         <label>
        <input type='text' name="valor" style="display:none; margin-left: -6px; " maxlength='15' pattern='([0-9]{1,3}\.)?[0-9]{1,3},[0-9]{2}$' title="Informe o custo do frete" placeholder="R$ Custo do frete" onkeypress='mascara( this, mvalor );'> 
        </label> 
      <label>
        <input type="checkbox" onclick="myFunction()" name="local" value="Y" /><span style="font-size: 12px;  margin-left: -8px; " title="Alterar endereço de entrega">ALTERAR LOCAL DA ENTREGA</span>
</label>

 
<button type="button" style="display:none" id="myCheck" name="op1"  data-toggle="modal" data-target="#myModal2"></button>          
                   
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

 <!-- Modal -->
  <div class="modal fade" id="myModal2" role="dialog">
    <div class="modal-dialog">    
      <!-- Modal content-->      
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Alterar endereço de entrega</h4>
        </div>
        <div class="modal-body">
          <div class="input-field">
            <input type="text" pattern="[0-9]{5}-[0-9]{3}" id="cep" value="{{ old('cep') }}" title="Informe o CEP. Consulta automática Ex:00000-000" onkeypress='mascara( this, mcep );' maxlength="9" name="cep" placeholder="Forneça o CEP" />
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
            <input type="text" title="Informe o Complemento" placeholder="Forneça o Complemento" maxlength="12" name="complemento"  value="{{ old('complemento') }}" />
            <label for="complemento" style="font-size: 15px;">Complemento</label>
          </div>

             <div class="input-field">
            <input type="text" id="cidade" title="Informe a Cidade" maxlength="191" name="cidade"  placeholder="Forneça o nome da Cidade"  value="{{ old('cidade') }}"/>
            <label for="cidade" style="font-size: 15px;">Cidade</label>     
                     

            <select name="estado" class="form-control" id="estado" title="Informe o Estado" style=" width: 200px;  float: left;"/>
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
          </select>
  </div>   
        </div><br>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>

    </div>

  </div> 
</div>
 </div> 

      @endforelse
</div>

<hr style="margin-top: -8px;">
<div class="row">    
    <div class="col-md-5">  
        <div class="input-field">          
          <select id="produtos" name="id" required="required"> <!--onchange="location = this.value;"-->
            @foreach($registros as $registro)
            <option></option>
            <option value="{{ $registro->id }}">{{$registro->prod_cod}}. {{$registro->prod_desc}}.   
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
           @forelse ($retiradaBalc as $retirada)
           <th id="center" title="Preço Balcão Unitário"><p style="color: #4db6ac; font-size: 10px;">BALCÃO</p> Preço Unitário</th>
            <th id="center">Total</th>
           <th id="center" style=" width: 100px;" >Acão</th>
           @empty
            <th id="center" title="Preço Padrão Unitário"><p style="color: #4db6ac; font-size: 10px;">PADRÃO</p> Preço Unitário</th>
           <!--<th id="center" title="Preço Profissionais Unitário">Profissionais Preço Unitário</th>-->
           <th id="center">Total</th>
           <th id="center" style=" width: 100px;" >Acão</th>
           @endforelse
         </tr>

       </thead> 
       <tbody> 
        @if (isset($retirada))

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


@elseif (isset($freteB))


        @php
        $total_pedido =0;
        @endphp
        @foreach ($pedido->itens_pedido_B as $item_pedido)
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
        $total_pedido += $total_frete->valor;
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

  <h4>Total Geral: R$ {{ number_format($total_pedido, 2, ',', '.') }}</h4>

<div class="divider" style="margin-top: -10px;"></div>
<br>


@else ( empty($retirada) && isset($freteC) == 'C'  )



        @php
        $total_pedido =0;
        @endphp
        @foreach ($pedido->itens_pedido_C as $item_pedido)
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
        $total_pedido += $total_frete->valor;
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

 @endif

  <form method="POST" action="{{ route('pedido.concluir') }}">
    {{ csrf_field() }}
    <input type="hidden" name="pedido_id" value="{{ $pedido->id }}">
    <button type="submit" class="btn waves-effect waves-light  blue darken-2">
     <strong>Salvar</strong>
    </button>   
  </form>


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
</form>

@push('scripts')
<script type="text/javascript" src="/js/carrinho.js"></script>
@endpush



@endsection