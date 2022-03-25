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

     <div class="row" style="height: 50px; width: 1170px; position: fixed; background-color: white; z-index: 1001; top: 50px; margin-bottom: 60px;">
      <div class="col-md-12">
      <h2><b>Pedidos Consignados</b></h2>
      <a href="{{route('pedido.compras')}}" 
            class="btn btn-small waves-effect waves-light  blue darken-2 pull-right" style="margin-top: -35px; width: 100px; height: 25px; padding: 2px 1px; margin-right: 90px;" title="Lista de pedidos comuns">
            <i class="fa fa-clipboard" aria-hidden="true"></i><b> Pedidos</b></a> 


      @forelse ($compras as $pedido)
  <a href="{{route('index')}}" 
            class="btn btn-small waves-effect waves-light  blue darken-2 pull-right" style="margin-top: -35px; width: 80px; height: 25px; padding: 2px 1px;" title="Novo Pedido">
            <i class="material-icons">add</i> <b>Novo</b></a>
      @empty

       <a href="{{route('index.consignado')}}" 
            class="btn btn-small waves-effect waves-light  blue darken-2 pull-right" style="margin-top: -35px; width: 80px; height: 25px; padding: 2px 1px;" title="Novo Pedido Consignado">
            <i class="material-icons">add</i> <b>Novo</b></a>
     
     
  
      @endforelse

       
      <div class="divider" style="margin-bottom: 1px;"></div>
    </div>
    </div>
      
      <div class="row" style="height: 50px; width: 1170px; position: fixed; z-index: 1001; top: 100px; ">
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

        <form method="POST" action="{{route('PedidoConsig.search')}}"> 
          {{ csrf_field() }}
       
          <div class="card-panel" style="height: 60px; margin-top: 0px; margin-bottom: 2px; padding: 12px 10px;">
           <div class="row"> 
            <div class="col-md-2">  
              <div class="input-field">   
                <select id="pedidoId" onchange="submit()" name="id">
                  @foreach($pedidos as $dp)
                  <option></option>
                  <option value="{{ $dp->id }}">{{ $dp->id }} {{$dp->Vendedor->name}}</option>

                  @endforeach     
                </select>
                <label for="pedido_id" style="font-size: 15px; margin-top: -30px;">Pedido</label>
              </div><br>
              <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
              <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script> 
              @if (isset($pedido_id))                
              <script type="text/javascript">
                $("#pedidoId").select2({
                  placeholder:'<?php $i = 0; $len = count($pedidos); foreach ($pedidos as $pedido){  if ($i == 0) { $pedido->id; echo $pedido->Vendedor->name;  }  else if ($i == $len - 1) { 

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

            <div class="col-md-3">
              <div class="input-field">   
                <select id="vendedor" onchange="submit()" name="vendedor_id">
                  @foreach($dadosVendedores as $dv)
                  <option></option>
                  <option value="{{ $dv->id }}">{{ $dv->id }}. {{$dv->name}} </option>

                  @endforeach     
                </select>
                <label for="vendedor" style="font-size: 15px; margin-top: -30px;">Vendedor</label>
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


               <div class="col-md-2">
              <div class="input-field">   
                <select name="status" onchange="submit()" class="form-control" style="height: 29px;" title="Status de acompanhamento do pedido">                                      
                  
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
               <label for="status" style="font-size: 15px;  margin-top: -30px;">Status Pedido</label>

             </div>
             <input type="hidden" name="consignado" value="S">

           </div>



           <a href="{{route('pedido.consignado')}}" style="margin-top: 10px; width: 30px; height: 26px; padding: 2px 1px;" title="Limpar Pesquisa" class="btn btn-small waves-effect pull-center"><i class="fa fa-eraser"></i></a>
         
    

       </div>
   </div>
       
     </form>
   </div>
 </div>


 <div class="row" style="margin-top: 95px;">
   <div class="col-md-12">   
        <table class="table table-sm table-striped table-bordered table-condensed table-hover" id="example" style="width:100%">
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
                     $name = $compra->Vendedor->name;
                     echo substr($name, 0, 24);                     
                     $result = strlen($name);                    
                     @endphp
                     @if ($result > 24)
                     ...
                     @else
                     @endif
                     
                   </td>
                   <td title="{{ $compra->created_at->format('d/m/Y H:i') }}">{{ $compra->created_at->format('d/m/Y') }}</td>                 
                  <td title="@if (isset($compra->Frete->local)) Local de Entrega: {{($compra->Frete->endereço)}} {{($compra->Frete->numero)}} {{($compra->Frete->bairro)}} {{($compra->Frete->cidade)}} {{($compra->Frete->estado)}}  {{($compra->Frete->cep)}} @elseif (isset($compra->Frete->balcao)) Nossa Loja  @else Local de Entrega: {{($compra->Vendedor->endereço)}} {{($compra->Vendedor->numero)}} {{($compra->Vendedor->bairro)}} {{($compra->Vendedor->cidade)}} {{($compra->Vendedor->estado)}} {{($compra->Vendedor->cep)}} @endif" > @if (isset($compra->Frete->pedido_id)) {{ $compra->Frete->entrega == NULL ? 'Retirada Balcão' : '' }} {{ $compra->Frete->entrega == 'CA' ? 'Entrega Cancelada' : '' }} {{ $compra->Frete->entrega == 'FI' ? 'Entrega Concluída' : '' }}{{ $compra->Frete->entrega == 'C' ? ' Correios' : '' }}{{ $compra->Frete->entrega == 'B' ? 'Moto Boy' : '' }} @else Em berto @endif </td>
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
                   <td>@if (isset($total_desconto))R$  {{ number_format($total_desconto, 2, ',', '.') }} @else R$ 0,00 @endif</td>            
                   

                   @elseif ($compra->Frete->status == 'EMB' && $compra->Frete->entrega == 'B')                 
                   @php
                   $total_produtos = $compra->pedido_produtos_itens->sum('prod_preco_padrao');
                   $total_desc_produto = $compra->pedido_produtos_itens->sum('prod_desconto');
                   $total_desc_request = $compra->pedido_produtos_itens->sum('request_desconto');
                   $total_desconto = $total_desc_request + $total_desc_produto;
                   $total_produto = $total_produtos;
                   @endphp               

                  <td>R$ {{ number_format($total_produto, 2, ',', '.') }}</td> 
                    <td>@if (isset($total_desconto))R$  {{ number_format($total_desconto, 2, ',', '.') }} @else R$ 0,00 @endif</td>              

                   

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
                   

                   <td id="center">
                    <a href="{{route('pedidos/{id}/consig/edit', $compra->id)}}" 
                     data-toggle="tooltip"
                     data-placement="top"
                     title="Alterar" class="btn btn-small waves-effect amber" style="width: 20px; height: 20px; padding: 0px 2px;"><i class="small material-icons">border_color</i></a>

                       <a href="{{route('pdf.consignado', $compra->id)}}" title="Detalhes do pedido em formato pdf." target="_blank" style="width: 20px; height: 20px; padding: 0px 3px;" class="btn btn-small waves-effect btn-default"><i class="fa fa-file-pdf-o"></i></a>
                                    
              </td>
              @elseif ($compra->status == 'CA')
              <td id="center">

                  <a href="{{route('pedido.infoConsig', $compra->id)}}" 
                     data-toggle="tooltip" 
                     data-placement="top"
                     title="Detalhes do pedido (Cancelado)" class="btn waves-effect red" style="width: 20px; height: 20px; padding: 0px 0px;"><span class="glyphicon glyphicon-info-sign"></span></a>

               <!-- <strong style="color:red;">Cancelado</strong>-->
              </td>

              @elseif ($compra->status == 'FI')

              <td id="center">
              

                     <a href="{{route('pedido.infoConsig', $compra->id)}}" 
                     data-toggle="tooltip" 
                     data-placement="top"
                     title="Detalhes do pedido (Finalizado)" class="btn btn-small waves-effect green" style="width: 20px; height: 20px; padding: 0px 0px;"><span style="margin-top: 2px;" class="fa fa-list-alt"></span></a>

                   <a href="{{route('pdf.consignado', $compra->id)}}" title="Detalhes do pedido em formato pdf." target="_blank" style="width: 20px; height: 20px; padding: 0px 3px;" class="btn btn-small waves-effect btn-default"><i class="fa fa-file-pdf-o"></i></a>


                <!--<strong style="color:green;">Concluído</strong>-->
              </td>

               @elseif ($compra->status == 'EC')

              <td id="center">

                  <a href="{{route('pedido.infoConsig', $compra->id)}}" 
                     data-toggle="tooltip" 
                     data-placement="top"
                     title="Detalhes do pedido (Enviado ao Cliente)" class="btn btn-small waves-effect pull-center" style="width: 20px; height: 20px; padding: 0px 0px;"><span style="margin-top: 9px;" class="fa fa-list-alt"></span></a>

                         <a href="{{route('pdf.consignado', $compra->id)}}" title="Detalhes do pedido em formato pdf." target="_blank" style="width: 20px; height: 20px; padding: 0px 3px;" class="btn btn-small waves-effect btn-default"><i class="fa fa-file-pdf-o"></i></a>

                     

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

                    <tfoot>
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
            
          </tfoot>
    
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