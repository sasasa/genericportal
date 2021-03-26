<?php

namespace App\Models;
/**
 * tag付けができるようになる
 * useで読み込まれる
 */
trait Taggable {

  public function tags(): object
  {
      return $this->belongsToMany('App\Models\Tag');
  }

  public function tagIds(): array
  {
      return $this->tags()->get()->modelKeys();
  }
}