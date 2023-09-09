<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxonomyFieldRelationshipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taxonomy_field_relationships', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('taxonomy_id');
            $table->string('taxonomy_field_relationship_object_class');
            $table->string('taxonomy_field_relationship_name');
            $table->string('taxonomy_field_relationship_slug');
            $table->string('taxonomy_field_relationship_description');
            $table->boolean('is_active');
            
            $table->foreign('taxonomy_id', 'taxonomy_field_relationships_taxonomy_id_foreign')->references('id')->on('taxonomies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('taxonomy_field_relationships');
    }
}
