@extends('templates.vendedor-login')

@section('css-view')
@endsection

@section('js-view')
@endsection

@section('templates.menu-superior-vendedor')
@endsection

@section('conteudo-view')

<div class="container-fluid">
 <div class="row">
  <div class="col-md-12">
    <h2>Listagem de Produtos</h2>
    <hr>
  </div>
</div>
<div class="row">
  <div class="col-md-12">
    <ol class="breadcrumb">                       
      <li class="active">Produtos</li>
      <li><a href="{{route('product.create')}}">Cadastro</a></li>
    </ol>
    <a href="{{route('product.create')}}" 
    class="btn btn-primary btn-sm pull-right">
    <span class="glyphicon glyphicon-plus"></span><b> Adicionar</b></a>  

    <div class="pull-right">
      <div class="form-group"> &nbsp;
        <select class="form-control form-control-sm" name="department" style=" width: 225px;  float: left;">
          <option value="">---- Selecione o Grupo ----</option>
          <option value=""> Higienização </option>
          <option value=""> Finalização de Procedimentos </option>
          <option value=""> Cuidados Pele Madura </option>
          <option value=""> Linha Facial Ortomolecular </option>
          <option value=""> Tratamento Profissional Rejuvenescimento </option>
          <option value=""> Rejuvenescimento Facial </option>
          <option value=""> Tratamento Profissional Acne e Pele Oleosa </option>

        </select>

      </div>
    </div>          

  </div>           
</div><br>


<div class="row">
  <div class="col-md-12">  
   <form method="POST" action="{{route('Produto.search')}}"> 
    {{ csrf_field() }}         
    <div class="table-responsive">
      <table class="table table-striped table-bordered table-condensed table-hover">
        <thead>
          <tr class="warning">
            <th id="center">Código
              <input type="text" placeholder="Pesquisar Códico" name="prod_cod" class="form-control input-sm" maxlength='5' onkeypress='mascara( this, mnum );'>
            </th>
            <th id="center">Descrição dos Produtos
              <input type="text" placeholder="Pesquisar Descrição" name="prod_desc" class="form-control input-sm">
            </th>
            <th id="center">Preço Padrão
             <div class="input-group">
              <span class="input-group-addon"><b>R$</b></span>
              <input type='text' placeholder='Pesquisar...' class="form-control input-sm" maxlength='8' name="prod_preco_padrao" size='15' pattern='([0-9]{1,3}\.)?[0-9]{1,3}.[0-9]{2}$' title="Ex: 0.00 / 000.00 / 0.000.00" onkeypress='mascara( this, fvalor );'>
            </div>
          </th> 
          <th id="center">Preço p/Prof
            <div class="input-group">
              <span class="input-group-addon"><b>R$</b></span>
              <input type='text' placeholder='Pesquisar...' class="form-control input-sm" maxlength='8' name="prod_preco_prof" size='15' pattern='([0-9]{1,3}\.)?[0-9]{1,3}.[0-9]{2}$' title=" 0.00 / 000.00 / 0.000.00" onkeypress='mascara( this, fvalor );'>
            </div>
          </th>
          <th id="center">Preço Balcão
             <div class="input-group">
              <span class="input-group-addon"><b>R$</b></span>
              <input type='text' placeholder='Pesquisar...' class="form-control input-sm" maxlength='8' name="prod_preco_balcao" size='15' pattern='([0-9]{1,3}\.)?[0-9]{1,3}.[0-9]{2}$' title="Ex: 0.00 / 000.00 / 0.000.00" onkeypress='mascara( this, fvalor );'>
            </div>
          </th>               
          <!--<th id="center">Imagem</th>-->                
          <th style=" width: 80px;" id="center">Ações <br>
            <a href="{{route('product.index')}}" 
            data-toggle="tooltip" 
            data-placement="top"
            title="Limpar Pesquisa"><i class="fa fa-eraser"></i></a> &nbsp;

            <button data-toggle="tooltip" 
            data-placement="top"
            title="Pesquisar" type="submit" style="background-color: #fff">
            <a ><i class="fa fa-search"></i></a>                                                    
          </button>

        </th>                
      </tr>
    </thead>
  </form>
  <tbody>
    @foreach($products as $product)
    <tr>
      <td title="Código do produto" id="center">{{$product->prod_cod}}</td>
      <td title="Descrição" id="center">{{$product->prod_desc}}</td>
      <td title="Preço" id="center">R$ {{number_format($product->prod_preco_padrao, 2,',','.')}}</td>
      <td title="Preço" id="center">R$ {{number_format($product->prod_preco_prof, 2,',','.')}}</td>
      <td title="Preço" id="center">R$ {{number_format($product->prod_preco_balcao, 2,',','.')}}</td>

      <td title="Ações" id="center">
        <a href="{{route('product.edit', $product->id)}}" 
         data-toggle="tooltip" 
         data-placement="top"
         title="Alterar"><i class="fa fa-pencil"></i></a>
         &nbsp;


         <form style="display: inline-block;" method="POST" 
         action="{{route('product.destroy', $product->id)}}"                                                        
         data-toggle="tooltip" data-placement="top"
         title="Excluir" 
         onsubmit="return confirm('Confirma exclusão?')">
         {{method_field('DELETE')}}{{ csrf_field() }}                                                
         <button type="submit" style="background-color: #fff">
          <a><i class="fa fa-trash-o"></i></a>                                                    
        </button></form></td>               
      </tr>
      @endforeach
    </tbody>
  </table>
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
@if (session('message'))
<div class="alert alert-success alert-dismissible">
  <a href="#" class="close" 
  data-dismiss="alert"
  aria-label="close">&times;</a>
  {{ session('message') }}
</div>
@endif
</div>
</div>

@endsection
