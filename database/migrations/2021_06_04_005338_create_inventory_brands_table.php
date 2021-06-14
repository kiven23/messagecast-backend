<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryBrandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_brands', function (Blueprint $table) {
            $table->id();
            $table->string('brand')->nullable();
            $table->string('partname_control')->nullable();
            $table->string('branch')->nullable();
            $table->string('by')->nullable();
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
        Schema::dropIfExists('inventory_brands');
    }
}
