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
        Schema::table('contact_messages', function (Blueprint $table) {
            $table->string('status')
                ->default('new')
                ->after('ip_address');

            $table->timestamp('handled_at')
                ->nullable()
                ->after('status');

            $table->unsignedBigInteger('handled_by')
                ->nullable()
                ->after('handled_at');

            $table->text('admin_notes')
                ->nullable()
                ->after('handled_by');

            $table->foreign('handled_by')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contact_messages', function (Blueprint $table) {
            $table->dropForeign(['handled_by']);
            $table->dropColumn(['status', 'handled_at', 'handled_by', 'admin_notes']);
        });
    }
};
