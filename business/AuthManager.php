<?php
    require_once(realpath(__DIR__ . '/..').'/models/AuthResponseModel.php');
    require_once(realpath(__DIR__ . '/..').'/shared/APIHelper.php'); 
    require_once(realpath(__DIR__ . '/..').'/shared/GeneralMethods.php');

    class AuthManager {

      public $config;
      public $authResponse;
      public $httpResponse;
      public $headers;

      function __Construct() {
         $this->config = GeneralMethods::GetConfig();
          
         $this->authResponse = new AuthResponseModel();
         $this->httpResponse = new HttpResponseModel(); 

         if(GeneralMethods::GetAuthResponse() != null)
            $this->authResponse = GeneralMethods::GetAuthResponse();
 
            $this->headers = array( 
               "accept: application/json",       
               "authorization: Bearer " . $this->authResponse->access_token,
               "content-type: application/json",
            );
      }

      function ConnectToCore() {
         $state = urlencode(GeneralMethods::GenerateRandomString());         
         $_SESSION['state'] = $state;
         header("Location: ".$this->config->CoreIdentityBaseUrl."/connect/authorize?client_id=".$this->config->ClientID."&response_type=code&scope=".$this->config->Scopes."&redirect_uri=".$this->config->RedirectURI."&state=".$state);
      }

      function DisconnectFromCore() {
         try{
            $this->headers = array(        
               "content-type: application/x-www-form-urlencoded",
            );
            
            $dataArray = array(
               "token" => $this->authResponse->access_token,
               "client_id" => $this->config->ClientID,
               "client_secret" => $this->config->Secret
            );

            $data = http_build_query($dataArray);

            $this->httpResponse = APIHelper::Post($this->config->CoreIdentityBaseUrl .'/connect/revocation',$data,$this->headers);
            if($this->httpResponse->header_code == 200){
               GeneralMethods::SaveAuthResponse('');
               header("Location: ../index.php");
            }
         }
         catch(Exception $ex){
            throw $ex;
         }
      }

      function Authorize($code) {
         try{
            $this->headers = array(        
               "content-type: application/x-www-form-urlencoded",
            );
            
            $dataArray = array(
               "code" => $code,
               "redirect_uri" => $this->config->RedirectURI,
               "grant_type" => "authorization_code",
               "client_id" => $this->config->ClientID,
               "client_secret" => $this->config->Secret
            );
      
            $data = http_build_query($dataArray);
      
            $this->httpResponse = APIHelper::Post($this->config->CoreIdentityBaseUrl .'/connect/token',$data,$this->headers);

            if($this->httpResponse->header_code == 200) {
               $this->authResponse =  json_decode($this->httpResponse->body);
               GeneralMethods::SaveAuthResponse($this->authResponse);
            }

            return $this->authResponse;
         }
         catch(Exception $ex){
            throw $ex;
         }
      }

      function ReAuthorize() {
         try {
            if(GeneralMethods::GetAuthResponse() != null){
               $auth = GeneralMethods::GetAuthResponse();

               $headers = array(        
                  "content-type: application/x-www-form-urlencoded",
               );
               
               $dataArray = array(
                  "refresh_token" => $auth->refresh_token,
                  "grant_type" => "refresh_token",
                  "client_id" => $this->config->ClientID,
                  "client_secret" => $this->config->Secret
               );

               $data = http_build_query($dataArray);

               $this->httpResponse = APIHelper::Post($this->config->CoreIdentityBaseUrl .'/connect/token',$data,$headers);

               if($this->httpResponse->header_code == 200) {
                  $this->authResponse =  json_decode($this->httpResponse->body);
                  GeneralMethods::SaveAuthResponse($this->authResponse);
               }

               return $this->authResponse;
            }
         }
         catch(Exception $ex){
            throw $ex;
         }
      }

      function IsValidState($state) {
         try {
            return urlencode($state) == $_SESSION['state'] ? true : false;
         }
         catch(Exception $ex) {
            throw $ex;
         }
      }
   }
      
    

    

    
?>