  /*  if($retirada == NULL && $frete == NULL ){
        $req->session()->flash('mensagem-falha', 'É preciso escolher um tipo de frete');
        return redirect()->route('index')->withInput();

      }*/

   /* if($frete == 'B' && $valor == NULL){
        $req->session()->flash('mensagem-falha', 'É preciso informar o valor do frete');
        return redirect()->route('index')->withInput();

      }*/

   /* if($frete == 'C' && $valor == NULL){
        $req->session()->flash('mensagem-falha', 'É preciso informar o valor do frete');
        return redirect()->route('index')->withInput();

      }*/

                           /*  if( isset($idrequest) && isset($idproduto) ) {
                                $req->session()->flash('mensagem-falha', 'Escolha adicionar somente Produto, ou somente Requisição');
                                return redirect()->route('index');
                              }*/


/*
                                                                        if ($retirada == 'Y')
                                            {
                                                Frete::create([

                                                    'balcao'  => 'Y', 
                                                    'pedido_id'  => $idpedido, 
                                                    'id_cliente' => $idcliente,
                                                    'user_id' => $idusuario,
                                                                             //'vendedor_id' $idusuario,
                                                    'boolean' =>  $tip,     
                                                    'entrega' => NULL,                                       
                                                    'valor' => NULL,      
                                                    'status'     => 'AR' //Aguardando Retirada                  
                                                                        ]);

                                                Pedido::where([
                                                    'id' => $idpedido,
                                                    'user_id' => $idusuario,                        
                                                                            'status'  => 'GE' // Gerado
                                                                        ])->update([
                                                                            'obs_pedido' => $obspedido
                                                                                                                        //'status' => 'RE' //Finalizado
                                                                        ]);


                                                                    }


                                                                                        if ($frete == 'B')

                                                                    {

                                                                        Frete::create([

                                                                            'balcao'  => NULL, 
                                                                            'pedido_id'  => $idpedido, 
                                                                            'id_cliente' => $idcliente,
                                                                            'user_id' => $idusuario,
                                                                            'boolean' =>  $tip,
                                                                            //'vendedor_id' $idusuario,
                                                                           // 'local' => $local,
                                                                            'valor'  =>  $valor,    
                                                                            'entrega' => 'B',
                                                                           // 'cep' => $cep,
                                                                          //  'endereço' => $endereço,
                                                                           // 'numero' => $numero,
                                                                           // 'bairro' => $bairro,
                                                                           // 'complemento' => $complemento,
                                                                          //  'cidade' => $cidade,
                                                                          //  'estado' => $estado,  
                                                            'status'     => 'EMB' //Entrega pelo moto boy                  
                                                        ]);


                                                                    }





                                                                    if ($frete == 'C')

                                                                    {

                                                                        Frete::create([

                                                                            'balcao'  => NULL, 
                                                                            'pedido_id'  => $idpedido, 
                                                                            'id_cliente' => $idcliente,
                                                                            'user_id' => $idusuario,
                                                                            'boolean' =>  $tip,
                                                                           // 'local' => $local,
                                                                                //'vendedor_id' $idusuario,
                                                                            'valor'  =>  $valor,    
                                                                            'entrega' => 'CPF',
                                                                           // 'cep' => $cep,
                                                                          //  'endereço' => $endereço,
                                                                           // 'numero' => $numero,
                                                                           // 'bairro' => $bairro,
                                                                           // 'complemento' => $complemento,
                                                                          //  'cidade' => $cidade,
                                                                          //  'estado' => $estado,  
                                                            'status'     => 'EC' //Entrega Correios                 
                                                        ]);

                                                                    }






                                                                      if ($retirada == 'Y')
                                                                      {

                                                                        Frete::create([

                                                                          'balcao'  => 'Y', 
                                                                          'pedido_id'  => $idpedido, 
                                                                          'id_cliente' => $idcliente,
                                                                          'user_id' => $idusuario,
                                                                             //'vendedor_id' $idusuario,
                                                                          'boolean' =>  $tip,     
                                                                          'entrega' => NULL,                                       
                                                                          'valor' => NULL,      
                                                                            'status'     => 'AR' //Aguardando Retirada                  
                                                                          ]);

                                                                        Pedido::where([
                                                                          'id' => $idpedido,
                                                                          'user_id' => $idusuario,                        
                                                                            'status'  => 'GE' // Gerado
                                                                          ])->update([
                                                                            'obs_pedido' => $obspedido
                                                                                                                        //'status' => 'RE' //Finalizado
                                                                          ]);


                                                                        }






                                                                        if ($frete == 'B')

                                                                        {

                                                                          Frete::create([

                                                                            'balcao'  => NULL, 
                                                                            'pedido_id'  => $idpedido, 
                                                                            'id_cliente' => $idcliente,
                                                                            'user_id' => $idusuario,
                                                                            'boolean' =>  $tip,
                                                                           //'vendedor_id' $idusuario,
                                                                           // 'local' => $local,
                                                                            'valor'  =>  $valor,    
                                                                            'entrega' => 'B',
                                                                         //   'cep' => $cep,
                                                                          //  'endereço' => $endereço,
                                                                         //  'numero' => $numero,
                                                                          //  'bairro' => $bairro,
                                                                          //  'complemento' => $complemento,
                                                                          //  'cidade' => $cidade,
                                                                          //  'estado' => $estado,  
                                                            'status'     => 'EMB' //Entrega pelo moto boy                  
                                                          ]);


                                                                        }




                                                                        if ($frete == 'C')

                                                                        {

                                                                          Frete::create([

                                                                            'balcao'  => NULL, 
                                                                            'pedido_id'  => $idpedido, 
                                                                            'id_cliente' => $idcliente,
                                                                            'user_id' => $idusuario,
                                                                            'boolean' =>  $tip,
                                                                          //  'local' => $local,
                                                                                //'vendedor_id' $idusuario,
                                                                            'valor'  =>  $valor,    
                                                                            'entrega' => 'CPF',
                                                                          //  'cep' => $cep,
                                                                          //  'endereço' => $endereço,
                                                                          //  'numero' => $numero,
                                                                           // 'bairro' => $bairro,
                                                                          //  'complemento' => $complemento,
                                                                          //  'cidade' => $cidade,
                                                                         //   'estado' => $estado,  
                                                            'status'     => 'EC' //Entrega Correios                 
                                                          ]);

                                                                        }


                                                                    */