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
 
          <h2><b>Novo Produto</b></h2>
     
      <div class="row">
       <div class="col-md-12"> 
        <ol class="breadcrumb" style="margin-bottom: 10px;">
          <li><a href="{{route('product.index')}}" id="btn" style="text-decoration: none"><b>Produtos</b></a></li>
          <li class="active"><b>Cadastro</b></li>
        </ol>
        <div class="card-panel">                       
        <form method="post" 
        action="{{route('product.store')}}">
        {{ csrf_field() }}
        <div class="row">
          <div class="col-md-2">           
            <div class="input-field">  
              <input type="text" name="prod_cod" id="prod_cod" title="Informe o código do produto contendo 5 dígitos" class="form-control" maxlength='5' minlength="5" onkeypress='mascara( this, mnum );' required autofocus="" />
              <label for="prod_cod" style="font-size: 15px;">Código</label>
            </div>
          </div>
          <div class="col-md-2">
            <div class="input-field">    
              <input type='text' required='required' class="form-control" maxlength='15' name="prod_preco_padrao" pattern='([0-9]{1,3}\.)?[0-9]{1,3},[0-9]{2}$' size='15' title="Informe o preço padrão" onkeypress='mascara( this, mvalor );'>
              <label for="prod_preco_padrao" style="font-size: 15px;">R$</label>
            </div>
          </div>
          <div class="col-md-2">
           <div class="input-field">                   
            <input type='text' required='required' class="form-control" maxlength='15' name='prod_preco_prof' pattern='([0-9]{1,3}\.)?[0-9]{1,3},[0-9]{2}$' size='15'  title="Informe o preço p/ profissionais" onkeypress='mascara( this, mvalor );'>
            <label for="prod_preco_prof" style="font-size: 15px;" >R$</label> 
          </div>
        </div>
        <div class="col-md-2">
         <div class="input-field"> 
          <input type="text" required="required" title="Informe o preço balcão" class='form-control' maxlength='15'  name='prod_preco_balcao' pattern='([0-9]{1,3}\.)?[0-9]{1,3},[0-9]{2}$' size='15' onkeypress='mascara( this, mvalor );'>
          <label for="prod_preco_balcao"  style="font-size: 15px;" >R$</label>
        </div>
      </div>
      <div class="col-md-4">
        <div class="input-field">
          <select id="groups" name="grup_cod" required="required">
            @foreach($list_CategProd as $registro)
            <option></option>
            <option value="{{ $registro->id }}">Grupo:{{$registro->grup_desc}} &verbar;Categoria:{{$registro->grup_desc_categ}}</option>

            @endforeach     
          </select>
          <label for="grup_cod" style="font-size: 15px; margin-top: -30px;">Categoria do produto </label> 

        </div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>      
        <script type="text/javascript">
          $("#groups").select2({
            placeholder:'---Selecione a Categoria---'
          });
        </script> 
      </div>
      <div class="col-md-8">
       <div class="input-field">
         <input type="text" class="form-control" name="prod_desc" id="prod_desc" onkeypress='mascara( this, soLetras );' title="Breve Descriçaõ do Produto" required />
         <label for="prod_desc" style="font-size: 15px;">Descrição</label>
       </div>              
     </div>
     <!--<div class="col-md-4">
      <div class="input-field">
        <select id="grupCateg" name="grup_categ_cod" required="required">
          @foreach($list_CategProd as $registro)
          <option></option>
          <option value="{{ $registro->grup_categ_cod }} ">{{$registro->grup_desc_categ}}</option>
          @endforeach     
        </select>
        <label for="grup_categ_cod" style="font-size: 15px; margin-top: -30px;">Categoria do produto </label> 
      </div>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>      
      <script type="text/javascript">
        $("#grupCateg").select2({
          placeholder:'---Selecione a categoria---'
        });
      </script> 
    </div>-->
    <div class="col-md-12"><br>              
      <button type="reset" class="btn btn-default">
        <b>Limpar</b>
      </button>
      <button type="submit" 
      class="btn waves-effect waves-light  blue darken-2"><b>
      Cadastrar</b>
    </button>
  </div>
</div>
</form>             
</div>
</div>
</div>

</div>
</div>
</div>

@endsection