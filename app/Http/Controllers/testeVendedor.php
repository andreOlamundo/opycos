<?php

namespace App\Http\Controllers;

use App\Entities\Vendedor;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
//use Exception;
use DB;


class VendedorController extends Controller
{

 
    public function index() {
       // $vendedores = Vendedor::orderBy('id', 'desc')->paginate(5);

        $vendedores = Vendedor::where([            
            'status' => 'active'
           
        ])->paginate(5);
        //$produtos = Produto::all();
       // $dadosVendedores=DB::table('vendedores')->get();
       $dadosVendedores = Vendedor::where([            
            'status' => 'active'
           
        ])->get();
        $total = Vendedor::all()->count();
         $totalPageSearch = ($vendedores)->count();
        return view('admin.vendedorResource.list-vendedores', compact('vendedores', 'total', 'dadosVendedores', 'totalPageSearch'));
    }



    public function status($id) {

        try {
        $vendedores = Vendedor::findOrFail($id);
         $vendedores = Vendedor::where([    
                 'id' => $id,
                 'status' => 'active'
           
        ])->update([
       'status' => 'inativo'
    ]);
        
        return redirect()->route('vendedores.index')->with('message', 'Vendedor(a) excluído(a) com sucesso!');

    } catch (QueryException $e) {
         return redirect()
        ->route('vendedores.index')
        ->with('message-failure', 'Não foi possível excluir o usuário (Usuário já possui pedido gerado!)');       
    }






        public function vendedorindex() {
        
       
        $vendedores = Vendedor::where([            
            'status' => 'active',
            'id' => Auth::id() 
        ])->get();     
      $total = Vendedor::all()->count();
        //$produtos = Produto::all();
        
        
        return view('vendedor.vendedorResource.list-vendedores', compact('vendedores', 'total'));
    }

    public function listCadastro() {        
       
        $vendedores = Vendedor::where([            
            'status' => 'active',
            'id' => Auth::id() 
        ])->get();     
        
        return view('vendedor.vendedorResource.list-cadastroVend', compact('vendedores'));
    }



    public function create() {
        return view('admin.vendedorResource.include-vendedor');
    }

    public function store(Request $request) {
         try {
        $vendedores = new Vendedor;
        $vendedores->name = $request->name;              
        $vendedores->email = $request->email;
        $vendedores->tel = $request->tel;
        $vendedores->cel = $request->cel;
        $vendedores->cpf = $request->cpf;
        $vendedores->cnpj = $request->cnpj; 
        //$vendedores->status = $request->status;       
       // $vendedores->cep = $request->cep;       
       // $vendedores->endereço = $request->endereço;
        //$vendedores->numero = $request->numero;
       // $vendedores->complemento = $request->complemento;
       // $vendedores->bairro = $request->bairro;
       // $vendedores->cidade = $request->cidade;
        //$vendedores->estado = $request->estado;
        //$vendedores->notes = $request->notes;
        $vendedores->password = bcrypt($request->password);

         if ( $vendedores->cnpj == NULL  &&  $vendedores->cpf == NULL ){      
                           
        
        return redirect()->route('vendedores.create')->with('message-failure', 'Informe o CPF ou CNPJ.')->withInput();

        }



        $vendedores->save();
        return redirect()->route('vendedores.index')->with('message', 'Vendedor(a) cadastrado(a) com sucesso!');
    } catch (QueryException $e) {

         return redirect()
        ->route('vendedores.create')
        ->with('message-failure', 'Não foi possível realizar o cadastro (Já existem lançamentos idênticos em nossos registros!)')
        ->withInput();

      }
    }

    public function show($id) {
        //
    }

    public function edit($id) {
        $vendedores = Vendedor::findOrFail($id);
        
        return view('admin.vendedorResource.alter-vendedor', compact('vendedores'));
    }

    public function update(Request $request, $id) {
        try {
        $vendedores = Vendedor::findOrFail($id); 
        $vendedores->name = $request->name;               
        $vendedores->email = $request->email;
        $vendedores->tel = $request->tel;
        $vendedores->cel = $request->cel; 
       // $vendedores->cpf = $request->cpf;
      // $vendedores->cnpj = $request->cnpj;       
     //  $vendedores->cep = $request->cep;       
      //  $vendedores->endereço = $request->endereço;
       // $vendedores->numero = $request->numero;
      //  $vendedores->complemento = $request->complemento;
     //  $vendedores->bairro = $request->bairro;
       // $vendedores->cidade = $request->cidade;
      //  $vendedores->estado = $request->estado;
      //  $vendedores->notes = $request->notes;
       // $vendedores->status = $request->status;
        $vendedores->password = bcrypt($request->password);     
        $vendedores->save();
        return redirect()->route('vendedores.index')->with('message', 'Vendedor(a) alterado(a) com sucesso!');
            }
    catch (QueryException $e) {
         return redirect()
        ->route('vendedores.index')
        ->with('message-failure', 'Não foi possível alterar o usuário (E-mail informado já consta em nossos registros!)');       
            }


    }

    public function destroy($id) {

        try {
        $vendedores = Vendedor::findOrFail($id);
        $vendedores->delete();
        return redirect()->route('vendedores.index')->with('message', 'Vendedor(a) excluído(a) com sucesso!');

    } catch (QueryException $e) {
         return redirect()
        ->route('vendedores.index')
        ->with('message-failure', 'Não foi possível excluir o usuário (Usuário já possui pedido gerado!)');       
    }



       
    }

    public function searchVendedor(Request $request, Vendedor $vendedor)
    {
        $dadosVendedores=DB::table('vendedores')->get();
        $dataForm = $request->except('_token');
        $vendedores = $vendedor->search($dataForm);
        $total = Vendedor::all()->count();   

        $idvendedor = $request->id;

        $allVendedores = $vendedor->searchTotal($dataForm);
        $totalSearch = ($allVendedores)->count();

        $totalPageSearch = ($vendedores)->count();         
        
        return view('admin.vendedorResource.list-vendedores', compact('vendedores', 'dadosVendedores', 'total', 'dataForm', 'totalPageSearch', 'totalSearch', 'idvendedor'));

    }


}







