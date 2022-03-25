@extends('templates.admin-login')

@section('css-view')
@endsection

@section('js-view')
@endsection

@section('templates.menu-superior-admin')
@endsection

@section('conteudo-view') 

<div class="container-fluid">
    <div class="row">
        <h3>Adicionar Produtos</h3>
        <div class="divider"></div>

        <div class="section col-md-12"> 

            <form method="POST" action="{{ route('pedidos.addProduct') }}">
                {{ csrf_field() }}

<div class="col-md-4">
    <div class="form-group">
      <select id="products" name="id" style="width: 400px;" required="required">
        @foreach($products as $prod)
        <option></option>
        <option value="{{$prod->prod_cod}}" >{{$prod->name}}</option>
        @endforeach     
      </select>
    </div><br>
    
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>      
 <script type="text/javascript">
    $("#products").select2({
      placeholder:'---Selecione o Produto---'
    });
  </script>
</div>

 <button class="btn-large col l6 m6 s6 green accent-4 tooltipped" data-position="bottom" data-delay="50" data-tooltip="O produto será adicionado ao seu pedido">Adicionar</button>   
            </form>
        </div>
      
    </div>
</div>
@endsection