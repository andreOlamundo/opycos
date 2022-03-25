@extends('layouts.app')

@section('content')
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">    
      <div class="navbar-header">
          <a class="navbar-brand" href="http://www.opycos.com.br/" target="_blank">
            <img src="{{ asset('/img/logo-opycos.png') }}" class="img-responsive"  width="140" height="26" alt="Opycos" title="Opycos"></a>
            <button type="button" class="navbar-toggle" 
            data-toggle="collapse" data-target="#myNavbar">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>               
    </div>
</div>
</nav>

<br>
<br>
<div class="row"> 
  <div class="container"> 
   <div class="col-md-12"> 


    <div class="panel-heading">
     <!-- <a href="#"><img src="{{ asset('/img/71a113banne-contato.jpg') }}" class="img-responsive"  width="120" height="26" alt="Opycos" title="Opycos"></a>-->

 </div>
 <br><br>
 <div class="card hoverable">
<div class="card-image">
          <img src="{{ asset('/img/71a113banne-contato.jpg') }}" class="img-responsive" alt="Opycos" title="Opycos">
          <span class="card-title">Pedidos Opycos </span>
         
       </div>

    <div class="panel-body">
        <form method="POST" action="{{ url('/admin/login') }}">
            {{ csrf_field() }}
            <div class="col-md-8 col-md-offset-2">                           
             <div class="input-field{{ $errors->has('email') ? ' has-error' : '' }}">
                <i class="material-icons prefix">account_circle</i>
                <input id="email" type="email" class="validate" name="email" value="{{ old('email') }}" required autofocus>
                <label for="email" class="control-label">Endereço de E-Mail</label>

                @if ($errors->has('email'))
                <span class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
                @endif
            </div>

            <div class="input-field {{ $errors->has('password') ? ' has-error' : '' }}">
                <i class="material-icons prefix">lock_outline</i>

                <input id="password" type="password" class="validate" name="password" required>
                <label for="password">Senha</label>

                @if ($errors->has('password'))
                <span class="help-block">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
                @endif
            </div>

            <div class="input-field pull-right">
                <button type="submit" class="btn btn-primary">
                    Login
                </button>
            </div>
            <div class="input-field pull-left"><br>
                <a style="color: #26a69a;" href="{{ route('password.request') }}">             
                    Esqueceu sua senha?
                </a>
            </div>

                   <div class="input-field" style="display: none;">                            
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox"  checked="checked" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <span>Lembrar credênciais</span>
                                    </label>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>

            </div>

        </div>
    </div>
</div>

<footer class="fixar-rodape" style="color: #743C2f;">
 <!--<footer class="fixar-rodape" style="color: #743C2f; background-image: url('/img/71a113banne-contato.jpg') ">-->
    <div class="text-center">



        <p><b>&copy; Opycos <?php echo date("Y"); ?>. <!--<a href="#">Privacy</a> &middot; <a href="#">Terms</a>--></b></p>
    </div>
</footer>





@endsection
