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
  <ul class="nav navbar-nav" id="link-white">
     @if (Auth::guest())    

     @else  
       <!--<li class="dropdown" style="margin-top: 8px;">
      <a id= "dropdown-item" class="dropdown-toggle" data-toggle="dropdown" 
      href="#" style="text-decoration: none;" > 
      <span><b id="underline">Pedidos</b></span>
      <span class="caret"></span></a>
      <ul class="dropdown-menu" style="margin-top: 7px;">
        <li><a href="{{route('indexint')}}"><b id="underline">Novo Pedido</b></a></li>
        <li><a href="{{route('pedidointer.compras')}}"><b id="underline">Lista de Pedidos</b></a></li>
      </ul>
    </li>-->

        <li style="margin-top: 8px;"><a href="{{route('pedidointer.compras')}}" style="text-decoration: none;">
       <!--<span class="glyphicon glyphicon-send"></span>-->
        <span><b id="underline">Pedidos</b></span></a>
      </li>

           <li style="margin-top: 8px;"><a href="{{route('pedidoint.comissoes')}}" style="text-decoration: none;">
        <!--<span class="glyphicon glyphicon-th-list"></span>-->
        <span><b id="underline">Comissões</b></span></a>
      </li>

     <li style="margin-top: 8px;"><a href="{{route('clientesinter.index')}}" style="text-decoration: none;">
       <!--<span class="glyphicon glyphicon-send"></span>-->
        <span><b id="underline">Clientes</b></span></a>
      </li>

           <li style="margin-top: 8px;"><a href="{{route('requestopycosint.index')}}" style="text-decoration: none;">
        <!--<span class="glyphicon glyphicon-th-list"></span>-->
        <span><b id="underline">Requisições</b></span></a>
      </li>
   <!-- <li class="dropdown" style="margin-top: 8px;">
      <a id= "dropdown-item" class="dropdown-toggle" data-toggle="dropdown" 
      href="#">
      <span class="glyphicon glyphicon-th"></span> 
      <span id="underline">Consultas</span>
      <span class="caret"></span></a>
      <ul class="dropdown-menu" style="margin-top: 7px;">                           
        <li><a href="{{route('productinter.index')}}">Produtos</a></li>
     
        <li><a href="{{route('clientesinter.index')}}">Clientes</a></li>    
      </ul>
    </li>-->
        <li style="margin-top: 8px;">
          <a href="{{route('cliente.linkInterWhatsapp')}}" style="text-decoration: none;"><!--<img src="{{URL::asset('img/WhatsApp.png')}}" width="20" height="20" style="margin-top: -10px;">--><span id="underline"><b>Whatsapp</b></span></a></li>
  </ul>
  <ul class="nav navbar-nav navbar-right" id="link-white">
    <li class="dropdown" style="margin-top: 8px;">          
      <a id= "dropdown-item" href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" style="text-decoration: none;" >
        <span class="glyphicon glyphicon-user">    </span>
        <span id="underline"><b> @php $str = Auth::user()->name; $pieces = explode(" ", $str); echo $pieces[0];@endphp</b></span> 
        <span class="caret"></span></a>

            <ul class="dropdown-menu" style="margin-top: 7px;">
               <li><a href="{{route('pedidointer.compras')}}" style="text-decoration: none;"><span class=" glyphicon glyphicon-inbox"></span><span id="underline"><b> Meus Pedidos</b></span></a></li>
           <li><a href="{{ route('listCadastroVend') }}" style="text-decoration: none;"><span class="glyphicon glyphicon-cog"></span><span id="underline"><b> Cadastro</b></span></a></li>
        <li>
          <a href="{{ route('logout') }}" onclick="event.preventDefault();
          document.getElementById('logout-form').submit();" style="text-decoration: none;">
          <span class="glyphicon glyphicon-log-out"></span><span id="underline">
          <b>Logout</b></span>
        </a>
        <form id="logout-form" action="{{ url('/admin/logout') }}" method="GET" style="display: none;">
          {{ csrf_field() }}
        </form>
      </li>

    </ul>         
  </li>
    @endif
 
   </ul>
 </div>
 </div>       
</nav>
@yield('content')