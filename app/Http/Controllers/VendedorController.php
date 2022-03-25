<?php

namespace App\Http\Controllers;

use App\Entities\Vendedor;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

use App\Entities\Pedido;

//use Exception;
use DB;


class VendedorController extends Controller
{


  public function index() {
    $vendedores = Vendedor::where('status', '!=', 'inactive')->take(100)->get();   



    $dadosVendedores=DB::table('vendedores')->get();
    $total = Vendedor::all()->count();
    $totalPageSearch = ($vendedores)->count();
    return view('admin.vendedorResource.list-vendedores', compact('vendedores', 'total', 'dadosVendedores', 'totalPageSearch'));
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
          $vendedores->cnpj = $request->cnpj;
          $vendedores->cpf = $request->cpf;

          if ( $vendedores->cnpj == NULL  &&  $vendedores->cpf == NULL ){                                   
            return redirect()->route('vendedores.create')
            ->with('message-failure', 'Informe o CPF ou CNPJ.')
            ->withInput();
          } 

          if (isset($vendedores->cnpj)) {     
            $vendedores->name = $request->name_contato;  
            $vendedores->email = $request->email;
            $vendedores->tel = $request->tel;
            $vendedores->tipo = $request->tipo;
            $vendedores->cel = $request->cel;

            $vendedores->comissao = $request->comissao;
             $vendedores->comissao = str_replace( '%', '', $vendedores->comissao);
          if ($vendedores->comissao > 100)
          {
        return  redirect()->route('vendedores.create')
            ->with('message-failure', 'Comissão não pode ser superior a 100%.')
            ->withInput();
        }         
            $vendedores->cnpj =  preg_replace( '/[^0-9]/is', '', $vendedores->cnpj );   
              // Valida tamanho
            if (strlen($vendedores->cnpj) != 14){
             return redirect()
             ->route('vendedores.create')
             ->with('message-failure', 'Faltam digitos!')
             ->withInput();
           }

              // Verifica se todos os digitos são iguais
           if (preg_match('/(\d)\1{13}/', $vendedores->cnpj)){
             return redirect()
             ->route('vendedores.create')
             ->with('message-failure', 'Sequência de digitos inválidos!')
             ->withInput();
           }

              // Valida primeiro dígito verificador
           for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++)
           {
            $soma += $vendedores->cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
          }

          $resto = $soma % 11;

          if ($vendedores->cnpj[12] != ($resto < 2 ? 0 : 11 - $resto)){
            return redirect()
            ->route('vendedores.create')
            ->with('message-failure', 'CNPJ Inválido!. Digite um CNPJ válido!')
            ->withInput();
          }

              // Valida segundo dígito verificador
          for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++)
          {
            $soma += $vendedores->cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
          }

          $resto = $soma % 11;

