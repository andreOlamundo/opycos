<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Entities\Admin;
use App\Entities\Cliente;
use App\Entities\Vendedor;
use Illuminate\Database\Eloquent\Model;
//use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Auth;
use DB;

class AdminController extends Controller
{
   /* public function __construct()
    {
      $this->middleware('auth');
    }*/


    /*
    *
    *
    *Index pedidosopycos.com.br
    */
    public function index()
    {

       $admins = Admin::orderBy('id', 'desc')->paginate(5);
        //$produtos = Produto::all();
        $totalPageSearch = ($admins)->count();
        $total = Admin::all()->count();
        return view('admin.adminResource.list-admins', compact('admins', 'total', 'totalPageSearch'));
      
    }

     public function users()
    {
        $total = Admin::all()->count();
        $totalC = Cliente::all()->count();
        $totalV = Vendedor::all()->count();
      
        return view('user.dashboard', compact('total', 'totalC', 'totalV'));
      
    }

         public function usersList()
    {

        $total = Admin::all()->count();
        $totalC = Cliente::all()->count();
        $totalV = Vendedor::all()->count();
      
        return view('user.list-dashboard', compact('total', 'totalC', 'totalV'));
      
    }  

    

        public function listCadastro() {        
       
        $admins = Admin::where([            
            'status' => 'active',
            'id' => Auth::id() 
        ])->get();     
        
        return view('admin.adminResource.list-cadastroAdmin', compact('admins'));
    }

   

     public function create()
    {



      return view('admin.adminResource.include-admin');
    }

    public function login()
    {

      return view('auth.login-adm');
    }


    public function postLogin(Request $request)
    {
     $validator = validator($request->all(),[
       'email' => 'required|min:3|max:100',
       'password' => 'required|min:3|max:100',       
     ]);



         if ( $validator->fails()){
      return redirect('/admin/login')->withErros($validator)->withInput();
    }

 
    $credentials = ['email' => $request->get('email'), 'password' => $request->get('password'), 'status' => 'active'];


    if (auth()->guard('admin')->attempt($credentials))
    {
      return redirect('/admin');
    }  


   if (auth()->guard('cliente')->attempt($credentials))
      {
                return redirect('/cliente');
            }

             if  (auth()->guard('vendedor')->attempt($credentials))
              {

                 return redirect('/vendedor');

              }

              else
              

      $errors = [$this->username() => trans('auth.failed')];

    {

      if ($request->expectsJson()) {
        return response()->json($errors, 422);
      }

      return redirect()->back()
      ->withInput($request->only($this->username(), 'remember'))
      ->withErrors($errors);

    } 

  }

  public function username()
  
  {
    return 'email';
  }


   /* public function logout()
    {
      auth()->guard('admin')->logout();
      return redirect('/admin/login');
    }*/
    public function logout(Request $request)
    {
      $this->guard()->logout();

      $request->session()->invalidate();

      return redirect('/admin/login');
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
      return Auth::guard();
    }

    public function store(Request $request) {
       try {
        $admins = new Admin;
        $admins->name = $request->name;              
        $admins->email = $request->email;
        $admins->tel = $request->tel;
        $admins->cel = $request->cel; 
        $admins->cpf = $request->cpf;
        //$vendedores->status = $request->status;       
       // $vendedores->cep = $request->cep;       
       // $vendedores->endereço = $request->endereço;
        //$vendedores->numero = $request->numero;
       // $vendedores->complemento = $request->complemento;
       // $vendedores->bairro = $request->bairro;
       // $vendedores->cidade = $request->cidade;
        //$vendedores->estado = $request->estado;
        //$vendedores->notes = $request->notes;
        $admins->password = bcrypt($request->password);
        


        $admins->save();
        return redirect()->route('admins.index')->with('message', 'Administrador(a) cadastrado(a) com sucesso!');
      } catch (QueryException $e) {

         return redirect()
        ->route('admins.create')
        ->with('message', 'Não foi possível realizar o cadastro (Já existem lançamentos idênticos em nossos registros!)')
        ->withInput();

      }





    }

     public function show($id) {
        //
    }

    public function edit($id) {
        $admins = Admin::findOrFail($id);
        return view('admin.adminResource.alter-admin', compact('admins'));
    }

    public function update(Request $request, $id) {
        $admins = Admin::findOrFail($id); 
        $admins->name = $request->name;               
        $admins->email = $request->email;
        //$admins->tel = $request->tel;

       // $admins->cel = $request->cel;        
      //  $vendedores->cep = $request->cep;       
      //  $vendedores->endereço = $request->endereço;
       // $vendedores->numero = $request->numero;
      //  $vendedores->complemento = $request->complemento;
     //   $vendedores->bairro = $request->bairro;
       // $vendedores->cidade = $request->cidade;
      // $vendedores->estado = $request->estado;
       // $admins->notes = $request->notes;
       // $vendedores->status = $request->status;
       // $admins->password = $request->password;
        $admins->password = bcrypt($request->password);
        

        $admins->save();
        return redirect()->route('admins.index')->with('message', 'Administrador(a) alterado(a) com sucesso!');
    }

    public function destroy($id) {

           try {
        $admins = Admin::findOrFail($id);
        $admins->delete();
        return redirect()->route('admins.index')->with('message', 'Administrador(a) excluído(a) com sucesso!');
    } catch (QueryException $e) {
         return redirect()
        ->route('admins.index')
        ->with('message-failure', 'Não foi possível excluir o usuário (Usuário já possui pedido gerado!)');       
    }


        
       
    }

    public function searchAdmin(Request $request, Admin $admin)
    {
        //$dadosAdmins=DB::table('admins')->get();
        $dataForm = $request->except('_token');
        $admins = $admin->search($dataForm);
        $total = Admin::all()->count(); 
        $idadmin = $request->id;   

          $allAdmins = $admin->searchTotal($dataForm);
        $totalSearch = ($allAdmins)->count();
        $totalPageSearch = ($admins)->count();        
        
        return view('admin.adminResource.list-admins', compact('admins', 'total', 'dataForm', 'idadmin', 'totalSearch', 'totalPageSearch', 'idadmin' ));

    }


  }



