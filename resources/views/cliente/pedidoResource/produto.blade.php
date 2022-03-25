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
    <h2>Detalhes do produto</h2><hr>
    </div>
    </div>
<div class="row">
            <div class="col-md-12">
        <ol class="breadcrumb">   
    <li><a href="{{route('carrinho.index')}}"  id="btn" style="text-decoration: none" ><b>Meu Pedido</b></a></li>                 
    
    <li class="active"><a href="{{route('index')}}"  id="btn" style="text-decoration: none" ><b>Novo Pedido</b></a></li>
    <li class="active"><b>Detalhes do Produto</b></li> 
  </ol>  
</div>
</div>


   <div class="col-md-6">
    <div class="row">
<div class="list-group">
    <ul class="list-group">


<li class="list-group-item list-group-item-warning d-flex justify-content-between align-items-center"><b>Produto</b>
      </li>

      <li class="list-group-item d-flex justify-content-between align-items-center"><span class="chip">{!! $registro->prod_cod !!}</span><span>{{ $registro->prod_desc }}</span>
    </li>


      <li class="list-group-item"><b> R$ {{ number_format($registro->prod_preco_padrao, 2, ',', '.') }}</b></li>
       
       <!--<li class="list-group-item"><b> Preço Dif. p/ Profissionais R$ {{ number_format($registro->prod_preco_prof, 2, ',', '.') }}</b></li>
        <li class="list-group-item"><b> Preço Padrão R$ {{ number_format($registro->prod_preco_padrao, 2, ',', '.') }}</b></li>-->
  </ul>

        </div>
      
        <div class="section col s12 m6 l6">
            
            <form method="POST" action="{{ route('carrinho.adicionar') }}">
                {{ csrf_field() }}
                <input type="hidden" name="id" value="{{ $registro->id }}">               
                <button class="btn btn-success" data-position="bottom" data-delay="50" data-toggle="tooltip" 
            data-placement="top"
            title="O produto será adicionado ao seu Pedido"><b>Adicionar</b></button>&nbsp;&nbsp;<a class="btn btn-default" href="{{route('index')}}"><b>Voltar</b></a>  
            </form>
           
        </div>
    
    </div>
</div>
</div>
</div>
@endsection