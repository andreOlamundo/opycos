<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class ItensPedidoInter extends model
{

	public $timestamps = true;
	
	protected $fillable = [
		'id',
		'pedido_id',
		'produto_id',
		'request_id',
		'status',
		'prod_cod',
		'tipo',
		'grup_cod',
		'grup_categ_cod',
		'quantidade',
		'prod_desc',
		'prod_preco_padrao',
		'prod_preco_prof',
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
	return $this->belongsTo('App\Entities\ProdutoInter', 'produto_id', 'id');
}

public function request()
{
	return $this->belongsTo('App\Entities\OpycosRequestInter', 'request_id', 'id');
}





public function search(Array $data)
	{    	

		return $this->where(function ($query) use ($data){

			if (isset($data['numero_pedido']))
				$query->where('numero_pedido', $data['numero_pedido']); 

			if (isset($data['prod_cod']))
				$query->where('prod_cod', $data['prod_cod']);

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


    	})//->toSql();dd($clientes);
		->paginate(10);
	}

	
}
