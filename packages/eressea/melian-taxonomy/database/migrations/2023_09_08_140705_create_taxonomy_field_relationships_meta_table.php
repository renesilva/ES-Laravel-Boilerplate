<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxonomyFieldRelationshipsMetaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taxonomy_field_relationships_meta', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('taxonomy_field_relationship_id');
            $table->string('type')->nullable();
            $table->string('key')->index('taxonomy_field_relationships_meta_key_index');
            $table->text('value')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('taxonomy_field_relationships_meta');
    }
}
