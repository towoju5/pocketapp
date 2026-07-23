<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The users.avatar column has defaulted every account (via
     * 2024_12_24_999999_add_avatar_to_users / the original users table
     * migration) to asset('assets/profile/default.png') — a file that has
     * never actually existed anywhere in public/assets. Any user who never
     * uploaded a real photo therefore has a permanently-broken avatar URL
     * baked into their row, which is indistinguishable from a genuine
     * upload failure once the avatar is actually rendered in the UI. Using
     * raw SQL rather than Schema::table()->change() since that requires
     * doctrine/dbal, which isn't installed.
     */
    public function up(): void
    {
        // SQLite (local/dev) has no ALTER COLUMN and only matters for a
        // handful of local rows — the broken default is a production/MySQL
        // data problem, since that's where real signups happened.
        if (Schema::getConnection()->getDriverName() === 'sqlite') {
            return;
        }

        DB::statement('ALTER TABLE users MODIFY avatar VARCHAR(255) NULL DEFAULT NULL');
        DB::statement("UPDATE users SET avatar = NULL WHERE avatar LIKE '%/assets/profile/default.png'");
    }

    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() === 'sqlite') {
            return;
        }

        DB::statement("ALTER TABLE users MODIFY avatar VARCHAR(255) NOT NULL DEFAULT ''");
    }
};
