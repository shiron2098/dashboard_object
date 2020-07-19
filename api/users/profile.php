<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../authentication/protectedaut.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


class profile extends protectedaut
{
    public $pdo;
    public function AUT()
    {
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'];
        $this->selectkey($authHeader);
        if(!empty($authHeader)) {
            if ($_SESSION['AUT'] === true) {
                $json_str = file_get_contents('php://input');
                $json_obj = json_decode($json_str);
                $this->DbConnectAuthencation();
                if(!empty($json_obj)) {
                    $this->start($json_obj);
                }else{
                    $this->selectdate();
                }
            } else {
                log::logInsert('access_token time*s up',log_file_users,ERROR);
                http_response_code(403);
            }
        }else{
            log::logInsert('not found HTTP_AUTHORIZATION server',log_file_users,ERROR);
            http_response_code(401);
        }
    }
    public function start($post)
    {
        if (isset($post->email) && !EMPTY($post->email)&& isset($post->newPassword)&&!empty($post->newPassword)&&!empty($post->currentPassword) && isset($post->currentPassword)) {
            if (filter_var($post->email, FILTER_VALIDATE_EMAIL)) {
                if ($text = $this->validate_password($post->newPassword) === true) {
                    $this->updateusers($post);
                } else {
                    log::logInsert('not correct new password',log_file_users,ALERT);
                    http_response_code(422);
                    echo 'not correct new password';
                }
            } else {
                log::logInsert('not correct email',log_file_users,ALERT);
                http_response_code(422);
                echo 'not correct email';
            }
        }else if(isset($post->email) && !EMPTY($post->email)&&!empty($post->currentPassword) && isset($post->currentPassword)){
            if (filter_var($post->email, FILTER_VALIDATE_EMAIL)) {
                $this->updateusersinf($post);
            }else {
                log::logInsert('not correct email',log_file_users,ALERT);
                http_response_code(422);
                echo 'not correct email';
            }
        }


    }
    private function updateusers($post){
        $password_hash = $this->selectPasswordHash();
        $password = password_verify($post->currentPassword, $password_hash);
        if($password === true) {
            $data=$this->selectupdate();
            if(empty($post->firstName)){
                $post->firstName = $data->firstName;
            }
            if(empty($post->lastName)){
                $post->lastName = $data->lastName;
            }
            if(empty($post->workPhone)){
                $post->workPhone = $data->workPhone;
            }
            if(empty($post->mobilePhone)){
                $post->mobilePhone = $data->mobilePhone;
            }
            try {
                $hashed_password = password_hash($post->newPassword, PASSWORD_DEFAULT);
                $data = [
                    'email' => $post->email,
                    'firstName' => $post->firstName,
                    'lastName' => $post->lastName,
                    'workPhone' => $post->workPhone,
                    'mobilePhone' => $post->mobilePhone,
                    'password_hash' => $hashed_password,
                    'requireToChangePassword' => 0,
                    'userGlobalKey' => $_SESSION['USERID'],
                ];
                $sql = "update users set email= :email, firstName= :firstName,lastName=:lastName,workPhone= :workPhone,mobilePhone=:mobilePhone,password_hash=:password_hash,requireToChangePassword=:requireToChangePassword,updateDatetimeUtc=now() where userGlobalKey= :userGlobalKey";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute($data);
                log::logInsert('Data update successfully #' . $_SESSION['USERID'],log_file_users,INFO);
                echo json_encode('Data update successfully');
            }catch (PDOException $e){
                http_response_code(422);
                log::logInsert('update to save data #' . $_SESSION['USERID'],log_file_users,ERROR);
                echo 'error update to save data';
            }
        }else{
            http_response_code(422);
            log::logInsert('current password is wrong #' . $_SESSION['USERID'],log_file_users,ALERT);
            echo 'current password is wrong';
        }
    }
    private function updateusersinf($post){
        $data=$this->selectupdate();
        $password_hash = $this->selectPasswordHash();
        $password = password_verify($post->currentPassword, $password_hash);
        if($password === true) {
            if (empty($post->firstName)) {
                $post->firstName = $data->firstName;
            }
            if (empty($post->lastName)) {
                $post->lastName = $data->lastName;
            }
            if (empty($post->workPhone)) {
                $post->workPhone = $data->workPhone;
            }
            if (empty($post->mobilePhone)) {
                $post->mobilePhone = $data->mobilePhone;
            }
            try {
                $data = [
                    'email' => $post->email,
                    'firstName' => $post->firstName,
                    'lastName' => $post->lastName,
                    'workPhone' => $post->workPhone,
                    'mobilePhone' => $post->mobilePhone,
                    'userGlobalKey' => $_SESSION['USERID'],
                ];
                $sql = "update users set email= :email, firstName= :firstName,lastName=:lastName,workPhone= :workPhone,mobilePhone=:mobilePhone,updateDatetimeUtc=now() where userGlobalKey= :userGlobalKey";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute($data);
                log::logInsert('Data update successfully #' .$_SESSION['USERID'],log_file_users,INFO);
                echo json_encode('Data update successfully');
            } catch (PDOException $e) {
                log::logInsert('error update to save data #' . $_SESSION['USERID'],log_file_users,ERROR);
                http_response_code(422);
                echo 'error update to save data';
            }
        }else{
            log::logInsert('current password is wrong #' . $_SESSION['USERID'],log_file_users,ALERT);
            http_response_code(422);
            echo 'current password is wrong';
        }
    }
    function validate_password($field)
    {
        if ($field == "")
            return "Не введен пароль";
        else if (strlen($field) < 8 OR strlen($field) > 32)
            return "В пароле должно быть не менее 6 символов и не более 30";
        else if (!preg_match("/^[a-zA-Z0-9\!@#\/\$%\^&\*\(\)\[\]\{\}\-=_\+\.,'\"<>\?]+$/", $field))
            return "В пароле недопустимые символы";
        return true;
    }
    private function selectdate(){
        $stmt = $this->pdo->prepare("SELECT email as email,firstName as firstName,lastName as lastName,workPhone as workPhone,mobilePhone as mobilePhone FROM users u
                                      where userGlobalKey=?"
        );
        $stmt->execute(array($_SESSION['USERID']));
        $profile = $stmt->fetchAll(PDO::FETCH_OBJ);
        foreach($profile as $date);
        echo json_encode($date);
    }
    private function selectupdate(){
        $stmt = $this->pdo->prepare("SELECT email as email,firstName as firstName,lastName as lastName,workPhone as workPhone,mobilePhone as mobilePhone FROM users u
                                      where userGlobalKey=?"
        );
        $stmt->execute(array($_SESSION['USERID']));
        $profile = $stmt->fetchAll(PDO::FETCH_OBJ);
        foreach($profile as $date);
        return $date;
    }
    private function selectPasswordHash()
    {
            $stmt = $this->pdo->prepare("SELECT password_hash FROM users u
                                      where userGlobalKey=?"
            );
            $stmt->execute(array($_SESSION['USERID']));
            $password_hash = $stmt->fetchColumn();
            return $password_hash;

    }

}
$start = new profile();
$start->AUT();