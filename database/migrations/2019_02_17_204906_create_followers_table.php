<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFollowersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('followers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('service_id')->unsigned()->index();
            $table->unsignedInteger('actor_id')->index();
            $table->string('profile_url')->index();
            $table->string('inbox_url')->index();
            $table->string('shared_inbox_url')->nullable()->index();
            $table->text('public_key')->nullable();
            $table->string('key_id')->nullable()->index();
            $table->unique(['actor_id', 'profile_url']);
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
        Schema::dropIfExists('followers');
    }
}
