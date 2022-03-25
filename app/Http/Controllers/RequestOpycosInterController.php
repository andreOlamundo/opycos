<?php

namespace App\Http\Controllers;


use App\Entities\OpycosRequestInter;
use App\Entities\Cliente;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\QueryException;
use Exception;
use Auth;



class RequestOpycosInterController extends Controller
{

    /*    public function __construct()
    {
        $this->middleware('auth');
    }*/
    
    public function index() {

        //$list_groupsProd = \App\Entities\GroupProducts::pluck('id','grup_desc')->all();
        $vendedor_id = \Auth::id();
        $list_request = OpycosRequestInter::where(['vendedor_id' => $vendedor_id])->get();
       // $list_CategProd = GroupProducts::all();
        $requisitions = OpycosRequestInter::where(['vendedor_id' => $vendedor_id])->get();       
        //$produtos = Produto::all();
        $total = OpycosRequestInter::where(['vendedor_id' => $vendedor_id])->count();



        $registros = OpycosRequestInter::where([
            'vendedor_id' => $vendedor_id,
            'ativo' => 's'
            ])->get();

        $requestCancelados = OpycosRequestInter::where([
           'vendedor_id' => $vendedor_id,            
            'status' => 'CA'
            ])->get();



        

        return view('vendedor.requestResource.list-request', compact('total','registros', 'list_request', 'requisitions', 'requestCancelados'));
    }


     /*   public function vendedorindex() {

        //$list_groupsProd = \App\Entities\GroupProducts::pluck('id','grup_desc')->all();
        $list_groupsProd = \App\Entities\GroupProducts::all();
        $products = Produto::orderBy('id', 'desc')->paginate(10);       
        //$produtos = Produto::all();
        $total = Produto::all()->count();

        $registros = Produto::where([
            'ativo' => 's'
            ])->get();
        

        return view('vendedor.produtoResource.list-produtos', compact('products', 'total','registros', 'list_groupsProd' ));
    }*/

    public function create() {

        //$list_GroupProducts = \App\Entities\GroupProducts::pluck('grup_desc', 'grup_cod')->all();
        //$list_GroupCategory = \App\Entities\GroupProducts::pluck('grup_desc_categ', 'grup_categ_cod')->all();
        //$list_CategProd = GroupProducts::all();
       // $list_groups = GroupProducts::all();
        //dd($list_CategProd);
         $vendedor_id = \Auth::id();
        $list_request = OpycosRequestInter::where(['vendedor_id' => $vendedor_id])->get();
        $list_Clientes= Cliente::where('status', '!=', 'A')->get();
        $request = OpycosRequestInter::where(['vendedor_id' => $vendedor_id])->get();
         $requestCancelados = OpycosRequestInter::where([
           //'user_id' => Auth::id() 
           'vendedor_id' => $vendedor_id,
            'status' => 'CA'
            ])->get();
        

              $requestFinalizados = OpycosRequestInter::where([
           'vendedor_id' => $vendedor_id,
            'status' => 'FI'
            ])->get();

              $totalPage = ($request)->count();

               $totalPageSearch = ($request)->count();

                $total = OpycosRequestInter::where(['vendedor_id' => $vendedor_id])->count();


       $requestAP = OpycosRequestInter::where([
          'vendedor_id' => Auth::id(),
            'status' => 'AP'
            ])->get();


        return view('vendedor.requestResource.include-request', compact('list_Clientes', 'request', 'list_request', 'requestCancelados', 'requestFinalizados', 'requestAP', 'total', 'totalPageSearch', 'totalPage'));
    }

