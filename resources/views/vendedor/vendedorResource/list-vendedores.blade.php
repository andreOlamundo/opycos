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

      <h2><b>Meu Cadastro</b></h2> 
    
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
 

      @foreach($vendedores as $vendedor)
      <div class="card-panel teal lighten-2">
        <ul>
          <strong>
          <li style="color: white;  font-size: 1.17em;">Documento: {{$vendedor->cpf}} {{$vendedor->cnpj}} </li>
          <li style="color: white; font-size: 1.17em;">Nome: {{$vendedor->name}}</li>
          <li style="color: white; font-size: 1.17em;">Email: {{$vendedor->email}}</li>
          <li style="color: white; font-size: 1.17em;">Telefone: {{$vendedor->tel}}</li>
          <li style="color: white; font-size: 1.17em;">Celular: {{$vendedor->cel}}</li>        
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



@endsection

