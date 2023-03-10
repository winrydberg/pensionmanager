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
        Schema::create('customer_breakdowns', function (Blueprint $table) {
            $table->id();
            $table->string('staff_no')->nullable();
            $table->string('policy_number_non_comp')->nullable();
            $table->string('policy_number_comp')->nullable();
            $table->string('policy_number')->nullable();
            $table->string('claimant')->nullable();
            $table->string('claimant_portion')->nullable();
            $table->string('non_claimant_portion')->nullable();
            $table->decimal('employee_contribution')->nullable();
            $table->decimal('employer_contribution')->nullable();
            $table->decimal('employee_portion_earned')->nullable();
            $table->decimal('employer_portion_earned')->nullable();
            $table->decimal('withdrawal_amount')->nullable();
            $table->decimal('taxable_portion')->nullable();
            $table->decimal('tax_application')->nullable();
            $table->decimal('deductible')->nullable();
            $table->decimal('refund')->nullable();
            $table->decimal('outstanding_loadn_deduction')->nullable();
            $table->decimal('employer_deduction')->nullable();
            $table->decimal('amount_payable_to_ssnit')->nullable();
            $table->decimal('amount_payable')->nullable();
            $table->decimal('deductions')->nullable();
            $table->string('beneficiaries')->nullable();
            $table->decimal('suspense')->nullable();
            $table->bigInteger('claim_id')->unsigned();
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
        Schema::dropIfExists('customer_breakdowns');
    }
};
