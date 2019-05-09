<?php
   session_start();
?>
<html>
<head>
   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge" />
   <title>Activities List</title>
   <meta name="viewport" content="width=device-width, initial-scale=1" />
   <!-- Jquery -->
   <script src="http://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
   <!-- Bootstrap JavaScript -->
   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
   <!-- Bootstrap CSS -->
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
   <script>
      function loadActivity(id) {
         window.open(
            "CreateActivityView.php?id="+encodeURI(id),"_self");
      }
      function deleteActivity(id) {
         window.open(
            "DeleteActivityView.php?id="+encodeURI(id),"_self");
      }
   </script>
</head>
<body style="margin: 20px;">
<?php
   try {
      require_once(realpath(__DIR__ . '/..').'/business/ActivityManager.php');
      require_once(realpath(__DIR__ . '/..').'/business/AuthManager.php');   
      require_once(realpath(__DIR__ . '/..').'/models/UserInfoModel.php'); 
      require_once(realpath(__DIR__ . '/..').'/models/AuthResponseModel.php');
      require_once(realpath(__DIR__ . '/..').'/business/UserInfoManager.php');

      //get the User Info
      $UserInfoManager = new UserInfoManager();
      $userInfoResponse = $UserInfoManager->GetUserInfo();  
            
      if($userInfoResponse->header_code == 401){ // UnAuthorised  
         $AuthManager = new AuthManager();     
         $authResponse = $AuthManager->ReAuthorize();
         if(isset($authResponse)){
            $UserInfoManager = new UserInfoManager();
            $userInfoResponse = $UserInfoManager->GetUserInfo();
            $userInfo = json_decode($userInfoResponse->body);
         }
      }
      else if($userInfoResponse->header_code == 200){ // Success
         $userInfo = json_decode($userInfoResponse->body);
      }
   }
   catch(Exception $ex){
      echo $ex->getMessage();
   }
   ?>
   <h2 style="text-align:center">Core API - PHP Sample</h2>
   <h4 style="text-align:center" title="Company"><?php echo $userInfo->company ?></h4>
   <div style="text-align:center">
      <form method="post">
            <input type="submit" class="btn btn-danger" name="btnDisconnectFromCore" id="btnDisconnectFromCore" value="Disconnect from Core" />
      </form>
   </div>
   <div style="text-align:right">
      <a href="CreateActivityView.php" class="btn btn-primary" role="button">Create Activity</a>   
   </div>
   <h3>Activities List</h3>
   <?php
      try {
         $config = GeneralMethods::GetConfig();     
         $ActivityManager = new ActivityManager(); 
         $AuthManager = new AuthManager();   
         $authResponse = new AuthResponseModel();
         

         //Disconnect from Core
         if(array_key_exists('btnDisconnectFromCore',$_POST)){
            $AuthManager->DisconnectFromCore();
            exit();
         }     

         $activityListResponse = $ActivityManager->GetList();    

         if($activityListResponse->header_code == 401){ // UnAuthorised    
            $AuthManager = new AuthManager();   
            $authResponse = $AuthManager->ReAuthorize();
            if(isset($authResponse)){
               $ActivityManager = new ActivityManager();
               $activityListResponse = $ActivityManager->GetList();
               $activityList = json_decode($activityListResponse->body);
               PrintList($activityList);
            }
         }
         else if($activityListResponse->header_code == 200){ // Success
            $activityList = json_decode($activityListResponse->body);
            PrintList($activityList);
         }
         else{
            echo "<p style='color:red'>".$activityResponse->body."</p>";
         }
   
      }
      catch(Exception $ex){
         echo $ex->getMessage();
      }

      function PrintList($activityList){
         try {
            echo '<table style="margin:20 0 20 0" class="table table-striped">
               <thead style="background: #000; color: #fff">
                  <th>Code</th>
                  <th>Description</th>
                  <th>Billable</th>
                  <th>Bill Rate</th>
                  <th>Cost Rate</th> 
                  <th></th>                  
               </thead>
            ';
            foreach ($activityList as $activity) {
            echo '<tr style="cursor:pointer">
                     <td onclick=loadActivity("'.$activity->id.'")>'.$activity->code.'</td>                
                     <td onclick=loadActivity("'.$activity->id.'")>'.$activity->description.'</td>
                     <td onclick=loadActivity("'.$activity->id.'")>'.($activity->billable == 1 ? "true" : "false").'</td>
                     <td onclick=loadActivity("'.$activity->id.'")>'.$activity->billRate.'</td>
                     <td onclick=loadActivity("'.$activity->id.'")>'.$activity->costRate.'</td>
                     <td onclick=deleteActivity("'.$activity->id.'")>Delete</td>
                  </tr>';
            }
            echo '</table>';
         }
         catch(Exception $ex){
            echo $ex->getMessage();
         }
      }
   
    
?>
</body>
</html>