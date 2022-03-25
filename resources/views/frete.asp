<html>
<body>
<form name="frm" action="frete2.asp" method="post" ID="Form1">
<fieldset style="background: EEEEEE;">
<legend>Calculo de Frete</legend>
<div>
<span style="width:130px;">Codigo:</span>
<span><input type="text" name="codigo" value=""></span>
</div>
<div>
<span style="width:130px;">Senha:</span>
<span><input type="password" name="senha" value=""></span>
</div>
<div>
<span style="width:130px;">Serviços:</span>
<span><input type="text" name="servicos" value=""></span>
</div>
<div>
<span style="width:130px;">Cep Origem:</span>
<span><input type="text" name="cepori" value=""></span>
</div>
<div>
<span style="width:130px;">Cep Destino:</span>
<span><input type="text" name="cepdes" value=""></span>
</div>
<div>
<span style="width:130px;">Peso:</span>
<span><input type="text" name="peso" value=""></span>
</div>
<div>
<span style="width:130px;">Formato:</span>
<span><select name="formato" size="1">
<option value="1">Caixa/pacote</option>
<option value="2">Rolo/prisma</option>
</select>
</span>
</div>
 <div>
<span style="width:130px;">Comprimento:</span>
<span><input type="text" name="comprimento" value=""></span>
</div>
<div>
<span style="width:130px;">Altura:</span>
<span><input type="text" name="altura" value=""></span>
</div>
<div>
<span style="width:130px;">Largura:</span>
<span><input type="text" name="largura" value=""></span>
</div>
<div>
<span style="width:130px;">Diâmetro:</span>
<span><input type="text" name="diametro" value=""></span>
</div>
<div>
<span style="width:130px;">Mão própria:</span>
<span><select name="maopropria" size="1">
14/16
DEENC – Departamento de Encomendas
Revisão 25/09/2019
<option value="S">Sim</option>
<option value="N">Não</option>
</select>
</span>
</div>
<div>
<span style="width:130px;">Valor declarado:</span>
<span><input type="text" name="valordeclarado" value=""></span>
</div>
<div>
<span style="width:130px;">Aviso de Recebimento:</span>
<span><select name="avisorecebimento" size="1">
<option value="S">Sim</option>
<option value="N">Não</option>
</select>
</span>
</div>
<div>
<span><input type="SUBMIT" name="BTN" value="Consultar"
ID="Submit1"></span>
</div>
</fieldset>
</form>
</body>
</html>