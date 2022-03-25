<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Comissao extends Model
{
   public $timestamps = true;

	protected $fillable = [
		'id',
		'pedido_id',
		'cliente_id',
		'vendedor_id',
		'status',
		'percentual_comissao',
		'idGeral',
		'user_id',
		'valor_comissao',		
		'obs_comissao'
	

	];
	protected $guarded = [
		'id',
		'created_at',
		'update_at',
		'deleted_at'
	];
	protected $table = 'comissoes';

	public function Cliente()
	{

	return $this->belongsTo('App\Entities\Cliente', 'id_cliente', 'id');

	}


		public function Vendedor()
	{

	return $this->belongsTo('App\Entities\Vendedor', 'vendedor_id', 'id');

	}



/*			public function RequestOpycos()
	{

	return $this->belongsTo('App\Entities\OpycosRequest', 'request_id', 'id');

	}*/

	public function itens_pedido()
	{
		return $this->hasMany('App\Entities\ItensPedido')
		->select( \DB::raw('produto_id, sum(prod_preco_padrao) as total, sum(prod_desconto) as totalDesconto, count(1) as quantidade') )
		->where('tipo', '=', 'P')
		->groupBy('produto_id')
		->orderBy('produto_id', 'desc');
	}

	

		public function itens_pedido_pedido()
	{
		return $this->hasMany('App\Entities\ItensPedido')
		->select(\DB::raw('pedido_id, sum(prod_preco_padrao) as total, sum(prod_desconto) as totalDesconto, count(1) as quantidade'))	
		->groupBy('pedido_id')	
		->orderBy('pedido_id', 'desc');
	}


    public static function consultaId($where)
    {
        $comissao = self::where($where)->first(['id']);
        return !empty($comissao->id) ? $comissao->id : null;
    }



				public function Pedido()
	{

	return $this->belongsTo('App\Entities\ItensPedido', 'pedido_id', 'pedido_id');
	

	}

		public function itens_pedido_desconto()
	{
		return $this->hasMany('App\Entities\ItensPedido')
		->select( \DB::raw('produto_id, sum(prod_desconto) as totalDesconto, count(1) as quantidade') )
		->where('tipo', '=', 'P')
		->groupBy('produto_id')
		->orderBy('produto_id', 'desc');
	}




	public function itens_pedido_request()
	{
		return $this->hasMany('App\Entities\ItensPedido')
		->select( \DB::raw('request_id, sum(prod_preco_padrao) as total,  sum(request_desconto) as totalDesconto, count(1) as quantidade') )
		->where('tipo', '=', 'R')
		->groupBy('request_id')
		->orderBy('request_id', 'desc');
	}

	public function itens_pedido_geral()
	{
		return $this->hasMany('App\Entities\ItensPedido')
		->select( \DB::raw('id, sum(prod_preco_padrao) as total, count(1) as quantidade') )
		->groupBy('id')
		->orderBy('id', 'desc');

			}


				public function itens_pedido_BPJ()
	{
		return $this->hasMany('App\Entities\ItensPedido')
		->select( \DB::raw('produto_id, sum(prod_preco_prof) as total, count(1) as quantidade') )
		->groupBy('produto_id')
		->orderBy('produto_id', 'desc');

			}

	public function Frete()
	{
			return $this->belongsTo('App\Entities\Frete', 'id', 'pedido_id');
	}

public function Produto()
	{
			return $this->belongsTo('App\Entities\Produto', 'id', 'produto_id');
	}


	public function itens_pedido_CPF()
	{
		return $this->hasMany('App\Entities\ItensPedido')
		->select( \DB::raw('produto_id, sum(prod_preco_padrao) as total, count(1) as quantidade') )
		->groupBy('produto_id')
		->orderBy('produto_id', 'desc');
	}

		public function itens_pedido_CPJ()
	{
		return $this->hasMany('App\Entities\ItensPedido')
		->select( \DB::raw('produto_id, sum(prod_preco_prof) as total, count(1) as quantidade') )
		->groupBy('produto_id')
		->orderBy('produto_id', 'desc');
	}


		public function item_pedido_PJ()
	{
		return $this->hasMany('App\Entities\ItensPedido')
		->select( \DB::raw('produto_id, sum(prod_preco_prof) as total, count(1) as quantidade') )
		->groupBy('produto_id')
		->orderBy('produto_id', 'desc');
	}

	



	

	    public function pedido_produtos_itens()
    {
        return $this->hasMany('App\Entities\ItensPedido');
    }

    	    public function pedido_produtos_valores()
    {
        return $this->hasMany('App\Entities\ItensPedido')
        ->select( \DB::raw('produto_id, sum(prod_preco_prof) as total, count(1) as quantidade') )
		->groupBy('produto_id')
		->orderBy('produto_id', 'desc');
    }

   



	public function search(Array $data)
	{    	

		return $this->where(function ($query) use ($data){

			if (isset($data['id']))
				$query->where('id', $data['id']);


			if (isset($data['cliente_id']))
				$query->where('cliente_id', $data['cliente_id']);

			if (isset($data['status']))
				$query->where('status', $data['status']);

			if (isset($data['pedido_id']))
				$query->where('pedido_id', $data['pedido_id']); 

			if (isset($data['user_id']))
				$query->where('user_id', $data['user_id']);

			if (isset($data['cliente_id']))
				$query->where('cliente_id', $data['cliente_id']);

			if (isset($data['vendedor_id']))
				$query->where('vendedor_id', $data['vendedor_id']);
		

			if (isset($data['obs_comissao']))
				$query->where('obs_comissao', $data['obs_comissao']);

			if (isset($data['valor_comissao']))
				$query->where('valor_comissao', $data['valor_comissao']);


    	})->take(100)->get();//->toSql();dd($clientes);
		//->paginate(7);
	}





		
}
