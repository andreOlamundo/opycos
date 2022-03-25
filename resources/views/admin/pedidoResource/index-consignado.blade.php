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
					<h2>Novo Pedido Consignado</h2>
					@forelse ($pedidos as $pedido)

					@empty
					<a href="{{route('index')}}" 
            class="btn btn-small waves-effect waves-light  blue darken-2 pull-right" style="margin-top: -35px; width: 150px; height: 25px; padding: 2px 1px;" title="Novo pedido comum">
            <i class="material-icons">add</i> <b>Pedido Comum</b></a>  
					@endforelse 
										
				</div>
			</div>

			<div class="row" style="height: 80px; width: 1170px; position: fixed; z-index: 1001; top: 93px;">
				<div class="col-md-12">  
          <!--<ol class="breadcrumb" style="margin-bottom: 5px;">                       
            <li><a href="{{route('pedido.compras')}}" id="btn" style="text-decoration: none"><b>Pedidos</b></a></li>
            <li class="active"><b>Novo Pedido</b></li>
        </ol>-->
        @if (Session::has('mensagem-sucesso'))
        <div class="alert alert-success alert-dismissible fade in" style="margin-bottom: 1px;">
        	<strong>{{ Session::get('mensagem-sucesso') }}</strong>
        	<a href="#" class="close" 
        	data-dismiss="alert"
        	aria-label="close">&times;</a>
        </div>
        <script type="text/javascript">
        	$(".alert-dismissible").fadeTo(5000, 500).slideUp(500, function(){
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
        	$(".alert-dismissible").fadeTo(5000, 500).slideUp(500, function(){
        		$(".alert-dismissible").alert('close');
        	});
        </script>
        @endif

        <div class="card-panel" style="height: 130px; padding: 10px 10px;"><!-- style="padding: 15px 10px; "-->

     <div class="row">
    <form method="POST" action="{{ route('carrinho.adicionarConsig') }}">
    {{ csrf_field() }}
    @forelse ($pedidos as $pedido)   
    <div class="col-md-6" style="margin-right: -35px;"> 
    <div class="input-field"  style="margin-top:-8px;">
	<section>      
        						<label style="font-size: 12px;">Vendedor</label>
        						<select id="vendedor" name="vendedor" class="form-control" title="{{$pedido->Vendedor->name}} &hybull; @if (isset($pedido->Vendedor->cnpj))cnpj:{{$pedido->Vendedor->cnpj}}@else cpf:{{$pedido->Vendedor->cpf}} @endif">       
        							<option></option>
        							<option value="{{$pedido->Vendedor->id}}" title="{{$pedido->Vendedor->name}} &hybull; @if (isset($pedido->Vendedor->cnpj))cnpj:{{$pedido->Vendedor->cnpj}}@else cpf:{{$pedido->Vendedor->cpf}} @endif">{{$pedido->Vendedor->id}} &hybull; {{$pedido->Vendedor->name}} &hybull; {{$pedido->Vendedor->cnpj}} {{$pedido->Vendedor->cpf}} &hybull; {{$pedido->Vendedor->cel}}</option>        
        						</select>


        						<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
        						<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
        						<script type="text/javascript">
        							$("#vendedor").select2({
        								placeholder:' {{$pedido->Vendedor->id}} - {{$pedido->Vendedor->name}} - {{$pedido->Vendedor->cpf}} {{$pedido->Vendedor->cnpj}} - {{$pedido->Vendedor->cel}}'
        							});
        						</script>	
        						<input type="hidden" name="vendedor_id" value="{{$pedido->vendedor_id}}">
	</div>

        				


   <div class="input-field" style="margin-top: 30px;">         
   	<select id="produtos" name="id"> <!--onchange="location = this.value;"-->
   		@foreach($registros as $registro)
   		<option></option>
   		<option value="{{ $registro->id }}" title="Padrão R$ {{number_format($registro->prod_preco_padrao, 2,',','.')}}">{{$registro->prod_cod}} &hybull; {{$registro->prod_desc}} &hybull; R$ {{number_format($registro->prod_preco_padrao, 2,',','.')}} 
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
	@forelse ($pedidos as $pedido)
	<div class="col-md-3">          
		<label style="font-size: 12px;">Pedido Número:<input type="text" readonly value="{{ $pedido->id }}"> 
		</div></label>
		<div class="col-md-3">  	 
			<label style="font-size: 12px;">Data do pedido<input type="text" readonly value="{{ $pedido->created_at->format('d/m/Y') }}"  title="{{ $pedido->created_at->format('d/m/Y H:i') }}"> </label>      

		</div>

		<div class="col-md-2">
	<label style="font-size: 12px;">Comissão(%)
			<input type="text" title="Vendedor" readonly value="{{$pedido->Vendedor->comissao}}%"></label>
</div>

			@empty
			@endforelse 
		</div>
		<!--<div class="col">
			<div class="col-md-3">

				<label style="font-size: 12px;">Desconto
					<input type="text" name="desconto_request" class="percent"  title="Desconto de 1% à 100%" placeholder="0%"> </label> 
				</div>
				<div class="col-md-3">
					<label style="font-size: 12px;">Qtd
						<input type="number" placeholder="1" max="50" min="1" pattern="[0-9]+$" name="quantidade_request"></label>

					</div>

					<button type="submit" class="btn btn btn-primary float-right" style="margin-top: 15px;" data-position="top" data-delay="50" data-toggle="tooltip" data-placement="bottom" title="Adicionar Requisições"><i class="fa fa-plus"></i>
						Adicionar
					</button>
				</div>-->
				<div class="col">
							<div class="col-md-3">
				<label style="font-size: 12px;">Desconto
					<input type="text" name="desconto_produto_reais" onclick="AlterDescontoProdReais();" class="money" pattern='([0-9]{1,3}\.)?[0-9]{1,3},[0-9]{2}$' id="moneyProd"  title="Desconto em Reais 'R$0,00'" placeholder="R$ 0,00"  value=""> </label> 
				</div>

					<div class="col-md-3">           
						<label style="font-size: 12px;">Desconto
							<input type="text" name="desconto_produto"  onclick="AlterDescontoProdPercent();" id="percentProd" class="percent"  title="Desconto de 1% à 100%" placeholder="0%" value=""> </label> 
						</div>
						<div class="col-md-3">
							<label style="font-size: 12px;">Qtd
								<input type="number" placeholder="1" max="50" min="1" pattern="[0-9]+$" name="quantidade_produto"></label>

							</div>

							<button type="submit" class="btn btn-small waves-effect btn-primary float-right" style="margin-top: 20px; width: 120px; height: 25px; padding: 2px 1px;" data-position="top" data-delay="50" data-toggle="tooltip" data-placement="bottom" title="Adicionar Produto"><i class="fa fa-plus"></i>
								Adicionar
							</button>


						</div>
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
					@empty

	<div class="col-md-6">
       
	<section>   
	<div class="input-field" style="margin-top: 20px;">      
	<select id="vendedor_id" required class="id_vendedorsearch" name="vendedor_id"> <!--onchange="location = this.value;"-->
		@foreach($dadosVendedores as $registro)
		<option></option>
		<option value="{{ $registro->id }}" title="{{$registro->name}} {{$registro->cel}}">{{$registro->id}} &hybull; {{$registro->name}}
		</option>
		@endforeach     
	</select>
	<label style="font-size: 12px; margin-top: -33px;">Vendedor</label>	
</div>
	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>      
<script type="text/javascript">
	$("#vendedor_id").select2({
		placeholder:'---Selecione o Vendedor---'
		
	});
</script> 

<script type="text/javascript">
		

								$(document).on('change','.id_vendedorsearch',function(){
										var prod_id=$(this).val();
										var a=$(this).parent();
										console.log(prod_id);
										var op="";

										$.ajax({
											type:'get',
											url:'{!!URL::to('findVendC') !!}',
											data:{'id':prod_id},
											datatype:'html',
											success:function(data){
												console.log("comissao");
												console.log(data.comissao);
												$('#comissao').val(data);
											},
											erro:function(){

											}

										});

									});


</script>

<input type="hidden" name="comissao" id="comissao" value="">

</section>
						

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
	<div class="col">
	<div class="col-md-3">          
		<label style="font-size: 12px;">Pedido Número:<input type="text" readonly value=""></label>
	</div>
	<div class="col-md-3">  	 
		<label style="font-size: 12px;">Data do pedido<input type="text" readonly value="<?php echo date("d/m/Y"); ?>"  title=""> </label>      

	</div>
</div>


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
	<!--<div class="col">
		<div class="col-md-3">

			<label style="font-size: 12px;">Desconto
				<input type="text" name="desconto_request" class="percent"  title="Desconto de 1% à 100%" placeholder="0%"> </label> 
			</div>
			<div class="col-md-3">
				<label style="font-size: 12px;">Qtd
					<input type="number" placeholder="1" max="50" min="1" pattern="[0-9]+$" name="quantidade_request"></label>

				</div>

				<button type="submit" class="btn btn btn-primary float-right" style="margin-top: 15px;" data-position="top" data-delay="50" data-toggle="tooltip" data-placement="bottom" title="Adicionar Requisições"><i class="fa fa-plus"></i>
					Adicionar
				</button>
			</div>-->
			<div class="col">
						<div class="col-md-3">
				<label style="font-size: 12px;">Desconto
					<input type="text" name="desconto_produto_reais" onclick="AlterDescontoProdReais();" class="money" id="moneyProd" pattern='([0-9]{1,3}\.)?[0-9]{1,3},[0-9]{2}$' id="moneyProd"  title="Desconto em Reais 'R$0,00'" placeholder="R$ 0,00"  value=""> </label> 
				</div>
				<div class="col-md-3">           
					<label style="font-size: 12px;">Desconto
						<input type="text" name="desconto_produto" onclick="AlterDescontoProdPercent();" id="percentProd" class="percent" title="Desconto de 1% à 100%" placeholder="0%" value=""> </label> 
					</div>
					<div class="col-md-3">
						<label style="font-size: 12px;">Qtd
							<input type="number" placeholder="1" max="50" min="1" pattern="[0-9]+$" name="quantidade_produto"></label>

						</div>

						<button type="submit" class="btn btn-small waves-effect btn-primary float-right" style="margin-top: 20px; width: 120px; height: 25px; padding: 2px 1px;" data-position="top" data-delay="50" data-toggle="tooltip" data-placement="bottom" title="Adicionar Produto"><i class="fa fa-plus"></i>
							Adicionar
						</button>


					</div>
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


				@endforelse



			</form>








		</div>
	</div>

	@forelse ($pedidos as $pedido)

	@empty

	<p class="lead" id="black" style="margin-bottom: -2px; margin-top: 5px; color: #4db6ac;">Não há nenhum item no pedido.</p>

	@endforelse
</div>
</div>

<div class="row" style="margin-top: 183px;">
	<div class="col-md-12"> 


		@forelse ($pedidos_produto as $pedido)




		<div class="table-responsive">
			<table class="table-sm table-bordered" style="width:100%">

				<thead>
				
					<tr style="background-color: #fcf8e3;">                    
						<th id="center">Código</th>
						<th id="center">Produto</th>
						<th id="center">Qtd</th>              
						<th id="center" title="Preço unitário">Preço Unit</th>
						<th id="center" title="Desconto unitário">Desconto</th>
						<th id="center">Total</th>

						<th id="center">Acões</th>

						<!--<th id="center" title="Preço Profissionais Unitário">Profissionais Preço Unitário</th>-->

					</tr>
				</thead> 

				<tbody> 


					@php
					$total_pedido =0;
					@endphp


					@foreach ($pedido->itens_pedido as $item_pedido )


					<tr> 

						<td>
							{{ $item_pedido->product->prod_cod }}
						</td> 
						<td>
							{{ $item_pedido->product->prod_desc}}
						</td>
						<td>
							{{ $item_pedido->quantidade}}
						</td>

						@php  
						$desconto = $item_pedido->totalDesconto;
						$desconto_ratiado = ($desconto /$item_pedido->quantidade);
						@endphp

						@if ($desconto > 0)
						@php 
						$preco_com_desconto = $item_pedido->product->prod_preco_padrao - $desconto_ratiado;
						@endphp
						<td title="Preço unitário com desconto R$ {{ number_format($preco_com_desconto, 2, ',', '.')}}" >
							<b style="text-decoration: line-through;"> R$ {{ number_format($item_pedido->product->prod_preco_padrao, 2, ',', '.')}}</b>
							<br>
							R$ {{ number_format($preco_com_desconto, 2, ',', '.')}}
						</td>  
						@else 
						<td>
							R$ {{ number_format($item_pedido->product->prod_preco_padrao, 2, ',', '.')}}
						</td>  
						@endif

						@php  
						$total_prod_desconto = $item_pedido->totalDesconto;
						@endphp


						<td title="Desconto unitário: R$ {{ number_format($desconto_ratiado, 2, ',', '.')}} ">  

							@if($desconto > 0) 
							R$ {{ number_format($total_prod_desconto, 2, ',', '.')}}
							@else
							R$ 0,00 
							@endif   



						</td> 




						@if ($desconto > 0)

						@php

						$total_produto = $item_pedido->total;
						$total_desconto = $item_pedido->totalDesconto;

						$total_pedido += $total_produto;
						$total_pedido -= $total_desconto;
						$total_produto_com_desconto = $total_produto - $total_desconto;
						@endphp

						@else
						@php
						$total_produto = $item_pedido->total;
						$total_pedido += $total_produto;          
						@endphp  


						@endif
						<td>



							@if ($desconto > 0)

							R$ {{ number_format($total_produto_com_desconto, 2, ',', '.')}}


							@else
							R$ {{ number_format($total_produto, 2, ',', '.') }}
							@endif


						</td>

						<td id="center"> 


<a href="#" onclick="carrinhoRemoverProduto({{ $pedido->id}}, {{ $item_pedido->produto_id }}, 1)"      
      title="Subtrair" style="color: red; text-decoration: none;"><i class="fa fa-minus-square" aria-hidden="true"></i>
    </a> &nbsp; 

    <a href="#" onclick="carrinhoAdicionarProduto({{ $item_pedido->produto_id }})" 
      data-toggle="tooltip" 
      data-placement="top"
      title="Incluir"  style="color: blue; text-decoration: none;"><i class="fa fa-plus-square" aria-hidden="true"></i></a>&nbsp; 

      <!--<a href="#" onclick="carrinhoRemoverProduto({{ $pedido->id}}, {{ $item_pedido->produto_id }}, 0)" class="waves-effect btn-Tiny secondary-content" style="text-align: center;">Remover Item </a>-->
      <a href="#" onclick="carrinhoRemoverProduto({{ $pedido->id}}, {{ $item_pedido->produto_id }}, 0)"  title="Remover" style="color: red; text-decoration: none;"><i class="fa fa-ban" aria-hidden="true"></i><!--<i class="material-icons" style="font-size: 12px; padding: 0px 0px;">
remove_shopping_cart
</i>--></a>

     
  </td>
</tr>

@endforeach

<tr><td colspan="4" style="text-align: center;"><b></b></td>
	@php
	$total_desconto_pedido = 0;
	@endphp

	@foreach ($pedido->itens_pedido as $item_pedido)
	@php    
	$total_desc_geral = $item_pedido->totalDesconto;
	$total_desconto_pedido -= $total_desc_geral;

	@endphp
	@endforeach

	<td><b style="color: red;">R$ {{ number_format($total_desconto_pedido, 2, ',', '.') }}</td>
		<td><b>R$ {{ number_format($total_pedido, 2, ',', '.') }}</b></td>
	</tr>




</tbody>    
</table>

</div>







@empty




@endforelse


@forelse ($pedidos_request as $pedido)  


<div class="table-responsive">
	<table class="table-sm table-bordered" style="width:100%">

		<thead>
		
			<tr style="background-color: #fcf8e3;">                    
				<th id="center">Código</th>
				<th id="center">Requisição</th>
				<th id="center">Qtd</th>              
				<th id="center" title="Preço unitário">Preço Unit</th>
				<th id="center" title="Desconto unitário">Desconto</th>
				<th id="center">Total</th>
				<th id="center">Acão</th>

				<!--<th id="center" title="Preço Profissionais Unitário">Profissionais Preço Unitário</th>-->

			</tr>
		</thead> 

		<tbody> 


			@php
			$total_pedido =0;
			@endphp




			@foreach ($pedido->itens_pedido_request as $item_pedido)
			<tr> 

				<td>
					 {{ $item_pedido->request->request_cod}}
				</td> 
				<td>
					{{ $item_pedido->request->request_desc}}
				</td>
				<td>
					{{ $item_pedido->quantidade}}

				</td>

				@php  
				$desconto = $item_pedido->totalDesconto;
				$desconto_ratiado = ($desconto /$item_pedido->quantidade);
				@endphp

				@if ($desconto > 0)
				@php 
				$preco_com_desconto = $item_pedido->request->request_valor - $desconto_ratiado;
				@endphp
				<td title="Preço unitário com desconto R$ {{ number_format($preco_com_desconto, 2, ',', '.')}}" >
					<b style="text-decoration: line-through;"> R$ {{ number_format($item_pedido->request->request_valor, 2, ',', '.')}}</b>
					<br>
					R$ {{ number_format($preco_com_desconto, 2, ',', '.')}}

				</td>
				@else 
				<td>
					R$ {{ number_format($item_pedido->request->request_valor, 2, ',', '.')}}
				</td>       
				@endif

				@php  
				$total_request_desconto = $item_pedido->totalDesconto;
				@endphp


				<td title="Desconto unitário: R$ {{ number_format($desconto_ratiado, 2, ',', '.')}} ">  

					@if($desconto > 0)
					R$ {{ number_format($total_request_desconto, 2, ',', '.')}} 
					@else
					R$ 0,00 
					@endif   



				</td>

				@if ($desconto > 0)

				@php

				$total_produto = $item_pedido->total;
				$total_desconto = $item_pedido->totalDesconto;

				$total_pedido += $total_produto;
				$total_pedido -= $total_desconto;
				$total_produto_com_desconto = $total_produto - $total_desconto;
				@endphp

				@else
				@php
				$total_produto = $item_pedido->total;
				$total_pedido += $total_produto;          
				@endphp  


				@endif






				<td>
					@if ($desconto > 0)

					R$ {{ number_format($total_produto_com_desconto, 2, ',', '.')}}


					@else
					R$ {{ number_format($total_produto, 2, ',', '.') }}
					@endif
				</td>

				<td id="center"> 
   <a href="#" onclick="carrinhoRemoverRequest({{ $pedido->id}}, {{ $item_pedido->request_id }}, 1)"
      data-toggle="tooltip" 
      data-placement="top"
      title="Remover" class="btn-floating btn-small waves-effect waves-light red"><i class="material-icons">remove_circle_outline</i>
    </a> &nbsp; 



    <a href="#" onclick="carrinhoAdicionarRequest({{ $item_pedido->request_id }})" data-toggle="tooltip" data-placement="top"
      title="Incluir"  style="color: blue; text-decoration: none;"><i class="fa fa-plus-square" aria-hidden="true"></i></a>&nbsp; 

 <a href="#" onclick="carrinhoRemoverRequest({{ $pedido->id}}, {{ $item_pedido->request_id }}, 0)"        
      title="Subtrair" style="color: red; text-decoration: none;"><i class="fa fa-minus-square" aria-hidden="true"></i></a>


 


      
  </td>

</tr>



@endforeach

<tr><td colspan="4" style="text-align: center;"><b></b></td>
	@php
	$total_desconto_pedido = 0;
	@endphp

	@foreach ($pedido->itens_pedido_request as $item_pedido)
	@php

	$total_desc_geral = $item_pedido->totalDesconto;
	$total_desconto_pedido -= $total_desc_geral;

	@endphp
	@endforeach

	<td>
		<b style="color: red;">
			R$ {{ number_format($total_desconto_pedido, 2, ',', '.') }}
		</b>
	</td>
	<td id="center">
		<b>
			R$ {{ number_format($total_pedido, 2, ',', '.') }}
		</b>
	</td>
</tr>



</tbody>    
</table>
</div>










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








  <!--<p class="lead" id="black" style="margin-top: 5px; margin-left: -190px;  display:inline-block;">Total de Itens: R$ {{ number_format($total_pedido, 2, ',', '.') }} + Custo Frete: ( R$
  </p>

 

  
  <div class="col-md-2"  style="display:inline-block;">
  <input type='text' name="valor" style="display:none; margin-left: 420px; margin-top: 5px;" maxlength='6' pattern='([0-9]{1,3}\.)?[0-9]{1,3},[0-9]{2}$' title="Informe o custo do frete" placeholder="0,00" onkeypress='mascara( this, mvalor );'>
  </div>

  <p class="lead" id="black" style="margin-top: 5px; margin-left: 160px;  display:inline-block;">) Total Pedido: R$ {{ number_format($total_pedido, 2, ',', '.') }}
  </p>-->
  <div class="divider" style="margin-bottom: 0px; margin-top: 5px;"></div>
  @empty
  @endforelse


  @forelse($pedidos as $pedido)
  @php
  $total_geral =0;

  @endphp
  @foreach($pedido->itens_pedido as $item_pedido)
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

  @foreach($pedido->itens_pedido_request as $item_pedido)
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



  <p class="lead" id="black" style="text-align: right;">TOTAL: R$ {{ number_format($total_geral, 2, ',', '.') }}</p>



 <div class="divider" style="margin-bottom: 10px; margin-top: -20px;"></div>




  @empty

  @endforelse
  @forelse($pedidos as $pedido)


  <button  onclick="myFunction()" class="btn waves-effect amber pull-right">
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

		<form method="POST" action="{{route('pedidos.detalhesConsig', $pedido->id)}}"> 
			{{ csrf_field() }} 

			<input type="hidden" name="id_cliente" value="{{$pedido->id_cliente}}">
			<input type="hidden" name="user_id" value="{{$pedido->user_id}}">
			<input type="hidden" name="vendedor_id" value="{{$pedido->vendedor_id}}">
			<input type="hidden" name="obs_pedido"  id="obspedidoDetalhes" value="{{$pedido->obs_pedido}}">
			<input type="hidden" name="comissao" value="{{$pedido->Vendedor->comissao}}">
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



                <p style="color: #9e9e9e; font-size: 12px;"><b>DETALHES DO FRETE</b></p> 

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


                });
            </script>             
            <label>
            	<input type="checkbox" name="balcao" id="balcao" checked="checked" value="Y" /><span style="font-size: 13px; margin-top: 2px;">Retirada Balcão</span>
            </label>  


            <div name="op2" style="display:none">

            	<label for="valor" style="font-size: 13px;" class="col-md-4" >Custo de entrega
            		<input type='text' name="valor" class="money" value="{{ old('valor')}}" style="display:none;" id="valor_frete" maxlength="6" pattern='([0-9]{1,3}\.)?[0-9]{1,3},[0-9]{2}$' title="Informe o custo do frete R$ 0,00" placeholder="R$ 0,00" value="">
            	</label>

            	<label for="prazo_entrega" id="prazo_entrega_label" style="font-size: 13px;" class="col-md-4" >Prazo de entrega
            		<input type='text' name="prazo_entrega"  value="{{ old('prazo_entrega')}}" style="display:none;" id="prazo_entrega"  title="Prazo de entrega" placeholder="nº Dias" value="">
            	</label>



            	<label>
            		<input type="radio" class="with-gap" id="disable" name="entrega" value="B" />
            		<span style="font-size: 13px;" >Entrega: MOTO BOY</span><!--color: #4dd0e1;  -->
            	</label>  
            	<label>
            		<input type="radio"  class="with-gap"  id="enable" name="entrega" value="C" />
            		<span style="font-size: 13px;">Entrega: CORREIOS</span><!--color: #ffd54f;  -->
            	</label>

            	<button type="button" onclick="LoadFrete();" id="textbox" disabled class="btn btn-small waves-effect amber pull-right" style="margin-top: 13px;">Calcular frete</button>


            	<div class="col-md-2 pull-right">
            		<div class="input-field">   
            			<select name="cdservico" class="form-control" id="textbox1" disabled title="Escolha o Serviço" style=" width: 170px;  float: right; height: 32px;"><!--onclick="calcularFrete()"-->
            				<option value="" >Serviços Correios</option>
            				<option value="04014">SEDEX à vista</option>
            				<option  value="04510">PAC à vista</option>
            				<option  value="04782">SEDEX 12 ( à vista)</option>
            				<option  value="04790">SEDEX 10 (à vista)</option>
            				<option  value="04804">SEDEX Hoje à vista</option>
            			</select>


            			<input type="hidden" name="pedido_id_load" id="pedido_id_load" value="{{$pedido->id}}">             



            		</div>

            	</div>

            	<script type="text/javascript">


            		$('#enable').click(function() {
            			$('input[name=balcao]').attr('checked', false);
            			$('input[name=prazo_entrega]').css('display', 'block').attr('disabled', false);
            			$('label[id=prazo_entrega_label]').css('display', 'block');
            			$('[id=valor_frete]').css('display', 'block');
            			$('label[id=valor_frete_label]').css('display', 'block');
            			$('[id=local]').css('display', 'block');
            			$('label[id=local_label]').css('display', 'block');
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
            			$('[id=local]').css('display', 'block');

            			$('label[id=local_label]').css('display', 'block');
            			//$('input[name=prazo_entrega]').css('display', 'none');
            			//$('label[id=prazo_entrega_label]').css('display', 'none');


            			$('[id=textbox1]').css('display', 'block').attr('disabled', true);
            			$('[id=textbox]').css('display', 'block').attr('disabled', true);

            			if($('[id="disable"]').prop('checked')){
            				$("#disable").val("B");



            			} else 

            			{
            				$("#disable").val("");
            			} 

            		});

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


               		},
               		erro:function(){
               			console.log('Erro!');
               		}

               	});

               });
           </script>


           <script type="text/javascript">

           	$('[name="balcao"]').change(function() 
           	{

                    //$('[name="op1"]').toggle(200);
                    $('[name="op2"]').toggle(200);
                    $('[name="valor"]').toggle(200);
                    $('[name="prazo_entrega"]').toggle(200);
                    $('input[name=entrega]').attr('checked', false);
                    $('input[name=local]').attr('checked', false);
                    $('input[name=local]').hide();
                    $('[name=localisset]').hide();
                    $('[name="balcaoempty"]').toggle(200);
                    $('[name="alterlocal"]').toggle(200);
                    
                    //$('input[name=entrega]').hide(); 
                });  


           	$('[name="cdservico"]').change(function() 
           	{

           		$('[id=textbox]').click();                      

           	});  


           	function myFunction() {
           		document.getElementById("myCheck").click();
           	}

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


           	};





           </script> 

       </div><br>



       <div name="balcaoempty" style="display:none">                          
       	<label>
       		<input type="checkbox" name="local" onclick="cepIsset();" id="local" value="Y"/><span style="font-size: 13px;" title="Caso o endereço de cadastro seja diferente do endereço de entrega">Alterar Local da Entrega</span><!--onclick="cepIsset();"-->
       	</label>
       </div>

       <div name="localisset" style="display:none">
       	<div class="input-field">
       		<input type="text" pattern="[0-9]{5}-[0-9]{3}" id="cep" value="{{ old('cep') }}" class="cep" title="Informe o CEP. Consulta automática Ex:00000-000" maxlength="9" name="cep" placeholder="Forneça o CEP" /><!--onclick="alert('Esta ação Altera o CEP de destino.')"-->
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

       	<div class="input-field">
       		<select name="estado" class="form-control" id="estado" title="Informe o Estado" style=" width: 200px; margin-top: 5px; margin-bottom: 10px;  float: left;">
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
       	</div><br><br><br>

       </div>
       <div name="alterlocal" style="display:none; text-align: left; margin-bottom: 8px;">
       	<label style="font-size: 13px;">Local de Entrega:</label><br> {{$pedido->Vendedor->endereço}}  {{$pedido->Vendedor->numero}}
       	{{$pedido->Vendedor->bairro}} | compl:
       	{{$pedido->Vendedor->complemento}}
       	{{$pedido->Vendedor->cidade}}-{{$pedido->Vendedor->estado}} {{$pedido->Vendedor->cep}}    
       </div>
       <div class="divider"></div>
       <br>
       <p style="color: #9e9e9e; font-size: 12px; margin-left: 4px; margin-top: 5px;"><b>FORMAS DE PAGAMENTO</b></p>
      <label>
       	<input type="radio" name="pagamento" value="CC">
       	<span style="font-size: 12px;" >Cartão de Crédito</span>
       </label>
       <label>
       	<input type="radio" name="pagamento" value="CD">
       	<span style="font-size: 12px;" >Cartão de Débito</span>
       </label><br>
       <label>
       	<input type='radio' name='pagamento' value="D">
       	<span style="font-size: 12px;">Dinheiro</span> <!--Dinheiro-->
       </label>
       <label>
       	<input type="radio" name="pagamento" value="BB">
       	<span style="font-size: 12px;" >Boleto Bancário</span>                         
       </label>
       <label>
       	<input type="radio" name="pagamento" value="DB">
       	<span style="font-size: 12px;" >Depósito Bancário</span>                         
       </label>
       <div class="divider"></div>
       <br>
       <p style="color: #9e9e9e; font-size: 12px; margin-left: 4px; margin-top: 5px; margin-bottom: 2px;"><b>OBSERVAÇÕES</b></p>         
       <input type="text" name="obs_pedido" style="margin-left: 4px;" class="obspedido" size="6" title="Informe as observações gerais do pedido" placeholder="Observações gerais do pedido."  />
       


       
   </div>


   <div class="modal-footer">
   	<button type="button" class="btn btn-default" data-dismiss="modal" style="margin-top: 11px;">Voltar</button>            
   	<button type="submit"  class="btn waves-effect waves-light  blue darken-2"><span class="glyphicon glyphicon-floppy-disk"></span><b> Salvar</b></button>
   </div>

</form>



</div> 
</div>

</div>



@empty
@endforelse



</div>


</div>
</div>
</div>


</div>


<form id="form-remover-produto" method="POST" action="{{ route('carrinho.removerConsig') }}">
	{{ csrf_field() }}
	{{ method_field('DELETE') }}
	{!! method_field('put') !!}
	<input type="hidden" name="pedido_id">
	<input type="hidden" name="produto_id">
	<input type="hidden" name="request_cod">
	<input type="hidden" name="item">
</form>



<form id="form-adicionar-produto" method="POST" action="{{ route('carrinho.adicionarConsig') }}">
	{{ csrf_field() }}
	<input type="hidden" name="id">
	<input type="hidden" name="request_cod">
	@forelse($pedidos as $pedido)
	<!--<input type="hidden" name="obs_pedido" value="{{$pedido->obs_pedido}}">-->
	<input type="hidden" name="vendedor_id" value="{{$pedido->vendedor_id}}">
	<input type="hidden" name="id_cliente" value="{{$pedido->id_cliente}}">
	@empty

	@endforelse

</form>



@push('scripts')
<script type="text/javascript" src="/js/carrinho.js"></script>
@endpush

@endsection