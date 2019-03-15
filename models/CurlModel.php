<?php
    class CurlModel {
        public $ch;

        function __construct() {
            $this->ch = curl_init();
            // CURL OPTIONS:            
            curl_setopt($this->ch , CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($this->ch , CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.17 (KHTML, like Gecko) Chrome/24.0.1312.52 Safari/537.17');
            curl_setopt($this->ch , CURLOPT_AUTOREFERER, true); 
            curl_setopt($this->ch , CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($this->ch , CURLOPT_VERBOSE, 1);
            curl_setopt($this->ch, CURLOPT_HEADER, 1);
        }

        function __destruct() {
            curl_close($this->ch);
        }

    }
    
?>