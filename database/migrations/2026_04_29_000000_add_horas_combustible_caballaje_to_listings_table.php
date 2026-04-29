<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->unsignedInteger('horas')->nullable()->after('condition');
            $table->string('combustible')->nullable()->after('horas');
            $table->unsignedInteger('caballaje')->nullable()->after('combustible');
        });
    }

    public function down(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->dropColumn(['horas', 'combustible', 'caballaje']);
        });
    }
};
