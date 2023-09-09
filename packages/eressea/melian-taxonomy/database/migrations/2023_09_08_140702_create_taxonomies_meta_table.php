<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxonomiesMetaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taxonomies_meta', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('taxonomy_id');
            $table->string('type')->nullable();
            $table->string('key')->index('taxonomies_meta_key_index');
            $table->text('value')->nullable();
            $table->timestamps();
            
            $table->foreign('taxonomy_id', 'taxonomies_meta_taxonomy_id_foreign')->references('id')->on('taxonomies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('taxonomies_meta');
    }
}
