<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agents', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('service_id')->unsigned()->nullable()->index();
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->uuid('slug')->unique()->index();
            $table->boolean('local')->default(true);
            $table->unsignedInteger('frequency')->default(15);
            $table->string('check_url')->nullable();
            $table->string('check_text')->nullable();
            $table->boolean('active')->default(false);
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
        Schema::dropIfExists('agents');
    }
}
