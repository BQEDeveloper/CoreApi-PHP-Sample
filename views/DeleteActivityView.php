<?php
   session_start();
   require_once(realpath(__DIR__ . '/..').'/business/ActivityManager.php');
   require_once(realpath(__DIR__ . '/..').'/business/AuthManager.php'); 

   $config = GeneralMethods::GetConfig();
   $ActivityManager = new ActivityManager();
   $AuthManager = new AuthManager();

   // Delete Activity
   if(isset($_GET['id'])){ 
      $activityResponse = $ActivityManager->Delete($_GET['id']);
      if($activityResponse->header_code == 401){ // UnAuthorised
        $authResponse = $AuthManager->ReAuthorize();
        if(isset($authResponse)){
          $activityResponse = $ActivityManager->Delete($_GET['id']);
          if($activityResponse->header_code == 200 || $activityResponse->header_code == 204) // Success or No Content
            header("Location: ActivityListView.php");
        }
      }
      else if($activityResponse->header_code == 200 || $activityResponse->header_code == 204){ // Success or No Content
        header("Location: ActivityListView.php");
      } 
      else {
        echo "<p style='color:red'>".$activityResponse->body."</p>";
      }  
   }

?>