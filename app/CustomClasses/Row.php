<?php
namespace App\CustomClasses;

class Row
{
    /**
     * Cell values of the row.
     */
    public $cells = [];

    /**
     * Creates a row instance, splits row data into cells.
     * @param array $data Row data
     * @return void
     */
    public function __construct($data)
    {
        foreach ($data as $i=>$cell) {
            //We don't need empty cells. These are last element.
            if ($cell == "")
                break;
            //A row's first element could not contain a parent.
            else if ($i == 0)
                $parentText = "";

            else{
                $parentText = $data[$i - 1];
            }

            $cellData = array(
                'content'=>$cell,
                'parentText'=>$parentText
            );

            array_push($this->cells, $cellData);
        }
    }
}
