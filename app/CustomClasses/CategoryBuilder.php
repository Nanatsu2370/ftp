<?php
namespace App\CustomClasses;
use App\Category;

/*
 * A Builder class for adding categories.
 * //? Maybe we should merge it into the models.
 *
*/
class CategoryBuilder
{
    //Insertion of a node. Creates and saves into the database.
    private static function insertNode($content,$left, $parentId)
    {
        $category = new Category;
        $category->content = $content;
        $category->lft = $left;
        $category->rgt = $left + 1;
        $category->parent_Id = $parentId;
        $category->save();
    }
    //Insertion of a root node. Has a few settings, then regular node insertion.
    private static function insertRoot($content)
    {
        //last available space for new root.
        $last_root_node = Category::where('parent_Id', 0)
            ->orderBy('rgt', 'desc')
            ->first();

        if (isset($last_root_node))
            $new_left = $last_root_node->rgt + 1;
        else
            $new_left = 1;

        self::insertNode($content,$new_left, 0);
    }
    //Basic insertion command. Checks the node is root, aligns the other node's values.
    static function insert($content, $parentText)
    {

        //? Maybe we dont need that nullcheck.
        if (!isset($content) || !isset($parentText))
            return;
        //If node already exists, then we shouldn't add it.
        if (Category::where("content", $content)->exists())
            return;

        //Node is root? If it isn't, then we need to increment other values.
        if ($parentText == "")
            self::insertRoot($content);
        else {
            $parentNode = Category::whereContent($parentText)->first();
            $left = $parentNode->rgt;
            Category::where('rgt', '>=', $left)->increment('rgt', 2);
            Category::where('lft', '>', $left)->increment('lft', 2);
            self::insertNode($content,$left, $parentNode->node_id);
        }
    }

    static function dump(){
        $data = Category::all();
        return $data->toJson(JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    }
}
