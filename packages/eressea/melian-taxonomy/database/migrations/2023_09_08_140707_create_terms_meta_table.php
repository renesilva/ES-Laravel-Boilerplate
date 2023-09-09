<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTermsMetaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('terms_meta', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('term_id');
            $table->string('type')->nullable();
            $table->string('key')->index('terms_meta_key_index');
            $table->text('value')->nullable();
            $table->timestamps();
            
            $table->foreign('term_id', 'terms_meta_term_id_foreign')->references('id')->on('terms')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('terms_meta');
    }
}
