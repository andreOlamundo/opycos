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
          <h2>Listagem de Categorias</h2>   
          <a href="{{route('categoria.create')}}" 
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
          $(".alert-dismissible").fadeTo(2000, 500).slideUp(500, function(){
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
          $(".alert-dismissible").fadeTo(2000, 500).slideUp(500, function(){
            $(".alert-dismissible").alert('close');
          });
        </script>
          @endif
          
            <div class="card-panel" style="height: 60px; margin-top: 0px; margin-bottom: 2px; padding: 12px 10px;">              
              <div class="row">
                 <form method="POST" action="{{route('Categoria.search')}}"> 
            {{ csrf_field() }} 
                <div class="col-md-4">  
                  <div class="input-field">   
                    <select id="categoria" onchange="submit()" name="id">
                      @foreach($list_categories as $registro)
                      <option></option>
                      <option value="{{ $registro->id }}">{{$registro->id}} {{$registro->grup_desc_categ}} </option>

                      @endforeach     
                    </select>
                    <label for="grup_categ_cod" style="font-size: 15px; margin-top: -30px;">Pesquisa por Categoria </label>
                  </div>
                  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
                  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>  

                     @if (isset($idcateg))                
              <script type="text/javascript">
                $("#categoria").select2({
                  placeholder:'<?php $i = 0; $len = count($categories); foreach ($categories as $category){  if ($i == 0) { $category->id; echo $category->grup_desc_categ;  }  else if ($i == $len - 1) { 

                  } $i++; }  ?>'
                });
              </script>
              


              @else    
            <script type="text/javascript">
                    $("#categoria").select2({
                      placeholder:'---Selecione a categoria---'
                    });
                  </script>             
              @endif     
                                  
                </div>
                <a href="{{route('categoria.index')}}" 
                     data-toggle="tooltip" 
                     data-placement="top"
                     title="Limpar Pesquisa" style="margin-top: 10px; width: 30px; height: 26px; padding: 2px 1px;" class="btn waves-effect"><i class="fa fa-eraser"></i></a> &nbsp;
         
              </form>
              </div> 
     
            </div>           

          </div>
            </div>

<div class="row" style="margin-top: 93px;">
          <div class="col-md-12">             
            <div class="table-responsive">
              <table class="table table-striped table-bordered table-condensed table-hover" id="grupo" style="width:100%">
                <thead>
                  <tr class="warning">
                    <th>Código
                    </th>
                    <th>Descrição da categoria  
                    </th>                                     
                    <th>Ações                                                             
                   </th>                
               </tr>
             </thead>        
           <tbody>
            @foreach($categories as $category)
            <tr>
              <td title="Código da categoria" id="center" >
               {{$category->grup_categ_cod}}</td>
               <td title="Descrição">{{$category->grup_desc_categ}}</td>              

               <td title="Ações" id="center">
                <a href="{{route('categoria.edit', $category->id)}}" 
                 data-toggle="tooltip" style="width: 20px; height: 20px; padding: 1px 1px;"
                 data-placement="top"
                 title="Alterar" class="btn btn-small waves-effect amber"><i class="small material-icons">border_color</i></a>
              


                 <form style="display: inline-block;" method="POST" 
                 action="{{route('categoria.destroy', $category->id)}}"                                                        
                 data-toggle="tooltip" data-placement="top"
                 title="Excluir" 
                 onsubmit="return confirm('Confirma exclusão?')">
                 {{method_field('DELETE')}}{{ csrf_field() }}                                                
                 <button type="submit" style="width: 20px; height: 20px; padding: 0px 0px;" class="btn btn-small waves-effect deep-orange">
                   <i class="fa fa-trash" style="font-size:13px"></i>                                                   
                 </button>
               </form>
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
<link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap.min.css" rel="stylesheet"/>
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap.min.js"></script>
<script type="text/javascript">

  

  $(document).ready(function() {
    $('#grupo').DataTable({


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


</script>



@endsection
