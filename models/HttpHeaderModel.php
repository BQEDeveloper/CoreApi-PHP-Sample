<?php
    class HttpHeaderModel {
        public $authorization;
        public $contentType;
        public $userAgent;

        public function __construct(){
            $this->userAgent = "Mozilla/5.0";
            $this->contentType = "application/json; charset=UTF-8";
        }
    }
?>