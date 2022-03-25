<?php

use Illuminate\Database\Seeder;
//use App\Entities\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::table('admins')->insert([
            'name' => 'Administrador',
            'notes' => 'Usuário Adm',
            'email' => 'admin@teste.com',
            'password' => Hash::make('123456'),
        ]);

        // $this->call(UsersTableSeeder::class);
    }
}

/*
*
*

 public function run()
    {
        DB::table('clientes')->insert([
            'name' => 'Cliente',
            'tel' => '(11)5077-9965',
            'cel' => '(11)984932164',
            'endereço' => 'rua teste',
            'numero' => '4',
            'cep' => '041-59-000',           
            'bairro' => 'teste',
            'cidade' => 'teste',
            'estado' => 'sp',          
            'notes' => 'Cadastro whats',            
            'email' => 'cliente@cliente.com',   
            'password'=> Hash::make('123456'),  
                
           
                     
        ]);

        // $this->call(UsersTableSeeder::class);
    }
}






User::create([
            'name' => 'teste',
            'notes' => 'Segundo User',
            'email' => 'teste@teste.com',
            'password' => env('PASSWORD_HASH') ? bcrypt('123456') : '123456',
        ]);
*
*/
