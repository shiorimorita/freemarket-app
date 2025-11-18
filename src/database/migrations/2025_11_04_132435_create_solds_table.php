<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSoldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            /* 商品1つにつき売れる回数は1つのため（複数回売り禁止）*/
            $table->unsignedBigInteger('item_id')->unique();
            $table->foreign('item_id')->references('id')->on('items')->cascadeOnDelete();
            $table->string('method');
            $table->string('post_code');
            $table->string('address');
            $table->string('building')->nullable();
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
        Schema::dropIfExists('solds');
    }
}
