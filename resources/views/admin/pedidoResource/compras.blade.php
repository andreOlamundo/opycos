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

     <div class="row" id="row1">
      <div class="col-md-12">
      <h2><b>Listagem de Pedidos</b></h2>
       <a href="{{route('pedido.consignado')}}" 
            class="btn btn-small waves-effect waves-light  blue darken-2 pull-right"  id="btconsignado" title="Lista de pedidos consignados">
            <i class="fa fa-handshake-o" aria-hidden="true"></i><b> Pedidos Consignados</b></a>


      <a href="{{route('index')}}" 
            class="btn btn-small waves-effect waves-light  blue darken-2 pull-right" id="novo" title="Novo Pedido">
            <i class="material-icons">add</i> <b>Novo</b></a>


       
      <div class="divider" style="margin-bottom: 1px;"></div>
    </div>
    </div>
      
      <div class="row" id="row2">
        <div class="col-md-12">
     

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

        <form method="POST" action="{{route('Pedido.search')}}"> 
          {{ csrf_field() }}
       
          <div class="card-panel" id="card-panel">
           <div class="row"> 
              <div class="col-md-1">          
         
                <div class="input-field" id="input-field">   
                <select onchange="submit()" name="updated_at" class="form-control" id="select1" title="Ano"> 
              @if (empty($ano)) 
               <option value="" onclick="myFunction()">Tudo</option>           

                 @if ($ano == '2022') 
                 <option value="2022" title="Ano"> 2022 </option>
                 <option value="2021" title="Ano"> 2021 </option>
                 <option value="2020" title="Ano"> 2020 </option>
                 @elseif ($ano == '2021')
                 <option value="2021" title="Ano"> 2021 </option>  
                 <option value="2020" title="Ano"> 2020 </option> 
                 <option value="2022" title="Ano"> 2022 </option> 
                 @else  
                 <option value="2020" title="Ano"> 2020 </option>  
                 <option value="2021" title="Ano"> 2021 </option> 
                 <option value="2022" title="Ano"> 2022 </option>             
                 @endif

               @else 
                @if ($ano == '2022') 
                <option value="2022" title="Ano"> 2022 </option> 
                <option value="2021" title="Ano"> 2021 </option>
                <option value="2020" title="Ano"> 2020 </option>
                 @elseif ($ano == '2021')
                <option value="2021" title="Ano"> 2021 </option>  
                <option value="2020" title="Ano"> 2020 </option> 
                <option value="2022" title="Ano"> 2022 </option> 
                 @else 
                 <option value="2020" title="Ano"> 2020 </option>  
                 <option value="2021" title="Ano"> 2021 </option>  
                 <option value="2022" title="Ano"> 2022 </option>                  
                 @endif
                 <option value="" onclick="myFunction()">Tudo</option>      
                 @endif      
               
               </select>
               <label for="updated_at" id="label">Ano</label>
             </div> 
              
      
              
              
            </div>  
                     <div class="col-md-1">  
                <div class="input-field" id="input-field">   
                <select name="periodo" onchange="submit()" class="form-control" id="select1" title="Período"> 


                 @if (empty($periodo)) 
                <option value="" onclick="myFunction()">Tudo</option>  
                <option value="01" title="Mês"> 01 </option>   
                <option value="02" title="Mês"> 02 </option>
                <option value="03" title="Mês"> 03 </option>
                <option value="04" title="Mês"> 04 </option>
                <option value="05" title="Mês"> 05 </option>
                <option value="06" title="Mês"> 06 </option>
                <option value="07" title="Mês"> 07 </option>
                <option value="08" title="Mês"> 08 </option>
                <option value="09" title="Mês"> 09 </option>
                <option value="10" title="Mês"> 10 </option>
                <option value="11" title="Mês"> 11 </option>
                <option value="12" title="Mês"> 12 </option>
                 @else    

               

                 @if ($periodo == '01') 
                  <option value="01" style="display: none;" title="Mês"> 01 </option> 
                  @elseif ($periodo == '02')
                  <option value="02" style="display: none;" title="Mês"> 02 </option> 
                  @elseif ($periodo == '03')
                  <option value="03" style="display: none;" title="Mês"> 03 </option>
                  @elseif ($periodo == '04')
          <option value="04" style="display: none;" title="Mês"> 04 </option>
                  @elseif ($periodo == '05')
          <option value="05" style="display: none;" title="Mês"> 05 </option>
                  @elseif ($periodo == '06')
          <option value="06" style="display: none;" title="Mês"> 06 </option>
                  @elseif ($periodo == '07')
          <option value="07" style="display: none;" title="Mês"> 07 </option>
                  @elseif ($periodo == '08')
          <option value="08" style="display: none;" title="Mês"> 08 </option>
                  @elseif ($periodo == '09')
          <option value="09" style="display: none;" title="Mês"> 09 </option>
                  @elseif ($periodo == '10')
          <option value="10" style="display: none;" title="Mês"> 10 </option>
                  @elseif ($periodo == '11')
          <option value="11" style="display: none;" title="Mês"> 11 </option>
                  @else 
              
                  <option value="12" style="display: none;" title="Mês"> 12 </option>
                 @endif
                 <option value="" onclick="myFunction()">Tudo</option>
                  <option value="01" {{ $periodo == '01' ? 'disabled' : '' }} title="Mês"> 01 </option>
                  <option value="02" {{ $periodo == '02' ? 'disabled' : '' }} title="Mês"> 02 </option>
                  <option value="03" {{ $periodo == '03' ? 'disabled' : '' }} title="Mês"> 03 </option>   
                  <option value="04" {{ $periodo == '04' ? 'disabled' : '' }} title="Mês"> 04 </option> 
                  <option value="05" {{ $periodo == '05' ? 'disabled' : '' }} title="Mês"> 05 </option> 
                  <option value="06" {{ $periodo == '06' ? 'disabled' : '' }} title="Mês"> 06 </option> 
                  <option value="07" {{ $periodo == '07' ? 'disabled' : '' }} title="Mês"> 07 </option> 
                  <option value="08" {{ $periodo == '08' ? 'disabled' : '' }} title="Mês"> 08 </option> 
                  <option value="09" {{ $periodo == '09' ? 'disabled' : '' }} title="Mês"> 09 </option> 
                  <option value="10" {{ $periodo == '10' ? 'disabled' : '' }} title="Mês"> 10 </option> 
                  <option value="11" {{ $periodo == '11' ? 'disabled' : '' }} title="Mês"> 11 </option> 
                  <option value="12" {{ $periodo == '12' ? 'disabled' : '' }} title="Mês"> 12 </option>                
                 @endif

               </select>
               <label for="periodo" id="label">Período</label>
             </div> 
              
            </div>  

                        <script type="text/javascript">
      function myFunction() {
        document.getElementById("myCheck").click();
      }

    </script>

 <a href="{{route('all.pedidos')}}" style="display:none" id="myCheck"></a>      
            <div class="col-md-1" id="col-md-1">  
              <div class="input-field">   
                <select id="pedidoId" class="select1" title="Pesquisa pelo Número do pedido" onchange="submit()" name="id">
                  @foreach($pedidos as $dp)
                  <option></option>
                  <option value="{{ $dp->id }}">{{ $dp->id }}</option>

                  @endforeach     
                </select>
                <label for="pedido_id" id="label">Pedido</label>
              </div>
              <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
              <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script> 
              @if (isset($pedido_id))                
              <script type="text/javascript">
                $("#pedidoId").select2({
                  placeholder:'<?php $i = 0; $len = count($pedidos); foreach ($pedidos as $pedido){  if ($i == 0) { $pedido->id; echo $pedido->Cliente->name;  }  else if ($i == $len - 1) { 

                  } $i++; }  ?>'
                });
              </script>
              


              @else    
              <script type="text/javascript">
                $("#pedidoId").select2({
                  placeholder:''
                });
              </script>
              @endif
              
              
            </div>          
             <div class="col-md-3" id="col-md-1">  
              <div class="input-field">   
                <select id="cliente" class="select1" title="Pesquisa por Cliente"  onchange="submit()" name="id_cliente">
                  @foreach($dadosClientes as $dc)
                  <option></option>
                  <option value="{{ $dc->id }}">{{ $dc->id }}. {{$dc->name}}. Cel:{{$dc->cel}}. Doc.{{$dc->cpf}} {{$dc->cnpj}}</option>

                  @endforeach     
                </select>
                <label for="id_cliente" id="label">Cliente</label>
              </div>
              <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
              <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script> 
              @if (isset($id_cliente))                
              <script type="text/javascript">
                $("#cliente").select2({
                  placeholder:'<?php $i = 0; $len = count($pedidos); foreach ($pedidos as $pedido){  if ($i == 0) { $pedido->id_cliente; echo $pedido->Cliente->name;  }  else if ($i == $len - 1) { 

                  } $i++; }  ?>'
                });
              </script>
              


              @else    
              <script type="text/javascript">
                $("#cliente").select2({
                  placeholder:''
                });
              </script>
              @endif
              
              
            </div>
            <div class="col-md-3" id="col-md-1">
              <div class="input-field">   
                <select id="vendedor" class="select1" title="Pesquisa por Vendedor"  onchange="submit()" name="vendedor_id">
                  @foreach($dadosVendedores as $dv)
                  <option></option>
                  <option value="{{ $dv->id }}">{{ $dv->id }}. {{$dv->name}} </option>

                  @endforeach     
                </select>
                <label for="vendedor" id="label">Vendedor</label>
              </div>
              <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
              <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>  

              @if (isset($vendedor_id))                
              <script type="text/javascript">
                $("#vendedor").select2({
                  placeholder:'<?php $i = 0; $len = count($pedidos); foreach ($pedidos as $pedido){  if ($i == 0) { $pedido->vendedor_id;   echo $pedido->Vendedor->name; } else if ($i == $len - 1) {        } $i++; } ?>'
                });
              </script>
              


              @else    
              
              <script type="text/javascript">
                $("#vendedor").select2({
                  placeholder:''
                });
              </script> 
              @endif                  

            </div>

            <div class="col-md-1" id="col-md-1">
              <div class="input-field">   
                <select onchange="submit()" name="status" class="form-control" id="select1" title="Status de acompanhamento do pedido">                                      
                  
                 @if (empty($status))
                 <option value=""></option>                
                 <option value="EC" title="Pedidos Enviados ao cliente">Enviados </option>
                 <option value="RE" title="Pedidos Reservados">Reservados </option>
                 <option value="FI" title="Pedidos Finalizados">Finalizados </option>
                 <option value="CA" title="Pedidos Cancelados">Cancelados </option>
                 <option value="EL" title="Pedidos Encaminhados ao Laboratório">Enc. ao Lab. </option>
                 <option value="AP" title="Pedidos Aguardando Pagamento">Ag. Pagamento </option> 

            
                 
                 <!--<option value="GE" title="Pedidos Em Aberto">Em Aberto </option>-->
                 @else
                 

                 @if ($status == 'FI') 
                 <option value="FI" style="display: none;" title="Pedidos Finalizados">Finalizado</option>
                 @elseif ($status == 'CA')
                 <!--<option value="E" title="Link de cadastro enviado">Enviado</option>-->
                 <option value="CA"  style="display: none;"  title="Pedidos Cancelados">Cancelado </option>  
                 @elseif ($status == 'RE')                 
                 <option value="RE"  style="display: none;"  title="Pedidos Reservados">Reservado </option>
                  @elseif ($status == 'AP') 
                 <option value="AP"  style="display: none;"  title="Pedidos Aguardando Pagamento">Ag. Pagamento </option>
                 @elseif ($status == 'EL')
                  <option value="EL"  style="display: none;"  title="Pedidos Encaminhados ao Laboratório">Enc. ao Lab. </option>
                  @elseif ($status == 'EC')
                  <option value="EC"  style="display: none;"  title="Pedidos Enviados ao cliente">Enviados </option>                  

                 @else ($status == 'GE')  
                 <!--<option value="GE" title="Pedidos Em Aberto">Em Aberto </option>-->
                 @endif
                    <option value="EC" title="Pedidos Enviados ao cliente">Enviados </option>
                 <option value="RE" title="Pedidos Reservados">Reservados </option>
                 <option value="FI" title="Pedidos Finalizados">Finalizados </option>
                 <option value="CA" title="Pedidos Cancelados">Cancelados </option>
                 <option value="EL" title="Pedidos Encaminhados ao Laboratório">Enc. ao Lab. </option>
                 <option value="AP" title="Pedidos Aguardando Pagamento">Ag. Pagamento </option>

                 @endif


                 
                 
                 
               </select>
               <label for="status" id="label">Status</label>
             </div>
             <input type="hidden" name="consignado" value="N">

           </div>
              <div class="col-md-1" id="col-md-2">

              @if (empty($cancelados))

              <label>
                <input type="radio" onclick="submit()"  class="with-gap" name="cancelados" value="N" />
                <span style="font-size: 13px;">(-)Cancelados</span><!--color: #ffd54f;  -->
              </label>
              <input type="hidden" name="cancelados" value="N" />
               
              @else


              <label>
                <input type="radio" onclick="submit()" {{ $cancelados == 'N' ? 'checked' : '' }}  class="with-gap" name="cancelados" value="N" />
                <span style="font-size: 13px;">(-)Cancelados</span>
              </label>
              @endif

              </div>



           <a href="{{route('all.pedidos')}}" title="Limpar Pesquisa" class="btn btn-small waves-effect pull-center" id="limpar"><i class="fa fa-eraser"></i></a>


       </div>
   </div>
       
     </form>
   </div>
 </div>


 <div class="row" id="rowtable">

   <div class="col-md-12"> 
      <div class="table-responsive">
        <table class="table table-sm table-striped table-bordered table-condensed table-hover" id="example">
           <thead>
            <tr class="warning">             
                       <th title="Código do Pedido">Pedido</th>
                       <th>Cliente</th>
                       <th title="Data de registro do pedido">Registro</th> 
                       <th title="Tipo de entrega">Entrega</th> 
                       <th title="Status do Pedido">Status(P)</th> 
                       <th title="Tipo de Pagamento">Pag.</th>
                       <th title="Total por produto">Produtos</th> 
                       <th title="Total desconto">Descontos</th>
                       <th title="Custo do Frete">Frete</th>                      
                       <th title="(Produto -Desconto + Frete)">Total</th>
                       <th title="Ações">Detalhes</th>
                     </tr>
                   </thead>
         
                   <tbody>
                  
                     @foreach ($pedidos as $compra)
                     <tr>

                    <td title="{{ $compra->id }}" id="center">
                     {{ $compra->id }}
                    </td>
                    <td title="Vendedor: {{ ($compra->Vendedor->name) }}">                  
                     @php 
                     $name = $compra->Cliente->name;
                     echo substr($name, 0, 24);                     
                     $result = strlen($name);                    
                     @endphp
                     @if ($result > 24)
                     ...
                     @else
                     @endif
                     
                   </td>
                   <td title="{{ $compra->created_at->format('d/m/Y H:i') }}">{{ $compra->created_at->format('d/m/Y') }}</td>                 
                  <td title="@if (isset($compra->Frete->local)) Local de Entrega: {{($compra->Frete->endereço)}} {{($compra->Frete->numero)}} {{($compra->Frete->bairro)}} {{($compra->Frete->cidade)}} {{($compra->Frete->estado)}}  {{($compra->Frete->cep)}} @elseif (isset($compra->Frete->balcao)) Nossa Loja  @else Local de Entrega: {{($compra->Cliente->endereço)}} {{($compra->Cliente->numero)}} {{($compra->Cliente->bairro)}} {{($compra->Cliente->cidade)}} {{($compra->Cliente->estado)}} {{($compra->Cliente->cep)}} @endif" > @if (isset($compra->Frete->pedido_id)) {{ $compra->Frete->entrega == NULL ? 'Retirada Balcão' : '' }} {{ $compra->Frete->entrega == 'CA' ? 'Entrega Cancelada' : '' }} {{ $compra->Frete->entrega == 'FI' ? 'Entrega Concluída' : '' }}{{ $compra->Frete->entrega == 'C' ? ' Correios' : '' }}{{ $compra->Frete->entrega == 'B' ? 'Moto Boy' : '' }} @else Em berto @endif </td>
                   <td  title="{{ $compra->status == 'RE' ? 'Reservado' : '' }}{{ $compra->status == 'AP' ? 'Aguardando Pagamento' : '' }}{{ $compra->status == 'EL' ? 'Encaminhado ao Laboratorio' : '' }}{{ $compra->status == 'EC' ? 'Enviado ao Cliente' : '' }}{{ $compra->status == 'FI' ? 'Finalizado' : '' }}{{ $compra->status == 'GE' ? 'Em Aberto' : '' }}{{ $compra->status == 'CA' ? 'Cancelado' : '' }} {{ $compra->updated_at->format('d/m/Y H:i') }}">{{ $compra->status == 'RE' ? 'Reservado' : '' }}{{ $compra->status == 'GE' ? 'Em aberto' : '' }}{{ $compra->status == 'AP' ? 'Ag. Pag.' : '' }}{{ $compra->status == 'EL' ? 'Enc.ao Lab' : '' }}{{ $compra->status == 'EC' ? 'Enviado ao Cliente' : '' }}{{ $compra->status == 'FI' ? 'Finalizado' : '' }}{{ $compra->status == 'CA' ? 'Cancelado' : '' }}</td> 
                   <td title="{{ $compra->pagamento == 'D' ? 'Dinheiro' : '' }}{{ $compra->pagamento == 'CC' ? 'Cartão de Crédito' : '' }}{{ $compra->pagamento == 'CD' ? 'Cartão de Débito' : '' }}{{ $compra->pagamento == 'BB' ? 'Boleto Bancário' : '' }}{{ $compra->pagamento == 'DB' ? 'Depósito Bancário' : '' }}">@if ($compra->pagamento == 'D') {{ $compra->pagamento == 'D' ? 'D' : '' }} @elseif ($compra->pagamento == 'CC') {{ $compra->pagamento == 'CC' ? 'CC' : '' }} @elseif ($compra->pagamento == 'CD') {{ $compra->pagamento == 'CD' ? 'CD' : '' }} @elseif ($compra->pagamento == 'BB'){{ $compra->pagamento == 'BB' ? 'BB' : '' }} @else {{ $compra->pagamento == 'DB' ? 'DB' : '' }} @endif</td>                         
                   @php
                   $total_pedido = 0;
                   @endphp

                   @foreach ($compra->pedido_produtos_itens as $pedido_produto)

                 
                   @if ($compra->Frete->status == 'AR' && $compra->Frete->balcao == 'Y')
                   @php
                   $total_produto = $compra->pedido_produtos_itens->sum('prod_preco_padrao'); 
                   $total_desc_produto = $compra->pedido_produtos_itens->sum('prod_desconto');
                   $total_desc_request = $compra->pedido_produtos_itens->sum('request_desconto');
                   $total_desconto = $total_desc_request + $total_desc_produto;
                   $total_pedido = $total_produto - $total_desconto;
                   @endphp           


                   @elseif ($compra->Frete->status == 'EMB' && $compra->Frete->entrega == 'B')
                  
                   @php
                   $total_produto = $compra->pedido_produtos_itens->sum('prod_preco_padrao');
                   $total_pedido = $compra->Frete->valor;
                   $total_desc_produto = $compra->pedido_produtos_itens->sum('prod_desconto');
                   $total_desc_request = $compra->pedido_produtos_itens->sum('request_desconto');
                   $total_desconto = $total_desc_request + $total_desc_produto;
                   $total_pedido += $total_produto - $total_desconto;              
                   @endphp
                 

                   @elseif ($compra->Frete->status == 'EC' && $compra->Frete->entrega == 'C')
                 
                   @php                 
                   $total_produto = $compra->pedido_produtos_itens->sum('prod_preco_padrao');                
                   $total_pedido = $compra->Frete->valor;
                   $total_desc_produto = $compra->pedido_produtos_itens->sum('prod_desconto');
                   $total_desc_request = $compra->pedido_produtos_itens->sum('request_desconto');
                   $total_desconto = $total_desc_request + $total_desc_produto;
                   $total_pedido += $total_produto - $total_desconto;
                   @endphp
                

                   @elseif ($compra->Frete->status == 'EMB' && $compra->Frete->entrega == 'CA')
                  
                   @php
                   $total_produto = $compra->pedido_produtos_itens->sum('prod_preco_padrao');
                   $total_pedido = $compra->Frete->valor;
                   $total_desc_produto = $compra->pedido_produtos_itens->sum('prod_desconto');
                   $total_desc_request = $compra->pedido_produtos_itens->sum('request_desconto');
                   $total_desconto = $total_desc_request + $total_desc_produto;
                   $total_pedido += $total_produto - $total_desconto;
                   @endphp
                  

                   @elseif ($compra->Frete->status == 'EC' && $compra->Frete->entrega == 'CA')
                 
                   @php
                   $total_produto = $compra->pedido_produtos_itens->sum('prod_preco_padrao');
                   $total_pedido = $compra->Frete->valor;
                   $total_desc_produto = $compra->pedido_produtos_itens->sum('prod_desconto');
                   $total_desc_request = $compra->pedido_produtos_itens->sum('request_desconto');
                   $total_desconto = $total_desc_request + $total_desc_produto;
                   $total_pedido += $total_produto - $total_desconto;              
                   @endphp
                 

                   @elseif ($compra->Frete->status == 'EMB' && $compra->Frete->entrega == 'FI')
                 
                   @php
                   $total_produto = $compra->pedido_produtos_itens->sum('prod_preco_padrao');
                   $total_pedido = $compra->Frete->valor;
                   $total_desc_produto = $compra->pedido_produtos_itens->sum('prod_desconto');
                   $total_desc_request = $compra->pedido_produtos_itens->sum('request_desconto');
                   $total_desconto = $total_desc_request + $total_desc_produto;
                   $total_pedido += $total_produto - $total_desconto;              
                   @endphp


                   @else ($compra->Frete->status == 'EC' && $compra->Frete->entrega == 'FI')
                  
                   @php
                   $total_produto = $compra->pedido_produtos_itens->sum('prod_preco_padrao');
                   $total_pedido = $compra->Frete->valor;
                   $total_desc_produto = $compra->pedido_produtos_itens->sum('prod_desconto');
                   $total_desc_request = $compra->pedido_produtos_itens->sum('request_desconto');
                   $total_desconto = $total_desc_request + $total_desc_produto;
                   $total_pedido += $total_produto - $total_desconto;              
                   @endphp
                 

                   @endif
                   

                   @endforeach



                   
                
                   @if ($compra->Frete->status == 'AR' && $compra->Frete->balcao == 'Y')
                   @php
                   $total_produtos = $compra->pedido_produtos_itens->sum('prod_preco_padrao');
                   $total_desc_produto = $compra->pedido_produtos_itens->sum('prod_desconto');
                   $total_desc_request = $compra->pedido_produtos_itens->sum('request_desconto');
                   $total_desconto = $total_desc_request + $total_desc_produto;
                   $total_produto = $total_produtos;

                   @endphp                
                                                      
                   <td>R$ {{ number_format($total_produto, 2, ',', '.') }}</td> 
                   <td>@if (isset($total_desconto))R$ {{ number_format($total_desconto, 2, ',', '.') }} @else R$ 0,00 @endif</td>           
                   

                   @elseif ($compra->Frete->status == 'EMB' && $compra->Frete->entrega == 'B')                 
                   @php
                   $total_produtos = $compra->pedido_produtos_itens->sum('prod_preco_padrao');
                   $total_desc_produto = $compra->pedido_produtos_itens->sum('prod_desconto');
                   $total_desc_request = $compra->pedido_produtos_itens->sum('request_desconto');
                   $total_desconto = $total_desc_request + $total_desc_produto;
                   $total_produto = $total_produtos;
                   @endphp               

                   <td>R$ {{ number_format($total_produto, 2, ',', '.') }}</td> 
                    <td>@if (isset($total_desconto))R$ {{ number_format($total_desconto, 2, ',', '.') }} @else R$ 0,00 @endif</td>             

                   

                   @else ($compra->Frete->status == 'EC' && $compra->Frete->entrega == 'C')
                   
                   @php
                   $total_produtos = $compra->pedido_produtos_itens->sum('prod_preco_padrao');
                   $total_desc_produto = $compra->pedido_produtos_itens->sum('prod_desconto');
                   $total_desc_request = $compra->pedido_produtos_itens->sum('request_desconto');
                   $total_desconto = $total_desc_request + $total_desc_produto;
                   $total_produto = $total_produtos;
                   @endphp
                   
                   <td>R$ {{ number_format($total_produto, 2, ',', '.') }}</td> 
                   <td>@if (isset($total_desconto))R$ {{ number_format($total_desconto, 2, ',', '.') }} @else R$ 0,00 @endif</td>          
                   
                   @endif            



                   <td>R$ {{ number_format($compra->Frete->valor, 2, ',', '.') }}</td>  

                
                  
                  
                



                   <td>R$ {{ number_format($total_pedido, 2, ',', '.') }}</td>

                   @if(($compra->status == 'RE') || ($compra->status == 'AP') || ($compra->status == 'EL') )                 
                   
