@extends('templates.admin-login')

@section('css-view')
@endsection

@section('js-view')
@endsection


@section('templates.menu-superior-admin')
@endsection

@section('conteudo-view')
<div id="line-one">
  <div id="line-one">
    <div class="container">
         <div class="row" style="height: 50px; width: 1170px; position: fixed; background-color: white; z-index: 1001; top: 50px; margin-bottom: 70px;">
        <div class="col-md-12">
          <h2>Alterar Administrator</h2>     
        </div>
      </div>  
   
        <div class="row" style="height: 50px; width: 1170px; position: fixed; z-index: 1001; top: 100px; "> 
        <div class="col-md-12">
          <!--<ol class="breadcrumb" style="margin-bottom: 5px;">  <li><a href="{{route('admins.index')}}" id="btn" style="text-decoration: none"><b>Administradores</b></a></li>                           
            <li><a href="{{route('admins.create')}}" id="btn" style="text-decoration: none"><b>Cadastro</b></a></li>  
                  
            <li class="active">Alteração</li>
          </ol>-->         
             @if (session('mensagem-falha'))
                  <div class="alert alert-danger alert-dismissible fade in" style="margin-bottom: 1px;">

                    <a href="#" class="close" 
                    data-dismiss="alert"
                    aria-label="close">&times;</a>
                    <b>{{ session('mensagem-falha') }}</b>
                  </div>
                  <script type="text/javascript">
                    $(".alert-dismissible").fadeTo(7000, 500).slideUp(500, function(){
                      $(".alert-dismissible").alert('close');
                    });
                  </script>
                  @endif   
                   @if (session('message-failure'))
          <div class="alert alert-danger alert-dismissible fade in" style="margin-bottom: 1px;">
            <a href="#" class="close" 
            data-dismiss="alert"
            aria-label="close">&times;</a>
            <b> {{ session('message-failure') }}</b>
          </div>
          <script type="text/javascript">
            $(".alert-dismissible").fadeTo(7000, 500).slideUp(500, function(){
              $(".alert-dismissible").alert('close');
            });
          </script>
          @endif
      


          <form method="post" 
          action="{{route('admins.update', $admins->id)}}"
          enctype="multipart/form-data">
          {!! method_field('put') !!}
          {{ csrf_field() }}
          <div class="card-panel">    
          <div class="row">     
            <div class="col-md-5">      
              <div class="input-field">                                   
               <input id="name" onkeypress='mascara( this, soLetras );' type="text" placeholder="Nome do usúario" title="'Campo Obrigatário' Nome do usúario" maxlength="64" name="name" value="{{$admins->name or old('name')}}" required>
               <label for="name" style="font-size: 15px;">Nome</label>
             </div>

             <div class="input-field">
              <input id="email" type="email" placeholder="Email:" title="'Campo Obrigatário' E-mail do usuário" name="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" value="{{$admins->email or old('email')}}" required > 
              <label for="email" style="font-size: 15px;" >E-mail</label>
            </div>

            <div class="input-field">         
              <input id="tel" placeholder="Telefone:" title="Telefone" onkeypress='mascara( this, mtel );' type="tel"  pattern="\([0-9]{2}\) [0-9]{4,6}-[0-9]{3,4}$" minlength="14" maxlength="14" name="tel" value="{{$admins->tel or old('tel')}}"/>
              <label for="tel" style="font-size: 15px;">Telefone</label>
            </div>

            <div class="input-field">

              <input id="cel" pattern="\([0-9]{2}\) [0-9]{4,6}-[0-9]{3,4}$" minlength="15" type="tel" title="'Campo Obrigatorio' Celular" placeholder="Celular" onkeypress='mascara( this, mcel );' maxlength="15" name="cel" value="{{$admins->cel or old('cel')}}"/>    
              <label for="cel" style="font-size: 15px;">Celular</label>
            </div>
          </div>  
          <div class="col-md-5"> 

        <div class="input-field">
          <input type="password" name="password" pattern="^(?=.*\d)(?=.*[a-z])(?!.*\s).*$" title="Senha com no mínimo seis caracteres Alfanuméricos (Letras e números)" minlength="6" placeholder="Senha de acesso" autocomplete="off"> 
          <label for="password" style="font-size: 15px;">Senha</label>
        </div>

        <div class="input-field">
          <input type="password" name="password_confirmation" title="Repita a Senha" pattern="^(?=.*\d)(?=.*[a-z])(?!.*\s).*$" minlength="6" autocomplete="off" placeholder="Repita a Senha"/>
          <label for="password_confirmation" style="font-size: 15px;">Repita Senha</label>
        </div>
          </div>  
</div>
          <a href="{{route('admins.index')}}" class="btn btn-default" style="margin-top: -20px; width: 130px; height: 25px; padding: 2px 1px; ">
          <b>Voltar</b>
        </a>
          <button type="submit" class="btn waves-effect waves-light  blue darken-2" style="margin-top: -20px; width: 130px; height: 25px; padding: 2px 1px; "><span class="glyphicon glyphicon-floppy-disk"></span>
          <b>Salvar</b>
        </button>
      </div>

    </form> 
  </div>
</div>

</div>
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