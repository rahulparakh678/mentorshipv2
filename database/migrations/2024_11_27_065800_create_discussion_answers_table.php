<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscussionAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discussion_answers', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->longText('discussion_answers');
         // $table->unsignedBigInteger('question_id'); // Foreign key to questions table
            $table->timestamps(); // created_at and updated_at fields
            $table->softDeletes(); // deleted_at field for soft deletes
            
            // Setting up the foreign key constraint
            // $table->foreign('question_id')->references('id')->on('questions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('discussion_answers');
    }
}

