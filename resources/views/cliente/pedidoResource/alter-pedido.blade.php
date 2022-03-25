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
    <div class="col-md-12">          
      <h2><b>Cadastro de Pedidos</b></h2>      
    </div>
  </div>
  
  <ol class="breadcrumb">                         
    <li><a href="#">Pedidos</a></li>                  
    <li class="active">Cadastro</li>
  </ol>    
  


  <div class="row">
    <!-- Select Multiple Departamento -->


        <form method="post" 
            action="{{route('pedidos.update', $pedidos->numero_pedido)}}"
            enctype="multipart/form-data">
            {!! method_field('put') !!}
            {{ csrf_field() }}


            <div class="col-md-5">              
           <div class="form-group">
        <div class="input-group">
          <span class="input-group-addon">Observação</span>
          <textarea name="obs_pedido" class="form-control" placeholder="Observações gerais do pedido" title="Breve descrição das observações do pedidos" rows="1"  maxlength="100" required="required" value="{{$pedidos->obs_pedido or old('obs_pedido')}}"></textarea>
        </div>
      </div><br>
    




<!-- Submete Form -->
<div class="col-md-12">
  <button type="reset" class="btn btn-default">
    Limpar
  </button>
  <button type="submit" 
  class="btn btn btn-primary btn-sm">
  <b>Cadastrar</b>
</button>
</form>
</div>
</div>


</div>



<script type="text/javascript">


// funcao remove uma linha da tabela
function removeLinha(linha) {
  var i=linha.parentNode.parentNode.rowIndex;
  document.getElementById('tbl').deleteRow(i);

  var total = 0;
  var x = document.getElementsByClassName('valor');
  for (var i = 0; i < x.length; i++) {
    total +=  parseFloat(x[i].value.replace(',','.'));
  }
  document.getElementById('result').value = String(total.toFixed(2)).formatMoney();  
}

String.prototype.formatMoney = function() {
  var x = this;
  
  if(x.indexOf('.') === -1) {

   x = x.replace(/([\d]+)/, "$1,00");
 }

 x = x.replace(/([\d]+)\.([\d]{1})$/, "$1,$20");
 x = x.replace(/([\d]+)\.([\d]{2})$/, "$1,$2");
 x = x.replace(/([\d]+)([\d]{3}),([\d]{2})$/, "$1.$2,$3");


 return x;
}

// funcao calcula as linhas com valores da tabela
function calcular() {
 var total = 0;
 var x = document.getElementsByClassName('valor');
 for (var i = 0; i < x.length; i++) {
   total +=  parseFloat(x[i].value.replace(',','.'));
 }
 document.getElementById("result").value = String(total.toFixed(2)).formatMoney();
}

</script>




