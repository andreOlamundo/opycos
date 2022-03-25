@extends('layouts.app')

@section('content')
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">    
      <div class="navbar-header">
          <a class="navbar-brand" href="#">
            <img src="{{ asset('/img/logo-opycos.png') }}" class="img-responsive"  width="170" height="26" alt="Opycos" title="Opycos"></a>
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
   <div class="col-md-12">  <br><br>
          
                <div class="panel-heading"></div>
                <div class="card hoverable">
<div class="card-image">
          <img src="/img/71a113banne-contato.jpg">
          <span class="card-title">Redefinição de senha </span>
         
       </div>


                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form class="form-horizontal" method="POST" action="{{ route('password.email') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">Endereço de E-mail</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-small btn-primary">
                                    Enviar link de redefinição de senha
                                </button>


                            </div>
                        </div>
                    </form>
           <a href="{{ url('/admin/login') }}" style="color: #26a69a;"><i class="material-icons">arrow_back</i>Voltar</a>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
