            //Funcao adiciona uma nova linha na tabela
            function adicionaLinha(idTabela) {
                var tabela = document.getElementById(idTabela);
                var numeroLinhas = tabela.rows.length;
                var linha = tabela.insertRow(numeroLinhas);
                var celula1 = linha.insertCell(0);
                var celula2 = linha.insertCell(1);   
                var celula3 = linha.insertCell(2);
                var celula4 = linha.insertCell(3); 
                celula1.innerHTML = "<input type='text' required='required' placeholder='R$ 0,00 ' maxlength='15' name='rf10_valor_item[]' onblur='calcular(this)' pattern='([0-9]{1,3}\.)?[0-9]{1,3},[0-9]{2}$' class='valor' size='15' onkeypress='mascara( this, mvalor );'>";
                celula2.innerHTML =  "<input type='date' required='required' maxlength='10' name='rf10_data_item[]' id='rf10_data_item' pattern='[0-9]{2}\/[0-9]{2}\/[0-9]{4}$'' min='2018-01-01' max='2020-12-01' class='form-control' />"; 
                  celula3.innerHTML =  "<textarea class='form-control' rows='1' cols='50' id='rf10_dd_item' name='rf10_dd_item[]' placeholder='Digite Aqui:' required='required' class='form-control'></textarea>"; 
                celula4.innerHTML =  "<button class='btn btn-danger' onclick='removeLinha(this)'>Remover</button>";
            }  

// funcao remove uma linha da tabela
  function removeLinha(linha) {
  var i=linha.parentNode.parentNode.rowIndex;
  document.getElementById('tbl').deleteRow(i);

  var total = 0;
  var x = document.getElementsByClassName('valor');
  for (var i = 0; i < x.length; i++) {
  total +=  parseFloat(x[i].value.replace(',','.'));
            						}
  document.getElementById('result').value = String(total.toFixed(2)).formatMoney();
  
 }

// funcao mascara campo valor onkeypress
  function mascara(o,f){
    v_obj=o
    v_fun=f
    setTimeout("execmascara()",1)
}
// funcao mascara campo valor onkeypress
function execmascara(){
    v_obj.value=v_fun(v_obj.value)
}
// funcao mascara alternativa para numeros (Não utilizada)
function mnum(v){
    v=v.replace(/\D/g,"");//Remove tudo o que não é dígito
    return v;
}
// funcao mascara campo valor onkeypress
function mvalor(v){
    v=v.replace(/\D/g,"");//Remove tudo o que não é dígito
   v=v.replace(/(\d)(\d{8})$/,"$1.$2");//coloca o ponto dos milhões

   v=v.replace(/(\d)(\d{5})$/,"$1.$2");//coloca o ponto dos milhares
        
    v=v.replace(/(\d)(\d{2})$/,"$1,$2");//coloca a virgula antes dos 2 últimos dígitos
    return v.replace('.','');
}

// funcao Mascara em formato Real TOTALR$
String.prototype.formatMoney = function() {
    var x = this;
   
  if(x.indexOf('.') === -1) {

   x = x.replace(/([\d]+)/, "$1,00");
    }

   x = x.replace(/([\d]+)\.([\d]{1})$/, "$1,$20");
   x = x.replace(/([\d]+)\.([\d]{2})$/, "$1,$2");
   x = x.replace(/([\d]+)([\d]{3}),([\d]{2})$/, "$1.$2,$3");


    return x;
}
// funcao calcula as linhas com valores da tabela
function calcular() {
 var total = 0;
 var x = document.getElementsByClassName('valor');
 for (var i = 0; i < x.length; i++) {
 total +=  parseFloat(x[i].value);
            						}
 document.getElementById("result").value = String(total.toFixed(2)).formatMoney();
            		}
