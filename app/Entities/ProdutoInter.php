<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class ProdutoInter extends model
{

	public $timestamps = true;

	protected $fillable = [
		'id',
		'prod_cod',
		'grup_cod',
		'grup_categ_cod',
		'prod_desc',
		'peso',
		'quantidade',
		'ativo',
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
	protected $table = 'products';


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

public function grupCod()
{
	return $this->belongsTo('App\Entities\GroupProducts', 'grup_cod', 'grup_cod');
}

	public function search(Array $data)
	{    	

		return $this->where(function ($query) use ($data){


if (isset($data['id']))
				$query->where('id', $data['id']);

			if (isset($data['prod_cod']))
				$query->where('prod_cod', $data['prod_cod']); 

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

			if (isset($data['prod_cod']))
				$query->where('prod_cod', $data['prod_cod']); 

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
