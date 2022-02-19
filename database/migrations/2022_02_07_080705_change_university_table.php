<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeUniversityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('course', function (Blueprint $table) {
        //     $table->dropForeign(['university_id']);
        //     $table->dropColumn('university_id');
        // });
        Schema::rename('course', 'courses');
        Schema::table('universities', function (Blueprint $table) {
            $table->dropColumn('intake');
        });
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('level');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('universities', function (Blueprint $table) {
            //
        });
    }
}
