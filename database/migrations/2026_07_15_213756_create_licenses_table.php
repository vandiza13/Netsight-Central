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
        Schema::create('licenses', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('license_key')->unique();
            $table->string('target_domain');
            $table->string('target_ip')->nullable();
            $table->integer('max_routers')->default(5);
            $table->enum('status', ['active', 'suspended', 'expired'])->default('active');
            $table->string('last_ping_ip')->nullable();
            $table->timestamp('last_ping_at')->nullable();
            $table->timestamp('expires_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('licenses');
    }
};
