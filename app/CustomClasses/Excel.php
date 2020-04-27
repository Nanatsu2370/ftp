<?php

namespace App\CustomClasses;

//Excel functions, uses SimpleXLSX to read.
class Excel
{
    /**
     * Get All rows from xlsx path.
     * @return xlsx->rows
     */
    static function getRows($path)
    {
        $xlsx = new \SimpleXLSX($path);
        if (!$xlsx->success())
            dd("Error:" . $xlsx->error());

        return $xlsx->rows();
    }
}
