@extends('templates.cliente-login')

@section('css-view')
@endsection

@section('js-view')
@endsection

@section('templates.menu-superior-cliente')
@endsection

@section('conteudo-view')
<div id="line-one">
<div id="line-one">
<div class="container"> 

      <h2><b>Meu Cadastro</b></h2> 
      <hr>
      
  <div class="row">
    <div class="col-md-12">
  
      @if (session('message'))
<div class="alert alert-success alert-dismissible">

  <a href="#" class="close" 
  data-dismiss="alert"
  aria-label="close">&times;</a>
  <b>{{ session('message') }}</b>
</div>
@endif
 
</div>
    <div class="col-md-12"> 
      @foreach($clientes as $cliente)
      <div class="card-panel teal lighten-2">
        <ul>
          <strong>
          <li style="color: white;  font-size: 1.17em;">Documento: {{$cliente->cpf}} {{$cliente->cnpj}} </li>
          <li style="color: white; font-size: 1.17em;">Nome: {{$cliente->name}}</li>
          <li style="color: white; font-size: 1.17em;">Email: {{$cliente->email}}</li>
          <li style="color: white; font-size: 1.17em;">Telefone: {{$cliente->tel}}</li>
          <li style="color: white; font-size: 1.17em;">Celular: {{$cliente->cel}}</li>
          <li style="color: white; font-size: 1.17em;">Endereço: {{$cliente->endereço}}</li>
          <li style="color: white; font-size: 1.17em;">Cep: {{$cliente->cep}}</li>
          </strong>     
        </ul>
        <div class="tab col s3" style="color: white; width: 5%;">
         <a href="#" 
         data-toggle="tooltip" 
         data-placement="top"
         title="Alterar" class="btn-floating waves-effect amber" style="width: 100%;"><i class="small material-icons">border_color</i></a>
       </div>
        


      </div>
       @endforeach          


</div>



</div>
</div>
</div>


</div>
</div>


@endsection

