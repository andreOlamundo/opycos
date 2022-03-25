<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Cod_registro_prof extends Model
{
    public $timestamps = true;

	protected $fillable = [
		'id',
		'cod_registro_id',
		'cod_prof_id'
		
	

	];
	protected $guarded = [
		'id',
		'created_at',
		'update_at',
		'deleted_at'
	];
	protected $table = 'cod_registro_prof';


	public function profissional()
	{
  
	return $this->belongsTo('App\Entities\Profissional', 'cod_prof_id', 'id');
  
	}
}

