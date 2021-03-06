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

      <h2><b>Novo Vendedor</b></h2>    

      <div class="row">
        <div class="col-md-12">
          <ol class="breadcrumb" style="margin-bottom: 10px;">                          
            <li><a href="{{route('vendedores.index')}}" id="btn" style="text-decoration: none"><b>Vendedores</b></a></li>
            <li class="active"><b>Cadastro</b></li>
          </ol>              
          <div class="card-panel">
            <div class="row">           
              <form method="post" 
              action="{{ route('vendedores.store') }}" 
              enctype="multipart/form-data">
              {{ csrf_field() }}
              <div class="col-md-4"><br>
                <div id="tipo1">
                  <div class="input-field">
                    <input class="form-control" type="text" pattern="\d{3}\.\d{3}\.\d{3}-\d{2}" minlength="14" title="Nº CPF no formato 000.000.000-00" name="cpf" onkeypress='mascara(this,mcpf)' maxlength="14" size="8" autofocus required />
                    <label for="grup_categ_cod" style="font-size: 15px;">Cpf</label>
                  </div>
                </div> 

                <div id="tipo2" style="display:none;">
                  <div class="input-field">

                    <input class="form-control" type="text" pattern="\d{2}\.\d{3}\.\d{3}/\d{4}-\d{2}" minlength="18" title="Nº CNPJ no formato 00.000.000/0000-00" name="cnpj" onkeypress='mascaraMutuario(this,Cnpj)' maxlength="18" size="8"/>
                    <label for="grup_categ_cod" style="font-size: 15px;">Cnpj</label>
                  </div>
                </div>
                <label>
                  <input type="radio" name="tipo" value="1" onclick="alterna(this.value);" checked/>
                  <span style="font-size: 15px;">Pessoa Fisica:&nbsp;</span>
                </label>
                <label>
                 <input type="radio" name="tipo" value="2" onclick="alterna(this.value);" />
                 <span style="font-size: 15px;">Pessoa Juridica:&nbsp;</span> 
               </label>

               <div class="input-field">
                <input id="nome" onkeypress='mascara( this, soLetras );' type="text" title="Campo Obrigatório" maxlength="64" name="name" value="{{ old('name') }}" required>
                <label for="nome" style="font-size: 15px;">Nome</label>     
              </div><br>
             
    <div class="input-field">        
                <input class="form-control" pattern="\([0-9]{2}\) [0-9]{4,6}-[0-9]{3,4}$" minlength="15" type="tel" title="(00)00000-0000 'Campo obrigatário'" onkeypress='mascara( this, mtel );' maxlength="15" size="8" name="cel" required />    <label for="cel" style="font-size: 15px;">ddd + Celular</label>
              </div> 
            <br>
              <div class="input-field">        
                <input class="form-control" title="Campo Obrigatário Telefone" onkeypress='mascara( this, mtel );' type="tel"  pattern="\([0-9]{2}\) [0-9]{4,6}-[0-9]{3,4}$" minlength="14" maxlength="14" size="8" name="tel" required />
                <label for="tel" style="font-size: 15px;">ddd + Telefone</label>
              </div>

           
            </div>
            <br>



<div class="col-md-4"><br><br>
<blockquote>
Dados de acesso:
</blockquote>  


      <div class="input-field">
                <input id="email" class="form-control" type="email" title="opycos@opycos.com.br 'Campo obrigatário'" name="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" value="{{ old('email') }}" required autofocus > 
                <label for="email" style="font-size: 15px;">Email</label>
              </div><br>
      <div class="input-field">
      <input class="form-control" type="password"  name="password" pattern="[a-zA-Z0-9]{6,8}" title="Senha no mínimo 6 dígitos" required autocomplete="off" autofocus> 
      <label for="email" style="font-size: 15px;">Senha</label>
    </div><br>
    
    <div class="input-field">
      <input class="form-control" type="password"  name="password_confirmation" title="Repita a Senha" pattern="[a-zA-Z0-9]{6,8}" required autocomplete="off" autofocus/>
      <label for="email" style="font-size: 15px;">Repita Senha</label>
   </div>
 </div>
</div>

    <button type="reset" class="btn btn-default">
      <b>Limpar</b>
    </button>
    
    <button type="submit" 
    class="btn waves-effect waves-light  blue darken-2">
    <b>Cadastrar</b>
  </button>
</form>
   </div>
</div>
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

