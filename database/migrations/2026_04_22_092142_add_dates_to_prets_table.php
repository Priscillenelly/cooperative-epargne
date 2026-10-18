<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('prets', function (Blueprint $table) {
            $table->date('date_demande')->nullable()->after('motif');
            $table->date('date_approbation')->nullable()->after('date_demande');
        });
    }

    public function down(): void
    {
        Schema::table('prets', function (Blueprint $table) {
            $table->dropColumn(['date_demande', 'date_approbation']);
        });
    }
};
