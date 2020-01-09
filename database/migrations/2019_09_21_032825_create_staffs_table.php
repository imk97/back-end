<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staffs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->collation('utf8_general_ci');
            $table->string('ic')->collation('utf8_general_ci');;
            $table->string('department')->collation('utf8_general_ci');;
            $table->string('username')->collation('utf8_general_ci');;
            $table->string('password')->collation('utf8_general_ci');;
            $table->string('phone')->collation('utf8_general_ci');;
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
        Schema::dropIfExists('staffs');
    }
}
