<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class GroupProducts extends model
{

	public $timestamps = true;
	
	protected $fillable = [
		'id',
		'grup_cod',
		'grup_desc',
		'grup_categ_cod',
		'grup_desc_categ'
		
	];

		protected $guarded = [
		'id',
		'created_at',
		'update_at',
		'deleted_at'
	];
	
	protected $table = 'groups_product';

	public function search(Array $data)
	{    	

		return $this->where(function ($query) use ($data){

			if (isset($data['id']))
				$query->where('id', $data['id']); 

			


    	})//->toSql();dd($clientes);
		->paginate(10);
	}

		public function searchTotal(Array $data)
	{    	

		return $this->where(function ($query) use ($data){

			if (isset($data['id']))
				$query->where('id', $data['id']); 

			


    	})//->toSql();dd($clientes);
		->paginate(10);
	}

	
	
}
