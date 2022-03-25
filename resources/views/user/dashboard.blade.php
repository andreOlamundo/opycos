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
      <h2 style="margin-bottom: 0px;" ><b>Novo Usuário</b></h2>      
     <div class="row">
       <div class="col-md-12">   
       <ol class="breadcrumb" style="margin-bottom: 10px;">
            <li><a href="{{route('list.users')}}" id="btn" style="text-decoration: none"><b>Escolha o perfil</b></a></li>
            <li class="active">Pedidos</li>                 
      </ol>     
      
    <ul class="collection">
    <li class="collection-item avatar">
      <i class="material-icons circle red">assignment_ind</i>
      <span class="title">Administrador</span>
      <p>Perfil Avançado.<br>
         <b>Leitura, Gravação.</b>
      </p>
      <a href="{{route('admins.create')}}" class="secondary-content"><span style="font-size: 15px;" class="new badge" data-badge-caption="Usuários"><span class="glyphicon glyphicon-send"></span> {{$total}}</span></a>
    </li>
    <li class="collection-item avatar">
      <i class="material-icons circle yellow">account_box</i>
      <span class="title">Vendedor</span>
      <p>Perfil Intermediário.<br>
         <b>Leitura(!), Gravação(!)</b>
      </p>
      <a href="{{route('vendedores.create')}}" class="secondary-content"><span style="font-size: 15px;" class="new badge" data-badge-caption="Usuários"><span class="glyphicon glyphicon-send"></span> {{$totalV}}</span></a>
    </li>
    <li class="collection-item avatar">
      <i class="material-icons circle green">perm_identity</i>
      <span class="title">Cliente</span>
      <p>Perfil Básico<br>
         <b>Somente Leitura</b>
      </p>
      <a href="{{route('clientes.create')}}" class="secondary-content"><span style="font-size: 15px;" class="new badge" data-badge-caption="Usuários"> <span class="glyphicon glyphicon-send"></span> {{$totalC}}</span></a>
    </li>
  </ul>

          <div class="fixed-action-btn">
            
       
  <a class="btn-floating btn-large lighten-2" title="Escolha um Perfil">
    <i class="large material-icons">mode_edit</i>
  </a>
  <ul>
    <li><a href="{{route('admins.create')}}" class="btn-floating red" title="Administrador"><i class="material-icons">assignment_ind</i></a></li>
    <li><a href="{{route('vendedores.create')}}" class="btn-floating yellow" title="Vendedor"><i class="material-icons">account_box</i></a></li>
    <li><a href="{{route('clientes.create')}}" class="btn-floating green" title="Cliente"><i class="material-icons">perm_identity</i></a></li>
    
  </ul>
  
</div>


      
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('.fixed-action-btn');
    var instances = M.FloatingActionButton.init(elems, {
      direction: 'left'
    });
  });

  // Or with jQuery

  $(document).ready(function(){
    $('.fixed-action-btn').floatingActionButton();
  });

  var instance = M.FloatingActionButton.getInstance(elem);
  instance.open();
  instance.close();
  instance.destroy();
  </script>
@endsection