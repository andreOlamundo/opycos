@extends('templates.admin-login')

@section('css-view')
@endsection

@section('js-view')
@endsection


@section('templates.menu-superior-admin')
@endsection

@section('conteudo-view')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">             
                <h2><b>Alteração do Vendedor</b></h2>
                <hr>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <ol class="breadcrumb">                               
                    <li><a href="{{route('vendedores.index')}}">Vendedores</a></li>
                    <li><a href="{{route('vendedores.create')}}">Cadastro</a></li>        
                    <li class="active">Alteração</li>
                </ol>              
            </div>          
        </div>
        <div class="row">  
            
            <form method="post" 
            action="{{route('vendedores.update', $vendedores->id)}}"
            enctype="multipart/form-data">
            {!! method_field('put') !!}
            {{ csrf_field() }}
            <div class="col-md-5">              
                <div class="form-group">
                    <div class="input-group">
                     <span class="input-group-addon">Nome</span>                   
                     <input id="name" onkeypress='mascara( this, soLetras );' class="form-control" type="text" placeholder="Digite aqui: " title="Campo Obrigatário Nome" maxlength="64" name="name" value="{{$vendedores->name or old('name')}}" required autofocus>
                 </div>
             </div><br>

                     <div class="input-group">
          <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
          <input id="email" class="form-control" type="email" placeholder="Email:" title="Campo Obrigatário E-mail corporativo" name="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" value="{{$vendedores->email or old('email')}}" required > 

        </div>
        <br>

        <div class="input-group">
          <span class="input-group-addon"><i class="glyphicon glyphicon-earphone"></i></span>
          <input id="login_input_ramal" class="form-control" placeholder="Telefone:" title="Campo Obrigatário Telefone" onkeypress='mascara( this, mtel );' type="tel"  pattern="\([0-9]{2}\) [0-9]{4,6}-[0-9]{3,4}$" minlength="14" maxlength="14" size="8" name="tel" value="{{$vendedores->tel or old('tel')}}" required />
        </div><br>

        <div class="input-group">
          <span class="input-group-addon"><i class="glyphicon glyphicon-phone"></i></span>
          <input id="login_input_ramal" class="form-control" pattern="\([0-9]{2}\) [0-9]{4,6}-[0-9]{3,4}$" minlength="15" type="tel" title="Campo Obrigatário Celular" placeholder="Celular:" onkeypress='mascara( this, mtel );' maxlength="15" size="8" name="cel" value="{{$vendedores->cel or old('cel')}}" required />    
        </div><br>


        <div class="input-group">
          <span class="input-group-addon"><i class="glyphicon glyphicon-briefcase"></i></span>
          <input type="text" class="form-control" onkeypress='mascara( this, soLetras );' placeholder="Profissão" title="Profissão:" name="profissao" value="{{$vendedores->profissao or old('profissao')}}">


        </div><br>

        <div class="input-group">
          <span class="input-group-addon">Registro Profíssional</span>
          <input id="login_input_ramal" class="form-control" type="text"  placeholder="Digite aqui:" title="Nº Registro" onkeypress='mascara( this, mnum );' pattern="[0-9]+$" maxlength="10" size="2"  name="num_registro" value="{{$vendedores->num_registro or old('num_registro')}}" />
        </div> 
        <br>

      </div>

      <div class="col-md-6">
       <div class="input-group">
        <span class="input-group-addon">Endereço:</span>
        <input id="login_input_ramal" class="form-control" type="text"  placeholder="Digite aqui:" title="Endereço" maxlength="191" size="8" name="endereço" value="{{$vendedores->endereço or old('endereço')}}" required/>
      </div><br>
    </div>


    <div class="col-md-4">
     <div class="input-group">
      <span class="input-group-addon">CEP:</span>
      <input id="login_input_ramal" class="form-control" type="tel" pattern="[0-9]{5}-[0-9]{3}" placeholder="Digite aqui:" title="Nº CEP 00000-000" onkeypress='mascara( this, mcep );' maxlength="9" size="8" name="cep" value="{{$vendedores->cep or old('cep')}}" required />
    </div><br>
   


         </div>

                     
                           
            <button type="reset" class="btn btn-default">
                <b>Restaurar</b>
            </button>
            <button type="submit" 
            class="btn btn-warning">
            <b>Alterar</b>
        </button>
   
</form>             
</div>
</div>

<script>
  function alterna(tipo) {

    if (tipo == 1) {
      document.getElementById("tipo1").style.display = "block";
      document.getElementById("tipo2").style.display = "none";
    } else {
      document.getElementById("tipo1").style.display = "none";
      document.getElementById("tipo2").style.display = "block";
    }

  }
</script>
@endsection