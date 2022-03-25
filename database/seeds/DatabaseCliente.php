<?php

use Illuminate\Database\Seeder;
//use App\Entities\User;

class DatabaseCliente extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::table('clientes')->insert([

         
            'name' => 'Andre Teste',
             //'tel',   
              'cel' => '11963445544',
               'profissao' => '1',
             'celInput'  => '11963445544',
               'endereço' => 'Rua teste',
                 'numero' => '12',
                  'cep' => '04165432',
                   //'complemento',
                   'vendedor_id' => '25',
                    'bairro' => 'teste',
                     'cidade' => 'teste',
                      'estado' => 'SP',
                       //'notes',
                        'tipo' => '1',
                           'cpf' => '11122233345',
                          //'cnpj',	                                                
                            //'status' ,
                            // 'ativo',
                            //  'razao_social'  
           
            'email' => 'cliente@teste.com',
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
