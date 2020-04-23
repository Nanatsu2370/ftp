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
    //Insertion of a root node. Has a few settings, then regular node insertion.
    private static function getAsRoot($content)
    {
        //last available space for new root.
        $last_root_node = Category::getLastRoot();

        if (isset($last_root_node))
            $new_left = $last_root_node->rgt + 1;
        else
            $new_left = 1;

        return Category::init($content,$new_left, 0);
    }
    //Basic insertion command. Checks the node is root, aligns the other node's values.
    static function generateCategory($content, $parentText)
    {
        //Node is root? If it isn't, then we need to increment other values.
        if ($parentText == "")
            return self::getAsRoot($content);

        $parentNode = Category::whereContent($parentText)->first();
        $left = $parentNode->rgt;
        Category::adjustIndexes($left);
        return Category::init($content,$left, $parentNode->node_id);

    }
    static function insert($content, $parentText){

        if (!isset($content) || !isset($parentText))
            return;

        //If node already exists, then we shouldn't add it.
        if (Category::where("content", $content)->exists())
            return;

        $category = self::generateCategory($content,$parentText);
        $category->save();
    }

}
