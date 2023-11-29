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
        Schema::create('client_plans', function (Blueprint $table) {
            $table->id();
            $table->string('short_name');
            $table->longText('description');
            $table->tinyInteger('status')->comment('1 = Active, 0 = Deactivate');
            $table->unsignedBigInteger('client_id');
            $table->string('purchase_source');
            $table->decimal('source_capacity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_plans');
    }
};
