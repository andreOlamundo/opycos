<?php

namespace App\Http\Controllers;

use App\Entities\Pedido;
use App\Entities\Produto;
use App\Entities\OpycosRequest;
use App\Entities\Frete;
use App\Entities\Cliente;
use App\Entities\Vendedor;
use Illuminate\Support\Facades\Validator;
//use App\Entities\GroupProducts;
use App\Entities\ItensPedido;
//use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use PDF;
//use Exception;
use DB;

class PedidoController extends Controller
{

  public function __construct()
  {
    $this->middleware('auth');
  }

  public function index() {


   $registros = Produto::where([
    'ativo' => 's'
  ])->get();                                                                            


   $list_requisitions= OpycosRequest::where('status','=','FI')->get();




   $pedidos = Pedido::where([     
    'status' => 'GE',
    'user_id' => Auth::id() 
  ])->get();

  /* $pedidos_request = Pedido::where([
    'request_cod' => '>', 0, 
    'user_id' => Auth::id(),
     'status' => 'GE'

   ])->get();*/

   $pedidos_produto = Pedido::where([     
    'status' => 'GE',
    'user_id' => Auth::id()
  ])->where( 'produto_id', '!=', NULL)->get();


   $pedidos_request=Pedido::select('id','id_cliente', 'request_id','status')->where('request_id', '!=', NULL)->where(['status' => 'GE',  'user_id' => Auth::id()])->take(100)->get();


   $dadosClientes= Cliente::where('status', '!=', 'A')->get();


   $retiradaBalcPF = Frete::where([
    'status' => 'AR',
    'balcao' => 'Y',
    'boolean' => 'Y',
    'user_id' => Auth::id()                   
  ])->get(); 


 /*$pedidosRequest = Pedido::where([
            'request_cod' => 'AR',
            'request_valor' => 'Y',
            'req_desc' => 'Y',
            'user_id' => Auth::id()                   
          ])->get();*/


          $requisitions = ItensPedido::where([
            'status' => 'GE', //Gerado
            'tipo' => 'R',
         //    'user_id' => Auth::id()
          ])->get();

   $pedidos_id = Pedido::where([     
    'status' => 'GE',
    'user_id' => Auth::id()
  ])->where( 'produto_id', '!=', NULL)->pluck('id');

        /*  $produtos = ItensPedido::where([
            'status' => 'GE', //Gerado
            'tipo' => 'P',
            'pedido_id' => $pedidos_id
          //   'user_id' => Auth::id()
          ])->orderBy('id', 'desc')->get();*/





       // dd($retirada);

          $freteB_PF = Frete::where([
            'user_id' => Auth::id(),                    
            'status' => 'EMB',            
            'entrega' => 'B',
            'boolean' => 'Y'   
          ])->get(); 




          $freteC_PF = Frete::where([
            'user_id' => Auth::id(),                    
            'status' => 'C',
            'entrega' => 'C',
            'boolean' => 'Y'    
          ])->get(); 



          $valorFrete = DB::table('fretes')->select('valor')->where([
           // 'user_id' => Auth::id(),                    
            'status' => 'EMB',            
            'boolean' => 'Y'    
          ])->get();

          $valorFreteC = DB::table('fretes')->select('valor')->where([
//'user_id' => Auth::id(),                    
            'status' => 'C',            
            'boolean' => 'Y'    
          ])->get();


       // $produtos = DB::table('itens_pedidos')->select('prod_preco_padrao')->get();


   /*dd([
            $pedidos,
            $pedidos[0]->itens_pedido,
            $pedidos[0]->itens_pedido[0]->product
          ]);*/

      // $total = Pedido::all()->count();
          return view('admin.pedidoResource.index', compact('registros', 'pedidos', 'dadosClientes', 'retiradaBalcPF', 'freteB_PF', 'freteC_PF', 'valorFrete','valorFreteC', 'list_requisitions', 'requisitions', 'pedidos_request', 'pedidos_produto'));
        }






        public function admin() {

         $pedidosAdm = Pedido::all();
  /*dd([
            $pedidosAdm,
            $pedidosAdm[0]->itens_pedido,
            $pedidosAdm[0]->itens_pedido[0]->product
          ]);*/

      // $total = Pedido::all()->count();
          return view('list-pedidosAdmin', compact('pedidosAdm'));
        }





        public function adicionar() 
        {

         $this->middleware('VerifyCsrfToken');
         $req = Request();        
         $obspedido = $req->input('obs_pedido');
         $idcliente = $req->input('id_cliente');
         $idproduto = $req->input('id');
         $desconto_produtos = $req->input('desconto_produto');                    
         // dd($desconto_produtos);
        $desconto_produtos = str_replace( ',', '.', $desconto_produtos);

        $desconto_request = $req->input('desconto_request');                    
         // dd($desconto_produtos);
        $desconto_request = str_replace( ',', '.', $desconto_request);
       //$retirada = $req->input('balcao');
       //$frete = $req->input('entrega');

        //dd($desconto_request);
        //dd($desconto_produtos);

         $idrequest = $req->input('request_cod');  
         $quantidade_prod =  $req->input('quantidade_produto');
         $quantidade_req =  $req->input('quantidade_request');
        // dd($quantidade_req);


       //$valor = $req->input('valor');
     //  $valor = str_replace( ',', '.', $valor );
         $tip = $req->input('boolean');
         $idvendedor = $req->input('vendedor_id');

         //dd($idvendedor);

         $validator = validator($req->all(),
          [
            'id_cliente' => 'required'
          ]);

         if ( $validator->fails()){


          $req->session()->flash('mensagem-falha', 'É preciso escolher um cliente.');
          return redirect()->route('index')->withInput();
        }


        if (isset($idrequest) && isset($idproduto))   
        {   

         $req->session()->flash('mensagem-falha', 'Escolha um item por vez');
         return redirect()->route('index')->withInput();


       }


       if ($idrequest == NULL)   
       {     

        if ($desconto_produtos == NULL)
        {
          $desconto_produtos = '0.00';
        }                   

         $produto = Produto::find($idproduto);

         if( empty($produto->id)) {
          $req->session()->flash('mensagem-falha', 'Escolha algum item.');
          return redirect()->route('index');
        }

         if( $produto->prod_preco_padrao <= $desconto_produtos ) {
          $req->session()->flash('mensagem-falha', 'Valor do desconto igual ou superior ao valor do Produto!');
          return redirect()->route('index');
          }



        $idusuario = Auth::id();      
        $idpedido = Pedido::consultaId([
          'user_id' => $idusuario,                        
           'status'  => 'GE' // Reservado            
                 ]);

        if( empty($idpedido) ) {            
          $pedido_novo = Pedido::create([
            'user_id' => $idusuario,
            'vendedor_id' => $idvendedor,
            'produto_id' => $idproduto,
            'obs_pedido' => $obspedido,
            'id_cliente' => $idcliente,

          'status'  => 'GE' // Gerado 
           ]);

          $idpedido = $pedido_novo->id;          


        }


        Pedido::where([
          'id' => $idpedido,
          'user_id' => $idusuario          
        ])->update([
          'obs_pedido' => $obspedido,
          'produto_id' => $idproduto,
          'vendedor_id'  => $idvendedor         
        ]);

        if (isset($quantidade_prod))
        {


          $contador = 0;


          while($contador < $quantidade_prod)
{

 ItensPedido::create([
          'pedido_id'  => $idpedido,
          'produto_id' => $idproduto,
          'tipo' => 'P',                        
          'prod_preco_balcao' => $produto->prod_preco_balcao,
          'prod_preco_padrao' => $produto->prod_preco_padrao,
          'prod_preco_prof' => $produto->prod_preco_prof, 
          'prod_desconto' => $desconto_produtos,      
          'status'     => 'GE'                   

        ]);
$contador++;
}

        

         $req->session()->flash('mensagem-sucesso', 'Produto adicionado!');

        return redirect()->route('index');

} else {

        ItensPedido::create([
          'pedido_id'  => $idpedido,
          'produto_id' => $idproduto,
          'tipo' => 'P',                        
          'prod_preco_balcao' => $produto->prod_preco_balcao,
          'prod_preco_padrao' => $produto->prod_preco_padrao,
          'prod_preco_prof' => $produto->prod_preco_prof,
          'prod_desconto' => $desconto_produtos,        
          'status'     => 'GE'                   

        ]);

         $req->session()->flash('mensagem-sucesso', 'Produto adicionado!');

        return redirect()->route('index');


}

       



      }

      if ($idproduto == NULL)   
      {  

           if ($desconto_request == NULL)
        {
          $desconto_request = '0.00';
        }                   


        $request = OpycosRequest::find($idrequest);
           if( empty($request->id)) {
          $req->session()->flash('mensagem-falha', 'Escolha algum item.');
          return redirect()->route('index');
        }

        if( $request->request_valor <= $desconto_request) {
          $req->session()->flash('mensagem-falha', 'Valor do desconto igual ou superior ao valor da Requisição!');
          return redirect()->route('index');
          }

                                                                   /* $check_request = OpycosRequest::where([
                                                                      'id' => $idrequest,                        
                                                                      'status' => 'FI'
                                                                    ])->exists();

                                                                    if( !$check_request) {

                                                                      $req->session()->flash('mensagem-falha', 'Requisição ainda não foi finalizada!');
                                                                      return redirect()->route('index');

                                                                    }*/


                                                                    $check_request = OpycosRequest::where([
                                                                      'id' => $idrequest,
                                                                      'id_cliente' => $idcliente                       
                                                                      //'status' => 'FI'
                                                                    ])->exists();

                                                                    if( !$check_request) {

                                                                      $req->session()->flash('mensagem-falha', 'Requisição não pertence ao cliente');
                                                                      return redirect()->route('index');

                                                                    }

                                                                    $idusuario = Auth::id();      
                                                                    $idpedido = Pedido::consultaId([
                                                                      'user_id' => $idusuario,                        
                                                                      'status'  => 'GE' // Reservado            
                                                                    ]);

                                                                    if( empty($idpedido) ) {            
                                                                      $pedido_novo = Pedido::create([
                                                                        'user_id' => $idusuario,
                                                                         //'pedido_cod' => $codpedido,
                                                                        'vendedor_id' => $idvendedor,
                                                                        'obs_pedido' => $obspedido,
                                                                        'id_cliente' => $idcliente,
                                                                        'request_id' => $request->id,
                                                                        'request_valor' => $request->request_valor,
                                                                        'request_desc' => $request->req_desc,
                                                                        'status'  => 'GE' // Gerado 
                                                                      ]);

                                                                      $idpedido = $pedido_novo->id;

                                                                    }


                                                                    Pedido::where([
                                                                      'id' => $idpedido,
                                                                      'user_id' => $idusuario          
                                                                    ])->update([
                                                                      'obs_pedido' => $obspedido,   
                                                                      'request_id' => $idrequest           
                                                                    ]);



                                                                    OpycosRequest::where([
                                                                      'id' => $idrequest,                        
                                                                       // 'status' => 'FI'
                                                                    ])->update([
                                                                      'status' => 'RE' // Reservado no pedido
                                                                    ]);

                                                                            if (isset($quantidade_req))
                                                                            {


                                                                              $contador = 0;


                                                                              while($contador < $quantidade_req)
                                                                    {

                                                                        ItensPedido::create([
                                                                      'pedido_id'  => $idpedido,
                                                                      'request_id' => $idrequest,  
                                                                      'tipo' => 'R',                      
                                                                      //'prod_preco_balcao' => $produto->prod_preco_balcao,
                                                                      'prod_preco_padrao' => $request->request_valor,
                                                                      'request_desconto' => $desconto_request,
                                                                     // 'prod_preco_prof' => $produto->prod_preco_prof,        
                                                                      'status'     => 'GE'                   

                                                                    ]);

                                                                    $contador++;
                                                                    }

                                                                            

                                                                             $req->session()->flash('mensagem-sucesso', 'Requisição adicionada!');

                                                                            return redirect()->route('index');

                                                                    } else {

                                                                              ItensPedido::create([
                                                                      'pedido_id'  => $idpedido,
                                                                      'request_id' => $idrequest,  
                                                                      'tipo' => 'R',                      
                                                                      //'prod_preco_balcao' => $produto->prod_preco_balcao,
                                                                      'prod_preco_padrao' => $request->request_valor,
                                                                      'request_desconto' => $desconto_request,
                                                                     // 'prod_preco_prof' => $produto->prod_preco_prof,        
                                                                      'status'     => 'GE'                   

                                                                    ]);

                                                                             $req->session()->flash('mensagem-sucesso', 'Requisição adicionada!');

                                                                            return redirect()->route('index');


                                                                    }


                                                                

                                                                 //   $req->session()->flash('mensagem-sucesso', 'Requisição adicionada!');

                                                                 //   return redirect()->route('index');


                                                                  }


                                                                }





public function adicionarEdit($id) 
        {

         $this->middleware('VerifyCsrfToken');
         $req = Request();        
         $obspedido = $req->input('obs_pedido');
         $idcliente = $req->input('id_cliente');
         $idproduto = $req->input('id');
       //$retirada = $req->input('balcao');
       //$frete = $req->input('entrega');
         $idrequest = $req->input('request_cod');  
         $quantidade =  $req->input('quantidade');
       //$valor = $req->input('valor');
     //  $valor = str_replace( ',', '.', $valor );
         $tip = $req->input('boolean');

         $validator = validator($req->all(),
          [
            'id_cliente' => 'required'
          ]);

         if ( $validator->fails()){
          $req->session()->flash('mensagem-falha', 'É preciso escolher um cliente.');
          return redirect()->route('pedidos/{id}/edit', $id)->withInput();
        }


        if (isset($idrequest) && isset($idproduto))   
        {   

         $req->session()->flash('mensagem-falha', 'Escolha um item por vez');
         return redirect()->route('pedidos/{id}/edit', $id)->withInput();

       }


       if ($idrequest == NULL)   
       {                        

         $produto = Produto::find($idproduto);

         if( empty($produto->id)) {
          $req->session()->flash('mensagem-falha', 'Quando houver requisição destinada ao cliente, então será possível adicionar ao pedido, caso contrário, selecione um produto da lista.');
          return redirect()->route('pedidos/{id}/edit', $id);
        }

       $idusuario = Auth::id();      
       $idpedido = Pedido::findOrFail($id);

      


      /*  Pedido::where([
          'id' => $idpedido,
          'user_id' => $idusuario          
        ])->update([
          'obs_pedido' => $obspedido,
          'produto_id' => $idproduto             
        ]);*/

        if (isset($quantidade))
        {

          $contador = 0;


          while($contador < $quantidade)
{

 ItensPedido::create([
          'pedido_id'  => $id,
          'produto_id' => $idproduto,
          'tipo' => 'P',                        
          'prod_preco_balcao' => $produto->prod_preco_balcao,
          'prod_preco_padrao' => $produto->prod_preco_padrao,
          'prod_preco_prof' => $produto->prod_preco_prof,        
          'status'     => 'GE'                   

        ]);
$contador++;
}

        

         $req->session()->flash('mensagem-sucesso', 'Produto adicionado!');

        return redirect()->route('pedidos/{id}/edit', $id);

} else {

        ItensPedido::create([
          'pedido_id'  => $id,
          'produto_id' => $idproduto,
          'tipo' => 'P',                        
          'prod_preco_balcao' => $produto->prod_preco_balcao,
          'prod_preco_padrao' => $produto->prod_preco_padrao,
          'prod_preco_prof' => $produto->prod_preco_prof,        
          'status'     => 'GE'                   

        ]);

         $req->session()->flash('mensagem-sucesso', 'Produto adicionado!');

        return redirect()->route('pedidos/{id}/edit', $id);


}

       



      }

      if ($idproduto == NULL)   
      {  

        $request = OpycosRequest::find($idrequest);

                                                                   /* $check_request = OpycosRequest::where([
                                                                      'id' => $idrequest,                        
                                                                      'status' => 'FI'
                                                                    ])->exists();

                                                                    if( !$check_request) {

                                                                      $req->session()->flash('mensagem-falha', 'Requisição ainda não foi finalizada!');
                                                                      return redirect()->route('index');

                                                                    }*/


                                                                    $check_request = OpycosRequest::where([
                                                                      'id' => $idrequest,
                                                                      'id_cliente' => $idcliente                       
                                                                      //'status' => 'FI'
                                                                    ])->exists();

                                                                    if( !$check_request) {

                                                                      $req->session()->flash('mensagem-falha', 'Requisição não pertence ao cliente');
                                                                      return redirect()->route('pedidos/{id}/edit', $id);

                                                                    }

                                                                    $idusuario = Auth::id();      
                                                                    $idpedido = Pedido::findOrFail($id);                                                        


                                                                 /*   Pedido::where([
                                                                      'id' => $idpedido,
                                                                      'user_id' => $idusuario          
                                                                    ])->update([
                                                                      'obs_pedido' => $obspedido,   
                                                                      'request_id' => $idrequest           
                                                                    ]);*/



                                                                    OpycosRequest::where([
                                                                      'id' => $idrequest,                        
                                                                       // 'status' => 'FI'
                                                                    ])->update([
                                                                      'status' => 'RE' // Reservado no pedido
                                                                    ]);

                                                                            if (isset($quantidade))
                                                                            {


                                                                              $contador = 0;


                                                                              while($contador < $quantidade)
                                                                    {

                                                                        ItensPedido::create([
                                                                      'pedido_id'  => $id,
                                                                      'request_id' => $idrequest,  
                                                                      'tipo' => 'R',                      
                                                                      //'prod_preco_balcao' => $produto->prod_preco_balcao,
                                                                      'prod_preco_padrao' => $request->request_valor,
                                                                     // 'prod_preco_prof' => $produto->prod_preco_prof,        
                                                                      'status'     => 'GE'                   

                                                                    ]);

                                                                    $contador++;
                                                                    }

                                                                            

                                                                             $req->session()->flash('mensagem-sucesso', 'Requisição adicionada!');

                                                                            return redirect()->route('pedidos/{id}/edit', $id);

                                                                    } else {

                                                                              ItensPedido::create([
                                                                      'pedido_id'  => $id,
                                                                      'request_id' => $idrequest,  
                                                                      'tipo' => 'R',                      
                                                                      //'prod_preco_balcao' => $produto->prod_preco_balcao,
                                                                      'prod_preco_padrao' => $request->request_valor,
                                                                     // 'prod_preco_prof' => $produto->prod_preco_prof,        
                                                                      'status'     => 'GE'                   

                                                                    ]);

                                                                             $req->session()->flash('mensagem-sucesso', 'Requisição adicionada!');

                                                                            return redirect()->route('pedidos/{id}/edit', $id);


                                                                    }


                                                                

                                                                  }


                                                                }






