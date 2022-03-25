@extends('templates.vendedor-login')

@section('css-view')
@endsection

@section('js-view')
@endsection


@section('templates.menu-superior-vendedor')
@endsection

@section('conteudo-view')
<div id="line-one">
  <div id="line-one">
    <div class="container">
                <h2><b>Alteração do Vendedor</b></h2>             
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
                
                    <div class="input-fild">                                   
                     <input id="name" onkeypress='mascara( this, soLetras );' type="text" placeholder="Nome do vendedor" title="'Campo Obrigatário' Nome do vendedor" maxlength="64" name="name" value="{{$vendedores->name or old('name')}}" required>
                     <label for="name" style="font-size: 15px;">Nome</label>
                 </div>
             <br>

                     <div class="input-field">
         
          <input id="email" type="email" placeholder="Email:" title="Campo Obrigatário E-mail corporativo" name="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" value="{{$vendedores->email or old('email')}}" required > 
          <label for="email" style="font-size: 15px;" >E-mail</label>

        </div>
        <br>

        <div class="input-field">         
          <input id="tel" placeholder="Telefone:" title="Telefone" onkeypress='mascara( this, mtel );' type="tel"  pattern="\([0-9]{2}\) [0-9]{4,6}-[0-9]{3,4}$" minlength="14" maxlength="14" name="tel" value="{{$vendedores->tel or old('tel')}}"/>
          <label for="tel" style="font-size: 15px;">Telefone</label>
        </div><br>

        <div class="input-field">
          
          <input id="cel" pattern="\([0-9]{2}\) [0-9]{4,6}-[0-9]{3,4}$" minlength="15" type="tel" title="'Campo Obrigatorio' Celular" placeholder="Celular" onkeypress='mascara( this, mcel );' maxlength="15" name="cel" value="{{$vendedores->cel or old('cel')}}" required />    
          <label for="cel" style="font-size: 15px;">Celular</label>
        </div><br>
      </div>

      <div class="col-md-6">
       <div class="input-field">      
        <input id="endereço" type="text"  placeholder="Endereço" title="Endereço" maxlength="191" name="endereço" value="{{$vendedores->endereço or old('endereço')}}"/>
        <label for="endereço"  style="font-size: 15px;">Endereço</label>
      </div><br>
    </div>


    <div class="col-md-4">
     <div class="input-field">
      <span class="input-group-addon">CEP:</span>
      <input id="login_input_ramal" type="tel" pattern="[0-9]{5}-[0-9]{3}" placeholder="Cep" title="CEP 00000-000" onkeypress='mascara( this, mcep );' maxlength="9" name="cep" value="{{$vendedores->cep or old('cep')}}"/>
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