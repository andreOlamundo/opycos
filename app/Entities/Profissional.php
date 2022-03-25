<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Profissional extends Model
{
    public $timestamps = true;

    protected $fillable = [
		'id',
		'tipo',
		'cod_registro_id',
		'percent_desc'	
		
	];

    protected $guarded = [
		'id',
		'created_at',
		'update_at',
		'deleted_at'
	];

    protected $table = 'profissionais';
}