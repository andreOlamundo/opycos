<?php

namespace App\Entities;

use Illuminate\Foundation\Auth\User as Authenticatable;


class Admin extends Authenticatable
{
public $timestamps = true;

protected $table = 'admins'; 

protected $fillable = [
	'name',
	 'notes',
	  'email',
	  'tel',
     	'cel',
     	'cpf',
	   'password',
	    'status'
	];

protected $guarded = [
	'id',
	 'created_at',
	  'update_at',
	   'deleted_at'
	]; 


protected $hidden = [
	'password',
	 'remember_token'
	];

	public function search(Array $data)
	{    	

		return $this->where(function ($query) use ($data){

			if (isset($data['id']))
				$query->where('id', $data['id']);
			if (isset($data['cpf']))
				$query->where('cpf', $data['cpf']);

			if (isset($data['name']))
				$query->where('name', $data['name']); 

			if (isset($data['cel']))
				$query->where('cel', $data['cel']);

			if (isset($data['email']))
				$query->where('email', $data['email']);

			if (isset($data['notes']))
				$query->where('notes', $data['notes']);
		


    	})//->toSql();dd($clientes);
		->paginate(7);
	}

	public function searchTotal(Array $data)
	{    	

		return $this->where(function ($query) use ($data){

			if (isset($data['id']))
				$query->where('id', $data['id']);
			if (isset($data['cpf']))
				$query->where('cpf', $data['cpf']);

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


