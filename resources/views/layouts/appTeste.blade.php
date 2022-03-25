<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <title>PedidosOpycos</title>
    <meta name="description" content="PROJETO Pedidos Opycos">
    <meta name="author" content="Projetoviril">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

      
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet"> 
    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">  
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css">
    <script type='text/javascript' src='http://code.jquery.com/jquery-1.5.1.min.js'></script>  
    <script type='text/javascript' src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> 
    <script type='text/javascript' src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script type='text/javascript'>
    // Faz o carregamento do balão informativo: tooltip.
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
</head>

<body id="myPage" data-spy="scroll" data-target=".navbar" data-offset="60">
    <div id="app">
        <nav class="navbar navbar-inverse navbar-fixed-top">

            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle" 
                data-toggle="collapse" data-target="#myNavbar">               
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <!-- Branding Image -->
            <a class="navbar-brand" href="{{ url('/') }}"><img src="{{ asset('/img/logo-opycos.png') }}" class="img-responsive"  width="120" height="26" alt="Opycos" title="Opycos"></a> 

        </div>

        <div class="collapse navbar-collapse" id="myNavbar">
            <!-- Left Side Of Navbar -->
            <ul class="nav navbar-nav" id="link-white">
                <li class="dropdown">
                    <a id= "dropdown-item" class="dropdown-toggle" data-toggle="dropdown" 
                    href="#" style="text-decoration: none">
                    <span class="glyphicon glyphicon-log-in"></span>
                    <span id="underline">Usuários</span>
                    <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="{{url('/admin/login')}}">Administradores</a></li>
                        <!--<li><a data-toggle="modal" data-target="#myModal2">Administrador</a></li>-->
                        <!--<li><iframe src="{{url('/admin/login')}}" style="width: 100% ;height: 900px;"  frameborder = "0" allowfullscreen></iframe>Administradores</li>-->
                        <li class="divider"></li>                 
                        <li><a href="{{url('/vendedor/login')}}">Vendedores</a></li>
                        <li class="divider"></li>
                        <li><a href="{{url('/cliente/login')}}">Clientes</a></li>      
                    </ul>
                </li>    
            </ul>

                                <!-- Right Side Of Navbar 
                    <ul class="nav navbar-nav navbar-right" >
                      ++++ Authentication Links 
                        @if (Auth::guest())
                            <li><a href="{{ route('login') }}">Login</a></li>
                            <li><a href="{{ route('register') }}">Register</a></li>
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>-->
 

    </div>           
</nav>
<div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <br>
        <section id="conteudo-view">
@yield('content')
 </section>

      </div>
    </div>
  </div>
</div>

<!--<script src="{{ asset('js/app.js') }}"></script>-->

</body>
</html>