          $vendedores->cnpj[13] == ($resto < 2 ? 0 : 11 - $resto); 
               //$vendedores->status = $request->status;       
          $vendedores->cep = $request->cep;       
          $vendedores->endereço = $request->endereço;
          $vendedores->numero = $request->numero;
          $vendedores->complemento = $request->complemento;
          $vendedores->bairro = $request->bairro;
          $vendedores->cidade = $request->cidade;
          $vendedores->estado = $request->estado;
               //$vendedores->notes = $request->notes;
          $vendedores->razao_social = $request->razao_social;
          $vendedores->password = bcrypt($request->password);
          $vendedores->save();
          return redirect()->route('vendedores.index')->with('message', 'Vendedor(a) cadastrado(a) com sucesso!');

        } 

          $vendedores->name = $request->name;            
          $vendedores->email = $request->email;
          $vendedores->tel = $request->tel;
          $vendedores->cel = $request->cel; 
          $vendedores->tipo = $request->tipo;
          $vendedores->comissao = $request->comissao;
           $vendedores->comissao = str_replace( '%', '', $vendedores->comissao);
          if ($vendedores->comissao > 100)
          {
        return  redirect()->route('vendedores.create')
            ->with('message-failure', 'Comissão não pode ser superior a 100%.')
            ->withInput();
        }         
            // Extrai somente os números
          $vendedores->cpf  = preg_replace( '/[^0-9]/is', '', $vendedores->cpf );

            // Verifica se foi informado todos os digitos corretamente
          if (strlen($vendedores->cpf) != 11) {
            return redirect()
            ->route('vendedores.create')
            ->with('message-failure', 'Faltam digitos!')
            ->withInput();
          }

              // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
          if (preg_match('/(\d)\1{10}/', $vendedores->cpf)) {
            return redirect()
            ->route('vendedores.create')
            ->with('message-failure', 'Sequência de digitos invalidos!')
            ->withInput();
          }

              // Faz o calculo para validar o CPF
          for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
              $d += $vendedores->cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($vendedores->cpf[$c] != $d) {
              return redirect()
              ->route('vendedores.create')
              ->with('message-failure', 'CPF inválido. Informe um CPF válido!')
              ->withInput();
            }
          }                        
              //$vendedores->status = $request->status;       
          $vendedores->cep = $request->cep;       
          $vendedores->endereço = $request->endereço;
          $vendedores->numero = $request->numero;
          $vendedores->complemento = $request->complemento;
          $vendedores->bairro = $request->bairro;
          $vendedores->cidade = $request->cidade;
          $vendedores->estado = $request->estado;
               //$vendedores->notes = $request->notes;
               //$vendedores->razao_social = $request->razao_social;
          $vendedores->password = bcrypt($request->password);
          $vendedores->save();
          return redirect()->route('vendedores.index')->with('message', 'Vendedor(a) cadastrado(a) com sucesso!');
        

  } catch (QueryException $e) {

      //dd($e);

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

          $this->middleware('VerifyCsrfToken');
          $vendedores = Vendedor::findOrFail($id); 

         /* $vendedor = Vendedor::find($id);          
          if( empty($vendedor->id)) {           
          return redirect()->route('vendedores/{id}/edit', $id)
          ->with('message-failure', 'Informe o CPF ou CNPJ.')
          ->withInput();
          }*/ 

          $vendedores->cnpj = $request->cnpj;
          $vendedores->cpf = $request->cpf;
          if ( $vendedores->cnpj == NULL  &&  $vendedores->cpf == NULL ){                                   
            return redirect()->route('vendedores/{id}/edit', $id)
            ->with('message-failure', 'Informe o CPF ou CNPJ.')
            ->withInput();
          } 

          if (isset($vendedores->cnpj)) {     
            $vendedores->name = $request->name_contato;  
            $vendedores->email = $request->email;
            $vendedores->tel = $request->tel;
            $vendedores->cel = $request->cel;
            $vendedores->comissao = $request->comissao; 
             $vendedores->comissao = str_replace( '%', '', $vendedores->comissao);
          if ($vendedores->comissao > 100)
          {
        return  redirect()->route('vendedores/{id}/edit', $id)
            ->with('message-failure', 'Comissão não pode ser superior a 100%.')
            ->withInput();
        }  
            $vendedores->cnpj =  preg_replace( '/[^0-9]/is', '', $vendedores->cnpj );   
              // Valida tamanho
            if (strlen($vendedores->cnpj) != 14)
            {
             return redirect()->route('vendedores/{id}/edit', $id)->with('message-failure', 'Faltam digitos!')->withInput();
           }

              // Verifica se todos os digitos são iguais
           if (preg_match('/(\d)\1{13}/', $vendedores->cnpj)){
             return redirect()->route('vendedores/{id}/edit', $id)
             ->with('message-failure', 'Sequência de digitos inválidos!')
             ->withInput();
           }

              // Valida primeiro dígito verificador
           for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++)
           {
            $soma += $vendedores->cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
          }

          $resto = $soma % 11;

          if ($vendedores->cnpj[12] != ($resto < 2 ? 0 : 11 - $resto)){
            return redirect()->route('vendedores/{id}/edit', $id)
            ->with('message-failure', 'CNPJ Inválido!. Digite um CNPJ válido!')
            ->withInput();
          }

              // Valida segundo dígito verificador
          for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++)
          {
            $soma += $vendedores->cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
          }

          $resto = $soma % 11;

          $vendedores->cnpj[13] == ($resto < 2 ? 0 : 11 - $resto); 
          $vendedores->status = $request->status;       
          $vendedores->cep = $request->cep;       
          $vendedores->endereço = $request->endereço;
          $vendedores->numero = $request->numero;
          $vendedores->complemento = $request->complemento;
          $vendedores->bairro = $request->bairro;
          $vendedores->cidade = $request->cidade;
          $vendedores->estado = $request->estado;
               //$vendedores->notes = $request->notes;
          $vendedores->razao_social = $request->razao_social;
          $vendedores->password = bcrypt($request->password);
          $vendedores->save();
          return redirect()->route('vendedores.index')->with('message', 'Vendedor(a) Alterado(a) com sucesso!');

        } 

          $vendedores->name = $request->name;            
          $vendedores->email = $request->email;
          $vendedores->tel = $request->tel;
          $vendedores->cel = $request->cel;  
          $vendedores->comissao = $request->comissao; 

           $vendedores->comissao = str_replace( '%', '', $vendedores->comissao);
           //dd($vendedores->comissao); 
          if ($vendedores->comissao > 100)
          {
        return  redirect()->route('vendedores/{id}/edit', $id)
            ->with('message-failure', 'Comissão não pode ser superior a 100%.')
            ->withInput();
        }    
            // Extrai somente os números
          $vendedores->cpf  = preg_replace( '/[^0-9]/is', '', $vendedores->cpf );

            // Verifica se foi informado todos os digitos corretamente
          if (strlen($vendedores->cpf) != 11) 
          {
            return  redirect()->route('vendedores/{id}/edit', $id)
            ->with('message-failure', 'Faltam digitos!')
            ->withInput();
          }

              // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
          if (preg_match('/(\d)\1{10}/', $vendedores->cpf)) {
            return  redirect()->route('vendedores/{id}/edit', $id)
            ->with('message-failure', 'Sequência de digitos invalidos!')
            ->withInput();
          }

              // Faz o calculo para validar o CPF
          for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
              $d += $vendedores->cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($vendedores->cpf[$c] != $d) 
            {
              return  redirect()->route('vendedores/{id}/edit', $id)
              ->with('message-failure', 'CPF inválido. Informe um CPF válido!')
              ->withInput();
            }
          }                        
              //$vendedores->status = $request->status;       
          $vendedores->cep = $request->cep;       
          $vendedores->endereço = $request->endereço;
          $vendedores->numero = $request->numero;
          $vendedores->complemento = $request->complemento;
          $vendedores->bairro = $request->bairro;
          $vendedores->cidade = $request->cidade;
          $vendedores->estado = $request->estado;
               //$vendedores->notes = $request->notes;
               //$vendedores->razao_social = $request->razao_social;          
    //$vendedores->notes = $request->notes;
    $vendedores->status = $request->status;

    if(isset($request->password)){
    $vendedores->password = bcrypt($request->password);
    }
    
    $vendedores->save();
    return redirect()->route('vendedores.index')->with('message', 'Vendedor(a) alterado(a) com sucesso!');
  }
  catch (QueryException $e) {
      dd($e);
   return redirect()
   ->route('vendedores.index')
   ->with('message-failure', 'Não foi possível alterar o usuário');       
 }


}


