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

    private static function calculateLeftRoot(){
        $last_root_node = self::getLastRoot();

        if (isset($last_root_node))
            return $last_root_node->rgt + 1;
        else
            return 1;
    }
    private static function calculateLeft($parentId=0)
    {
         if ($parentId == 0)
            return self::calculateLeftRoot();
        echo $parentId;
        $parentNode = Category::find($parentId);
        return $parentNode->rgt;
    }
    //Seems like constructor has problems with Eloquent's static calls.
    static function init($content,$parentId=0){
        $left = self::calculateLeft($parentId);

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
    public function save(array $options = []){
        self::adjustIndexes($this->lft);
        parent::save();
    }
}
