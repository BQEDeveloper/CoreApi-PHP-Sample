<?php
   session_start();
   require_once(realpath(__DIR__ . '/..').'/models/ActivityModel.php');
   require_once(realpath(__DIR__ . '/..').'/business/ActivityManager.php');
   require_once(realpath(__DIR__ . '/..').'/business/AuthManager.php'); 

   $config = GeneralMethods::GetConfig();
   $ActivityManager = new ActivityManager();
   $AuthManager = new AuthManager(); 
   $activity = new ActivityModel();

   // Load Activity
   if(isset($_GET['id']) && !isset($_POST['submit'])){ 
    $activityResponse = $ActivityManager->Get($_GET['id']);
    if($activityResponse->header_code == 401){ // UnAuthorised
      $authResponse = $AuthManager->ReAuthorize();
      if(isset($authResponse)){
         $ActivityManager = new ActivityManager();
         $activityResponse = $ActivityManager->Get($_GET['id']);
         $activity = json_decode($activityResponse->body);
      }
    }
    else if($activityResponse->header_code == 200){ // Success 
      $activity = json_decode($activityResponse->body);
    } 
    else {
      echo "<p style='color:red'>".$activityResponse->body."</p>";
    }  
   }

   //Check if form was submitted for Update / Create 
   if(isset($_POST['submit'])){ 
    
      $activity->code = $_POST['code'];
      $activity->description = $_POST['description'];
      $activity->billRate = $_POST['billRate'];
      $activity->costRate = $_POST['costRate'];
      $activity->billable = isset($_POST['isBillable']) ? true : false;      

      if(isset($_GET['id'])){ //update
        $activity->id = $_GET['id'];
        $data = json_encode($activity);
        $activityResponse = $ActivityManager->Update($activity->id,$data);
      }
      else { //create
        $data = json_encode($activity);
        $activityResponse = $ActivityManager->Create($data);
      }

      if($activityResponse->header_code == 401){ // UnAuthorised
        $authResponse = $AuthManager->ReAuthorize();
        if(isset($authResponse)){
          if(isset($_GET['id'])){ //update
            $activity->id = $_GET['id'];
            $data = json_encode($activity);
            $ActivityManager = new ActivityManager();
            $activityResponse = $ActivityManager->Update($activity->id,$data);
          }
          else { //create
            $data = json_encode($activity);
            $activityResponse = $ActivityManager->Create($data);
          }
          if($activityResponse->header_code == 200 || $activityResponse->header_code == 201) // Success or created
            header("Location: ActivityListView.php");
        }
      }
      else if($activityResponse->header_code == 200 || $activityResponse->header_code == 201){ // Success or created
        header("Location: ActivityListView.php");
      } 
      else {
        echo "<p style='color:red'>".$activityResponse->body."</p>";
      }  
  }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Create Activity</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- Jquery -->
    <script src="http://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <!-- Bootstrap JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
</head>
<body style="margin: 20px;">
  <a href="ActivityListView.php" class="col-sm-10 col-sm-offset-2">Back to List</a>
  <form class="form-horizontal" method="post" style="padding-top: 50px;" action="">
    <div class="form-group">
      <label class="control-label col-sm-2" >Code: *</label>
      <div class="col-sm-6">
        <input type="text" class="form-control" name="code" placeholder="Enter code" required value="<?php echo $activity->code ?>">
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-2" >Description: *</label>
      <div class="col-sm-6">          
        <input type="text" class="form-control" name="description" placeholder="Enter Description" required value="<?php echo $activity->description ?>">
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-2" >Bill Rate:</label>
      <div class="col-sm-6">          
        <input type="text" class="form-control" name="billRate" placeholder="Enter Bill Rate" value="<?php echo $activity->billRate ?>">
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-2" >Cost Rate:</label>
      <div class="col-sm-6">          
        <input type="text" class="form-control" name="costRate" placeholder="Enter Cost Rate" value="<?php echo $activity->costRate ?>">
      </div>
    </div>
    <div class="form-group">        
      <div class="col-sm-offset-2 col-sm-6">
        <div class="checkbox">
          <label><input type="checkbox" name="isBillable" <?php echo ($activity->billable == 1 ? 'checked' : ''); ?> > Billable</label>
        </div>
      </div>
    </div>
    <div class="form-group">        
      <div class="col-sm-offset-2 col-sm-6">
        <button type="submit" class="btn btn-success" name="submit">Submit</button>
      </div>
    </div>
  </form>
</body>
</html>