     public function statusEdit($id)
          
          {
             $pedidos = Pedido::findOrFail($id);
             $this->middleware('VerifyCsrfToken');
             $req = Request();
             $status = $req->input('status');             

            $check_pedido = Pedido::where([
            'id'      => $id,
            //'user_id' => $idusuario,
           // 'status'  => 'GE' // Gerado
             ])->exists();
              if( !$check_pedido ) {
             $req->session()->flash('mensagem-falha', 'Pedido não encontrado!');
             return redirect()->route('pedido.compras');
             }  


                    if( $status == 'RE' ) {

                 Pedido::where([
               // 'user_id' => $idusuario,
                //  'pedido_cod' => $codpedido,
                'id' => $id
               // 'status'  => 'GE'
              ])->update([
                'status' => $status
               
               ]); 

                 $req->session()->flash('mensagem-sucesso', 'Pedido aberto para alterações.');
                return redirect()->route('pedidos/{id}/edit', $id);


               }

            
               if( $status == 'AP' ) {

                 Pedido::where([
               // 'user_id' => $idusuario,
                //  'pedido_cod' => $codpedido,
                'id' => $id
               // 'status'  => 'GE'
              ])->update([
                'status' => $status
               
               ]); 

                 $req->session()->flash('mensagem-sucesso', 'Pedido Aguardando confirmação de pagamento.');
                return redirect()->route('pedido.compras');


               }

               if( $status == 'EL' ) {

                 Pedido::where([
               // 'user_id' => $idusuario,
                //  'pedido_cod' => $codpedido,
                'id' => $id
               // 'status'  => 'GE'
              ])->update([
                'status' => $status
               
               ]); 

                 $req->session()->flash('mensagem-sucesso', 'Pedido Encaminhado ao Laboratório.');
                return redirect()->route('pedido.compras');


               }


                if( $status == 'EC' ) {

                 Pedido::where([
               // 'user_id' => $idusuario,
                //  'pedido_cod' => $codpedido,
                'id' => $id
               // 'status'  => 'GE'
              ])->update([
                'status' => $status
               
               ]); 

                 $req->session()->flash('mensagem-sucesso', 'Pedido Enviado ao cliente.');
                return redirect()->route('pedido.compras');


               }


   

             if( $status == 'FI') {

                 Pedido::where([
               // 'user_id' => $idusuario,
                //  'pedido_cod' => $codpedido,
                'id' => $id
               // 'status'  => 'GE'
              ])->update([
                'status' => $status
               
               ]); 

                 $req->session()->flash('mensagem-sucesso', 'Pedido Entregue ao cliente. Finalizado com sucesso!');
                return redirect()->route('pedido.compras');


               }

                if( $status == 'CA') {

                 Pedido::where([
               // 'user_id' => $idusuario,
                //  'pedido_cod' => $codpedido,
                'id' => $id
               // 'status'  => 'GE'
              ])->update([
                'status' => $status
               
               ]); 

                $req->session()->flash('mensagem-sucesso', 'Pedido Cancelado pelo Cliente.');
                return redirect()->route('pedido.compras');


               }



       
       }





                                                                public function remover()
                                                                {

                                                                  $this->middleware('VerifyCsrfToken');

                                                                  $req = Request();
                                                                  $idpedido           = $req->input('pedido_id');
                                                                  $idrequest          = $req->input('request_cod');
                                                                  $idproduto          = $req->input('produto_id');
                                                                  $remove_apenas_item = (boolean)$req->input('item');
                                                                  $idusuario          = Auth::id();

                                                                  $idpedido = Pedido::consultaId([
                                                                    'id'      => $idpedido,
                                                                    'user_id' => $idusuario,
                                                                      'status'  => 'GE' // Reservada
                                                                    ]);

                                                                  if( empty($idpedido) ) {
                                                                    $req->session()->flash('mensagem-falha', 'Pedido não encontrado!');
                                                                    return redirect()->route('index');
                                                                  }


                                                                  if ($idrequest == NULL)   
                                                                  {  


                                                                    $where_produto = [
                                                                      'pedido_id'  => $idpedido,
                                                                      'produto_id' => $idproduto
                                                                    ];

                                                                    $produto = ItensPedido::where($where_produto)->orderBy('id', 'desc')->first();
                                                                    if( empty($produto->id) ) {
                                                                      $req->session()->flash('mensagem-falha', 'Produto não encontrado no pedido!');
                                                                      return redirect()->route('index');
                                                                    }

                                                                    if( $remove_apenas_item ) {
                                                                      $where_produto['id'] = $produto->id;

                                                                    }


                                                                    ItensPedido::where($where_produto)->delete();




                                                                    $check_pedido = ItensPedido::where([
                                                                      'pedido_id' => $produto->pedido_id
                                                                    ])->exists();

                                                                    if( !$check_pedido ) {
                                                                      Pedido::where([
                                                                        'id' => $produto->pedido_id
                                                                      ])->delete();

                                                                      Pedido::where([
                                                                        'id' => $produto->pedido_id,

                                                                      ])->update([
                                                                        'produto_id' => NULL           
                                                                      ]);

                                                                    }

                                                                    $req->session()->flash('mensagem-sucesso', 'Produto removido do pedido com sucesso!');

                                                                    return redirect()->route('index');


                                                                  }

                                                                  if ($idproduto == NULL)   
                                                                  {  

                                                                    $where_request = [
                                                                      'pedido_id'  => $idpedido,
                                                                      'request_id' => $idrequest
                                                                    ];

                                                                    $request = ItensPedido::where($where_request)->orderBy('id', 'desc')->first();
                                                                    if( empty($request->id) ) {
                                                                      $req->session()->flash('mensagem-falha', 'Requisição não localizada!');
                                                                      return redirect()->route('index');
                                                                    }


                                                                    if( $remove_apenas_item ) {
                                                                      $where_request['id'] = $request->id;


                                                                    }

                                                                    ItensPedido::where($where_request)->delete();


                                                                    $check_pedido = ItensPedido::where([
                                                                      'pedido_id' => $request->pedido_id
                                                                    ])->exists();

                                                                    if( !$check_pedido ) {
                                                                      Pedido::where([
                                                                        'id' => $request->pedido_id
                                                                      ])->delete();

                                                                      

                                                                    }

                                                                    OpycosRequest::where([
                                                                      'id' => $idrequest                                             

                                                                    ])->update([
                                                                      'status' => 'FI' // Finalizado
                                                                    ]);

                                                                    $req->session()->flash('mensagem-sucesso', 'Requisição removida do pedido com sucesso!');

                                                                    return redirect()->route('index');


                                                                  }


                                                                }


