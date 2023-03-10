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
        Schema::create('claims', function (Blueprint $table) {
            $table->id();
            $table->string('claimid')->nullable();
            $table->text('description')->nullable();
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->bigInteger('scheme_id')->unsigned();
            $table->foreign('scheme_id')->references('id')->on('schemes');
            $table->bigInteger('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('companies');
            $table->string('department_reached')->nullable();
            $table->integer('department_reached_id')->nullable();
            $table->string('state')->nullable(); //1-Uploaded, 2-Donwloaded
            $table->boolean('paid')->default(false); //claim paid
            $table->boolean('active')->default(true); //if claim has been cancelled
            $table->decimal('claim_amount')->nullable();
            $table->string('claim_directory')->nullable(); //root directory for claim files
            $table->string('head_directory')->nullable(); // current department claim reached directory
            $table->string('payment_status')->nullbale();  // pending / paid
            $table->boolean('audited')->default(false);  // if files/claim is audited
            $table->bigInteger('auditor_id')->nullable(); 
            $table->string('audited_by')->nullable(); // name of user that audited he claim
            $table->boolean('has_issue')->default(false);
            $table->boolean('processed')->default(false); //
            $table->bigInteger('payment_id')->nullable();
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
        Schema::dropIfExists('claims');
    }
};