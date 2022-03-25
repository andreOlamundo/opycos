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

      <h2><b>Novo Cliente</b></h2>    

      <div class="row">
        <div class="col-md-12">
          <ol class="breadcrumb" style="margin-bottom: 10px;">                          
            <li><a href="{{route('clientes.index')}}" id="btn" style="text-decoration: none"><b>Clientes</b></a></li>
            <li class="active"><b>Cadastro</b></li>
          </ol>              
          <div class="card-panel">
            <div class="row">           
              <form method="post" 
              action="{{ route('clientes.store') }}" 
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

            <div class="col-md-4">
              <div class="input-field">
                <input class="form-control" type="text" pattern="[0-9]{5}-[0-9]{3}" id="cep" value="" title="Informe o CEP Ex 00000-000" onkeypress='mascara( this, mcep );' maxlength="9" size="10" name="cep" autofocus required />
                <label for="cep" style="font-size: 15px;">Cep</label>
              </div>
                       
             <div class="input-field">

              <input class="form-control" type="text" onkeypress='mascara( this, soLetras );' id="endereço" title="Informe o Endereço" maxlength="191" size="8" name="endereço" autofocus required/>
              <label for="endereço" style="font-size: 15px;">Endereço</label>
            </div>

             <div class="input-field">

              <input class="form-control" type="text" onkeypress='mascara( this, mnum );' title="Informe o Numero" maxlength="8" size="8" name="numero" autofocus/>
              <label for="numero" style="font-size: 15px;">Número</label>
            </div>

                      <div class="input-field">
              <input class="form-control" type="text"  id="bairro" title="Informe o Bairro" maxlength="191" size="8" name="bairro" autofocus required/>
              <label for="bairro" style="font-size: 15px;">Bairro</label>
            </div>

                   <div class="input-field">
              <input class="form-control" type="text" title="Informe o Complemento" maxlength="12" size="12" name="complemento" autofocus/>
              <label for="complemento" style="font-size: 15px;">Complemento</label>
            </div>

              <div class="input-field">

              <input class="form-control" type="text" id="cidade" title="Informe a Cidade" maxlength="191" size="8" name="cidade" autofocus required/>
              <label for="cidade" style="font-size: 15px;">Cidade</label>
            
              </div>

              <div class="input-field">
        
              <select class="form-control"  name="estado" id="estado" style=" width: 200px;  float: left;" required/>
                   <option value="">--- Selecione o Estado ---</option>
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
        @endsection

