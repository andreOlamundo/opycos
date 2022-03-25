<div class="col-md-4">

     <select class="grup_desc" id="grup_cod" name="grup_cod" style="width: 400px;"><!--name="grup_categ_cod"-->
      <option value="0" disabled="true" selected="true">---Selecione o Grupo---</option>
      @foreach($dadosGroupsProduct as $dgp)
      
      <option value="{{$dgp->grup_cod}}">{{$dgp->grup_desc}}</option>
      @endforeach
      </select> <br><br>
    
 
  <!--<form method="POST" action="{{route('ProdutoPedido.search')}}" id="search"> (id="produtos")->tag select -->
   <!-- {{ csrf_field() }}  -->  

      <select class="prod_desc"  name="prod_desc" style="width: 400px;" id="">
        <option value="0" disabled="true" selected="true">---Selecione o Produto---</option>
        @foreach($products as $dp)        
        <option value="{{$dp->prod_desc}}">{{$dp->prod_desc}}</option>
        @endforeach    

      </select><br><br>
        
        <input type='text' name="prod_cod" style="width: 200px;" placeholder="--Código do produto--" class="prod_cod" readonly="readonly">   
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script type="text/javascript">
  
  $(document).ready(function(){
    $(document).on('change','.grup_desc',function(){
     //console.log("humm its change");
      var grup_cod=$(this).val();
     // console.log(cod_id);
     var div=$(this).parent();
      var op=" ";

     $.ajax({
      type:'get',
      url:'{!!URL::to('findProductName') !!}',
      data:{'id':grup_cod},
      success:function(data){
     // console.log('success');
       // console.log(data);
        //console.log(data.length);
        op+='<option value="0" selected disabled>Escolha um produto</option>';
        for(var i=0;i<data.length;i++){
          op+='<option value="'+data[i].id+'">'+data[i].prod_desc+'</option>';
        }
        div.find('.prod_desc').html(" ");
        div.find('.prod_desc').append(op);
      },
      erro:function(){

      }

     });
    });

    $(document).on('change','.prod_desc',function(){
      var prod_id=$(this).val();
      var a=$(this).parent();
      console.log(prod_id);
      var op="";

           $.ajax({
      type:'get',
      url:'{!!URL::to('findProductCod') !!}',
      data:{'id':prod_id},
      dataType:'json',
      success:function(data){
        console.log("prod_cod");
        console.log(data.prod_cod);
        a.find('.prod_cod').val(data.prod_cod);

      },
      erro:function(){

      }

     });

    });


        $(document).on('change','.numero_pedido',function(){
      var numero_pedido=$(this).val();
      var b=$(this).parent();
      console.log(numero_pedido);
      var op="";

           $.ajax({
      type:'get',
      url:'{!!URL::to('findItensPedido') !!}',
      itenspedido:{'id':numero_pedido}, 
      //dataType:'json',     
      success:function(itenspedido){
        console.log("numero_pedido");
       console.log(itenspedido.numero_pedido);
        a.find('.numero_pedido').val(itenspedido.numero_pedido);

      },
      erro:function(){

      }

     });

    });

  });
</script>         

</div>