        <div role="tabpanel">
            <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Home</a></li>
    <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Profile</a></li>
  </ul>
    <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="home">

          <table class='table table-striped sticky-header' id='example' >
          

            <thead>
            <tr class="warning"> 
              <th id="center">

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

                 </table>
                </div>
    <div role="tabpanel" class="tab-pane" id="profile"> 

        

        <table  class='table table-striped sticky-header' id='table2'>
           <thead>
            <tr class="warning"> 
              <th id="center">

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
        
</div>
  </div>

</div>
