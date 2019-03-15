<?php
   session_start();
   require_once('business/ActivityManager.php');

   $config = GeneralMethods::GetConfig();
   $ActivityManager = new ActivityManager();

   if(isset($_GET['id'])){ // Delete Activity
    $activityResponse = $ActivityManager->Delete($_GET['id']);
    if($activityResponse->header_code == 401){ // UnAuthorised
      header("Location: ".$config->CoreIdentityBaseUrl."/connect/authorize?client_id=".$config->ClientID."&response_type=code&scope=read:core%20readwrite:core%20openid%20offline_access&redirect_uri=".$config->RedirectURI);
    }
    else if($activityResponse->header_code == 200 || $activityResponse->header_code == 204){ // Success or No Content
      header("Location: ActivityView.php");
    } 
    else {
      echo "<p style='color:red'>".$activityResponse->body."</p>";
    }  
   }

?>