<!--$total_pedido = $compra->Frete->valor;-->
                   <td id="center">
                    <a href="{{route('pedidos.edit', $compra->id)}}" 
                     data-toggle="tooltip" 
                     data-placement="top"
                     title="Alterar" class="btn btn-small waves-effect amber" style="width: 20px; height: 20px; padding: 0px 2px;"><i class="small material-icons">border_color</i></a>

                       <a href="{{route('pdf', $compra->id)}}" title="Detalhes do pedido em formato pdf." target="_blank" style="width: 20px; height: 20px; padding: 0px 3px;" class="btn btn-small waves-effect btn-default"><i class="fa fa-file-pdf-o"></i></a>
                                    <!--<form style="display: inline-block;" method="POST" action="{{route('pedidos.destroy', $compra->id)}}"data-toggle="tooltip" data-placement="top" title="Excluir" onsubmit="return confirm('Confirma exclusão?')">
                   {{method_field('DELETE')}}{{ csrf_field() }}                                                
                   <button type="submit" class="btn waves-effect deep-orange">
                    <i class="fa fa-trash"></i>                                                    
                  </button>
                </form>-->
              </td>
              @elseif ($compra->status == 'CA')
              <td id="center">

                  <a href="{{route('pedido.info', $compra->id)}}" 
                     data-toggle="tooltip" 
                     data-placement="top"
                     title="Detalhes do pedido (Cancelado)" class="btn waves-effect red" style="width: 20px; height: 20px; padding: 0px 0px;"><span class="glyphicon glyphicon-info-sign"></span></a>

               <!-- <strong style="color:red;">Cancelado</strong>-->
              </td>

              @elseif ($compra->status == 'FI')

              <td id="center">

                  <a href="{{route('pedido.info', $compra->id)}}" 
                     data-toggle="tooltip" 
                     data-placement="top"
                     title="Detalhes do pedido (Finalizado)" class="btn btn-small waves-effect green" style="width: 20px; height: 20px; padding: 0px 0px;"><span style="margin-top: 2px;" class="fa fa-list-alt"></span></a>

                   <a href="{{route('pdf', $compra->id)}}" title="Detalhes do pedido em formato pdf." target="_blank" style="width: 20px; height: 20px; padding: 0px 3px;" class="btn btn-small waves-effect btn-default"><i class="fa fa-file-pdf-o"></i></a>


                <!--<strong style="color:green;">Concluído</strong>-->
              </td>

               @elseif ($compra->status == 'EC')

              <td id="center">

                  <a href="{{route('pedido.info', $compra->id)}}" 
                     data-toggle="tooltip" 
                     data-placement="top"
                     title="Detalhes do pedido (Enviado ao Cliente)" class="btn btn-small waves-effect pull-center" style="width: 20px; height: 20px; padding: 0px 0px;"><span style="margin-top: 9px;" class="fa fa-list-alt"></span></a>

                         <a href="{{route('pdf', $compra->id)}}" title="Detalhes do pedido em formato pdf." target="_blank" style="width: 20px; height: 20px; padding: 0px 3px;" class="btn btn-small waves-effect btn-default"><i class="fa fa-file-pdf-o"></i></a>

                     

                <!--<strong style="color:green;">Concluído</strong>-->
              </td>




              @else ($compra->status == 'GE')
              <td id="center">
                <strong style="color:blue;">Em aberto</strong>
              </td>

              @endif 



              @endforeach


              
            </tr>
          </tbody>
         <!-- <tfoot>
            <tr>
              <th style="border-right: none;"></th>
              <th style="border-right: none;"></th>             
              <th style="border-right: none;"></th>
              <th style="border-right: none;"></th>
              <th style="border-right: none;"></th>              
              <th style="border-right: none;"></th>                       
              <th style="border-right: none;">R$ {{ number_format($soma_produtos, 2, ',', '.') }}</th>
              <th style="border-right: none;">-R$ {{ number_format($desconto, 2, ',', '.') }}</th>
                <th style="border-right: none;">+R$ {{ number_format($frete_total, 2, ',', '.') }}</th>
                <th style="border-right: none;">R$ {{ number_format($total_preco, 2, ',', '.') }}</th>
                 <th></th>
            </tr>
            
          </tfoot>-->
    
        </table>
        



     


     <!-- @forelse ($compras as $pedido)
      <input type="hidden" name="pedido_id" value="{{ $pedido->id }}"/>
      @empty
      <h5 id="center">
      Você ainda não fez nenhum pedido.</h5>
      @endforelse-->

      
    </div>
  </div>




  </div>
