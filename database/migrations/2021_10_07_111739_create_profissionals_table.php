<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfissionalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profissionais', function (Blueprint $table) {
            $table->increments('id');           
            //$table->text('detalhes')->nullable();
            //$table->string('num_registro');
            //$table->double('desconto',[10,2]); 
            //$table->float('desconto', 2, 2);  
            //$table->decimal('percent_desc', 10,2);
            //$table->integer('percent_desc');
            $table->enum('tipo',['Medico','Dentista', 'Outro'])->default('Outro');
            $table->timestamps();
            $table->softDeletes();           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profissionais');
    }
}
