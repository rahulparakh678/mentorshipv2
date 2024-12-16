<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChaptersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chapters', function (Blueprint $table) {
            //$table->id();
            //$table->timestamps();

            $table->bigIncrements('id');
            $table->string('chaptername');
            $table->string('description');
            $table->string('published');
            $table->unsignedBigInteger('module_id')->nullable();
            $table->unsignedBigInteger('chapter_id')->nullable();
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
        Schema::dropIfExists('chapters');
    }
}
