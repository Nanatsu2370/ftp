<?php

namespace App\CustomClasses;

//Excel functions, uses SimpleXLSX to read.
class Excel
{
    //Returns all rows of the xlsx file, specified on parameter.
    static function getRows($url)
    {
        $xlsx = new \SimpleXLSX($url);
        if (!$xlsx->success())
            dd("Error:" . $xlsx->error());

        return $xlsx->rows();
    }
    //Seperates a xlsx row data to its contents and parent texts, for each cell.
    static function getRowData($row)
    {
        $rowData = array();
        foreach ($row as $i => $content)
        {
            //We don't need empty cells. These are last element.
            if ($content == "")
                break;
            //A row's first element could not contain a parent.
            else if ($i == 0)
                $parent_text = '';
            else
                $parent_text = $row[$i - 1];

            $celldata = array(
                "content" => $content,
                "parentText" => $parent_text
            );
            array_push($rowData,$celldata);
        }
        return $rowData;
    }
}
