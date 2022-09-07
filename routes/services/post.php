<?php

require_once 'controllers/postController.php';

if (isset($_POST)){
    
    $columns = array();
    foreach (array_keys($_POST) as $key => $value) {
        array_push($columns,$value);
    }
    
    $response = new PostController();
    if (isset($_GET['create']) && $_GET['create'] == true){
        $response -> postCreate($_POST);    
    
    }else if (isset($_GET['readAll']) && $_GET['readAll'] == true){
        $response -> postReadAll($_POST);

    }else if (isset($_GET['updateByEmail']) && $_GET['updateByEmail'] == true){
        $response -> postUpdateByEmail($_POST);
    
    }else if (isset($_GET['deleteByEmail']) && $_GET['deleteByEmail'] == true){
        $response -> postDeleteByEmail($_POST);

    }else if (isset($_GET['login']) && $_GET['login'] == true){
        $response -> postLogin($_POST);    

    }else if (isset($_GET['getArticles']) && $_GET['getArticles'] == true){
        $query = (isset($_POST['query']) ? $_POST['query']:false);
        $limit = (isset($_POST['limit']) ? $_POST['limit']:false);
        $searchby = (isset($_POST['searchby']) ? $_POST['searchby']:false);
        $response -> postGetArticles($query,$limit,$searchby);    

    }else{
        $response -> postData($_POST);
    }
    
    
}