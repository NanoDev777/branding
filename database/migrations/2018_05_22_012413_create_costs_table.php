<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('costs', function (Blueprint $table) {
            $table->increments('id');
            $table->float('chilean', 8, 2);
            $table->float('dollar', 8, 2);
            $table->float('purchase', 8, 2);
            $table->float('sale', 8, 2);
            $table->float('transfer', 8, 2);
            $table->float('amount', 8, 2);
            $table->float('transport', 8, 2);
            $table->float('iva', 8, 2);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('costs');
    }
}
