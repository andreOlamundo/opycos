                                                               $ceporigem = '99040000';
                                                                                  $cepdestino = '99025020';
                                                                                  $peso = '0.5';
                                                                                  $comprimento = '20';
                                                                                  $altura = '105';
                                                                                  $largura = '105';
                                                                                  $diametro = '105';
                                                                                  $formato = 1;
                                                                                  $maopropria = 'N'; 
                                                                                  $valordeclarado = 0; 
                                                                                  $avisorecebimento = 'N'; 
                                                                                  $tiporetorno = 'xml';
                                                                                  $indicacalculo = 3;
                                                                                  $cdservico = '04014';

                                                               $url = "http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx?nCdEmpresa=&sDsSenha=&sCepOrigem=".$ceporigem."&sCepDestino=".$cepdestino."&nVlPeso=".$peso."&nCdFormato=".$formato."&nVlComprimento=".$comprimento."&nVlAltura=".$altura."&nVlLargura=".$largura."&sCdMaoPropria=".$maopropria."&nVlValorDeclarado=".$valordeclarado."&sCdAvisoRecebimento=".$avisorecebimento."&nCdServico=".$cdservico."&nVlDiametro=".$diametro."&StrRetorno=".$tiporetorno."&nIndicaCalculo=".$indicacalculo;


                                                                //link do arquivo xml
                                                             
                                                              //carrega o arquivo XML e retornando um Array
                                                               $xml = simplexml_load_file($url);


                                                              // return $xml;

                                                               

                                                               return $xml->cServico->Valor;
                                                                                                                             
                                                             

                                                              $check_cep = Cliente::select('cep')->where('id', '=', $idcliente)->get(); //CEP de destino!!!!!!!!!


                                                       

                                                           
                                                               // return response()->json($url);

                                                               //return response()->file($pathToFile);
                                                         

                                                             // return redirect()->away($url);

