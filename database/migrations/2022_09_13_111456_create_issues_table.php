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
        Schema::create('issues', function (Blueprint $table) {
            $table->id();
            $table->string('issue_ticket')->nulable(); //random issue tracker
            $table->string('title')->nullable();
            $table->text('message');
            $table->bigInteger('claim_id')->unsigned();
            $table->foreign('claim_id')->references('id')->on('claims');
            $table->boolean('resolved')->default(false);
            $table->bigInteger('department_id')->unsigned(); //department issue is meant for or who should resolve the issue
            $table->foreign('department_id')->references('id')->on('departments');
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
        Schema::dropIfExists('issues');
    }
};