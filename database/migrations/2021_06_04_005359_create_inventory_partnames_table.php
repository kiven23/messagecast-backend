<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryPartnamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_partnames', function (Blueprint $table) {
            $table->id();
            $table->string('partname_control')->nullable();
            $table->string('partname')->nullable();
            $table->string('partnameno')->nullable();
            $table->string('sap')->nullable();
            $table->string('unitprice')->nullable();
            $table->string('total')->nullable();
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
        Schema::dropIfExists('inventory_partnames');
    }
}
