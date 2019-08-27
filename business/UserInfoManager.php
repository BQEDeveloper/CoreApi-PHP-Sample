<?php
    require_once(realpath(__DIR__ . '/..').'/models/AuthResponseModel.php');
    require_once(realpath(__DIR__ . '/..').'/models/UserInfoModel.php');
    require_once(realpath(__DIR__ . '/..').'/models/HttpHeaderModel.php');
    require_once(realpath(__DIR__ . '/..').'/shared/APIHelper.php'); 
    require_once(realpath(__DIR__ . '/..').'/shared/GeneralMethods.php');
    require_once(realpath(__DIR__ . '/..').'/business/AuthManager.php');

    class UserInfoManager {

      public $config;
      public $authResponse;
      public $headers;
      public $authManager;
      public $httpHeader;

      function __Construct() {
         $this->config = GeneralMethods::GetConfig();
          
         $this->authResponse = new AuthResponseModel();
         $this->authManager = new AuthManager();

         if($this->authManager->GetAuthResponse() != null)
            $this->authResponse = $this->authManager->GetAuthResponse();

            $this->httpHeader = new HttpHeaderModel();
      }

      function GetUserInfo() {
         try {
            return APIHelper::Get($this->config->CoreIdentityBaseUrl.'/connect/userinfo',$this->httpHeader);
         }
         catch(Exception $ex){
            throw $ex;
         }
      }
    }

    

    
?>