                                                                 public function removerEdit($id)
                                                                {

                                                                  $this->middleware('VerifyCsrfToken');

                                                                  $req = Request();
                                                                 // $idpedido           = $req->input('pedido_id');
                                                                  $idrequest          = $req->input('request_cod');
                                                                  $idproduto          = $req->input('produto_id');
                                                                 // dd($idproduto);
                                                                  $remove_apenas_item = (boolean)$req->input('item');                                                                  
                                                                  $idusuario          = Auth::id();

                                                                 $idpedido = Pedido::findOrFail($id);

                                                                 /* if( empty($idpedido) ) {
                                                                    $req->session()->flash('mensagem-falha', 'Pedido não encontrado!');
                                                                    return redirect()->route('pedidos/{id}/edit', $id);
                                                                  }*/


                                                                  if ($idrequest == NULL)   
                                                                  {  


                                                                    $where_produto = [
                                                                      'pedido_id'  => $id,
                                                                      'produto_id' => $idproduto
                                                                    ];

                                                                    $produto = ItensPedido::where($where_produto)->orderBy('id', 'desc')->first();
                                                                    if( empty($produto->id) ) {
                                                                      $req->session()->flash('mensagem-falha', 'Produto não encontrado no pedido!');
                                                                      return redirect()->route('pedidos/{id}/edit', $id);
                                                                    }

                                                                    if( $remove_apenas_item ) {
                                                                      $where_produto['id'] = $produto->id;

                                                                    }


                                                                    ItensPedido::where($where_produto)->delete();




                                                                    $check_pedido = ItensPedido::where([
                                                                      'pedido_id' => $produto->pedido_id
                                                                    ])->exists();

                                                                    if( !$check_pedido ) {
                                                                    /*  Pedido::where([
                                                                        'id' => $produto->pedido_id
                                                                      ])->delete();*/

                                                                      Pedido::where([
                                                                        'id' => $produto->pedido_id,

                                                                      ])->update([
                                                                        'produto_id' => NULL           
                                                                      ]);

                                                                    }

                                                                    $req->session()->flash('mensagem-sucesso', 'Produto removido do pedido com sucesso!');

                                                                    return redirect()->route('pedidos/{id}/edit', $id);


                                                                  }

                                                                  if ($idproduto == NULL)   
                                                                  {  

                                                                    $where_request = [
                                                                      'pedido_id'  => $id,
                                                                      'request_id' => $idrequest
                                                                    ];

                                                                    $request = ItensPedido::where($where_request)->orderBy('id', 'desc')->first();
                                                                    if( empty($request->id) ) {
                                                                      $req->session()->flash('mensagem-falha', 'Requisição não localizada!');
                                                                      return redirect()->route('pedidos/{id}/edit', $id);
                                                                    }


                                                                    if( $remove_apenas_item ) {
                                                                      $where_request['id'] = $request->id;
                                                                    }

                                                                    ItensPedido::where($where_request)->delete();


                                                                 /*   $check_pedido = ItensPedido::where([
                                                                      'pedido_id' => $request->pedido_id
                                                                    ])->exists();

                                                                    if( !$check_pedido ) {
                                                                      Pedido::where([
                                                                        'id' => $request->pedido_id
                                                                      ])->delete();

                                                                      

                                                                    }*/

                                                                    OpycosRequest::where([
                                                                      'id' => $idrequest                                             

                                                                    ])->update([
                                                                      'status' => 'FI' // Finalizado
                                                                    ]);

                                                                    $req->session()->flash('mensagem-sucesso', 'Requisição removida do pedido com sucesso!');

                                                                    return redirect()->route('pedidos/{id}/edit', $id);


                                                                  }


                                                                }



                                                                public function detalhes($id) {






                                                                  $pedidos = Pedido::findOrFail($id);
                                                                  $this->middleware('VerifyCsrfToken');
                                                                  $req = Request(); 
                                                                  $local = $req->input('local');   
                                                                  $cep = $req->input('cep');// CEP de destino lterado
                                                                  $endereço = $req->input('endereço');
                                                                  $numero = $req->input('numero');
                                                                  $bairro = $req->input('bairro');
                                                                  $complemento = $req->input('complemento');
                                                                  $cidade = $req->input('cidade');
                                                                  $estado = $req->input('estado');   
                                                                  $obspedido = $req->input('obs_pedido');
                                                                  $idcliente = $req->input('id_cliente');
                                                                  $idvendedor = $req->input('vendedor_id');
                                                                  $idusuario = $req->input('user_id');

                                                                  $prazoentrega = $req->input('prazo_entrega');
                                                                  $prazoentrega = str_replace( ' ', '', str_replace('Dias', '', $prazoentrega));
                                                                 // $prazoentrega = $prazoentrega + 2;
                                                                  $cdservico = $req->input('cdservico');

                                                                 // $idproduto = $req->input('id');
                                                                  $retirada = $req->input('balcao');                                                                  
                                                                  $frete = $req->input('entrega');
                                                                 // $status = $req->input('status');
                                                                  $valor = $req->input('valor');
                                                                  $valor = str_replace( ',', '.', $valor );
                                                                  $pagamento = $req->input('pagamento');
                                                                  $cdservico = $req->input('cdservico');
                                                                  $idusuario = Auth::id(); 
                                                                 // $cepOrigem = "09090520"; //CEP de Origem!!!!!!!!!


                                                                /* $produto = Produto::find($idproduto);
                                                                if( empty($produto->id) ) {
                                                                    $req->session()->flash('mensagem-falha', 'Produto não encontrado em nossa loja!');
                                                                    return redirect()->route('pedidos.index');
                                                                  }*/



                                                                  
                                                               //  $check_cep = Cliente::select('cep')->where('id', '=', $idcliente)->get(); //CEP de destino!!!!!!!!!


                                                                  $check_pedido = Pedido::where([
                                                                    'id'      => $id,
                                                                   // 'user_id' => $idusuario,
                                                                    'status'  => 'GE' // Gerado
                                                                  ])->exists();

                                                                  if( !$check_pedido ) {
                                                                    $req->session()->flash('mensagem-falha', 'Pedido não encontrado!');
                                                                    return redirect()->route('index');
                                                                  }                                                                 


                                                                  $idpedido = Pedido::consultaId([
                                                                    'id' => $id,                        
                                                                    'status'  => 'GE' // GERADO            
                                                                  ]);

                                                                  if( isset($idpedido) ) {   

                                                                    if( empty($pagamento) ) {
                                                                  //  dd($pagamento);
                                                                      $req->session()->flash('message', 'Preencha a forma de Pagamento!');
                                                                      return redirect()->route('index')->withInput();
                                                                    }

                                                                    Pedido::where([
                                                                      'user_id' => $idusuario,
                                                                      //  'pedido_cod' => $codpedido,
                                                                      'id' => $id,
                                                                      'status'  => 'GE'
                                                                    ])->update([
                                                                      'pagamento' => $pagamento, 
                                                                      'vendedor_id' => $idvendedor,
                                                                       'obs_pedido' => $obspedido
                                                    
                                                                    ]);

                                                                } /*END ISSET PEDIDO*/




    
                                                                    if ($retirada == NULL && $frete == NULL)
                                                                    {
                                                                      $req->session()->flash('message', 'Informe o tipo de frete!');
                                                                      return redirect()->route('index')->withInput();

                                                                    }

                                                                


                                                                  if ($frete == "B") 
                                                                  {

                                                                    if ($valor == NULL) {
                                                                      $req->session()->flash('message', 'Informar o custo do frete!');
                                                                      return redirect()->route('index')->withInput();
                                                                    }


                                                                    if (isset($local))
                                                                    {

                                                                      Frete::create([
                                                                        'pedido_id'  => $id,
                                                                      // 'produto_id' => $idproduto,
                                                                        'local' => $local,
                                                                        'cep' =>        $cep,
                                                                        'endereço' =>   $endereço,
                                                                        'numero' =>  $numero,
                                                                        'bairro' =>  $bairro,
                                                                        'complemento' =>  $complemento,
                                                                        'cidade' =>  $cidade,
                                                                        'estado' =>  $estado,
                                                                        'id_cliente' =>  $idcliente,
                                                                        'user_id' =>   $idusuario,
                                                                        'vendedor_id' => $idvendedor,
                                                                        'boolean' => 'Y',
                                                                        'balcao' => NULL,
                                                                       'entrega' => 'B', //Boy "Moto Booy"
                                                                       'valor' => $valor,
                                                                     //  'prazo_entrega' => $prazoentrega,
                                                                       'status' => 'EMB' //Entrega moto boy        

                                                                     ]);

                                                                     Pedido::where([
                                                                      'user_id' => $idusuario,
                                                                      //  'pedido_cod' => $codpedido,
                                                                      'id' => $id,
                                                                     // 'status'  => 'GE'
                                                                    ])->update([
                                                                      'status' => 'RE' //REservado
                                                                    ]);



                                                                      $req->session()->flash('mensagem-sucesso', 'Pedido Salvo com sucesso!');

                                                                      return redirect()->route('pedido.compras');

                                                                    }

                                                                    Frete::create([
                                                                      'pedido_id'  => $id,
                                                                      // 'produto_id' => $idproduto,
                                                                      'id_cliente' =>  $idcliente,
                                                                      'user_id' =>   $idusuario,
                                                                      'vendedor_id' => $idvendedor,
                                                                      'boolean' => 'Y',
                                                                      'balcao' => NULL,
                                                                     'entrega' => 'B', //Boy "Moto Booy"
                                                                     'valor' => $valor,
                                                                   //  'prazo_entrega' => $prazoentrega,
                                                                     'status' => 'EMB' //Entrega moto boy        

                                                                   ]);

                                                                     Pedido::where([
                                                                      'user_id' => $idusuario,
                                                                      //  'pedido_cod' => $codpedido,
                                                                      'id' => $id,
                                                                     // 'status'  => 'GE'
                                                                    ])->update([
                                                                      'status' => 'RE' //REservado
                                                                    ]);




                                                                    $req->session()->flash('mensagem-sucesso', 'Pedido Salvo com sucesso!');

                                                                    return redirect()->route('pedido.compras');



                                                                  }


                                                                  if ($frete == "C") 
                                                                  {

                                                                             if ($valor == NULL) {
                                                                              $req->session()->flash('message', 'Informar o custo do frete!');
                                                                              return redirect()->route('index')->withInput();


                                                                            }


                                                                            if (isset($local))

                                                                            {

                                                                              Frete::create([
                                                                                'pedido_id'  => $id,
                                                                                // 'produto_id' => $idproduto,
                                                                                'local' => $local,
                                                                                'cep' =>        $cep,
                                                                                'endereço' =>   $endereço,
                                                                                'numero' =>  $numero,
                                                                                'bairro' =>  $bairro,
                                                                                'complemento' =>  $complemento,
                                                                                'cidade' =>  $cidade,
                                                                                'estado' =>  $estado,
                                                                                'id_cliente' =>  $idcliente,
                                                                                'user_id' =>   $idusuario,
                                                                                'vendedor_id' => $idvendedor,
                                                                                'boolean' => 'Y',
                                                                                'balcao' => NULL,
                                                                                 'entrega' => 'B', //Boy "Moto Booy"
                                                                                 'valor' => $valor,
                                                                                 'prazo_entrega' => $prazoentrega,
                                                                                 'serviço_correio' => $cdservico,
                                                                                 'status' => 'EMB' //Entrega moto boy        

                                                                               ]);

                                                                               Pedido::where([
                                                                                'user_id' => $idusuario,
                                                                                //  'pedido_cod' => $codpedido,
                                                                                'id' => $id,
                                                                               // 'status'  => 'GE'
                                                                              ])->update([
                                                                                'status' => 'RE' //REservado
                                                                              ]);

                                                                              $req->session()->flash('mensagem-sucesso', 'Pedido Salvo com sucesso!');

                                                                              return redirect()->route('pedido.compras');

                                                                            }



                                                                              Frete::create([
                                                                                'pedido_id'  => $id,
                                                                                
                                                                               // 'produto_id' => $idproduto,                                                                  

                                                                                'id_cliente' =>  $idcliente,
                                                                                'user_id' =>   $idusuario,
                                                                                'vendedor_id' => $idvendedor,
                                                                                'boolean' => 'Y',
                                                                                'balcao' => NULL,
                                                                               'entrega' => 'C', //Correios
                                                                               'valor' => $valor,
                                                                               'prazo_entrega' => $prazoentrega,
                                                                                'serviço_correio' => $cdservico,
                                                                               'status' => 'EC' //Entrega Correios

                                                                             ]);

                                                                                   Pedido::where([
                                                                                'user_id' => $idusuario,
                                                                                //  'pedido_cod' => $codpedido,
                                                                                'id' => $id,
                                                                               // 'status'  => 'GE'
                                                                              ])->update([
                                                                                'status' => 'RE' //REservado
                                                                              ]);

                                                                              $req->session()->flash('mensagem-sucesso', 'Pedido Salvo com sucesso!');

                                                                              return redirect()->route('pedido.compras');


                                                                }






                                                                if ($frete == NULL && $retirada == 'Y' ) {

                                                                  Frete::create([
                                                                    'pedido_id'  => $id,
                                                                   // 'produto_id' => $idproduto,
                                                                    'id_cliente' =>  $idcliente,
                                                                    'user_id' =>   $idusuario,
                                                                    'vendedor_id' => $idvendedor,
                                                                    'boolean' => 'Y',
                                                                    'balcao' => 'Y',
                                                                    'entrega' => NULL,
                                                                   'status' => 'AR' //Aguardando Retirada 

                                                                 ]);

                                                                       Pedido::where([
                                                                                'user_id' => $idusuario,
                                                                                //  'pedido_cod' => $codpedido,
                                                                                'id' => $id,
                                                                               // 'status'  => 'GE'
                                                                              ])->update([
                                                                                'status' => 'RE' //REservado
                                                                              ]);

                                                                  
                                                                  $req->session()->flash('mensagem-sucesso', 'Pedido Salvo com sucesso!');

                                                                  return redirect()->route('pedido.compras');



                                                                }






                                                              }



