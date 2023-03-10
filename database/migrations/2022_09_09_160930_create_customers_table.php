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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('claimtype')->nullable();
            $table->string('approvaldate')->nullable();
            $table->string('company')->nullable();
            $table->string('amount')->nullable();
            $table->string('accname')->nullable();
            $table->string('bank')->nullable();
            $table->string('bankbranch')->nullable();
            $table->string('accnumber')->nullable();
            $table->decimal('employee_contribution')->nullable();
            $table->decimal('employer_contribution')->nullable();
            $table->decimal('withdrawal_amount')->nullable();
            $table->float('tax_percentage')->default(0.0);
            $table->decimal('amount_payable_to_ssnit')->nullable();
            $table->string('policy_number')->nullable();
            $table->string('name_on_cheque')->nullable();
            $table->string('cheque_number')->nullable();
            $table->string('beneficiaries')->nullable();
            $table->bigInteger('claim_id')->unsigned();
            
            $table->boolean('payment_status')->default(false);
            $table->foreign('claim_id')->references('id')->on('claims')->onDelete('cascade');
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
        Schema::dropIfExists('customers');
    }
};