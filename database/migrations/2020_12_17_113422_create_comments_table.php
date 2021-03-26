<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            // コメント
            $table->string('title');
            // 画像パス
            $table->string('image_path');
            // サムネイルパス
            $table->string('thumbnail_path')->nullable();
            // タイプ
            $table->text('body');
            // 外部キー
            $table->biginteger('item_id')->unsigned()->index();

            $table->timestamps();

            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
