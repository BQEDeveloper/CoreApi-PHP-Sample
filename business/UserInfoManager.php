<?php
    require_once(realpath(__DIR__ . '/..').'/models/AuthResponseModel.php');
    require_once(realpath(__DIR__ . '/..').'/models/UserInfoModel.php');
    require_once(realpath(__DIR__ . '/..').'/shared/APIHelper.php'); 
    require_once(realpath(__DIR__ . '/..').'/shared/GeneralMethods.php');

    class UserInfoManager {

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

      function GetUserInfo() {
         try {
            return APIHelper::Get($this->config->CoreIdentityBaseUrl.'/connect/userinfo',$this->headers);
         }
         catch(Exception $ex){
            throw $ex;
         }
      }
    }

    

    
?>