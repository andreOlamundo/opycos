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

      <div class="row">
        <div class="col-md-12">
          <h2>Relatório de Quitação de Produtos</h2>         
     </div>
   </div>	
<div class="row">
  <div class="col-md-12">   
     <table class="table table-sm table-striped table-bordered table-condensed table-hover" id="example" style="width:100%">
      <thead>
        <tr class="warning"> 
        <th id="center" title="Pagamento">Pagamento</th>                      
        <th id="center">Código</th>
        <th id="center">Produto</th>
        <th id="center" title="Preço unitário">Preço</th>
        <th id="center">Desconto</th>
        <th id="center">Total</th>       
      </tr>
    </thead> 

    <tbody> 
     @php
     $total_pedido =0;
     @endphp


 @foreach ($pedidos->itens_pedido_uni as $item_pedido)
<tr>
  
 
      <td id="center">       
      @if($item_pedido->status == 'FI') 
         <strong style="color:blue;">Quitado</strong>
      
      @else
      <strong style="color:red;">Em Aberto</strong>
      @endif

    </td>
     
     <td>
       {{ $item_pedido->product->prod_cod }}
     </td> 
     <td>
       {{ $item_pedido->product->prod_desc}}
     </td>



     @php
     $desconto = $item_pedido->prod_desconto;
     if($desconto > 0)
     {
      $percentual_desconto = ($desconto/$item_pedido->product->prod_preco_padrao)*100;
    }
    else
    {
      $percentual_desconto = 0;
    }

    @endphp

    <td id="center">  R$ {{ number_format($item_pedido->product->prod_preco_padrao, 2, ',', '.')}}</td>

    <td id="center" title="@if($desconto > 0) {{ round($percentual_desconto)}}% @else  0% @endif">

     @if($desconto > 0)       
     R${{ number_format($desconto, 2, ',', '.')}}
     @else

     R$0,00
        
     @endif       

    </td> 


    @php
    $total = $item_pedido->product->prod_preco_padrao - $item_pedido->prod_desconto;
    @endphp

    <td id="center">R$ {{ number_format($total, 2, ',', '.')}}</td>





@endforeach

</tr>
</tbody> 

  <tfoot>
            <tr>
             <th style="border-right: none;"></th>
              <th style="border-right: none;"></th>             
              <th style="border-right: none;"></th>
  @php
  $total_desconto_pedido = 0;
  $total_produtos = 0;
  $total_quantidade = 0;
  @endphp

  @foreach ($pedidos->itens_pedido as $item_pedido)
  @php
  $total_quantidade += $item_pedido->quantidade;  
  $total_produtos += $item_pedido->total;  
  $total_desc_geral = $item_pedido->totalDesconto;
  $total_desconto_pedido -= $total_desc_geral;

  @endphp
  @endforeach

  <th id="center">R$ {{ number_format($total_produtos, 2, ',', '.') }}</th>  
  <th><b style="color: red;">R$ {{ number_format($total_desconto_pedido, 2, ',', '.') }}</th>
    @php
$total_geral =0;

@endphp

@foreach ($pedidos->itens_pedido as $item_pedido)
@php
$desconto = $item_pedido->totalDesconto;
@endphp
@if ($desconto > 0)
@php

$total_geral += $item_pedido->total;
$total_geral -= $item_pedido->totalDesconto
@endphp
@else
@php
$total_geral += $item_pedido->total;
@endphp
@endif

@endforeach

@foreach($pedidos->itens_pedido_request as $item_pedido)
@php
$desconto = $item_pedido->totalDesconto;
@endphp
@if ($desconto > 0)

@php
$total_geral += $item_pedido->total;
$total_geral -= $item_pedido->totalDesconto
@endphp

@else
@php
$total_geral += $item_pedido->total;
@endphp
@endif


@endforeach
    <th id="center"><b>R$ {{ number_format($total_geral, 2, ',', '.') }}</b></th>

  </tr>
</tfoot>

         




   
</table>
</div>



<div class="divider" style="margin-bottom: 5px;"></div>

<p class="lead" id="black">Total Geral: R$ {{ number_format($total_geral, 2, ',', '.') }}</p>

<div class="divider" style="margin-bottom: 10px; margin-top: -15px;"></div>




<a href="{{route('pedidos/{id}/consig/edit', $pedidos->id)}}" class="btn btn-default"><b>VOLTAR</b></a>



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
                margin: [ 0, 0, 0, 0 ],              
                orientation : 'portrait',
                pageSize: 'A4',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5]
                  
                }

            },

                  {
                        extend: 'print',
                        exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5]
                }
                    },

                             {
                        extend: 'excel',
                        exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5]

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