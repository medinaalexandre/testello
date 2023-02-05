<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('delivery_weight_cost', static function (Blueprint $table) {
            $table->id('delivery_weight_cost_id');
            $table->integer('location_id');
            $table->float('from_weight');
            $table->float('to_weight');
            $table->integer('cost_cents');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('location_id')->references('location_id')->on('delivery_location');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_weight_cost');
    }
};
