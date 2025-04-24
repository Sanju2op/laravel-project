    <?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add user_id column as nullable first
        Schema::table('categories', function (Blueprint $table) {
            if (!Schema::hasColumn('categories', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id');
            }
        });

        // Assign user_id to existing categories (assign to user id 1 as default)
        DB::table('categories')->whereNull('user_id')->update(['user_id' => 1]);

        // Make user_id column non-nullable and add foreign key constraint
        Schema::table('categories', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
        });

        // Drop foreign key if exists and add foreign key constraint
        // Removed getDoctrineSchemaManager() usage due to error in Laravel MySQL connection
        // Instead, try to drop foreign key and catch exception if it does not exist
        try {
            Schema::table('categories', function (Blueprint $table) {
                $table->dropForeign('categories_user_id_foreign');
            });
        } catch (\Exception $e) {
            // Foreign key does not exist, ignore
        }
        Schema::table('categories', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
