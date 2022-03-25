<?php

namespace App\Http\Controllers;

use App\Entities\Cliente;
use App\Entities\Vendedor;
use App\Entities\CadastroWhatsapp;
use App\Entities\Pedido;
//use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
//use Illuminate\Support\Facades\Auth;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Exception;
use DB;
use App\Entities\Profissional;
use App\Entities\Cod_registro_prof;

use App\Http\Controllers\Profissional as ControllersProfissional;
//use Profissional as GlobalProfissional;

class ClienteController extends Controller
{
 
    public function index(Request $request) {
        //$clientes = Cliente::orderBy('id', 'desc')->paginate(7);
        $clientes = Cliente::where('ativo', '!=', 'N')->get();
        $clientesWhatsapp = CadastroWhatsapp::orderBy('id', 'desc')->get();
        //$produtos = Produto::all();
         $dadosClientes = Cliente::where('ativo', '!=', 'N')->get(); 
        $dadosVendedores=DB::table('vendedores')->get();
        $dadosStatus=Cliente::select('id', 'status')->where('status', $request->id)->get();
        $total = Cliente::all()->count();
         $totalPageSearch = ($clientes)->count();
        return view('admin.clienteResource.list-clientes', compact('clientes', 'total', 'dadosVendedores', 'dadosClientes', 'clientesWhatsapp', 'dadosStatus', 'totalPageSearch'));
    }

    public function clienteindex() {        
       
        $clientes = Cliente::where([            
            'status' => 'R',
            'status' => 'active',
            'id' => Auth::id() 
        ])->get();     
        
        return view('cliente.clienteResource.list-clientewhats', compact('clientes'));
    }


    public function indexInter() {        
       
        $clientes = Cliente::where([            
            'status' => 'active'             
        ])->get();   
          
        
        return view('vendedor.clienteResource.list-clientes', compact('clientes'));
    }



     public function login()
    {

      return view('auth.login-admin');
    }


    public function create() {

        $vendedores=DB::table('vendedores')->get();      
        $profissionais=DB::table('profissionais')->get();

        return view('admin.clienteResource.include-cliente', compact('vendedores', 'profissionais'));
    }

        public function createInter() {

            $vendedores = Vendedor::where([            
            'status' => 'active',
            'id' => Auth::id() 
        ])->get();        

        return view('vendedor.clienteResource.include-cliente', compact('vendedores'));
    }


        public function indexWhats($id) {


             $query = CadastroWhatsapp::findOrFail($id);
            $clientes = CadastroWhatsapp::where([                      
            'status' => 'A' ,
            'id'     => $id     

        ])->get();   

           $clienteAuth = CadastroWhatsapp::where([
            'status' => 'R',
            'id' =>   $id
             
        ])->get();


		
            
        return view('admin.clienteResource.list-clientewhats', compact('clientes', 'query', 'clienteAuth'));


    }

            public function indexWhatsapp($id) {

        $clientes = CadastroWhatsapp::findOrFail($id);
       // $dataForm = $request->except('_token');           
       //$clientesAuthent = $cliente->search($dataForm);
               $clienteAuth = CadastroWhatsapp::where([
            'status' => 'R',
            'id' =>   $id
             
        ])->get();

        return view('admin.clienteResource.list-clientewhats', compact('clientes','clienteAuth'));

    }



    public function vendedores($vendedor_id)
    {

        $vendedor = Vendedor::findOrFail($vendedor_id);


return view('cliente.vendedorResource.list-vendedores', compact('vendedor'));
        

    }


   /* public function autentic()
    {


        
        $this->middleware('VerifyCsrfToken');
        $req = Request();
        $cel = $req->input('cel');
        $status = $req->input('status');
        $id = $req->input('id');



        Cliente::where([

            'cel'  => $cel,
            'status' => $status           

           
            ])->update([
                'status' => 'R'
            ]);

             

            $req->session()->flash('mensagem-sucesso', 'Cliente validado com sucesso! Preencha os campos em branco');

        return redirect()->route('indexWhats')->withInput();   

    }*/

