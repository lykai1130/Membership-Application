<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('reward_achievers', function (Blueprint $table) {
            $table->string('member_name_snapshot')->nullable()->after('achieved_at');
            $table->string('member_email_snapshot')->nullable()->after('member_name_snapshot');
            $table->string('member_referral_code_snapshot')->nullable()->after('member_email_snapshot');
        });

        if (DB::getDriverName() !== 'sqlite') {
            Schema::table('reward_achievers', function (Blueprint $table) {
                $table->dropForeign(['member_id']);
            });
        }

        Schema::table('reward_achievers', function (Blueprint $table) {
            $table->unique(['member_id', 'reward_id'], 'reward_achievers_member_reward_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reward_achievers', function (Blueprint $table) {
            $table->dropUnique('reward_achievers_member_reward_unique');
        });

        if (DB::getDriverName() !== 'sqlite') {
            Schema::table('reward_achievers', function (Blueprint $table) {
                $table->foreign('member_id')
                    ->references('id')
                    ->on('members')
                    ->cascadeOnDelete();
            });
        }

        Schema::table('reward_achievers', function (Blueprint $table) {
            $table->dropColumn([
                'member_name_snapshot',
                'member_email_snapshot',
                'member_referral_code_snapshot',
            ]);
        });
    }
};
