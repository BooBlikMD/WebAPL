<?php

class Post extends Eloquent {

    protected $table = 'apl_post';
    public static $ftable = 'apl_post'; // public table name
    public $timestamps = true;

    public function langs() {
        return $this->hasMany('PostLang', 'post_id', 'id');
    }

    public static function tree($taxonomy_id, $parent = 0) {
        $list = Post::where('parent', $parent)->where('taxonomy_id', $taxonomy_id)->get();

        foreach ($list as &$item) {
            $item['lang'] = $item->langs()->where('lang_id', Language::getId())->first();
            $item['list'] = Post::tree($taxonomy_id, $item->id);
        }

        return $list;
    }

    public static function findTax($id, $taxonomy_id) {
        return Post::where('id', $id)->where('taxonomy_id', $taxonomy_id)->first();
    }

    public static function feedsID($post_id) {
        $ids = array();
        foreach (FeedPost::where('post_id', $post_id)->get() as $record) {
            $ids[] = $record->feed_id;
        }
        return $ids;
    }

}