     /*   public function findRequisitionsNameInt(Request $request)
    {   
        
      $data=OpycosRequest::select('id_cliente', 'id', 'request_cod', 'request_desc', 'status')->where('id_cliente', $request->id)->where('status', '=', 'FI')->take(100)->get();

        //if our chosen id and products table prod_cat_id
       // $request->id here is the id of our chosen option id

        return response()->json($data);
    }*/




               public function detalhesEdit($id) {






                                                                  $pedidos = Pedido::findOrFail($id);
                                                                  $this->middleware('VerifyCsrfToken');
                                                                  $req = Request(); 
                                                                  $local = $req->input('local');   
                                                                  $cep = $req->input('cep');// CEP de destino lterado
                                                                  $endereço = $req->input('endereço');
                                                                  $numero = $req->input('numero');
                                                                  $bairro = $req->input('bairro');
                                                                  $complemento = $req->input('complemento');
                                                                  $cidade = $req->input('cidade');
                                                                  $estado = $req->input('estado');   
                                                                  $obspedido = $req->input('obs_pedido');
                                                                  $idcliente = $req->input('id_cliente');
                                                                   $idvendedor = $req->input('vendedor_id');
                                                                  $idusuario = $req->input('user_id');

                                                                  $prazoentrega = $req->input('prazo_entrega');
                                                                  $prazoentrega = str_replace( ' ', '', str_replace('Dias', '', $prazoentrega));
                                                                 // $prazoentrega = $prazoentrega + 2;
                                                                  $cdservico = $req->input('cdservico');

                                                                 // $idproduto = $req->input('id');
                                                                  $retirada = $req->input('balcao');                                                                  
                                                                  $frete = $req->input('entrega');
                                                                 // $status = $req->input('status');
                                                                  $valor = $req->input('valor');
                                                                  $valor = str_replace( ',', '.', $valor );
                                                                  $pagamento = $req->input('pagamento');
                                                                  $cdservico = $req->input('cdservico');
                                                                  $idusuario = Auth::id(); 
                                                                 /* $dataAtual = $req->input('dataAtual');
                                                                  $dataRegistro = $req->input('dataRegistro');*/
                                                                 // dd($valor);
                                                                 // $cepOrigem = "09090520"; //CEP de Origem!!!!!!!!!


                                                                /* $produto = Produto::find($idproduto);
                                                                if( empty($produto->id) ) {
                                                                    $req->session()->flash('mensagem-falha', 'Produto não encontrado em nossa loja!');
                                                                    return redirect()->route('pedidos.index');
                                                                  }*/



                                                                  
                                                               //  $check_cep = Cliente::select('cep')->where('id', '=', $idcliente)->get(); //CEP de destino!!!!!!!!!

                                                                    /*   if ($dataAtual > $dataRegistro){
                                                                    $req->session()->flash('mensagem-falha', 'Pedido não pode ser alterado!');
                                                                    return redirect()->route('pedidos/{id}/edit', $id);
                                                                  }*/


                                                                  $check_pedido = Pedido::where([
                                                                    'id'      => $id,
                                                                  //  'user_id' => $idusuario,
                                                                    'status'  => 'RE' // Reservado
                                                                  ])->exists();

                                                                  if( !$check_pedido ) {
                                                                    $req->session()->flash('mensagem-falha', 'Pedido não encontrado!');
                                                                    return redirect()->route('pedidos/{id}/edit', $id);
                                                                  }                                                                 


                                                                  $idpedido = Pedido::consultaId([
                                                                    'id' => $id,                        
                                                                    'status'  => 'RE' // GERADO            
                                                                  ]);

                                                                  if( isset($idpedido) ) {   

                                                                    if( empty($pagamento) ) {
                                                                  //  dd($pagamento);
                                                                      $req->session()->flash('message', 'Preencha a forma de Pagamento!');
                                                                      return redirect()->route('pedidos/{id}/edit', $id)->withInput();
                                                                    }

                                                                    Pedido::where([
                                                                     // 'user_id' => $idusuario,
                                                                      //  'pedido_cod' => $codpedido,
                                                                      'id' => $id,
                                                                      'status'  => 'RE'
                                                                    ])->update([
                                                                      'pagamento' => $pagamento, 
                                                                    //  'vendedor_id' => $idvendedor,
                                                                       'obs_pedido' => $obspedido
                                                    
                                                                    ]);

                                                                } /*END ISSET PEDIDO*/




    
                                                                    if ($retirada == NULL && $frete == NULL)
                                                                    {
                                                                      $req->session()->flash('message', 'Informe o tipo de frete!');
                                                                      return redirect()->route('pedidos/{id}/edit', $id)->withInput();

                                                                    }

                                                                


                                                                  if ($frete == "B") 
                                                                  {

                                                                    if ($valor == NULL) {
                                                                      $req->session()->flash('message', 'Informar o custo do frete!');
                                                                      return redirect()->route('pedidos/{id}/edit', $id)->withInput();
                                                                    }


                                                                    if (isset($local))
                                                                    {

                                                                      Frete::where([
                                                                        'pedido_id'  => $id                                                                       

                                                                     ])->update([
                                                                      // 'produto_id' => $idproduto,
                                                                        'local' => $local,
                                                                        'cep' =>        $cep,
                                                                        'endereço' =>   $endereço,
                                                                        'numero' =>  $numero,
                                                                        'bairro' =>  $bairro,
                                                                        'complemento' =>  $complemento,
                                                                        'cidade' =>  $cidade,
                                                                        'estado' =>  $estado,
                                                                        'id_cliente' =>  $idcliente,
                                                                        'user_id' =>   $idusuario,
                                                                        'boolean' => 'Y',
                                                                        'balcao' => NULL,
                                                                       'entrega' => 'B', //Boy "Moto Booy"
                                                                       'valor' => $valor,
                                                                       'prazo_entrega' => $prazoentrega,
                                                                       'status' => 'EMB' //Entrega moto boy      
                                                    
                                                                    ]);

                                                                     Pedido::where([
                                                                    //  'user_id' => $idusuario,
                                                                      //  'pedido_cod' => $codpedido,
                                                                      'id' => $id
                                                                     // 'status'  => 'GE'
                                                                    ])->update([
                                                                      'status' => 'RE' //REservado
                                                                    ]);



                                                                      $req->session()->flash('mensagem-sucesso', 'Pedido Salvo com sucesso!');

                                                                      return redirect()->route('pedido.compras');

                                                                    }

                                                                    Frete::where([
                                                                      'pedido_id'  => $id
                                                                    
                                                                   ])->update([
                                                                      // 'produto_id' => $idproduto,
                                                                      'id_cliente' =>  $idcliente,
                                                                      'user_id' =>   $idusuario,
                                                                      'boolean' => 'Y',
                                                                      'balcao' => NULL,
                                                                     'entrega' => 'B', //Boy "Moto Booy"
                                                                     'valor' => $valor,
                                                                     'prazo_entrega' => $prazoentrega,
                                                                     'status' => 'EMB' //Entrega moto boy        


                                                                   ]);

                                                                     Pedido::where([
                                                                     // 'user_id' => $idusuario,
                                                                      //  'pedido_cod' => $codpedido,
                                                                      'id' => $id
                                                                     // 'status'  => 'GE'
                                                                    ])->update([
                                                                      'status' => 'RE' //REservado
                                                                    ]);




                                                                    $req->session()->flash('mensagem-sucesso', 'Pedido Salvo com sucesso!');

                                                                    return redirect()->route('pedido.compras');



                                                                  }


                                                                  if ($frete == "C") 
                                                                  {

                                                                             if ($valor == NULL) {
                                                                              $req->session()->flash('message', 'Informar o custo do frete!');
                                                                              return redirect()->route('index')->withInput();


                                                                            }


                                                                            if (isset($local))

                                                                            {

                                                                              Frete::where([
                                                                                'pedido_id'  => $id
                                                                                    

                                                                               ])->update([
                                                                                 // 'produto_id' => $idproduto,
                                                                                'local' => $local,
                                                                                'cep' =>        $cep,
                                                                                'endereço' =>   $endereço,
                                                                                'numero' =>  $numero,
                                                                                'bairro' =>  $bairro,
                                                                                'complemento' =>  $complemento,
                                                                                'cidade' =>  $cidade,
                                                                                'estado' =>  $estado,
                                                                                'id_cliente' =>  $idcliente,
                                                                                'user_id' =>   $idusuario,
                                                                                'boolean' => 'Y',
                                                                                'balcao' => NULL,
                                                                                 'entrega' => 'B', //Boy "Moto Booy"
                                                                                 'valor' => $valor,
                                                                                 'prazo_entrega' => $prazoentrega,
                                                                                 'serviço_correio' => $cdservico,
                                                                                 'status' => 'EMB' //Entrega moto boy   


                                                                               ]);

                                                                               Pedido::where([
                                                                                'user_id' => $idusuario,
                                                                                //  'pedido_cod' => $codpedido,
                                                                                'id' => $id,
                                                                               // 'status'  => 'GE'
                                                                              ])->update([
                                                                                'status' => 'RE' //REservado
                                                                              ]);

                                                                              $req->session()->flash('mensagem-sucesso', 'Pedido Salvo com sucesso!');

                                                                              return redirect()->route('pedido.compras');

                                                                            }



                                                                              Frete::where([
                                                                                'pedido_id'  => $id,
                                                                                
                                                                             
                                                                             ])->update([
                                                                                // 'produto_id' => $idproduto,                                                                  

                                                                                'id_cliente' =>  $idcliente,
                                                                                'user_id' =>   $idusuario,
                                                                                'boolean' => 'Y',
                                                                                'balcao' => NULL,
                                                                               'entrega' => 'C', //Correios
                                                                               'valor' => $valor,
                                                                               'prazo_entrega' => $prazoentrega,
                                                                                'serviço_correio' => $cdservico,
                                                                               'status' => 'EC' //Entrega Correios

                                                                             ]);

                                                                                   Pedido::where([
                                                                                'user_id' => $idusuario,
                                                                                //  'pedido_cod' => $codpedido,
                                                                                'id' => $id,
                                                                               // 'status'  => 'GE'
                                                                              ])->update([
                                                                                'status' => 'RE' //REservado
                                                                              ]);

                                                                              $req->session()->flash('mensagem-sucesso', 'Pedido Salvo com sucesso!');

                                                                              return redirect()->route('pedido.compras');


                                                                }






                                                                if ($frete == NULL && $retirada == 'Y' ) {

                                                                  Frete::where([
                                                                    'pedido_id'  => $id
                                                                   
                                                                 ])->update([
                                                                  // 'produto_id' => $idproduto,
                                                                    'id_cliente' =>  $idcliente,
                                                                    'user_id' =>   $idusuario,
                                                                    'boolean' => 'Y',
                                                                    'balcao' => 'Y',
                                                                    'entrega' => NULL,
                                                                    'valor' => NULL,
                                                                     'status' => 'AR' //Aguardando Retirada 



                                                                 ]);

                                                                       Pedido::where([
                                                                                'user_id' => $idusuario,
                                                                                //  'pedido_cod' => $codpedido,
                                                                                'id' => $id,
                                                                               // 'status'  => 'GE'
                                                                              ])->update([
                                                                                'status' => 'RE' //REservado
                                                                              ]);

                                                                  
                                                                  $req->session()->flash('mensagem-sucesso', 'Pedido Salvo com sucesso!');

                                                                  return redirect()->route('pedido.compras');



                                                                }






                                                              }






