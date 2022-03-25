<?php


namespace App\Http\Controllers;

use App\Entities\ClienteInter;
use App\Entities\Vendedor;
use App\Entities\CadastroWhatsapp;
//use App\Http\Requests;

use App\Http\Controllers\Controller;
//use Illuminate\Support\Facades\Auth;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Exception;
use DB;


class ClienteInterController extends Controller
{



    public function index(Request $request) {
        $clientes = ClienteInter::where([          

            'vendedor_id' => Auth::id()          
        ])->take(100)->get();

        $clientesWhatsapp = CadastroWhatsapp::orderBy('id', 'desc')->paginate(5);
        //$produtos = Produto::all();
        $dadosClientes = ClienteInter::where([            
            'vendedor_id' => Auth::id()            
        ])->get(); 

        $dadosStatus=ClienteInter::select('id', 'status')->where('status', $request->id)->take(100)->get();

           $totalPageSearch = ($clientes)->count(); 

        $total = ClienteInter::where([            
           'vendedor_id' => Auth::id()            
       ])->count();

         $vendedores = Vendedor::where([            
        'status' => 'active',
        'id' => Auth::id() 
    ])->get(); 

        return view('vendedor.clienteResource.list-clientes', compact('clientes', 'total', 'dadosClientes', 'clientesWhatsapp', 'totalPageSearch', 'vendedores'));
    }





    public function login()
    {

      return view('auth.login-cliente');
  }


  public function create() {

    $vendedores = Vendedor::where([            
        'status' => 'active',
        'id' => Auth::id() 
    ])->get();        

    return view('vendedor.clienteResource.include-cliente', compact('vendedores'));
}

public function createWhatsapp() {


    return view('vendedor.clienteResource.include-clientewhats');
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


   return view('vendedor.clienteResource.list-clientewhats', compact('clientes', 'query', 'clienteAuth'));


}

public function indexWhatsapp($id) {

    $clientes = CadastroWhatsapp::findOrFail($id);
       // $dataForm = $request->except('_token');           
       //$clientesAuthent = $cliente->search($dataForm);
    $clienteAuth = CadastroWhatsapp::where([
        'status' => 'R',
        'id' =>   $id

    ])->get();

    return view('vendedor.clienteResource.list-clientewhats', compact('clientes','clienteAuth'));

}

public function vendedores($vendedor_id)
{

    $vendedor = Vendedor::findOrFail($vendedor_id);


    return view('vendedor.vendedorResource.list-vendedores', compact('vendedor'));


}





/*public function store(Request $request) {
    try {
        $clientes = new ClienteInter;
        $clientes->name = $request->name;               
        $clientes->email = $request->email;
        $clientes->tel = $request->tel;
        $clientes->celInput = $request->celInput;
        $clientes->cel = $request->celInput;

        $clientes->cpf = $request->cpf;
        $clientes->cnpj = $request->cnpj; 
        $clientes->tipo = $request->tipo;
        $clientes->cep = $request->cep;
        $clientes->vendedor_id = $request->vendedor_id;               
        $clientes->endereço = $request->endereço;
        $clientes->numero = $request->numero;
        $clientes->complemento = $request->complemento;
        $clientes->bairro = $request->bairro;
        $clientes->cidade = $request->cidade;
        $clientes->estado = $request->estado;
        $clientes->password = bcrypt($request->password);


           if ( $clientes->cnpj == NULL  &&  $clientes->cpf == NULL ){      
                           
        
        return redirect()->route('clientesinter.create')->with('message-failure', 'Informe o CPF ou CNPJ.')->withInput();

        }


        //dd(bcrypt($request->password));

        $clientes->save();
        return redirect()->route('clientesinter.index')->with('message', 'Cliente gerado com sucesso!');
    } catch (QueryException $e) {

        return redirect()
        ->route('clientesinter.create')
        ->with('message', 'Não foi possível realizar o cadastro (Já existem lançamentos idênticos em nossos registros!)')->withInput();



    }

}*/

