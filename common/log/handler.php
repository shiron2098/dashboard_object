<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../config/log_params.php';
require_once __DIR__ . '/logbasic.php';

set_error_handler('err_handler');
function err_handler($errno, $errmsg, $filename, $linenum,$level) {
    if(!empty($level)){
        $levelCODE = $level;
    }else{
        $levelCODE = WARNING;
    }
    $filename  =str_replace($_SERVER['DOCUMENT_ROOT'],'',$filename);
    $err  = "$errmsg = $filename = $linenum\r\n";
    logbasic::logtext(path_folder . 'errors_free',$err,$levelCODE,0);
}
function myShutdownHandler() {
    if (@is_array($e = @error_get_last())) {
        $code = isset($e['type']) ? $e['type'] : 0;
        $msg = isset($e['message']) ? $e['message'] : '';
        $file = isset($e['file']) ? $e['file'] : '';
        $line = isset($e['line']) ? $e['line'] : '';
        if($code>0)err_handler($code,$msg,$file,$line,CRITICAL);

    }
}
register_shutdown_function('myShutdownHandler');



