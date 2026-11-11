<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            if (!Schema::hasColumn('messages', 'chat_id')) {
                $table->foreignId('chat_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('chats')
                    ->nullOnDelete();
            }
            if (!Schema::hasColumn('messages', 'attachment')) {
                $table->string('attachment')->nullable()->after('type');
            }
        });
    }

    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            if (Schema::hasColumn('messages', 'chat_id')) {
                $table->dropForeign(['chat_id']);
                $table->dropColumn('chat_id');
            }
            if (Schema::hasColumn('messages', 'attachment')) {
                $table->dropColumn('attachment');
            }
        });
    }
};
