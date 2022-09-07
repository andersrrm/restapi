<?php

require_once "vendor/autoload.php";
use Firebase\JWT\JWT;

class Connection{
    static public function infoDatabase(){
        $DB = array(
            "database" => db_name,
            "user" => db_user,
            "password" => db_password,
        );
        return $DB;
    }

    static public function connect(){
        try{
            $link = new PDO(
                "mysql:host=localhost;dbname=".Connection::infoDatabase()['database'],
                Connection::infoDatabase()['user'],
                Connection::infoDatabase()['password']
            );
            $link->exec("set names utf8");
        }catch(PDOException $e){
            die("Error: ". $e->getMessage());   
        }
        return $link;
    }
    static public function jwt($id,$email){
        $token = array (
            'iat' => time(),
            'exp' => time() + token_exp,
            'data' => [
                "id" => $id,
                "email" => $email,
            ]
        );

        $jwt = JWT::encode($token,"asdasdasd",token_hash);
        return [
            "token" => $token,
            "jwt" => $jwt,
        ];
    }
}