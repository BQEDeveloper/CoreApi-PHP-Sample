<?php
    require_once(realpath(__DIR__ . '/..').'/models/JWTModel.php');
    require_once(realpath(__DIR__ . '/..').'/models/JWKSModel.php');
    require_once(realpath(__DIR__ . '/..').'/shared/APIHelper.php'); 
    require_once(realpath(__DIR__ . '/..').'/shared/GeneralMethods.php');
    require_once(realpath(__DIR__ . '/..').'/packages/phpseclib/Crypt/RSA.php');
    require_once(realpath(__DIR__ . '/..').'/packages/phpseclib/Crypt/Math/BigInteger.php');

    class JWTManager {

      public $config;      
      public $httpResponse;
      public $httpHeader;
      public $id_token;
      public $jwt;
      public $jwks;
      public $headers;

      function __Construct($config, $id_token) {
         $this->config = $config;
         $this->id_token = $id_token;
          
         $this->httpResponse = new HttpResponseModel(); 
         $this->httpHeader = new HttpHeaderModel();
         $this->jwt = new JWTModel();
         $this->jwks = new JWKSModel();

         $this->httpResponse = APIHelper::Get($this->config->CoreIdentityBaseUrl .'/.well-known/openid-configuration/jwks',$this->httpHeader);
         $this->jwks =  json_decode($this->httpResponse->body)->keys[0];
         
      }

      function DecodeJWT() {
         try {
         
            list($header,$payload,$signature) = explode('.',$this->id_token);
            $this->jwt->header = json_decode(GeneralMethods::Base64UrlDecode($header));
            $this->jwt->payload = json_decode(GeneralMethods::Base64UrlDecode($payload));
            $this->jwt->signature = GeneralMethods::Base64UrlDecode($signature);

            return $this->jwt;
         }
         catch(Exception $ex) {
            throw $ex;
         }
      }

      function ValidateJWT($jwt) {
         try {  
            $this->jwt = $jwt;         
            return $this->ValidateJWTHeader() && $this->ValidateJWTPayload() && $this->VerifyJWTSingature();
         }
         catch(Exception $ex) {
            throw $ex;
         }
      }

      private function ValidateJWTHeader() {
         try {            

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
            $rsa = new Crypt_RSA();
            $rsa->loadKey([
               'e' => new Math_BigInteger(GeneralMethods::Base64UrlDecode($this->jwks->e), 256),
               'n' => new Math_BigInteger(GeneralMethods::Base64UrlDecode($this->jwks->n), 256)
            ]);

            $publickey = $rsa->getPublicKey();

            list($header,$payload,$signature) = explode('.',$this->id_token);

            $message = $header.".".$payload;

            $signature = GeneralMethods::Base64UrlDecode($signature);

            return $rsa->VerifySignature($message, $signature, $publickey, $this->jwt->header->alg);

         }
         catch(Exception $ex) {
            throw $ex;
         }
      }
   }
      
    

    

    
?>
