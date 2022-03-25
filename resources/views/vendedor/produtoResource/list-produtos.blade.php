@extends('templates.vendedor-login')

@section('css-view')
@endsection

@section('js-view')
@endsection


@section('templates.menu-superior-vendedor')
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
     
    </ol>     
@if (session('message'))
<div class="alert alert-success alert-dismissible">
  <a href="#" class="close" 
  data-dismiss="alert"
  aria-label="close">&times;</a>
 <b> {{ session('message') }}</b>
</div>
@endif
<form method="POST" action="{{route('ProdutoInter.search')}}"> 
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
        <a href="{{route('productinter.index')}}" 
            data-toggle="tooltip" 
            data-placement="top"
            title="Limpar Pesquisa" class="btn waves-effect"><i class="fa fa-eraser"></i></a>
      <a href="#" title="Usuário não possui permissão para cadastro de produtos" 
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
            <th id="center">Código
            
            </th>
            <th id="center">Descrição dos Produtos
             
            </th>
             <th id="center" title="Categoria do produto" >Categoria
             
            </th>
            <th id="center" title="Preço Padrão">Preço 
         
          </th> 
          <th id="center" title="Preço para Profissionais">Preço
           
          </th>
          <th id="center" title="Preço Balcão">Preço
         
          </th>               
          <!--<th id="center">Imagem</th>-->               
                
      </tr>
    </thead>
  </form>
  <tbody>
    @foreach($products as $product)
    <tr>
      <td title="Código do produto" id="center">
       <span class="chip">{{$product->prod_cod}}</span></td>
      <td title="Descrição do produto">{{$product->prod_desc}}</td>
      <td title="Categoria do produto">{{$product->grupCateg->grup_desc_categ}}</td>
      <td  style="width: 80px;" title="Preço Padrão">R$ {{number_format($product->prod_preco_padrao, 2,',','.')}}</td>
      <td  style="width: 80px;" title="Preço para Profissionais">R$ {{number_format($product->prod_preco_prof, 2,',','.')}}</td>
      <td  style="width: 80px;" title="Preço Balcão">R$ {{number_format($product->prod_preco_balcao, 2,',','.')}}</td>

             
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
