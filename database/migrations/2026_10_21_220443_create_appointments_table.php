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
      Schema::create('appointments', function (Blueprint $table) {
    $table->id();
    $table->foreignId('service_id')->constrained();
    $table->dateTime('starts_at');
    $table->dateTime('ends_at'); // starts_at + duração do serviço
    $table->string('customer_name');
    $table->string('customer_phone');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
