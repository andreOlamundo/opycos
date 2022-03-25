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
         <h2><b> Lista de Clientes</b></h2> 
         <a href="{{route('clientesinter.create')}}" 
         class="btn btn-small waves-effect waves-light  blue darken-2 pull-right" style="margin-top: -35px; width: 80px; height: 25px; padding: 2px 1px;">
         <i class="material-icons">add</i> <b>Novo</b></a>   
         <div class="divider" style="margin-bottom: 1px;"></div>
       </div>
     </div> 
      
      <div class="row" style="height: 50px; width: 1170px; position: fixed; z-index: 1001; top: 100px; ">
        <div class="col-md-12">   

          <!-- <ol class="breadcrumb" style="margin-bottom: 5px;">                     
            <li class="active">Clientes</li>
            <li><a href="{{route('clientesinter.create')}}" id="btn" style="text-decoration: none"><b>Cadastro</b></a></li>
            <li><a href="{{route('cliente.linkInterWhatsapp')}}" id="btn" style="text-decoration: none"><b>Pré-Cadastro Whatsapp</b></a></li>
          </ol>-->

          @if (session('message'))
          <div class="alert alert-success alert-dismissible fade in" style="margin-top: 1px; margin-bottom: 1px;">

            <a href="#" class="close" 
            data-dismiss="alert"
            aria-label="close">&times;</a>
            <b>{{ session('message') }}</b>
          </div>
            <script type="text/javascript">
            $(".alert-dismissible").fadeTo(3000, 500).slideUp(500, function(){
              $(".alert-dismissible").alert('close');
            });
          </script>
          @endif

            @if (session('message-failure'))
            <div class="alert alert-danger alert-dismissible fade in" style="margin-top: 1px; margin-bottom: 1px;">
              <a href="#" class="close" 
              data-dismiss="alert"
              aria-label="close">&times;</a>
              <b> {{ session('message-failure') }}</b>
            </div>
              <script type="text/javascript">
            $(".alert-dismissible").fadeTo(3000, 500).slideUp(500, function(){
              $(".alert-dismissible").alert('close');
            });
          </script>
            @endif  


          <form method="POST" action="{{route('ClienteInter.search')}}"> 
            {{ csrf_field() }}
            <div class="card-panel" style="height: 60px; margin-top: 0px; margin-bottom: 2px; padding: 12px 10px;">
             <div class="row">
               <div class="col-md-4" style="margin-right: -20px;">  
                     <div class="input-field">   
                  <select id="cliente" onchange="submit()" name="id">
                    @foreach($dadosClientes as $dc)
                    @php
             $dc->cpf;
             $cpf_formatado = NULL;            
             $bloco_1 = substr($dc->cpf,0,3);
             $bloco_2 = substr($dc->cpf,3,3);
             $bloco_3 = substr($dc->cpf,6,3);
             $dig_verificador = substr($dc->cpf,-2);
             $cpf_formatado = $bloco_1.".".$bloco_2.".".$bloco_3."-".$dig_verificador;            
             @endphp 
          
             @php  
             $dc->cnpj;           
             $cnpj_formatado = NULL;
             $bloco_1 = substr($dc->cnpj,0,2);
             $bloco_2 = substr($dc->cnpj,2,3);
             $bloco_3 = substr($dc->cnpj,5,3);
             $bloco_4 = substr($dc->cnpj,8,4);
             $digito_verificador = substr($dc->cnpj,-2);
             $cnpj_formatado = $bloco_1.".".$bloco_2.".".$bloco_3."/".$bloco_4."-".$digito_verificador;           
             @endphp
                    <option></option>
                    <option value="{{ $dc->id }}">{{ $dc->id }}. {{$dc->name}} cel.{{ $dc->celInput }} @if (isset($dc->cpf)) cpf.{{ $cpf_formatado }} @elseif (isset($dc->cnpj)) cnpj.{{ $cnpj_formatado }} @else @endif </option>

                     @endforeach     
                   </select>
                   <label for="id" style="font-size: 15px; margin-top: -30px;">Cliente</label>
                 </div>
                 <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
                 <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>   
                   @if (isset($idcliente))                
              <script type="text/javascript">
                $("#cliente").select2({
                  placeholder:'<?php $i = 0; $len = count($clientes); foreach ($clientes as $cliente){  if ($i == 0) { $cliente->id; echo $cliente->name;  }  else if ($i == $len - 1) { 

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
                 <div class="col-md-2" style="margin-right: -20px;">
                <div class="input-field">   
                  <select onchange="submit()" name="status" class="form-control" style="height: 29px;" title="Status de acompanhamento do cadastro de clientes">   


                @if (empty($status))
                    <option value="" ></option> 
                    <option value="A" title="Link de cadastro Aguardando retorno">Aguardando retorno </option>
                    <!--<option value="E" title="Link de cadastro enviado">Enviado</option>-->
                    <option value="R" title="Link de cadastro respondido">Respondido </option>                    
                    <option value="active" title="Cadastro realizado internamente">Cadastro interno </option>
                    @else


                    @if ($status == 'A') 
                    <option value="A" style="display: none;" title="Link de cadastro Aguardando retorno">Aguardando retorno </option>
                    @elseif ($status == 'R')
                    <!--<option value="E" title="Link de cadastro enviado">Enviado</option>-->
                    <option value="R" style="display: none;" title="Link de cadastro respondido">Respondido </option>   
                    @else ($status == 'active')                 
                    <option value="active" style="display: none;" title="Cadastro realizado internamente">Cadastro interno </option>

                    @endif
                         <option value="A" title="Link de cadastro Aguardando retorno">Aguardando retorno </option>
                    <!--<option value="E" title="Link de cadastro enviado">Enviado</option>-->
                    <option value="R" title="Link de cadastro respondido">Respondido </option>                    
                    <option value="active" title="Cadastro realizado internamente">Cadastro interno </option>
                    @endif



                   
                   
                                     
                   </select>
                   <label for="status" style="font-size: 15px; margin-top: -30px;">Cadastro</label>
                 </div>         
              </div>

                                          <div class="col-md-2" style="margin-right: -5px;">
                <div class="input-field">   
                  <select onchange="submit()" style="height: 29px;" name="tipo" class="form-control" title="Status de acompanhamento do cadastro de clientes">   


                    @if (empty($tipo))
                    <option value="" ></option> 
                    <option value="1" title="Pessoa Física">Pessoa Física</option>
                    <!--<option value="E" title="Link de cadastro enviado">Enviado</option>-->
                    <option value="2" title="Pesso Jurídica">Pesso Jurídica </option>                    
                   
                    @else


                    @if ($tipo == '1') 
                    <option value="1"  style="display: none;" title="Pessoa Física">Pessoa Física</option> 
                    @else($tipo == '2')
                    <!--<option value="E" title="Link de cadastro enviado">Enviado</option>-->
                     <option value="2"  style="display: none;" title="Pesso Jurídica">Pesso Jurídica</option>    
                                        
                    @endif
                     <option value="1" title="Pessoa Física">Pessoa Física</option>
                    <!--<option value="E" title="Link de cadastro enviado">Enviado</option>-->
                    <option value="2" title="Pesso Jurídica">Pesso Jurídica </option>  
                    @endif





                  </select>
                  <label for="status" style="font-size: 15px; margin-top: -30px;">Tipo</label>
                </div> 
                @foreach($vendedores as $vendedor) 
                <input type="hidden" name="vendedor_id" value="{{$vendedor->id}}">  
                @endforeach     
              </div>
                    <!--<div class="col-md-2">
                    	
                <div class="input-field">   
                  <select onchange="submit()" name="status" class="form-control" title="Status de acompanhamento do cadastro de clientes">                    
                    <option value="" >Status</option>

                    <option value="A" title="Link de cadastro aguardando resposta">Aguardando Resposta </option>
                    <option value="E" title="Link de cadastro enviado">Enviado</option>
                    <option value="R" title="Link de cadastro respondido">Respondido </option>                    
                    <option value="active" title="Cadastro realizado internamente">Cadastro interno </option>                                     
                   </select>
                   <label for="status" style="font-size: 15px; margin-top: -30px;">Escolha o status</label>
                 </div>         
              </div>-->
             <!-- <a href="{{route('clientesinter.create')}}" 
              class="btn-floating btn-large waves-effect waves-light btn-primary pull-right" title="Adicionar Cliente">
              <i class="material-icons">add</i></a>-->
              <a href="{{route('clientesinter.index')}}"  style="margin-top: 10px; width: 30px; height: 27px; padding: 2px 1px;" title="Limpar Pesquisa" class="btn waves-effect"><i class="fa fa-eraser"></i></a>
            </div>
            
          </div> 
        </form>
        </div>
      </div>
  <div class="row" style="margin-top: 93px;">
        <div class="col-md-12"> 
          <div class="table-responsive">
            <table class="table table-striped table-bordered table-condensed table-hover" id="clientes" style="width:100%">
              <thead>
                <tr class="warning">
      <th id="center">Código                     
                  </th>               
                  <th id="center">Nome                     
                  </th>
                   <th id="center">Data do registro                     
                  </th>
                  <th id="center">Documento</th>
                  <th id="center">E-mail
                  </th>                    
                  <th id="center">Celular
                  </th>             
                  <th id="center">Endereço
                  </th>
                  <th id="center">Status
                  </th>
                                  
                  <th id="center">Ações                   
                </th>                               
              </tr>
            </thead>            
          
          <tbody>
            @foreach($clientes as $cliente)
               @php
             $cliente->cpf;
             $cpf_formatado = NULL;            
             $bloco_1 = substr($cliente->cpf,0,3);
             $bloco_2 = substr($cliente->cpf,3,3);
             $bloco_3 = substr($cliente->cpf,6,3);
             $dig_verificador = substr($cliente->cpf,-2);
             $cpf_formatado = $bloco_1.".".$bloco_2.".".$bloco_3."-".$dig_verificador;            
             @endphp 
          
             @php  
             $cliente->cnpj;           
             $cnpj_formatado = NULL;
             $bloco_1 = substr($cliente->cnpj,0,2);
             $bloco_2 = substr($cliente->cnpj,2,3);
             $bloco_3 = substr($cliente->cnpj,5,3);
             $bloco_4 = substr($cliente->cnpj,8,4);
             $digito_verificador = substr($cliente->cnpj,-2);
             $cnpj_formatado = $bloco_1.".".$bloco_2.".".$bloco_3."/".$bloco_4."-".$digito_verificador;           
             @endphp
            <tr> 
          <td id="center" title="{{$cliente->id}}">{{$cliente->id}}</td>
              <td>  @php 
                     $name = $cliente->name;
                     echo substr($name, 0, 24);                     
                     $result = strlen($name);                    
                     @endphp
                     @if ($result > 24)
                     ...
                     @else
                     @endif</td>
               <td title="{{$cliente->created_at->format('d/m/Y H:i')}}" >{{$cliente->created_at->format('d/m/Y')}}</td>
              <td title="@if (isset($cliente->cpf))CPF: {{($cpf_formatado)}} @elseif (isset($cliente->cnpj))CNPJ {{$cnpj_formatado}} @else @endif" style="width: 130px;">@if (isset($cliente->cpf)) {{$cpf_formatado}} @elseif (isset($cliente->cnpj)) {{$cnpj_formatado}} @else @endif</td>
              <td title="E-mail: {{$cliente->email}}" style="width: 80px;">{{$cliente->email}}</td>                            
              <td title="Cel:{{$cliente->celInput}} Tel:{{$cliente->tel}}">{{$cliente->celInput}}</td>     
              <td title="Endereço: {{$cliente->endereço}} {{$cliente->numero}}. {{$cliente->bairro}} {{$cliente->cidade}} {{$cliente->cep}}">{{$cliente->endereço}}</td>
               <td title="{{$cliente->updated_at->format('d/m/Y H:i')}}">{{$cliente->status == 'A' ? 'Aguardando retorno' : '' }} {{$cliente->status == 'R' ? 'Respondido' : '' }} {{$cliente->status == 'active' ? 'Cadastro interno' : '' }} {{$cliente->status == 'E' ? 'Conclusão Pendente!' : '' }}</td>

              <td title="Ações" id="center">
                <a href="{{route('clientesinter.edit', $cliente->id)}}" 
                 data-toggle="tooltip" 
                 data-placement="top"
                 title="Alterar" class="btn waves-effect amber" style="width: 20px; height: 20px; padding: 1px 1px;"><i class="small material-icons">border_color</i></a>


                <!--<form style="display: inline-block;" method="POST" 
                 action="{{route('clientesinter.destroy', $cliente->id)}}"                                                        
                 data-toggle="tooltip" data-placement="top"
                 title="Excluir" 
                 onsubmit="return confirm('Confirma exclusão?')">
                 {{method_field('DELETE')}}{{ csrf_field() }}                                                
                 <button type="submit" class="btn waves-effect deep-orange">
                  <i class="fa fa-trash"></i>                                                    
                </button></form>--></td>   
                   
              </tr>

              @endforeach
            </tbody>
          </table>
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
    $('#clientes').DataTable({




      "dom": '<"top"i>rt<"bottom"<"col-md-5"fl><"col-md-7"p>><"clear">',
     // "dom": '<"top"i>Brt<"bottom"<"col-md-5"fl><"col-md-7"p>><"clear">',
      /* buttons: [
            'pdf', 'print'
        ],*/

                    

      /*  buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],*/

      "language": {
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
        "aaSorting": [[1, 'asc']],
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

