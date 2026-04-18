<?php

use App\Models\Member;
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
        if (!Schema::hasColumn('documents', 'documentable_id')) {
            Schema::table('documents', function (Blueprint $table) {
                $table->unsignedBigInteger('documentable_id')->nullable()->after('id');
                $table->string('documentable_type')->nullable()->after('documentable_id');
                $table->index(['documentable_type', 'documentable_id'], 'documents_documentable_index');
            });
        }

        if (Schema::hasColumn('documents', 'member_id')) {
            DB::table('documents')->update([
                'documentable_id' => DB::raw('member_id'),
                'documentable_type' => Member::class,
            ]);

            Schema::table('documents', function (Blueprint $table) {
                $table->dropConstrainedForeignId('member_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasColumn('documents', 'member_id')) {
            Schema::table('documents', function (Blueprint $table) {
                $table->foreignId('member_id')->nullable()->after('id')->constrained('members')->cascadeOnDelete();
            });
        }

        if (Schema::hasColumn('documents', 'documentable_id')) {
            DB::table('documents')
                ->where('documentable_type', Member::class)
                ->update([
                    'member_id' => DB::raw('documentable_id'),
                ]);

            Schema::table('documents', function (Blueprint $table) {
                $table->dropIndex('documents_documentable_index');
                $table->dropColumn(['documentable_id', 'documentable_type']);
            });
        }
    }
};

