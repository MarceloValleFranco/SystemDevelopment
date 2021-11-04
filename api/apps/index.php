<?php

// INDEX.PHP 1.0 (2019/11/05)

header("Access-Control-Allow-Origin: *");

require "../../core/config.php";
require "../../core/functions.php";

$requestMethod = $_SERVER['REQUEST_METHOD'];

switch($requestMethod) {

    case 'POST': 

        $appkey = ''; if (isset($_POST['appkey'])) { $appkey = $_POST['appkey']; }
        
        // DATABASE OPEN
        openDB();
        
            require_once('jwt.php');        
        
            // CREATE PAYLOAD ARRAY
            $payloadArray = array();

            // GET APP DATA
            if ($d = select("*", "sysapps", "where AppKey='" . $appkey . "'")) {        

                $payloadArray['AppID'] = $d[0]['ItemID'];            
            
                // $nbf = strtotime('2021-01-01 00:00:01');
                // $exp = strtotime('2021-01-01 00:00:01');
                
                if (isset($nbf)) { $payloadArray['nbf'] = $nbf; }
                if (isset($exp)) { $payloadArray['exp'] = $exp; }
                
                // CREATE TOKEN
                $serverKey = '5f2b5cdbe5194f10b3241568fe4e2b24';
                $token = JWT::encode($payloadArray, $serverKey);

                // RETURN TO CALLER
                $returnArray = array('token' => $token);
                $jsonEncodedReturnArray = json_encode($returnArray, JSON_PRETTY_PRINT);
                echo $jsonEncodedReturnArray;

            } else {
                
                // INVALID KEY
                $returnArray = array('error' => 'Invalid App Key!');
                $jsonEncodedReturnArray = json_encode($returnArray, JSON_PRETTY_PRINT);
                echo $jsonEncodedReturnArray;
            }
        
        // DATABASE CLOSE
        closeDB();        

        break;

    case 'GET':

        $token = null;
        
        if (isset($_GET['token'])) { $token = $_GET['token']; }

        if (!is_null($token)) {

            require_once('jwt.php');

            $serverKey = '5f2b5cdbe5194f10b3241568fe4e2b24';

            try {
                
                $payload = JWT::decode($token, $serverKey, array('HS256'));
                
                $returnArray = array('AppID' => $payload->AppID);
                
                if (isset($payload->exp)) { $returnArray['exp'] = date(DateTime::ISO8601, $payload->exp); }
                
            } catch(Exception $e) {
                
                $returnArray = array('error' => $e->getMessage());
            }
        
        } else {
            
            $returnArray = array('error' => 'Invalid Token!');
        }
        
        // RETURN TO CALLER
        $jsonEncodedReturnArray = json_encode($returnArray, JSON_PRETTY_PRINT);
        echo $jsonEncodedReturnArray;

        break;

    default:
    
        // API ACCESS ERROR
        $returnArray = array('error' => 'Invalid API Call Method!');
        $jsonEncodedReturnArray = json_encode($returnArray, JSON_PRETTY_PRINT);
        echo $jsonEncodedReturnArray;
        
}