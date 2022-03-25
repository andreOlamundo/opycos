
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
       
    <li style="margin-top: 8px;">
      <a href="#" style="text-decoration: none">
        <span class="glyphicon glyphicon-inbox"></span> 
        <span id="underline"><b>Pedidos</b></span>
      </a>
    </li>

 @else
        <li class="dropdown" style="margin-top: 8px;">
      <a id= "dropdown-item" class="dropdown-toggle" data-toggle="dropdown" 
      href="#" style="text-decoration: none">
       <span class="glyphicon glyphicon-send"></span>
      <span id="underline"><b>Contato</b></span>
      <span class="caret"></span></a>
      <ul class="dropdown-menu" style="margin-top: 6px;">                           
        <li><a href="{{route('contato.vendedores', Auth::user()->vendedor_id )}}"><span id="underline"><b> Comercial</b></span></a></li>   
      </ul>
    </li>
  </ul>
  <ul class="nav navbar-nav navbar-right" id="link-white">
    <li class="dropdown" style="margin-top: 8px;">
 
      <a id= "dropdown-item" href="#" class="dropdown-toggle"  data-toggle="dropdown" role="button" aria-expanded="false" style="text-decoration: none;" >
        <b>Olá,</b>
        <span id="underline"><b> @php $str = Auth::user()->name; $pieces = explode(" ", $str); echo $pieces[0];@endphp</b></span>
        <span class="caret"></span></a>

        <ul class="dropdown-menu"  style="margin-top: 6px;">
          <li><a href="{{ route('pedido.clientecompras') }}" style="text-decoration: none;"><span class=" glyphicon glyphicon-inbox"></span><span id="underline"> <b>Meu Pedido</b></span></a></li>
           <li><a href="{{ route('clienteindex') }}" style="text-decoration: none;"><span class="glyphicon glyphicon-cog"></span><span id="underline"> <b>Cadastro</b></span></a></li>

       
        <li>
          <a href="{{ route('logout') }}" onclick="event.preventDefault();
          document.getElementById('logout-form').submit();" style="text-decoration: none;"><span class="glyphicon glyphicon-log-out"></span><span id="underline"> <b>Logout</b></span>
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
 

 
