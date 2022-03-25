<?php

namespace App\Entities;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Vendedor extends Authenticatable
{

 public $timestamps = true;


   protected $fillable = [
   	'id',   	
   	'name',
   	'email',
   	 'tel',
   	  'cel',
   	   'cep',
   	   'cpf',
   	   'tipo',
   	   'cnpj',
   	    'endereço',
   	     'numero',
   	      'complemento',
   	       'bairro',
   	        'cidade',
   	        'razao_social',
   	        'comissao',
   	         'estado',
   	          'notes',   	    
   	           'password',
   	            'status'
   	   ];

protected $hidden = [

	'password',
	 'remember_token'
	];

protected $guarded = [

	'id',
	 'created_at',
	  'update_at',
	   'deleted_at'
	];

protected $table = 'vendedores';

    	    public function Vendedor()
    {
        return $this->hasMany('App\Entities\Pedidos')
        ->select( \DB::raw('vendedor_id') )
		->groupBy('vendedor_id')
		->orderBy('vendedor_id', 'desc');
    }





public function search(Array $data)
	{    	

		return $this->where(function ($query) use ($data){

			if (isset($data['id']))
				$query->where('id', $data['id']);

			if (isset($data['name']))
				$query->where('name', $data['name']);

			if (isset($data['comissao']))
				$query->where('comissao', $data['comissao']);

			if (isset($data['tipo']))
				$query->where('tipo', $data['tipo']);		
				

			if (isset($data['cel']))
				$query->where('cel', $data['cel']);

			if (isset($data['email']))
				$query->where('email', $data['email']);

			if (isset($data['notes']))
				$query->where('notes', $data['notes']);
		


    	})//->toSql();dd($clientes);
		->paginate(10);
	}

	public function searchTotal(Array $data)
	{    	

		return $this->where(function ($query) use ($data){

			if (isset($data['id']))
				$query->where('id', $data['id']);

			if (isset($data['name']))
				$query->where('name', $data['name']); 

			if (isset($data['cel']))
				$query->where('cel', $data['cel']);

			if (isset($data['email']))
				$query->where('email', $data['email']);

			if (isset($data['notes']))
				$query->where('notes', $data['notes']);
		


    	})//->toSql();dd($clientes);
		->get();
	}
}

