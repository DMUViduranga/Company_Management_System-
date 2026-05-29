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
       
        Schema::create('device_authorizations', function (Blueprint $table) {
            $table->id();
           
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('device_token')->index(); 
            $table->string('browser_name')->nullable();
            $table->string('ip_address')->nullable();
            $table->boolean('is_approved')->default(false); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('device_authorizations');
    }
};
