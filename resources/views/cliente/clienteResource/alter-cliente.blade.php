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
                
                <h2><b>Alterar Cliente</b></h2>
            
   
        <div class="row">
            <div class="col-md-12">
                <ol class="breadcrumb">                               
                    <li><a href="{{route('clientes.index')}}" id="btn" style="text-decoration: none"><b>Clientes</b></a></li>
                    <li><a href="{{route('clientes.create')}}" id="btn" style="text-decoration: none"><b>Cadastro</b></a></li>        
                    <li class="active"><b>Alteração</b></li>
                </ol>              
     
        
          <form method="post" 
            action="{{route('clientes.update', $clientes->id)}}"
            enctype="multipart/form-data">
            {!! method_field('put') !!}
            {{ csrf_field() }}
            <div class="card-panel">
             <div class="row"> 
            <div class="col-md-5">              
                <div class="input-field">                
                                      
                     <input id="name" onkeypress='mascara( this, soLetras );' class="form-control" type="text" placeholder="Digite aqui: " title="Campo Obrigatário Nome" maxlength="64" name="name" value="{{$clientes->name or old('name')}}" required autofocus>
                <label for="name">Nome</label>
             </div><br>

                     <div class="input-field">
         
          <input id="email" class="form-control" type="email" placeholder="Email:" title="Campo Obrigatário E-mail corporativo" name="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" value="{{$clientes->email or old('email')}}" required > 
           <label>Email</label>

        </div>
        <br>

        <div class="input-field">
          
          <input id="login_input_ramal" class="form-control" placeholder="Telefone:" title="Campo Obrigatário Telefone" onkeypress='mascara( this, mtel );' type="tel"  pattern="\([0-9]{2}\) [0-9]{4,6}-[0-9]{3,4}$" minlength="14" maxlength="14" size="8" name="tel" value="{{$clientes->tel or old('tel')}}" required />
           <label>Telefone</label>
        </div><br>

        <div class="input-field">
          
          <input id="login_input_ramal" class="form-control" pattern="\([0-9]{2}\) [0-9]{4,6}-[0-9]{3,4}$" minlength="15" type="tel" title="Campo Obrigatário Celular" placeholder="Celular:" onkeypress='mascara( this, mtel );' maxlength="15" size="8" name="cel" value="{{$clientes->cel or old('cel')}}" required />   
           <label>Celular</label>
        </div><br>


   

      </div>

      <div class="col-md-6">
       <div class="input-field">
     
        <input id="login_input_ramal" class="form-control" type="text"  placeholder="Digite aqui:" title="Endereço" maxlength="191" size="8" name="endereço" value="{{$clientes->endereço or old('endereço')}}" required/>
        <label>Endereço:</label>
      </div><br>
    </div>


    <!--<div class="col-md-4">
     <div class="input-field">
    
      <input id="login_input_ramal" class="form-control" type="tel" pattern="[0-9]{5}-[0-9]{3}" placeholder="Digite aqui:" title="Nº CEP 00000-000" onkeypress='mascara( this, mcep );' maxlength="9" size="8" name="cep" value="{{$clientes->cep or old('cep')}}" required />
      <label> CEP:</label>
    </div><br>

    Pessoa Fisica:&nbsp;<input type="radio" name="tipo" value="1" onclick="alterna(this.value);" /><br>

    Pessoa Juridica:&nbsp;<input type="radio" name="tipo" value="2" onclick="alterna(this.value);" /><br>

<br>
    <div id="tipo1">
     <div class="input-group">
      <span class="input-group-addon">CPF</span>
      <input id="login_input_ramal" class="form-control" type="text" pattern="\d{3}\.\d{3}\.\d{3}-\d{2}"  minlength="14" placeholder="Digite aqui:" title="Nº CPF no formato 000.000.000-00" name='cpf' value="{{$clientes->cpf or old('cpf')}}" onkeypress='mascara(this,mcpf)' maxlength="14" size="8" />
    </div>
  </div> 

  <div id="tipo2" style="display:none;">
    <div class="input-group">
      <span class="input-group-addon">CNPJ</span>
      <input id="login_input_ramal" class="form-control" type="text" pattern="\d{2}\.\d{3}\.\d{3}/\d{4}-\d{2}" minlength="18"  placeholder="Digite aqui:" title="Nº CNPJ no formato 00.000.000/0000-00" name='cnpj' value="{{$clientes->cnpj or old('cnpj')}}" onkeypress='mascaraMutuario(this,Cnpj)' maxlength="18" size="8" />
    </div>
  </div> <br>
</div>-->


         </div>


                     
                           
            <button type="reset" class="btn btn-default">
                <b>Restaurar</b>
            </button>
            <button type="submit" 
            class="btn btn-warning">
            <b>Alterar</b>
        </button>
      </div>
   
</form>             
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