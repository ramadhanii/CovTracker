<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Session extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sessions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->string('token');
            $table->string('fcm_token')->nullable();
            $table->string('device_id');
            $table->enum('status', ['ACTIVE', 'INACTIVE']);
            $table->dateTime("expired_at");
            $table->timestamps();

            $table->unique("token");
            $table->index("token");
            $table->index("fcm_token");
            $table->index("device_id");
            $table->index("status");
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
