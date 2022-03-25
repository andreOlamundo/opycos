
<nav class="navbar navbar-inverse navbar-fixed-top">
<div class="container">       
  <div class="navbar-header">
      <a class="navbar-brand" href="#">
    <img src="{{ asset('/img/logo-opycos.png') }}" class="img-responsive"  width="120" height="26" alt="Opycos" title="Opycos"></a>
    <button type="button" class="navbar-toggle" 
    data-toggle="collapse" data-target="#myNavbar">
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
  </button>               
</div>

<div class="collapse navbar-collapse" id="myNavbar">
  <ul class="nav navbar-nav" > 
   @if (Auth::guest()) 

     @else
      <!--<li style="margin-top: 8px;"><a href="{{route('pedido.allcompras')}}" style="text-decoration: none;">
        <span class="glyphicon glyphicon-inbox"></span>
        <span><b id="underline">Pedidos</b></span></a>
      </li>-->

       <li style="margin-top: 8px;"><a href="{{route('pedido.compras')}}" style="text-decoration: none;" class="{{request()->routeIs('pedido.compras') ? 'active' : ''}} {{request()->routeIs('index') ? 'active' : ''}} {{request()->routeIs('index.consignado') ? 'active' : ''}} {{request()->routeIs('pedido.consignado') ? 'active' : ''}} {{request()->routeIs('pedido.info') ? 'active' : ''}} {{request()->routeIs('pedidos/{id}/edit') ? 'active' : ''}} {{request()->routeIs('pedidos/{id}/consig/edit') ? 'active' : ''}} {{request()->routeIs('pedido.infoConsig') ? 'active' : ''}} {{request()->routeIs('compras') ? 'active' : ''}}">  <!--<span class="glyphicon glyphicon-briefcase"></span>  class="{{request()->routeIs('pedido.compras') ? 'active' : ''}}"--><span><b >Pedidos</b></span></a></li>

            <li style="margin-top: 8px;"><a href="{{route('itens.pedidos')}}" style="text-decoration: none;" class="{{request()->routeIs('itens.pedidos') ? 'active' : ''}} {{request()->routeIs('Itens.search') ? 'active' : ''}}"> <!-- <span class="glyphicon glyphicon-briefcase"></span>--><span><b>Vendas</b></span></a></li>
 
       <!-- <li class="dropdown" style="margin-top: 8px;">
      <a id= "dropdown-item" class="dropdown-toggle" data-toggle="dropdown" 
      href="#" style="text-decoration: none;" >
     
      <span><b id="underline">Pedidos</b></span>
      <span class="caret"></span></a>
     <ul class="dropdown-menu" style="margin-top: 7px;">
        <li><a href="{{route('index')}}"><b id="underline">Novo Pedido</b></a></li>        
        <li><a href="{{route('pedido.compras')}}"><b id="underline">Lista de Pedidos</b></a></li>
      </ul>
    </li>-->


      <li style="margin-top: 8px;" class="dropdown">
      <a id= "dropdown-item" class="dropdown-toggle {{request()->routeIs('product.index') ? 'active' : ''}} {{request()->routeIs('requestopycos.index') ? 'active' : ''}} {{request()->routeIs('requestopycos.create') ? 'active' : ''}}  {{request()->routeIs('product.create') ? 'active' : ''}} {{request()->routeIs('categoria.create') ? 'active' : ''}} {{request()->routeIs('categoria.index') ? 'active' : ''}} {{request()->routeIs('product.edit') ? 'active' : ''}} {{request()->routeIs('requestopycos.edit') ? 'active' : ''}}" data-toggle="dropdown" 
      href="#" style="text-decoration: none;" >
        <!--<span class="glyphicon glyphicon-th-list"></span>-->
      <span><b id="underline">Estoque</b></span>
      <span class="caret"></span></a>
      <ul class="dropdown-menu" style="margin-top: 7px;">
       
        <li><a href="{{route('product.index')}}" class="{{request()->routeIs('product.index') ? 'active' : ''}} {{request()->routeIs('product.create') ? 'active' : ''}} {{request()->routeIs('categoria.create') ? 'active' : ''}} {{request()->routeIs('categoria.index') ? 'active' : ''}} {{request()->routeIs('product.edit') ? 'active' : ''}}" ><b id="underline">Produtos</b></a></li>         
        <li><a href="{{route('requestopycos.index')}}" class="{{request()->routeIs('requestopycos.index') ? 'active' : ''}} {{request()->routeIs('requestopycos.create') ? 'active' : ''}} {{request()->routeIs('requestopycos.edit') ? 'active' : ''}}"><b id="underline">Requisições</b></a></li>
       
      
         <!--<li><a href="{{route('product.index')}}"><b id="underline">Lista de Produtos</b></a></li>-->
        <!--<li><a href="{{route('categoria.index')}}"><b id="underline">Lista de Categorias</b></a></li>-->

      </ul>
    </li>
    


   <li class="dropdown" style="margin-top: 8px;">
      <a id= "dropdown-item" class="dropdown-toggle {{request()->routeIs('clientes.index') ? 'active' : ''}} {{request()->routeIs('vendedores.index') ? 'active' : ''}} {{request()->routeIs('admins.index') ? 'active' : ''}} {{request()->routeIs('clientes.create') ? 'active' : ''}} {{request()->routeIs('vendedores.create') ? 'active' : ''}} {{request()->routeIs('admins.create') ? 'active' : ''}} {{request()->routeIs('clientes.edit') ? 'active' : ''}} {{request()->routeIs('vendedores.edit') ? 'active' : ''}} {{request()->routeIs('admins.edit') ? 'active' : ''}}" data-toggle="dropdown" 
      href="" style="text-decoration: none;" >
        <!--<span class="glyphicon glyphicon-send"></span>-->
      <span><b id="underline">Usuários</b></span>
      <span class="caret"></span></a>
      <ul class="dropdown-menu" style="margin-top: 7px;"> 
        
        <!--<li><a href="{{route('list.users')}}"><b id="underline">Perfis:</b></a></li>-->
        <li><a href="{{route('clientes.index')}}" class="{{request()->routeIs('clientes.index') ? 'active' : ''}} {{request()->routeIs('clientes.create') ? 'active' : ''}} {{request()->routeIs('clientes.edit') ? 'active' : ''}}"><b id="underline">Clientes</b></a></li>
         <li><a href="{{route('vendedores.index')}}" class="{{request()->routeIs('vendedores.index') ? 'active' : ''}} {{request()->routeIs('vendedores.create') ? 'active' : ''}} {{request()->routeIs('vendedores.edit') ? 'active' : ''}}"><b id="underline">Vendedores</b></a></li>
     <li><a href="{{route('admins.index')}}" class="{{request()->routeIs('admins.index') ? 'active' : ''}} {{request()->routeIs('admins.create') ? 'active' : ''}} {{request()->routeIs('admins.edit') ? 'active' : ''}}"><b  id="underline">Administradores</b></a></li>
     <!--<li><a href="{{route('admins.create')}}"><b id="underline">Novo Adm</b></a></li>-->
    
     <!--<li><a href=""><b id="underline">Novo Vendedor</b></a></li>-->
 
      <!--<li><a href="{{route('clientes.create')}}"><b id="underline">Novo Cliente</b></a></li>-->
   

       <!-- <li><a href="{{route('new.users')}}"><b id="underline">Usuários</b></a></li>

      <li><a href="{{route('product.create')}}"><b id="underline"> Produto</b></a></li> -->    
     
      </ul>
    </li>


    <!--<li class="dropdown" style="margin-top: 8px;">
      <a id= "dropdown-item" class="dropdown-toggle" data-toggle="dropdown" 
      href="#" style="text-decoration: none;">
      <span class="glyphicon glyphicon-search"></span> 
      <span><b id="underline">Consultas</b></span>
      <span class="caret"></span></a>
      <ul class="dropdown-menu" style="margin-top: 7px;">
        <li><a href="{{route('pedido.allcompras')}}"><b id="underline">Pedidos</b></a></li>

        <li><a href="{{route('list.users')}}"><b id="underline"> Usuários</b></a></li>
      
       
        

           
      </ul>
    </li>-->
        <li style="margin-top: 8px;"><a href="{{route('pedido.comissoes')}}" style="text-decoration: none;" class="{{request()->routeIs('pedido.comissoes') ? 'active' : ''}}">  <!--<span class="glyphicon glyphicon-briefcase"></span>--><span><b id="underline">Comissões</b></span></a></li>

    <li style="margin-top: 8px;"><a href="{{route('cliente.linkWhatsapp')}}" style="text-decoration: none;" class="{{request()->routeIs('cliente.linkWhatsapp') ? 'active' : ''}}">  <!--<img src="{{URL::asset('img/WhatsApp.png')}}" width="20" height="20" style="margin-top: -10px;">--> <span><b id="underline">Whatsapp</b></span></a></li>

  </ul>


  <ul class="nav navbar-nav navbar-right" id="link-white">
    <li class="dropdown" style="margin-top: 8px;">
  
      <a id= "dropdown-item" href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" style="text-decoration: none;"><span class="glyphicon glyphicon-user"></span>
       <span id="underline"><b id="underline">@php $str = Auth::user()->name; $pieces = explode(" ", $str); echo $pieces[0];@endphp</b></span>
        <span class="caret"></span></a>

      <ul class="dropdown-menu" style="margin-top: 7px;">
        <!--<li><a href="{{route('pedido.compras')}}"><span class=" glyphicon glyphicon-inbox"></span><b id="underline"> Meus Pedidos</b></a></li>-->
        
        <li><a href="{{route('index')}}"><span class=" glyphicon glyphicon-plus-sign"></span><b id="underline"> Novo Pedido</b></a></li>
      
         <li><a href="{{ route('listCadastro') }}" style="text-decoration: none;"><span class="glyphicon glyphicon-cog"></span><span id="underline"><b> Cadastro</b></span></a></li>
        <li>
         <a href="{{ route('logout') }}" onclick="event.preventDefault();
         document.getElementById('logout-form').submit();"><span class="glyphicon glyphicon-log-out"></span>  <b id="underline">Logout</b>
       </a>
       <form id="logout-form" action="{{ url('/admin/logout') }}" method="GET" style="display: none;">
        {{ csrf_field() }}
      </form>
    </li>
        
  

  </ul>                      
</li>

   </ul>

    @endif
 </div> 
     </div>
</nav>

@yield('content')


<!--<script src="{{ asset('js/app.js') }}"></script>-->