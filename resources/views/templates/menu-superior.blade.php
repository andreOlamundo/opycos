<nav class="navbar navbar-inverse navbar-fixed-top">       
  <div class="navbar-header">
    <button type="button" class="navbar-toggle" 
    data-toggle="collapse" data-target="#myNavbar">
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
  </button>               
</div>
<div class="collapse navbar-collapse" id="myNavbar">
  <ul class="nav navbar-nav" id="link-white">
    <li>
      <a href="{{route('product.index')}}" style="text-decoration: none">
        <span class="glyphicon glyphicon-home"></span> 
        <span id="underline">Panel</span>
      </a>
    </li>
    <li class="dropdown">
      <a id= "dropdown-item" class="dropdown-toggle" data-toggle="dropdown" 
      href="#" style="text-decoration: none">
      <span class="glyphicon glyphicon-pencil"></span>
      <span id="underline">Cadastros</span> 
      <span class="caret"></span></a>
      <ul class="dropdown-menu">                           
        <li><a href="{{route('product.index')}}">Produtos</a></li>
        <li class="divider"></li>
        <li><a href="#">Pedidos</a></li>
        <li class="divider"></li>
        <li><a href="#">Vendedores</a></li>
        <li class="divider"></li>
        <li><a href="#">Clientes</a></li>
      </ul>
    </li>
    <li class="dropdown">
      <a id= "dropdown-item" class="dropdown-toggle" data-toggle="dropdown" 
      href="#">
      <span class="glyphicon glyphicon-th"></span> 
      <span id="underline">Consultas</span>
      <span class="caret"></span></a>
      <ul class="dropdown-menu">                           
        <li><a href="{{route('product.index')}}">Produtos</a></li>
        <li class="divider"></li>
        <li><a href="#">Pedidos</a></li>
        <li class="divider"></li>
        <li><a href="#">Vendedores</a></li>
        <li class="divider"></li>
        <li><a href="#">Clientes</a></li>    
      </ul>
    </li>
  </ul>
  <ul class="nav navbar-nav navbar-right" id="link-white">
    <li class="dropdown">
      <a href="#" style="text-decoration: none">
        <img src="{{URL::asset('img/favicon.png')}}" 
        class="img-circle" width="26" height="26" 
        style="margin-top: -3px" title="Opycos"> 
        <span id="underline">Administrator</span> 
      </a>                      
    </li>
    <li><a href="{{ asset('/login') }}" style="text-decoration: none">
     <span class="glyphicon glyphicon-log-in"></span> 
     <span id="underline">Sair</span></a></li>  
     <li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li>
   </ul>
 </div>       
</nav>