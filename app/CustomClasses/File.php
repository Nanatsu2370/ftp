<?php

namespace App\CustomClasses;


//Handles Filename's string manipulations.
class File
{
    /**
     * Extracts the date of the filename. Gets the regex format from .env
     * @param string $filename Full file name which contains date info.
     * @return Date
    */
    protected static function extractDate($filename)
    {
        $dateFormat = env("FILE_DATE_FORMAT");
        preg_match("/\d+/", $filename, $m);
        if (!$m) return NULL;

        return \DateTime::createFromFormat($dateFormat, $m[0]);
    }

    /**
     * Gets the most recent file's path from the FTP.
     * @param array $files Candidate files.
     * @param string $base Base path of the files for auto-combining.
     * @return string
     * */
    static function getRecentURL($files,$base="")
    {
        //initial date, 1970 Jan. Minimum date for increasing.
        $date = new \DateTime('@0');

        foreach ($files as $file)
        {
            $tempDate = self::extractDate($file);
            if (!isset($tempdate) && $tempDate < $date) continue;

            $date = $tempDate;
            $recentFile = $file;
        }
        return File::combinePath($base, $recentFile);
    }

    /**
     * Just a helper to combining paths for imroving readibility.
     * @param array $filenames file names to combine each other.
     * @return string full path.
     */
    static function combinePath(...$filenames){
        return join(DIRECTORY_SEPARATOR,$filenames);
    }
}
