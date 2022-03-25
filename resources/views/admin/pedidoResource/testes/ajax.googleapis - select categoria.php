<div class="col-md-4">  
    <span><b>Selecione a categoria do produto</b></span>
     <select class="grup_desc_categ" id="grup_categ_cod" name="grup_categ_cod" style="width: 400px;"><!--name="grup_categ_cod"-->
      <option value="0" disabled="true" selected="true">---Selecione a categoria---</option>
      @foreach($list_CategProd as $dgp)
      
      <option value="{{$dgp->grup_categ_cod}}">{{$dgp->grup_desc_categ}}</option>
      @endforeach
      </select> <br><br>
    
 


      <select class="grup_desc"  name="grup_desc" style="width: 400px;" id="">
        <option value="0" disabled="true" selected="true">---Selecione o grupo---</option>
        @foreach($list_groups as $dp)        
        <option value="{{$dp->grup_desc}}">{{$dp->grup_desc}}</option>
        @endforeach    

      </select><br><br>       
         
    

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script type="text/javascript">
  
  $(document).ready(function(){
    $(document).on('change','.grup_desc_categ',function(){
     //console.log("humm its change");
      var grup_categ_cod=$(this).val();
     // console.log(cod_id);
     var div=$(this).parent();
     var op=" ";

     $.ajax({
      type:'get',
      url:'{!!URL::to('findProductName') !!}',
      data:{'id':grup_categ_cod},
      success:function(data){
     // console.log('success');
       // console.log(data);
        //console.log(data.length);
        op+='<option value="0" selected disabled>Escolha um grupo</option>';
        for(var i=0;i<data.length;i++){
          op+='<option value="'+data[i].id+'">'+data[i].grup_desc+'</option>';
        }
        div.find('.grup_desc').html(" ");
        div.find('.grup_desc').append(op);
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




  });
</script> 
</div>