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
          <h2>Novo Vendedor</h2>     
           </div>
      </div>


      <div class="row"  style="height: 50px; width: 1170px; position: fixed; z-index: 1001; top: 100px; ">
        <div class="col-md-12">
          <!--<ol class="breadcrumb" style="margin-bottom: 5px;">                          
            <li><a href="{{route('vendedores.index')}}" id="btn" style="text-decoration: none"><b> Vendedores</b></a></li>
            <li class="active">Cadastro</li>
          </ol> -->
        @if (session('message'))
          <div class="alert alert-success alert-dismissible fade in" style="margin-bottom: 1px;">
            <a href="#" class="close" 
            data-dismiss="alert"
            aria-label="close">&times;</a>
            <b> {{ session('message') }}</b>
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
          <div class="fixed-action-btn">

            <a class="btn-floating btn-large lighten-2" title="Alterar Perfil">
              <i class="large material-icons">mode_edit</i>
            </a>
            <ul>
              <li><a href="{{route('admins.create')}}" class="btn-floating red" title="Administrador"><i class="material-icons">assignment_ind</i></a></li>
              <li><a href="{{route('vendedores.create')}}" class="btn-floating yellow" title="Vendedor"><i class="material-icons">account_box</i></a></li>
              <li><a href="{{route('clientes.create')}}" class="btn-floating green" title="Cliente"><i class="material-icons">perm_identity</i></a></li>

            </ul>

          </div> 
          <script src="https://code.jquery.com/jquery-3.2.1.min.js"
integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
crossorigin="anonymous"></script>

<!-- Adicionando Javascript -->
<script type="text/javascript" >

  $(document).ready(function() {

    function limpa_formul??rio_cep() {
                // Limpa valores do formul??rio de cep.

                $("#endere??o").val("");
                $("#bairro").val("");
                $("#cidade").val("");
                $("#estado").val(""); 
           


              }

            //Quando o campo cep perde o foco.
            $("#cep").blur(function() {

                //Nova vari??vel "cep" somente com d??gitos.
                var cep = $(this).val().replace(/\D/g, '');

                //Verifica se campo cep possui valor informado.
                if (cep != "") {

                    //Express??o regular para validar o CEP.
                    var validacep = /^[0-9]{8}$/;

                    //Valida o formato do CEP.
                    if(validacep.test(cep)) {

                        //Preenche os campos com "..." enquanto consulta webservice.
                        $("#endere??o").val("Pesquisando...");
                        $("#bairro").val("Pesquisando...");
                        $("#cidade").val("Pesquisando...");
                        $("#estado").val("Pesquisando...");


                        //Consulta o webservice viacep.com.br/
                        $.getJSON("https://viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {

                          if (!("erro" in dados)) {
                                //Atualiza os campos com os valores da consulta.
                                $("#endere??o").val(dados.logradouro);
                                $("#bairro").val(dados.bairro);
                                $("#cidade").val(dados.localidade);
                                $("#estado").val(dados.uf);                                
                            } //end if.
                            else {
                                //CEP pesquisado n??o foi encontrado.
                                limpa_formul??rio_cep();
                                alert("CEP n??o encontrado.");
                              }
                            });
                    } //end if.
                    else {
                        //cep ?? inv??lido.
                        limpa_formul??rio_cep();
                        alert("Formato de CEP inv??lido.");
                      }
                } //end if.
                else {
                    //cep sem valor, limpa formul??rio.
                    limpa_formul??rio_cep();
                  }
                });
          });

        </script>            
          <div class="card-panel" style="height: 370px; margin-top: 2px; margin-bottom: 2px; padding: 12px 10px;">
            <div class="row">           
              <form method="post" 
              action="{{ route('vendedores.store') }}" 
              enctype="multipart/form-data">
              {{ csrf_field() }}
              <div class="col-md-4">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.js"></script>
  <script type="text/javascript">
    $(document).ready(function(){
      var maskBehavior = function (val) {
        return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
      },
      options = {onKeyPress: function(val, e, field, options) {
        field.mask(maskBehavior.apply({}, arguments), options);
      }
    };


    $('.percent').mask('000%', {reverse: true}).attr('maxlength','4');

  });
