<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer', static function (Blueprint $table) {
            $table->id('customer_id');
            $table->string('name')->unique();
            $table->string('email')->nullable()->unique();
            $table->dateTime('last_readjustment_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer');
    }
};
