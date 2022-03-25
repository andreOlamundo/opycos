<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class OpycosRequestInter extends model
{

	public $timestamps = true;

	protected $fillable = [
		'id',
		'request_desc',
		'request_cod',
		'request_valor',
		'id_cliente',
		'peso',
		'quantidade',
		'user_id',
		'vendedor_id',
		'status',		
		'ativo',
		'grup_cod',
		'grup_categ_cod'
		
	];
	protected $guarded = [
		'created_at',
		'update_at',
		'deleted_at'
	];
	protected $table = 'requisitions';


	/*public function grup_categ_cod()
	{
		return $this->hasMany('App\Entities\GroupProducts')
		->select( \DB::raw('grup_categ_cod') )
		->groupBy('grup_desc_categ')
		->orderBy('grup_desc_categ', 'desc');
	}*/


public function grupCateg()
{
	return $this->belongsTo('App\Entities\GroupProducts', 'grup_categ_cod', 'grup_categ_cod');
}

public function Cliente()
{
	return $this->belongsTo('App\Entities\Cliente', 'id_cliente', 'id');
}

public function grupCod()
{
	return $this->belongsTo('App\Entities\GroupProducts', 'grup_cod', 'grup_cod');
}

	public function search(Array $data)
	{    	

		return $this->where(function ($query) use ($data){


				if (isset($data['id']))
				$query->where('id', $data['id']);

			if (isset($data['id_cliente']))
				$query->where('id_cliente', $data['id_cliente']);		

			if (isset($data['prod_cod']))
				$query->where('prod_cod', $data['prod_cod']); 


			if (isset($data['status']))
				$query->where('status', $data['status']); 

			if (isset($data['grup_cod']))
				$query->where('grup_cod', $data['grup_cod']);

			if (isset($data['grup_categ_cod']))
				$query->where('grup_categ_cod', $data['grup_categ_cod']);

			if (isset($data['prod_desc']))
				$query->where('prod_desc', $data['prod_desc']);

			if (isset($data['prod_preco_padrao']))
				$query->where('prod_preco_padrao', $data['prod_preco_padrao']);

			if (isset($data['prod_preco_prof']))
				$query->where('prod_preco_prof', $data['prod_preco_prof']);

			if (isset($data['prod_preco_balcao']))
				$query->where('prod_preco_balcao', $data['prod_preco_balcao']);


    	})//->toSql();dd($clientes);
		->paginate(7);
	}

public function searchTotal(Array $data)
	{    	

		return $this->where(function ($query) use ($data){


				if (isset($data['id']))
				$query->where('id', $data['id']);

			if (isset($data['id_cliente']))
				$query->where('id_cliente', $data['id_cliente']);		

			if (isset($data['prod_cod']))
				$query->where('prod_cod', $data['prod_cod']); 


			if (isset($data['status']))
				$query->where('status', $data['status']); 

			if (isset($data['grup_cod']))
				$query->where('grup_cod', $data['grup_cod']);

			if (isset($data['grup_categ_cod']))
				$query->where('grup_categ_cod', $data['grup_categ_cod']);

			if (isset($data['prod_desc']))
				$query->where('prod_desc', $data['prod_desc']);

			if (isset($data['prod_preco_padrao']))
				$query->where('prod_preco_padrao', $data['prod_preco_padrao']);

			if (isset($data['prod_preco_prof']))
				$query->where('prod_preco_prof', $data['prod_preco_prof']);

			if (isset($data['prod_preco_balcao']))
				$query->where('prod_preco_balcao', $data['prod_preco_balcao']);


    	})//->toSql();dd($clientes);
		->get();
	}
		


}
