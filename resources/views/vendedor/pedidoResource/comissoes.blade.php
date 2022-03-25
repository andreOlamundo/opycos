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

     <div class="row" style="height: 50px; width: 1170px; position: fixed; background-color: white; z-index: 1001; top: 50px; margin-bottom: 60px;">
      <div class="col-md-12">
      <h2><b>Comissões</b></h2>
      <div class="divider" style="margin-bottom: 1px;"></div>
    </div>
    </div>
      
      <div class="row"style="height: 50px; width: 1170px; position: fixed; z-index: 1001; top: 100px; " >
        <div class="col-md-12">
     

        @if (Session::has('mensagem-sucesso'))
        <div class="alert alert-success alert-dismissible fade in" style="margin-bottom: 1px;">
          <strong>{{ Session::get('mensagem-sucesso') }}</strong>
          <a href="#" class="close" 
          data-dismiss="alert"
          aria-label="close">&times;</a>
        </div>
        <script type="text/javascript">
            $(".alert-dismissible").fadeTo(8000, 500).slideUp(500, function(){
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

        <form method="POST" action="{{route('Comissoesint.search')}}"> 
          {{ csrf_field() }}
       
          <div class="card-panel" style="height: 60px; margin-top: 0px; margin-bottom: 2px; padding: 12px 10px;">
           <div class="row"> 
            <!--<div class="col-md-1">  
              <div class="input-field" style="margin-right: -25px;">   
                <select id="pedidoId" onchange="submit()" name="id">
                  @foreach($pedidos as $dp)
                  <option></option>
                  <option value="{{ $dp->id }}">Pedido Nº{{ $dp->id }}</option>

                  @endforeach     
                </select>
                <label for="pedido_id" style="font-size: 15px; margin-top: -30px;">Pedido</label>
              </div><br>
              <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
              <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script> 
              @if (isset($pedido_id))                
              <script type="text/javascript">
                $("#pedidoId").select2({
                  placeholder:'<?php $i = 0; $len = count($pedidos); foreach ($pedidos as $pedido){  if ($i == 0) { $pedido->id; echo $pedido->id;  }  else if ($i == $len - 1) { 

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
              
              
            </div> -->
             <div class="col-md-1"> 
        
                <div class="input-field" style="margin-right: -25px;">   
                <select onchange="submit()" name="updated_at" class="form-control" style="height: 29px;" title="Ano"> 
               @if (empty($ano))
                 <option value="">Tudo</option>
                 <option value="2020" title="Ano"> 2020 </option> 
                 <option value="2021" title="Ano"> 2021 </option>  
                 <option value="2022" title="Ano"> 2022 </option>                       
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

                 @endif              
                  
               
               </select>
               <label for="updated_at" style="font-size: 15px;  margin-top: -30px;">Ano</label>
             </div> 
              
      
              
              
            </div>  
                     <div class="col-md-1">  
                <div class="input-field" style="margin-right: -25px;">   
                <select name="periodo" onchange="submit()" class="form-control" style="height: 29px;" title="Período"> 
                 @if (empty($periodo)) 
                <option value="">Tudo</option>  
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
               <label for="periodo" style="font-size: 15px;  margin-top: -30px;">Período</label>
             </div> 
              
            </div>       
             <!--<div class="col-md-4">  
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
              
              


              @else    
              <script type="text/javascript">
                $("#cliente").select2({
                  placeholder:'---Selecione o Cliente---'
                });
              </script>
              @endif
              
              
            </div>-->




               <div class="col-md-2">
              <div class="input-field" style="margin-right: -25px;">   
                <select name="status"  onchange="submit()" class="form-control" style="height: 29px;" title="Status do pedido">                                      
                  
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
                 <option value="FI" style="display: none;" title="Pedidos Finalizados">Finalizado</option><!--style="background-color: #1e90ff;"-->
                 @elseif ($status == 'CA')
                 <!--<option value="E" title="Link de cadastro enviado">Enviado</option>-->
                 <option value="CA" style="display: none;" title="Pedidos Cancelados">Cancelado </option>  
                 @elseif ($status == 'RE')                 
                 <option value="RE" style="display: none;" title="Pedidos Reservados">Reservado </option>
                  @elseif ($status == 'AP') 
                 <option value="AP" style="display: none;" title="Pedidos Aguardando Pagamento">Ag. Pagamento </option>
                 @elseif ($status == 'EL')
                  <option value="EL" style="display: none;" title="Pedidos Encaminhados ao Laboratório">Enc. ao Lab. </option>
                  @elseif ($status == 'EC')
                  <option value="EC" style="display: none;" title="Pedidos Enviados ao cliente">Enviados </option>                  

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
               <label for="status" style="font-size: 15px;  margin-top: -30px;">Status(P)</label>
             </div>
             

           </div>

                      <div class="col-md-2">
              <div class="input-field" style="margin-right: -10px;">   
                <select onchange="submit()" name="comissao" class="form-control" style="height: 29px;" title="Status da comissão">                                      
                  
                 @if (empty($comissao))
                 <option value=""></option>
                 <option value="PE" title="Comissões Pendentes"> Comissão Pendente </option>
                 <option value="PA" title="Comissões Pagas"> Comissão Paga </option>           
                 @else                 

                 @if ($comissao == 'PE') 
                 <option value="PE" style="display: none;" title="Comissões Pendentes"> Comissão Pendente </option>
                 @else ($comissao == 'PA')
                 <option value="PA" style="display: none;" title="Comissões Pagas"> Comissão Paga </option>
                
                 @endif
                 <option value="PE" title="Comissões Pendentes"> Comissão Pendente </option>
                 <option value="PA" title="Comissões Pagas"> Comissão Paga </option>

                 @endif
               </select>
               <label for="comissao" style="font-size: 15px;  margin-top: -30px;">Status(C)</label>
             </div>  
             <input type="hidden" name="consignado" value="N" > 
              
           </div>
            <input type="hidden" name="vendedor_id" value="{{$idvendedor}}">


           <a href="{{route('pedidoint.allcomissoes')}}" style="margin-top: 10px; width: 30px; height: 28px; padding: 2px 1px;" title="Limpar Pesquisa" class="btn btn-small waves-effect pull-center"><i class="fa fa-eraser"></i></a>
         
    

       </div>
   </div>
       
     </form>
   </div>
 </div>


 <div class="row" style="margin-top: 95px;">
   <div class="col-md-12">   
        <table class="table table-sm table-striped table-bordered table-condensed table-hover" id="example" class="display" style="width:100%;">
           <thead>
            <tr class="warning">             
                       <th>Cod</th>
                        <th title="Data de registro do Pedido">Registro</th>
                        <th title="Data de finalização do Pedido">Finalização</th> 
                        <th title="Status do Pedido">Status</th>                   
                        <th>Vendedor</th>
                        <th>Cliente</th>
                        <th>Produtos</th>
                        <th>Desconto</th> 
                        <!--<th>Frete</th>-->
                        <th>Total</th>            
                        <!--<th title="Status do pagamento da comissão">Status(C)</th>-->
                         <th>Comissão</th>
                         <th title="Status da comissão">Status</th>
                    
                
                     </tr>
                   </thead>
         
                   <tbody>
                  
                     @foreach ($pedidos as $compra)
                     <tr>

                    <td title="{{ $compra->id }}" id="center">
                     {{ $compra->id }}
                    </td>
                    <td>{{$compra->created_at->format('d/m/Y') }} </td>
                      @if ($compra->status == 'FI')<td title="Finalizado" style="color: blue;"> {{ $compra->updated_at->format('d/m/Y') }}</td> @else <td title="Pendente" style="color: red;">{{ $compra->updated_at->format('d/m/Y') }}</td>@endif 
                    <td>{{ $compra->status == 'RE' ? 'Reservado' : '' }}{{ $compra->status == 'GE' ? 'Em aberto' : '' }}{{ $compra->status == 'AP' ? 'Ag. Pag.' : '' }}{{ $compra->status == 'EL' ? 'Enc.Lab' : '' }}{{ $compra->status == 'EC' ? 'Enviado ao Cliente' : '' }}{{ $compra->status == 'FI' ? 'Finalizado' : '' }}{{ $compra->status == 'CA' ? 'Cancelado' : '' }}</td>        
                     <td style="width: 250px;">
                     @php 
                     $name = $compra->Vendedor->name; echo substr($name, 0, 24); $result = strlen($name); @endphp @if ($result > 24) @php
                     echo '...'; @endphp @else @endif</td>
                    <td  title="Cliente:@if ($compra->consignado == 'N') {{$compra->Cliente->name}}  @else Consignado @endif" style="width: 250px;">
                    @if ($compra->consignado == 'N') 
                    @php 
                     $name = $compra->Cliente->name; echo substr($name, 0, 15); $result = strlen($name); @endphp @if ($result > 15) @php echo '...';          @endphp @else @endif @else Consignado @endif
                     </td>
                                        
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
                   
                   $total_pedido = 0;
                   $total_desc_produto = $compra->pedido_produtos_itens->sum('prod_desconto');
                   $total_desc_request = $compra->pedido_produtos_itens->sum('request_desconto');
                   $total_desconto = $total_desc_request + $total_desc_produto;
                   $total_pedido += $total_produto - $total_desconto;              
                   @endphp
                 

                   @elseif ($compra->Frete->status == 'EC' && $compra->Frete->entrega == 'C')
                 
                   @php                 
                   $total_produto = $compra->pedido_produtos_itens->sum('prod_preco_padrao');                
                   $total_pedido = 0;
                   $total_desc_produto = $compra->pedido_produtos_itens->sum('prod_desconto');
                   $total_desc_request = $compra->pedido_produtos_itens->sum('request_desconto');
                   $total_desconto = $total_desc_request + $total_desc_produto;
                   $total_pedido += $total_produto - $total_desconto;
                   @endphp
                

                   @elseif ($compra->Frete->status == 'EMB' && $compra->Frete->entrega == 'CA')
                  
                   @php
                   $total_produto = $compra->pedido_produtos_itens->sum('prod_preco_padrao');
                   $total_pedido = 0;
                   $total_desc_produto = $compra->pedido_produtos_itens->sum('prod_desconto');
                   $total_desc_request = $compra->pedido_produtos_itens->sum('request_desconto');
                   $total_desconto = $total_desc_request + $total_desc_produto;
                   $total_pedido += $total_produto - $total_desconto;
                   @endphp
                  

                   @elseif ($compra->Frete->status == 'EC' && $compra->Frete->entrega == 'CA')
                 
                   @php
                   $total_produto = $compra->pedido_produtos_itens->sum('prod_preco_padrao');
                   $total_pedido = 0;
                   $total_desc_produto = $compra->pedido_produtos_itens->sum('prod_desconto');
                   $total_desc_request = $compra->pedido_produtos_itens->sum('request_desconto');
                   $total_desconto = $total_desc_request + $total_desc_produto;
                   $total_pedido += $total_produto - $total_desconto;              
                   @endphp
                 

                   @elseif ($compra->Frete->status == 'EMB' && $compra->Frete->entrega == 'FI')
                 
                   @php
                   $total_produto = $compra->pedido_produtos_itens->sum('prod_preco_padrao');
                   $total_pedido = 0;
                   $total_desc_produto = $compra->pedido_produtos_itens->sum('prod_desconto');
                   $total_desc_request = $compra->pedido_produtos_itens->sum('request_desconto');
                   $total_desconto = $total_desc_request + $total_desc_produto;
                   $total_pedido += $total_produto - $total_desconto;              
                   @endphp


                   @else ($compra->Frete->status == 'EC' && $compra->Frete->entrega == 'FI')
                  
                   @php
                   $total_produto = $compra->pedido_produtos_itens->sum('prod_preco_padrao');
                   $total_pedido = 0;
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
                   

                   @elseif ($compra->Frete->status == 'EMB' && $compra->Frete->entrega == 'B')                 
                   @php
                   $total_produtos = $compra->pedido_produtos_itens->sum('prod_preco_padrao');
                   $total_desc_produto = $compra->pedido_produtos_itens->sum('prod_desconto');
                   $total_desc_request = $compra->pedido_produtos_itens->sum('request_desconto');
                   $total_desconto = $total_desc_request + $total_desc_produto;
                   $total_produto = $total_produtos;
                   @endphp               

                   <td>R$ {{ number_format($total_produto, 2, ',', '.') }}</td>              

                   

                   @else ($compra->Frete->status == 'EC' && $compra->Frete->entrega == 'C')
                   
                   @php
                   $total_produtos = $compra->pedido_produtos_itens->sum('prod_preco_padrao');
                   $total_desc_produto = $compra->pedido_produtos_itens->sum('prod_desconto');
                   $total_desc_request = $compra->pedido_produtos_itens->sum('request_desconto');
                   $total_desconto = $total_desc_request + $total_desc_produto;
                   $total_produto = $total_produtos;
                   @endphp
                   
                   <td style="width: 100px;">R$ {{ number_format($total_produto, 2, ',', '.') }}</td>           
                   
                   @endif 

                   

                  <td> @if (isset($total_desconto)) R$ {{ number_format($total_desconto, 2, ',', '.') }} @else R$ 0,00 @endif</td>

                  <!--<td>@if(isset($compra->Frete->valor)) R$ {{ number_format($compra->Frete->valor, 2, ',', '.') }} @else R$ 0,00 @endif</td>-->

                  <td style="width: 100px;" title="@if(isset($compra->Frete->valor))- R$ {{ number_format($compra->Frete->valor, 2, ',', '.') }} Frete @else R$ 0,00 Frete @endif">R$ {{ number_format($total_pedido, 2, ',', '.') }}</td>

                    <!--@if ($compra->Comissao->status == 'PE')
                   <td style="color: red;" title="Pagamento Pendente">Pendente</td>
                @else ($compra->Comissao->status == 'PA')
                <td title="Pago em {{ $compra->Comissao->updated_at->format('d/m/Y') }}" style="color: blue;">Paga em:<br>{{ $compra->Comissao->updated_at->format('d/m/Y') }}</td>
                @endif-->




                @if ($compra->Comissao->status == 'PA')
                   <td>R$ {{ number_format($compra->Comissao->valor_comissao, 2, ',', '.') }}</td>
                @else ($compra->Comissao->status == 'PE')
                <td>R$ {{ number_format($compra->Comissao->valor_comissao, 2, ',', '.') }}</td>
                @endif

                   @if ($compra->Comissao->status == 'PE')
                   <td style="color: red;" title="Pagamento Pendente">Pendente</td>
                @else ($compra->Comissao->status == 'PA')
                <td title="Paga: {{ $compra->Comissao->updated_at->format('d/m/Y') }} Obs: {{ ($compra->Comissao->obs_comissao) }} " style="color: blue;">Paga </td>
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
              <th style="border-right: none;">R${{ number_format($total_preco_prod, 2, ',', '.') }}</th>         
              <th style="border-right: none; color: red;">R${{ number_format($desconto, 2, ',', '.') }} </th>        
             <!--  //<th style="border-right: none; color: red;">R$ {{ number_format($frete_total, 2, ',', '.') }}</th>-->
                <th style="border-right: none;">R${{ number_format($total, 2, ',', '.') }}</th>
             <!-- //<th style="border-right: none;"></th>-->
                <th style="border-right: none;">R${{ number_format($total_preco, 2, ',', '.') }}</th>
                  <th></th>
            
              </tr>
          </tfoot>
    
        </table>

      
    </div>




  </div>
</div>
</div>
</div>
<!--Padrão de utilização do DTATABLE  -->
<!--Statyle 2--><link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap.min.css" rel="stylesheet"/>
<!--1--><script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<!--2--><script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<!--3--><script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap.min.js"></script>

<!--Data staly d/m/A-->
<script src=https://cdn.datatables.net/plug-ins/1.10.20/sorting/datetime-moment.js></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script>




<!--Auxiliares para controle de style do Botão-->
<!--Statyle 1--><!--<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"  rel="stylesheet"/>-->
<!--<link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap.min.css" rel="stylesheet"/>-->
<!--Statyle 3--><!--<link href="https://cdn.datatables.net/buttons/1.6.1/css/buttons.bootstrap.min.css" rel="stylesheet"/>-->
<!--<link href="https://cdn.datatables.net/buttons/1.6.1/css/buttons.bootstrap.min.css" rel="stylesheet"/>-->






<!--PDF CVS PRINT  -->
<!--Auxiliares para controle de script do Botão-->
<!--4--><script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
<!--5--><script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.bootstrap.min.js"></script>
<!--6--><script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<!--7--><script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<!--8--><script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<!--9--><script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>
<!--10--><script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.print.min.js"></script>
<!--11--><script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.colVis.min.js"></script>


<!-- Carrega Classe que compõe a Imagem do pdf -->
<script src="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css"></script>
<link href="https://cdn.datatables.net/buttons/1.6.1/css/buttons.dataTables.min.css" rel="stylesheet"/>


<!--<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.flash.min.js"></script>-->

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



//portrait

  $(document).ready(function() {

    $.fn.dataTable.moment('DD/MM/YYYY');
    $('#example').DataTable({



      //"pageLength": 50,



      "dom": '<"top"<"pull-right"B>i>rt<"bottom"<"col-md-5"fl><"col-md-7"p>><"clear">',


  
        buttons: [
        {
                extend: 'pdfHtml5',
                    customize: function ( doc ) {
                    doc.content.splice( 1, 0, {
                        margin: [ 0, 0, 0, 12 ],
                        alignment: 'center',
                        image: 'data:/img/logo-opycos.png;base64,iVBORw0KGgoAAAANSUhEUgAAAPsAAABECAYAAABH7kMGAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyZpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNi1jMDY3IDc5LjE1Nzc0NywgMjAxNS8wMy8zMC0yMzo0MDo0MiAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIDIwMTUgKFdpbmRvd3MpIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOkRERkNGQzcyMTdBQTExRTc5NzIyQjc1OUE1QzczQjNDIiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOkRERkNGQzczMTdBQTExRTc5NzIyQjc1OUE1QzczQjNDIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6RERGQ0ZDNzAxN0FBMTFFNzk3MjJCNzU5QTVDNzNCM0MiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6RERGQ0ZDNzExN0FBMTFFNzk3MjJCNzU5QTVDNzNCM0MiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz4LoucdAAAMAElEQVR42uxd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',
                         width: 90,
                        height: 22
                    } );
                },
                footer: true,              
                orientation : 'landscape',
                pageSize: 'A4',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9 ]
                  
                }

            },

                  {
                        extend: 'print',
                        exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9 ]
                }
                    },

                             {
                        extend: 'excel',
                        exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]

                }
                    },

                       
          'copy'
        ],

         

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



 table.buttons().container()
        .appendTo( '#example_wrapper .col-sm-6:eq(0)' );

   



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