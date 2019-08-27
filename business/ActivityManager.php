<?php
    require_once('../models/AuthResponseModel.php');
    require_once('../models/ActivityModel.php');
    require_once('../models/HttpHeaderModel.php');
    require_once('../models/HttpResponseModel.php');
    require_once('../shared/APIHelper.php'); 
    require_once('../shared/GeneralMethods.php');
    require_once('AuthManager.php');

    class ActivityManager {

      public $config;
      public $authResponse;
      public $headers;
      public $authManager;
      public $httpHeader;
      public $httpResponse;

      function __Construct() {
         try {
            $this->config = GeneralMethods::GetConfig();
          
            $this->authResponse = new AuthResponseModel();
            $this->authManager = new AuthManager(); 

            if($this->authManager->GetAuthResponse() != null) {
               $this->authResponse = $this->authManager->GetAuthResponse();
               $this->httpHeader = new HttpHeaderModel();
               $this->httpResponse = new HttpResponseModel();
               $this->httpHeader->authorization = "Bearer ". $this->authResponse->access_token;
            }
         } catch (Exception $ex) {
            throw $ex;
         }         
      }

      function GetList() {
         try{
            $this->httpResponse = APIHelper::Get($this->config->CoreAPIBaseUrl.'/activity?page=0,100&orderby=name',$this->httpHeader);

            if($this->httpResponse->header_code == 401){ // UnAuthorised       
               $this->authResponse = $this->authManager->ReAuthorize();
               if(isset($this->authResponse)){
                  $this->httpHeader->authorization = "Bearer ". $this->authResponse->access_token;
                  return $this->GetList();
               }
            }
            else if($this->httpResponse->header_code == 200){ // Success
               $activityList = json_decode($this->httpResponse->body);
               return $activityList;
            } else {
               throw new Exception($this->httpResponse->body);
            }
         } catch(Exception $ex){
            throw $ex;
         }
      }

      function Get(String $id) {
         try{                     

            $this->httpResponse = APIHelper::Get($this->config->CoreAPIBaseUrl.'/activity/'.$id,$this->httpHeader);

            if($this->httpResponse->header_code == 401){ // UnAuthorised       
               $this->authResponse = $this->authManager->ReAuthorize();
               if(isset($this->authResponse)){
                  $this->httpHeader->authorization = "Bearer ". $this->authResponse->access_token;
                  return $this->Get($id);
               }
            }
            else if($this->httpResponse->header_code == 200){ // Success
               $activity = json_decode($this->httpResponse->body);
               return $activity;
            } else {
               throw new Exception($this->httpResponse->body);
            }
         }
         catch(Exception $ex){
            throw $ex;
         }
      }

      function Create($data) {   
         try {      
            $this->httpResponse = APIHelper::Post($this->config->CoreAPIBaseUrl.'/activity',$data,$this->httpHeader);   

            if($this->httpResponse->header_code == 401){ // UnAuthorised       
               $this->authResponse = $this->authManager->ReAuthorize();
               if(isset($this->authResponse)){
                  $this->httpHeader->authorization = "Bearer ". $this->authResponse->access_token;
                  return $this->Create($data);
               }
            }
            else if($this->httpResponse->header_code == 200 || $this->httpResponse->header_code == 201){ // Success or Created
               return $this->httpResponse->body;               
            } else {
               throw new Exception($this->httpResponse->body);
            }      
         }
         catch(Exception $ex){
            throw $ex;
         }
      }

      function Update(String $id, $data) {
         try {         
            $this->httpResponse =  APIHelper::Put($this->config->CoreAPIBaseUrl.'/activity/'.$id,$data,$this->httpHeader);

            if($this->httpResponse->header_code == 401){ // UnAuthorised       
               $this->authResponse = $this->authManager->ReAuthorize();
               if(isset($this->authResponse)){
                  $this->httpHeader->authorization = "Bearer ". $this->authResponse->access_token;
                  return $this->Update($id, $data);
               }
            }
            else if($this->httpResponse->header_code == 200){ // Success
               $activity = json_decode($this->httpResponse->body);
               return $activity;               
            } else {
               throw new Exception($this->httpResponse->body);
            } 
         }
         catch(Exception $ex){
            throw $ex;
         }
      }

      function Delete(String $id) {  
         try {       
            $this->httpResponse =  APIHelper::Delete($this->config->CoreAPIBaseUrl.'/activity/'.$id,$this->httpHeader);

            if($this->httpResponse->header_code == 401){ // UnAuthorised       
               $this->authResponse = $this->authManager->ReAuthorize();
               if(isset($this->authResponse)){
                  $this->httpHeader->authorization = "Bearer ". $this->authResponse->access_token;
                  return $this->Delete($id);
               }
            }
            else if($this->httpResponse->header_code == 200 || $this->httpResponse->header_code == 204){ // Success or No-Content
               return $this->httpResponse->body;                
            } else {
               throw new Exception($this->httpResponse->body);
            }
         }
         catch(Exception $ex){
            throw $ex;
         }
      }
    }

    

    
?>