    public function store(Request $request) {

        
        try {
        $req = Request(); 
        $requisitions = new OpycosRequestInter;
        $requisitions->request_desc = $request->request_desc;
        $requisitions->request_cod = $request->request_cod;
        $requisitions->id_cliente = $request->id_cliente;  
        $requisitions->status  = $request->status; 
        $requisitions->quantidade = $request->quantidade;
        $requisitions->peso = str_replace(",",".", $request->peso);

        $vendedor_id = \Auth::id();

        $requisitions->vendedor_id = $vendedor_id;


        //$requisitions->grup_cod = $request->grup_cod;
        //$requisitions->grup_categ_cod = $request->grup_cod;
        $requisitions->request_valor = str_replace(",",".", str_replace('.', '', $request->request_valor));  
             
        $requisitions->save();
        return redirect()->route('requestopycosint.index')->with('message', 'Requisição gerada com sucesso!');
    } catch (QueryException $e) {
        //dd($e->getMessage());
        //flash()->error('Mensagem para o usuário');
        return redirect()
        ->route('requestopycosint.create')
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
        $requisitions = OpycosRequestInter::findOrFail($id);
     //   $list_CategProd = GroupProducts::all();
        
        return view('vendedor.requestResource.alter-request', compact('requisitions'));
    }


        public function cancelar($id) {
        $requisitions = OpycosRequestInter::findOrFail($id);
     //   $list_CategProd = GroupProducts::all();

           $validate = OpycosRequestInter::where([
            
                'status' => 'FI',
                'id' => $id
                                                   
                             
            ])->get();

           //dd($validate);


        if (isset($validate)  == false)
        {
            return redirect()->route('requestopycosint.edit', $id)->with('message-failure', 'Rquisição já foi finalizada não é possível cancelar!');


        } else {
                 OpycosRequestInter::where([
                'id' => $id                                    
                             
            ])->update([
                'status' => 'CA'
                //'status' => 'RE' //Finalizado
            ]);

return redirect()->route('requestopycosint.index')->with('message', 'Rquisição foi Cancelada com sucesso!.');

        }

          

        
        return view('vendedor.requestResource.alter-request', compact('requisitions'));
    }

    public function update($id) {
        $requisitions = OpycosRequestInter::findOrFail($id); 
      
        try {
        $req = Request();
        $request_cod = $req->input('request_cod');
        $request_desc = $req->input('request_desc');
        $request_valor = $req->input('request_valor');
        $request_valor = str_replace( ',', '.', $request_valor );       
        $status = $req->input('status');

            OpycosRequestInter::where([
                'id' => $id,
               
               
            ])->update([
                'request_cod' => $request_cod,
                'request_desc' => $request_desc,
                'request_valor' => $request_valor,
                'status' => $status              
                
            ]);          

            
    

        return redirect()->route('requestopycosint.index')->with('message', 'Requisição alterada com sucesso!.');
    } catch (QueryException $e) {
        //dd($e->getMessage());
        //flash()->error('Mensagem para o usuário');
        return redirect()
        ->route('requestopycosint.edit', $id)
        ->with('message-failure', 'Não foi possível realizar a alteração.')
        ->withInput();
    }

       
 
    }

    public function destroy($id) {


        try {
        $requisitions = OpycosRequestInter::findOrFail($id);
        $requisitions->delete();
        return redirect()->route('requestopycosint.index')->with('message', 'Requisição excluída com sucesso!');
    } catch (QueryException $e) {
         return redirect()
        ->route('requestopycosint.index')
        ->with('message-failure', 'Não foi possível excluir a Requisição');       
    }
     
       
        
    }


    public function searchRequest(Request $request, OpycosRequestInter $requestOpy)
    {

         $vendedor_id = \Auth::id();
        
        $list_request = OpycosRequestInter::where(['vendedor_id' => $vendedor_id])->get();

        $dataForm = $request->except('_token');        
        $requisitions = $requestOpy->search($dataForm);
        $total = OpycosRequestInter::all()->count(); 

         $idreq = $request->id;
       // $status = $request->status; 

        $totalPageSearch = ($requisitions)->count();

         $allrequisitions = $requestOpy->searchTotal($dataForm);
         $totalSearch = ($allrequisitions)->count();        
        



      $request = OpycosRequestInter::orderBy('id', 'desc')->paginate(7);   
        $list_Clientes= Cliente::where('status', '!=', 'A')->get();        
        
        return view('vendedor.requestResource.list-request', compact('requisitions', 'total', 'dataForm','list_request', 'request', 'list_Clientes', 'totalPageSearch', 'totalSearch', 'idreq'));

    }


        public function findRequisitionsName(Request $request)
    {   
        
      $data=OpycosRequestInter::select('id_cliente', 'id', 'request_cod', 'request_desc', 'status')->where('id_cliente', $request->id)->where('status', '=', 'FI')->take(100)->get();

        //if our chosen id and products table prod_cat_id
       // $request->id here is the id of our chosen option id

        return response()->json($data);
    }



        public function findRequisitionsNameInt(Request $request)
    {   
        
      $data=OpycosRequestInter::select('id_cliente', 'id', 'request_cod', 'request_desc', 'status')->where('id_cliente', $request->id)->where('status', '=', 'FI')->take(100)->get();

        //if our chosen id and products table prod_cat_id
       // $request->id here is the id of our chosen option id

        return response()->json($data);
    }




}
