<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assets', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('assets')->nullOnDelete();
            $table->foreignId('asset_type_id')->constrained('asset_types')->restrictOnDelete();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('status', 40)->default('draft')->index();
            $table->timestampTz('commissioned_at')->nullable();
            $table->timestampTz('decommissioned_at')->nullable();
            $table->timestamps();

            $table->index(['asset_type_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
