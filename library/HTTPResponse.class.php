<?php
namespace library;
 
class HTTPResponse {

    public function redirect($url, $interne = true) {
        header("Location: ".($interne ? WWW_ROOT : '').$url);
        exit;
    }
}