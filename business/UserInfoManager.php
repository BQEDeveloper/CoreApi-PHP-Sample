<?php
    require_once(realpath(__DIR__ . '/..').'/models/AuthResponseModel.php');
    require_once(realpath(__DIR__ . '/..').'/models/UserInfoModel.php');
    require_once(realpath(__DIR__ . '/..').'/models/HttpResponseModel.php');
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
      public $httpResponse;

      function __Construct() {
         $this->config = GeneralMethods::GetConfig();
          
         $this->authResponse = new AuthResponseModel();
         $this->authManager = new AuthManager();

         if($this->authManager->GetAuthResponse() != null)
            $this->authResponse = $this->authManager->GetAuthResponse();
            $this->httpResponse = new HttpResponseModel();
            $this->httpHeader = new HttpHeaderModel();
            $this->httpHeader->authorization = "Bearer ". $this->authResponse->access_token;
      }

      function GetUserInfo() {
         try {
            $this->httpResponse = APIHelper::Get($this->config->CoreIdentityBaseUrl.'/connect/userinfo',$this->httpHeader);

            if($this->httpResponse->header_code == 401){ // UnAuthorised  
               $this->authResponse = $this->authManager->ReAuthorize();
               if(isset($this->authResponse)){
                  $this->httpHeader->authorization = "Bearer ". $this->authResponse->access_token;
                  return $this->GetUserInfo();
               }
            }
            else if($this->httpResponse->header_code == 200){ // Success
               $userInfo = json_decode($this->httpResponse->body);
               return $userInfo;
            }
         }
         catch(Exception $ex){
            throw $ex;
         }         
      }
    }

    

    
?>