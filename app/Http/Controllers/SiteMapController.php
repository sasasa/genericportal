<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Article;
use App\Models\Item;
use App\Models\Tag;

class SiteMapController extends Controller
{
    private function kumiawase(array $zentai, int $nukitorisu): ?array {
        $zentaisu=count($zentai);
        if($zentaisu < $nukitorisu){
            return null;
        }elseif($nukitorisu == 1){
            for($i=0; $i<$zentaisu; $i++){
                $arrs[$i] = array($zentai[$i]);
            }
        }elseif($nukitorisu > 1){
            $j=0;
            for($i=0; $i<$zentaisu-$nukitorisu+1; $i++){
                $ts = $this->kumiawase(array_slice($zentai,$i+1), $nukitorisu-1);
                foreach($ts as $t){
                    array_unshift($t, $zentai[$i]);
                    $arrs[$j] = $t;
                    $j++;
                }
            }
        }
        return $arrs;
    }

    private function makeKumiawaseAry(array $array, int $i): array {
        return collect($this->kumiawase($array, $i))->map(function($ary){
            return collect($ary)->map(function($val){
                return "tags%5B%5D=". $val["id"];
            })->toArray();
        })->toArray();
    }

    public function sitemap()
    {
        $sitemap = \App::make("sitemap");
        $now = Carbon::now();

        $articles = Article::where('article_type', Article::OPEN)->orderBy('id', 'DESC')->get();
        foreach ($articles as $article) {
            $images = [];
            $ary = [
                'url' => \URL::to('/storage/'. $article->image_path),
                'title' => $article->title,
                'caption' => $article->title,
                'geo_location' => '',
            ];
            $images[] = $ary;
            $sitemap->add(\URL::to(route('root.article', ['article'=>$article])), $now, '0.9', 'weekly', $images);
        }

        $tags = Tag::orderBy('id', 'DESC')->get();
        $tagsArray = $tags->toArray();

        foreach ($tags as $tag) {
            $sitemap->add(\URL::to(route('root.tag', ['tag'=>$tag])), $now, '0.9', 'weekly', null);

            for($i=1; $i<5; $i++) {
                // 4つまでの組み合わせを作成して組み合わせにtag->idが含まれている時はURLとして構築する
                $tmpAry = $this->makeKumiawaseAry($tagsArray, $i);
                foreach($tmpAry as $tmp) {
                    $key = in_array("tags%5B%5D=". $tag->id, $tmp);
                    if( $key ) {
                        $tmpStr = implode("&", $tmp);
                        $sitemap->add(\URL::to(route('root.tag', ['tag'=>$tag]). '?'. $tmpStr), $now, '0.9', 'weekly', null);
                    }
                }
            }

            foreach ($tag->articles()->where('article_type', Article::OPEN)->orderBy('id', 'DESC')->get() as $article) {
                $images = [];
                $ary = [
                    'url' => \URL::to('/storage/'. $article->image_path),
                    'title' => $article->title,
                    'caption' => $article->title,
                    'geo_location' => '',
                ];
                $images[] = $ary;
                $sitemap->add(\URL::to(route('root.tag.article', ['tag'=>$tag, 'article'=>$article])), $now, '0.9', 'weekly', $images);
            }

            foreach ($tag->items()->orderBy('id', 'DESC')->get() as $item) {
                $images = [];
                $ary = [
                    'url' => \URL::to('/storage/'. $item->image_path),
                    'title' => $item->item_name,
                    'caption' => $item->item_name,
                    'geo_location' => '',
                ];
                $images[] = $ary;
                $sitemap->add(\URL::to(route('root.tag.item', ['tag'=>$tag, 'item'=>$item])), $now, '0.9', 'weekly', $images);
            }
        }

        $items = Item::orderBy('id', 'DESC')->get();
        foreach ($items as $item) {
            $images = [];
            $ary = [
                'url' => \URL::to('/storage/'. $item->image_path),
                'title' => $item->item_name,
                'caption' => $item->item_name,
                'geo_location' => '',
            ];
            $images[] = $ary;
            $sitemap->add(\URL::to(route('root.item', ['item'=>$item])), $now, '0.9', 'weekly', $images);
        }

        $sitemap->add(\URL::to(route('root')), $now, '1.0', 'weekly');
        // generate your sitemap (format, filename)
        // $sitemap->store('xml', 'sitemap');
        return $sitemap->render('xml');
    }
}
