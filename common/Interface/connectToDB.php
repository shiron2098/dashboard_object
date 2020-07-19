<?php
namespace app;

interface connectToDB
{

    public static function ConnectToDB($host,$username,$password,$database);
}