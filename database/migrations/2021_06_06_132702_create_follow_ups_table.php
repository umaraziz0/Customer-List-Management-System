<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFollowUpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('follow_ups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("agent_id");
            $table->foreign("agent_id")->references("id")->on("users")->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger("customer_id");
            $table->foreign("customer_id")->references("id")->on("customers")->onUpdate('cascade')->onDelete('cascade');
            $table->string("title");
            $table->text("message");
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
        Schema::dropIfExists('follow_ups');
    }
}