                                                                      public function infoFrete(Request $request) {

                                                                       //  $this->middleware('VerifyCsrfToken');

                                                              //$req = Request();

                                                               // $id  = $req->input('infoFrete');
                                                               // dd($id);

                                                             $idpedido =  $request->pedido_id_load;


                                                             $cep_alt =  $request->cep_alter;

                                                             $cdservico =  $request->cdservico_alt;

                                                              if (empty($cdservico)){

                                                                $cdservico_vazio = "Preencha o Serviço!";


                                                                      return $cdservico_vazio;

                                                              }






                                                             //     $pedidos = Pedido::findOrFail($id);

                                                            
                                                           //  $id = Pedido::select('id')->where('id', $request->pedido_id_load)->first();

                                                                $idcliente = Pedido::select('id_cliente')->where('id', '=', $idpedido)->pluck('id_cliente');

                                                                 // $idcliente = Pedido::select('id_cliente')->where('id', '=', $id)->get();

                                                                 // $cep_destino_alt = Frete::select('cep')->where('pedido_id', '=', $idpedido)->pluck('cep');

                                                               //   if (empty($cep_destino_alt)){


                                                                if (empty($cep_alt)){

                                                                   $cepdestino = Cliente::select('cep')->where('id', '=', $idcliente)->pluck('cep');

                                                                    $cepdestino = str_replace('-', '',  $cepdestino);

                                                                    $cepdestino = preg_replace("/[^0-9]/", "", $cepdestino);




                                                                } else {


                                                                   $cepdestino =  $cep_alt;

                                                                    $cepdestino = str_replace('-', '',  $cepdestino);

                                                                    $cepdestino = preg_replace("/[^0-9]/", "", $cepdestino);


                                                                }

                                                                   




                                                               

                                                            //  $cepdestino = Frete::select('cep')->where('pedido_id', '=', $idpedido)->pluck('cep');
                                                              

                                                                 

                                                               //  dd($cepdestino);

                                                                //  $cepdestino = '04159000';

                                                                  $ceporigem = '09090520';                                                               
                                                                  $peso = '0.3';
                                                                  $comprimento = '20';
                                                                  $altura = '20';
                                                                  $largura = '20';
                                                                  $diametro = '0';
                                                                  $formato = 1;
                                                                  $maopropria = 'N'; 
                                                                  $valordeclarado = 0; 
                                                                  $avisorecebimento = 'N'; 
                                                                  $tiporetorno = 'xml';
                                                                  $indicacalculo = 3;
                                                                 // $cdempresa = '16148185';
                                                                  //$cdsenha = 'Odet1734';
                                                                  //$cdservico = '04014';


                                                                  $url = "http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx?nCdEmpresa=&sDsSenha=&sCepOrigem=".$ceporigem."&sCepDestino=".$cepdestino."&nVlPeso=".$peso."&nCdFormato=".$formato."&nVlComprimento=".$comprimento."&nVlAltura=".$altura."&nVlLargura=".$largura."&sCdMaoPropria=".$maopropria."&nVlValorDeclarado=".$valordeclarado."&sCdAvisoRecebimento=".$avisorecebimento."&nCdServico=".$cdservico."&nVlDiametro=".$diametro."&StrRetorno=".$tiporetorno."&nIndicaCalculo=".$indicacalculo;


                                                                 


                                                                //link do arquivo xml
                                                             
                                                              //carrega o arquivo XML e retornando um Array
                                                               $xml = simplexml_load_file($url);

/*if($xml->cServico->Erro == '0') {
 $sedex = array(
    "codigo"    => $xml->cServico->Codigo,
    "valor"     => $xml->cServico->Valor,
    "prazo_entrega" => $xml->cServico->PrazoEntrega,
    "aviso_recebto" => $xml->cServico->ValorAvisoRecebimento,
    "erro"      => $xml->cServico->Erro,
    "msg_erro"    => $xml->cServico->MsgErro,
 );
 return $sedex;*/




                                                            $data = $xml->cServico->Valor;

                                                           if ($data == '0,00'){

                                                             return  $xml->cServico->MsgErro;


                                                           } else {




                                                                 return $xml->cServico->Valor;// $xml->cServico->PrazoEntrega;


                                                           }

                                                            // $data1 = $xml->cServico->MsgErro
                                                           //   return response()->json($data);    
                                                           
                                                               // return response()->json($url);

                                                               //return response()->file($pathToFile);
                                                         

                                                             // return redirect()->away($url);





                                                             


                                                                }



                                                                      public function infoFretePrazoEntrega(Request $request) {

                                                                       //  $this->middleware('VerifyCsrfToken');

                                                              //$req = Request();

                                                               // $id  = $req->input('infoFrete');
                                                               // dd($id);

                                                             $idpedido =  $request->pedido_id_load;


                                                             $cep_alt =  $request->cep_alter;

                                                             $cdservico =  $request->cdservico_alt;

                                                              if (empty($cdservico)){

                                                                $cdservico_vazio = "Preencha o Serviço!";


                                                                      return $cdservico_vazio;

                                                              }






                                                             //     $pedidos = Pedido::findOrFail($id);

                                                            
                                                           //  $id = Pedido::select('id')->where('id', $request->pedido_id_load)->first();

                                                                $idcliente = Pedido::select('id_cliente')->where('id', '=', $idpedido)->pluck('id_cliente');

                                                                 // $idcliente = Pedido::select('id_cliente')->where('id', '=', $id)->get();

                                                                 // $cep_destino_alt = Frete::select('cep')->where('pedido_id', '=', $idpedido)->pluck('cep');

                                                               //   if (empty($cep_destino_alt)){


                                                                if (empty($cep_alt)){

                                                                   $cepdestino = Cliente::select('cep')->where('id', '=', $idcliente)->pluck('cep');

                                                                    $cepdestino = str_replace('-', '',  $cepdestino);

                                                                    $cepdestino = preg_replace("/[^0-9]/", "", $cepdestino);




                                                                } else {


                                                                   $cepdestino =  $cep_alt;

                                                                    $cepdestino = str_replace('-', '',  $cepdestino);

                                                                    $cepdestino = preg_replace("/[^0-9]/", "", $cepdestino);


                                                                }

                                                                   




                                                               

                                                            //  $cepdestino = Frete::select('cep')->where('pedido_id', '=', $idpedido)->pluck('cep');
                                                              

                                                                 

                                                               //  dd($cepdestino);

                                                                //  $cepdestino = '04159000';

                                                                  $ceporigem = '09090520';                                                               
                                                                  $peso = '0.3';
                                                                  $comprimento = '20';
                                                                  $altura = '20';
                                                                  $largura = '20';
                                                                  $diametro = '0';
                                                                  $formato = 1;
                                                                  $maopropria = 'N'; 
                                                                  $valordeclarado = 0; 
                                                                  $avisorecebimento = 'N'; 
                                                                  $tiporetorno = 'xml';
                                                                  $indicacalculo = 3;
                                                                 // $cdempresa = '16148185';
                                                                  //$cdsenha = 'Odet1734';
                                                                  //$cdservico = '04014';


                                                                  $url = "http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx?nCdEmpresa=&sDsSenha=&sCepOrigem=".$ceporigem."&sCepDestino=".$cepdestino."&nVlPeso=".$peso."&nCdFormato=".$formato."&nVlComprimento=".$comprimento."&nVlAltura=".$altura."&nVlLargura=".$largura."&sCdMaoPropria=".$maopropria."&nVlValorDeclarado=".$valordeclarado."&sCdAvisoRecebimento=".$avisorecebimento."&nCdServico=".$cdservico."&nVlDiametro=".$diametro."&StrRetorno=".$tiporetorno."&nIndicaCalculo=".$indicacalculo;


                                                                 


                                                                //link do arquivo xml
                                                             
                                                              //carrega o arquivo XML e retornando um Array
                                                               $xml = simplexml_load_file($url);

/*if($xml->cServico->Erro == '0') {
 $sedex = array(
    "codigo"    => $xml->cServico->Codigo,
    "valor"     => $xml->cServico->Valor,
    "prazo_entrega" => $xml->cServico->PrazoEntrega,
    "aviso_recebto" => $xml->cServico->ValorAvisoRecebimento,
    "erro"      => $xml->cServico->Erro,
    "msg_erro"    => $xml->cServico->MsgErro,
 );
 return $sedex;*/




                                                            $data = $xml->cServico->Valor;

                                                           if ($data == '0,00'){

                                                             return  $xml->cServico->MsgErro;


                                                           } else {

                                                           $prazo =  $xml->cServico->PrazoEntrega;

                                                           $prazo +=2;



                                                            return $prazo;// $xml->cServico->PrazoEntrega;


                                                           }

                                                            // $data1 = $xml->cServico->MsgErro
                                                           //   return response()->json($data);    
                                                           
                                                               // return response()->json($url);

                                                               //return response()->file($pathToFile);
                                                         

                                                             // return redirect()->away($url);





                                                             


                                                                }


                                                              public function concluir()
                                                              {
                                                                $this->middleware('VerifyCsrfToken');

                                                                $req = Request();
                                                                $idpedido  = $req->input('pedido_id');
                                                                $idusuario = Auth::id(); 
                                                                $idvendedor = $req->input('vendedor_id');    
                                                                $local = $req->input('localIsset');        
                                                                $cep = $req->input('cep');
                                                                $endereço = $req->input('endereço');
                                                                $numero = $req->input('numero');
                                                                $bairro = $req->input('bairro');
                                                                $complemento = $req->input('complemento');
                                                                $cidade = $req->input('cidade');
                                                                $estado = $req->input('estado');
                                                                $pagamentoIsset = $req->input('pagamentoIsset');




                                                                $check_pedido = Pedido::where([
                                                                  'id'      => $idpedido,
                                                                  'user_id' => $idusuario,
                                                                      'status'  => 'GE' // Reservada
                                                                    ])->exists();

                                                                if( !$check_pedido ) {
                                                                  $req->session()->flash('mensagem-falha', 'Pedido não encontrado!');
                                                                  return redirect()->route('index');
                                                                }

   /* $check_produtos = ItensPedido::where([
        'pedido_id' => $idpedido
    ])->exists();

    if(!$check_produtos) {
        $req->session()->flash('mensagem-falha', 'Produtos do pedido não encontrados!');
        return redirect()->route('index');
      }*/


      if( $pagamentoIsset == NULL ) {
        $req->session()->flash('mensagem-falha', 'Preencha a forma de Pagamento!');
        return redirect()->route('index')->withInput();
      }

   /* ItensPedido::where([
        'pedido_id' => $idpedido
    ])->update([
                'status' => 'RE' //Reservado
              ]);*/


              Pedido::where([
                'id' => $idpedido
               // 'obs_pedido' => $obs_pedido
              ])->update([
                'pagamento' => $pagamentoIsset,    
                'vendedor_id' => $idvendedor,           
                'status' => 'RE' //Reservado

      ]);

              Frete::where([
                'pedido_id' => $idpedido,
                'user_id' => $idusuario,
               // 'obs_pedido' => $obs_pedido
              ])->update([        

                'boolean' => 'N'
                //'status' => 'RE' //Reservado
              ]);

              $req->session()->flash('mensagem-sucesso', 'Pedido gerado com sucesso!');

              return redirect()->route('pedido.compras');
            }

            public function compras()
            {

              $dadosClientes=DB::table('clientes')->get();

              $dadosVendedores=DB::table('vendedores')->get();

              $dadosPedidos=DB::table('pedidos')->get();


            //  $compras = Pedido::where([
            //  'status'  => 'RE'    
             //  'user_id' => Auth::id()       
             //])->orderBy('id', 'desc')->paginate(7);

                   $compras = Pedido::where([
            //  'status'  => 'RE'    
               'user_id' => Auth::id()       
             ])->take(100)->get();

              $totalPageSearch = ($compras)->count();

              $produtos = DB::table('itens_pedidos')->select('prod_preco_padrao')->get();

              $desconto_produtos = DB::table('itens_pedidos')->select('prod_desconto')->get();

              $desconto_request = DB::table('itens_pedidos')->select('request_desconto')->get();

              $total_desconto_prod = $desconto_produtos->sum('prod_desconto');

              $total_desconto_req = $desconto_request->sum('request_desconto');

              $desconto = $total_desconto_prod + $total_desconto_req;


              $total_produtos = $produtos->sum('prod_preco_padrao');

              $frete = DB::table('fretes')->select('valor')->get();

              $frete_total = $frete->sum('valor');

              $geral = $total_produtos + $frete_total - $desconto;

              $total_preco = $geral; 


             
              

       /* $idpedido = Pedido::consultaId([
            'user_id' => $idusuario,                        
            'status'  => 'RE' // Reservado            
          ]);*/




         // $pedidos = Pedido::where('id', '!=', NULL)->orderBy('id', 'desc')->paginate(7);
          $pedidos = Pedido::where('id', '!=', NULL)->orderBy('id', 'desc')->take(100)->get();

       //  $dadosClientes= Cliente::where('status', '!=', 'A')->get();

       // dd($retirada);
          $valorFrete = DB::table('fretes')->select('valor')->where([
          //  'user_id' => Auth::id(),                    
            'status' => 'EMB', 
            'entrega'   => 'B',        
            'boolean' => 'Y'    
          ])->get();

          $valorFreteC = DB::table('fretes')->select('valor')->where([
          //  'user_id' => Auth::id(),                    
            'status' => 'EC',            
            'boolean' => 'Y'    
          ])->get();

          $cancelados = Pedido::where([
            'status'  => 'CA',
            'user_id' => Auth::id()
          ])->orderBy('updated_at', 'desc')->get();

            $totaladm = Pedido::where([
          //  'status'  => 'CA',
            'user_id' => Auth::id()
          ])->get();

        //  $total = Pedido::all()->count();
           $total = ($totaladm)->count();

          return view('admin.pedidoResource.compras', compact('compras',
            'retiradaBalcPF',
            'retiradaBalcPJ',
            'dadosPedidos',
            'freteB_PF',
            'freteB_PJ',
            'freteC_PF',
            'freteC_PJ',
            'valorFrete',
            'valorFreteC',
            'pedidos',
            'cancelados',
            'total',
            'dadosClientes',
            'dadosVendedores',
            'totalPageSearch',
            'total_preco'));

        }

