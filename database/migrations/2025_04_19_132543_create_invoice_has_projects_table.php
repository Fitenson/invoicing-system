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
        Schema::create('invoice_has_projects', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('invoice')->nullable();
            $table->uuid('project')->nullable();
            $table->uuid('created_by')->nullable();
            $table->uuid('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('invoice')->references('id')->on('invoices')->onDelete('set null');
            $table->foreign('project')->references('id')->on('projects')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_has_projects');
    }
};
