<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Category;
use App\CustomClasses\CategoryBuilder;
use Illuminate\Support\Str;


//DefineAs was deprected for this version so that is a workaround

/* Generates a Root */

$factory->state(Category::class, 'root', function () {

    $category = Category::init(Str::random(5), 0);
    return [
        'content' => $category->content,
        'lft' => $category->lft,
        'rgt' => $category->rgt,
        'parent_Id' => $category->parent_Id
    ];
});

/* Generates a child node */
$factory->define(Category::class, function () {

    $randomParent = Category::all()->random();
    $category = Category::init(Str::random(5), $randomParent->node_id);
    return [
        'content' => $category->content,
        'lft' => $category->lft,
        'rgt' => $category->rgt,
        'parent_Id' => $category->parent_Id
    ];
});
