<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerRemindersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_reminders', function (Blueprint $table) {
            $table->id();
            $table->string("customercode");
            $table->string("name");
            $table->string("installment_terms");
            $table->string("posting_date");
            $table->string("total_payment_due");
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
        Schema::dropIfExists('customer_reminders');
    }
}
