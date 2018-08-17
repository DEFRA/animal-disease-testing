<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminLookupsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('admin_lookups', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('table_name', 255);
            $table->string('description');
        });

        DB::table('admin_lookups')
            ->insert([
                ['table_name' => 'age_categories', 'description' => 'Age Categories'],
                ['table_name' => 'clinical_signs', 'description' => 'Clinical Signs'],
                ['table_name' => 'counties', 'description' => 'Counties'],
                ['table_name' => 'organic_environment', 'description' => 'Organic Environment'],
                ['table_name' => 'sex_groups', 'description' => 'Sex Groups'],
                ['table_name' => 'species', 'description' => 'Species'],
                ['table_name' => 'species_animal_breeds', 'description' => 'Species Animal Breeds'],
                ['table_name' => 'species_animal_purposes', 'description' => 'Species Animal Purposes'],
                ['table_name' => 'species_housings', 'description' => 'Species Housings']
            ]
        );

        DB::commit();

    }

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::drop('admin_lookups');
	}

}
