<?php
require_once __DIR__ . '/../vendor/autoload.php';

class PDORealization
{

    public static function Realization($link,$procedure_name,$data,$name_param)
    {
        $stmt = $link->prepare($procedure_name);
        $stmt->execute($data);
        if (!$stmt) {
            throw new Exception('Query Failed');
        }
        if($name_param === object) {
            $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        }else if($name_param === array_data){
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        if (!empty($result)) {
            return $result;
        }else{
            return null;
        }
    }

}
