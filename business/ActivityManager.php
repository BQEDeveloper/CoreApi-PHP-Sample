<?php
    require_once('../models/AuthResponseModel.php');
    require_once('../models/ActivityModel.php');
    require_once('../shared/APIHelper.php'); 
    require_once('../shared/GeneralMethods.php');

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
         try{
            return APIHelper::Get($this->config->CoreAPIBaseUrl.'/activity?page=0,100&orderby=name',$this->headers);
         }
         catch(Exception $ex){
            throw $ex;
         }
      }

      function Get($id) {
         try{         
            return APIHelper::Get($this->config->CoreAPIBaseUrl.'/activity/'.$id,$this->headers);
         }
         catch(Exception $ex){
            throw $ex;
         }
      }

      function Create($data) {   
         try {      
            return APIHelper::Post($this->config->CoreAPIBaseUrl.'/activity',$data,$this->headers);         
         }
         catch(Exception $ex){
            throw $ex;
         }
      }

      function Update($id,$data) {
         try {         
            return APIHelper::Put($this->config->CoreAPIBaseUrl.'/activity/'.$id,$data,$this->headers);
         }
         catch(Exception $ex){
            throw $ex;
         }
      }

      function Delete($id) {  
         try {       
            return APIHelper::Delete($this->config->CoreAPIBaseUrl.'/activity/'.$id,$this->headers);
         }
         catch(Exception $ex){
            throw $ex;
         }
      }
    }

    

    
?>