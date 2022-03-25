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
     <h2><b> Lista de Vendedores</b></h2> 
     <a href="{{route('vendedores.create')}}" 
            class="btn btn-small waves-effect waves-light  blue darken-2 pull-right" style="margin-top: -35px; width: 80px; height: 25px; padding: 2px 1px;">
            <i class="material-icons">add</i> <b>Novo</b></a>   
             <div class="divider" style="margin-bottom: 1px;"></div>
    </div>
    </div>       

     <div class="row" style="height: 50px; width: 1170px; position: fixed; z-index: 1001; top: 100px; ">
      <div class="col-md-12">
        <!--<ol class="breadcrumb" style="margin-bottom: 5px;">                     
          <li class="active">Vendedores</li>
          <li><a href="{{route('vendedores.create')}}" id="btn" style="text-decoration: none"><b> Cadastro</b></a></li>
        </ol>-->
        <div class="fixed-action-btn">           
          <a class="btn-floating btn-large lighten-2">
            <i class="large material-icons">search</i>
          </a>
          <ul>
            <li><a href="{{route('admins.index')}}" class="btn-floating red" title="Administrador"><i class="material-icons">assignment_ind</i></a></li>
            <li><a href="{{route('vendedores.index')}}" class="btn-floating yellow" title="Vendedor"><i class="material-icons">account_box</i></a></li>
            <li><a href="{{route('clientes.index')}}" class="btn-floating green" title="Cliente"><i class="material-icons">perm_identity</i></a></li>

          </ul>

        </div>

        @if (session('message'))
        <div class="alert alert-success alert-dismissible fade in" style="margin-top: 1px; margin-bottom: 1px;">
          <a href="#" class="close" 
          data-dismiss="alert"
          aria-label="close">&times;</a>
          <b>{{ session('message') }}</b>
        </div>
         <script type="text/javascript">
          $(".alert-dismissible").fadeTo(7000, 500).slideUp(500, function(){
            $(".alert-dismissible").alert('close');
          });
        </script>

        @endif 

             @if (session('message-failure'))
            <div class="alert alert-danger alert-dismissible  fade in" style="margin-top: 1px; margin-bottom: 1px;">
              <a href="#" class="close" 
              data-dismiss="alert"
              aria-label="close">&times;</a>
              <b> {{ session('message-failure') }}</b>
            </div>
                <script type="text/javascript">
          $(".alert-dismissible").fadeTo(7000, 500).slideUp(500, function(){
            $(".alert-dismissible").alert('close');
          });
        </script>
            @endif             


        <form method="POST" action="{{route('Vendedor.search')}}"> 
          {{ csrf_field() }}
          <div class="card-panel" style="height: 60px; margin-top: 0px; margin-bottom: 2px; padding: 12px 10px;">
           <div class="row">
             <div class="col-md-4" style="margin-right: -20px;">  
              <div class="input-field">   
                <select id="vendedor" onchange="submit()" name="id" title="Escolha um vendedor para pesquisa">
                  @foreach($dadosVendedores as $dv)
                  <option></option>
                  <option value="{{ $dv->id }}">{{$dv->id}}. {{$dv->name}} </option>

                   @endforeach     
                 </select>
                 <label for="id" style="font-size: 15px; margin-top: -30px;">Vendedor</label>
               </div>
               <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
               <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>  
                @if (isset($idvendedor))                
              <script type="text/javascript">
                $("#vendedor").select2({
                  placeholder:'<?php $i = 0; $len = count($vendedores); foreach ($vendedores as $vendedor){  if ($i == 0) { $vendedor->id; echo $vendedor->name;  }  else if ($i == $len - 1) { 

                  } $i++; }  ?>'
                });
              </script>            


              @else    
            <script type="text/javascript">
                $("#vendedor").select2({
                  placeholder:'---Selecione o vendedor---'
                });
            </script>                  
              @endif                         
                        
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
              </div>

     

            <a href="{{route('vendedores.index')}}" title="Limpar Pesquisa" class="btn waves-effect" style="margin-top: 10px; width: 30px; height: 26px; padding: 2px 1px;"><i class="fa fa-eraser"></i></a>
          </div>

        </div> 
      </form>
    </div>
  </div>

 <div class="row" style="margin-top: 93px;">
    <div class="col-md-12"> 
      <div class="table-responsive">
        <table class="table table-striped table-bordered table-condensed table-hover" id="vendedores" style="width:100%">
          <thead>
            <tr class="warning">
               <th>Nome                                        
              </th>
              <th>Documento                                       
              </th> 
              <th>Comissão(%)</th>                
              <th>E-mail
              </th>                      
              <th>Celular
              </th> 
              <th>Status
              </th>            
              <th>Ações
                


            </th>                
          </tr>
        </thead>            
        
        <tbody>
          @foreach($vendedores as $vendedor)

             @php
             $vendedor->cpf;
             $cpf_formatado = NULL;            
             $bloco_1 = substr($vendedor->cpf,0,3);
             $bloco_2 = substr($vendedor->cpf,3,3);
             $bloco_3 = substr($vendedor->cpf,6,3);
             $dig_verificador = substr($vendedor->cpf,-2);
             $cpf_formatado = $bloco_1.".".$bloco_2.".".$bloco_3."-".$dig_verificador;            
             @endphp 
          
             @php  
             $vendedor->cnpj;           
             $cnpj_formatado = NULL;
             $bloco_1 = substr($vendedor->cnpj,0,2);
             $bloco_2 = substr($vendedor->cnpj,2,3);
             $bloco_3 = substr($vendedor->cnpj,5,3);
             $bloco_4 = substr($vendedor->cnpj,8,4);
             $digito_verificador = substr($vendedor->cnpj,-2);
             $cnpj_formatado = $bloco_1.".".$bloco_2.".".$bloco_3."/".$bloco_4."-".$digito_verificador;           
             @endphp
           

          <tr>
            <td title="{{$vendedor->razao_social}}">
               @php 
                     $name = $vendedor->name;
                     echo substr($name, 0, 24);                     
                     $result = strlen($name);                    
                     @endphp
                     @if ($result > 24)
                     ...
                     @else
                     @endif
              </td>
            <td title="@if (isset($vendedor->cpf))CPF: {{($cpf_formatado)}} @elseif (isset($vendedor->cnpj))CNPJ {{$cnpj_formatado}} @else @endif" >@if (isset($vendedor->cpf)) {{$cpf_formatado}} @elseif (isset($vendedor->cnpj)) {{$cnpj_formatado}} @else @endif</td>
             <td>{{$vendedor->comissao}}%</td>
            <td>{{$vendedor->email}}</td>                
            <td>{{$vendedor->cel}}</td>
            <td>@if ($vendedor->status == 'active') {{$vendedor->status == 'active' ? 'Ativo' : ''}} @else Bloqueado @endif </td>   
               



            <td title="Ações" id="center">
              <a href="{{route('vendedores.edit', $vendedor->id)}}" 
               data-toggle="tooltip" 
               data-placement="top"
               title="Alterar" class="btn btn-small waves-effect amber" style="width: 20px; height: 20px; padding: 1px 1px;"><i class="small material-icons">border_color</i></a>
             
                <a href="{{route('vendedor.status', $vendedor->id)}}" 
               data-toggle="tooltip" onclick="return confirm('Esta ação excluirá o vendedor da base de dados, confirma exclusão?')"
               data-placement="top"
               title="Remover" class="btn btn-small waves-effect deep-orange" style="width: 20px; height: 20px; padding: 1px 1px;"><i class="fa fa-trash"></i></a>


               <!--<form style="display: inline-block;" method="POST" 
               action="{{route('vendedores.destroy', $vendedor->id)}}"                                                        
               data-toggle="tooltip" data-placement="top"
               title="Excluir" 
               onsubmit="return confirm('Confirma exclusão?')">
               {{method_field('DELETE')}}{{ csrf_field() }}                                                
               <button type="submit" class="btn waves-effect deep-orange">
               <i class="fa fa-trash"></i>                                                    
              </button>
            </form>-->
          </td>               
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

<script type="text/javascript">
  document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('.fixed-action-btn');
    var instances = M.FloatingActionButton.init(elems, {
      direction: 'left'
    });
  });

  // Or with jQuery

  $(document).ready(function(){
    $('.fixed-action-btn').floatingActionButton();
  });

  var instance = M.FloatingActionButton.getInstance(elem);
  instance.open();
  instance.close();
  instance.destroy();
</script>
<link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap.min.css" rel="stylesheet"/>
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap.min.js"></script>

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
    $('#vendedores').DataTable({


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


</script>

@endsection

