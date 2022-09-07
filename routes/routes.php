<?php 

$routes = explode('/',$_SERVER['REQUEST_URI']);
$routes = array_filter($routes);

if (count($routes)==0) {
    $json = array(
        'status' => 404,
        'result' => 'Not found'
    );
    
    echo json_encode($json, http_response_code($json['status']));
}

if (count($routes)>0 && isset($_SERVER['REQUEST_METHOD'])) {
    
    if ($_SERVER['REQUEST_METHOD']=='GET'){
        include "services/get.php";
    }

    if ($_SERVER['REQUEST_METHOD']=='POST'){
        include "services/post.php";
    }
}

return;