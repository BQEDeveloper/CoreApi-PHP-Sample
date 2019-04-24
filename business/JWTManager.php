<?php
    require_once(realpath(__DIR__ . '/..').'/models/JWTModel.php');
    require_once(realpath(__DIR__ . '/..').'/models/JWKSModel.php');
    require_once(realpath(__DIR__ . '/..').'/shared/APIHelper.php'); 
    require_once(realpath(__DIR__ . '/..').'/shared/GeneralMethods.php');

    class JWTManager {

      public $config;      
      public $httpResponse;
      public $jwt;
      public $jwks;
      public $headers;

      function __Construct($config) {
         $this->config = $config;
          
         $this->httpResponse = new HttpResponseModel(); 
         $this->jwt = new JWTModel();
         $this->jwks = new JWKSModel();

         $this->headers = array( 
            "accept: application/json",       
            "content-type: application/json",
         );
      }

      function DecodeJWT($id_token) {
         try {
         
            list($header,$payload,$signature) = explode('.',$id_token);
            $this->jwt->header = json_decode(base64_decode($header));
            $this->jwt->payload = json_decode(base64_decode($payload));
            $this->jwt->signature = json_decode(base64_decode($signature));

            return $this->jwt;
         }
         catch(Exception $ex) {
            throw $ex;
         }
      }

      function ValidateJWT($jwt) {
         try {  
            $this->jwt = $jwt;         
            return $this->ValidateJWTHeader() && $this->ValidateJWTPayload() && $this->VerifyJWTSingature() ? true : false;
         }
         catch(Exception $ex) {
            throw $ex;
         }
      }

      private function ValidateJWTHeader() {
         try {
            
            $this->httpResponse = APIHelper::Get($this->config->CoreIdentityBaseUrl .'/.well-known/openid-configuration/jwks',$this->headers);
            $this->jwks =  json_decode($this->httpResponse->body)->keys[0];

            //verify whether algorithm mentioned in Id Token (JWT) matches to the one in JWKS
            if($this->jwt->header->alg != $this->jwks->alg)
               throw new Exception("JWT algorithm doesn't match to the one mentioned in the Core API JWKS");
            //verify whether kid mentioned in Id Token (JWT) matches to the one in JWKS
            if($this->jwt->header->kid != $this->jwks->kid)
               throw new Exception("JWT kid doesn't match to the one mentioned in the Core API JWKS");
            
            return true;
         }
         catch(Exception $ex) {
            throw $ex;
         }
      }

      private function ValidateJWTPayload() {
         try {
            
            //verify issuer (iss) mentioned in Id Token (JWT) matches to the one in config.ini
            if($this->jwt->payload->iss != $this->config->CoreIdentityBaseUrl)
               throw new Exception("JWT issuer (iss) doesn't match to the one mentioned in the config.ini");
            //verify audience (aud) mentioned in Id Token (JWT) matches to the one in config.ini
            if($this->jwt->payload->aud != $this->config->ClientID)
               throw new Exception("JWT audience (aud) doesn't match to the one mentioned in the config.ini");
            //verify expiry time (exp) mentioned in Id Token (JWT) has not passed
            if($this->jwt->payload->exp < time())
               throw new Exception("JWT expiry time (exp) has already passed. Verify if the PHP server timezone (current timestamp) is correct or the JWT is already expired.");
            return true;
         }
         catch(Exception $ex) {
            throw $ex;
         }
      }

      private function VerifyJWTSingature() {
         try {
            


            return true;
         }
         catch(Exception $ex) {
            throw $ex;
         }
      }
   }
      
    

    

    
?>