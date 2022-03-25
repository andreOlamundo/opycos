<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CodRegistroProf extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cod_registro_prof', function (Blueprint $table) {
            $table->increments('id');           
            //$table->text('detalhes')->nullable();
            //$table->string('num_registro');
            //$table->double('desconto',[10,2]); 
            //$table->float('desconto', 2, 2);  
            //$table->decimal('percent_desc', 10,2);
            $table->integer('cod_registro_id');
            $table->integer('cod_prof_id');
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
        Schema::dropIfExists('cod_registro_prof');
    }
}
