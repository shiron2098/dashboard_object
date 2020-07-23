<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../config/log_params.php';
require_once __DIR__ . '/handler.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Formatter\LineFormatter;
use Monolog\Processor\MemoryPeakUsageProcessor;


class logbasic extends RotatingFileHandler
{

    public static function logtext($name, $text,$level,$number)
    {
        while (true) {
            $log = new Logger('security');
            $stream = new RotatingFileHandler($name . $number, maxfiles, $level, true, 0777, false);
            $formatter = new LineFormatter(output, dateFormat);
            $stream->setFormatter($formatter);
            $log->pushHandler($stream);
            $path = $log->getHandlers()['0']->url;
            if (file_exists($path)) {
                $size = filesize($path);
                if ($size <= sizemax) {
                    logbasic::message($log,$text,$level);
                    return $number;
                }else {
                    $number++;
                }
            } else {
              logbasic::message($log,$text,$level);
                return $number;
            }
        }
    }
    private static function message($log,$text,$level){
        switch ($level) {
            case 200:
                $log->addInfo($text);
                break;
            case 250:
                $log->addNotice($text);
                break;
            case 300:
                $log->addWarning($text);
                break;
            case 400:
                $log->addError($text);
                break;
            case 500:
                $log->addCritical($text);
                break;
            case 550:
                $log->alert($text);
                break;
            case 600:
                $log->emergency($text);
                break;
        }
    }
}