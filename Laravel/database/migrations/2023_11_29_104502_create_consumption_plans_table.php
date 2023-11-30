<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('consumption_plans', function (Blueprint $table) {
            $table->id();
            $table->string('client');
            $table->string('client_user');
            $table->string('client_plan');
            $table->decimal('consumption')->comment('In MWh');
            $table->dateTime('last_update')->nullable();
            $table->string('status')->comment('Final, Draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consumption_plans');
    }
};
