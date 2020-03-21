<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UserPass extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_pass', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->integer('user_id2');
            $table->string('longitude');
            $table->string('latitude');
            $table->string('device_id');
            $table->string('device_id2');
            $table->date('pass_date')->useCurrent();
            $table->timestamps();

            $table->unique(['user_id', 'user_id2', 'pass_date']);
            $table->index("user_id");
            $table->index("user_id2");
            $table->index("device_id");
            $table->index("device_id2");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
