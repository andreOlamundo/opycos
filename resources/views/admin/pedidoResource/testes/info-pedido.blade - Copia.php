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
			<h2>Detalhes do Pedido</h2> 
			<div class="divider" style="margin-bottom: 3px; margin-top: -8px;" ></div>
			<div class="row">
				<div class="col-md-12">         

					<ol class="breadcrumb" style="margin-bottom: 5px;">
						<li><a href="{{route('pedido.compras')}}" id="btn" style="text-decoration: none"><b>Pedidos</b></a></li>
						<li><a href="{{route('index')}}" id="btn" style="text-decoration: none"><b>Novo Pedido</b></a></li>
						<li class="active">Detalhes</li>
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
						<p class="lead" id="black" style="color: #4db6ac;  margin-top: -10px; margin-bottom: 8px;"> Pedido: <span class="chip">{{ $pedidos->id }}</span>
							Gerado: {{ $pedidos->created_at->format('d/m/Y H:i') }}<br> Atualizado: {{ $pedidos->updated_at->format('d/m/Y H:i') }}  </p>              
							<div class="row" style="margin-bottom: -10px;">
								<form method="POST" action="{{ route('carrinho.adicionar') }}">
									{{ csrf_field() }}               

									<div class="col-md-5">             
										<label style="font-size: 12px; margin-top: -10px;">CLIENTE</label>
										<select id="clientes" name="id_cliente" class="form-control">      
											<option value="{{$pedidos->Cliente->id}}" title="doc:{{$pedidos->Cliente->cnpj}}{{$pedidos->Cliente->cpf}}">{{$pedidos->Cliente->id}}. {{$pedidos->Cliente->name}}. cel:{{$pedidos->Cliente->celInput}}</option>        
										</select>

										<input type="hidden" name="id_cliente" value="{{$pedidos->Cliente->id}}">

									</div>

									<div class="col-md-12"><div class="divider" style="margin-top: 5px;"></div><br></div>

									<div class="col-md-6">         
										<div class="input-field">              
											<input type="text" name="obs_pedido" class="obspedido" obspedidoDetalhes='obspedidoDetalhes' title="Informe as observações gerais do pedido" placeholder="Observações gerais do pedido." value="{{$pedidos->obs_pedido}}" readonly="readonly" />
											<label for="obs_pedido" style="font-size: 15px;">OBSERVAÇÕES</label>
										</div> 
									</div>
								</form>

										<button  onclick="myFunction()" title="Outras informações do Pedido" class="btn-floating btn-large waves-effect waves-light pull-right amber">
			<i class="material-icons" style="font-size: 30px;">info_outline</i>   
			<script type="text/javascript">
				function myFunction() {
					document.getElementById("myCheck").click();
				}

			</script>
		</button> 

							</div>

						</div>

					</div>
				</div>

				<div class="table-responsive" style="margin-top: -15px;">
					<table class="table table-striped table-bordered table-condensed table-hover" >
						<thead>
							<tr style="color: #4db6ac;  background-color: #fcf8e3;"><th id="center" colspan="6"> PRODUTOS</th></tr>
							<tr class="warning">                    
								<th id="center">Código</th>
								<th id="center">Produto</th>
								<th id="center">Qtd</th>              
								<th id="center" title="Preço PADRÃO">Preço Unitário</th>
								<th id="center">Total</th>
							</tr>
						</thead> 

						<tbody> 
							@php
							$total_pedido =0;
							@endphp

							@foreach ($pedidos->itens_pedido as $item_pedido)
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
  <!-- <td title="Ações" id="center" > 


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

  </td>-->
</tr>


@endforeach

<tr><td colspan="7"><b>Total: R$ {{ number_format($total_pedido, 2, ',', '.') }}</b></td></tr>

</tbody>    
</table>
</div>

<div class="table-responsive">
	<table class="table table-striped table-bordered table-condensed table-hover" >

		<thead>
			<tr><th id="center" colspan="6" style="color: #4db6ac;  background-color: #fcf8e3;"> REQUISIÇÕES</th></tr>
			<tr class="warning">                    
				<th id="center">Código</th>
				<th id="center">Requisição</th>
				<th id="center">Qtd</th>              
				<th id="center" title="Preço PADRÃO">Preço Unitário</th>
				<th id="center">Total</th>

			</tr>
		</thead> 

		<tbody> 


			@php
			$total_pedido =0;
			@endphp

			@foreach ($pedidos->itens_pedido_request as $item_pedido)
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

   <!--<td title="Ações" id="center"> 
    <a href="#" onclick="carrinhoRemoverRequest({{ $pedidos->id}}, {{ $item_pedido->request_id }}, 1)"
      data-toggle="tooltip" 
      data-placement="top"
      title="Remover" class="btn-floating btn-small waves-effect waves-light red"><i class="material-icons">remove_circle_outline</i>
    </a> &nbsp; 



    <a href="#" onclick="carrinhoAdicionarRequest({{ $item_pedido->request_id }})" 
      data-toggle="tooltip" 
      data-placement="top"
      title="Adicionar" class="btn-floating btn-small waves-effect waves-light btn-primary"><i class="material-icons">add_circle_outline</i></a>

      <a href="#" onclick="carrinhoRemoverRequest({{ $pedidos->id}}, {{ $item_pedido->request_id }}, 0)" class="waves-effect btn-Tiny secondary-content">Remover Item</a>
  </td>-->

