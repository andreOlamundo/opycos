<?php

use Illuminate\Database\Seeder;

class Profissional extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('profissionais')->insert([
            'detalhes' => 'Destistas são Profissionais Liberais',
            'percent_desc' => '10',
            'tipo' => 'Médico'
           
        ]);
    }
}
