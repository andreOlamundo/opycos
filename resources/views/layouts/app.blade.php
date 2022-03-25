<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <title>PedidosOpycos</title>
    <meta name="description" content="PROJETO Pedidos Opycos">
    <meta name="author">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">       
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css">     
    <script type='text/javascript' src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> 
    <script type='text/javascript' src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</head>

<body><!--style="background-color: #fff8e6 !important;"-->

    <div id="app">
    <div class="container">
        <div class="row">          
            <section id="conteudo-view">
                @yield('content')
            </section>      
    </div>
</div>
</div>

<!--<script src="{{ asset('js/app.js') }}"></script>-->

</body>
<!--<div class="footer text-center">
    <footer>
        <span class="glyphicon glyphicon-chevron-up"></span>  
        <p>&copy; 2019 Opycos., LTD. &middot; <a href="#">Privacy</a> &middot; <a href="#">Terms</a></p>
    </footer>
</div>-->
</html>
