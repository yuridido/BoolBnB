<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApartmentSponsorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apartment_sponsor', function (Blueprint $table) {
            $table->id();
            $table->datetime('start_sponsor')->nullable();
            $table->datetime('end_sponsor')->nullable();
            $table->string('transaction_id', 20);
            $table->timestamps();
            
            $table->unsignedBigInteger('apartment_id');
            $table->foreign('apartment_id')
            ->references('id')
            ->on('apartments')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->unsignedBigInteger('sponsor_id');
            $table->foreign('sponsor_id')
            ->references('id')
            ->on('sponsors')
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
        Schema::dropIfExists('apartment_sponsor');
    }
}
