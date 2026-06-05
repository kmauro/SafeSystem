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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone');
            $table->string('email')->unique();
            $table->string('cuit')->unique()->nullable();
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('category');
        });

        Schema::create('states', function (Blueprint $table) {
            $table->id();
            $table->string('state');
            $table->string('color');
        });

        Schema::create('installations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->string('address');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->foreignId('state_id')->constrained('states')->onDelete('cascade');
            $table->foreignId('assigned_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('description');
            $table->string('annotation')->nullable();
            $table->timestamps();
        });

        Schema::create('installation_devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->constrained('devices')->onDelete('cascade');
            $table->foreignId('installation_id')->constrained('installations')->onDelete('cascade');
            $table->string('code');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('states');
        Schema::dropIfExists('installations');
        Schema::dropIfExists('installation_devices');
    }
};
