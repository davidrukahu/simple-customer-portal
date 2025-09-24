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
        Schema::create('domains', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->string('fqdn');
            $table->string('registrar')->nullable();
            $table->date('registered_at')->nullable();
            $table->date('expires_at');
            $table->integer('term_years')->default(1);
            $table->decimal('price', 10, 2);
            $table->string('currency')->default('KES');
            $table->enum('status', ['active', 'expired', 'grace', 'redemption', 'transfer-pending'])->default('active');
            $table->boolean('auto_renew')->default(false);
            $table->text('service_notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->unique(['customer_id', 'fqdn']);
            $table->index(['customer_id', 'expires_at']);
            $table->index(['status', 'expires_at']);
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('domains');
    }
};
