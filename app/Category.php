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
        $last_root_node = self::where('parent_Id', 0)
            ->orderBy('rgt', 'desc')
            ->first();

        if (isset($last_root_node))
            return $last_root_node->rgt + 1;
        else
            return 1;
    }
    private static function calculateLeft($parent_Id)
    {
         if ($parent_Id == 0)
            return self::calculateLeftRoot();

        $parentNode = Category::find($parent_Id);
        return $parentNode->rgt;
    }
    //Seems like constructor which has parameters, has problems with Eloquent's static calls.
    static function init($content,$parentId=0){
        $left = self::calculateLeft($parentId);

        $category = new Category;
        $category->content = $content;
        $category->parent_Id = $parentId;
        $category->lft = $left;
        $category->rgt = $left + 1;
        return $category;
    }

    static function calculateId($parentText){
        if ($parentText == "")
            return 0;

        return Category::where('content', $parentText)
            ->first()->node_id;
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
        //Nullcheck
        if (!isset($this->content) || !isset($this->parent_Id))
            return;
        //If node already exists, then we shouldn't add it.
        if (self::where("content", $this->content)->exists())
            return;

        self::adjustIndexes($this->lft);
        parent::save();
    }
}