         public function comprasVendedor()
            {

         
              $dadosVendedores=DB::table('vendedores')->get();




              $compras = Pedido::where([
            //  'status'  => 'RE'    
               'vendedor_id' => Auth::id()       
             ])->orderBy('id', 'desc')->paginate(7);

              $totalPage = ($compras)->count();

              $idvendedor = Auth::id();
         

              $pedidoVendedor = Pedido::select('id')->where('vendedor_id', '=', $idvendedor)->pluck('id');
    
              $produtos = ItensPedido::select('prod_preco_padrao')->whereIn('pedido_id', $pedidoVendedor)->get();
  
                $frete  = Frete::whereIn('pedido_id', $pedidoVendedor)->get();   

                 $frete_total = $frete->sum('valor');

              $total_produtos = $produtos->sum('prod_preco_padrao');

               $geral = $total_produtos + $frete_total;
               $total_preco = $geral;       
              

   



          $pedidos = Pedido::where([
           // 'status'  => 'RE',            
            'vendedor_id' => Auth::id()
          ])->orderBy('id', 'desc')->paginate(7);

          $dadosClientes = Cliente::where('status', '!=', 'A')->get();


          $valorFrete = DB::table('fretes')->select('valor')->where([
            'vendedor_id' => Auth::id(),                    
            'status' => 'EMB',            
            'boolean' => 'Y'    
          ])->get();

          $valorFreteC = DB::table('fretes')->select('valor')->where([
            'vendedor_id' => Auth::id(),                    
            'status' => 'EC',            
            'boolean' => 'Y'    
          ])->get();


          $cancelados = Pedido::where([
            'status'  => 'CA',
            'vendedor_id' => Auth::id()
          ])->orderBy('updated_at', 'desc')->get();

          $total = Pedido::where('vendedor_id', '=', $idvendedor)->count();

          return view('vendedor.pedidoResource.compras', compact('compras',           
            'idvendedor',           
            'pedidos',
            'cancelados',
            'total',
            'dadosClientes',
            'dadosVendedores',
            'totalPage',
            'total_preco'));

        }


       
      public function pedidoPdf($id)
{
     $registros = Produto::where([
                'ativo' => 's'
              ])->get();

               $list_requisitions= OpycosRequest::where('status','=','FI')->get();


              $pedidos = Pedido::findOrFail($id);

              $retiradaBalcPF = Frete::where([
                'pedido_id' => $id,
                'status' => 'AR',
                'balcao' => 'YPF',
             //   'entrega' => 'BPF',                
                'boolean' => 'N',
                'user_id' => Auth::id()               
              ])->get();

          // $dadosClientes= Frete::where('status', '!=', 'A')->get();
           // dd($retiradaBalcPF);

              $retiradaBalcPJ = Frete::where([
               'pedido_id' => $id,
               'status' => 'AR',
               'balcao' => 'YPJ',
               'boolean' => 'N',
               'user_id' => Auth::id()                   
             ])->get();



       // dd($retirada);

              $freteB_PF = Frete::where([
               'pedido_id' => $id,
               'user_id' => Auth::id(),                    
               'status' => 'EMB',            
               'entrega' => 'BPF',
               'boolean' => 'N'   

             ])->get(); 

              $freteB_PJ = Frete::where([
                'pedido_id' => $id,
                'user_id' => Auth::id(),                    
                'status' => 'EMB',            
                'entrega' => 'BPJ',
                'boolean' => 'N'   
              ])->get();


              $freteC_PF = Frete::where([
               'pedido_id' => $id,
               'user_id' => Auth::id(),                    
               'status' => 'EC',
               'entrega' => 'CPF',
               'boolean' => 'N'    
             ])->get(); 

              $freteC_PJ = Frete::where([
               'pedido_id' => $id,
               'user_id' => Auth::id(),                    
               'status' => 'EC',
               'entrega' => 'CPJ',
               'boolean' => 'N'    
             ])->get();

              $valorFrete = DB::table('fretes')->select('valor')->where([
               'pedido_id' => $id,
               'user_id' => Auth::id(),                    
               'status' => 'EMB',            
               'boolean' => 'N'    
             ])->get();

              $valorFreteC = DB::table('fretes')->select('valor')->where([
               'pedido_id' => $id,
               'user_id' => Auth::id(),                    
               'status' => 'EC',            
               'boolean' => 'N'    
             ])->get();



              $itenspedido = ItensPedido::where($id);


              return PDF::loadView('admin.pedidoResource.pdf-compras', compact('pedidos', 'registros', 'itenspedido', 'retiradaBalcPF', 'retiradaBalcPJ','freteB_PF','freteB_PJ','freteC_PF','freteC_PJ','valorFrete','valorFreteC', 'list_requisitions'))->stream('PedidosOpycos.pdf');
      
 
   
}


        public function allcompras()
        {

         $dadosClientes=DB::table('clientes')->get();

         $dadosVendedores=DB::table('vendedores')->get();

 /*$totalPedido = Pedido::where([
            'status'  => 'RE'           
          ])->orderBy('id', 'desc')->paginate(5);*/

            $produtos = DB::table('itens_pedidos')->select('prod_preco_padrao')->get();
              $total_produtos = $produtos->sum('prod_preco_padrao');

              $frete = DB::table('fretes')->select('valor')->get();

              $frete_total = $frete->sum('valor');

              $geral = $total_produtos + $frete_total;

              $total_preco = $geral; 



          $compras = Pedido::orderBy('id', 'desc')->paginate(7);

           $totalPageSearch = ($compras)->count();


          $pedidos = Pedido::orderBy('id', 'desc')->paginate(7);

          // $pedidos = Pedido::where('id', '!=', NULL)->orderBy('id', 'desc')->paginate(7);
        // dd($pedidos);

          $cancelados = Pedido::where([
            'status'  => 'CA'          
          ])->orderBy('updated_at', 'desc')->get();

          $total = Pedido::all()->count();


          $retiradaBalcPF = Frete::where([
            'status' => 'AR',
            'balcao' => 'Y',
            'boolean' => 'N',
            'user_id' => Auth::id()                   
          ])->get(); 






       // dd($retirada);

          $freteB_PF = Frete::where([
            'user_id' => Auth::id(),                    
            'status' => 'EMB',            
            'entrega' => 'BPF',
            'boolean' => 'N'   
          ])->get(); 




          $freteC_PF = Frete::where([
            'user_id' => Auth::id(),                    
            'status' => 'EC',
            'entrega' => 'CPF',

            'boolean' => 'N'    
          ])->get(); 



          $valorFrete = DB::table('fretes')->select('valor')->where([
           // 'user_id' => Auth::id(),                    
            'status' => 'EMB',            
            'boolean' => 'Y'    
          ])->get();

          $valorFreteC = DB::table('fretes')->select('valor')->where([
          //  'user_id' => Auth::id(),                    
            'status' => 'EC',            
            'boolean' => 'Y'    
          ])->get();








          return view('admin.pedidoResource.compras', compact('compras',
            'pedidos',
            'cancelados',
            'retiradaBalcPF',

            'freteB_PF',

            'freteC_PF',

            'valorFrete',
            'valorFreteC',
            'total',
            'dadosClientes',
            'dadosVendedores',
            'totalPageSearch',
            'total_preco'));

        }


        public function clientecompras()
        {

          $compras = Pedido::where([
            'status'  => 'RE'           
          ])->orderBy('id', 'desc')->paginate(5);

          $pedidos = Pedido::where([
            'status'  => 'RE',
            'id_cliente' => Auth::id()
          ])->orderBy('id', 'desc')->paginate(5);

          $cancelados = Pedido::where([
            'status'  => 'CA',
            'id_cliente' => Auth::id()
          ])->orderBy('updated_at', 'desc')->get();

          $total = Pedido::all()->count();

          return view('cliente.pedidoResource.compras', compact('compras', 'pedidos','cancelados', 'total'));

        }



        public function cancelar()
        {
          $this->middleware('VerifyCsrfToken');

          $req = Request();
          //$idpedido       = $req->input('pedido_id');
          //$idspedido_prod = $req->input('id');
          $idSpedido = $req->input('id');
          $idusuario      = Auth::id();

          if( empty($idSpedido) ) {
            $req->session()->flash('mensagem-falha', 'Nenhum pedido selecionado para cancelamento!');
            return redirect()->route('pedido.compras');
          }

          /*$check_pedido = Pedido::where([
            'id'      => $idpedido,
            'user_id' => $idusuario,
            'status'  => 'RE' // Finalizado
          ])->exists();*/

          $check_pedidos = Pedido::where([
                //'pedido_id' => $idpedido,            
                'user_id' => $idusuario,
                'status'    => 'RE' //Finalizado
              ])->whereIn('id', $idSpedido)->exists();

          if( !$check_pedidos ) {
            $req->session()->flash('mensagem-falha', 'Pedido não localizado para cancelamento!');
            return redirect()->route('pedido.compras');
          }

         /* $check_produtos = ItensPedido::where([
            'pedido_id' => $idpedido,
                'status'    => 'RE' //Finalizado
              ])->whereIn('id', $idSpedido)->exists();

          if( !$check_produtos ) {
            $req->session()->flash('mensagem-falha', 'Produtos do pedido não encontrados!');
            return redirect()->route('pedido.compras');
          }*/

          $idrequest = ItensPedido::where([
                'pedido_id' => $idSpedido,
                'tipo' => 'R',
                'status'    => 'GE' //Gerado
              ])->pluck('request_id');

if (isset($idrequest)) {

    Pedido::where([
           // 'id'      => $idpedido,
            'user_id' => $idusuario,
            'status'  => 'RE' // Finalizado
          ])->whereIn('id', $idSpedido)->update([
                'status' => 'CA' //Cancelado
               // 'prod_preco_padrao' => 0.00
              ]);


       /*   Frete::where([
           // 'id'      => $idpedido,
            'user_id' => $idusuario,
            'boolean' => 'Y'
          //  'status'  => 'RE' // Finalizado
          ])->whereIn('pedido_id', $idSpedido)->update([
                'entrega' => 'CA', //Cancelado
               
              ]);*/


                    OpycosRequest::where([
           // 'id'      => $idpedido,
           // 'user_id' => $idusuario,
            'ativo' => 's'
          //  'status'  => 'RE' // Finalizado
          ])->whereIn('id', $idrequest)->update([
                'status' => 'FI', //Cancelado
               
              ]);

           $req->session()->flash('mensagem-sucesso', 'Pedidos Cancelados!');

              

              return redirect()->route('pedido.compras');


} else {


    Pedido::where([
           // 'id'      => $idpedido,
            'user_id' => $idusuario,
            'status'  => 'RE' // Finalizado
          ])->whereIn('id', $idSpedido)->update([
                'status' => 'CA' //Cancelado
               // 'prod_preco_padrao' => 0.00
              ]);


          Frete::where([
           // 'id'      => $idpedido,
            'user_id' => $idusuario,
            'boolean' => 'Y'
          //  'status'  => 'RE' // Finalizado
          ])->whereIn('pedido_id', $idSpedido)->update([
                'entrega' => 'CA', //Cancelado
               
              ]);





             

            $req->session()->flash('mensagem-sucesso', 'Pedidos Cancelados!');

              

              return redirect()->route('pedido.compras');





}                 



           
            }