</div>
</div>
</div>
<link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap.min.css" rel="stylesheet"/>
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap.min.js"></script>
<!--Data staly d/m/A-->
<script src=https://cdn.datatables.net/plug-ins/1.10.20/sorting/datetime-moment.js></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script>

<!--PDF CVS PRINT  -->
<!--<link href="https://cdn.datatables.net/buttons/1.6.1/css/buttons.dataTables.min.css" rel="stylesheet"/>


<script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.print.min.js"></script>-->



<!--PDFMAKE

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.60/pdfmake.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.60/pdfmake.js.map"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.60/pdfmake.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.60/pdfmake.min.js.map"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.60/vfs_fonts.js"></script>

<script type="text/javascript">
var docDefinition = {
  content: [
    {
      layout: 'lightHorizontalLines', // optional
      table: {
        // headers are automatically repeated if the table spans over multiple pages
        // you can declare how many rows should be treated as headers
        headerRows: 1,
        widths: [ '*', 'auto', 100, '*' ],

        body: [
          [ 'First', 'Second', 'Third', 'The last one' ],
          [ 'Value 1', 'Value 2', 'Value 3', 'Value 4' ],
          [ { text: 'Bold value', bold: true }, 'Val 2', 'Val 3', 'Val 4' ]
        ]
      }
    }
  ]
};


