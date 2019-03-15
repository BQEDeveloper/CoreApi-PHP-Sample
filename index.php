<?php
   session_start();
?>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Core Public API - PHP Sample</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
</head>
<body style="margin: 20px;">
<?php
      require_once('models/AuthResponseModel.php');
      require_once('models/ActivityModel.php');
      require_once('shared/APIHelper.php'); 
      require_once('shared/GeneralMethods.php');

      $config = GeneralMethods::GetConfig();
          
      $authResponse = new AuthResponseModel();      
      
      if(isset($_GET['code'])){
         $headers = array(        
            "content-type: application/x-www-form-urlencoded",
         );
         
         $dataArray = array(
            "code" => $_GET['code'],
            "redirect_uri" => $config->RedirectURI,
            "grant_type" => "authorization_code",
            "client_id" => $config->ClientID,
            "client_secret" => $config->Secret
         );
   
         $data = http_build_query($dataArray);
   
         $authResponse = APIHelper::Post($config->CoreIdentityBaseUrl .'/connect/token',$data,$headers);

         $_SESSION["AuthResponse"] = serialize(json_decode($authResponse->body));
         
      }      

      header("Location: ActivityView.php");
     

       
?>
</body>
</html>