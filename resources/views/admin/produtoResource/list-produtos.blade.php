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
          <h2>Listagem de Produtos</h2>
          <a href="{{route('categoria.create')}}" 
          class="btn btn-small waves-effect waves-light  blue darken-2 pull-right" style="margin-top: -35px; width: 130px; height: 25px; padding: 2px 1px; margin-right: 90px;" title="Lista de pedidos consignados">
          <i class="fa fa-bookmark" aria-hidden="true"></i><b> Categorias</b></a>
          <a href="{{route('product.create')}}" 
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
              <form method="POST" action="{{route('Produto.search')}}"> 
                {{ csrf_field() }} 
                <div class="col-md-4">  
                  <div class="input-field">   
                    <select id="produtos" onchange="submit()" name="id" title="Escolha um produto para pesquisa">
                      @foreach($list_prod as $registro)
                      <option></option>
                      <option value="{{ $registro->id }}">{{$registro->prod_cod}} {{$registro->prod_desc}} </option>

                      @endforeach     
                    </select>
                    <label for="id" style="font-size: 15px; margin-top: -30px;">Escolha o produto </label>
                  </div>
                  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
                  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>      
                  <script type="text/javascript">
                    $("#produtos").select2({
                      placeholder:'---Selecione o produto---'
                    });
                  </script> 
                </div>
                <div class="col-md-4">
                  <div class="input-field">   
                    <select id="categoria" onchange="submit()" name="grup_categ_cod" title="Escolha uma categoria para pesquisa">
                      @foreach($list_CategProd as $registro)
                      <option></option>
                      <option value="{{ $registro->grup_categ_cod }}">{{$registro->grup_categ_cod}} {{$registro->grup_desc_categ}} </option>

                      @endforeach     
                    </select>
                    <label for="grup_categ_cod" style="font-size: 15px; margin-top: -30px;">Escolha a Categoria </label>
                  </div>
                  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
                  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>      
                  <script type="text/javascript">
                    $("#categoria").select2({
                      placeholder:'---Selecione a categoria---'
                    });
                  </script>                 
                </div>

                <a href="{{route('product.index')}}" 
                data-toggle="tooltip" 
                data-placement="top"
                title="Limpar Pesquisa" class="btn waves-effect" style="margin-top: 10px; width: 30px; height: 26px; padding: 2px 1px;"><i class="fa fa-eraser"></i></a>
              </form>
            </div>

          </div>           

        </div>
      </div>

      <div class="row" style="margin-top: 93px;">
       <div class="col-md-12">    
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-condensed table-hover"  id="example" style="width:100%">
            <thead>
              <tr class="warning">
                <th id="center">Código

                </th>
                <th id="center">Descrição dos Produtos<br>

                </th>
                <th id="center">Categoria<br>

                </th>
                <th title="Preço" id="center">Preço

                </th> 

                <!--<th id="center">Imagem</th>-->                
                <th id="center">Ações                   





                </th>                
              </tr>
            </thead>

            <tbody>
              @foreach($products as $product)
              <tr>
                <td title="Código do produto" id="center" >
                {{$product->prod_cod}}</td>
                 <td title="Descrição">{{$product->prod_desc}}</td>
                 <td title="Descrição">{{$product->grupCateg->grup_desc_categ}}</td>
                 <td title="Preço R$ {{number_format($product->prod_preco_padrao, 2,',','.')}}">R$ {{number_format($product->prod_preco_padrao, 2,',','.')}}</td>

                 <td title="Ações" id="center">
                  <a href="{{route('product.edit', $product->id)}}" 
                   data-toggle="tooltip" 
                   data-placement="top"
                   title="Alterar" class="btn btn-small waves-effect amber" style="width: 20px; height: 20px; padding: 1px 1px;"><i class="small material-icons">border_color</i></a>
                 
                   <form style="display: inline-block;" method="POST" 
                   action="{{route('product.destroy', $product->id)}}"                                                        
                   data-toggle="tooltip" data-placement="top"
                   title="Excluir" 
                   onsubmit="return confirm('Confirma exclusão?')">
                   {{method_field('DELETE')}}{{ csrf_field() }}                                                
                   <button type="submit" class="btn btn-small waves-effect deep-orange" style="width: 20px; height: 20px; padding: 0px 0px;">
                     <i class="fa fa-trash"></i>                                                   
                   </button></form></td>               
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



<script type="text/javascript">

  

  $(document).ready(function() {
     $.extend( $.fn.dataTable.defaults, {
    searching: false,  
   // ordering:  false
} );

    $('#example').DataTable( {
        "dom": '<"top"<"pull-right"B>i>rt<"bottom"<"col-md-5"fl><"col-md-7"p>><"clear">',

        buttons: [ 
        'excel',
 

        {
            extend: 'pdfHtml5',
            title: 'Produtos ',
            text: 'PDF',
            
            customize: function ( doc ) {
                // Splice the image in after the header, but before the table
                doc.content.splice( 1, 0, {
                    margin: [ 0, 0, 0, 12 ],
                    alignment: 'center',
                    image: 'data:/img/logo-opycos.png;base64,iVBORw0KGgoAAAANSUhEUgAAAPsAAABECAYAAABH7kMGAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyZpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNi1jMDY3IDc5LjE1Nzc0NywgMjAxNS8wMy8zMC0yMzo0MDo0MiAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIDIwMTUgKFdpbmRvd3MpIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOkRERkNGQzcyMTdBQTExRTc5NzIyQjc1OUE1QzczQjNDIiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOkRERkNGQzczMTdBQTExRTc5NzIyQjc1OUE1QzczQjNDIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6RERGQ0ZDNzAxN0FBMTFFNzk3MjJCNzU5QTVDNzNCM0MiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6RERGQ0ZDNzExN0FBMTFFNzk3MjJCNzU5QTVDNzNCM0MiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz4LoucdAAAMAElEQVR42uxd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',
                     width: 90,
                     height: 22
                } );
                // Data URL generated by http://dataurl.net/#dataurlmaker
            },
            exportOptions: {
                    columns: [0, 1, 2, 3 ]
                  
                }


        } ],

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


    } );


} );


</script>


 @endsection
