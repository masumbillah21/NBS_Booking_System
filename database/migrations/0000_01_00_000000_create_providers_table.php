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
        Schema::create('providers', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->text('description');
            $table->string('logo')->nullable();
            $table->string('address');
            $table->string('phone_number');
            $table->string('email')->unique();
            $table->foreignId('category_id')->constrained('categories');
            $table->integer('status')->default(0);
            $table->string('slug')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('providers');
    }
};
