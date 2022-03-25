@extends('templates.cliente-login')

@section('css-view')
@endsection

@section('js-view')
@endsection


@section('templates.menu-superior-cliente')
@endsection

@section('conteudo-view')
<div id="line-one">
<div id="line-one">

<div class="container">

  <h2></h2>
            
   
        <div class="row">
            <div class="col-md-12">
    <ul class="collection">
  <li style="margin-left: 8px; margin-top: 8px; margin-bottom: -50px;"><a class="btn-floating btn-medium green pulse"><i class="material-icons">perm_identity</i></a>

  </li>

    <li class="collection-item avatar">

      <span class="title"><b>Primeiro acesso</b></span>
      <p>Seja Bem vindo(a).       
      </p>
      <p><b>Preencha os campos abaixo e conclua seu cadastro.</b>      
      </p>
    </li>
  </ul>

 @if (session('message'))
<div class="alert alert-success alert-dismissible">

  <a href="#" class="close" 
  data-dismiss="alert"
  aria-label="close">&times;</a>
  <b>{{ session('message') }}</b>
</div>
@endif

 @if (Session::has('mensagem-falha'))
    <div class="alert alert-danger alert-dismissible">
      <strong>{{ Session::get('mensagem-falha') }}</strong>
      <a href="#" class="close" 
      data-dismiss="alert"
      aria-label="close">&times;</a>
    </div>
    @endif             
     
         @forelse ($clientes as $cliente)
          <form action="{{route('alter.cliente')}}" method="POST" accept-charset="utf-8">          
            {{ csrf_field() }}
            <div class="card-panel">
             <div class="row"> 
            <div class="col-md-4">              
                
               <!-- <input type="hidden" name="status" value="F">-->
               <div id="tipo1">
                <div class="input-field">
                  <input type="text" pattern="\d{3}\.\d{3}\.\d{3}-\d{2}" minlength="14" title="'Campo obrigatório' 000.000.000-00" name="cpf" onkeypress='mascara(this,mcpf)' value="{{$cliente->cpf or old('cpf') }}" placeholder="Cadastro pessoa física" maxlength="14" autofocus/>
                  <label for="cpf" style="font-size: 15px;">CPF</label>
                </div>
              </div>  

              <div id="tipo2" style="display:none;">
               <div class="input-field">
                <input type="text" pattern="\d{2}\.\d{3}\.\d{3}/\d{4}-\d{2}" minlength="18" title="'Campo obrigatório' 00.000.000/0000-00" name="cnpj" onkeypress='mascaraMutuario(this,Cnpj)' value="{{$cliente->cnpj  or old('cnpj') }}" maxlength="18" placeholder="Cadastro nacional pessoa jurídica" />
                <label for="grup_categ_cod" style="font-size: 15px;">CNPJ</label>
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

              <input type="hidden" name="preview_id" value="{{ $cliente->preview_id or old('preview_id') }}">

              <input onkeypress='mascara( this, soLetras );' type="text" title="'Campo obrigatório' Nome do usuário" maxlength="64" name="name" value="{{$cliente->name or old('name') }}" placeholder="{{$clientes->name or old('name') }}" style="margin-top: 10px;" required>
            <label for="name" style="font-size: 15px; margin-top: 10px;">Nome</label>     
          </div> 

          <div class="input-field">        
            <input type="tel" title="Digite apenas números!.Caracteres especiais incluídos automaticamente. Ex:(71) 8578-3746 - Não é necessario incluir o número '0' Zero, afrente do código de área." placeholder="ddd+ celular" pattern="\([0-9]{2}\) [0-9]{4,5}-[0-9]{4,4}$"  onkeypress='mascara( this, mtel );' style="margin-top: 7px;" name="cel" value="{{$cliente->cel or old('cel') }}" maxlength="15" minlength="14" required />
            <label for="cel" style="font-size: 15px; margin-top: 7px;">ddd + Celular</label>
          </div>        


     <div class="input-field">        
            <input title="(00) 1000-0001" onkeypress='mascara( this, mtel );' type="tel"  pattern="\([0-9]{2}\) [0-9]{4,6}-[0-9]{3,4}$" minlength="14" maxlength="14" style="margin-top: 9px;" placeholder="Número do telefone" name="tel" value="{{$cliente->tel or old('tel') }}" />
            <label for="tel" style="font-size: 15px; margin-top: 9px;">ddd + Telefone</label>
          </div> 
                                                 
               </div>


