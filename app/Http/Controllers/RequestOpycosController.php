<?php

namespace App\Http\Controllers;

use App\Entities\Produto;
use App\Entities\GroupProducts;
use App\Entities\OpycosRequest;
use App\Entities\Cliente;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\QueryException;
use Exception;



class RequestOpycosController extends Controller
{

    /*    public function __construct()
    {
        $this->middleware('auth');
    }*/
    
    public function index() {

        //$list_groupsProd = \App\Entities\GroupProducts::pluck('id','grup_desc')->all();
        $list_request = OpycosRequest::all();
       // $list_CategProd = GroupProducts::all();
        $requestOpy = OpycosRequest::all();       
        //$produtos = Produto::all();
      
        $registros = OpycosRequest::where([
            'ativo' => 's'
            ])->get();

        $requestCancelados = OpycosRequest::where([
           // 'user_id' => Auth::id() 
            
            'status' => 'CA'
            ])->get();



        

        return view('admin.requestResource.list-request', compact('registros', 'list_request', 'requestOpy', 'requestCancelados'));
    }




    public function create() {

        //$list_GroupProducts = \App\Entities\GroupProducts::pluck('grup_desc', 'grup_cod')->all();
        //$list_GroupCategory = \App\Entities\GroupProducts::pluck('grup_desc_categ', 'grup_categ_cod')->all();
        //$list_CategProd = GroupProducts::all();
       // $list_groups = GroupProducts::all();
        //dd($list_CategProd);
        $list_request = OpycosRequest::all();
        $list_Clientes= Cliente::where('status', '!=', 'A')->get();
        $request = OpycosRequest::all();
         $requestCancelados = OpycosRequest::where([
           //'user_id' => Auth::id() 
            'status' => 'CA'
            ])->get();

              $requestFinalizados = OpycosRequest::where([
           //'user_id' => Auth::id() 
            'status' => 'FI'
            ])->get();

            
   

       $requestAP = OpycosRequest::where([
           //'user_id' => Auth::id() 
            'status' => 'AP'
            ])->get();


        return view('admin.requestResource.include-request', compact('list_Clientes', 'request', 'list_request', 'requestCancelados', 'requestFinalizados', 'requestAP'));
    }

    public function store(Request $request) {

        
        try {
        $req = Request(); 
        $requisitions = new OpycosRequest;
        $requisitions->request_desc = $request->request_desc;
        $requisitions->request_cod = $request->request_cod;
        $requisitions->id_cliente = $request->id_cliente;  
        $requisitions->status  = $request->status; 
        $requisitions->quantidade = $request->quantidade;
        $requisitions->peso = str_replace(',', '.',  $request->peso);

        $user_id = \Auth::id();

        $requisitions->user_id = $user_id;


        //$requisitions->grup_cod = $request->grup_cod;
        //$requisitions->grup_categ_cod = $request->grup_cod;
        $requisitions->request_valor = str_replace(",",".", str_replace('.', '', $request->request_valor));  
             
        $requisitions->save();
        return redirect()->route('requestopycos.index')->with('message', 'Requisição gerada com sucesso!');
    } catch (QueryException $e) {
        //dd($e->getMessage());
        //flash()->error('Mensagem para o usuário');
        return redirect()
        ->route('requestopycos.create')
        ->with('message-failure', 'Não foi possível realizar o cadastro.')
        ->withInput();
    }
       
  
    //    $validate = validator($dataForm, $this->search);
     //   if ($validate->fails())

//{

//return redirect()
//->route('product.create')
//->withErros($validate)
//->withInput();

//}

     

    }

    public function show($id) {
        //
    }

    public function edit($id) {
        $requisitions = OpycosRequest::findOrFail($id);
     //   $list_CategProd = GroupProducts::all();
        
        return view('admin.requestResource.alter-request', compact('requisitions'));
    }


        public function cancelar($id) {
        $requisitions = OpycosRequest::findOrFail($id);
     //   $list_CategProd = GroupProducts::all();

           $validate = OpycosRequest::where([
            
                'status' => 'FI',
                'id' => $id
                                                   
                             
            ])->get();

           //dd($validate);


        if (isset($validate)  == false)
        {
            return redirect()->route('requestopycos.create')->with('message-failure', 'Rquisição já foi finalizada não é possível cancelar!');


        } else {
                 OpycosRequest::where([
                'id' => $id                                    
                             
            ])->update([
                'status' => 'CA'
                //'status' => 'RE' //Finalizado
            ]);

return redirect()->route('requestopycos.index')->with('message', 'Rquisição foi Cancelada com sucesso!.');

        }

          

        
        return view('admin.requestResource.alter-request', compact('requisitions'));
    }

    public function update($id) {
        $requisitions = OpycosRequest::findOrFail($id); 
      
        try {
        $req = Request();
        $request_cod = $req->input('request_cod');
        $request_desc = $req->input('request_desc');
        $request_valor = $req->input('request_valor');
        $request_valor = str_replace( ',', '.', $request_valor );       
        $status = $req->input('status');

            OpycosRequest::where([
                'id' => $id,
               
               
            ])->update([
                'request_cod' => $request_cod,
                'request_desc' => $request_desc,
                'request_valor' => $request_valor,
                'status' => $status              
                
            ]);          

            
    

        return redirect()->route('requestopycos.index')->with('message', 'Requisição alterada com sucesso!.');
    } catch (QueryException $e) {
        //dd($e->getMessage());
        //flash()->error('Mensagem para o usuário');
        return redirect()
        ->route('requestopycos.edit', $id)
        ->with('message-failure', 'Não foi possível realizar a alteração.')
        ->withInput();
    }

       
 
    }

    public function destroy($id) {


        try {
        $requisitions = OpycosRequest::findOrFail($id);
        $request = $requisitions->status;
        if ($request == 'RE'){

        return redirect()
        ->route('requestopycos.create')
        ->with('message-failure', 'Não foi possível excluir a Requisição (Requisiçao já pertence a um pedido!)');    

        }


        $requisitions->delete();
        return redirect()->route('requestopycos.index')->with('message', 'Requisição excluída com sucesso!');

    } catch (QueryException $e) {
         return redirect()
        ->route('requestopycos.create')
        ->with('message-failure', 'Não foi possível excluir a Requisição.');       
    }
     
       
        
    }


    public function searchRequest(Request $request, OpycosRequest $requestOpy)
    {
        
        $list_request = OpycosRequest::all();      
       
         $idreq = $request->id;
       // $status = $request->status; 
          $dataForm = $request->except('_token'); 
         $requestOpy = $requestOpy->search($dataForm);   

        //$request = OpycosRequest::all();  
        $list_Clientes= Cliente::where('status', '!=', 'A')->get();        
        
        return view('admin.requestResource.list-request', compact('dataForm','list_request', 'requestOpy', 'list_Clientes', 'idreq'));

    }


        public function findRequisitionsName(Request $request)
    {   
        
      $data=OpycosRequest::select('id_cliente', 'id', 'request_cod', 'request_desc', 'status', 'request_valor')->where('id_cliente', $request->id)->where('status', '=', 'FI')->take(100)->get();

        //if our chosen id and products table prod_cat_id
       // $request->id here is the id of our chosen option id

        return response()->json($data);
    }



        public function findRequisitionsNameInt(Request $request)
    {   
        
      $data=OpycosRequest::select('id_cliente', 'id', 'request_cod', 'request_desc', 'status', 'request_valor')->where('id_cliente', $request->id)->where('status', '=', 'FI')->take(100)->get();

        //if our chosen id and products table prod_cat_id
       // $request->id here is the id of our chosen option id

        return response()->json($data);
    }




}
