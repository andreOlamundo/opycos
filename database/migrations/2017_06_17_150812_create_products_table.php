<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('products', function (Blueprint $table) {
             $table->increments('id');
            //people data
            $table->integer('prod_cod', 10);
            $table->integer('grup_cod', 10);
            $table->integer('grup_categ_cod', 10);
            $table->string('prod_desc');
            $table->decimal('prod_preco_padrao', 10,2);
            $table->decimal('prod_preco_prof', 10,2);
            $table->decimal('prod_preco_balcao', 10,2); 
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
       Schema::dropIfExists('products');
    }
}
