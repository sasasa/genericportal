<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            // タイトル
            $table->string('title');

            // タイプ
            $table->tinyInteger('article_type');

            // 画像
            $table->string('image_path')->nullable();

            // サムネイルパス
            $table->string('thumbnail_path')->nullable();

            // 要約
            $table->string('description');

            // 本文
            $table->text('body');

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
        Schema::dropIfExists('articles');
    }
}