pdfMake.tableLayouts = {
  exampleLayout: {
    hLineWidth: function (i, node) {
      if (i === 0 || i === node.table.body.length) {
        return 0;
      }
      return (i === node.table.headerRows) ? 2 : 1;
    },
    vLineWidth: function (i) {
      return 0;
    },
    hLineColor: function (i) {
      return i === 1 ? 'black' : '#aaa';
    },
    paddingLeft: function (i) {
      return i === 0 ? 0 : 8;
    },
    paddingRight: function (i, node) {
      return (i === node.table.widths.length - 1) ? 0 : 8;
    }
  }
};

// download the PDF
pdfMake.createPdf(docDefinition).download();
</script>-->





<script type="text/javascript">

   /* $(document).ready(function() {
    $('#example').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    } );
} );*/





  $(document).ready(function() {

$.fn.dataTable.moment('DD/MM/YYYY');

     $('#example').DataTable({


      "dom": '<"top"i>rt<"bottom"<"col-md-5"fl><"col-md-7"p>><"clear">',
     // "dom": '<"top"i>Brt<"bottom"<"col-md-5"fl><"col-md-7"p>><"clear">',
      /* buttons: [
            'pdf', 'print'
        ],*/

                    

      /*  buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],*/

      "language": {
            "decimal":        ",",
            "thousands":      ".",
            "lengthMenu": " <div style='font-size: 12px;'>_MENU_ Registros por página</div>",             
            "zeroRecords": "Nenhuma Informação localizada",
            "info": "Mostrando página _PAGE_ de _PAGES_  - Total de Registros _MAX_ ",
            "infoEmpty": "Nenhum registro disponível",
            "infoFiltered": "(filtrado do _MAX_ total registros)",
      "paginate": {

        "first":      "Primeiro",
        "last":       "Último",
        "next":       "Próximo",
        "previous":   "Anterior"
    },
            //"sSearch": false,




        },
        "aaSorting": [[0, 'desc']],
        /* "aoColumns": [
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,        
            { "orderSequence": ["desc", "asc"] },
            null
        ],*/




       // scrollY: 300,
      //  "pageLength": 50, 

      /*"sLengthMenu": "Mostrar _MENU_ registros por página",
            "sZeroRecords": "Nenhum registro encontrado",
            "sInfo": "Mostrando _START_ / _END_ de _TOTAL_ registro(s)",
            "sInfoEmpty": "Mostrando 0 / 0 de 0 registros",
            "sInfoFiltered": "(filtrado de _MAX_ registros)",
            "sSearch": "Pesquisar: ",
            "oPaginate": {
                "sFirst": "Início",
                "sPrevious": "Anterior",
                "sNext": "Próximo",
                "sLast": "Último"
            }
        },
        "aaSorting": [[0, 'desc']],
        "aoColumnDefs": [
            {"sType": "num-html", "aTargets": [0]}
 
        ]*/

    });


 



} );

  $.extend( $.fn.dataTable.defaults, {
    searching: false,

  
   // ordering:  false

} );

  $.fn.dataTable.moment = function ( format, locale ) {
    var types = $.fn.dataTable.ext.type;
 
    // Add type detection
    types.detect.unshift( function ( d ) {
        return moment( d, format, locale, true ).isValid() ?
            'moment-'+format :
            null;
    } );
 
    // Add sorting method - use an integer for the sorting
    types.order[ 'moment-'+format+'-pre' ] = function ( d ) {
        return moment( d, format, locale, true ).unix();
    };
};






</script>



@endsection