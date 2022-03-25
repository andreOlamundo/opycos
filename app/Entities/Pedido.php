<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
   public $timestamps = true;

	protected $fillable = [
		'id',
		'pedido_id',
		'produto_id',
		'user_id',
		'id_cliente',
		'vendedor_id',		
		'data_pedido',
		'obs_pedido',
		'pagamento',
		'consignado',
		'comissao',
		'status',
		'percentual_comissao',
		'cancelados',
		'request_id',
		'valorReq',
		'calculo_comissao',

	];
	protected $guarded = [
		'id',
		'created_at',
		'update_at',
		'deleted_at'
	];
	protected $table = 'pedidos';

	public function Cliente()
	{

	return $this->belongsTo('App\Entities\Cliente', 'id_cliente', 'id');

	}


		public function Vendedor()
	{

	return $this->belongsTo('App\Entities\Vendedor', 'vendedor_id', 'id');

	}


		public function Comissao()
	{

	return $this->belongsTo('App\Entities\Comissao', 'id', 'pedido_id');

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


		public function itens_pedido_uni()
	{
		return $this->hasMany('App\Entities\ItensPedido')
		->select('id', 'status', 'produto_id', 'comissao', 'prod_desconto', 'prod_preco_padrao')
		->where('tipo', '=', 'P')
		->orderBy('produto_id', 'desc');
	}


		public function itens_pedido_u()
	{
		return $this->hasMany('App\Entities\ItensPedido')
		->select('id', 'request_id', 'comissao', 'request_desconto', 'prod_preco_padrao')
		->where('tipo', '=', 'R')
		->orderBy('request_id', 'desc');
	}
	

		public function itens_pedido_pedido()
	{
		return $this->hasMany('App\Entities\ItensPedido')
		->select(\DB::raw('pedido_id, sum(prod_preco_padrao) as total, sum(prod_desconto) as totalDesconto, count(1) as quantidade'))	
		->groupBy('pedido_id')	
		->orderBy('pedido_id', 'desc');
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

	



/*public function comissao_gerais()
	{
		return $this->hasMany('App\Entities\Comissao')
		->select( \DB::raw('pedido_id, sum(valor_comissao) as total, count(1) as quantidade') )
		->where('status', '=', 'PA')
		->groupBy('pedido_id')
		->orderBy('pedido_id', 'desc');
	}*/


	

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

    public static function consultaId($where)
    {
        $pedido = self::where($where)->first(['id']);
        return !empty($pedido->id) ? $pedido->id : null;
    }



	public function search(Array $data)
	{    	

		return $this->where(function ($query) use ($data){

			if (isset($data['id']))
				$query->where('id', $data['id']);

			if (isset($data['id_cliente']))
				$query->where('id_cliente', $data['id_cliente']);

			if (isset($data['status']))
				$query->where('status', $data['status']);

			if (isset($data['statusdefaut']))
				$query->where('status', '<>', 'GE');


			if (isset($data['comissao']))
				$query->where('comissao', $data['comissao']);

			/*if (isset($data['updated_at']))
				$query->where('updated_at', 'like', $data['updated_at'].'%');*/	

			
			if (isset($data['updated_at']))
				$query->whereYear('updated_at', $data['updated_at']);

			if (isset($data['periodo']))
				$query->whereMonth('updated_at', $data['periodo']);	

			if (isset($data['pedido_id']))
				$query->where('pedido_id', $data['pedido_id']);

				if (isset($data['cancelados']))
				$query->where('cancelados', $data['cancelados']); 

			if (isset($data['consignado']))
				$query->where('consignado', $data['consignado']); 

			if (isset($data['user_id']))
				$query->where('user_id', $data['user_id']);

			if (isset($data['pedido_cod']))
				$query->where('pedido_cod', $data['pedido_cod']);

			if (isset($data['cliente_id']))
				$query->where('cliente_id', $data['cliente_id']);

			if (isset($data['vendedor_id']))
				$query->where('vendedor_id', $data['vendedor_id']);

			if (isset($data['data_pedido']))
				$query->where('data_pedido', $data['data_pedido']);

			if (isset($data['obs_pedido']))
				$query->where('obs_pedido', $data['obs_pedido']);

			if (isset($data['valor_total_pedido']))
				$query->where('valor_total_pedido', $data['valor_total_pedido']);


    	})->get();//->toSql();dd($clientes);
		//->paginate(7);
	}


	        public function searchTotal(Array $data)
    {     

   	return $this->where(function ($query) use ($data){

			if (isset($data['id']))
				$query->where('id', $data['id']);

			if (isset($data['id_cliente']))
				$query->where('id_cliente', $data['id_cliente']);

			if (isset($data['comissao']))
				$query->where('comissao', $data['comissao']);

			if (isset($data['pedido_id']))
				$query->where('pedido_id', $data['pedido_id']); 

			if (isset($data['user_id']))
				$query->where('user_id', $data['user_id']);

			if (isset($data['pedido_cod']))
				$query->where('pedido_cod', $data['pedido_cod']);

			if (isset($data['cliente_id']))
				$query->where('cliente_id', $data['cliente_id']);

			if (isset($data['vendedor_id']))
				$query->where('vendedor_id', $data['vendedor_id']);

			if (isset($data['data_pedido']))
				$query->where('data_pedido', $data['data_pedido']);

			if (isset($data['obs_pedido']))
				$query->where('obs_pedido', $data['obs_pedido']);

			if (isset($data['valor_total_pedido']))
				$query->where('valor_total_pedido', $data['valor_total_pedido']);


    	})//->toSql();dd($clientes);
		->get();
          
       

        }


		
}
