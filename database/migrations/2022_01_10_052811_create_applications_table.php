<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string('application_id')->nullable();
            $table->string('university_name');
            $table->string('course_level');
            $table->string('course_name');
            $table->string('student_name');
            $table->string('student_email');
            $table->string('student_number');
            $table->date('student_dob');
            $table->boolean('visa_refusal');
            $table->string('nationality');
            $table->foreignId('user_id')->constrained();
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
        Schema::dropIfExists('applications');
    }
}
