<?php
namespace App\CustomClasses;
use App\Category;
use App\CustomClasses\CategoryBuilder;

class Row
{
    public $categories = [];
    public function __construct($data)
    {
        foreach ($data as $i=>$cell) {
            //We don't need empty cells. These are last element.
            if ($cell == "")
                break;
            //A row's first element could not contain a parent.
            else if ($i == 0)
                $parentText = '';
            else
                $parentText = $data[$i - 1];

            $category = Category::init($cell,$parentText);
            array_push($this->categories,$category);
        }
    }
    public function processCells()
    {
        foreach($this->categories as $category){
            CategoryBuilder::insert($category->content, $category->parentId);
        }
    }

}
