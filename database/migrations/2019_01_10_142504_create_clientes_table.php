<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
             $table->increments('id');
            //people data
            $table->string('name');
            $table->string('tel');
            $table->string('cel');
            $table->string('endereço');
            $table->integer('numero');
            $table->string('cep');
            $table->string('complemento')->nullable();
            $table->string('bairro');
            $table->string('cidade');           
            $table->string('estado');
            $table->text('notes')->nullable();
            $table->string('cpf')->unique();
            $table->string('cnpj')->unique();
            //auth data
            $table->string('email')->unique();
            $table->string('password', 60)->nullable();
            //status
            $table->string('status')->default('active');

            $table->rememberToken();
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
        Schema::dropIfExists('clientes');
    }
}
