<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class CadastroWhatsapp extends model
{
     
public $timestamps = true;

protected $table = 'cadastro_whatsapp';

protected $fillable = [
  'id',  
   'name',
     'cel',
     'celInput',
      'email',
       'msg',
       'user_id',
       'vendedor_id',
       'preview_id',
        'status'    

	       ];

protected $guarded = [
	'id',
	 'created_at',
	  'update_at',
	   'deleted_at'
	];


  public function Vendedor()
  {

  return $this->belongsTo('App\Entities\Vendedor', 'vendedor_id', 'id');

  }

  public static function consultaId($where)
    {
        $preview = self::where($where)->first(['id']);
        return !empty($preview->id) ? $preview->id : null;
    }


public function search(Array $data)
    {    	

    	return $this->where(function ($query) use ($data){

if (isset($data['id']))
          $query->where('id', $data['id']);

    		if (isset($data['name']))
    			$query->where('name', $data['name']); 

          if (isset($data['preview_id']))
          $query->where('preview_id', $data['preview_id']); 


          if (isset($data['cel']))
          $query->where('cel', $data['cel']);   

                    if (isset($data['celInput']))
          $query->where('celInput', $data['celInput']);    

    		if (isset($data['email']))
    			$query->where('email', $data['email']); 	
    		
    		if (isset($data['msg']))
    			$query->where('msg', $data['msg']);

        if (isset($data['status']))
    			$query->where('status', $data['status']);

    	})//->toSql();dd($clientes);
    	->paginate(10);    	
       

        }


}
