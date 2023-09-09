<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxonomyFieldRelationshipObjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taxonomy_field_relationship_objects', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('taxonomy_field_relationship_id');
            $table->unsignedInteger('term_id');
            $table->unsignedInteger('object_id');
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('taxonomy_field_relationship_objects');
    }
}
