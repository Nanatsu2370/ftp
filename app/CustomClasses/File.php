<?php

namespace App\CustomClasses;


//Handles Filename's string manipulations.
class File
{
    /**
     * Extracts the date of the filename.
     * @param string $filename Full file name which contains date info.
     * @param string $dateFormat filename's date's format.
     * @return Date
    */
    protected static function extractDate($filename)
    {
        $dateFormat = env("FILE_DATE_FORMAT");
        preg_match("/\d+/", $filename, $m);
        if (!$m) return NULL;

        $date =  \DateTime::createFromFormat($dateFormat, $m[0]);
        return $date;
    }

    /**
     * Gets the most recent file's path from the FTP.
     * @param array $files Candidate files.
     * @param string $base Base path of the files for auto-combining.
     * @return string
     * */
    static function getRecentURL($files,$base="")
    {
        $recentFile = File::getNewest($files);
        return File::combinePath($base, $recentFile);
    }

    /**
     * Selects the most recent file
     * @param array $files Candidate files.
     * @return string Most recent file's name
     */
    static function getNewest($files)
    {
        //initial date, 1970 Jan. Minimum date for increasing.
        $date = new \DateTime('@0');

        foreach ($files as $file) {
            $tempDate = self::extractDate($file);
            if (!isset($tempdate) && $tempDate < $date) continue;

            $date = $tempDate;
            $newestFile = $file;
        }
        return $newestFile;
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
