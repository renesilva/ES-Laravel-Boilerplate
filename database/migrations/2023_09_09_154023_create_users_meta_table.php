<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('users_meta', function (Blueprint $table) {
      $table->id();
      $table->bigInteger('user_id')->unsigned()->index();
      $table->string('type')->nullable();
      $table->string('key')->index('users_meta_key_index');
      $table->text('value')->nullable();
      $table->timestamps();

      $table->foreign('user_id', 'users_meta_user_id_foreign')->references('id')->on('users')->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('users_meta');
  }
};
