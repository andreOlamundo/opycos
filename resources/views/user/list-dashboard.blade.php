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

      <h2><b>Perfis</b></h2>
         <div class="divider" style="margin-bottom: 3px; margin-top: -8px;" ></div>
     <div class="row">
       <div class="col-md-12"> 
              <ol class="breadcrumb" style="margin-bottom: 10px;">
                <li class="active"><b>Perfil:</b></li>
            <li><a href="{{route('admins.index')}}" id="btn" style="text-decoration: none"><b>Administrador</b></a></li>
            <li><a href="{{route('vendedores.index')}}" id="btn" style="text-decoration: none"><b>Vendedor</b></a></li>
            <li><a href="{{route('clientes.index')}}" id="btn" style="text-decoration: none"><b>Cliente</b></a></li>                
      </ol> 
      
   

             <ul class="collection">
    <li class="collection-item avatar">
      <i class="material-icons circle red">assignment_ind</i>
      <span class="title">Administrador</span>
      <p>Perfil Avançado.<br>
       <b> Controle total de Pedidos, Produtos e Usuários.</b><br>
        As alterações e exclusões respeitam os relacionamentos entre as entidades.<br>  
         <b>Leitura, Gravação.</b>
      </p>
      <a href="{{route('admins.index')}}" class="secondary-content"><span style="font-size: 15px;" class="new badge" data-badge-caption="Usuários"><span class="glyphicon glyphicon-search"></span> {{$total}}</span></a>
    </li>
    <li class="collection-item avatar">
      <i class="material-icons circle yellow">account_box</i>
      <span class="title">Vendedor</span>
      <p>Perfil Intermediário.<br>
       <b> Controla os próprios Pedidos e Clientes, somente visualiza Produtos</b><br>
        As alterações respeitam os relacionamentos entre as entidades.<br>
        <b>Leitura, Gravação</b>
      </p>
      <a href="{{route('vendedores.index')}}" class="secondary-content"><span style="font-size: 15px;" class="new badge" data-badge-caption="Usuários"><span class="glyphicon glyphicon-search"></span> {{$totalV}}</span></a>
    </li>
    <li class="collection-item avatar">
      <i class="material-icons circle green">perm_identity</i>
      <span class="title">Cliente</span>
      <p>Perfil Básico.<br>
        Visualiza apenas os próprios dados cadastrais e Pedidos.<br>
         <b>Somente Leitura.</b>
      </p>
      <a href="{{route('clientes.index')}}" class="secondary-content"><span style="font-size: 15px;" class="new badge" data-badge-caption="Usuários"><span class="glyphicon glyphicon-search"></span> {{$totalC}}</span></a>
    </li>
  </ul>

          <div class="fixed-action-btn">
            
       
  <a class="btn-floating btn-large lighten-2">
    <i class="large material-icons">search</i>
  </a>
  <ul>
    <li><a href="{{route('admins.index')}}" class="btn-floating red" title="Administrador"><i class="material-icons">assignment_ind</i></a></li>
    <li><a href="{{route('vendedores.index')}}" class="btn-floating yellow" title="Vendedor"><i class="material-icons">account_box</i></a></li>
    <li><a href="{{route('clientes.index')}}" class="btn-floating green" title="Cliente"><i class="material-icons">perm_identity</i></a></li>
    
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