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
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->string("user_id")->index();
            $table->text("cover_photo")->nullable();
            $table->string("date_of_birth")->nullable();
            $table->string("anniversary_date")->nullable();
            $table->string("alternative_mobile")->nullable();
            $table->enum("gender",['male','female','other'])->nullable();
            $table->enum("food_preference",['veg','non_veg','both'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
