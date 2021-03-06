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

     <div class="row" style="height: 50px; width: 1170px; position: fixed; background-color: white; z-index: 1001; top: 50px; margin-bottom: 70px;">
      <div class="col-md-12">
      <h2><b>Listagem de pedidos</b></h2>

       <a href="{{route('index')}}" 
            class="btn btn-small waves-effect waves-light  blue darken-2 pull-right" style="margin-top: -35px; width: 80px; height: 25px; padding: 2px 1px;">
            <i class="material-icons">add</i> <b>Novo</b></a> 
      <div class="divider" style="margin-bottom: 1px;"></div>
    </div>
    </div>
      
      <div class="row" style="height: 50px; width: 1170px; position: fixed; z-index: 1001; top: 100px; " >
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
       
          <div class="card-panel" style="height: 60px; margin-top: 2px; margin-bottom: 2px; padding: 12px 10px;">
           <div class="row"> 
            <div class="col-md-2">  
              <div class="input-field">   
                <select id="pedidoId" onchange="submit()" name="id">
                  @foreach($pedidos as $dp)
                  <option></option>
                  <option value="{{ $dp->id }}">Pedido N??{{ $dp->id }} {{$dp->Cliente->name}}</option>

                  @endforeach     
                </select>
                <label for="pedido_id" style="font-size: 15px; margin-top: -30px;">Pesquisa por Pedido</label>
              </div><br>
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
                  placeholder:'---N??Pedido---'
                });
              </script>
              @endif
              
              
            </div>          
             <div class="col-md-4">  
              <div class="input-field">   
                <select id="cliente" onchange="submit()" name="id_cliente">
                  @foreach($dadosClientes as $dc)
                  <option></option>
                  <option value="{{ $dc->id }}">{{ $dc->id }}. {{$dc->name}}. Cel:{{$dc->cel}}. Doc.{{$dc->cpf}} {{$dc->cnpj}}</option>

                  @endforeach     
                </select>
                <label for="id_cliente" style="font-size: 15px; margin-top: -30px;">Pesquisa por Cliente</label>
              </div><br>
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
                  placeholder:'---Selecione o Cliente---'
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
                <label for="vendedor" style="font-size: 15px; margin-top: -30px;">Pesquisa por Vendedor</label>
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
                  placeholder:'---Selecione o Vendedor---'
                });
              </script> 
              @endif                  

            </div>

            <div class="col-md-2">
              <div class="input-field">   
                <select onchange="submit()" name="status" class="form-control" style="height: 29px;" title="Status de acompanhamento do pedido">                                      
                  
                 @if (empty($status))
                 <option value="">--Status--</option>
                 <option value="EC" title="Pedidos Enviados ao cliente">Enviados </option>
                 <option value="RE" title="Pedidos Reservados">Reservados </option> 
                 <option value="CA" title="Pedidos Cancelados">Cancelados </option>                                 
                 <option value="FI" title="Pedidos Finalizados">Finalizados </option>
                 <option value="CA" title="Pedidos Cancelados">Cancelados </option>
                 <option value="EL" title="Pedidos Encaminhados ao Laborat??rio">Encaminhados ao Lab. </option>
                 <option value="AP" title="Pedidos Aguardando Pagamento">Aguardando Pagamento </option>                 
            
                 
                 <!--<option value="GE" title="Pedidos Em Aberto">Em Aberto </option>-->
                 @else
                 

                 @if ($status == 'FI') 
                 <option value="FI" title="Pedidos Finalizados">Finalizado</option>
                 @elseif ($status == 'CA')
                 <!--<option value="E" title="Link de cadastro enviado">Enviado</option>-->
                 <option value="CA" title="Pedidos Cancelados">Cancelado </option>  
                 @elseif ($status == 'RE')                 
                 <option value="RE" title="Pedidos Reservados">Reservado </option>
                  @elseif ($status == 'AP') 
                 <option value="AP" title="Pedidos Aguardando Pagamento">Aguardando Pagamento </option>
                 @elseif ($status == 'EL')
                  <option value="EL" title="Pedidos Encaminhados ao Laborat??rio">Encaminhados ao Lab. </option>
                  @elseif ($status == 'EC')
                  <option value="EC" title="Pedidos Enviados ao cliente">Enviados </option>                  

                 @else ($status == 'GE')  
                 <!--<option value="GE" title="Pedidos Em Aberto">Em Aberto </option>-->
                 @endif

                 @endif


                 
                 
                 
               </select>
               <label for="status" style="font-size: 15px;  margin-top: -30px;">Pesquisa por Status</label>
             </div>         
           </div>



           <a href="{{route('pedido.compras')}}" style="margin-top: 8px;" title="Limpar Pesquisa" class="btn btn-small waves-effect pull-center"><i class="fa fa-eraser"></i></a>
         
    

       </div>
   </div>
       
     </form>
   </div>
 </div>


 <div class="row" style="margin-top: 100px;">
   <div class="col-md-12">   
        <table class="table table-sm table-striped table-bordered table-condensed table-hover" id="example" style="width:100%">
           <thead>
            <tr class="warning">             
                       <th>C??digo</th>
                       <th>Cliente</th>
                       <th>Registro</th> 
                       <th>Entrega</th> 
                       <th>Pedido</th> 
                       <th>Pagamento</th>
                       <th>Produtos</th> 
                       <th>Frete</th>                      
                       <th>Total Geral</th>
                       <th> Detalhes</th>
                     </tr>
                   </thead>
         
                   <tbody>
                  
                     @foreach ($pedidos as $compra)
                     <tr>


                    
                    <td title="{{ $compra->id }}" id="center">
                      <span class="chip">{{ $compra->id }}</span>
                    </td>
                    <td title="Vendedor: {{ ($compra->Vendedor->name) }}">
                     {{ ($compra->Cliente->name) }}
                   </td>
                   <td title="{{ $compra->created_at->format('d/m/Y H:i') }}">{{ $compra->created_at->format('d/m/Y') }}</td>  
                   

                   <td title="@if (isset($compra->Frete->local)) Local de Entrega: {{($compra->Frete->endere??o)}} {{($compra->Frete->numero)}} {{($compra->Frete->bairro)}} {{($compra->Frete->cidade)}} {{($compra->Frete->estado)}}  {{($compra->Frete->cep)}} @elseif (isset($compra->Frete->balcao)) Nossa Loja  @else Local de Entrega: {{($compra->Cliente->endere??o)}} {{($compra->Cliente->numero)}} {{($compra->Cliente->bairro)}} {{($compra->Cliente->cidade)}} {{($compra->Cliente->estado)}} {{($compra->Cliente->cep)}} @endif" > @if (isset($compra->Frete->pedido_id)) {{ $compra->Frete->entrega == NULL ? 'Retirada Balc??o' : '' }} {{ $compra->Frete->entrega == 'CA' ? 'Entrega Cancelada' : '' }} {{ $compra->Frete->entrega == 'FI' ? 'Entrega Conclu??da' : '' }}{{ $compra->Frete->entrega == 'C' ? ' Correios' : '' }}{{ $compra->Frete->entrega == 'B' ? 'Moto Boy' : '' }} @else Em berto @endif </td>
                   <td title="{{ $compra->status == 'RE' ? 'Reservado' : '' }}{{ $compra->status == 'AP' ? 'Aguardando Pagamento' : '' }}{{ $compra->status == 'EL' ? 'Encaminhado ao Laboratorio' : '' }}{{ $compra->status == 'EC' ? 'Enviado ao Cliente' : '' }}{{ $compra->status == 'FI' ? 'Finalizado' : '' }}{{ $compra->status == 'GE' ? 'Em Aberto' : '' }}{{ $compra->status == 'CA' ? 'Cancelado' : '' }} {{ $compra->updated_at->format('d/m/Y H:i') }}">{{ $compra->status == 'RE' ? 'Reservado' : '' }}{{ $compra->status == 'GE' ? 'Em aberto' : '' }}{{ $compra->status == 'AP' ? 'Aguardando Pagamento' : '' }}{{ $compra->status == 'EL' ? 'Encaminhado ao Laboratorio' : '' }}{{ $compra->status == 'EC' ? 'Enviado ao Cliente' : '' }}{{ $compra->status == 'FI' ? 'Finalizado' : '' }}{{ $compra->status == 'CA' ? 'Cancelado' : '' }}</td> 
                   <td title="{{ $compra->pagamento == 'D' ? 'Dinheiro' : '' }}">{{ $compra->pagamento == 'D' ? 'Dinheiro' : '' }}{{ $compra->pagamento == 'CC' ? 'Cart??o de Cr??dito' : '' }}{{ $compra->pagamento == 'CD' ? 'Cart??o de D??bito' : '' }}{{ $compra->pagamento == 'BB' ? 'Boleto Banc??rio' : '' }}{{ $compra->pagamento == 'DB' ? 'Dep??sito Banc??rio' : '' }}</td>                         
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
                   $total_produto = $total_produtos - $total_desconto;

                   @endphp                
                                                      
                   <td title="@if (isset($total_desconto)) Desconto: R$ {{ number_format($total_desconto, 2, ',', '.') }} @else Desconto: R$ 0,00 @endif">R$ {{ ($total_produto) }}</td>             
                   

                   @elseif ($compra->Frete->status == 'EMB' && $compra->Frete->entrega == 'B')                 
                   @php
                   $total_produtos = $compra->pedido_produtos_itens->sum('prod_preco_padrao');
                   $total_desc_produto = $compra->pedido_produtos_itens->sum('prod_desconto');
                   $total_desc_request = $compra->pedido_produtos_itens->sum('request_desconto');
                   $total_desconto = $total_desc_request + $total_desc_produto;
                   $total_produto = $total_produtos - $total_desconto;
                   @endphp               

                   <td title="@if (isset($total_desconto)) Desconto: R$ {{ number_format($total_desconto, 2, ',', '.') }} @else Desconto: R$ 0,00 @endif">R$ {{ ($total_produto) }}</td>              

                   

                   @else ($compra->Frete->status == 'EC' && $compra->Frete->entrega == 'C')
                   
                   @php
                   $total_produtos = $compra->pedido_produtos_itens->sum('prod_preco_padrao');
                   $total_desc_produto = $compra->pedido_produtos_itens->sum('prod_desconto');
                   $total_desc_request = $compra->pedido_produtos_itens->sum('request_desconto');
                   $total_desconto = $total_desc_request + $total_desc_produto;
                   $total_produto = $total_produtos - $total_desconto;
                   @endphp
                   
                   <td title="@if (isset($total_desconto)) Desconto: R$ {{ number_format($total_desconto, 2, ',', '.') }} @else Desconto: R$ 0,00 @endif">R$ {{ ($total_produto) }}</td>           
                   
                   @endif            



                   <td>R$ {{ number_format($compra->Frete->valor, 2, ',', '.') }}</td>  

                
                  
                  
                



                   <td>R$ {{ ($total_pedido) }}</td>

                   @if(($compra->status == 'RE') || ($compra->status == 'AP') || ($compra->status == 'EL') )                 
                   

                   <td id="center">
                    <a href="{{route('pedidos.edit', $compra->id)}}" 
                     data-toggle="tooltip" 
                     data-placement="top"
                     title="Alterar" class="btn btn-small waves-effect amber" style="padding: 1px 5px;"><i class="small material-icons"  style="margin-top: 5px;">border_color</i></a>

                       <a href="{{route('pdf', $compra->id)}}" title="Detalhes do pedido em formato pdf." target="_blank" style="padding: 5px 5px;" class="btn btn-small waves-effect btn-default"><i class="fa fa-file-pdf-o"></i></a>
                                    <!--<form style="display: inline-block;" method="POST" action="{{route('pedidos.destroy', $compra->id)}}"data-toggle="tooltip" data-placement="top" title="Excluir" onsubmit="return confirm('Confirma exclus??o?')">
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
                     title="Detalhes do pedido (Cancelado)" class="btn waves-effect red"><span class="glyphicon glyphicon-info-sign"></span></a>

               <!-- <strong style="color:red;">Cancelado</strong>-->
              </td>

              @elseif ($compra->status == 'FI')

              <td id="center">

                  <a href="{{route('pedido.info', $compra->id)}}" 
                     data-toggle="tooltip" 
                     data-placement="top"
                     title="Detalhes do pedido (Finalizado)" class="btn btn-small waves-effect green" style="padding: 1px 3px;"><span style="margin-top: 9px;" class="fa fa-list-alt"></span></a>

                   <a href="{{route('pdf', $compra->id)}}" title="Detalhes do pedido em formato pdf." target="_blank" style="padding: 5px 5px;" class="btn btn-small waves-effect btn-default"><i class="fa fa-file-pdf-o"></i></a>


                <!--<strong style="color:green;">Conclu??do</strong>-->
              </td>

               @elseif ($compra->status == 'EC')

              <td id="center">

                  <a href="{{route('pedido.info', $compra->id)}}" 
                     data-toggle="tooltip" 
                     data-placement="top"
                     title="Detalhes do pedido (Enviado ao Cliente)" class="btn btn-small waves-effect pull-center" style="padding: 1px 3px;"><span style="margin-top: 9px;" class="fa fa-list-alt"></span></a>

                         <a href="{{route('pdf', $compra->id)}}" title="Detalhes do pedido em formato pdf." target="_blank" style="padding: 5px 5px;" class="btn btn-small waves-effect btn-default"><i class="fa fa-file-pdf-o"></i></a>

                     

                <!--<strong style="color:green;">Conclu??do</strong>-->
              </td>




              @else ($compra->status == 'GE')
              <td id="center">
                <strong style="color:blue;">Em aberto</strong>
              </td>

              @endif 



              @endforeach


              
            </tr>
          </tbody>
    
        </table>
        



     