 public function store(Request $request) {

try {
          $clientes = new Cliente; 
          $clientes->cnpj = $request->cnpj;
          $clientes->cpf = $request->cpf;
          //$profissionais = new Profissional;
          //$profissao = $request->profissional;
          //$profissionais->id = $profissao;
          //$cod_registro = $request->cod_registro_id; 
          //dd($cod_registro);
         /* if ($cod_registro != NULL) { 
                 // dd($cod_registro);   
            $cod_registro_id = new Cod_registro_prof; 
            $cod_registro_id->cod_registro_id = $cod_registro;
            $cod_registro_id->cod_prof_id = $profissao;   
            $cod_registro_id->save();    
            
            
            //$cod_cliente_id
            }*/

          if ( $clientes->cnpj == NULL  &&  $clientes->cpf == NULL ){                                   
            return redirect()->route('clientes.create')
            ->with('message-failure', 'Informe o CPF ou CNPJ.')
            ->withInput();
          } 
          
          if (isset($clientes->cnpj)) {     
            $clientes->name = $request->name_contato;  
            $clientes->email = $request->email;
            $clientes->tel = $request->tel;
            $clientes->cel = $request->cel;
            $clientes->celInput = $request->cel;
            //dd( $clientes->celInput);
            $clientes->vendedor_id = $request->vendedor_id;
            $clientes->tipo = $request->tipo;


            $clientes->cnpj =  preg_replace( '/[^0-9]/is', '', $clientes->cnpj );   
              // Valida tamanho
            if (strlen($clientes->cnpj) != 14){
             return redirect()
             ->route('clientes.create')
             ->with('message-failure', 'Faltam digitos!')
             ->withInput();
           }

              // Verifica se todos os digitos são iguais
           if (preg_match('/(\d)\1{13}/', $clientes->cnpj)){
             return redirect()
             ->route('clientes.create')
             ->with('message-failure', 'Sequência de digitos inválidos!')
             ->withInput();
           }

              // Valida primeiro dígito verificador
           for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++)
           {
            $soma += $clientes->cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
          }

          $resto = $soma % 11;

          if ($clientes->cnpj[12] != ($resto < 2 ? 0 : 11 - $resto)){
            return redirect()
            ->route('clientes.create')
            ->with('message-failure', 'CNPJ Inválido!. Digite um CNPJ válido!')
            ->withInput();
          }

              // Valida segundo dígito verificador
          for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++)
          {
            $soma += $clientes->cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
          }

          $resto = $soma % 11;

          $clientes->cnpj[13] == ($resto < 2 ? 0 : 11 - $resto); 
          //$clientes->status = $request->status;       
          $clientes->cep = $request->cep;       
          $clientes->endereço = $request->endereço;
          $clientes->numero = $request->numero;
          $clientes->complemento = $request->complemento;
          $clientes->bairro = $request->bairro;
          $clientes->cidade = $request->cidade;
          $clientes->estado = $request->estado;
          //$clientes->notes = $request->notes;
          //$clientes->profissao = $profissao;
          //$clientes->cod_registro_id = $cod_registro;
          $clientes->razao_social = $request->razao_social;
          $clientes->password = bcrypt($request->password);
          $clientes->save();
          return redirect()->route('clientes.index')->with('message', 'Cliente(a) cadastrado(a) com sucesso!');

        } 

          $clientes->name = $request->name;            
          $clientes->email = $request->email;
          $clientes->tel = $request->tel;
          $clientes->cel = $request->cel;
          $clientes->celInput = $request->cel;
         // dd( $clientes->celInput);        
          $clientes->vendedor_id = $request->vendedor_id;
          $clientes->tipo = $request->tipo;
        
            // Extrai somente os números
          $clientes->cpf  = preg_replace( '/[^0-9]/is', '', $clientes->cpf );

            // Verifica se foi informado todos os digitos corretamente
          if (strlen($clientes->cpf) != 11) {
            return redirect()
            ->route('clientes.create')
            ->with('message-failure', 'Faltam digitos!')
            ->withInput();
          }

              // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
          if (preg_match('/(\d)\1{10}/', $clientes->cpf)) {
            return redirect()
            ->route('clientes.create')
            ->with('message-failure', 'CPF - Sequência de digitos invalidos!')
            ->withInput();
          }

