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
          <h2><b>Novo Produto</b></h2>
        </div>
      </div>
      <div class="row">
       <div class="col-md-12"> 
        <ol class="breadcrumb">
          <li><a href="{{route('product.index')}}" id="btn" style="text-decoration: none"><b>Produtos</b></a></li>             

          <li class="active"><b>Cadastro</b></li>
        </ol>                       
        <form method="post" 
        action="{{route('product.store')}}">
        {{ csrf_field() }}

        <div class="row">
          <div class="col-md-2">           
            <div class="input-field">  
              <input type="text" name="prod_cod" id="prod_cod" title="Informe o código contendo 5 dígitos" placeholder="Cod." class="form-control" maxlength='5' minlength="5" onkeypress='mascara( this, mnum );' required />
              <label for="prod_cod" style="font-size: 15px;">Código do produto </label>
            </div>
          </div>
          <div class="col-md-2">
            <div class="input-field">    
              <input type='text' required='required' placeholder="R$" class="form-control" maxlength='15' name="prod_preco_padrao" pattern='([0-9]{1,3}\.)?[0-9]{1,3},[0-9]{2}$' size='15' title="Informe o preço padrão" onkeypress='mascara( this, mvalor );'>
              <label for="prod_preco_padrao" style="font-size: 15px;">Preço praticado</label>
            </div>
          </div>
          <div class="col-md-2">
           <div class="input-field">                   
            <input type='text' required='required' placeholder="R$" class="form-control" maxlength='15' name='prod_preco_prof' pattern='([0-9]{1,3}\.)?[0-9]{1,3},[0-9]{2}$' size='15'  title="Informe o preço p/ profissionais" onkeypress='mascara( this, mvalor );'>
            <label for="prod_preco_prof" style="font-size: 15px;" >Preço segmentado</label> 

          </div>
        </div>
        <div class="col-md-2">
         <div class="input-field"> 
          <input type="text" required="required" placeholder="R$" title="Informe o preço balcão" class='form-control' maxlength='15'  name='prod_preco_balcao' pattern='([0-9]{1,3}\.)?[0-9]{1,3},[0-9]{2}$' size='15' onkeypress='mascara( this, mvalor );'>
          <label for="prod_preco_balcao"  style="font-size: 15px;" >Preço segmentado</label>
        </div>
      </div>
     <div class="col-md-4">
      <div class="input-field">
        <select id="produtos" name="grup_categ_cod" required="required">
          @foreach($list_CategProd as $registro)
          <option></option>
          <option value="{{ $registro->grup_categ_cod }}">{{$registro->grup_desc_categ}}</option>

          @endforeach     
        </select>
        <label for="prod_preco_balcao" style="font-size: 15px; margin-top: -30px;">Categoria do produto </label> 

      </div>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>      
      <script type="text/javascript">
        $("#produtos").select2({
          placeholder:'---Selecione a categoria---'
        });
      </script> 
    </div>

          <div class="col-md-8">
       <div class="input-field">
         <input type="text" class="form-control" name="prod_desc" id="prod_desc" onkeypress='mascara( this, soLetras );' placeholder="Breve descriçaõ do produto" title="Breve Descriçaõ do Produto" required />
         <label for="prod_desc" style="font-size: 15px;">Descrição</label>
       </div>              
     </div>
    <div class="col-md-12"><br>                 
      <button type="reset" class="btn btn-default">
        <b>Limpar</b>
      </button>
      <button type="submit" 
      class="btn btn-primary"><b>
      Cadastrar</b>
    </button>
  </div>
</div><hr>
</form>             
</div>
</div>

</div>
</div>
</div>

@endsection