<div class="col-md-4">
  <div class="input-field">
            <input type="text" pattern="[0-9]{5}-[0-9]{3}" id="cep" value="{{$cliente->cep or old('cep') }}" title="Informe o CEP Ex 00000-000" onkeypress='mascara( this, mcep );' maxlength="9" name="cep" placeholder="Forneça o cep" required />
            <label for="cep" style="font-size: 15px;">Cep</label>
          </div>

           <div class="input-field">

            <input type="text" onkeypress='mascara( this, soLetras );' id="endereço" title="Informe o Endereço" maxlength="191" placeholder=" Forneça o endereço" name="endereço" value="{{$cliente->endereço or old('endereço') }}" required/>
            <label for="endereço" style="font-size: 15px;">Endereço</label>
          </div>

           <div class="input-field">
            <input type="text" onkeypress='mascara( this, mnum );' title="Informe o Número" maxlength="8" name="numero" value="{{$cliente->numero or old('numero') }}" placeholder="Forneça o número"/>
            <label for="numero" style="font-size: 15px;">Número</label>
          </div>

               <div class="input-field">
            <input type="text"  id="bairro" title="Informe o Bairro" placeholder="Forneça o Bairro" maxlength="191" name="bairro" value="{{$cliente->bairro or old('bairro') }}" required/>
            <label for="bairro" style="font-size: 15px;">Bairro</label>
          </div>

          <div class="input-field">
            <input type="text" title="Informe o Complemento" placeholder="Forneça o Complemento" maxlength="12" name="complemento"  value="{{$cliente->complemento or old('complemento') }}" />
            <label for="complemento" style="font-size: 15px;">Complemento</label>
          </div>


          <div class="input-field">

            <input type="text" id="cidade" title="Informe a Cidade" maxlength="191" name="cidade"  placeholder="Forneça o nome da Cidade"  value="{{$cliente->cidade or old('cidade') }}" required/>
            <label for="cidade" style="font-size: 15px;">Cidade</label>

          </div>

           <div class="input-field" style="margin-top: 20px;">
            <label for="estado" style="margin-top: -30px; font-size: 15px;">Escolha o estado</label>

            <select name="estado" class="form-control" id="estado" title="Informe o Estado" style=" width: 200px;  float: left;" required/>
            <option value="{{$cliente->estado or old('estado') }}">{{$cliente->estado or old('estado') }} </option>
            <option value="AC">AC</option>
            <option value="AL">AL</option>
            <option value="AP">AP</option>
            <option value="AM">AM</option>
            <option value="BA">BA</option>
            <option value="CE">CE</option>
            <option value="DF">DF</option>
            <option value="ES">ES</option>
            <option value="GO">GO</option>
            <option value="MA">MA</option>
            <option value="MT">MT</option>
            <option value="MS">MS</option>
            <option value="MG">MG</option>
            <option value="PA">PA</option>
            <option value="PB">PB</option>
            <option value="PR">PR</option>
            <option value="PE">PE</option>
            <option value="PI">PI</option>
            <option value="RJ">RJ</option>
            <option value="RN">RN</option>
            <option value="RS">RS</option>
            <option value="RO">RO</option>
            <option value="RR">RR</option>
            <option value="SC">SC</option>
            <option value="SP">SP</option>
            <option value="SE">SE</option>
            <option value="TO">TO</option>
          </select>

        </div>

        </div> 

        <div class="col-md-4">
        <blockquote>
          Dados de acesso:
        </blockquote> 

         <div class="input-field">         
          <input id="email" type="email" placeholder="{{ $cliente->email }}" title="'Campo obrigatório' E-mail de acesso" name="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" value="{{$cliente->email or old('email')}}" placeholder="E-mail de acesso" required> 
           <label for="email" style="font-size: 15px;">Email</label>

        </div><br>

           <div class="input-field">
          <input type="password" name="password" minlength="6" pattern="^(?=.*\d)(?=.*[a-z])(?!.*\s).*$" title="Senha com no mínimo seis caracteres Alfanuméricos (Letras e números)" autocomplete="off" placeholder="Senha de acesso ao sistema" required> 
          <label for="password" style="font-size: 15px;">Senha</label>
        </div><br>
            <div class="input-field">
          <input type="password" name="password_confirmation" title="Repita a Senha" pattern="^(?=.*\d)(?=.*[a-z])(?!.*\s).*$"  minlength="6" autocomplete="off" placeholder="Repita a Senha" required/>
          <label for="password_confirmation" style="font-size: 15px;">Repita Senha</label>
        </div>    


 


   

      </div>        
</div>
</div>

                     
                           
            <button type="reset" class="btn btn-default">
                <b>Restaurar</b>
            </button>
            <button type="submit" 
            class="btn btn-warning">
            <b>Concluir</b>
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
<!-- Adicionando JQuery -->
<script src="https://code.jquery.com/jquery-3.2.1.min.js"
integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
crossorigin="anonymous"></script>

<!-- Adicionando Javascript -->
<script type="text/javascript" >

  $(document).ready(function() {

    function limpa_formulário_cep() {
                // Limpa valores do formulário de cep.

                $("#prod_cod").val("");
                $("#grup_cod").val("");
                $("#grup_categ_cod").val("");
                $("#prod_desc").val(""); 
                $("#prod_desc").val("");


              }

            //Quando o campo cep perde o foco.
            $("#cep").blur(function() {

                //Nova variável "cep" somente com dígitos.
                var cep = $(this).val().replace(/\D/g, '');

                //Verifica se campo cep possui valor informado.
                if (cep != "") {

                    //Expressão regular para validar o CEP.
                    var validacep = /^[0-9]{8}$/;

                    //Valida o formato do CEP.
                    if(validacep.test(cep)) {

                        //Preenche os campos com "..." enquanto consulta webservice.
                        $("#endereço").val("Pesquisando...");
                        $("#bairro").val("Pesquisando...");
                        $("#cidade").val("Pesquisando...");
                        $("#estado").val("Pesquisando...");


                        //Consulta o webservice viacep.com.br/
                        $.getJSON("https://viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {

                          if (!("erro" in dados)) {
                                //Atualiza os campos com os valores da consulta.
                                $("#endereço").val(dados.logradouro);
                                $("#bairro").val(dados.bairro);
                                $("#cidade").val(dados.localidade);
                                $("#estado").val(dados.uf);                                
                            } //end if.
                            else {
                                //CEP pesquisado não foi encontrado.
                                limpa_formulário_cep();
                                alert("CEP não encontrado.");
                              }
                            });
                    } //end if.
                    else {
                        //cep é inválido.
                        limpa_formulário_cep();
                        alert("Formato de CEP inválido.");
                      }
                } //end if.
                else {
                    //cep sem valor, limpa formulário.
                    limpa_formulário_cep();
                  }
                });
          });

        </script>
@empty
        
<div class="card-panel">
<blockquote>                                          
Erro! Celular não foi localizado em nossos registros
</blockquote>

                        <a href="{{ URL::previous() }}" class="btn waves-effect">Tentar Novamente</a>
                  
</div>


@endforelse

@endsection