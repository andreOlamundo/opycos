<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
	<title>PedidosOpycos</title>
 <meta name="description" content="PROJETO Pedidos Opycos">
 <meta name="author" content="ProjetoOpycos">
 <meta charset="utf-8">
 <meta name="viewport" content="width=device-width, initial-scale=1">
 <meta http-equiv="X-UA-Compatible" content="IE=edge">
 <!-- Favicon -->
 <link href="img/favicon.png" rel="icon" >

 <!-- Fonts --> 
 <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
 <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css">

 <!-- Styles -->
 <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
 <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet"/>
 <!--<link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">-->
 <link href="{{URL::asset('css/lightbox.css')}}" rel="stylesheet" type="text/css" />
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


 <!-- JavaScript -->  
 <script type='text/javascript' src='http://code.jquery.com/jquery-1.5.1.min.js'></script>  
 <script type='text/javascript' src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
 <script type='text/javascript' src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
 <script src="{{URL::asset('js/ajax.js')}}"></script>
 <script src="{{URL::asset('js/lightbox.js')}}"></script>

 @yield('js-view')
 @yield('css-view')

</head>
<body id="myPage" data-spy="scroll" data-target=".navbar" data-offset="60">




  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <br>
        <section id="conteudo-view">
          @yield('conteudo-view')
        </section>

      </div>
    </div>
  </div>
  

</body>
</html>