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

      <h2><b>Meu Cadastro</b></h2>
         <div class="divider" style="margin-bottom: 3px; margin-top: -8px;" ></div> 

      
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
      @foreach($admins as $admin)
      <div class="card-panel teal lighten-2">
        <ul>
          <strong>
          <li style="color: white;  font-size: 1.17em;">Documento: {{$admin->cpf}} {{$admin->cnpj}} </li>
          <li style="color: white; font-size: 1.17em;">Nome: {{$admin->name}}</li>
          <li style="color: white; font-size: 1.17em;">Email: {{$admin->email}}</li>
          <li style="color: white; font-size: 1.17em;">Telefone: {{$admin->tel}}</li>
          <li style="color: white; font-size: 1.17em;">Celular: {{$admin->cel}}</li>
          <li style="color: white; font-size: 1.17em;">Endereço: {{$admin->endereço}}</li>
          <li style="color: white; font-size: 1.17em;">Cep: {{$admin->cep}}</li>
          </strong>     
        </ul>
        <div class="tab col s3" style="color: white; width: 5%;">
         <a href="{{route('admins.edit', $admin->id)}}" 
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

