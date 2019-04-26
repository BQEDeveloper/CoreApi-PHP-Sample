<?php
    require_once(realpath(__DIR__ . '/..').'/models/ConfigModel.php');
    class GeneralMethods {

        public static function GetConfig() : ConfigModel {
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
                fwrite($AuthResponseFile, serialize($authResponse));
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
            catch(Exception $ex){
                throw $ex;
            }
        }

        public static function GenerateRandomString($length = 20) {
            try {
                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()_+":?><';
                $charactersLength = strlen($characters);
                $randomString = '';
                for ($i = 0; $i < $length; $i++) {
                    $randomString .= $characters[rand(0, $charactersLength - 1)];
                }
                return $randomString;
            }
            catch(Exception $ex){
                throw $ex;
            }
        }

        public static function Base64UrlDecode(string $str) {
            try {
                $decoded = base64_decode(strtr($str, '-_', '+/'), true);
                return $decoded;
            }
            catch(Exception $ex){
                throw $ex;
            }
        }

    }
?>