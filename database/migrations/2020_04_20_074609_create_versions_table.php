<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVersionsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('versions', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->bigInteger('article_id')->unsigned();
            $table->foreign('article_id')->references('id')->on('articles');
            $table->bigInteger('editor_id')->unsigned();
            $table->string('old_header', 100)->nullable();
            $table->string('header', 100)->nullable();
            $table->string('old_description', 255)->nullable();
            $table->string('description', 255)->nullable();
            $table->text('old_text')->nullable();
            $table->text('text')->nullable();
            $table->text('old_tags')->nullable();
            $table->text('tags')->nullable();
            $table->tinyInteger('old_publish')->nullable();
            $table->tinyInteger('publish')->nullable();
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
        Schema::dropIfExists('versions');
    }
}
