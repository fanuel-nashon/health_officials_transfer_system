<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transfers', function (Blueprint $table) {
            $table->text('reason')->after('user_id');
            $table->text('facility_comment')->nullable()->after('level_status');
            $table->text('district_comment')->nullable()->after('facility_comment');
            $table->text('region_comment')->nullable()->after('district_comment');
            $table->text('ministry_comment')->nullable()->after('region_comment');
        });
    }

    public function down(): void
    {
        Schema::table('transfers', function (Blueprint $table) {
            $table->dropColumn(['reason', 'facility_comment', 'district_comment', 'region_comment', 'ministry_comment']);
        });
    }
};
