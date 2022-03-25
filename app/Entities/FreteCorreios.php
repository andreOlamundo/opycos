<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;


    class FretePrazo extends Model
     {
        
        public $dados;
        
        public function __construct($cdservico, $ceporigem, $cepdestino, $peso, $comprimento, $altura, $largura, $diametro, $formato = 1, $maopropria = 'N', $valordeclarado = 0, $avisorecebimento = 'N', $tiporetorno = 'xml', $indicacalculo = 3){
        
            $url = "http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx?nCdEmpresa=&sDsSenha=&sCepOrigem=".$ceporigem."&sCepDestino=".$cepdestino."&nVlPeso=".$peso."&nCdFormato=".$formato."&nVlComprimento=".$comprimento."&nVlAltura=".$altura."&nVlLargura=".$largura."&sCdMaoPropria=".$maopropria."&nVlValorDeclarado=".$valordeclarado."&sCdAvisoRecebimento=".$avisorecebimento."&nCdServico=".$cdservico."&nVlDiametro=".$diametro."&StrRetorno=".$tiporetorno."&nIndicaCalculo=".$indicacalculo;
            
            $this->dados = $url;
            
        }
        
    }

$frete = new FretePrazo("41106", "99040000", "99025020", "0.5", "20", "105", "105", "105");

if($frete->dados->cServico->Erro == 0){
    echo "Valor do frete: R$ ".$frete->dados->cServico->Valor."<br/>";
    echo "Prazo: ".$frete->dados->cServico->PrazoEntrega." dias";
} else {
    echo "Ocorreu um erro ao efetuar a consulta. Mensagem: "; 
    echo $frete->dados->cServico->MsgErro;
    echo " Codigo: ".$frete->dados->cServico->Erro;
}