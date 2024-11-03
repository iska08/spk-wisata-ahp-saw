<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('criterias', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kriteria')->unique();
            $table->string('slug')->unique();
            $table->string('kategori');
            $table->longText('keterangan');
            $table->string('skala1');
            $table->string('skala2');
            $table->string('skala3');
            $table->string('skala4');
            $table->string('skala5');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('criterias');
    }
};
