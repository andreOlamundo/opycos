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

function soLetras(v){
v=v.replace(/\d/g,""); //Remove tudo o que não é Letra
return v;
}

function mtel(v){
    v=v.replace(/\D/g,"");             //Remove tudo o que não é dígito
    v=v.replace(/^(\d{2})(\d)/g,"($1) $2"); //Coloca parênteses em volta dos dois primeiros dígitos
    v=v.replace(/(\d)(\d{4})$/,"$1-$2");    //Coloca hífen entre o quarto e o quinto dígitos
    return v;
  }

  function mcel(v){
    v=v.replace(/\D/g,"");          //Remove tudo o que não é dígito
    v=v.replace(/^(\d{2})(\d)$/,"55 $1 $2");
    v=v.replace(/^(\d{2})(\d)/,"$1$2"); //Coloca parênteses em volta dos dois primeiros dígitos
   v=v.replace(/(\d)(\d{4})$/,"$1$2");    //Coloca hífen entre o quarto e o quinto dígitos
    return v;
  }


  function mtelwhats(v){
   v=v.replace(/\D/g,"");             //Remove tudo o que não é dígito
    v=v.replace(/^(\d{2})(\d)/g,"($1) $2"); //Coloca parênteses em volta dos dois primeiros dígitos
    v=v.replace(/(\d)(\d{4})$/,"$1-$2");    //Coloca hífen entre o quarto e o quinto dígitos
    return v;
  }

  function mdata(v){
    v=v.replace(/\D/g,"");                    //Remove tudo o que não é dígito
    v=v.replace(/(\d{2})(\d)/,"$1/$2");       
    v=v.replace(/(\d{2})(\d)/,"$1/$2");       
                                             
    v=v.replace(/(\d{2})(\d{2})$/,"$1$2");
    return v;
}


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
    return v;
  }

// funcao mascara campo valor onkeypress
function fvalor(v){
    v=v.replace(/\D/g,"");//Remove tudo o que não é dígito
   v=v.replace(/(\d)(\d{8})$/,"$1.$2");//coloca o ponto dos milhões

   v=v.replace(/(\d)(\d{5})$/,"$1.$2");//coloca o ponto dos milhares

    v=v.replace(/(\d)(\d{2})$/,"$1.$2");//coloca um ponto antes dos 2 últimos dígitos
    return v;
  }

  function mcpf(v){
    v=v.replace(/\D/g,"")                    //Remove tudo o que não é dígito
    v=v.replace(/(\d{3})(\d)/,"$1.$2")       //Coloca um ponto entre o terceiro e o quarto dígitos
    v=v.replace(/(\d{3})(\d)/,"$1.$2")       //Coloca um ponto entre o terceiro e o quarto dígitos
                                             //de novo (para o segundo bloco de números)
    v=v.replace(/(\d{3})(\d{1,2})$/,"$1-$2") //Coloca um hífen entre o terceiro e o quarto dígitos
    return v
  }

  function mcep(v){
    v=v.replace(/\D/g,"")                    //Remove tudo o que não é dígito
    v=v.replace(/^(\d{5})(\d)/,"$1-$2")         //Esse é tão fácil que não merece explicações
    return v
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

function mascaraMutuario(o,f){
  v_obj=o
  v_fun=f
  setTimeout('execmascara()',1)
}

function execmascara(){
  v_obj.value=v_fun(v_obj.value)
}

function Cnpj(v){

    //Remove tudo o que não é dígito
    v=v.replace(/\D/g,"")
    //CNPJ

        //Coloca ponto entre o segundo e o terceiro dígitos
        v=v.replace(/^(\d{2})(\d)/,"$1.$2")

        //Coloca ponto entre o quinto e o sexto dígitos
        v=v.replace(/^(\d{2})\.(\d{3})(\d)/,"$1.$2.$3")

        //Coloca uma barra entre o oitavo e o nono dígitos
        v=v.replace(/\.(\d{3})(\d)/,".$1/$2")

        //Coloca um hífen depois do bloco de quatro dígitos
        v=v.replace(/(\d{4})(\d)/,"$1-$2")

        return v
      }



