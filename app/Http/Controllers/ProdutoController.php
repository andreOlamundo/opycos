<?php

namespace App\Http\Controllers;

use App\Entities\Produto;
use App\Entities\GroupProducts;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\QueryException;
use Exception;



class ProdutoController extends Controller
{

    /*    public function __construct()
    {
        $this->middleware('auth');
    }*/
    
    public function index() {

        //$list_groupsProd = \App\Entities\GroupProducts::pluck('id','grup_desc')->all();
        $list_prod = Produto::all();
        $list_CategProd = GroupProducts::all();
        $products = Produto::orderBy('id', 'desc')->get();    
        //$produtos = Produto::all();

        $total = Produto::all()->count();


        $registros = Produto::where([
            'ativo' => 's'
            ])->get();
        

        return view('admin.produtoResource.list-produtos', compact('products', 'total','registros', 'list_prod', 'list_CategProd' ));
    }


        public function vendedorindex() {

        //$list_groupsProd = \App\Entities\GroupProducts::pluck('id','grup_desc')->all();
        $list_groupsProd = \App\Entities\GroupProducts::all();
        $products = Produto::orderBy('id', 'desc')->get();      
        //$produtos = Produto::all();
        $total = Produto::all()->count();

        $registros = Produto::where([
            'ativo' => 's'
            ])->get();
        

        return view('vendedor.produtoResource.list-produtos', compact('products', 'total','registros', 'list_groupsProd' ));
    }

    public function create() {

        //$list_GroupProducts = \App\Entities\GroupProducts::pluck('grup_desc', 'grup_cod')->all();
        //$list_GroupCategory = \App\Entities\GroupProducts::pluck('grup_desc_categ', 'grup_categ_cod')->all();
        $list_CategProd = GroupProducts::all();
        $list_groups = GroupProducts::all();
        $products = Produto::orderBy('id', 'desc')->paginate(7);

         $totalPage = ($products)->count();
         $totalPageSearch = ($products)->count();



         $total = Produto::all()->count();
          $list_prod = Produto::all();
        //dd($list_CategProd);



        return view('admin.produtoResource.include-produto', compact('list_CategProd', 'list_groups','products', 'total', 'list_prod', 'totalPage', 'totalPageSearch'));
    }

    public function store(Request $request) {

        
        try {
        $product = new Produto;
        $product->prod_desc = $request->prod_desc;
        $product->prod_cod = $request->prod_cod;
        $product->grup_cod = $request->grup_cod;
        $product->grup_categ_cod = $request->grup_cod;
        $product->prod_preco_padrao = str_replace(",",".", str_replace('.', '', $request->prod_preco_padrao));
        $product->quantidade = $request->quantidade;
        $product->peso = str_replace(",",".", $request->peso);
        //$product->prod_preco_prof = str_replace(",",".",  str_replace('.', '', $request->prod_preco_prof));
        //$product->prod_preco_balcao = str_replace(",",".",  str_replace('.', '', $request->prod_preco_balcao));        
        $product->save();
        return redirect()->route('product.index')->with('message', 'Produto gerado com sucesso!');
    } catch (QueryException $e) {
        //dd($e->getMessage());
        //flash()->error('Mensagem para o usuário');
        return redirect()
        ->route('product.index')
        ->with('message-failure', 'Não foi possível realizar o cadastro')
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
        $product = Produto::findOrFail($id);
        $list_CategProd = GroupProducts::all();
        
        return view('admin.produtoResource.alter-produto', compact('product', 'list_CategProd'));
    }

    public function update(Request $request, $id) {
        $product = Produto::findOrFail($id); 
        $product->prod_desc = $request->prod_desc;
        $product->prod_cod = $request->prod_cod;
        $product->grup_cod = $request->grup_cod;
        $product->grup_categ_cod = $request->grup_cod;
        $product->prod_preco_padrao = str_replace(",",".", str_replace('.', '', $request->prod_preco_padrao));
        $product->ativo = $request->ativo;
        $product->peso = $request->peso;
        $product->quantidade = $request->quantidade;


       // $product->prod_preco_prof = str_replace(",",".",  str_replace('.', '', $request->prod_preco_prof));
       // $product->prod_preco_balcao = str_replace(",",".",  str_replace('.', '', $request->prod_preco_balcao));
        //$product->prod_preco_padrao = $request->prod_preco_padrao;
        //$product->prod_preco_prof = $request->prod_preco_prof;
        //$product->prod_preco_balcao =  $request->prod_preco_balcao;

        $product->save();
        return redirect()->route('product.index')->with('message', 'Produto alterado com sucesso!');
 
    }

    public function destroy($id) {


        try {
        $product = Produto::findOrFail($id);
        $product->delete();
        return redirect()->route('product.index')->with('message', 'Produto excluído com sucesso!');
    } catch (QueryException $e) {
         return redirect()
        ->route('product.index')
        ->with('message-failure', 'Não foi possível excluir o produto (Produto já pertence a um pedido!)');       
    }
     
       
        
    }


    public function searchProduto(Request $request, Produto $product)
    {
        $list_CategProd = GroupProducts::all();
        $list_prod = Produto::all();
        $dataForm = $request->except('_token');        
        $products = $product->search($dataForm);
        $total = Produto::all()->count();   

        $idprod = $request->id;
        $grup_categ_cod = $request->grup_categ_cod; 

        $totalPageSearch = ($products)->count();

         $allproducts = $product->searchTotal($dataForm);
         $totalSearch = ($allproducts)->count();        
        
        return view('admin.produtoResource.list-produtos', compact('products', 'total', 'dataForm', 'list_prod','list_CategProd', 'totalSearch', 'totalPageSearch', 'idprod', 'grup_categ_cod'  ));

    }


    public function findProductName(Request $request)
    {   
        
      $data=GroupProducts::select('grup_cod', 'id')->where('grup_categ_cod', $request->id)->take(100)->get();

        //if our chosen id and products table prod_cat_id
       // $request->id here is the id of our chosen option id

        return response()->json($data);
    }

            public function findProductCod(Request $request)
    {   
        
      $p=Produto::select('prod_cod')->where('id', $request->id)->first();

       

        return response()->json($p);
    }


}
