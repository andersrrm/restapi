<?php

require_once "connection.php";

class Users{
    static $table = 'users';

    /**
     * Author: anders.rojas@pucp.pe
     * Descr: Get All user rows except of password,token,token_expiration,status.
     * @return array Users fetched.
	 */    
    public function getAll(){
        $sql = "SELECT * from ".self::$table;
        $conn = Connection::connect();
        $stmt = $conn->prepare($sql);
        if ($stmt->execute()){
            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $response = [];
            foreach ($result as $row){
                $re = [];
                foreach ($row as $key => $value) {
                    $re[$key] = $value;
                }
                unset($re['password']);
                unset($re['token']);
                unset($re['token_expiration']);
                unset($re['status']);
                $response[] = $re;
            }
            return $response;
        }else{
            return $conn->errorInfo();
        }
    }

    /**
     * Author: anders.rojas@pucp.pe
     * Descr: Get One user row.
     * @param string email
     * @return array User fetched.
	 */    
    public function getOne($email){
        $sql = "SELECT * from ".self::$table." where email = '".$email."' limit 1";
        $conn = Connection::connect();
        $stmt = $conn->prepare($sql);
        if ($stmt->execute()){
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }else{
            return $conn->errorInfo();
        }
        
    }
    /**
     * Author: anders.rojas@pucp.pe
     * Descr: Get if emails exists
     * @param string email
     * @return bool if user exists
	 */    
    public function existMail($email){
        $sql = "SELECT * from ".self::$table." where email = '".$email."' limit 1";
        $conn = Connection::connect();
        $stmt = $conn->prepare($sql);
        if ($stmt->execute()){
            return ($stmt->rowCount()==0) ? false : true;
        }else{
            return $conn->errorInfo();
        }
        
    }

    /**
     * Author: anders.rojas@pucp.pe
     * Descr: Update one user based on email
     * @param string email
     * @param string name
     * @param string password
     * @return bool if email updated
	 */    
    public function updateByEmail($email,$name,$password){
        $req = '';
        if ($name) $req .= " name = '".$name."',";
        if ($password) $req .= " password = '".$password."',";
        $req = rtrim($req, ",");

        $id = Users::getOne($email)[0]['id'];
        $sql = "UPDATE ". self::$table ." SET ".$req." where id = '".$id."'";
        
        $conn = Connection::connect();
        $stmt = $conn->prepare($sql);
        if ($stmt->execute()){
            return ($stmt->rowCount()==0) ? false : true;
        }else{
            return $conn->errorInfo();
        }
    }

    /**
     * Author: anders.rojas@pucp.pe
     * Descr: Delete user based on email
     * @param string email
     * @return bool if email has been deleted
	 */    
    public function deleteByEmail($email){
        $sql = "DELETE from ". self::$table ." where email = '".$email."'";
        
        $conn = Connection::connect();
        $stmt = $conn->prepare($sql);
        if ($stmt->execute()){
            return ($stmt->rowCount()==0) ? false : true;
        }else{
            return $conn->errorInfo();
        }
    }

    /**
     * Author: anders.rojas@pucp.pe
     * Descr: Update user with new token
     * @param string email
     * @param string token_name
     * @param int token_expiration
     * @return bool If token updated 
	 */    
    public function updateToken($id, $token_name, $token_expiration){
        $updated_at = time();
        $sql = "UPDATE " . self::$table . " 
                SET token = '".$token_name."', token_expiration = '".$token_expiration."' 
                , updated_at = '".$updated_at."' WHERE id = ".$id."";
        $conn = Connection::connect();
        $stmt = $conn->prepare($sql);
        if ($stmt->execute()){
            return true;
        }else{
            return $conn->errorInfo();
        }
        
    }

    /**
     * Author: anders.rojas@pucp.pe
     * Descr: Store user information
     * @param array user information
     * @return array [id stored, 'success']
	 */    
    static public function save($data){
        $name = $data['name'];
        $email = $data['email'];
        $password = $data['password'];
        $updated_at = time();
        $created_at = time();
        
        $sql = "INSERT INTO ". self::$table ." 
                (name,email,password,created_at,updated_at) 
                VALUES ('".$name."','".$email."','".$password."','".$created_at."','".$updated_at."')";
        $conn = Connection::connect();
        $stmt = $conn->prepare($sql);

        if ($stmt->execute()){
            $response = array(
                'last_id' => $conn->lastInsertId(),
                'comment' => 'succesfully',
            );
            return $response;
        }else{
            return $conn->errorInfo();
        }
    }
    
}