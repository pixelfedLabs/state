<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentChecksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_checks', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedInteger('system_id')->nullable()->index();
            $table->bigInteger('service_id')->unsigned()->nullable()->index();
            $table->unsignedInteger('agent_id')->index();

            $table->unsignedInteger('response_code')->nullable()->index();
            $table->json('headers')->nullable();
            $table->boolean('online')->default(false)->index();

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
        Schema::dropIfExists('agent_checks');
    }
}
