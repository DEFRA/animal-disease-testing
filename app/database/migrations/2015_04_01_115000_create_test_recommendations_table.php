<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTestRecommendationsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_recommendations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('species_group');
            $table->string('species');
            $table->string('disease');
            $table->string('age_category');
            $table->string('condition_cause');
            $table->string('sample_type');
            $table->string('tests');
            $table->text('further_info');
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
        Schema::drop('test_recommendations');
    }

}
