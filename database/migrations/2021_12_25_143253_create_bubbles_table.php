<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBubblesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bubbles', function (Blueprint $table) {
            $table->id();
            $table->integer('userid');
            $table->integer('bubble_type');
            $table->string('title');
            $table->string('text');
            $table->double('longitude');
            $table->double('latitude');
            $table->integer('upvotes')->nullable();
            $table->integer('downvotes')->nullable();
            $table->integer('multiplier')->nullable();
            $table->integer('numcomments')->nullable();
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
        Schema::dropIfExists('bubbles');
    }
}
