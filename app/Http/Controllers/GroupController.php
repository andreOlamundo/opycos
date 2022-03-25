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



class GroupController extends Controller
{

    /*    public function __construct()
    {
        $this->middleware('auth');
    }*/    
    public function index() {

        //$list_groupsProd = \App\Entities\GroupProducts::pluck('id','grup_desc')->all();
        $list_categories = GroupProducts::all();
        $categories = GroupProducts::all();      
        $total = GroupProducts::all()->count();
     
        

        return view('admin.produtoResource.list-groups', compact('categories', 'total','list_categories' ));
     }


        public function vendedorindex() {

        //$list_groupsProd = \App\Entities\GroupProducts::pluck('id','grup_desc')->all();
        $list_groupsProd = \App\Entities\GroupProducts::all();
        $products = Produto::all();      
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
         $categories = GroupProducts::all(); 
           $list_categories = GroupProducts::all();
            $total = GroupProducts::all()->count();
             $totalPage = ($categories)->count();
         $totalPageSearch = ($categories)->count();
        //dd($list_CategProd);



        return view('admin.produtoResource.include-group', compact('list_CategProd', 'list_groups', 'categories', 'list_categories', 'total', 'totalPage', 'totalPageSearch'));
    }

    public function store(Request $request) {
        try {
        $category = new GroupProducts;
        $category->grup_categ_cod = $request->grup_categ_cod;
        $category->grup_desc_categ = $request->grup_desc_categ;
        $category->grup_desc = $request->grup_desc;
        $category->grup_cod = $request->grup_cod;                   
        $category->save();
        return redirect()->route('categoria.index')->with('message', 'Categoria gerada com sucesso!');
    } catch (QueryException $e) {
        //dd($e->getMessage());
        //flash()->error('Mensagem para o usuário');
        return redirect()
        ->route('categoria.create')
        ->with('message', 'Não foi possível realizar o cadastro.')
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
        $category = GroupProducts::findOrFail($id);
        //$list_CategProd = GroupProducts::all();
        
        return view('admin.produtoResource.alter-group', compact('category'));
    }

    public function update(Request $request, $id) {
try {
        $category = GroupProducts::findOrFail($id); 
        $category->grup_categ_cod = $request->grup_categ_cod;
        $category->grup_desc_categ = $request->grup_desc_categ;
        $category->grup_desc = $request->grup_desc; 
        $category->grup_cod = $request->grup_cod;     
        $category->save();
        return redirect()->route('categoria.index')->with('message', 'Categoria alterada com sucesso!');
 }  catch (QueryException $e) {
         return redirect()
        ->route('categoria.create')
        ->with('message-failure', 'Não foi possível alterar a categoria.');       
    }
    }

    public function destroy($id) {


        try {
        $category = GroupProducts::findOrFail($id);
        $category->delete();

        return redirect()->route('categoria.index')->with('message', 'Categoria excluída com sucesso!');
    } catch (QueryException $e) {
         return redirect()
        ->route('categoria.edit', $id)
        ->with('message-failure', 'Não foi possível excluir a categoria.');       
    }
     
       
        
    }


    public function searchCategoria(Request $request, GroupProducts $category)
    {
        $list_categories = GroupProducts::all();
        $dataForm = $request->except('_token');        
        $categories = $category->search($dataForm);
        $total = GroupProducts::all()->count();  

         $idcateg = $request->id;
       // $grup_categ_cod = $request->grup_categ_cod; 

        $totalPageSearch = ($categories)->count();

         $allcateg = $category->searchTotal($dataForm);
         $totalSearch = ($allcateg)->count();                  
        
        return view('admin.produtoResource.list-groups', compact('categories', 'total', 'dataForm', 'list_categories', 'totalPageSearch', 'totalSearch', 'idcateg' ));

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
