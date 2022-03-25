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
             
          <h2><b>Alterar produto</b></h2>

      <div class="row">
        <div class="col-md-12">
          <ol class="breadcrumb">                            
            <li><a href="{{route('product.index')}}" id="btn" style="text-decoration: none"><b>Produtos</b></a></li>
            <li><a href="{{route('product.create')}}" id="btn" style="text-decoration: none"><b>Cadastro</b></a></li>                  
            <li class="active"><b>Alteração</b></li>
          </ol>                 

          <form method="post" 
          action="{{route('product.update', $product->id)}}" 
          enctype="multipart/form-data">
          {!! method_field('put') !!}
          {{ csrf_field() }} 
          <div class="card-panel">
          <div class="row">
            <div class="col-md-2">              
              <div class="input-field">

               <input type="text" name="prod_cod" placeholder='Digite o Código' 
               class="form-control" maxlength='5' title="Digite o Código contendo 5 Digitos" onkeypress='mascara( this, mnum );' value="{{$product->prod_cod or old('prod_cod')}}" 
               required autofocus>
               <label for="prod_cod" style="font-size: 15px;">Código</label>
             </div>
           </div> 
           <div class="col-md-2">
             <div class="input-field">

              <input type='text' required='required' placeholder='0,00' class="form-control" value="{{number_format($product->prod_preco_padrao, 2,',','.' or old('prod_preco_padrao'))}}" maxlength='15' name="prod_preco_padrao" pattern='([0-9]{1,3}\.)?[0-9]{1,3},[0-9]{2}$' size='15' title="Preço de venda Padrão" onkeypress='mascara( this, mvalor );'>
              <label for="prod_preco_padrao" style="font-size: 15px;">Preço praticado</label>
            </div>
          </div>

          <div class="col-md-2">
            <div class="input-field"> 
              <input type='text' required='required' placeholder='0,00' class="form-control" value="{{number_format($product->prod_preco_prof, 2,',','.' or old('prod_preco_prof'))}}" maxlength='15' name='prod_preco_prof' pattern='([0-9]{1,3}\.)?[0-9]{1,3},[0-9]{2}$' size='15' title=" Preço de venda para Profissionais" onkeypress='mascara( this, mvalor );'> 
              <label for="prod_preco_prof" style="font-size: 15px;">Preço segmentado</label>
            </div>  
          </div>

          <div class="col-md-2">
            <div class="input-field">
              <input type='text' required='required' placeholder='0,00' class='form-control' value="{{number_format($product->prod_preco_balcao, 2,',','.' or old('prod_preco_balcao'))}}" maxlength='15'  name='prod_preco_balcao' pattern='([0-9]{1,3}\.)?[0-9]{1,3},[0-9]{2}$' size='15' title=" Preço de venda Balcão" onkeypress='mascara( this, mvalor );'>
              <label for="prod_preco_balcao" style="font-size: 15px;">Preço segmentado</label>
            </div>
          </div>
               <div class="col-md-4">
      <div class="input-field">
        <select id="grupCateg" name="grup_cod" title="Revisão obrigatória do campo" required="required">
          @foreach($list_CategProd as $registro)
          <option></option>
          <option value="{{ $registro->id }}">Grupo:{{$registro->grup_desc}} <br>Categoria:{{$registro->grup_desc_categ}}</option>

          @endforeach     
        </select>
        <label for="grup_cod" style="font-size: 15px; margin-top: -30px;">Categoria do produto </label> 

      </div>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>      
      <script type="text/javascript">
        $("#grupCateg").select2({
          placeholder:'Grupo:{{$product->grupCateg->grup_desc or old('grup_desc')}} Categoria:{{$product->grupCateg->grup_desc_categ or old('grup_desc_categ')}}'
        });
      </script> 
    </div>
          <!--<div class="col-md-4"> 
             <div class="input-field">
                                               
              <select id="groups" name="grup_categ_cod" title="Categoria do produto" required="required">
                @foreach($list_CategProd as $registro)
                <option></option>
                <option value="{{ $registro->grup_cod }}">{{$registro->grup_desc}}</option>

                @endforeach     
              </select>
              <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
              <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>      
              <script type="text/javascript">
                $("#groups").select2({
                  placeholder:'{{$product->grupCateg->grup_desc or old('grup_desc')}}'

                });
              </script> 
              <label for="prod_preco_balcao" style="font-size: 15px; margin-top: -30px;">Grupo do produto </label> 
            </div>
            
          </div> -->
          <div class="col-md-8">
            <div class="input-field">
              <input type='text' class="form-control" maxlength="191" value="{{$product->prod_desc or old('prod_desc')}}" name="prod_desc" placeholder="Digite Aqui:" title="Breve Descriçaõ do Produto" required/>
              <label for="prod_desc" style="font-size: 15px;">Descrição</label>
            </div>
          </div> 

    
          <div class="col-md-12"><br>
            <button type="reset" class="btn btn-default"> 
              <b>Restaurar</b>
            </button>
            <button type="submit" 
            class="btn waves-effect amber">
            <b>Alterar</b>
          </button>
        </div>
      </div>
    </div>
    </form> 
  </div>
</div>            
</div>
</div>
</div>

@endsection