</script>
                  
              <div id="tipo1">
                  <div class="input-field">
                    <input class="cpf" type="text" pattern="\d{3}\.\d{3}\.\d{3}-\d{2}" minlength="14" title="'Campo obrigat??rio' 000.000.000-00" name="cpf" onkeypress='mascara(this,mcpf)' value="{{ old('cpf') }}" maxlength="14" placeholder="Cadastro Pessoa F??sica" />
                    <label for="cpf" style="font-size: 15px;">CPF</label>
                  </div>

              <div class="input-field">
                <input onkeypress='mascara( this, soLetras );' type="text" title="Nome do Vendedor" maxlength="191" name="name" value="{{ old('name') }}" placeholder="Nome do Vendedor">
                <label for="name" style="font-size: 15px;">Nome</label>     
              </div>
                </div> 

                <div id="tipo2" style="display:none;">
                  <div class="input-field">
                    <input class="cnpj" type="text" pattern="\d{2}\.\d{3}\.\d{3}/\d{4}-\d{2}" minlength="18" title="'Campo obrigat??rio' 00.000.000/0000-00" name="cnpj" onkeypress='mascaraMutuario(this,Cnpj)' value="{{ old('cnpj') }}" maxlength="18" placeholder="Cadastro Nacional Pessoa Jur??dica" />
                    <label for="cnpj" style="font-size: 15px;">CNPJ</label>
                  </div>
                 <div class="input-field">
                <input type="text" title="'Campo obrigat??rio' Raz??o Social" maxlength="191" name="razao_social" value="{{ old('razao_social') }}" placeholder="Raz??o Social"><!--onkeypress='mascara( this, soLetras );'-->
                <label for="razao_social" style="font-size: 15px;">Raz??o Social</label>     
              </div>
               <div class="input-field">
                <input onkeypress='mascara( this, soLetras );' type="text" title="Nome para contato" maxlength="191" name="name_contato" value="{{ old('name_contato') }}" placeholder="Nome para Contato">
                <label for="name_contato" style="font-size: 15px;">Nome de Contato</label>     
              </div>
                </div>
      


              <div class="input-field">        
                <input pattern="\([0-9]{2}\) [0-9]{4,6}-[0-9]{3,4}$" minlength="15" type="tel" title="'Campo obrigat??rio' (00)00000-0000 " placeholder="(00) 90000-0001" onkeypress='mascara( this, mtel );' maxlength="15" name="cel" value="{{ old('cel') }}" required />
                <label for="cel" style="font-size: 15px;">ddd + Celular</label>
              </div> 

              <div class="input-field">        
                <input title="(00) 1000-0001" onkeypress='mascara( this, mtel );' type="tel"  pattern="\([0-9]{2}\) [0-9]{4,6}-[0-9]{3,4}$" minlength="14" maxlength="14" placeholder="(00) 1000-0001" name="tel"  value="{{ old('tel') }}" />
                <label for="tel" style="font-size: 15px;">ddd + Telefone</label>
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
                <input type="text" title="Comiss??o%" placeholder="0%" name="comissao" class="percent" value="{{ old('comissao') }}" required />
                <label for="comissao" style="font-size: 15px;">Comiss??o%</label>
              </div> 


            </div>

            <div class="col-md-4">
            
              <div class="input-field">
            <input type="text" pattern="[0-9]{5}-[0-9]{3}" id="cep" value="{{ old('cep') }}" title="Informe o CEP. Consulta autom??tica Ex:00000-000" onkeypress='mascara( this, mcep );' maxlength="9" name="cep" placeholder="Forne??a o CEP" />
            <label for="cep" style="font-size: 15px;">Cep</label>
          </div>

          <div class="input-field">

            <input type="text" onkeypress='mascara( this, soLetras );' id="endere??o" title="Informe o Endere??o" maxlength="191" placeholder="Forne??a o endere??o" name="endere??o" value="{{ old('endere??o') }}"/>
            <label for="endere??o" style="font-size: 15px;">Endere??o</label>
          </div>

          <div class="input-field">
            <input type="text" onkeypress='mascara( this, mnum );' title="Informe o N??mero" maxlength="8" name="numero" value="{{ old('numero') }}" placeholder="Forne??a o n??mero"/>
            <label for="numero" style="font-size: 15px;">N??mero</label>
          </div>

          <div class="input-field">
            <input type="text"  id="bairro" title="Informe o Bairro" placeholder="Forne??a o Bairro" maxlength="191" name="bairro" value="{{ old('bairro') }}" />
            <label for="bairro" style="font-size: 15px;">Bairro</label>
          </div>

          <div class="input-field">
            <input type="text" title="Informe o Complemento" placeholder="Forne??a o Complemento" maxlength="12" name="complemento"  value="{{ old('complemento') }}" />
            <label for="complemento" style="font-size: 15px;">Complemento</label>
          </div>

          <div class="input-field">

            <input type="text" id="cidade" title="Informe a Cidade" maxlength="191" name="cidade"  placeholder="Forne??a o nome da Cidade"  value="{{ old('cidade') }}" />
            <label for="cidade" style="font-size: 15px;">Cidade</label>

          </div>

          <div class="input-field" style="margin-top: 20px;">
            <label for="estado" style="margin-top: -30px; font-size: 15px;">Escolha o estado</label>

            <select name="estado" class="form-control" id="estado" title="Informe o Estado" style=" width: 200px;  float: left;" />
            @if (empty(old('estado')))
             <option value="">---Selecione o Estado ---</option>
            @else
            <option value="{{ old('estado') }}">{{old('estado') }}</option>
            @endif
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
        <!-- Adicionando JQuery -->

            </div>

            <div class="col-md-4">
        
              <div class="input-field">
                <input id="email" type="text" title="'Campo obrigat??rio' EX: opycos@opycos.com.br" name="email" value="{{ old('email') }}" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" placeholder="E-mail de acesso" required > 
                <label for="email" style="font-size: 15px;">Email</label>
              </div>
              <div class="input-field">
                <input type="password" name="password" pattern="^(?=.*\d)(?=.*[a-z])(?!.*\s).*$" title="Senha com no m??nimo seis caracteres Alfanum??ricos (Letras e n??meros)" minlength="6" required placeholder="Senha de acesso" autocomplete="off"> 
                <label for="password" style="font-size: 15px;">Senha</label>
              </div>

              <div class="input-field">
                <input type="password" name="password_confirmation" title="Repita a Senha" pattern="^(?=.*\d)(?=.*[a-z])(?!.*\s).*$" minlength="6" required autocomplete="off" placeholder="Repita a Senha"/>
                <label for="password_confirmation" style="font-size: 15px;">Repita Senha</label>
              </div>
  
        


         <a href="{{route('vendedores.index')}}" class="btn btn-default" style="margin-top: -20px; width: 130px; height: 25px; padding: 2px 1px; "><!--{{ redirect()->back()->getTargetUrl() }}-->
          <b>Voltar</b>
        </a>

          <button type="submit"  title="Enviar formul??rio" class="btn waves-effect waves-light  blue darken-2" style="margin-top: -20px; width: 130px; height: 25px; padding: 2px 1px; "><span class="glyphicon glyphicon-floppy-disk"></span>
          <b>Salvar</b>
        </button>
    </div>
      </div>
      </form>


    </div>

  </div>
</div>
</div>
</div>
</div>
</div>

<script type="text/javascript">
  document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('.fixed-action-btn');
    var instances = M.FloatingActionButton.init(elems, {
      direction: 'left'
    });
  });

  // Or with jQuery

  $(document).ready(function(){
    $('.fixed-action-btn').floatingActionButton();
  });

  var instance = M.FloatingActionButton.getInstance(elem);
  instance.open();
  instance.close();
  instance.destroy();
</script>

<script>
  function alterna(tipo) {

    if (tipo == 1) {
      document.getElementById("tipo1").style.display = "block";
      document.getElementById("tipo2").style.display = "none";
      $(".cnpj").val("");
    } else {
      document.getElementById("tipo1").style.display = "none";
      document.getElementById("tipo2").style.display = "block";
      $(".cpf").val("");
    }

  }

</script>




@endsection

