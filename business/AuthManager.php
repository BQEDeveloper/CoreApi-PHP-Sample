<?php
    require_once(realpath(__DIR__ . '/..').'/models/AuthResponseModel.php');
    require_once(realpath(__DIR__ . '/..').'/shared/APIHelper.php'); 
    require_once(realpath(__DIR__ . '/..').'/shared/GeneralMethods.php');

    class AuthManager {

      public $config;
      public $authResponse;
      public $headers;

      function __Construct() {
         $this->config = GeneralMethods::GetConfig();
          
         $this->authResponse = new AuthResponseModel(); 

         if(GeneralMethods::GetAuthResponse() != null)
            $this->authResponse = GeneralMethods::GetAuthResponse();
 
            $this->headers = array( 
               "accept: application/json",       
               "authorization: Bearer " . $this->authResponse->access_token,
               "content-type: application/json",
            );
      }

      function ConnectToCore() {
         header("Location: ".$this->config->CoreIdentityBaseUrl."/connect/authorize?client_id=".$this->config->ClientID."&response_type=code&scope=read:core%20readwrite:core%20openid%20offline_access&redirect_uri=".$this->config->RedirectURI);
      }

      function DisconnectFromCore() {
         GeneralMethods::SaveAuthResponse('');
         header("Location: ../index.php");
      }

      function Authorize($code) {
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
   
         $this->authResponse = APIHelper::Post($this->config->CoreIdentityBaseUrl .'/connect/token',$data,$this->headers);

         if($this->authResponse->header_code == 200) 
            GeneralMethods::SaveAuthResponse($this->authResponse->body);

         return $this->authResponse;
      }

      function ReAuthorize() {
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

            $this->authResponse = APIHelper::Post($this->config->CoreIdentityBaseUrl .'/connect/token',$data,$headers);

            if($this->authResponse->header_code == 200) 
               GeneralMethods::SaveAuthResponse($this->authResponse->body);

            return $this->authResponse;
         }
      }
    }

    

    
?>