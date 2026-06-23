<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->index('first_name');
            $table->index('last_name');
            $table->index('phone_number');
        });

        Schema::table('sale_transactions', function (Blueprint $table) {
            $table->index('transaction_date');
            $table->index('payment_status');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->index('name');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->index('role');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropIndex(['first_name']);
            $table->dropIndex(['last_name']);
            $table->dropIndex(['phone_number']);
        });

        Schema::table('sale_transactions', function (Blueprint $table) {
            $table->dropIndex(['transaction_date']);
            $table->dropIndex(['payment_status']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['name']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role']);
            $table->dropIndex(['status']);
        });
    }
};
