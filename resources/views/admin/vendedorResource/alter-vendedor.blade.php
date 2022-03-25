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
          <h2>Alterar Vendedor</h2>     
           </div>
      </div>

      <div class="row" style="height: 50px; width: 1170px; position: fixed; z-index: 1001; top: 100px; ">
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
          <!-- Adicionando JQuery -->
<script src="https://code.jquery.com/jquery-3.2.1.min.js"
integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
crossorigin="anonymous"></script>

<!-- Adicionando Javascript -->
<script type="text/javascript" >

  $(document).ready(function() {

    function limpa_formulário_cep() {
                // Limpa valores do formulário de cep.

                $("#endereço").val("");
                $("#bairro").val("");
                $("#cidade").val("");
                $("#estado").val(""); 



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
           
          <div class="card-panel" style="margin-top: 2px; margin-bottom: 2px; padding: 12px 10px;">
            <div class="row">           
             <form method="post" 
             action="{{route('vendedores.update', $vendedores->id)}}"
             enctype="multipart/form-data">
             {!! method_field('put') !!}
             {{ csrf_field() }}

             @if(isset($vendedores->cpf))
             @php
             $cpf_formatado = NULL;            
             $bloco_1 = substr($vendedores->cpf,0,3);
             $bloco_2 = substr($vendedores->cpf,3,3);
             $bloco_3 = substr($vendedores->cpf,6,3);
             $dig_verificador = substr($vendedores->cpf,-2);
             $cpf_formatado = $bloco_1.".".$bloco_2.".".$bloco_3."-".$dig_verificador;            
             @endphp 
             @else
             @php             
             $cnpj_formatado = NULL;
             $bloco_1 = substr($vendedores->cnpj,0,2);
             $bloco_2 = substr($vendedores->cnpj,2,3);
             $bloco_3 = substr($vendedores->cnpj,5,3);
             $bloco_4 = substr($vendedores->cnpj,8,4);
             $digito_verificador = substr($vendedores->cnpj,-2);
             $cnpj_formatado = $bloco_1.".".$bloco_2.".".$bloco_3."/".$bloco_4."-".$digito_verificador;           
             @endphp
             @endif 

             <div class="col-md-4"> 
            @if(isset($vendedores->cpf))                 
              <div id="tipo1">
                <div class="input-field">
                  <input class="cpf" type="text" pattern="\d{3}\.\d{3}\.\d{3}-\d{2}" minlength="14" title="'Campo obrigatório' 000.000.000-00" name="cpf" onkeypress='mascara(this,mcpf)' value="{{$cpf_formatado or old('cpf') }}" maxlength="14" placeholder="Cadastro Pessoa Física" />
                  <label for="cpf" style="font-size: 15px;">CPF</label>
                </div>

                <div class="input-field">
                  <input onkeypress='mascara( this, soLetras );' type="text" title="Nome do Vendedor" maxlength="191" name="name" value="{{$vendedores->name or old('name')}}" placeholder="Nome do Vendedor">
                  <label for="name" style="font-size: 15px;">Nome</label>     
                </div>
              </div> 
  @else
              <div id="tipo2">
                <div class="input-field">
                  <input class="cnpj" type="text" pattern="\d{2}\.\d{3}\.\d{3}/\d{4}-\d{2}" minlength="18" title="'Campo obrigatório' 00.000.000/0000-00" name="cnpj" onkeypress='mascaraMutuario(this,Cnpj)' value="{{$cnpj_formatado or old('cnpj') }}" maxlength="18" placeholder="Cadastro Nacional Pessoa Jurídica" />
                  <label for="cnpj" style="font-size: 15px;">CNPJ</label>
                </div>
                <div class="input-field">
                  <input type="text" title="'Campo obrigatório' Razão Social" maxlength="191" name="razao_social" value="{{$vendedores->razao_social or old('razao_social') }}" placeholder="Razão Social"><!--onkeypress='mascara( this, soLetras );'-->
                  <label for="razao_social" style="font-size: 15px;">Razão Social</label>     
                </div>
                <div class="input-field">
                  <input onkeypress='mascara( this, soLetras );' type="text" title="Nome para contato" maxlength="191" name="name_contato" value="{{$vendedores->name or old('name_contato')}}" placeholder="Nome para Contato">
                  <label for="name_contato" style="font-size: 15px;">Nome de Contato </label>     
                </div>
              </div>
  @endif 

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


              <div class="input-field">        
                <input pattern="\([0-9]{2}\) [0-9]{4,6}-[0-9]{3,4}$" minlength="15" type="tel" title="'Campo obrigatório' (00)00000-0000 " placeholder="(00) 90000-0001" onkeypress='mascara( this, mtel );' maxlength="15" name="cel" value="{{$vendedores->cel or old('cel')}}" required />
                <label for="cel" style="font-size: 15px;">ddd + Celular</label>
              </div> 

              <div class="input-field">        
                <input title="(00) 1000-0001" onkeypress='mascara( this, mtel );' type="tel"  pattern="\([0-9]{2}\) [0-9]{4,6}-[0-9]{3,4}$" minlength="14" maxlength="14" placeholder="(00) 1000-0001" name="tel"  value="{{$vendedores->tel or old('tel')}}" />
                <label for="tel" style="font-size: 15px;">ddd + Telefone</label>
              </div>

              <label>
                <input type="radio" name="tipo" value="1" disabled {{ $vendedores->cpf > '0' ? 'checked' : '' }} />
                <span style="font-size: 15px;">Pessoa Fisica:&nbsp;</span>
              </label>
              <label>
               <input type="radio" name="tipo" value="2" disabled  {{ $vendedores->cnpj > '0' ? 'checked' : '' }}  />
               <span style="font-size: 15px;">Pessoa Juridica:&nbsp;</span> 
             </label>

             <div class="input-field" style="margin-top: 20px;">
              <label for="status" style="margin-top: -30px; font-size: 15px;">Permissões</label>
              <select name="status" class="form-control"  title="Permite Bloquear o acesso do Vendedor ao sistema"style=" width: 200px;  float: left;"/>  
              <option value="{{$vendedores->status or old('status')}}">---Status ---</option>         
              <option value="block">Bloquear Acesso</option>
              <option value="active">Permitir Acesso</option>
                         
            </select>


          </div> 
           
           
   <div class="input-field"  style="margin-top: 80px;">        
                <input type="text" title="Comissão%" placeholder="{{$vendedores->comissao}}%" name="comissao" class="percent" value="{{ old('comissao') }}" required />
                <label for="comissao" style="font-size: 15px;">Comissão%</label>
              </div>
        </div>

         

        <div class="col-md-4">

          <div class="input-field">
            <input type="text" pattern="[0-9]{5}-[0-9]{3}" id="cep" value="{{$vendedores->cep or old('cep') }}" title="Informe o CEP. Consulta automática Ex:00000-000" onkeypress='mascara( this, mcep );' maxlength="9" name="cep" placeholder="Forneça o CEP" />
            <label for="cep" style="font-size: 15px;">Cep</label>
          </div>

          <div class="input-field">

            <input type="text" onkeypress='mascara( this, soLetras );' id="endereço" title="Informe o Endereço" maxlength="191" placeholder="Forneça o endereço" name="endereço" value="{{$vendedores->endereço or old('endereço') }}" />
            <label for="endereço" style="font-size: 15px;">Endereço</label>
          </div>

          <div class="input-field">
            <input type="text" onkeypress='mascara( this, mnum );' title="Informe o Número" maxlength="8" name="numero" value="{{$vendedores->numero or old('numero') }}" placeholder="Forneça o número"/>
            <label for="numero" style="font-size: 15px;">Número</label>
          </div>

          <div class="input-field">
            <input type="text"  id="bairro" title="Informe o Bairro" placeholder="Forneça o Bairro" maxlength="191" name="bairro" value="{{$vendedores->bairro or old('bairro') }}" />
            <label for="bairro" style="font-size: 15px;">Bairro</label>
          </div>

          <div class="input-field">
            <input type="text" title="Informe o Complemento" placeholder="Forneça o Complemento" maxlength="12" name="complemento"  value="{{$vendedores->complemento or old('complemento') }}" />
            <label for="complemento" style="font-size: 15px;">Complemento</label>
          </div>

          <div class="input-field">

            <input type="text" id="cidade" title="Informe a Cidade" maxlength="191" name="cidade"  placeholder="Forneça o nome da Cidade"  value="{{$vendedores->cidade or old('cidade') }}" />
            <label for="cidade" style="font-size: 15px;">Cidade</label>

          </div>

          <div class="input-field" style="margin-top: 20px;">
            <label for="estado" style="margin-top: -30px; font-size: 15px;">Escolha o estado</label>

            <select name="estado" class="form-control" id="estado" title="Informe o Estado" style=" width: 200px;  float: left;"/>
            @if (empty($vendedores->estado))
            <option value="">---Selecione o Estado ---</option>
            @else
            <option value="{{$vendedores->estado}}">{{$vendedores->estado}}</option>
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
      </div>

      <div class="col-md-4">

        <div class="input-field">
          <input id="email" type="text" title="'Campo obrigatório' EX: opycos@opycos.com.br" name="email" value="{{$vendedores->email or old('email')}}" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" placeholder="E-mail de acesso" required > 
          <label for="email" style="font-size: 15px;">Email</label>
        </div>
        <div class="input-field">
          <input type="password" name="password" pattern="^(?=.*\d)(?=.*[a-z])(?!.*\s).*$" title="Senha com no mínimo seis caracteres Alfanuméricos (Letras e números)" minlength="6" placeholder="Senha de acesso" autocomplete="off"> 
          <label for="password" style="font-size: 15px;">Senha</label>
        </div>

        <div class="input-field">
          <input type="password" name="password_confirmation" title="Repita a Senha" pattern="^(?=.*\d)(?=.*[a-z])(?!.*\s).*$" minlength="6" autocomplete="off" placeholder="Repita a Senha"/>
          <label for="password_confirmation" style="font-size: 15px;">Repita Senha</label>
        </div>

        <a href="{{route('vendedores.index')}}" class="btn btn-default" style="margin-top: -20px; width: 130px; height: 25px; padding: 2px 1px; "><!--{{ redirect()->back()->getTargetUrl() }}-->
          <b>Voltar</b>
        </a>

        <button type="submit"  title="Enviar formulário" class="btn waves-effect waves-light  blue darken-2" style="margin-top: -20px; width: 130px; height: 25px; padding: 2px 1px; "><span class="glyphicon glyphicon-floppy-disk"></span>
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

<!--<script>
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

</script>-->



        @endsection

