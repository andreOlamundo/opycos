<?php

namespace App\Entities;



use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable

{


/**
======================================================
The ORM database attributes
======================================================
*/


public $timestamps = true;
protected $table = 'clientes';        
protected $fillable = ['name', 'notes', 'email', 'password', 'status', 'tel', 'cel', 'profissao', 'num_registro'];
protected $guarded = ['id', 'created_at', 'update_at', 'deleted_at'];  
protected $hidden = ['password', 'remember_token'];





}
