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
        // you can use $table->text() instead of $table->string() if you want to store more than 255 characters
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->uuid()->index();
            $table->foreignUuid('user_id')->constrained('users','uuid');   
            $table->string('name'); 
            $table->double('balance', 8, 2)->default(0);    
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
