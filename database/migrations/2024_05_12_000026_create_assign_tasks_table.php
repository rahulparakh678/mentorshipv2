<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssignTasksTable extends Migration
{
    public function up()
    {
        Schema::create('assign_tasks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->longText('description');
            $table->datetime('start_date_time');
            $table->datetime('end_date_time');
            $table->integer('mentor_id');
            $table->integer('mentee_id');
            $table->integer('submitted')->nullable();        
            $table->string('completed')->nullable();
            $table->string('task_response')->nullable();
            
            
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