              // Faz o calculo para validar o CPF
          for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
              $d += $clientes->cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($clientes->cpf[$c] != $d) {
              return redirect()
              ->route('clientes.create')
              ->with('message-failure', 'CPF inválido. Informe um CPF válido!')
              ->withInput();
            }
          }                        
              //$clientes->status = $request->status;       
          $clientes->cep = $request->cep;       
          $clientes->endereço = $request->endereço;
          $clientes->numero = $request->numero;
          $clientes->complemento = $request->complemento;
          $clientes->bairro = $request->bairro;
          $clientes->cidade = $request->cidade;
          $clientes->estado = $request->estado;
          //$clientes->notes = $request->notes;
          //$clientes->razao_social = $request->razao_social;
          //$clientes->profissao = $profissao;
          //$clientes->cod_registro_id = $cod_registro;
          $clientes->password = bcrypt($request->password);                  
         $clientes->save();
         
         return redirect()->route('clientes.index')->with('message', 'Cliente(a) cadastrado(a) com sucesso!');

         

       
        

  } catch (QueryException $e) {

     //dd($e);

       $validator = validator($request->all(),[
       'cel' => 'unique:clientes'      
     ]);

     if ( $validator->fails()){
      return redirect()
   ->route('clientes.create')
   ->with('message-failure', 'Já existe o mesmo número de celular cadastrado em nossos registros')->withInput();
 }

  return redirect()
   ->route('clientes.create')
   ->with('message-failure', 'Não foi possível realizar o cadastro CPF existe')
   ->withInput();

 }
}


    public function clientestore() {
try {

        $this->middleware('VerifyCsrfToken');
        $req = Request();
        $idcliente = $req->input('cel');
        $clienteName = $req->input('name');
        $clienteEmail = $req->input('email');
        $clienteTel = $req->input('tel');
        $clienteCpf = $req->input('cpf');
        $tipo = $req->input('tipo');
        $clienteCnpj = $req->input('cnpj');
        $clienteCep = $req->input('cep');
        $clienteEnd = $req->input('endereço');
        $clienteN = $req->input('numero');
        $clienteC = $req->input('complemento');
        $clienteB = $req->input('bairro');
        $clienteCity = $req->input('cidade');
        $clienteEst = $req->input('estado');
        $clientepass = bcrypt($req->input('password'));
                              
         $cliente = CadastroWhatsapp::where([
            'cel' => $idcliente,                      
                       ])->get();

         //$data=GroupProducts::select('grup_cod', 'id')->where('grup_categ_cod', $request->id)->take(100)->get();
        //var_dump($clientecel->id);
      //dd($cliente);


        if( empty($cliente) ) {
           $req->session()->flash('mensagem-falha', 'Cliente não localizado! Celular não consta em nossos registros');
            return redirect()->route('createWhatsapp');                    
        } else {

            Cliente::create([
            'cel'  =>  $idcliente,
            'name'  =>   $clienteName,
            'tipo' => $tipo,
            'email'  =>  $clienteEmail,
            'tel'  =>   $clienteTel,
            'cpf'  =>   $clienteCpf,
            'cnpj'  =>  $clienteCnpj,
            'cep'  =>  $clienteCep,
            'endereço'  => $clienteEnd,
            'numero'  =>   $clienteN,
            'complemento'  =>  $clienteC,
            'bairro'  =>   $clienteB,
            'cidade'  =>   $clienteCity,
            'estado'  =>   $clienteEst, 
            'password' => $clientepass           

            ]);

               //CadastroWhatsapp::create([
              //   'email' => $clienteEmail,
             //   'status' => 'R'
            //
           // ]);


                       
           $req->session()->flash('mensagem-sucesso', 'Cliente cadastrado sucesso!');

        return redirect()->route('cliente.primeiroacesso');   
               }

                              }    catch (QueryException $e) {

                      return redirect()
                        ->route('clientes.create')
                           ->with('message', 'Não foi possível realizar o cadastro (Já existem lançamentos idênticos em nossos registros!)')
                           ->withInput();

      }       
           
        



}


    public function show($id) {
        //
    }

    public function edit($id) {
        $clientes = Cliente::findOrFail($id);
        $dadosVendedores=DB::table('vendedores')->get();
        return view('admin.clienteResource.alter-cliente', compact('clientes', 'dadosVendedores'));
    }

        public function editInter($id) {
        $clientes = Cliente::findOrFail($id);
        return view('vendedor.clienteResource.alter-cliente', compact('clientes'));
    }




    public function editar($id) {
        $clientes = CadastroWhatsapp::findOrFail($id);
        return view('admin.clienteResource.list-clientewhats', compact('clientes'));
    }

   /* public function update(Request $request, $id) {
        $clientes = Cliente::findOrFail($id); 
        $clientes->name = $request->name;               
        $clientes->email = $request->email;
        $clientes->tel = $request->tel;
        $clientes->cel = $request->cel;         
        $clientes->cpf = $request->cpf;
        $clientes->cnpj = $request->cnpj;
        $clientes->tipo = $request->tipo;
        $clientes->vendedor_id = $request->vendedor_id; 
        $clientes->cep = $request->cep;       
        $clientes->endereço = $request->endereço;
        $clientes->numero = $request->numero;
        $clientes->complemento = $request->complemento;
        $clientes->bairro = $request->bairro;
        $clientes->cidade = $request->cidade;
        $clientes->estado = $request->estado;
        $clientes->password = bcrypt($request->password);
        $cel = $request->input('cel');
        $name = $request->input('name');
        $email = $request->input('email');
        $tipo = $request->input('tipo');
         CadastroWhatsapp::where([
            'preview_id'  => $id
           
            ])->update([
                'cel' => $cel,
                'name' => $name,
                'email' => $email
              

            ]);

            if ( $clientes->cnpj == NULL  &&  $clientes->cpf == NULL ){      
                           
        
        return redirect()->route('clientes.edit', $id)->with('mensagem-falha', 'Informe CPF ou CNPJ.')->withInput();

        }

            $clientes->save();
        return redirect()->route('clientes.index')->with('message', 'Cliente alterado com sucesso!');
    }*/

    public function update(Request $request, $id) {
  try {

          $this->middleware('VerifyCsrfToken');
          $clientes = Cliente::findOrFail($id); 

         /* $cliente = Cliente::find($id);          
          if( empty($cliente->id)) {           
          return redirect()->route('clientes/{id}/edit', $id)
          ->with('message-failure', 'Informe o CPF ou CNPJ.')
          ->withInput();
          }*/ 

          $clientes->cnpj = $request->cnpj;
          $clientes->cpf = $request->cpf;
          if ( $clientes->cnpj == NULL  &&  $clientes->cpf == NULL ){                                   
            return redirect()->route('clientes/{id}/edit', $id)
            ->with('message-failure', 'Informe o CPF ou CNPJ.')
            ->withInput();
          } 

          if (isset($clientes->cnpj)) {     
            $clientes->name = $request->name_contato;  
            $clientes->email = $request->email;
            $clientes->tel = $request->tel;
            $clientes->cel = $request->cel;
            $clientes->celInput = $request->cel;
            $clientes->razao_social = $request->razao_social;
             $clientes->tipo = $request->tipo;

          
         /* if ($clientes->comissao > 100)
          {
        return  redirect()->route('clientes/{id}/edit', $id)
            ->with('message-failure', 'Comissão não pode ser superior a 100%.')
            ->withInput();
        }  */


            $clientes->cnpj =  preg_replace( '/[^0-9]/is', '', $clientes->cnpj );   
              // Valida tamanho
            if (strlen($clientes->cnpj) != 14)
            {
             return redirect()->route('clientes/{id}/edit', $id)->with('message-failure', 'Faltam digitos!')->withInput();
           }

              // Verifica se todos os digitos são iguais
           if (preg_match('/(\d)\1{13}/', $clientes->cnpj)){
             return redirect()->route('clientes/{id}/edit', $id)
             ->with('message-failure', 'Sequência de digitos inválidos!')
             ->withInput();
           }

              // Valida primeiro dígito verificador
           for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++)
           {
            $soma += $clientes->cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
          }

          $resto = $soma % 11;

          if ($clientes->cnpj[12] != ($resto < 2 ? 0 : 11 - $resto)){
            return redirect()->route('clientes/{id}/edit', $id)
            ->with('message-failure', 'CNPJ Inválido!. Digite um CNPJ válido!')
            ->withInput();
          }

              // Valida segundo dígito verificador
          for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++)
          {
            $soma += $clientes->cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
          }

          $resto = $soma % 11;

          $clientes->cnpj[13] == ($resto < 2 ? 0 : 11 - $resto); 
               //$clientes->status = $request->status;       
          $clientes->cep = $request->cep;       
          $clientes->endereço = $request->endereço;
          $clientes->numero = $request->numero;
          $clientes->complemento = $request->complemento;
          $clientes->bairro = $request->bairro;
          $clientes->cidade = $request->cidade;
          $clientes->estado = $request->estado;
               //$clientes->notes = $request->notes;
          $clientes->razao_social = $request->razao_social;
          $clientes->password = bcrypt($request->password);
          $clientes->save();
          return redirect()->route('clientes.index')->with('message', 'Cliente(a) Alterado(a) com sucesso!');

        } 

          $clientes->name = $request->name;            
          $clientes->email = $request->email;
          $clientes->tel = $request->tel;
          $clientes->cel = $request->cel;
          $clientes->celInput = $request->cel;
          $clientes->razao_social = $request->razao_social;
          $clientes->tipo = $request->tipo; 
         
            // Extrai somente os números
          $clientes->cpf  = preg_replace( '/[^0-9]/is', '', $clientes->cpf );

            // Verifica se foi informado todos os digitos corretamente
          if (strlen($clientes->cpf) != 11) 
          {
            return  redirect()->route('clientes/{id}/edit', $id)
            ->with('message-failure', 'Faltam digitos!')
            ->withInput();
          }

              // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
          if (preg_match('/(\d)\1{10}/', $clientes->cpf)) {
            return  redirect()->route('clientes/{id}/edit', $id)
            ->with('message-failure', 'Sequência de digitos invalidos!')
            ->withInput();
          }

              // Faz o calculo para validar o CPF
          for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
              $d += $clientes->cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($clientes->cpf[$c] != $d) 
            {
              return  redirect()->route('clientes/{id}/edit', $id)
              ->with('message-failure', 'CPF inválido. Informe um CPF válido!')
              ->withInput();
            }
          }                        
              //$clientes->status = $request->status;       
          $clientes->cep = $request->cep;       
          $clientes->endereço = $request->endereço;
          $clientes->numero = $request->numero;
          $clientes->complemento = $request->complemento;
          $clientes->bairro = $request->bairro;
          $clientes->cidade = $request->cidade;
          $clientes->estado = $request->estado;
               //$clientes->notes = $request->notes;
               //$clientes->razao_social = $request->razao_social;          
    //$clientes->notes = $request->notes;
    //$clientes->status = $request->status;

    if(isset($request->password)){
    $clientes->password = bcrypt($request->password);
    }
    
    $clientes->save();
    return redirect()->route('clientes.index')->with('message', 'Cliente(a) alterado(a) com sucesso!');
  }
  catch (QueryException $e) {
      dd($e);
   return redirect()
   ->route('clientes.index')
   ->with('message-failure', 'Não foi possível alterar o usuário');       
 }


}




        public function updateAuth(Request $request, CadastroWhatsapp $cliente) {


		$this->middleware('VerifyCsrfToken');
        $req = Request();
        //$mypassword = 'opycos123';
        $cpf = $req->input('cpf');
        $cnpj = $req->input('cnpj');
        $tipo = $req->input('tipo');
        $preview_id = $req->input('preview_id');
        $name = $req->input('name');
        $celInput = $req->input('celInput');
        $tel = $req->input('tel');
        $cep = $req->input('cep');
        $endereço = $req->input('endereço'); 
        $numero = $req->input('numero');
        $bairro = $req->input('bairro');
        $complemento = $req->input('complemento');
        $cidade = $req->input('cidade');
        $estado = $req->input('estado');                 
        $email = $req->input('email');
        $password = bcrypt($req->input('password'));
                
          
  

        $check_link = CadastroWhatsapp::where([
            'preview_id'  => $preview_id,            
            'celInput' => $celInput,
            'status'  => 'A' // Aguardando Confirmação
            ])->exists();

        if( !$check_link ) {
            $req->session()->flash('mensagem-falha', 'Cliente não Localizado!');
            return redirect()->route('search-cliente');
        }


           

        if ( $cnpj == NULL  && $cpf == NULL ){         
          
        $dataForm = $request->except('_token');
        $clientes = $cliente->search($dataForm);  

        
        $req->session()->flash('mensagem-falha', 'Informe o CPF ou CNPJ!');

        

           return view('admin.clienteResource.alter-clientewhats', compact('clientes','dataForm'));

        }



         CadastroWhatsapp::where([
            'preview_id'  => $preview_id
           
            ])->update([
                'status' => 'R' //Respondido

            ]);

                     Cliente::where([
            'id'  => $preview_id
           
            ])->update([
                'status' => 'R', //Respondido
                'cpf' => $cpf,
                'cnpj' => $cnpj,
                'tipo' => $tipo,
                'name' => $name,
                'celInput' => $celInput,
                'tel' => $tel,
                'cep' => $cep,
                'endereço' => $endereço,
                'numero' => $numero,
                'bairro' => $bairro,
                'complemento' => $complemento,
                'cidade' => $cidade,
                'estado' => $estado,
                'email' => $email,
                'password' => $password

            ]);

        $clientes = CadastroWhatsapp::where([
            'id' =>   $preview_id,        
            'status' => 'R'
             
        ])->get();


   $clienteAuth = CadastroWhatsapp::where([
            'preview_id' =>   $preview_id,        
            'status' => 'R'
             
        ])->get(); 



             $req->session()->flash('mensagem-sucesso', 'Informações gravadas com sucesso.');      
           
       // dd($links);

        return view('admin.clienteResource.list-clientewhats', compact('clienteAuth', 'clientes'));





















      /*$cliente = CadastroWhatsapp::findOrFail($id); 
        $cliente->name = $request->name;               
        $cliente->email = $request->email;
        $client->tel = $request->tel;
        $cliente->cel = $request->cel;*/        


        //$cliente->cpf = $request->cpf;
       // $cliente->cnpj = $request->cnpj;
       // $cliente->tipo = $request->tipo; 
       // $cliente->cep = $request->cep;       
      //  $cliente->endereço = $request->endereço;
      //  $cliente->numero = $request->numero;
       // $cliente->complemento = $request->complemento;
      //  $cliente->bairro = $request->bairro;
       // $cliente->cidade = $request->cidade;
        //$cliente->estado = $request->estado;
       // $cliente->password = $request->password;

        /*if( isset($cliente->cel) ) {
            $request->session()->flash('mensagem-falha', 'Cliente já possui cadastro em nosso sistema!');
            return redirect()->route('indexWhatsapp');
        }*/


    

     //$cliente->save();

     



        //return redirect()->route('search-cliente')->with('message', 'Cliente criado com sucesso!');   



       
        
        
    }


   /* public function autentic(Request $request, $id) {

       
        //$req = Request();
        $cel = $request->input('cel');
        $status = $request->input('status');
        $id = $request->input('id');



        Cliente::where([

            'cel'  => $cel,
            'status' => $status           

           
            ])->update([
                'status' => 'R'
            ]);




                 
        //$clientes->email = $request->email;
        //$clientes->tel = $request->tel;
       

        //$clientes->cpf = $request->cpf;
       // $clientes->cnpj = $request->cnpj;
       // $clientes->tipo = $request->tipo; 
        //$clientes->cep = $request->cep;       
       // $clientes->endereço = $request->endereço;
       // $clientes->numero = $request->numero;
        //$clientes->complemento = $request->complemento;
       // $clientes->bairro = $request->bairro;
       // $clientes->cidade = $request->cidade;
       // $clientes->estado = $request->estado;
       // $clientes->password = $request->password;
        

       
        return redirect()->route('indexWhatsapp')->with('message', 'Cliente alterado com sucesso!');
    }*/

    public function status($id) {
  try {
    $clientes = Cliente::findOrFail($id);  


    $pedido = Pedido::select('id')->where(['id_cliente' => $id])->first();  

          if(isset($pedido)) {           
          return redirect()->route('clientes.index')
   ->with('message-failure', 'Não foi possível realizar a exclusão, existem pedidos gerados para o Cliente');
          }

    $clientes->ativo = 'N';
    //$password = 'opycos@123';
    //$vendedores->password = bcrypt($password);     
    $clientes->save();
    return redirect()->route('clientes.index')->with('message', 'Registro Removido com sucesso!');
  }
  catch (QueryException $e) {
   return redirect()
   ->route('clientes.index')
   ->with('message-failure', 'Não foi possível realizar a exclusão');       
 }


}

    public function destroy($id) {

         try {
        $clientes = Cliente::findOrFail($id);
        $clientes->delete();
        return redirect()->route('clientes.index')->with('message', 'Cliente excluído com sucesso!');
    } catch (QueryException $e) {
            //dd($e);
         return redirect()
        ->route('clientes.index')
        ->with('message-failure', 'Não foi possível excluir o usuário');       
    }

       
    }

    public function searchCliente(Request $request, Cliente $cliente)
    {

        
        $dadosStatus=DB::table('clientes')->get();
       // $dadosClientes=DB::table('clientes')->get();
         $dadosClientes = Cliente::where('ativo', '!=', 'N')->get();
        $dadosVendedores=DB::table('vendedores')->get();

  

        $idcliente = $request->id;
        $idvendedor = $request->vendedor_id;
        $status = $request->status;
        $tipo = $request->tipo;

       // dd($tipo);

        $dataForm = $request->except('_token');
        $clientes = $cliente->search($dataForm);
       

        $allClientes = $cliente->searchTotal($dataForm);
        $totalSearch = ($allClientes)->count();
        
        $total = Cliente::all()->count();
         $totalPageSearch = ($clientes)->count();


        
        return view('admin.clienteResource.list-clientes', compact('clientes', 'total', 'dataForm', 'dadosClientes', 'dadosVendedores', 'dadosStatus', 'totalSearch', 'totalPageSearch', 'idcliente', 'idvendedor', 'status', 'tipo'));

    }




         public function searchClienteWhats(CadastroWhatsapp $cliente)
    {


        $this->middleware('VerifyCsrfToken');
        $req = Request();
        $id = $req->input('id');
        $name = $req->input('name');
        $celInput = $req->input('celInput');
      //  $celInput1 = $req->input('celInput1');


        
          /*   $id_cliente = DB::table('cadastro_whatsapp')->select('id')->where([
              'celInput' => $celInput,
              'name' => $name,
              'id' =>   $id,                         
              'status' => 'A'            
               
        ])->get();       */



            $clientes = CadastroWhatsapp::where([
              'celInput' => $celInput,
              'name' => $name,
              'id' =>   $id,                         
              'status' => 'A'                          
        ])->get();



//dd($id_cliente);

  


    //  return redirect()->back();
     

       // return redirect('/cadastro/whatsapp')->withErros($validator)->withInput();               
            
   
   
         //  $dataForm = $request->intersect('id','name','celInput');
         //$clientes = $cliente->search($dataForm);

        return view('admin.clienteResource.alter-clientewhats', compact('clientes'));
    




        
      

    }





