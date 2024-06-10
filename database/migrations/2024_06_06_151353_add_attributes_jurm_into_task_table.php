<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('task', function (Blueprint $table) {
            $table->string('crime_no')->nullable();
            $table->string('crime_section')->nullable();
            $table->text('criminal_address')->nullable();
            $table->date('arrest_date')->nullable();
            $table->text('remand')->nullable();
            $table->string('arrest_status')->nullable();
            $table->longText('arrest_by')->nullable();
            $table->string('designation')->nullable();
            $table->string('condition')->nullable();
            $table->integer('assigned_to')->default(0);
        
          
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('task', function (Blueprint $table) {
            //
        });
    }
};
