<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Entities\Produto;
use App\Entities\Cliente;
use DB;

class HomeController extends Controller
{
    public function index()
    {
        $registros = Produto::where([
            'ativo' => 's'
            ])->get();

            $dadosClientes=DB::table('clientes')->get();        

        return view('admin.pedidoResource.index', compact('registros', 'dadosClientes'));
    }

    public function produto($id = null)
    {
        if( !empty($id) ) {
            $registro = Produto::where([
                'id'    => $id,
                'ativo' => 's'
                ])->first();

            if( !empty($registro) ) {
                return view('admin.pedidoResource.produto', compact('registro'));
            }
        }
        return redirect()->route('index');
    }


}
