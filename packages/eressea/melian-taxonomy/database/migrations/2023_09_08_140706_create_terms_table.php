<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTermsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('terms', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('taxonomy_id');
            $table->string('term_name');
            $table->string('term_slug');
            $table->text('term_description');
            $table->unsignedInteger('parent_term_id')->index('terms_parent_term_id_index');
            $table->boolean('is_active');
            
            $table->foreign('taxonomy_id', 'terms_taxonomy_id_foreign')->references('id')->on('taxonomies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('terms');
    }
}
