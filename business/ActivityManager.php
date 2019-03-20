<?php
    require_once('models/AuthResponseModel.php');
    require_once('models/ActivityModel.php');
    require_once('shared/APIHelper.php'); 
    require_once('shared/GeneralMethods.php');

    class ActivityManager {

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

      function GetList() {
         return APIHelper::Get($this->config->CoreAPIBaseUrl.'/activity/query?page=number=1,size=1000',$this->headers);
      }

      function Get($id) {
         return APIHelper::Get($this->config->CoreAPIBaseUrl.'/activity/'.$id,$this->headers);
      }

      function Create($data) {         
         return APIHelper::Post($this->config->CoreAPIBaseUrl.'/activity',$data,$this->headers);
      }

      function Update($id,$data) {         
         return APIHelper::Put($this->config->CoreAPIBaseUrl.'/activity/'.$id,$data,$this->headers);
      }

      function Delete($id) {         
         return APIHelper::Delete($this->config->CoreAPIBaseUrl.'/activity/'.$id,$this->headers);
      }
    }

    

    
?>