<?php
   session_start();
?>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Core API - PHP Sample</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- Jquery -->
    <script src="http://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <!-- Bootstrap JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
</head>
<body style="margin: 20px;">
<h2 style="text-align:center">Core API - PHP Sample</h2>
<div style="text-align:center;padding-top: 20px;">
   <form method="post">
      <input type="submit" class="btn btn-primary" name="btnConnectToCore" id="btnConnectToCore" value="Connect to Core API" />
   </form>
</div>
<?php
      require_once('business/AuthManager.php');
      require_once('shared/GeneralMethods.php');

      $config = GeneralMethods::GetConfig();          
      $AuthManager = new AuthManager();      
      
      //Authenticate
      if(isset($_GET['code'])){
         //verfiy that the state parameter returned by the server is the same that was sent earlier.
         if($AuthManager->IsValidState($_GET['state']))
            $AuthManager->Authorize($_GET['code']);                  
         else
            throw new Exception("State Parameter returned doesn't match to the one sent to Core OAuth Server.");
      }      

      //Load Activity List
      if(GeneralMethods::GetAuthResponse() != null){
         header("Location: views/ActivityListView.php");
         exit();
      }
      
      //Connect To Core
      if(array_key_exists('btnConnectToCore',$_POST)){
         $AuthManager->ConnectToCore();
      }  
      
?>
</body>
</html>