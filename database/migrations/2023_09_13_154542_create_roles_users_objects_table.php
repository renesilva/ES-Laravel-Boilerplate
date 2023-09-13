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
    Schema::create('roles_users_objects', function (Blueprint $table) {
      $table->id();
      $table->string('object_class');
      $table->unsignedBigInteger('object_id');
      $table->bigInteger('user_id')->unsigned()->index();
      $table->bigInteger('role_id')->unsigned()->index();
      $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('roles_users_objects');
  }
};
