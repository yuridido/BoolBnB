<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('name');
            $table->string('lastname');
            $table->text('message');
            $table->boolean('read')->default(0);
            $table->timestamps();

            $table->unsignedBigInteger('apartment_id');
            $table->foreign('apartment_id')
            ->references('id')
            ->on('apartments')
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
        Schema::dropIfExists('messages');
    }
}
