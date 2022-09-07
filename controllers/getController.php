<?php
require_once "models/articles.php";

class GetController{

    static function getNArticles($query, $limit, $searchby){
        $result = [
			'status' => false,
			'errors' => [],
			'message' => '',
            'response' => '',
		];
        
        $data = Articles::getMany($query,$limit,$searchby);

        $result['response'] = $data;
        $result['status'] = true;


        $status = ($result['status'] == true) ? 200 : 400;

        $return = new GetController();
        $return -> response($result,$status);
    }
    
    public function response($result,$status){
        $json = array(
            'status' => $status,
            'result' => $result['response'],
            'errors' => $result['errors'],
        );
        echo json_encode($json, http_response_code($json['status']));
    }
}