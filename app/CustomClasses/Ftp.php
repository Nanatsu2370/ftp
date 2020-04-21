<?php

namespace App\CustomClasses;

//Handles FTP operations.
class FTP
{
    //Creates an URL for filepath, with .env specified values.
    static function buildFTPUrl($filepath=NULL)
    {
        $url = sprintf("ftp://%s:%s", rawurlencode(env("FTP_USERNAME")), rawurlencode(env("FTP_PASSWORD")));
        $url .= sprintf("@%s", env("FTP_HOST"));

        if($filepath)
            $url .= "/".$filepath;

        return $url;
    }
    //Returns files from ftpfilepath param. Scans the dir and excludes '..' files.
    static function getAllFiles($ftp_filePath)
    {
        $url = self::buildFTPUrl($ftp_filePath);
        return array_slice(scandir($url), 2);
    }
}
