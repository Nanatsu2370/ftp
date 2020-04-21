<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /* Settings */
    protected $table = "category_map";
    protected $primaryKey = 'node_id';
    public $timestamps = false;
    protected $attributes = ['parent_Id' => 0];

}
