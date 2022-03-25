<?php

namespace App\Entities;


use Illuminate\Database\Eloquent\Model;

class FreteInter extends model
{
     
public $timestamps = true;

protected $table = 'fretes';

protected $fillable = [
            
            'pedido_id',  
            'id_cliente',
            'user_id',
            'vendedor_id', 
            'boolean',
            'local',
            'balcao', 
            'prazo_entrega',
            'serviço_correio',       
            'entrega',
            'cep',
            'endereço',
            'numero',
            'bairro',
            'complemento',
            'cidade',
            'estado',
            'valor',
            'notes',
            'status'         
    

	       ];

protected $guarded = [
	'id',
	 'created_at',
	  'update_at',
	   'deleted_at'
	];



  public function Cliente()
  {

  return $this->belongsTo('App\Entities\Clientes', 'id_cliente', 'id');

  }

    public function Vendedor()
  {

  return $this->belongsTo('App\Entities\Vendedor', 'vendedor_id', 'id');

  }


public function search(Array $data)
    {    	

    	return $this->where(function ($query) use ($data){

if (isset($data['id']))
          $query->where('id', $data['id']);

    		if (isset($data['balcao']))
    			$query->where('balcao', $data['balcao']); 

        if (isset($data['pedido_id']))
          $query->where('pedido_id', $data['pedido_id']);

        if (isset($data['id_cliente']))
          $query->where('id_cliente', $data['id_cliente']);

        
            if (isset($data['user_id']))
    			$query->where('user_id', $data['user_id']);

         if (isset($data['valor']))
          $query->where('valor', $data['valor']);

        if (isset($data['status']))
          $query->where('status', $data['status']);

    		if (isset($data['email']))
    			$query->where('email', $data['email']);

    		if (isset($data['entrega']))
    			$query->where('entrega', $data['entrega']);


    	})//->toSql();dd($clientes);
    	->paginate(7);    	
       

        }


}
