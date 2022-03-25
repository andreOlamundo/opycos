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

     <div class="row" style="height: 80px; width: 70%; position: fixed; background-color: white; z-index: 1001; top: 50px; margin-bottom: 80px;">
      <div class="col-md-12">
      <h2><b>Listagem de pedidos</b></h2>

       <a href="{{route('index')}}" 
            class="btn btn-small waves-effect waves-light  blue darken-2 pull-right" style="margin-top: -35px;">
            <i class="material-icons">add</i> Novo</a> 
      <div class="divider" style="margin-bottom: 2px;"></div>
    </div>
    </div>
      
      <div class="row" style="height: 80px; width: 70.3%; position: fixed; z-index: 1001; top: 120px; " >
        <div class="col-md-12">
         <!--<ol class="breadcrumb" style="margin-bottom: 5px;">
          <li class="active">Pedidos</li>
          <li><a href="{{route('index')}}" id="btn" style="text-decoration: none"><b>Novo Pedido</b></a></li>
        </ol>-->

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

        <form method="POST" action="{{route('Pedido.search')}}"> 
          {{ csrf_field() }}
       
          <div class="card-panel" style="height: 80px;">
           <div class="row">
           
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
            <div class="col-md-4">
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
                 <option value="FI" title="Pedidos Finalizados">Finalizado</option>
                 <option value="CA" title="Pedidos Cancelados">Cancelado </option> 
                 <option value="RE" title="Pedidos Reservados">Reservado </option>
                 <option value="GE" title="Pedidos Em Aberto">Em Aberto </option>
                 @else
                 

                 @if ($status == 'FI') 
                 <option value="FI" title="Pedidos Finalizados">Finalizado</option>
                 @elseif ($status == 'CA')
                 <!--<option value="E" title="Link de cadastro enviado">Enviado</option>-->
                 <option value="CA" title="Pedidos Cancelados">Cancelado </option>  
                 @elseif ($status == 'RE')                 
                 <option value="RE" title="Pedidos Reservados">Reservado </option>
                 @else ($status == 'GE')  
                 <option value="GE" title="Pedidos Em Aberto">Em Aberto </option>
                 @endif
                 @endif


                 
                 
                 
               </select>
               <label for="status" style="font-size: 15px;  margin-top: -30px;">Pesquisa por Status</label>
             </div>         
           </div>


           <!--<a href="{{route('index')}}" 
           class="btn-floating btn-large waves-effect waves-light btn-primary pull-right" title="Novo Pedido">
           <i class="material-icons">add</i></a>-->


           <a href="{{route('pedido.compras')}}" style="margin-top: 8px;" title="Limpar Pesquisa" class="btn btn-small waves-effect pull-center"><i class="fa fa-eraser"></i></a>



   
         
       </div> 
   </div>
       
     </form>
   </div>
 </div>


 <div class="row">
   <div class="col-md-12" style="margin-top: 150px;">           
    
    
      <form method="POST" id="form-finalizar-pedido" action="{{ route('carrinho.finalizar') }}"> 
        {{ csrf_field() }}  

        <input type="hidden" id="selectAll" name="id[]"/>

      </form>

      
      <script src="https://cdnjs.cloudflare.com/ajax/libs/floatthead/2.1.4/jquery.floatThead.js"></script>
      <script type="text/javascript">
        
      </script>
 


      <form method="POST" id="form-cancelar-pedido"  action="{{ route('carrinho.cancelar') }}">
        {{ csrf_field() }} 

        

        <table class="table table-striped table-bordered table-condensed table-hover" id="example">
         
           <thead>             
            <tr class="warning"> 
              <th id="center" style=" width: 100px;">

                    <!--<button type="submit" class="btn-floating btn-small waves-effect waves-light red" data-position="top" data-delay="50" data-toggle="tooltip" data-placement="top" title="Cancelar itens selecionados">
                      <span class="glyphicon glyphicon-minus"></span></button>-->

                      <a href="#" name="cancelar" class="btn-floating btn-small waves-effect waves-light red" data-position="top" data-delay="50" data-toggle="tooltip" data-placement="top" title="Cancelar Pedidos Selecionados" >
                        <span class="glyphicon glyphicon-remove"></span></a>&nbsp; 

                        <a href="#" name="finalizar" class="btn-floating btn-small waves-effect waves-light green" data-position="top" data-delay="50" data-toggle="tooltip" data-placement="top" title="Finalizar Pedidos Selecionados">
                          <span class="glyphicon glyphicon-ok"></span></a>

                          <script type="text/javascript">

                            $(document).ready(function(){
                              $(".select").on("input", function(){
                                var textoDigitado = $(this).val();
                                var inputCusto = $(this).attr("selectAll");
                                $("#"+ inputCusto).val(textoDigitado);
                              });
                            });

                            $('[name="cancelar"]').on( "click", function()
                            {
                              
                             $('#form-cancelar-pedido').submit();
                           });                      
                            
                            $('[name="finalizar"]').on( "click", function()
                            {
                              
                             $('#form-finalizar-pedido').submit();
                           }); 
                         </script>


                         
                       </th>
                       <th id="center">Código</th>
                       <th id="center">Cliente</th>
                       <th id="center">Registro</th> 
                       <th id="center">Status Frete</th> 
                       <th id="center">Status Pedido</th> 
                       <th id="center">Pagamento</th>
                       <th id="center">Total Produtos</th> 
                       <th id="center">Custo Frete</th>                      
                       <th id="center">Total Geral</th>
                       <th id="center"> Detalhes</th>

                     </tr>
                     
                   </thead>              
                   <tbody>               
                  @foreach ($pedidos as $compra)
                     <tr>

                       <td id="center">
                        @if($compra->status == 'RE')
                        <p>
                          <label for="item-{{ $compra->id }}">
                            <input type="checkbox" id="item-{{ $compra->id }}" name="id[]" value="{{ $compra->id }}" class="select" selectAll='selectAll' />
                            <span>Selecionar</span>
                          </label>
                        </p>
                      </form>
                      
                      
                      
                      @elseif ($compra->status == 'CA')

                      
                      <i class="material-icons" style="color:red;">cancel</i>

                      @elseif ($compra->status == 'FI')
                      <i class="material-icons" style="color:green;">check_circle</i>

                      @else
                      <i class="material-icons" style="color:blue;">add_circle</i>


                      @endif 
                    </td> 

                    
                    <td title="{{ $compra->id }}" id="center">
                      <span class="chip">{{ $compra->id }}</span>
                    </td>
                    <td title="Vendedor: {{ ($compra->Vendedor->name) }}">
                     {{ ($compra->Cliente->name) }}
                   </td>
                   <td title="{{ $compra->created_at->format('d/m/Y H:i') }}">{{ $compra->created_at->format('d/m/Y H:i') }}</td>  
                   

                   <td title="@if (isset($compra->Frete->local)) Local de Entrega:{{($compra->Frete->endereço)}} {{($compra->Frete->numero)}} {{($compra->Frete->bairro)}} {{($compra->Frete->cidade)}} {{($compra->Frete->estado)}}  {{($compra->Frete->cep)}} @elseif (isset($compra->Frete->balcao)) Nossa Loja  @else Local de Entrega:{{($compra->Cliente->endereço)}} {{($compra->Cliente->numero)}} {{($compra->Cliente->bairro)}} {{($compra->Cliente->cidade)}} {{($compra->Cliente->estado)}}  {{($compra->Cliente->cep)}} @endif" > @if (isset($compra->Frete->pedido_id)) {{ $compra->Frete->entrega == NULL ? 'Aguandando Retirada' : '' }} {{ $compra->Frete->entrega == 'CA' ? 'Entrega Cancelada' : '' }} {{ $compra->Frete->entrega == 'FI' ? 'Entrega Concluída' : '' }}{{ $compra->Frete->entrega == 'C' ? 'Entrega Correios' : '' }}{{ $compra->Frete->entrega == 'B' ? 'Entrega Moto Boy' : '' }} @else Em berto @endif </td>
                   <td title="{{ $compra->status == 'RE' ? 'Reservado' : '' }} {{ $compra->status == 'AG' ? 'Alterado' : '' }} {{ $compra->status == 'FI' ? 'Finalizado' : '' }} {{ $compra->status == 'GE' ? 'Em Aberto' : '' }} {{ $compra->status == 'CA' ? 'Cancelado' : '' }} {{ $compra->updated_at->format('d/m/Y H:i') }}">{{ $compra->status == 'RE' ? 'Reservado' : '' }} {{ $compra->status == 'GE' ? 'Em aberto' : '' }} {{ $compra->status == 'AG' ? 'Alterado' : '' }} {{ $compra->status == 'FI' ? 'Finalizado' : '' }} {{ $compra->status == 'CA' ? 'Cancelado' : '' }} </td> 
                   <td title="{{ $compra->pagamento == 'D' ? 'Dinheiro' : '' }}">{{ $compra->pagamento == 'D' ? 'Dinheiro' : '' }}{{ $compra->pagamento == 'CC' ? 'Cartão de Crédito' : '' }}{{ $compra->pagamento == 'CD' ? 'Cartão de Débito' : '' }}{{ $compra->pagamento == 'BB' ? 'Boleto Bancário' : '' }}</td>         
                   
                   

                   @php
                   $total_pedido = 0;
                   @endphp
                   @foreach ($compra->pedido_produtos_itens as $pedido_produto)


                   


                   @if (isset($compra->Frete->pedido_id))

                   @if ($compra->Frete->status == 'AR' && $compra->Frete->balcao == 'Y')
                   @php
                   $total_produto = $compra->pedido_produtos_itens->sum('prod_preco_padrao'); 
                   $total_pedido = $total_produto;
                   @endphp           


                   @elseif ($compra->Frete->status == 'EMB' && $compra->Frete->entrega == 'B')
                   @foreach($valorFrete as $total_frete)
                   @php
                   $total_produto = $compra->pedido_produtos_itens->sum('prod_preco_padrao');
                   $total_pedido = $total_frete->valor;
                   $total_pedido += $total_produto;              
                   @endphp
                   @endforeach

                   @elseif ($compra->Frete->status == 'EC' && $compra->Frete->entrega == 'C')
                   @foreach($valorFreteC as $total_frete)
                   @php                 
                   $total_produto = $compra->pedido_produtos_itens->sum('prod_preco_padrao');                
                   $total_pedido = $total_frete->valor;
                   $total_pedido += $total_produto;
                   @endphp
                   @endforeach

                   @elseif ($compra->Frete->status == 'EMB' && $compra->Frete->entrega == 'CA')
                   @foreach($valorFrete as $total_frete)
                   @php
                   $total_produto = $compra->pedido_produtos_itens->sum('prod_preco_padrao');
                   $total_pedido = $total_frete->valor;
                   $total_pedido += $total_produto;              
                   @endphp
                   @endforeach

                   @elseif ($compra->Frete->status == 'EC' && $compra->Frete->entrega == 'CA')
                   @foreach($valorFreteC as $total_frete)
                   @php
                   $total_produto = $compra->pedido_produtos_itens->sum('prod_preco_padrao');
                   $total_pedido = $total_frete->valor;
                   $total_pedido += $total_produto;              
                   @endphp
                   @endforeach

                   @elseif ($compra->Frete->status == 'EMB' && $compra->Frete->entrega == 'FI')
                   @foreach($valorFrete as $total_frete)
                   @php
                   $total_produto = $compra->pedido_produtos_itens->sum('prod_preco_padrao');
                   $total_pedido = $total_frete->valor;
                   $total_pedido += $total_produto;              
                   @endphp
                   @endforeach

                   @else ($compra->Frete->status == 'EC' && $compra->Frete->entrega == 'FI')
                   @foreach($valorFreteC as $total_frete)
                   @php
                   $total_produto = $compra->pedido_produtos_itens->sum('prod_preco_padrao');
                   $total_pedido = $total_frete->valor;
                   $total_pedido += $total_produto;              
                   @endphp
                   @endforeach

                   @endif
                   
                   

                   @else

                   @php
                   $total_produto = $compra->pedido_produtos_itens->sum('prod_preco_padrao'); 
                   $total_pedido = $total_produto;
                   @endphp 

                   @endif

                   @endforeach



                   
                   @if (isset($compra->Frete->pedido_id))

                   @if ($compra->Frete->status == 'AR' && $compra->Frete->balcao == 'Y')
                   @php
                   $total_produto = $compra->pedido_produtos_itens->sum('prod_preco_padrao');
                   @endphp   
                   <td>R$ {{ number_format($total_produto, 2, ',', '.') }}</td>             
                   

                   @elseif ($compra->Frete->status == 'EMB' && $compra->Frete->entrega == 'B')                 
                   @php
                   $total_produto = $compra->pedido_produtos_itens->sum('prod_preco_padrao');
                   @endphp               

                   <td>R$ {{ number_format($total_produto, 2, ',', '.') }}</td>              

                   

                   @else ($compra->Frete->status == 'EC' && $compra->Frete->entrega == 'C')
                   
                   @php
                   $total_produto =$compra->pedido_produtos_itens->sum('prod_preco_padrao'); 
                   @endphp
                   
                   <td>R$ {{ number_format($total_produto, 2, ',', '.') }}</td>           
                   
                   @endif            



                   <td>R$ {{ number_format($compra->Frete->valor, 2, ',', '.') }}</td>  

                   @else

                   @php
                   $total_produto = $compra->pedido_produtos_itens->sum('prod_preco_padrao');
                   @endphp   
                   <td>R$ {{ number_format($total_produto, 2, ',', '.') }}</td>  
                   <td>R$ 0,00 </td><!-- inclui uma tag <td> quando o pedido ainda esta em aberto-->
                   @endif  



                   <td title="R$ {{ number_format($total_pedido, 2, ',', '.') }}">R$ {{ number_format($total_pedido, 2, ',', '.') }}</td>
                   @if($compra->status == 'RE')                 
                   

                   <td id="center">
                    <a href="{{route('pedidos.edit', $compra->id)}}" 
                     data-toggle="tooltip" 
                     data-placement="top"
                     title="Alterar" class="btn waves-effect amber"><i class="small material-icons">border_color</i></a>
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
                     title="info" class="btn waves-effect red"><span class="glyphicon glyphicon-info-sign"></span></a>

               <!-- <strong style="color:red;">Cancelado</strong>-->
              </td>

              @elseif ($compra->status == 'FI')

              <td id="center">

                  <a href="{{route('pedido.info', $compra->id)}}" 
                     data-toggle="tooltip" 
                     data-placement="top"
                     title="info" class="btn waves-effect green"><span class="glyphicon glyphicon-info-sign"></span></a>

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
           
            
            
            
            
           
          </tfoot>
        </table>
        



     

      <div class="divider" style="margin-bottom: 5px;"></div>
      <p class="lead" id="black">Total: R$ {{ number_format($total_preco, 2, ',', '.') }}</p>
      <div class="divider" style="margin-bottom: 10px; margin-top: -15px;"></div>

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
<script type="text/javascript">


  $(document).ready(function() {
    $('#example').DataTable({
       "dom": '<"top"i>rt<"bottom"flp><"clear">',
      "language": {
            "lengthMenu": " <div style='margin-bottom: -50px; font-size: 12px;'>_MENU_ Registros por página</div>",             
            "zeroRecords": "Nenhuma Informação localizada",
            "info": "Mostrando página _PAGE_ de _PAGES_  - Total de Registros _MAX_ ",
            "infoEmpty": "Nenhum registro disponível",
            "infoFiltered": "(filtrado do _MAX_ total registros)",
            //"sSearch": false,

            "paginate": {
        "first":      "Primeiro",
        "last":       "Último",
        "next":       "Próximo",
        "previous":   "Anterior"
    }

        },
       // scrollY: 300,
          //"pageLength": 50  

    });




   



} );

  $.extend( $.fn.dataTable.defaults, {
    searching: false,
   // ordering:  false

} );


</script>



@endsection