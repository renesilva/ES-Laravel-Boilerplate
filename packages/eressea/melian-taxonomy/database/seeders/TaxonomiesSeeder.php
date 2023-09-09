<?php

namespace Eressea\MelianTaxonomy\Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class TaxonomiesSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // Taxonomy
    DB::table('taxonomies')->insert([
      [
        'taxonomy_name' => 'Locación',
        'taxonomy_slug' => 'location',
        'taxonomy_description' => '',
        'hierarchy' => '0',
        'is_active' => true,
      ],
    ]);
    // Terms
    $countries = [
      'Bolivia', 'Argentina', 'Brasil', 'Chile', 'Colombia', 'Ecuador', 'Paraguay', 'Peru', 'Uruguay', 'Venezuela',
      'Estados Unidos', 'Canada', 'Mexico', 'Guatemala', 'El Salvador', 'Honduras', 'Nicaragua', 'Costa Rica', 'Panama',
    ];
    foreach ($countries as $country) {
      DB::table('terms')->insert([
        [
          'taxonomy_id' => 1,
          'term_name' => $country,
          'term_slug' => Str::slug($country),
          'term_description' => '',
          'parent_term_id' => 0,
          'is_active' => true,
        ],
      ]);
    }
    // Taxonomy Field Relationship
    DB::table('taxonomy_field_relationships')->insert([
      [
        'taxonomy_field_relationship_object_class' => 'App\Models\User',
        'taxonomy_field_relationship_name' => 'Participantes de %s',
        'taxonomy_field_relationship_slug' => 'user-location',
        'taxonomy_field_relationship_description' => '',
        'taxonomy_id' => 1,
        'is_active' => true,
      ],
    ]);
  }
}

