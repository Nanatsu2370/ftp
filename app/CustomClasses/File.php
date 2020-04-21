<?php

namespace App\CustomClasses;

//require_once base_path() . '/vendor/autoload.php';

use App\CustomClasses\FTP;
//Handles Filename's string manipulations.
class FTP_File
{
    //Extracts the date of the filename. We need the recent one so first, we need the dates.
    static function extractDate($filename)
    {
        //Format : kategoriler-{YmdHis}.xlsx​
        $dateFormat = "YmdHis";
        preg_match("/\d+/", $filename, $m);
        if (!$m) return NULL;
        $date =  \DateTime::createFromFormat($dateFormat, $m[0]);
        return $date;
    }

    //Gets the most recent file's path from the FTP.
    static function getRecentURL()
    {
        $ftp_basePath = "categories";
        $files = FTP::getAllFiles($ftp_basePath);
        $recentFile = FTP_File::getNewest($files);
        $ftp_filePath = FTP_File::combinePath($ftp_basePath, $recentFile);
        return FTP::buildFTPUrl($ftp_filePath);
    }

    //Now, Start from the base date, get recent one's file.
    static function getNewest($files)
    {
        $date = new \DateTime('@0');

        foreach ($files as $file) {
            $tempDate = self::extractDate($file);
            if (!isset($tempdate) && $tempDate < $date) continue;

            $date = $tempDate;
            $newestFile = $file;
        }
        return $newestFile;
    }
    //Just a helper to combining paths. Improves readibility.
    static function combinePath(...$filenames){
        return join(DIRECTORY_SEPARATOR,$filenames);
    }
}
