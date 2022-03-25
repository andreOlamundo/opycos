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
         <h2><b> Lista de Requisições</b></h2> 
         <a href="{{route('requestopycosint.create')}}" 
         class="btn btn-small waves-effect waves-light  blue darken-2 pull-right" style="margin-top: -35px; width: 80px; height: 25px; padding: 2px 1px;">
         <i class="material-icons">add</i> <b>Novo</b></a>   
         <div class="divider" style="margin-bottom: 1px;"></div>
       </div>
     </div> 

     <div class="row" style="height: 50px; width: 1170px; position: fixed; z-index: 1001; top: 100px; ">
      <div class="col-md-12">

        @if (session('message'))
        <div class="alert alert-success alert-dismissible fade in" style="margin-bottom: 1px;">
          <a href="#" class="close" 
          data-dismiss="alert"
          aria-label="close">&times;</a>
          <b> {{ session('message') }}</b>
        </div>
        <script type="text/javascript">
          $(".alert-dismissible").fadeTo(5000, 500).slideUp(500, function(){
            $(".alert-dismissible").alert('close');
          });
        </script>
        @endif
        @if (session('message-failure'))
        <div class="alert alert-danger alert-dismissible fade in" style="margin-bottom: 1px;">
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

        <div class="card-panel" style="height: 60px; margin-top: 0px; margin-bottom: 2px; padding: 12px 10px;">

          <div class="row">
           <form method="POST" action="{{route('Requestint.search')}}"> 
            {{ csrf_field() }} 
            <div class="col-md-4">  
              <div class="input-field">   
                <select id="request" onchange="submit()" name="id" title="Escolha uma requisição para pesquisa">
                  @foreach($list_request as $registro)
                  <option></option>
                  <option value="{{ $registro->id }}">{{$registro->request_cod}} - {{$registro->request_desc}} </option>
                  @endforeach     
                </select>
                <label for="request" style="font-size: 12px; margin-top: -30px;">PESQUISA POR REQUISIÇÃO</label>
              </div>
              <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
              <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script> 

              @if (isset($idreq))                
              <script type="text/javascript">
                $("#request").select2({
                  placeholder:'<?php $i = 0; $len = count($requisitions); foreach ($requisitions as $request){  if ($i == 0) { $request->id; echo $request->request_desc;  }  else if ($i == $len - 1) { 

                  } $i++; }  ?>'
                });
              </script>
              


              @else    
              <script type="text/javascript">
                $("#request").select2({
                  placeholder:'---Selecione o Requisição---'
                });
              </script>            
              @endif 


              
            </div>
            

            <a href="{{route('requestopycosint.index')}}" data-toggle="tooltip" data-placement="top" style="margin-top: 10px; width: 30px; height: 27px; padding: 2px 1px;"  title="Limpar Pesquisa" class="btn waves-effect"><i class="fa fa-eraser"></i></a>


          </form>                     
        </div>
      </div>           

    </div>
  </div>
  <div class="row" style="margin-top: 100px;">
    <div class="col-md-12">             
      <div class="table-responsive">
        <table class="table table-striped table-bordered table-condensed table-hover" id="requestTable" style="width:100%">
          <thead>
            <tr class="warning">
              <th>Código
              </th>
              <th>Descrição da Requisição
                <th>Valor
                </th> 
                <th>Registro
                </th>
                <th>Atualização
                </th>
                <th>Cliente                     
                    </th>

               <th title="Status">Status                     
                 </th>                            
                <th>Ações                   
                </th>                
              </tr>
            </thead>  
            <tbody>
              @foreach($requisitions as $req)
              <tr>
                <td title="Código da requisição" id="center">
                 {{$req->request_cod}}</td>

                 <td title="Descrição">{{$req->request_desc}}</td>  

                 <td>R$ {{number_format($req->request_valor, 2,',','.')}}</td>


                  <td title="{{$req->created_at->format('d/m/Y H:i')}}">{{$req->created_at->format('d/m/Y')}} </td>
                 <td title="{{$req->updated_at->format('d/m/Y H:i')}}">{{$req->updated_at->format('d/m/Y')}} </td>
                  <td title="{{ $req->Cliente->name}} {{ $req->Cliente->celInput}}">{{ $req->Cliente->name}}</td>
                 <td title="{{$req->updated_at->format('d/m/Y H:i')}}" id="center">{{ $req->status == 'AP' ? 'Aguardando Produção' : '' }} {{ $req->status == 'CA' ? 'Cancelada' : '' }} {{ $req->status == 'FI' ? 'Finalizada' : '' }}{{ $req->status == 'RE' ? 'Atrelada ao pedido' : '' }}</td>

                  @if ($req->status == 'FI')
                          <td title="Ações" id="center">
                  <a href="{{route('requestopycosint.edit', $req->id)}}" 
                   data-toggle="tooltip" 
                   data-placement="top"
                   title="Alterar" class="btn btn-small waves-effect amber" style="width: 20px; height: 20px; padding: 1px 1px;"><i class="small material-icons">border_color</i></a>

                   <form style="display: inline-block;" method="POST" 
                   action="{{route('requestopycosint.destroy', $req->id)}}"                                                        
                   data-toggle="tooltip" data-placement="top"
                   title="Excluir" 
                   onsubmit="return confirm('Confirma exclusão?')">
                   {{method_field('DELETE')}}{{ csrf_field() }}                                                
                   <button type="submit" class="btn btn-small waves-effect deep-orange" style="width: 20px; height: 20px; padding: 0px 0px;">
                     <i class="fa fa-trash" style="font-size:13px"></i>                                                   
                   </button>
                 </form>
               </td> 

                 @elseif ($req->status == 'RE')

                  <td title="Ações" id="center">
                  <a href="#" onclick="return confirm('Requisição não pode mais ser alterada!')"
                   data-toggle="tooltip" 
                   data-placement="top"
                   title="Alterar" class="btn btn-small waves-effect amber" style="width: 20px; height: 20px; padding: 1px 1px;"><i class="small material-icons">border_color</i></a>

                  
                   <button type="submit" onclick="return confirm('Requisição não pode mais ser excluída!')" class="btn btn-small waves-effect deep-orange" style="width: 20px; height: 20px; padding: 0px 0px;">
                     <i class="fa fa-trash" style="font-size:13px"></i>                                                   
                   </button>
               
               </td> 
                 
                @else ($req->status == 'CA')
                 <td title="Ações" id="center">
                  <a href="#" onclick="return confirm('Requisição não pode mais ser alterada!')"
                   data-toggle="tooltip" 
                   data-placement="top"
                   title="Alterar" class="btn btn-small waves-effect amber" style="width: 20px; height: 20px; padding: 1px 1px;"><i class="small material-icons">border_color</i></a>

                  
                       <form style="display: inline-block;" method="POST" 
                   action="{{route('requestopycosint.destroy', $req->id)}}"                                                        
                   data-toggle="tooltip" data-placement="top"
                   title="Excluir" 
                   onsubmit="return confirm('Confirma exclusão?')">
                   {{method_field('DELETE')}}{{ csrf_field() }}                                                
                   <button type="submit" class="btn btn-small waves-effect deep-orange" style="width: 20px; height: 20px; padding: 0px 0px;">
                     <i class="fa fa-trash" style="font-size:13px"></i>                                                   
                   </button>
                 </form>
               
               </td> 
                 
                  @endif

                   
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
    $('#requestTable').DataTable({




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
        "aaSorting": [[3, 'desc']],
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
