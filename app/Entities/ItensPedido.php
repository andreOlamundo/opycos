<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class ItensPedido extends model
{

	public $timestamps = true;
	
	protected $fillable = [
		'id',
		'pedido_id',
		'produto_id',
		'request_id',
		'prod_desconto',
		'request_desconto',
		'status',
		'prod_cod',
		'note',
		'tipo',
		'grup_cod',
		'grup_categ_cod',
		'quantidade',
		'prod_desc',
		'prod_preco_padrao',
		'prod_preco_prof',
		'comissao',
		'prod_preco_balcao'
		
	];

		protected $guarded = [
		'id',
		'created_at',
		'update_at',
		'deleted_at'
	];
	
	protected $table = 'itens_pedidos';

public function product()
{
	return $this->belongsTo('App\Entities\Produto', 'produto_id', 'id');
}

		public function desconto()
	{
			return $this->belongsTo('App\Entities\ItensPedido', 'produto_id', 'produto_id');
	}

	public function Cliente()
	{

	return $this->belongsTo('App\Entities\Cliente', 'id_cliente', 'id');

	}



			public function desconto_request()
	{
			return $this->belongsTo('App\Entities\ItensPedido', 'request_id', 'request_id');
	}

			public function desconto_pedido()
	{
			return $this->belongsTo('App\Entities\ItensPedido', 'pedido_id', 'pedido_id');
	}

				public function desconto_id()
	{
			return $this->belongsTo('App\Entities\ItensPedido', 'id', 'id');
	}

			public function Pedido()
	{
			return $this->belongsTo('App\Entities\ItensPedido', 'pedido_id', 'pedido_id');
	}



public function request()
{
	return $this->belongsTo('App\Entities\OpycosRequest', 'request_id', 'id');
}




public function search(Array $data)
	{    	

		return $this->where(function ($query) use ($data){

			if (isset($data['pedido_id']))
				$query->where('pedido_id', $data['pedido_id']); 

			if (isset($data['item_id']))
				$query->where('produto_id', $data['item_id']); 

			if (isset($data['grup_cod']))
				$query->where('grup_cod', $data['grup_cod']);

			if (isset($data['grup_categ_cod']))
				$query->where('grup_categ_cod', $data['grup_categ_cod']);

			if (isset($data['quantidade']))
				$query->where('quantidade', $data['quantidade']);

			if (isset($data['prod_desc']))
				$query->where('prod_desc', $data['prod_desc']);

			if (isset($data['prod_preco_padrao']))
				$query->where('prod_preco_padrao', $data['prod_preco_padrao']);

			if (isset($data['prod_preco_prof']))
				$query->where('prod_preco_prof', $data['prod_preco_prof']);

			if (isset($data['prod_preco_balcao']))
				$query->where('prod_preco_balcao', $data['prod_preco_balcao']);
		if (isset($data['periodo']))
				$query->whereMonth('updated_at', $data['periodo']);	

			if (isset($data['ano']))
				$query->whereYear('updated_at', $data['ano']);

			if (isset($data['id_cliente']))
				$query->where('tipo', '=', 'P');


    	})//->toSql();dd($clientes);
		->get();
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
