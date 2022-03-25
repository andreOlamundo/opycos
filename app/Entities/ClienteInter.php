<?php

namespace App\Entities;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class ClienteInter extends Authenticatable
{
     
public $timestamps = true;

protected $table = 'clientes';

protected $fillable = [
  'id',
   'name',
    'tel',
     'cel',
     'celInput',
      'endereço',
        'numero',
         'cep',
          'complemento',
          'vendedor_id',
           'bairro',
            'cidade',
             'estado',
              'notes',
               'tipo',
	              'cpf',
                 'cnpj',	
	                'email',   
	   	     	       'password',
                   'preview_id',  
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


  public function Cliente()
  {

  return $this->belongsTo('App\Entities\ClientesInter', 'preview_id', 'id');

  }

public function search(Array $data)
    {    	

    	return $this->where(function ($query) use ($data){

if (isset($data['id']))
          $query->where('id', $data['id']);

        if (isset($data['name']))
          $query->where('name', $data['name']); 

        if (isset($data['vendedor_id']))
          $query->where('vendedor_id', $data['vendedor_id']);

        if (isset($data['status']))
          $query->where('status', $data['status']);

        
            if (isset($data['cpf']))
          $query->where('cpf', $data['cpf']);

         if (isset($data['cnpj']))
          $query->where('cnpj', $data['cnpj']);

        if (isset($data['tipo']))
          $query->where('tipo', $data['tipo']);

        if (isset($data['email']))
          $query->where('email', $data['email']);

        if (isset($data['tel']))
          $query->where('tel', $data['tel']);

        if (isset($data['cel']))
          $query->where('cel', $data['cel']);  

          if (isset($data['celInput']))
          $query->where('celInput', $data['celInput']);

        if (isset($data['endereço']))
          $query->where('endereço', $data['endereço']);

        if (isset($data['cep']))
          $query->where('cep', $data['cep']);

    	})//->toSql();dd($clientes);
    	->paginate(7);    	
       

        }

        public function searchTotal(Array $data)
    {     

      return $this->where(function ($query) use ($data){

if (isset($data['id']))
          $query->where('id', $data['id']);

        if (isset($data['name']))
          $query->where('name', $data['name']); 

        if (isset($data['vendedor_id']))
          $query->where('vendedor_id', $data['vendedor_id']);

        if (isset($data['status']))
          $query->where('status', $data['status']);

        
            if (isset($data['cpf']))
          $query->where('cpf', $data['cpf']);

         if (isset($data['cnpj']))
          $query->where('cnpj', $data['cnpj']);

        if (isset($data['tipo']))
          $query->where('tipo', $data['tipo']);

        if (isset($data['email']))
          $query->where('email', $data['email']);

        if (isset($data['tel']))
          $query->where('tel', $data['tel']);

        if (isset($data['cel']))
          $query->where('cel', $data['cel']);  

          if (isset($data['celInput']))
          $query->where('celInput', $data['celInput']);

        if (isset($data['endereço']))
          $query->where('endereço', $data['endereço']);

        if (isset($data['cep']))
          $query->where('cep', $data['cep']);

      })//->toSql();dd($clientes);
      ->get();     
       

        }


}
