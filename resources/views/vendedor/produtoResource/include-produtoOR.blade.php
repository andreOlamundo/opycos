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
            </div>          
        </div>

        <div class="row"> 
        <div class="col-md-12"> 
          
            <form method="post" 
            action="{{route('product.store')}}">
            {{ csrf_field() }}

            
            <div class="row">
                <div class="col-md-4">
                   <div class="input-field">
                     <input type="text" class="form-control" name="prod_desc" id="prod_desc" onkeypress='mascara( this, soLetras );' placeholder="Breve descriçaõ do produto" title="Breve Descriçaõ do Produto" required />
                     <label for="prod_desc">Descrição do produto</label>
                   </div>              
              </div>

              <div class="col-md-2">           
                  <div class="input-field">  
                    <input type="text" name="prod_cod" id="prod_cod" title="Informe o código contendo 5 dígitos" placeholder="Informe o código" class="form-control" maxlength='5' minlength="5" onkeypress='mascara( this, mnum );' required />
                    <label for="prod_cod">Código do produto </label>
                </div>
            </div>

<div class="col-md-2">
    <div class="input-field">    
    <input type='text' required='required' placeholder="R$" class="form-control" maxlength='15' name="prod_preco_padrao" pattern='([0-9]{1,3}\.)?[0-9]{1,3},[0-9]{2}$' size='15' title="Informe o preço padrão" onkeypress='mascara( this, mvalor );'>
    <label>Preço praticado</label>
</div>
</div>


<div class="col-md-2">
   <div class="input-field">                   
        <input type='text' required='required' placeholder="R$" class="form-control" maxlength='15' name='prod_preco_prof' pattern='([0-9]{1,3}\.)?[0-9]{1,3},[0-9]{2}$' size='15'  title="Informe o preço p/ profissionais" onkeypress='mascara( this, mvalor );'>
        <label>Preço segmentado</label> 

    </div>
</div>

<div class="col-md-2">
   <div class="input-field"> 
     
        <input type="text" required="required" placeholder="R$" title="Informe o preço balcão" class='form-control' maxlength='15'  name='prod_preco_balcao' pattern='([0-9]{1,3}\.)?[0-9]{1,3},[0-9]{2}$' size='15' onkeypress='mascara( this, mvalor );'>
        <label>Preço segmentado</label>
    </div>
</div>
</div>
<div class="row">
<div class="col-md-4">
  <span>Escolha a categoria do produto</span>
                     <select id="produtos" name="grup_categ_cod" required="required">
            @foreach($list_groups as $registro)
            <option></option>
            <option value="{{ $registro->grup_categ_cod }}">{{$registro->grup_desc_categ}}</option>

            @endforeach     
          </select>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>      
        <script type="text/javascript">
          $("#produtos").select2({
            placeholder:'---Selecione a categoria---'
          });
        </script> 
</div>
@foreach($list_CategProd as $registro)
 <input type="hidden" class="form-control" name="grup_cod" value="{{$registro->grup_cod }}"/>
@endforeach 
<div class="col-md-12"> <hr>                  
    <button type="reset" class="btn btn-default">
        <b>Limpar</b>
    </button>
    <button type="submit" 
    class="btn btn-primary"><b>
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

     @endsection