<hr style="margin-top: 50px; margin-bottom: 5px;">
     
      <p class="lead" id="black">Total: R$ {{ number_format($total_preco, 2, ',', '.') }}</p>
      <div class="divider" style="margin-bottom: 10px; margin-top: -15px;"></div>

     <!-- @forelse ($compras as $pedido)
      <input type="hidden" name="pedido_id" value="{{ $pedido->id }}"/>
      @empty
      <h5 id="center">
      Voc?? ainda n??o fez nenhum pedido.</h5>
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
            "lengthMenu": " <div style='font-size: 12px;'>_MENU_ Registros por p??gina</div>",             
            "zeroRecords": "Nenhuma Informa????o localizada",
            "info": "Mostrando p??gina _PAGE_ de _PAGES_  - Total de Registros _MAX_ ",
            "infoEmpty": "Nenhum registro dispon??vel",
            "infoFiltered": "(filtrado do _MAX_ total registros)",
      "paginate": {

        "first":      "Primeiro",
        "last":       "??ltimo",
        "next":       "Pr??ximo",
        "previous":   "Anterior"
    },
            //"sSearch": false,




        },
        //"aaSorting": [[1, 'desc']],
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

      /*"sLengthMenu": "Mostrar _MENU_ registros por p??gina",
            "sZeroRecords": "Nenhum registro encontrado",
            "sInfo": "Mostrando _START_ / _END_ de _TOTAL_ registro(s)",
            "sInfoEmpty": "Mostrando 0 / 0 de 0 registros",
            "sInfoFiltered": "(filtrado de _MAX_ registros)",
            "sSearch": "Pesquisar: ",
            "oPaginate": {
                "sFirst": "In??cio",
                "sPrevious": "Anterior",
                "sNext": "Pr??ximo",
                "sLast": "??ltimo"
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


</script>



@endsection