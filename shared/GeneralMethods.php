<?php
    require_once('models/ConfigModel.php');
    class GeneralMethods {

        public static function GetConfig() {
            $config = new ConfigModel();
            $configArray = parse_ini_file("config.ini");
            //foreach($configArray  as $x => $x_value) { echo "Key = " . $x . ", Value = " . $x_value; }
            $config->CoreAPIBaseUrl = $configArray["CoreAPIBaseUrl"];
            $config->CoreIdentityBaseUrl = $configArray["CoreIdentityBaseUrl"];
            $config->Secret = $configArray["Secret"];
            $config->ClientID = $configArray["ClientID"];
            $config->RedirectURI = $configArray["RedirectURI"];
            
            return $config;
        }
    }
?>