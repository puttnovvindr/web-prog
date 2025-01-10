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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('gender', ['Male', 'Female'])->after('email');
            $table->json('fields_of_work')->after('gender');
            $table->string('linkedin_url')->nullable()->after('fields_of_work');
            $table->string('mobile_number')->nullable()->after('linkedin_url');
            $table->integer('wallet_balance')->default(0)->after('mobile_number');
            $table->boolean('visible')->default(true)->after('wallet_balance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['gender', 'fields_of_work', 'linkedin_url', 'mobile_number', 'wallet_balance', 'visible']);
        });
    }
};
