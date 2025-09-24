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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('business_name')->default('OneChamber LTD');
            $table->string('email_from')->default('noreply@onechamber.com');
            $table->string('support_email')->default('support@onechamber.com');
            $table->string('phone')->nullable();
            $table->text('billing_instructions_md')->nullable();
            $table->json('address_json')->nullable();
            $table->string('default_currency')->default('KES');
            $table->string('timezone')->default('Africa/Nairobi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