 public function store(Request $request) {

try {
          $clientes = new ClienteInter; 
          $clientes->cnpj = $request->cnpj;
          $clientes->cpf = $request->cpf;

          if ( $clientes->cnpj == NULL  &&  $clientes->cpf == NULL ){                                   
            return redirect()->route('clientesinter.create')
            ->with('message-failure', 'Informe o CPF ou CNPJ.')
            ->withInput();
          } 

          if (isset($clientes->cnpj)) {     
            $clientes->name = $request->name_contato;  
            $clientes->email = $request->email;
            $clientes->tel = $request->tel;
            $clientes->cel = $request->cel;
            $clientes->celInput = $request->cel;
            $clientes->vendedor_id = $request->vendedor_id;
            $clientes->tipo = $request->tipo;


            $clientes->cnpj =  preg_replace( '/[^0-9]/is', '', $clientes->cnpj );   
              // Valida tamanho
            if (strlen($clientes->cnpj) != 14){
             return redirect()
             ->route('clientesinter.create')
             ->with('message-failure', 'Faltam digitos!')
             ->withInput();
           }

              // Verifica se todos os digitos são iguais
           if (preg_match('/(\d)\1{13}/', $clientes->cnpj)){
             return redirect()
             ->route('clientesinter.create')
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
            ->route('clientesinter.create')
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
          return redirect()->route('clientesinter.index')->with('message', 'Cliente(a) cadastrado(a) com sucesso!');

        } 

          $clientes->name = $request->name;            
          $clientes->email = $request->email;
          $clientes->tel = $request->tel;
          $clientes->cel = $request->cel; 
          //dd( $clientes->cel);
          $clientes->celInput = $request->cel;
          $clientes->vendedor_id = $request->vendedor_id;
          $clientes->tipo = $request->tipo;
        
            // Extrai somente os números
          $clientes->cpf  = preg_replace( '/[^0-9]/is', '', $clientes->cpf );

            // Verifica se foi informado todos os digitos corretamente
          if (strlen($clientes->cpf) != 11) {
            return redirect()
            ->route('clientesinter.create')
            ->with('message-failure', 'Faltam digitos!')
            ->withInput();
          }

              // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
          if (preg_match('/(\d)\1{10}/', $clientes->cpf)) {
            return redirect()
            ->route('clientesinter.index')
            ->with('message-failure', 'Sequência de digitos invalidos!')
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
              ->route('clientesinter.create')
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
          $clientes->password = bcrypt($request->password);
          $clientes->save();
          return redirect()->route('clientesinter.index')->with('message', 'Cliente(a) cadastrado(a) com sucesso!');
        

  } catch (QueryException $e) {

   $validator = validator($request->all(),[
       'cel' => 'unique:clientes'      
     ]);

     if ( $validator->fails()){
      return redirect()
   ->route('clientesinter.create')
   ->with('message', 'Já existe o mesmo número de celular cadastrado em nossos registros')->withInput();
 }

     // return redirect('/admin/login')->withErros($validator)->withInput();
    //dd($e);
    //dd($e->getMessage());

return redirect()
   ->route('clientesinter.create')
   ->with('message-failure', 'Não foi possível realizar o cadastro.')
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
    $clientes = ClienteInter::findOrFail($id);

    $vendedores = Vendedor::where([            
        'status' => 'active',
        'id' => Auth::id() 
    ])->get(); 

    return view('vendedor.clienteResource.alter-cliente', compact('clientes', 'vendedores'));
}

public function editInter($id) {
    $clientes = ClienteInter::findOrFail($id);
    return view('vendedor.clienteResource.alter-cliente', compact('clientes'));
}




public function editar($id) {
    $clientes = ClienteInter::findOrFail($id);
    return view('vendedor.clienteResource.alter-clientewhats', compact('clientes'));
}

/*public function update(Request $request, $id) {
    $clientes = ClienteInter::findOrFail($id); 
    $clientes->name = $request->name;               
    $clientes->email = $request->email;
    $clientes->tel = $request->tel;
    $clientes->cel = $request->cel; 
    $clientes->cpf = $request->cpf;
    $clientes->cnpj = $request->cnpj;
    $clientes->tipo = $request->tipo; 
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

    CadastroWhatsapp::where([
        'preview_id'  => $id

    ])->update([
        'cel' => $cel,
        'name' => $name,
        'email' => $email

    ]);

    $clientes->save();
    return redirect()->route('clientesinter.index')->with('message', 'Cliente alterado com sucesso!');       

    

}*/


 public function update(Request $request, $id) {
  try {

          $this->middleware('VerifyCsrfToken');
          $clientes = ClienteInter::findOrFail($id);  

          $clientes->cnpj = $request->cnpj;
          $clientes->cpf = $request->cpf;
          if ( $clientes->cnpj == NULL  &&  $clientes->cpf == NULL ){                                   
            return redirect()->route('clientesinter/{id}/edit', $id)
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

          
        
            $clientes->cnpj =  preg_replace( '/[^0-9]/is', '', $clientes->cnpj );   
              // Valida tamanho
            if (strlen($clientes->cnpj) != 14)
            {
             return redirect()->route('clientesinter/{id}/edit', $id)->with('message-failure', 'Faltam digitos!')->withInput();
           }

              // Verifica se todos os digitos são iguais
           if (preg_match('/(\d)\1{13}/', $clientes->cnpj)){
             return redirect()->route('clientesinter/{id}/edit', $id)
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
            return redirect()->route('clientesinter/{id}/edit', $id)
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
          return redirect()->route('clientesinter.index')->with('message', 'Cliente(a) Alterado(a) com sucesso!');

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
            return  redirect()->route('clientesinter/{id}/edit', $id)
            ->with('message-failure', 'Faltam digitos!')
            ->withInput();
          }

              // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
          if (preg_match('/(\d)\1{10}/', $clientes->cpf)) {
            return  redirect()->route('clientesinter/{id}/edit', $id)
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
              return  redirect()->route('clientesinter/{id}/edit', $id)
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
    return redirect()->route('clientesinter.index')->with('message', 'Cliente(a) alterado(a) com sucesso!');
  }
  catch (QueryException $e) {
      dd($e);
   return redirect()
   ->route('clientesinter.index')
   ->with('message-failure', 'Não foi possível alterar o usuário');       
 }


}

public function updateAuth() {


    $this->middleware('VerifyCsrfToken');
    $req = Request();
        //$mypassword = 'opycos123';
    $cpf = $req->input('cpf');
    $cnpj = $req->input('cnpj');
    $preview_id = $req->input('preview_id');
    $name = $req->input('name');
    $cel = $req->input('cel');
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
        'cel' => $cel,
            'status'  => 'A' // Aguardando Confirmação
        ])->exists();

    if( !$check_link ) {
        $req->session()->flash('mensagem-falha', 'Cliente não Localizado!');
        return redirect()->route('search-cliente');
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
                'name' => $name,
                'cel' => $cel,
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




    public function destroy($id) {
       try {
        $clientes = ClienteInter::findOrFail($id);
        $clientes->delete();
        return redirect()->route('clientesinter.index')->with('message', 'Cliente excluído com sucesso!');
    } catch (QueryException $e) {

       return redirect()
       ->route('clientesinter.index')
       ->with('message-failure', 'Não foi possível excluir o usuário (Usuário já possui pedido gerado!)');   

   }
}

public function searchCliente(Request $request, ClienteInter $cliente)
{

    $vendedores = Vendedor::where([            
        'status' => 'active',
        'id' => Auth::id() 
    ])->get();

    //dd($vendedores);



    $dadosClientes = ClienteInter::where([            
            'vendedor_id' => Auth::id()            
        ])->get(); 

   $dadosStatus=DB::table('clientes')->get();

    $clientes = ClienteInter::where(['vendedor_id' => Auth::id()          
        ])->take(100)->get(); 


    $idcliente = $request->id;
    $status = $request->status;
    $tipo = $request->tipo;
    $idvendedor = $request->vendedor_id;
    //dd($idvendedor);
     
     //dd($idvendedor);
   $dataForm = $request->except('_token');

   
    $clientes = $cliente->search($dataForm);

     /*if (empty($idcliente) && empty($status))
     {
       return redirect()->route('clientesinter.index')->with('message-failure', 'Cliente Não localizado.');
     }*/
       // $idvendedor = $request->vendedor_id;
      //  $status = $request->status;

      
              

    return view('vendedor.clienteResource.list-clientes', compact('clientes','dataForm', 'dadosClientes',  'dadosStatus', 'idcliente', 'status', 'tipo', 'vendedores'));

}

    /*public function searchClienteWhats(Request $request, CadastroWhatsapp $cliente)
    {
          
        $dataForm = $request->except('_token');
        $clientes = $cliente->search($dataForm);

                   
        
        return view('vendedor.clienteResource.alter-clientewhats', compact('clientes','dataForm'));

    }*/



    public function searchClienteWhats(CadastroWhatsapp $cliente)
    {


        $this->middleware('VerifyCsrfToken');
        $req = Request();
        $id = $req->input('id');
        $name = $req->input('name');
        $celInput = $req->input('celInput');
        $celInput1 = $req->input('celInput1');


        
          /*   $id_cliente = DB::table('cadastro_whatsapp')->select('id')->where([
              'celInput' => $celInput,
              'name' => $name,
              'id' =>   $id,                         
              'status' => 'A'            
               
          ])->get();       */

          if ($celInput == NULL) {

             $clientes = CadastroWhatsapp::where([
              'celInput' => $celInput1,
              'name' => $name,
              'id' =>   $id,                         
              'status' => 'A'                          
          ])->get();

         } else {


            $clientes = CadastroWhatsapp::where([
              'celInput' => $celInput,
              'name' => $name,
              'id' =>   $id,                         
              'status' => 'A'                          
          ])->get();

        }

//dd($id_cliente);




    //  return redirect()->back();


       // return redirect('/cadastro/whatsapp')->withErros($validator)->withInput();               



         //  $dataForm = $request->intersect('id','name','celInput');
         //$clientes = $cliente->search($dataForm);

        return view('vendedor.clienteResource.alter-clientewhats', compact('clientes'));





        


    }



    public function linkWhats() {



       $vendedores = Vendedor::where([            
        'status' => 'active',
        'id' => Auth::id() 
    ])->get(); 

       $previews = CadastroWhatsapp::where([            
        'status' => 'A',
        'vendedor_id' => Auth::id() 
    ])->get();

       // dd($links);

       return view('vendedor.clienteResource.link-whatsapp', compact('vendedores', 'previews' ));
   }


   public function linkWhatsapp() {

    $mypassword = 'opycos123';
    $vendedores = Vendedor::where([            
        'status' => 'active',
        'id' => Auth::id() 
    ])->get(); 
    $previews = CadastroWhatsapp::where([            
        'status' => 'E',
        'vendedor_id' => Auth::id() 
    ])->get();

       // dd($links);

    return view('vendedor.clienteResource.link-whatsapp', compact('vendedores', 'previews', 'mypassword' ));
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



      /*  $cel1 = '55'.$req->input('cel1');
        $cel1 = str_replace('(', '', $cel1);
        $cel1 = str_replace(')', '', $cel1);
        $cel1 = str_replace(' ', '', $cel1);
        $cel1 = str_replace('-', '', $cel1);*/


       /* if ($req->input('cel') == NULL) {
           $celInput = $req->input('cel1');   

           $validator = validator($req->all(),
            [
                'cel1' => 'required|min:14|max:15'
            ]);

           if ( $validator->fails()){
              return redirect()->route('cliente.linkInterWhatsapp')->with('message', 'Não foi possível realizar o cadastro, revisar o campo: Celular')->withInput();
          }


          $idusuario = Auth::id();
          $preview_id = CadastroWhatsapp::consultaId([    
            'vendedor_id' => $idusuario,                      
            'status'  => 'E' //Enviado            
        ]);


          if( empty($preview_id) ) {


            $preview_novo = ClienteInter::create([

                'user_id' => NULL,                
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
            'user_id' => NULL,
            'cel' => $cel1,
            'celInput' => $celInput,
            'name' => $name,
            'email' => $email,            
            'status'     => $status                   

            
        ]);




        $req->session()->flash('mensagem-sucesso', 'Pré-Cadastro gerado com sucesso!
            Clique no botão Gerar Link em seguida envie a mensagem ao cliente.');

        return redirect()->route('cliente.linkInterWhatsapp');






                    }*/ 

                       $celInput = $req->input('cel');      

                       $validator = validator($req->all(),
                        [
                            'cel' => 'required|min:14|max:15'
                        ]);

                       if ( $validator->fails()){
                          return redirect()->route('cliente.linkInterWhatsapp')->with('message', 'Não foi possível realizar o cadastro, revisar o campo: Celular')->withInput();
                      }


                      $idusuario = Auth::id();
                      $preview_id = CadastroWhatsapp::consultaId([    
                        'vendedor_id' => $idusuario,                      
                            'status'  => 'E' //Enviado            
                        ]);


                      if( empty($preview_id) ) {


                        $preview_novo = ClienteInter::create([

                            'user_id' => NULL,                
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
                        'user_id' => NULL,
                        'cel' => $cel,
                        'celInput' => $celInput,
                        'name' => $name,
                        'email' => $email,            
                        'status'     => $status                   


                    ]);


                    

                    $req->session()->flash('mensagem-sucesso', 'Pré-Cadastro gerado com sucesso!
                        Clique no botão Gerar Link em seguida envie a mensagem ao cliente.');

                    return redirect()->route('cliente.linkInterWhatsapp');
                

} catch (QueryException $e) {

   return redirect()
   ->route('cliente.linkInterWhatsapp')
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
        'vendedor_id' => $idusuario,
            'status'  => 'E' // Enviado
        ])->exists();

    if( !$check_link ) {
        $req->session()->flash('mensagem-falha', 'Cliente não Localizado!');
        return redirect()->route('cliente.linkInterWhatsapp');
    }



    CadastroWhatsapp::where([
        'preview_id'  => $preview_id

    ])->update([
        'status' => 'A'
    ]);

    ClienteInter::where([
        'id'  => $preview_id



    ])->update([
        'status' => 'A'
    ]);

    $previews = CadastroWhatsapp::where([
        'preview_id' =>   $preview_id,        
        'status' => 'E',
        'vendedor_id' => Auth::id() 
    ])->get();

    $vendedores = Vendedor::where([            
        'status' => 'active',
        'id' => Auth::id() 
    ])->get(); 


    $req->session()->flash('mensagem-sucesso', 'Informações gravadas com sucesso.
        Agora o cliente será capaz de prosseguir com cadastro, é necessario apenas que ele confirme o número de celular.
        Enquanto o cliente não concluir o cadastro, seu STATUS permanecerá "Aguardando Retorno"');        



       // dd($links);

    return view('vendedor.clienteResource.link-whatsapp', compact('vendedores', 'previews', 'mypassword' ));
}



public function whatsapplist (){
    $clientesWhatsapp = CadastroWhatsapp::orderBy('id', 'desc')->paginate(5);
    $total = CadastroWhatsapp::all()->count();
    return view('vendedor.clienteResource.list-whatsapp', compact('clientesWhatsapp', 'total'));
}

public function postWhatsapp(Request $request)
{
   $validator = validator($request->all(),[
     'cel' => 'required|min:14|max:15',

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









}