public function status($id) {
  try {
    $vendedores = Vendedor::findOrFail($id);  


    $pedido = Pedido::select('id')->where(['vendedor_id' => $id])->first();  

          if(isset($pedido)) {           
          return redirect()->route('vendedores.index')
   ->with('message-failure', 'Não foi possível realizar a exclusão, existem pedidos gerados para o vendedor');
          }

    $vendedores->status = 'inactive';
    //$password = 'opycos@123';
    //$vendedores->password = bcrypt($password);     
    $vendedores->save();
    return redirect()->route('vendedores.index')->with('message', 'Registro Removido com sucesso!');
  }
  catch (QueryException $e) {
   return redirect()
   ->route('vendedores.index')
   ->with('message-failure', 'Não foi possível realizar a exclusão');       
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

  $idvendedor = $request->id;
  $tipo = $request->tipo;


  $vendedores = $vendedor->search($dataForm);
  $total = Vendedor::all()->count();   


  $allVendedores = $vendedor->searchTotal($dataForm);
  $totalSearch = ($allVendedores)->count();

  $totalPageSearch = ($vendedores)->count();         

  return view('admin.vendedorResource.list-vendedores', compact('vendedores', 'dadosVendedores', 'total', 'dataForm', 'totalPageSearch', 'totalSearch', 'idvendedor', 'tipo'));

}


}







