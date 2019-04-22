<?php
    require_once(realpath(__DIR__ . '/..').'/models/ConfigModel.php');
    class GeneralMethods {

        public static function GetConfig() {
            try{
                $config = new ConfigModel();
                $configArray = parse_ini_file(realpath(__DIR__ . '/..')."/config.ini");
                //foreach($configArray  as $x => $x_value) { echo "Key = " . $x . ", Value = " . $x_value; }
                $config->CoreAPIBaseUrl = $configArray["CoreAPIBaseUrl"];
                $config->CoreIdentityBaseUrl = $configArray["CoreIdentityBaseUrl"];
                $config->Secret = $configArray["Secret"];
                $config->ClientID = $configArray["ClientID"];
                $config->RedirectURI = $configArray["RedirectURI"];
                $config->Scopes = $configArray["Scopes"];
                
                return $config;
            }
            catch(Exception $ex){
               throw $ex;
            }
        }

        public static function SaveAuthResponse($authResponse) {
            try{
                $AuthResponseFile = fopen(realpath(__DIR__ . '/..')."/AuthResponse.ini", "w+") or die("Unable to open file!");
                fwrite($AuthResponseFile, serialize(json_decode($authResponse)));
                fclose($AuthResponseFile);
            }
            catch(Exception $ex){
               throw $ex;
            }
        }

        public static function GetAuthResponse() {
            try{            
                $AuthResponseFile = fopen(realpath(__DIR__ . '/..')."/AuthResponse.ini", "r+") or die("Unable to open file!");
                $authResponse =  fread($AuthResponseFile,6000);
                fclose($AuthResponseFile);
                return unserialize($authResponse);                                         
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }

    }
?>