<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestsTable extends Migration
{
    public function up()
    {
        Schema::create('tests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->nullable();
            $table->longText('description')->nullable();
            $table->unsignedBigInteger('module_id')->nullable(); // Foreign key to modules
            $table->unsignedBigInteger('chapter_id')->nullable(); // Foreign key to chapters
            $table->boolean('is_published')->default(0)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