public function linkWhats() {
    
       
        $vendedores=DB::table('vendedores')->get();
        $previews = CadastroWhatsapp::where([            
            'status' => 'A',
            'user_id' => Auth::id() 
        ])->get();

       // dd($links);

        return view('admin.clienteResource.link-whatsapp', compact('vendedores', 'previews' ));
    }
    

public function linkWhatsapp() {
    
        $mypassword = 'opycos123';     
        $vendedores=DB::table('vendedores')->get();
        $previews = CadastroWhatsapp::where([            
            'status' => 'E',
            'user_id' => Auth::id() 
        ])->get();

       // dd($links);

        return view('admin.clienteResource.link-whatsapp', compact('vendedores', 'previews', 'mypassword' ));
    }






    public function previewCadastroWhats(){

    try {

        $this->middleware('VerifyCsrfToken');
        $req = Request();

        $name = $req->input('name');  

        $email = $req->input('email');
        $msg = $req->input('msg');
        $vendedor_id = $req->input('vendedor_id');
        $status = $req->input('status');
        $password = bcrypt($req->input('password'));



        $cel = '55'.$req->input('cel');
        $cel = str_replace('(', '', $cel);
        $cel = str_replace(')', '', $cel);
        $cel = str_replace(' ', '', $cel);
        $cel = str_replace('-', '', $cel);



      /*   $cel1 = '55'.$req->input('cel1');
        $cel1 = str_replace('(', '', $cel1);
        $cel1 = str_replace(')', '', $cel1);
        $cel1 = str_replace(' ', '', $cel1);
        $cel1 = str_replace('-', '', $cel1);


       if ($req->input('cel') == NULL) {
           $celInput = $req->input('cel1');   

           $validator = validator($req->all(),
            [
                'cel1' => 'required|min:14|max:15'
            ]);

           if ( $validator->fails()){
              return redirect()->route('cliente.linkWhatsapp')->with('message', 'Não foi possível realizar o cadastro, revisar o campo: Celular')->withInput();
          }


          $idusuario = Auth::id();
          $preview_id = CadastroWhatsapp::consultaId([    
            'user_id' => $idusuario,                      
            'status'  => 'E' //Enviado            
        ]);


          if( empty($preview_id) ) {


            $preview_novo = Cliente::create([

                'user_id' => $idusuario,                
                'vendedor_id' => $vendedor_id,             
                'name' => $name,
                'cel' => $cel1,
                'celInput' => $celInput,
                'email' => $email,
                'msg' => $msg,
                'status' => $status,
                'password' => $password              

            ]);

            $preview_id = $preview_novo->id;

        }

        CadastroWhatsapp::create([
            'preview_id'  => $preview_id,
            'vendedor_id' => $vendedor_id,
            'user_id' => $idusuario,
            'cel' => $cel1,
            'celInput' => $celInput,
            'name' => $name,
            'email' => $email,            
            'status'     => $status                   

            
        ]);




        $req->session()->flash('mensagem-sucesso', 'Pré-Cadastro gerado com sucesso!
            Clique no botão Gerar Link em seguida envie a mensagem ao cliente.');

        return redirect()->route('cliente.linkWhatsapp');






                    }*/  

                       $celInput = $req->input('cel');      

                       $validator = validator($req->all(),
                        [
                            'cel' => 'required|min:14|max:15'
                        ]);

                       if ( $validator->fails()){
                          return redirect()->route('cliente.linkWhatsapp')->with('message', 'Não foi possível realizar o cadastro, revisar o campo: Celular')->withInput();
                      }


                      $idusuario = Auth::id();
                      $preview_id = CadastroWhatsapp::consultaId([    
                        'user_id' => $idusuario,                      
                            'status'  => 'E' //Enviado            
                        ]);


                      if( empty($preview_id) ) {


                        $preview_novo = Cliente::create([

                            'user_id' => $idusuario,                
                            'vendedor_id' => $vendedor_id,             
                            'name' => $name,
                            'cel' => $cel,
                            'celInput' => $celInput,
                            'email' => $email,
                            'msg' => $msg,
                            'status' => $status,
                            'password' => $password              

                        ]);

                        $preview_id = $preview_novo->id;

                    }

                    CadastroWhatsapp::create([
                        'preview_id'  => $preview_id,
                        'vendedor_id' => $vendedor_id,
                        'user_id' => $idusuario,
                        'cel' => $cel,
                        'celInput' => $celInput,
                        'name' => $name,
                        'email' => $email,            
                        'status'     => $status                   


                    ]);


                    

                    $req->session()->flash('mensagem-sucesso', 'Pré-Cadastro gerado com sucesso!
                        Clique no botão Gerar Link em seguida envie a mensagem ao cliente.');

                    return redirect()->route('cliente.linkWhatsapp');
                

} catch (QueryException $e) {

   return redirect()
   ->route('cliente.linkWhatsapp')
   ->with('message', 'Não foi possível realizar o cadastro (Já existem lançamentos idênticos em nossos registros!)')->withInput();


}
}

       
              

public function concluirLinkWhats() {


       
      	$this->middleware('VerifyCsrfToken');
        $req = Request();
        $mypassword = 'opycos123';
        $preview_id = $req->input('preview_id');    
        $idusuario = Auth::id();      

  

        $check_link = CadastroWhatsapp::where([
            'preview_id'  => $preview_id,            
            'user_id' => $idusuario,
            'status'  => 'E' // Enviado
            ])->exists();

        if( !$check_link ) {
            $req->session()->flash('mensagem-falha', 'Cliente não Localizado!');
            return redirect()->route('cliente.linkWhatsapp');
        }



         CadastroWhatsapp::where([
            'preview_id'  => $preview_id
           
            ])->update([
                'status' => 'A'
            ]);

                     Cliente::where([
            'id'  => $preview_id


           
            ])->update([
                'status' => 'A'
            ]);

                $previews = CadastroWhatsapp::where([
            'preview_id' =>   $preview_id,        
            'status' => 'E',
            'user_id' => Auth::id() 
        ])->get();

      $vendedores=DB::table('vendedores')->get();


             $req->session()->flash('mensagem-sucesso', 'Informações gravadas com sucesso.
                Agora o cliente será capaz de prosseguir com cadastro, é necessario apenas que ele confirme o número de celular.
                Enquanto o cliente não concluir o cadastro, seu STATUS permanecerá "Aguardando Retorno")');


        
      
           

       // dd($links);

        return view('admin.clienteResource.link-whatsapp', compact('vendedores', 'previews', 'mypassword' ));
    }



public function whatsapplist (){
    $clientesWhatsapp = CadastroWhatsapp::orderBy('id', 'desc')->paginate(7);
    $total = CadastroWhatsapp::all()->count();
    return view('admin.clienteResource.list-whatsapp', compact('clientesWhatsapp', 'total'));
}

    public function postWhatsapp(Request $request)
    {
     $validator = validator($request->all(),[
       'cel' => 'required|min:13|max:13',
       
     ]);

     if ( $validator->fails()){
      return redirect('/cadastro/whatsapp')->withErros($validator)->withInput();
    }
        //dd($request->get('email'));
    $credentials = ['cel' => $request->get('cel')];

    if (auth()->guard('cliente')->attempt($credentials))
    {
      return redirect('/cliente');
    } else {
      $errors = [$this->username() => trans('auth.failed')];

      if ($request->expectsJson()) {
        return response()->json($errors, 422);
      }

      return redirect()->back()
      ->withInput($request->only($this->username(), 'remember'))
      ->withErrors($errors);

    } 

  }



  public function findCodRegistro(Request $request)
  {   
      
      $data=Cliente::select('cod_registro_id', 'profissao')->where('id', $request->id)->orderBy('id', 'desc')->get();
    
     
      return response()->json($data);
  }





}







