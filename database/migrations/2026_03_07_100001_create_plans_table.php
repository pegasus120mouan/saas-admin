<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price_monthly', 10, 2)->default(0);
            $table->decimal('price_yearly', 10, 2)->default(0);
            $table->string('currency', 3)->default('EUR');
            $table->integer('trial_days')->default(14);
            
            // Limites du plan
            $table->integer('max_users')->default(1);
            $table->integer('max_customers')->default(100);
            $table->integer('max_invoices_per_month')->default(50);
            $table->integer('max_products')->default(100);
            $table->integer('max_quotes_per_month')->default(50);
            $table->integer('max_storage_mb')->default(500);
            
            // Fonctionnalités
            $table->boolean('feature_quotes')->default(true);
            $table->boolean('feature_expenses')->default(true);
            $table->boolean('feature_reports')->default(false);
            $table->boolean('feature_api_access')->default(false);
            $table->boolean('feature_multi_currency')->default(false);
            $table->boolean('feature_custom_templates')->default(false);
            $table->boolean('feature_reminders')->default(false);
            $table->boolean('feature_stock_management')->default(false);
            $table->boolean('feature_purchase_orders')->default(false);
            $table->boolean('feature_priority_support')->default(false);
            $table->boolean('feature_white_label')->default(false);
            
            $table->json('features_list')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_popular')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