             public function descontoPedido()
        {

          $this->middleware('VerifyCsrfToken');
          $req = Request();
          $idpedido  = $req->input('pedido_id');    
      

          $idproduto = $req->input('idproduto');
          $idrequest = $req->input('idrequest');
         //dd($idproduto);
        //  $quantidade =  ($idproduto)->count();


          $desconto_produtos = $req->input('desconto_produto');
                    
         // dd($desconto_produtos);
          $desconto_produtos = str_replace( ',', '.', $desconto_produtos);




          $desconto_request = $req->input('desconto_request');

         // $idusuario      = Auth::id();

          if (empty($desconto_produtos)){

             $req->session()->flash('mensagem-falha', 'Não foram localizados os valores para desconto!'); 
             return redirect()->route('index');
          } 
        
          if (isset($idproduto)){

          /*    $check_desconto_prod_sup =  ItensPedido::where([
              'pedido_id' => $idpedido,
              
              ])->where('produto_id', $idproduto)->where('prod_preco_padrao', '<', $desconto_produtos)->exists();

          if( !$check_desconto_prod_sup ) {
            $req->session()->flash('mensagem-falha', 'Descontos superaram o valor unitário do produto!');
            return redirect()->route('index');


          }*/

           $check_desconto_prod =  ItensPedido::where([
              'pedido_id' => $idpedido,
              
              ])->where('produto_id', $idproduto)->exists();

           if( !$check_desconto_prod) {

             $req->session()->flash('mensagem-falha', 'Produtos não foram localizados!'); 
             return redirect()->route('index');

           } else {           


           $id_produtos =  ItensPedido::where([
              'pedido_id' => $idpedido,
              
              ])->where('produto_id', $idproduto)->pluck('produto_id');


 /*           $contador = 0;


          while($contador < $quantidade)
{

  ItensPedido::where([
              'pedido_id' => $idpedido,              
              ])->where('produto_id', $idproduto)->update([
                'prod_desconto' => $desconto_produtos                
              
              ]);

$contador++;
}*/

/*foreach( $all_ids as $key => $id ) {
    $id_ind = $all_id_ind[ $key ];

    $query = "UPDATE `dados_inds` SET `id_inds` = '$id_ind' WHERE id = '$id'";
    $sql = mysqli_query( $_conexao, $query );              
}*/

 

foreach( $idproduto as $key => $id ) {




    $desconto = $desconto_produtos[ $key ];

        if ($desconto == NULL) {
      $desconto = '0.00';

    }

      ItensPedido::where(
              'produto_id', $id              
              )->update([               
                'prod_desconto' => $desconto               
              
              ]);
            

   // $query = "UPDATE `dados_inds` SET `prod_desconto` = '$desconto_produtos' WHERE produto_id = '$id'";
              
}


       /* ItensPedido::where(
              'produto_id', $id_produtos              
              )->update([               
                'prod_desconto' => $desconto_produtos               
              
              ]);*/
            
            $req->session()->flash('mensagem-sucesso', 'Desconto Aplicado com sucesso!');             

             return redirect()->route('index');

        /*   $id_produtos =  ItensPedido::where([
              'pedido_id' => $idpedido,
              
              ])->where('produto_id', $idproduto)->pluck('produto_id');

             ItensPedido::where([
              'pedido_id' => $idpedido,              
              ])->where('produto_id', $id_produtos)->update([
                'prod_desconto' => $desconto_produtos
              
              ]);

            $req->session()->flash('mensagem-sucesso', 'Desconto Aplicado com sucesso!');             

             return redirect()->route('index');*/

               
          } 
                

              
          }   

          if (isset($idrequest)) {  
    

          ItensPedido::where([
              'pedido_id' => $idpedido,
              
              ])->whereIn('request_id', $idrequest)->update([
                'prod_preco_padrao' => $desconto_request
               
              ]);

        $req->session()->flash('mensagem-sucesso', 'Pedidos Cancelados!');             

       return redirect()->route('index');


} 

}                 








             public function finalizar()
        {
          $this->middleware('VerifyCsrfToken');

          $req = Request();
          //$idpedido       = $req->input('pedido_id');
          //$idspedido_prod = $req->input('id');
          $idSpedido = $req->input('id');
          //dd($idSpedido);
          $idusuario      = Auth::id();

          if( empty($idSpedido) ) {
            $req->session()->flash('mensagem-falha', 'Nenhum pedido selecionado para finalização!');
            return redirect()->route('pedido.compras');
          }

          /*$check_pedido = Pedido::where([
            'id'      => $idpedido,
            'user_id' => $idusuario,
            'status'  => 'RE' // Finalizado
          ])->exists();*/

          $check_pedidos = Pedido::where([
                //'id' => $idpedido,            
                //'user_id' => $idusuario,
                'status'    => 'RE' //Finalizado
              ])->whereIn('id', $idSpedido)->exists();

          if( !$check_pedidos ) {
            $req->session()->flash('mensagem-falha', 'Pedido não localizado para finalização!');
            return redirect()->route('pedido.compras');
          }

         /* $check_produtos = ItensPedido::where([
            'pedido_id' => $idpedido,
                'status'    => 'RE' //Finalizado
              ])->whereIn('id', $idSpedido)->exists();

          if( !$check_produtos ) {
            $req->session()->flash('mensagem-falha', 'Produtos do pedido não encontrados!');
            return redirect()->route('pedido.compras');
          }*/

          /*ItensPedido::where([
                'pedido_id' => $idpedido,
                'status'    => 'RE' //Finalizado
              ])->whereIn('id', $idSpedido)->update([
                'status' => 'CA' //Cancelado
               // 'prod_preco_padrao' => 0.00
              ]);*/

             Pedido::where([
           // 'id'      => $idpedido,
           // 'user_id' => $idusuario,
            'status'  => 'RE' // Finalizado
          ])->whereIn('id', $idSpedido)->update([
                'status' => 'FI' //Finalizado
               // 'prod_preco_padrao' => 0.00
              ]);

            /* Frete::where([
           // 'id'      => $idpedido,
               'user_id' => $idusuario,
               'boolean' => 'Y'
          //  'status'  => 'RE' // Finalizado
          ])->whereIn('pedido_id', $idSpedido)->update([
                'entrega' => 'FI' //Finalizado               
               // 'prod_preco_padrao' => 0.00
              ]);*/

             

            $req->session()->flash('mensagem-sucesso', 'Pedidos Finalizados!');

              

              return redirect()->route('pedido.compras');
            }




            public function show($id) {
        //
            }

            public function edit($id) {
              $registros = Produto::where([
                'ativo' => 's'
              ])->get();

               $list_requisitions= OpycosRequest::where('status','=','FI')->get();


              $pedidos = Pedido::findOrFail($id);

              $retiradaBalcPF = Frete::where([
                'pedido_id' => $id,
                'status' => 'AR',
                'balcao' => 'YPF',
             //   'entrega' => 'BPF',                
                'boolean' => 'N',
                'user_id' => Auth::id()               
              ])->get();

          // $dadosClientes= Frete::where('status', '!=', 'A')->get();
           // dd($retiradaBalcPF);

              $retiradaBalcPJ = Frete::where([
               'pedido_id' => $id,
               'status' => 'AR',
               'balcao' => 'YPJ',
               'boolean' => 'N',
               'user_id' => Auth::id()                   
             ])->get();



       // dd($retirada);

              $freteB_PF = Frete::where([
               'pedido_id' => $id,
               'user_id' => Auth::id(),                    
               'status' => 'EMB',            
               'entrega' => 'BPF',
               'boolean' => 'N'   

             ])->get(); 

              $freteB_PJ = Frete::where([
                'pedido_id' => $id,
                'user_id' => Auth::id(),                    
                'status' => 'EMB',            
                'entrega' => 'BPJ',
                'boolean' => 'N'   
              ])->get();


              $freteC_PF = Frete::where([
               'pedido_id' => $id,
               'user_id' => Auth::id(),                    
               'status' => 'EC',
               'entrega' => 'CPF',
               'boolean' => 'N'    
             ])->get(); 

              $freteC_PJ = Frete::where([
               'pedido_id' => $id,
               'user_id' => Auth::id(),                    
               'status' => 'EC',
               'entrega' => 'CPJ',
               'boolean' => 'N'    
             ])->get();

              $valorFrete = DB::table('fretes')->select('valor')->where([
               'pedido_id' => $id,
              // 'user_id' => Auth::id(),                    
               'status' => 'EMB',            
               'boolean' => 'N'    
             ])->get();

              $valorFreteC = DB::table('fretes')->select('valor')->where([
               'pedido_id' => $id,
              // 'user_id' => Auth::id(),                    
               'status' => 'EC',            
               'boolean' => 'N'    
             ])->get();





              $itenspedido = ItensPedido::where($id);


              return view('admin.pedidoResource.alter-pedido', compact('pedidos', 'registros', 'itenspedido', 'retiradaBalcPF', 'retiradaBalcPJ','freteB_PF','freteB_PJ','freteC_PF','freteC_PJ','valorFrete','valorFreteC', 'list_requisitions'));
            }


             public function info($id) {
              $registros = Produto::where([
                'ativo' => 's'
              ])->get();

               $list_requisitions= OpycosRequest::where('status','=','FI')->get();


              $pedidos = Pedido::findOrFail($id);

              $retiradaBalcPF = Frete::where([
                'pedido_id' => $id,
                'status' => 'AR',
                'balcao' => 'YPF',
             //   'entrega' => 'BPF',                
                'boolean' => 'N',
                'user_id' => Auth::id()               
              ])->get();

          // $dadosClientes= Frete::where('status', '!=', 'A')->get();
           // dd($retiradaBalcPF);

              $retiradaBalcPJ = Frete::where([
               'pedido_id' => $id,
               'status' => 'AR',
               'balcao' => 'YPJ',
               'boolean' => 'N',
               'user_id' => Auth::id()                   
             ])->get();



       // dd($retirada);

              $freteB_PF = Frete::where([
               'pedido_id' => $id,
               'user_id' => Auth::id(),                    
               'status' => 'EMB',            
               'entrega' => 'BPF',
               'boolean' => 'N'   

             ])->get(); 

              $freteB_PJ = Frete::where([
                'pedido_id' => $id,
                'user_id' => Auth::id(),                    
                'status' => 'EMB',            
                'entrega' => 'BPJ',
                'boolean' => 'N'   
              ])->get();


              $freteC_PF = Frete::where([
               'pedido_id' => $id,
               'user_id' => Auth::id(),                    
               'status' => 'EC',
               'entrega' => 'CPF',
               'boolean' => 'N'    
             ])->get(); 

              $freteC_PJ = Frete::where([
               'pedido_id' => $id,
               'user_id' => Auth::id(),                    
               'status' => 'EC',
               'entrega' => 'CPJ',
               'boolean' => 'N'    
             ])->get();

              $valorFrete = DB::table('fretes')->select('valor')->where([
               'pedido_id' => $id,
               'user_id' => Auth::id(),                    
               'status' => 'EMB',            
               'boolean' => 'N'    
             ])->get();

              $valorFreteC = DB::table('fretes')->select('valor')->where([
               'pedido_id' => $id,
               'user_id' => Auth::id(),                    
               'status' => 'EC',            
               'boolean' => 'N'    
             ])->get();





              $itenspedido = ItensPedido::where($id);


              return view('admin.pedidoResource.info-pedido', compact('pedidos', 'registros', 'itenspedido', 'retiradaBalcPF', 'retiradaBalcPJ','freteB_PF','freteB_PJ','freteC_PF','freteC_PJ','valorFrete','valorFreteC', 'list_requisitions'));
            }


            public function update($id) {

              $this->middleware('VerifyCsrfToken');

              $pedidos = Pedido::findOrFail($id);

              $this->middleware('VerifyCsrfToken');

              $req = Request();        
              $obspedido = $req->input('obs_pedido');
              $idcliente = $req->input('id_cliente');
              $idproduto = $req->input('id');


              $produto = Produto::find($idproduto);
              if( empty($produto->id) ) {
                $req->session()->flash('mensagem-falha', 'Produto não encontrado em nossa loja!');
                return redirect()->route('pedidos.index');
              }

              $idusuario = Auth::id();      
              $idpedido = Pedido::consultaId([
                'id' => $id,                        
            'status'  => 'RE' // Reservada            
          ]);

              if( isset($idpedido) ) {            
                Pedido::where([
                  'user_id' => $idusuario,
              //  'pedido_cod' => $codpedido,
                  'id' => $id,

                  'status'  => 'RE'
                ])->update([

                'status' => 'AG' //Alterado
              ]);

              }



              ItensPedido::create([
                'pedido_id'  => $id,
                'produto_id' => $idproduto,
                'prod_preco_balcao' => $produto->prod_preco_balcao,
                'prod_preco_padrao' => $produto->prod_preco_padrao,
                'prod_preco_prof' => $produto->prod_preco_prof,
          //'cliente_id' => $idcliente,
          //'vendedor_id' => $idvendedor,
            'status'     => 'RE'  //Reservado                 

            
          ]);

              $req->session()->flash('mensagem-sucesso', 'Produto adicionado ao pedido com sucesso!');

              return redirect()->route('pedidos.edit', $id);



            }




