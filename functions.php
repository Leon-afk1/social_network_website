<?php

//functions to get embedded youtube video
//source code : https://youthsforum.com/programming/php/get-youtube-embed-code-from-video-url-using-php/
function __getYouTubeEmbeddedURL($url) {
    return "https://www.youtube.com/embed/" . __getYouTubeID($url);
}

function __getYouTubeID($url) {
    $queryString = parse_url($url, PHP_URL_QUERY);
    parse_str($queryString, $params);
    if (isset($params['v']) && strlen($params['v']) > 0) {
        return $params['v'];
    } else {
        return "";
    }
}


?>