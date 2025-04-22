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
        Schema::table('files', function (Blueprint $table) {
            if (!Schema::hasColumn('files', 'file_path')) {
                $table->string('file_path')->after('id');
            }
            if (!Schema::hasColumn('files', 'file_type')) {
                $table->string('file_type')->after('file_path');
            }
            if (!Schema::hasColumn('files', 'file_size')) {
                $table->unsignedBigInteger('file_size')->after('file_type');
            }
            if (!Schema::hasColumn('files', 'folder_id')) {
                $table->foreignId('folder_id')->constrained()->onDelete('cascade')->after('file_size');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('files', function (Blueprint $table) {
            if (Schema::hasColumn('files', 'folder_id')) {
                $table->dropForeign(['folder_id']);
                $table->dropColumn('folder_id');
            }
            if (Schema::hasColumn('files', 'file_size')) {
                $table->dropColumn('file_size');
            }
            if (Schema::hasColumn('files', 'file_type')) {
                $table->dropColumn('file_type');
            }
            if (Schema::hasColumn('files', 'file_path')) {
                $table->dropColumn('file_path');
            }
        });
    }
};
