<?php
  try{
    session_start();
    require_once(realpath(__DIR__ . '/..').'/business/ActivityManager.php');
    require_once(realpath(__DIR__ . '/..').'/business/AuthManager.php'); 

    $config = GeneralMethods::GetConfig();
    $ActivityManager = new ActivityManager();
    $AuthManager = new AuthManager();

    // Delete Activity
    if(isset($_GET['id'])){ 
        $ActivityManager->Delete($_GET['id']);
        header("Location: ActivityListView.php"); 
    }
  }
  catch(Exception $ex){
    echo "<p style='color:red'>".$ex->getMessage()."</p>";
  }
?>