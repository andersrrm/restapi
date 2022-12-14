<?php

class Articles{

    /**
     * Author: anders.rojas@pucp.pe
     * Descr: Gets all the articles in English from GNEWS API.
	 * @param string $query, to search
     * @param int $max, limit result quantity
	 * @param string $searchby, if matches with title,description,content
     * @return array Articles fetched.
	 */    

    static public function getMany($query,$max,$searchby){
        $url = Articles::form_url($query,$max,$searchby);
        return Articles::fetch_url($url);
    }

    function form_url($query,$max,$searchby){
        $base_url = 'https://gnews.io/api/v4/search?';
        if (isset($query)){
            $query = str_replace(",", "%20AND%20",trim($query));
            $query = str_replace(" ", "%20AND%20",trim($query));
            $base_url .=  'q='.$query.'&';
        }
        
        if (isset($max) || 100){
            if ($max>0 && $max<100){
                $base_url .=  'max='.$max.'&';
            }
        }
        
        if (isset($searchby) || 'title,description,content'){
            $substring = '';
            if (strpos($searchby, 'title') !== false) {
                $substring .= 'title,';
            }
            if (strpos($searchby, 'description') !== false) {
                $substring .= 'description,';
            }
            if (strpos($searchby, 'content') !== false) {
                $substring .= 'content';
            }
            
            $substring = rtrim($substring, ",");

            $base_url .=  'in='.$substring.'&';
        }
        
        $base_url .= 'lang=en&';
        $base_url .= 'sortby=publishedAt&';
        $base_url .= 'token='.gnewsapi;
        return $base_url;
    }

    function fetch_url($url){
        $response = file_get_contents($url);
        $response = json_decode($response);
        if ($response) $response = $response->articles;
        return $response;
    }
}