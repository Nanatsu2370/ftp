<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Category;
use App\CustomClasses\CategoryBuilder;
use Illuminate\Support\Str;


//DefineAs was deprected for this version so that is a workaround

/* Generates a Root */

$factory->state(Category::class, 'root', function () {
    $category = CategoryBuilder::generateCategory(Str::random(5), "");
    return [
        'content' => $category->content,
        'lft' => $category->lft,
        'rgt' => $category->rgt,
        'parent_Id' => $category->parent_Id
    ];
});

/* Generates a child node */
$factory->state(Category::class, "node", function () {
    $randomParent = Category::all()->random();
    $category = CategoryBuilder::generateCategory(Str::random(5), $randomParent->content);
    return [
        'content' => $category->content,
        'lft' => $category->lft,
        'rgt' => $category->rgt,
        'parent_Id' => $category->parent_Id
    ];
});

/* Default values. */
$factory->define(Category::class, function () {
    return [
        'content' => "",
        'lft' => -2,
        'rgt' => -1,
        'parent_Id' => -1
    ];
});
