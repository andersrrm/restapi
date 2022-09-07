<?php

require_once 'controllers/getController.php';

$order = explode("?",$routes[1])[0];
$response = new GetController();

$query = (isset($_GET['query']) ? $_GET['query']:false);
$limit = (isset($_GET['limit']) ? $_GET['limit']:false);
$searchby = (isset($_GET['searchby']) ? $_GET['searchby']:false);


if (isset($order) && $order == 'getArticles'){
    $response -> getNArticles($query,$limit,$searchby);    
}