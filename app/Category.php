<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Category extends Model
{
    use Notifiable;
    /* Settings */
    protected $table = "category_map";
    protected $primaryKey = 'node_id';
    public $timestamps = false;
    protected $attributes = ['parent_Id' => 0];

    //Seems like constructor has problems with Eloquent's static calls.
    static function init($content,$left=1,$parentId=0){
        $category = new Category;
        $category->content = $content;
        $category->lft = $left;
        $category->rgt = $left + 1;
        $category->parent_Id = $parentId;
        return $category;
    }
    static function getLastRoot(){
        return self::where('parent_Id', 0)
            ->orderBy('rgt', 'desc')
            ->first();
    }
    static function adjustIndexes($limit){
        self::where('rgt', '>=', $limit)->increment('rgt', 2);
        self::where('lft', '>', $limit)->increment('lft', 2);
    }
    static function dump()
    {
        $data = parent::all();
        return $data->toJson(JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
}
