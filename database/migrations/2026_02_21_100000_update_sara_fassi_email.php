<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('users')
            ->where('email', 'sara.devofws4@ofppt.com')
            ->update(['email' => 'elmehdisekrare@gmail.com']);
    }

    public function down(): void
    {
        DB::table('users')
            ->where('email', 'elmehdisekrare@gmail.com')
            ->update(['email' => 'sara.devofws4@ofppt.com']);
    }
};
