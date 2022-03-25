<%@LANGUAGE=VBScript%>
<%
option explicit
'On Error Resume Next
dim oXmlHttp, oXmlDom
dim sReturn, sPacoteSoap
dim nCodigoRet, sDescricaoRet
set oXmlHttp = server.CreateObject("Microsoft.XMLHTTP")
const sSoapServer = "http://ws.correios.com.br/calculador/CalcPrecoPrazo.asmx"
'Tratamento de campos obrigatórios
dim peso, comprimento, altura, largura, diametro, valordeclarado
if request.form("peso") = "" then
peso = "0"
else
peso = request.form("peso")
end if
if request.form("comprimento") = "" then
comprimento = "0"
else
comprimento = request.form("comprimento")
end if
if request.form("altura") = "" then
altura = "0"
else
altura = request.form("altura")
end if
if request.form("largura") = "" then
largura = "0"
else
largura = request.form("largura")
end if
if request.form("diametro") = "" then
diametro = "0"
else
diametro = request.form("diametro")
end if
if request.form("valordeclarado") = "" then
valordeclarado = "0"
15/16
DEENC – Departamento de Encomendas
Revisão 25/09/2019
else
valordeclarado = request.form("valordeclarado")
end if
sPacoteSoap =
GerarPacoteSoap(request.form("codigo"),request.form("senha"),request.form("servicos"),requ
est.form("cepori"),request.form("cepdes"),peso,request.form("formato"),comprimento,
altura, largura, diametro, request.form("maopropria"), valordeclarado,
request.form("avisorecebimento"))
oXmlHttp.open "POST", sSoapServer, false
oXmlHttp.setRequestHeader "SOAPAction", "http://tempuri.org/CalcPrecoPrazo"
oXmlHttp.setRequestHeader "Content-Type", "text/xml; charset=utf-8"
oXmlHttp.send(sPacoteSoap)
sReturn = oXmlHttp.responseText
'parse xml
Set oXmlDom = Server.CreateObject("Microsoft.XMLDOM")
oXmlDom.loadXML sReturn
set oXmlDom =
oXmlDom.selectSingleNode("soap:Envelope/soap:Body/CalcPrecoPrazoResponse/CalcPrecoPrazoRes
ult")
'Se o retorno foi OK então listar os fretes disponíveis
dim oNodes, oNode
'Seleciona os fretes no XML
Set oNodes = oXmlDom.selectNodes("Servicos/cServico")
'Mostra todos os fretes disponíveis
for each oNode in oNodes
Response.Write "<div><span style=width:150px;>Código:</span><span>" &
oNode.selectSingleNode("Codigo").Text & "</span><br>"
Response.Write "<span style=width:150px;>Valor:</span><span>" &
oNode.selectSingleNode("Valor").Text & "</span><br>"
Response.Write "<span style=width:150px;>Prazo: </span><span>" &
oNode.selectSingleNode("PrazoEntrega").Text & "</span></div>"
Response.Write "<span style=width:150px;>Valor Mão Própria: </span><span>" &
oNode.selectSingleNode("ValorMaoPropria").Text & "</span></div>"
Response.Write "<span style=width:150px;>Valor Aviso de Recebimento:
</span><span>" & oNode.selectSingleNode("ValorAvisoRecebimento").Text & "</span></div>"
Response.Write "<span style=width:150px;>Valor Valor Declarado: </span><span>" &
oNode.selectSingleNode("ValorValorDeclarado").Text & "</span></div>"
Response.Write "<span style=width:150px;>Entrega Domiciliar: </span><span>" &
oNode.selectSingleNode("EntregaDomiciliar").Text & "</span></div>"
Response.Write "<span style=width:150px;>Entrega Sábado: </span><span>" &
oNode.selectSingleNode("EntregaSabado").Text & "</span></div>"
next
Set oXmlDom = Nothing
function GerarPacoteSoap(nCdEmpresa, sDsSenha, nCdServico, sCepOrigem, sCepDestino,
nVlPeso, nCdFormato, nVlComprimento, nVlAltura, nVlLargura, nVlDiametro, sCdMaoPropria,
nVlValorDeclarado, sCdAvisoRecebimento)
dim sSoap
sSoap = "<?xml version=""1.0"" encoding=""utf-8""?>"
sSoap = sSoap & "<soap:Envelope xmlns:xsi=""http://www.w3.org/2001/XMLSchemainstance"" xmlns:xsd=""http://www.w3.org/2001/XMLSchema""
xmlns:soap=""http://schemas.xmlsoap.org/soap/envelope/"">"
sSoap = sSoap & "<soap:Body>"
sSoap = sSoap & "<CalcPrecoPrazo xmlns=""http://tempuri.org/"">"
sSoap = sSoap & "<nCdEmpresa>" & nCdEmpresa & "</nCdEmpresa>"
sSoap = sSoap & "<sDsSenha>" & sDsSenha & "</sDsSenha>"
sSoap = sSoap & "<nCdServico>" & nCdServico & "</nCdServico>"
sSoap = sSoap & "<sCepOrigem>" & sCepOrigem & "</sCepOrigem>"
sSoap = sSoap & "<sCepDestino>" & sCepDestino & "</sCepDestino>"
sSoap = sSoap & "<nVlPeso>" & nVlPeso & "</nVlPeso>"
sSoap = sSoap & "<nCdFormato>" & nCdFormato & "</nCdFormato>"
sSoap = sSoap & "<nVlComprimento>" & nVlComprimento & "</nVlComprimento>"
sSoap = sSoap & "<nVlAltura>" & nVlAltura & "</nVlAltura>"
sSoap = sSoap & "<nVlLargura>" & nVlLargura & "</nVlLargura>"
sSoap = sSoap & "<nVlDiametro>" & nVlDiametro & "</nVlDiametro>"
sSoap = sSoap & "<sCdMaoPropria>" & sCdMaoPropria & "</sCdMaoPropria>"
sSoap = sSoap & "<nVlValorDeclarado>" & nVlValorDeclarado & "</nVlValorDeclarado>"
sSoap = sSoap & "<sCdAvisoRecebimento>" & sCdAvisoRecebimento &
"</sCdAvisoRecebimento>"
sSoap = sSoap & "</CalcPrecoPrazo>"
sSoap = sSoap & "</soap:Body>"
sSoap = sSoap & "</soap:Envelope>"
GerarPacoteSoap = sSoap
end function
%>
<input type="button" name="btn" value="Voltar" onclick="history.go(-1);" ID="Button1">