<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('delivery_location', static function (Blueprint $table) {
            $table->id('location_id');
            $table->integer('customer_id');
            $table->char('from_postcode', 8);
            $table->char('to_postcode', 8);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('customer_id')->references('customer_id')->on('customer');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_location');
    }
};
