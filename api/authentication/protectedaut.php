<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/abstractFunctionAut.php';
require_once __DIR__ . '/../../config/t2s_users.php';
require_once __DIR__ . '/../../common/MYSQLConnect.php';

use \Firebase\JWT\JWT;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


class protectedaut
{

    public function selectkey($auttoken)
    {
        $_SESSION['AUT'] = false;
            $this->checktoken($auttoken);

    }

    private function checktoken($token)
    {

        $arr = explode(" ", $token);
        $jwt = $arr[1];
        try {
            $decoded = JWT::decode($jwt, userglobalkey, array('HS256'));
            if ($decoded->ext > time()) {
                $_SESSION['AUT'] = true;
            } else {
                $_SESSION['AUT'] = false;
            //    log::logInsert('protectaut_autification false acces-token time end #'  . $decoded->id ,log_file_authentication,ERROR);
                http_response_code(403);
            }

        } catch (Exception $e) {
            log::logInsert($e->getMessage() ,log_file_authentication,ERROR);
            http_response_code(401);
            echo json_encode(array(
                "message" => "Access denied.",
                "error" => $e->getMessage()
            ));
        }
    }
}