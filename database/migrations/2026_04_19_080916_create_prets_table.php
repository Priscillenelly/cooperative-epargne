<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('membre_id')->constrained()->onDelete('cascade');
            $table->decimal('montant_demande', 10, 2);
            $table->decimal('montant_accorde', 10, 2)->nullable();
            $table->string('motif');
            $table->decimal('taux_interet', 5, 2)->nullable();
            $table->integer('duree_mois')->nullable();
            $table->decimal('montant_total', 10, 2)->nullable();
            $table->decimal('capital_restant', 10, 2)->nullable();
            $table->enum('statut', ['en_attente', 'approuve', 'rejete', 'rembourse'])->default('en_attente');
            $table->string('motif_rejet')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prets');
    }
};
