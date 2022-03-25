
<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
	<title>PedidosOpycos</title>
	<meta name="description" content="Pedidos Opycos">
	<meta name="author" content="PedidosOpycos Versão 1.3">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{csrf_token()}}">

	<!-- Favicon -->
	<link href="img/favicon.png" rel="icon" >

	<!-- Fonts --> 
	<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css"/>
	<link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css"/>

	
   <!-- Icons Materialize-->
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    

    <!-- Compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
            


	<!-- Styles -->
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/>
	<!--<link href="{{ asset('css/app.css') }}" rel="stylesheet">-->
	<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet"/>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet"/>
	<link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">

	<link href="{{URL::asset('css/lightbox.css')}}" rel="stylesheet" type="text/css" />
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
	



	<!-- JavaScript -->  
	<script type='text/javascript' src='http://code.jquery.com/jquery-1.5.1.min.js'></script>  
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<!--MASCARA DOS FORMULARIOS-->
	<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>-->	

	   <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script><!-- -->
       <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.js"></script>
       <script type="text/javascript">
            $(document).ready(function(){
              var maskBehavior = function (val) {
            return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
          },
          options = {onKeyPress: function(val, e, field, options) {
                  field.mask(maskBehavior.apply({}, arguments), options);
              }
          };

       $('.phone').mask(maskBehavior, options);
          $('.money').mask('000.000.000.000.000,00', {reverse: true}).attr('maxlength','8'); 
          $('.cep').mask('00000-000');
           $('.phone_with_ddd').mask('(00) 0000-0000');
           $('.cpf').mask('000.000.000-00', {reverse: true});
          $('.cnpj').mask('00.000.000/0000-00', {reverse: true});
        // $('.weight').mask("#0.00.000 Kg", {reverse: true}).attr('maxlength','8');
        //$('.weight').mask("#.#0,000 Kg", {reverse: true}).attr('maxlength','8');  

              
              });
          </script>

	
	<script type='text/javascript' src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script src="{{URL::asset('js/ajax.js')}}"></script>
	<script src="{{URL::asset('js/lightbox.js')}}"></script>
	<script src="{{URL::asset('js/formMask.js')}}"></script>

	@yield('js-view')
	@yield('css-view')

</head>
<body id="myPage" data-spy="scroll" data-target=".navbar" data-offset="60">
	@include('templates.menu-superior-admin')
	<!--<div class="body-content">-->
	<section id="conteudo-view">
		@yield('conteudo-view')
	</section>
<!--</div>-->
	@stack('scripts')
	<script type="text/javascript">
		$( document ).ready(function(){
					$('select').material_select();

		});

		

	</script>
	<!--<style type="text/css">
		.footer {
   position: fixed;
   left: 0;
   bottom: 0;
   width: 100%;
   color: #743C2f;   
   text-align: center;
}
	</style>-->
</body>

	<!--<footer class="fixar-rodape">
		<div class="text-center">
		<span class="glyphicon glyphicon-chevron-up"></span>  
		<p>&copy; 2019 Opycos., LTD. &middot; <a href="#">Privacy</a> &middot; <a href="#">Terms</a></p>
	</div>
	</footer>-->

 <footer style="color: #743C2f; text-align: center;"> 
    <b>© Opycos <?php echo date("Y"); ?>.</b>
        </footer>
	
	


</html>
