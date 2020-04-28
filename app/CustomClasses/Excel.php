<?php

namespace App\CustomClasses;

/**
 * Handles Excel file operations.
 */
class Excel
{
    /**
     * Get rows from an Excel file which specified on params.
     * @param string $filename Path of the excel file.
     * @return array Excel Rows
     */
    static function getRows($filename)
    {
        $xlsx = new \SimpleXLSX($filename);
        if (!$xlsx->success())
            dd("Error:" . $xlsx->error());

        //Slice the first row as it contains category numbers.
        return array_slice($xlsx->rows(), 1);
    }
}
