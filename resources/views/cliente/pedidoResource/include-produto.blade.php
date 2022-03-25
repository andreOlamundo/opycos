@extends('templates.admin-login')

@section('css-view')
@endsection

@section('js-view')
@endsection

@section('templates.menu-superior-admin')
@endsection

@section('conteudo-view')



<div class="container">
  <div class="row">
    <div class="col-md-12"> 
           <h2><b>Cadastro de Produto</b></h2>
           <hr>
        </div>
      </div>
        <div class="row">
            <div class="col-md-12">
                <ol class="breadcrumb">
                    <li><a href="{{route('product.index')}}"><b>Produtos</b></a></li>              
                                     
                    <li class="active">Cadastro</li>
                </ol>              
            </div>          
        </div>
        <div class="row"> 
        <div class="col-md-12"> 
          
            <form method="post" 
            action="{{route('product.store')}}" 
            enctype="multipart/form-data">
            {{ csrf_field() }}

            
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                      <div class="input-group">
                          <span class="input-group-addon">Descrição</span>                
                          <textarea class="form-control" rows="1" maxlength="191" name="prod_desc" placeholder="Breve Descriçaõ do Produto" title="Breve Descriçaõ do Produto" required></textarea>
                      </div>
                  </div>
              </div>

              <div class="col-md-2">              
                <div class="form-group">
                    <label for="prod_cod">Código do Produto</label>
                    <input type="text" name="prod_cod" placeholder="Código contendo 5 digitos" 
                    class="form-control" maxlength='5' onkeypress='mascara( this, mnum );' 
                    required>
                </div>
            </div>

<div class="col-md-4">  
    <span><b>Selecione a categoria do produto</b></span>
     <select class="grup_desc_categ" id="grup_desc" name="grup_categ_cod" style="width: 400px;"><!--name="grup_categ_cod"-->
      <option value="0" disabled="true" selected="true">---Selecione a categoria---</option>
      @foreach($list_CategProd as $dgp)
      
      <option value="{{$dgp->grup_categ_cod}}">{{$dgp->grup_desc_categ}}</option>
      @endforeach
      </select> <br><br>
    
 
  <!--<form method="POST" action="{{route('ProdutoPedido.search')}}" id="search"> (id="produtos")->tag select -->
   <!-- {{ csrf_field() }}  -->  

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
<div class="col-md-2">
 <div class="form-group">
    <label>Preço Padrão</label>
    <input type='text' required='required' placeholder='R$ 0,00 ' class="form-control" maxlength='15' name="prod_preco_padrao" pattern='([0-9]{1,3}\.)?[0-9]{1,3},[0-9]{2}$' size='15' onkeypress='mascara( this, mvalor );'>
</div>
</div>
<div class="col-md-2">
    <div class="form-group">
        <label>Preço Prof.</label>                
        <input type='text' required='required' placeholder='R$ 0,00 ' class="form-control" maxlength='15' name='prod_preco_prof' pattern='([0-9]{1,3}\.)?[0-9]{1,3},[0-9]{2}$' size='15' onkeypress='mascara( this, mvalor );'>

    </div>
</div>

<div class="col-md-2">
    <div class="form-group">
        <label for="prod_preco_balcao">Preço Balcão</label>
        <input type='text' required='required' placeholder='R$ 0,00 ' class='form-control' maxlength='15'  name='prod_preco_balcao' pattern='([0-9]{1,3}\.)?[0-9]{1,3},[0-9]{2}$' size='15' onkeypress='mascara( this, mvalor );'>
    </div>
</div>
</div>
<div class="col-md-12">                   
    <button type="reset" class="btn btn-default">
        Limpar
    </button>
    <button type="submit" 
    class="btn btn-warning" id="black">
    Cadastrar
</button>
</div>
</form>             
</div>
</div>

<script type="text/javascript">
              // funcao mascara campo valor onkeypress
              function mascara(o,f){
                v_obj=o
                v_fun=f
                setTimeout("execmascara()",1)
            }
            // funcao mascara campo valor onkeypress
            function execmascara(){
                v_obj.value=v_fun(v_obj.value)
            }
            // funcao mascara alternativa para numeros (Não utilizada)
            function mnum(v){
            v=v.replace(/\D/g,"");//Remove tudo o que não é dígito
            return v;
        }
            // funcao mascara campo valor onkeypress
            function mvalor(v){
             v=v.replace(/\D/g,"");//Remove tudo o que não é dígito
             v=v.replace(/(\d)(\d{8})$/,"$1.$2");//coloca o ponto dos milhões

             v=v.replace(/(\d)(\d{5})$/,"$1.$2");//coloca o ponto dos milhares

             v=v.replace(/(\d)(\d{2})$/,"$1,$2");//coloca a virgula antes dos 2 últimos dígitos
             return v;
         }
     </script>
  

     @endsection