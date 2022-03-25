@extends('layouts.app')

@section('content')
<br>
<br>
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

                       
                            

                          

                             <input id="password" type="hidden" class="form-control" name="password" value="Opycos123" >
                              

                           
                       
                            <div class="col-md-6 col-md-offset-4">
                                <div class="input-field">
                                <button type="submit" class="btn btn-primary">
                                    Login
                                </button>

                              
                            </div>
                        </div>
                

                        
                
                    </form>
                </div>
            </div>
        </div>





@endsection

                           
                                                                       



                    
