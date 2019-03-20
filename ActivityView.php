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
            "DeleteActivity.php?id="+encodeURI(id),"_self");
      }
   </script>
</head>
<body style="margin: 20px;">
<h2 style="text-align:center">Core Public API - PHP Sample</h2>
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
    require_once('business/ActivityManager.php');
    require_once('business/AuthManager.php');    

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
      $authResponse = $AuthManager->ReAuthorize();
      if(isset($authResponse)){
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

    function PrintList($activityList){
      echo '<table style="margin:20 0 20 0" class="table table-striped">
         <thead style="background: #000; color: #fff">
            <th>Code</th>
            <th>Sub-Code</th>
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
               <td onclick=loadActivity("'.$activity->id.'")>'.$activity->sub.'</td>                   
               <td onclick=loadActivity("'.$activity->id.'")>'.$activity->description.'</td>
               <td onclick=loadActivity("'.$activity->id.'")>'.($activity->billable == 1 ? "true" : "false").'</td>
               <td onclick=loadActivity("'.$activity->id.'")>'.$activity->billRate.'</td>
               <td onclick=loadActivity("'.$activity->id.'")>'.$activity->costRate.'</td>
               <td onclick=deleteActivity("'.$activity->id.'")>Delete</td>
            </tr>';
      }
      echo '</table>';
    }

    
?>
</body>
</html>