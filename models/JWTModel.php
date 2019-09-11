<?php
    class JWTModel {
        public $header;
        public $payload;
        public $signature;
        
        public function __construct() {
            $this->header = new JWTHeader();
            $this->payload = new JWTPayload();
        }
    }

    class JWTHeader {
        public $alg;
        public $kid;
        public $typ;
        public $x5t;
    }

    class JWTPayload {
        public $nbf;
        public $exp;
        public $iss;
        public $aud;
        public $iat;
        public $at_hash;
        public $sid;
        public $sub;
        public $auth_time;
        public $idp;
        public $amr = array();
    }
?>