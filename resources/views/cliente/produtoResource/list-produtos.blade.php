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
    <h2>Listagem de Produtos</h2>
<div class="row">
  <div class="col-md-12">
    <ol class="breadcrumb" style="margin-bottom: 10px;">                       
      <li class="active"><b>Produtos</b></li>
      <li><a href="{{route('product.create')}}" id="btn" style="text-decoration: none"><b>Cadastro</b></a></li>
    </ol>     
@if (session('message'))
<div class="alert alert-success alert-dismissible">
  <a href="#" class="close" 
  data-dismiss="alert"
  aria-label="close">&times;</a>
 <b> {{ session('message') }}</b>
</div>
@endif
<form method="POST" action="{{route('Produto.search')}}"> 
    {{ csrf_field() }}  
    <div class="card-panel">
      <div class="row">
      <div class="col-md-4">  
        <div class="input-field">   
          <select id="produtos" onchange="submit()" name="grup_categ_cod">
            @foreach($list_groupsProd as $registro)
            <option></option>
            <option value="{{ $registro->grup_categ_cod }}">{{$registro->grup_desc_categ}} </option>

            @endforeach     
          </select>
          <label for="grup_categ_cod" style="font-size: 15px; margin-top: -30px;">Escolha a categoria para pesquisa </label>
         </div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>      
        <script type="text/javascript">
          $("#produtos").select2({
            placeholder:'---Selecione a categoria---'
          });
        </script>                  
      </div>
      <a href="{{route('product.create')}}" 
    class="btn-floating btn-large waves-effect waves-light btn-primary pull-right">
    <i class="material-icons">add</i></a>
  </div>
  </div>           

</div>

  <div class="col-md-12">             
    <div class="table-responsive">
      <table class="table table-striped table-bordered table-condensed table-hover">
        <thead>
          <tr class="warning">
            <th style="width: 100px;">Código
              <input type="text" name="prod_cod" class="form-control input-sm" maxlength='5' onkeypress='mascara( this, mnum );'>
            </th>
            <th>Descrição dos Produtos<br>
              <input type="text" name="prod_desc" class="form-control input-sm">
            </th>
             <th>Categoria<br>
              <input type="text" name="grup_desc_categ" class="form-control input-sm">
            </th>
            <th>Preço Padrão
             <div class="input-group">
              
              <input type='text' class="form-control input-sm" maxlength='8' name="prod_preco_padrao" size='15' pattern='([0-9]{1,3}\.)?[0-9]{1,3}.[0-9]{2}$' title="Insira um numero válido Ex 0.00" onkeypress='mascara( this, fvalor );'>
            </div>
          </th> 
          <th>Preço p/Prof
            <div class="input-group">              
              <input type='text' class="form-control input-sm" maxlength='8' name="prod_preco_prof" size='15' pattern='([0-9]{1,3}\.)?[0-9]{1,3}.[0-9]{2}$' title="Insira um numero válido Ex 0.00" onkeypress='mascara( this, fvalor );'>
            </div>
          </th>
          <th>Preço Balcão
             <div class="input-group">
            
              <input type='text' class="form-control input-sm" maxlength='8' name="prod_preco_balcao" size='15' pattern='([0-9]{1,3}\.)?[0-9]{1,3}.[0-9]{2}$' title="Insira um numero válido Ex 0.00" onkeypress='mascara( this, fvalor );'>
            </div>
          </th>               
          <!--<th id="center">Imagem</th>-->                
          <th style=" width: 110px;" id="center">Ações
           <br><br>
            <a href="{{route('product.index')}}" 
            data-toggle="tooltip" 
            data-placement="top"
            title="Limpar Pesquisa" class="btn waves-effect"><i class="fa fa-eraser"></i></a> &nbsp;

            <button data-toggle="tooltip" 
            data-placement="top"
            title="Pesquisar" type="submit" class="btn waves-effect">
            <i class="fa fa-search"></i>                                                   
          </button>

        </th>                
      </tr>
    </thead>
  </form>
  <tbody>
    @foreach($products as $product)
    <tr>
      <td title="Código do produto" id="center" >
       <span class="chip">{{$product->prod_cod}}</span></td>
      <td title="Descrição">{{$product->prod_desc}}</td>
      <td title="Descrição">{{$product->grupCateg->grup_desc_categ}}</td>
      <td title="Preço">R$ {{number_format($product->prod_preco_padrao, 2,',','.')}}</td>
      <td title="Preço">R$ {{number_format($product->prod_preco_prof, 2,',','.')}}</td>
      <td title="Preço">R$ {{number_format($product->prod_preco_balcao, 2,',','.')}}</td>

      <td title="Ações" id="center">
        <a href="{{route('product.edit', $product->id)}}" 
         data-toggle="tooltip" 
         data-placement="top"
         title="Alterar" class="btn waves-effect amber"><i class="small material-icons">border_color</i></a>
         &nbsp;


         <form style="display: inline-block;" method="POST" 
         action="{{route('product.destroy', $product->id)}}"                                                        
         data-toggle="tooltip" data-placement="top"
         title="Excluir" 
         onsubmit="return confirm('Confirma exclusão?')">
         {{method_field('DELETE')}}{{ csrf_field() }}                                                
         <button type="submit" class="btn waves-effect deep-orange">
         <i class="fa fa-trash" style="font-size:13px"></i>                                                   
        </button></form></td>               
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
</div>



<div class="row">
  <div class="col-sm-5 pull-left">
    <p class="text-cinza"> Mostrando 1 até 10 de {{$total}} Registros</p>
  </div>
  <div class="pull-right">
    @if (isset($dataForm))
    {!! $products->appends($dataForm)->links() !!}
    @else
    {!! $products->links() !!}
    
    @endif
  </div>
</div>

</div>

</div>
</div>
</div>



@endsection
