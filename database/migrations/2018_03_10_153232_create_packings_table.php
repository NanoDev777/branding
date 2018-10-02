<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packings', function (Blueprint $table) {
            $table->increments('id');
            $table->float('width', 8, 2);
            $table->float('height', 8, 2);
            $table->float('thickness', 8, 2);
            $table->float('weight', 8, 2);
            $table->string('box', 16);
            $table->integer('product_id')->unsigned();
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('packings');
    }
}
