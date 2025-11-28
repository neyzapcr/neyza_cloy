<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('multiuploads', function (Blueprint $table) {
            $table->id();
            $table->string('filename', 250);

            // kolom reusable untuk banyak entitas
            $table->string('ref_table', 100);
            $table->integer('ref_id');

            $table->timestamps();

            // index biar query cepat
            $table->index(['ref_table', 'ref_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('multiuploads');
    }
};
