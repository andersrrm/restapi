<?php

require_once "libraries/helpers.php";
require_once "libraries/language.php";
require_once "models/connection.php";
require_once "models/articles.php";
require_once "models/users.php";

class PostController{

    static public function postLogin($data){
        $result = [
			'status' => false,
			'errors' => [],
			'message' => '',
            'response' => '',
		];
        if (isset($data['email']) && isset($data['password'])) {
            $user = Users::getOne($data['email']);
            if (!empty($user)){
                $user = $user[0];
                $crypted = crypt($data['password'],'$2a$07$azybxcags23425sdg23sdfhsd$');
                if ($user['password'] == $crypted){

                    $jwt = Connection::jwt($user['id'],$user['email']);
                    $updated = Users::updateToken($user['id'],$jwt['jwt'],$jwt['token']['exp']);
                    if ($updated){
                        $user['token'] = $jwt['jwt'];
                        $user['token_expiration'] = $jwt['token']['exp'];
                        unset($user['password']);
                        $result['response'] = $user;
                        $result['status'] = true;    
                        $result['message'] = 'Succesfully';
                    }
                }else{
                    $result['errors'][] = passwordIncorrect;
                    $result['message'] = passwordIncorrect;    
                }
            }else{
                $result['errors'][] = userNotFound;
                $result['message'] = userNotFound;
            }
        }else{
            $result['errors'][] = missingParameter;
            $result['message'] = missingParameter;
        }

        $status = ($result['status'] == true) ? 200 : 400;

        $return = new PostController();
        $return -> response($result,$status);
    }

    static public function postCreate($data){

        $result = [
			'status' => false,
			'errors' => [],
			'message' => '',
            'response' => '',
		];

		
        if (isset($data['password']) && isset($data['name']) && isset($data['password'])) {
            $password_validate_msg = '';
            $password_validate = password_validate($data['password'], $password_validate_msg);
            if (!$password_validate) {
                $result['errors'][] = $password_validate_msg;
                $result['message'] = $password_validate_msg;
            }
            if (!isset($data['email']) or empty($data['email']) or !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $result['errors'][] = emailNotValid;
                $result['message'] = emailNotValid;
            }
            if (!isset($data['name']) or empty($data['name']) or strlen($data['name']) > 255) {
                $result['errors'][] = nameNotValid;
                $result['message'] = nameNotValid;
            }
            $isRegistered = Users::existMail($data['email']);
            if ($isRegistered){
               $result['errors'][] = alreadyRegistered;
                $result['message'] = alreadyRegistered;
            }
        }else{
            $result['errors'][] = missingParameter;
            $result['message'] = missingParameter;
        }

        if(count($result['errors'])==0){
            $data['password'] = crypt($data['password'],'$2a$07$azybxcags23425sdg23sdfhsd$');
            $result['response'] = Users::save($data);
            $result['message'] = 'Succesfully';
            $result['status'] = true;
        }

        $status = ($result['status'] == true) ? 200 : 400;

        $return = new PostController();
        $return -> response($result,$status);
    }

    
    static public function postReadAll($data){

        $result = [
			'status' => false,
			'errors' => [],
			'message' => '',
            'response' => '',
		];

        if(count($result['errors'])==0){
            $result['response'] = Users::getAll();
            $result['message'] = 'Succesfully';
            $result['status'] = true;
        }

        $status = ($result['status'] == true) ? 200 : 400;

        $return = new PostController();
        $return -> response($result,$status);
    }

    static public function postUpdateByEmail($data){
        $result = [
			'status' => false,
			'errors' => [],
			'message' => '',
            'response' => '',
		];
        if (!isset($data['email']) or empty($data['email']) or !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $result['errors'][] = emailNotValid;
            $result['message'] = emailNotValid;
        }
        if (isset($data['name']) && strlen($data['name']) > 255) {
            $result['errors'][] = nameNotValid;
            $result['message'] = nameNotValid;
        }
        $password_validate_msg = '';
        if (isset($data['password']) && !password_validate($data['password'], $password_validate_msg)) {
            $result['errors'][] = $password_validate_msg;
			$result['message'] = $password_validate_msg;
        }

        if(count($result['errors'])==0){
            if (Users::existMail($data['email'])){
                $data['password'] = (isset($data['password']) ? crypt($data['password'],'$2a$07$azybxcags23425sdg23sdfhsd$'):false);
                $data['email'] = (isset($data['email']) ? $data['email']:false);
                $data['name'] = (isset($data['name']) ? $data['name']:false);
                $result['response'] = Users::updateByEmail($data['email'],$data['name'],$data['password']);
                $result['message'] = 'Succesfully';
                $result['status'] = true;
            }else{
                $result['errors'][] = emailNotExist;
                $result['message'] = emailNotExist;
            }
        }

        $status = ($result['status'] == true) ? 200 : 400;

        $return = new PostController();
        $return -> response($result,$status);
    }
    
    static public function postDeleteByEmail($data){
        $result = [
			'status' => false,
			'errors' => [],
			'message' => '',
            'response' => '',
		];
        if (!isset($data['email']) or empty($data['email']) or !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $result['errors'][] = emailNotValid;
            $result['message'] = emailNotValid;
        }

        if(count($result['errors'])==0){
            if (Users::existMail($data['email'])){
                $result['response'] = Users::deleteByEmail($data['email']);
                $result['message'] = 'Succesfully';
                $result['status'] = true;
            }else{
                $result['errors'][] = emailNotExist;
                $result['message'] = emailNotExist;
            }
        }

        $status = ($result['status'] == true) ? 200 : 400;

        $return = new PostController();
        $return -> response($result,$status);
    }

    static public function postGetArticles($query, $limit, $searchby){
        $result = [
			'status' => false,
			'errors' => [],
			'message' => '',
            'response' => '',
		];
        
        if ($query){
            $articles = [];
            $redis = new Predis\Client();
            $cachedEntry = $redis->get('articles');
            if ($cachedEntry){
                $articles = json_decode($cachedEntry);
                $result['message'] = 'cache';
            }else{
                $articles = Articles::getMany($query,$limit,$searchby);
                $redis->set('articles', json_encode($articles));
                $redis->expire('articles', redis_exp);
                $result['message'] = 'fetch';
            }
            $result['response'] = $articles;
            $result['status'] = true;
        }else{
            $result['errors'] = noQuery;
        }

        $status = ($result['status'] == true) ? 200 : 400;

        $return = new PostController();
        $return -> response($result,$status);
    }

    public function response($result,$status){
        $json = array(
            'status' => $status,
            'message' => $result['message'],
            'result' => $result['response'],
            'errors' => $result['errors'],
        );
        echo json_encode($json, http_response_code($json['status']));
    }
}