</tr>



@endforeach

<tr><td colspan="7"><b>Total: R$ {{ number_format($total_pedido, 2, ',', '.') }}</b></td></tr>



</tbody>    
</table>
</div>



@foreach ($pedidos->itens_pedido as $item_pedido)

@php
$total_produto = $item_pedido->total;
$total_pedido += $total_produto;          
@endphp
@endforeach

<div class="divider" style="margin-bottom: 5px;"></div>

<p class="lead" id="black">Total Geral: R$ {{ number_format($total_pedido, 2, ',', '.') }}</p>



<div class="divider" style="margin-bottom: 10px; margin-top: -15px;"></div>

<div class="row">
	<div class="col-md-6">
		<a href="{{route('pedido.allcompras')}}" class="btn btn-default"><b>VOLTAR</b></a>
 


		<!--<form method="POST" action="{{ route('carrinho.cancelar') }}" style="display: inline;">     
			{{ csrf_field() }}  
			<input type="hidden"  name="id[]" value="{{ $pedidos->id }}" />    
			<button type="submit" title="Cancelar Pedido" onclick="return confirm('Tem certeza que deseja Cancelar o pedido?. Após a Cancelamento não será possível realizar outras alterações, se estiver em dúvida clique em VOLTAR!')" class="btn waves-effect waves-light  red darken-2">
				<strong>Cancelar</strong>   </button>  
			</form>

			<form method="POST" action="{{ route('carrinho.finalizar') }}" style="display: inline;">     
				{{ csrf_field() }}  
				<input type="hidden"  name="id[]" value="{{ $pedidos->id }}" />    
				<button type="submit" title="Finalizar Pedido" onclick="return confirm('Tem certeza que deseja Finalizar o pedido?. Após a finalização não será possível realizar outras alterações, se estiver em dúvida clique em VOLTAR!')" class="btn waves-effect waves-light  green darken-2">
					<strong>Finalizar</strong> </button>  
				</form>-->

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

				<form method="POST" action="{{route('pedidos.detalhes', $pedidos->id)}}"> 
					{{ csrf_field() }} 

					<input type="hidden" name="id_cliente" value="{{$pedidos->id_cliente}}">
					<input type="hidden" name="user_id" value="{{$pedidos->user_id}}">
					<input type="hidden" name="vendedor_id" value="{{$pedidos->Cliente->vendedor_id}}">
					<input type="hidden" name="obs_pedido"  id="obspedidoDetalhes" value="{{$pedidos->obs_pedido}}">
					<!-- Modal content-->   

					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h2>Outras informações do Pedido</h2>

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


                	});
                </script>

                <label>
                	<input type="checkbox" name="balcao" id="balcao" disabled value="{{$pedidos->Frete->balcao}}" {{ $pedidos->Frete->balcao == 'Y' ? 'checked' : '' }} /><span style="font-size: 13px; margin-top: 2px;">Retirada Balcão</span>
                </label>  


                <div name="op2">


                	<label for="valor" style="font-size: 13px;" class="col-md-4" >Custo Frete
                		<input type='text' name="valor" class="money" value="R$ {{ number_format($pedidos->Frete->valor, 2, ',', '.') }}" maxlength="6" pattern='([0-9]{1,3}\.)?[0-9]{1,3},[0-9]{2}$' title="Informe o custo do frete R$ 0,00" placeholder="R$ 0,00" readonly></label>

                	
                		@if ($pedidos->Frete->entrega == 'B' || $pedidos->Frete->entrega == NULL )

                		@else
                		<label for="prazo_entrega" style="font-size: 13px;" class="col-md-4" >Prazo de entrega
                        <input type='text' name="prazo_entrega"  value="{{$pedidos->Frete->prazo_entrega}} Dias" id="prazo_entrega"  title="Prazo de entrega" placeholder="nº Dias" readonly>
                      </label>

                		@endif


                	<label>
                		<input type="radio" name="entrega" class="with-gap" disabled value="{{$pedidos->Frete->entrega}}" {{ $pedidos->Frete->entrega == 'B' ? 'checked' : '' }} />
                		<span style="font-size: 13px;" >Entrega: MOTO BOY</span><!--color: #4dd0e1;  -->
                	</label>   
                	<label>
                		<input type="radio" name="entrega" class="with-gap" disabled value="{{$pedidos->Frete->entrega}}" {{ $pedidos->Frete->entrega == 'C' ? 'checked' : '' }} />
                		<span style="font-size: 13px;">Entrega: CORREIOS</span><!--color: #ffd54f;  -->
                	</label> 

                	 <button type="button" onclick="LoadFrete();" id="textbox" disabled class="btn btn-small waves-effect amber pull-right" style="margin-top: 12px;">Calcular frete</button>
					
					<div class="col-md-2 pull-right">
                 <div class="input-field">   
                     <select name="cdservico" class="form-control" id="textbox1" disabled title="Escolha o Serviço" style=" width: 170px;  float: right;">
                     <option value="" >
                     	@if ($pedidos->Frete->serviço_correio != NULL)
                     	{{$pedidos->Frete->serviço_correio == '04014' ? 'SEDEX à vista' : ''}} {{$pedidos->Frete->serviço_correio == '04510' ? 'PAC à vista' : ''}} {{$pedidos->Frete->serviço_correio == '04782' ? 'SEDEX 12 ( à vista)' : ''}}
                    	{{$pedidos->Frete->serviço_correio == '04790' ? 'SEDEX 10 (à vista)' : ''}}
                  		{{$pedidos->Frete->serviço_correio == '04804' ? 'SEDEX Hoje à vista' : ''}}
                  	@else  Serviços Correios @endif</option>
                    
                     
                   
                    
                     </select>
                

                               
                    
           
                           
                    </div>

   </div>





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


           </div><br>
      



       <div name="balcaoempty">                                
       	<label>
       		<input type="checkbox" name="local" disabled  value="{{$pedidos->Frete->local}}" {{ $pedidos->Frete->local == 'Y' ? 'checked' : '' }}   /><span style="font-size: 13px;" title="Caso o endereço de cadastro seja diferente do endereço de entrega">Alterar Local da Entrega</span>
       	</label>
       </div>

       <div name="localisset">

       	<div class="input-field">
       		<input type="text" pattern="[0-9]{5}-[0-9]{3}" id="cep" value="{{$pedidos->Frete->cep}}" title="Informe o CEP. Consulta automática Ex:00000-000" maxlength="9" name="cep" placeholder="Forneça o CEP" readonly />
       		<label for="cep" style="font-size: 15px;">Cep</label>
       	</div>
       	<div class="input-field">

       		<input type="text" id="endereço" title="Informe o Endereço" maxlength="191" placeholder="Forneça o endereço" name="endereço"  value="{{$pedidos->Frete->endereço}}" readonly/>
       		<label for="endereço" style="font-size: 15px;">Endereço</label>
       	</div>
       	<div class="input-field">
       		<input type="text" title="Informe o Número" maxlength="8" name="numero" value="{{$pedidos->Frete->numero}}" placeholder="Forneça o número" readonly/>
       		<label for="numero" style="font-size: 15px;">Número</label>
       	</div>
       	<div class="input-field">
       		<input type="text"  id="bairro" title="Informe o Bairro" placeholder="Forneça o Bairro" maxlength="191" name="bairro" value="{{$pedidos->Frete->bairro}}" readonly/>
       		<label for="bairro" style="font-size: 15px;">Bairro</label>
       	</div>
       	<div class="input-field">
       		<input type="text" title="Informe o Complemento" placeholder="Forneça o Complemento" maxlength="191" name="complemento"  value="{{$pedidos->Frete->complemento}}" readonly/>
       		<label for="complemento" style="font-size: 15px;">Complemento</label>
       	</div>

       	<div class="input-field">
       		<input type="text" id="cidade" title="Informe a Cidade" maxlength="191" name="cidade"  placeholder="Forneça o nome da Cidade"  value="{{$pedidos->Frete->cidade}}" readonly/>
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

    <div class="divider"></div>
              <br>
       
       	<p style="color: #9e9e9e; font-size: 12px; margin-left: 4px; margin-top: 5px;"><b>FORMAS DE PAGAMENTO</b></p>
       	<label>
       		<input type='radio' name='pagamento' disabled value="{{$pedidos->pagamento}}" {{ $pedidos->pagamento == 'D' ? 'checked' : '' }}>
       		<span style="font-size: 12px;">Dinheiro</span> <!--Dinheiro-->
       	</label>
       	<label>
       		<input type="radio" name="pagamento" disabled value="{{$pedidos->pagamento}}" {{ $pedidos->pagamento == 'CC' ? 'checked' : '' }}>
       		<span style="font-size: 12px;" >Cartão de Crédito</span>
       	</label>
       	<label>
       		<input type="radio" name="pagamento" disabled value="{{$pedidos->pagamento}}" {{ $pedidos->pagamento == 'CD' ? 'checked' : '' }}>
       		<span style="font-size: 12px;" >Cartão de Débito</span>
       	</label>
       	<label>
       		<input type="radio" name="pagamento" disabled value="{{$pedidos->pagamento}}" {{ $pedidos->pagamento == 'BB' ? 'checked' : '' }}>
       		<span style="font-size: 12px;" >Boleto Bancário</span>                         
       	</label>


      
   </div>


       <div class="modal-footer">
       	
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Voltar</button>      
               
        
        </div>

    </form>



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

	<input type="hidden" name="obs_pedido" value="{{$pedidos->obs_pedido}}">
	<input type="hidden" name="id_cliente" value="{{$pedidos->id_cliente}}">


</form>



@push('scripts')
<script type="text/javascript" src="/js/carrinho.js"></script>
@endpush

@endsection