            public function updateFrete($id) {

              $this->middleware('VerifyCsrfToken');

              $pedidos = Pedido::findOrFail($id);

              $this->middleware('VerifyCsrfToken');

              $req = Request();        
              $obspedido = $req->input('obs_pedido');
              $idcliente = $req->input('id_cliente');
              $idproduto = $req->input('id');
              $retirada = $req->input('balcao');
              $frete = $req->input('entrega');
              $status = $req->input('status');
              $valor = $req->input('valor');
              $valor = str_replace( ',', '.', $valor );



      /*  $produto = Produto::find($idproduto);
        if( empty($produto->id) ) {
            $req->session()->flash('mensagem-falha', 'Produto não encontrado em nossa loja!');
            return redirect()->route('pedidos.index');
          }*/

          $idusuario = Auth::id();      
          $idpedido = Pedido::consultaId([
            'id' => $id,                        
            'status'  => 'RE' // Reservada            
          ]);

          if( isset($idpedido) ) {            
            Pedido::where([
              'user_id' => $idusuario,
              //  'pedido_cod' => $codpedido,
              'id' => $id,

              'status'  => 'RE'
            ])->update([

                'status' => 'AG' //Alterado
              ]);

          }

          if ($retirada == NULL && $frete == NULL)
          {
            $req->session()->flash('mensagem-falha', 'Informe o tipo de frete');
            return redirect()->route('pedidos.edit', $id);

          }

          if ($frete == "BPF") {


            if ($valor == NULL) {
              $req->session()->flash('mensagem-falha', 'Informar o custo do frete!');
              return redirect()->route('pedidos.edit', $id);


            }

            Frete::Where([
              'pedido_id'  => $id,
           // 'produto_id' => $idproduto,         

            ])->update([

             'balcao' => NULL,
             'entrega' => 'BPF',
             'valor' => $valor,
             'status' => 'EMB'       

           ]);

            $req->session()->flash('mensagem-sucesso', 'Frete alterado com sucesso!');

            return redirect()->route('pedidos.edit', $id);



          }


          if ($frete == "CPF") {

           if ($valor == NULL) {
            $req->session()->flash('mensagem-falha', 'Informar o custo do frete!');
            return redirect()->route('pedidos.edit', $id);


          }



          Frete::Where([
            'pedido_id'  => $id,
            
           // 'produto_id' => $idproduto,


            
          ])->update([

           'balcao' => NULL,
           'entrega' => 'CPF',
           'valor' => $valor,
           'status' => 'EC'



         ]);

          $req->session()->flash('mensagem-sucesso', 'Frete alterado com sucesso!');

          return redirect()->route('pedidos.edit', $id);


        }


        if ($frete == "BPJ") {

          Frete::Where([
            'pedido_id'  => $id,
           // 'produto_id' => $idproduto,



          ])->update([

           'balcao' => NULL,
           'entrega' => 'BPJ',
           'status' => 'EMB'              

         ]);

          $req->session()->flash('mensagem-sucesso', 'Frete alterado com sucesso!');

          return redirect()->route('pedidos.edit', $id);



        }



        if ($frete == "CPJ") {

          Frete::Where([
            'pedido_id'  => $id,
           // 'produto_id' => $idproduto,



          ])->update([

           'balcao' => NULL,
           'entrega' => 'CPJ',
           'status' => 'EC'              

         ]);

          $req->session()->flash('mensagem-sucesso', 'Frete alterado com sucesso!');

          return redirect()->route('pedidos.edit', $id);



        }


        if ($frete == NULL && $retirada == 'YPF' ) {

          Frete::Where([
            'pedido_id'  => $id,
           // 'produto_id' => $idproduto,



          ])->update([

           'balcao' => 'YPF',
           'entrega' => NULL,
           'status' => 'AR'              

         ]);

          $req->session()->flash('mensagem-sucesso', 'Frete alterado com sucesso!');

          return redirect()->route('pedidos.edit', $id);



        }


        if ($frete == NULL && $retirada == 'YPJ' ) {

          Frete::Where([
            'pedido_id'  => $id,
           // 'produto_id' => $idproduto,



          ])->update([

           'balcao' => 'YPJ',
           'entrega' => NULL,
           'status' => 'AR'              

         ]);

          $req->session()->flash('mensagem-sucesso', 'Frete alterado com sucesso!');

          return redirect()->route('pedidos.edit', $id);



        }



      }


      public function destroy($id) {
        try
        {
          $pedidos = Pedido::findOrFail($id);
          $pedidos->delete();
          return redirect()->route('pedidos.index')->with('message', 'Pedido excluído com sucesso!');
        } catch (QueryException $e) {
         return redirect()
         ->route('pedido.allcompras')
         ->with('mensagem-falha', 'Não foi possível realizar a exclusão (Já existe lançamento de produto neste pedido!)');
       }
     }

     public function searchPedido(Request $request, Pedido $pedido)
     {   

      // $produtos = \App\Entities\Produto::pluck('prod_cod', 'prod_desc', 'prod_preco_padrao')->all();      

      /* $valorFreteC = DB::table('fretes')->select('valor')->where([
       // 'user_id' => Auth::id(),                    
        'status' => 'EC',            
        'boolean' => 'Y'    
      ])->get();

       $valorFrete = DB::table('fretes')->select('valor')->where([
      //  'user_id' => Auth::id(),                    
        'status' => 'EMB',            
        'boolean' => 'Y'    
      ])->get();*/

       $id_cliente = $request->id_cliente;

       $vendedor_id = $request->vendedor_id;

       $status = $request->status;

       $pedido_id = $request->id;
       


       if (isset($id_cliente) && isset($status)){
        //dd($status);

        $pedidossearch = Pedido::select('id')->where([
        'status'  => $status,  
        'id_cliente'  => $id_cliente   
      ])->pluck('id');

            $produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
            $total_produtos = $produtossearch->sum('prod_preco_padrao');
            $desconto_produtos = $produtossearch->sum('prod_desconto');
            $desconto_request = $produtossearch->sum('request_desconto');
            $desconto = $desconto_produtos + $desconto_request;


             // $frete = DB::table('fretes')->select('valor')->get();
             /*  $fretesearch = Frete::select('id')->where([
                'pedido_id'  => $pedidossearch       
           ])->pluck('id');*/

            $frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
            //dd($frete); 
            $frete_total = $frete->sum('valor');
             // dd($frete_total);
            $geral = $total_produtos + $frete_total - $desconto;

            $total_preco = $geral; 



      }

        elseif  (isset($id_cliente)){


      $pedidossearch = Pedido::select('id')->where([
        'id_cliente'  => $id_cliente

      ])->pluck('id');

     //dd($pedidossearch);   

     // $produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
    
     //$total_produtos = $produtossearch->sum('prod_preco_padrao');

     //  $total_preco = $total_produtos;





              //$produtossearch = DB::table('itens_pedidos')->select('prod_preco_padrao')->get();
             $produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
            $total_produtos = $produtossearch->sum('prod_preco_padrao');
            $desconto_produtos = $produtossearch->sum('prod_desconto');
            $desconto_request = $produtossearch->sum('request_desconto');

            $desconto = $desconto_produtos + $desconto_request;

             // $frete = DB::table('fretes')->select('valor')->get();
             /*  $fretesearch = Frete::select('id')->where([
                'pedido_id'  => $pedidossearch       
           ])->pluck('id');*/

            $frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();  
            //dd($frete); 


              $frete_total = $frete->sum('valor');
             // dd($frete_total);
              $geral = $total_produtos + $frete_total -  $desconto;

              $total_preco = $geral; 




      }



       elseif  ((isset($vendedor_id)) && (isset($status)))
 {

   $pedidossearch = Pedido::select('id')->where([
        'status'  => $status,  
        'vendedor_id'  => $vendedor_id   
      ])->pluck('id');
     //dd($pedidossearch); 
  

      $produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();

     // dd($produtossearch);

      // $produtos = ItensPedido::select('prod_preco_padrao')->where(['pedido_id' => $pedidossearch])->get();
      // $produtos = DB::table('itens_pedidos')->select('prod_preco_padrao')->get();
       $total_produtos = $produtossearch->sum('prod_preco_padrao'); 
        $desconto_produtos = $produtossearch->sum('prod_desconto');
        $desconto_request = $produtossearch->sum('request_desconto');

            $desconto = $desconto_produtos + $desconto_request;   

     $frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();    

             

              $frete_total = $frete->sum('valor');

              $geral = $total_produtos + $frete_total - $desconto;

              $total_preco = $geral; 




}

elseif (isset($vendedor_id))

       {

        $pedidossearch = Pedido::select('id')->where([
        'vendedor_id'  => $vendedor_id       
      ])->pluck('id');
     //dd($pedidossearch); 
  

      $produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();

     // dd($produtossearch);

      // $produtos = ItensPedido::select('prod_preco_padrao')->where(['pedido_id' => $pedidossearch])->get();
      // $produtos = DB::table('itens_pedidos')->select('prod_preco_padrao')->get();
       $total_produtos = $produtossearch->sum('prod_preco_padrao');
        $desconto_produtos = $produtossearch->sum('prod_desconto');
        $desconto_request = $produtossearch->sum('request_desconto');

            $desconto = $desconto_produtos + $desconto_request; 

      

   /* $fretesearch = Frete::select('id')->where([
        'vendedor_id'  => $vendedor_id       
      ])->pluck('id');*/

     $frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();         

             

              $frete_total = $frete->sum('valor');

              $geral = $total_produtos + $frete_total - $desconto;

              $total_preco = $geral; 


}

elseif ((isset($pedido_id)) && (isset($status)))
  
{

   $pedidossearch = Pedido::select('id')->where([
        'id' => $pedido_id,
        'status'  => $status
       
      ])->pluck('id');

     //dd($pedidossearch); 
  

      $produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();

     // dd($produtossearch);

      // $produtos = ItensPedido::select('prod_preco_padrao')->where(['pedido_id' => $pedidossearch])->get();
      // $produtos = DB::table('itens_pedidos')->select('prod_preco_padrao')->get();
       $total_produtos = $produtossearch->sum('prod_preco_padrao'); 
        $desconto_produtos = $produtossearch->sum('prod_desconto');
        $desconto_request = $produtossearch->sum('request_desconto');

            $desconto = $desconto_produtos + $desconto_request; 
   

     $frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();    

             

              $frete_total = $frete->sum('valor');

              $geral = $total_produtos + $frete_total -  $desconto;

              $total_preco = $geral; 


}
 
elseif (isset($status))
  
{

   $pedidossearch = Pedido::select('id')->where([
        'status'  => $status
       
      ])->pluck('id');

     //dd($pedidossearch); 
  

      $produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();

     // dd($produtossearch);

      // $produtos = ItensPedido::select('prod_preco_padrao')->where(['pedido_id' => $pedidossearch])->get();
      // $produtos = DB::table('itens_pedidos')->select('prod_preco_padrao')->get();
       $total_produtos = $produtossearch->sum('prod_preco_padrao'); 
        $desconto_produtos = $produtossearch->sum('prod_desconto');
        $desconto_request = $produtossearch->sum('request_desconto');

            $desconto = $desconto_produtos + $desconto_request; 
   

     $frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();    

             

              $frete_total = $frete->sum('valor');

              $geral = $total_produtos + $frete_total -  $desconto;

              $total_preco = $geral; 


}

else
{

   $pedidossearch = Pedido::select('id')->where([
        'id'  => $pedido_id
       
      ])->pluck('id');

     //dd($pedido_id); 
  

      $produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();

     // dd($produtossearch);

      // $produtos = ItensPedido::select('prod_preco_padrao')->where(['pedido_id' => $pedidossearch])->get();
      // $produtos = DB::table('itens_pedidos')->select('prod_preco_padrao')->get();
       $total_produtos = $produtossearch->sum('prod_preco_padrao'); 
           $desconto_produtos = $produtossearch->sum('prod_desconto');
        $desconto_request = $produtossearch->sum('request_desconto');

            $desconto = $desconto_produtos + $desconto_request;    

     $frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();    

             

              $frete_total = $frete->sum('valor');

              $geral = $total_produtos + $frete_total - $desconto;

              $total_preco = $geral; 


}



       $compras = Pedido::where(
        'id', '!=', NULL
       // 'user_id' => Auth::id()             
      )->orderBy('id', 'desc')->paginate(5);

       $totalPage = ($compras)->count();



       $dataForm = $request->except('_token');        
       $pedidos = $pedido->search($dataForm); 


        $allsearch = $pedido->searchTotal($dataForm);
        $totalSearch = ($allsearch)->count(); 
        $totalPageSearch = ($pedidos)->count(); 
        
       


       $dadosClientes=DB::table('clientes')->get();
       $dadosVendedores=DB::table('vendedores')->get();
       $dadosGroupsProduct=DB::table('groups_product')->get();
       $dadosProducts=DB::table('products')->get(); 
       $total = Pedido::all()->count();


       return view('admin.pedidoResource.compras', compact('dadosGroupsProduct','products', 'dataForm', 'dadosClientes', 'dadosVendedores', 'pedidos', 'compras', 'total', 'total_preco', 'valorFreteC', 'valorFrete', 'totalSearch', 'id_cliente', 'vendedor_id', 'status', 'totalPageSearch', 'totalPage'));

     }

     public function findProductName(Request $request)
     {   

      $data=Produto::select('prod_desc', 'id')->where('grup_cod', $request->id)->take(100)->get();

        //if our chosen id and products table prod_cat_id
       // $request->id here is the id of our chosen option id

      return response()->json($data);
    }

    public function findProductCod(Request $request)

    {   

      $p=Produto::select('prod_cod')->where('id', $request->id)->first();



      return response()->json($p);
    }


    public function findVendId(Request $request)

    {   

      $p=Cliente::select('vendedor_id')->where('id', $request->id)->first();



      return response()->json($p);
    }

        public function findVendName(Request $request)

    {   

      $p=Cliente::select('vendedor_id')->where('id', $request->id)->pluck('vendedor_id');

       $v=Vendedor::select('name')->where('id', $p)->first();

     // $v=Vendedor::select('name')->where('id', $v)->first();



      return response()->json($v);
    }






  }
