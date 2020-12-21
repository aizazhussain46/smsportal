<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->boolean('job_type')->default(0);
            $table->string('task_title')->nullable();
            $table->string('nature_of_task')->nullable();
            $table->longText('brief')->nullable();
            $table->longText('deliverables')->nullable();
            $table->longText('attachment')->nullable();
            $table->integer('district_id')->nullable();
            $table->integer('department_id')->nullable();
            $table->integer('status_id')->nullable();
            $table->integer('created_by');
            $table->integer('assigned_to')->nullable();;
            $table->timestamp('_from')->nullable();
            $table->timestamp('_to')->nullable();
            $table->time('from_time')->nullable();
            $table->time('to_time')->nullable();
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
        Schema::dropIfExists('jobs');
    }
}
