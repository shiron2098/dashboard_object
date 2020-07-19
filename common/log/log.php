<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../config/log_params.php';
require_once __DIR__ . '/logbasic.php';

class log
{
    private static $number = 0;
    public static function logInsert($text,$logname,$level)
    {
        if (!file_exists(path_folder)) {
            mkdir(path_folder, 0777, true);
            chmod(path_folder,0777);

        }
        logbasic::logtext($logname,$text,$level,log::$number);
        if(!empty($logresponse)){
            log::$number = $logresponse;
        }
    }
}