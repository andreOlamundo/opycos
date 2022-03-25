<?php

namespace App\Entities;

/*use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;*/
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{
  /* use SoftDeletes;
    use Notifiable;*/

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

/*public function setPasswordAttribute($value)
{
$this->attributes['password'] = env('PASSWORD_HASH') ? bcrypt($value) : $value;
}

    public function getPermissions()
    {
        // user permissions overridden from role.
        $permissions = $this->getPermissionsInherited();

        if(!empty($permissions)) return $permissions;

        // permissions based on role.
        // more permissive permission wins
        // if user has multiple roles we keep
        // true values.
        foreach ($this->roles as $role) {
            foreach ($role->getPermissions() as $slug => $array) {
                if ( array_key_exists($slug, $permissions) ) {
                    foreach ($array as $clearance => $value) {
                        if( !array_key_exists( $clearance, $permissions[$slug] ) ) {
                            ! $value ?: $permissions[$slug][$clearance] = true;
                        }
                    }
                } else {
                    $permissions = array_merge($permissions, [$slug => $array]);
                }
            }
        }

        return $permissions;
    }*/



}
