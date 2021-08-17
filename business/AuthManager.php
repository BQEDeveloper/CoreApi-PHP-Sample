<?php
    require_once(realpath(__DIR__ . '/..').'/models/AuthResponseModel.php');
    require_once(realpath(__DIR__ . '/..').'/models/HttpHeaderModel.php');
    require_once(realpath(__DIR__ . '/..').'/shared/APIHelper.php'); 
    require_once(realpath(__DIR__ . '/..').'/shared/GeneralMethods.php');

    class AuthManager {

      public $config;
      public $authResponse;
      public $httpResponse;
      public $httpHeader;

      function __Construct() {
         $this->config = GeneralMethods::GetConfig();
          
         $this->authResponse = new AuthResponseModel();
         $this->httpResponse = new HttpResponseModel();
         $this->httpHeader = new HttpHeaderModel();

         if($this->GetAuthResponse() != null)
            $this->authResponse = $this->GetAuthResponse();
      }

      function ConnectToCore() {
         $state = urlencode(GeneralMethods::GenerateRandomString());         
         $_SESSION['state'] = $state;
         header("Location: ".$this->config->CoreIdentityBaseUrl."/connect/authorize?client_id=".$this->config->ClientID."&response_type=code&scope=".$this->config->Scopes."&redirect_uri=".$this->config->RedirectURI."&state=".$state);
      }

      function DisconnectFromCore() {
         try{
            $this->httpHeader->contentType = "application/x-www-form-urlencoded";
            
            $dataArray = array(
               "token" => $this->authResponse->access_token,
               "client_id" => $this->config->ClientID,
               "client_secret" => $this->config->Secret
            );

            $data = http_build_query($dataArray);

            $this->httpResponse = APIHelper::Post($this->config->CoreIdentityBaseUrl .'/connect/revocation',$data,$this->httpHeader);
            if($this->httpResponse->header_code == 200){
               $this->SaveAuthResponse('');
               header("Location: ../index.php");
            }
         }
         catch(Exception $ex){
            throw $ex;
         }
      }

      function Authorize($code) {
         try{
            $this->httpHeader->contentType = "application/x-www-form-urlencoded";
            
            $dataArray = array(
               "code" => $code,
               "redirect_uri" => $this->config->RedirectURI,
               "grant_type" => "authorization_code",
               "client_id" => $this->config->ClientID,
               "client_secret" => $this->config->Secret
            );
      
            $data = http_build_query($dataArray);
      
            $this->httpResponse = APIHelper::Post($this->config->CoreIdentityBaseUrl .'/connect/token',$data,$this->httpHeader);

            if($this->httpResponse->header_code == 200){
               $this->authResponse =  json_decode($this->httpResponse->body);                           
               if(substr($this->authResponse->endpoint, -1) == '/')
                  $this->authResponse->endpoint = substr($this->authResponse->endpoint, 0, -1);
            }
            else 
               throw new Exception($this->httpResponse);

            return $this->authResponse;
         }
         catch(Exception $ex){
            throw $ex;
         }
      }

      function ReAuthorize() {
         try {
            if($this->GetAuthResponse() != null){
               $auth = $this->GetAuthResponse();

               $this->httpHeader->contentType = "application/x-www-form-urlencoded";
               
               $dataArray = array(
                  "refresh_token" => $auth->refresh_token,
                  "grant_type" => "refresh_token",
                  "client_id" => $this->config->ClientID,
                  "client_secret" => $this->config->Secret
               );

               $data = http_build_query($dataArray);

               $this->httpResponse = APIHelper::Post($this->config->CoreIdentityBaseUrl .'/connect/token',$data,$this->httpHeader);

               if($this->httpResponse->header_code == 200) {
                  $this->authResponse =  json_decode($this->httpResponse->body);
                  if(substr($this->authResponse->endpoint, -1) == '/')
                     $this->authResponse->endpoint = substr($this->authResponse->endpoint, 0, -1);
                  $this->SaveAuthResponse($this->authResponse);
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

      public function SaveAuthResponse($authResponse) {
         try{
             $AuthResponseFile = fopen(realpath(__DIR__ . '/..')."/AuthResponse.ini", "w+") or die("Unable to open file!");
             fwrite($AuthResponseFile, serialize($authResponse));
             fclose($AuthResponseFile);
         }
         catch(Exception $ex){
            throw $ex;
         }
     }

     public function GetAuthResponse() {
         try{            
             $AuthResponseFile = fopen(realpath(__DIR__ . '/..')."/AuthResponse.ini", "r+") or die("Unable to open file!");
             $authResponse =  fread($AuthResponseFile,6000);
             fclose($AuthResponseFile);
             return unserialize($authResponse);                                         
         }
         catch(Exception $ex){
             throw $ex;
         }
     }
   }
      
    

    

    
?>