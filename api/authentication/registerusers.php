<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/abstractFunctionAut.php';

class registerusers extends abstractFunctionAut
{


    public function __construct()
    {
        $this->pdo = MYSQLConnect::ConnectToDB(host,user,password,database_t2s_users);
    }

    public function RegisterData($json_obj){
        if(isset($json_obj->email)&&!empty($json_obj->email)&&isset($json_obj->password)&&!empty($json_obj->password)){
          $hashed_password = password_hash( $json_obj->password, PASSWORD_DEFAULT );
            $this->pdo=$this->DbConnectAuthencation();
            try{
                $data = [
                    'email' => $json_obj->email,
                    'password_hash' => $hashed_password,
                    'userGlobalKey' => rand(1,10000),
                ];
                $sql = "INSERT INTO users (email,password_hash,userGlobalKey) VALUES (:email,:password_hash,:userGlobalKey)";
                $stmt= $this->pdo->prepare($sql);
                $stmt->execute($data);
                if( !$stmt ){
                    throw new Exception('ERROR create new user to dashboard');
                }
            } catch (Exception $e) {
                log::logInsert($e->getMessage(),log_file_authentication,ERROR);
                echo $e->getMessage();
            }
        }
    }
}
$json_str = file_get_contents('php://input');
$json_obj = json_decode($json_str);
$a= new registerusers();
$array = (object) ['email'=> 'admin','password' => 'admin'];
$a->RegisterData($array);
