@extends('layouts.app')

@section('content')
<br>
<br>
 <div class="row"> 
 <div class="col-md-12"> 
            <div class="panel panel-warning">
                <div class="panel-heading"> <a href="#"><img src="{{ asset('/img/logo-opycos.png') }}" class="img-responsive"  width="120" height="26" alt="Opycos" title="Opycos"></a>
                 </div>
                <div class="panel-body">
                    <form method="POST" action="{{ url('/admin/login') }}">
                        {{ csrf_field() }}
                        <div class="col-md-6">                           
                           <div class="input-field {{ $errors->has('email') ? ' has-error' : '' }}">
                                <i class="material-icons prefix">account_circle</i>
                                <input id="email" type="email" class="validate" name="email" value="{{ old('email') }}" required autofocus>
                                <label for="email">Endereço de E-Mail</label>

                                @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
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
                        </div>
                        <div class="col-md-6 col-md-offset-4">
                        <div class="input-field">
                            
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <span>Lembrar credênciais</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        
                            <div class="col-md-6 col-md-offset-4">
                            	<div class="input-field">
                                <button type="submit" class="btn btn-primary">
                                    Login
                                </button>

                                <a class="btn btn-light btn-small" style="margin-top: 3px;" href="{{ route('password.request') }}">                     
                                    Esqueceu sua senha?
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
        
   <footer class="fixar-rodape" style="color: #743C2f;">
        <div class="text-center">
        <span class="glyphicon glyphicon-chevron-up"></span>
 
        <p><b>&copy; Opycos <?php echo date("Y"); ?>. <a href="#">Privacy</a> &middot; <a href="#">Terms</a></b></p>
    </div>
    </footer>





@endsection
