<?php

// VIDEO.PHP 1.0 (2019/11/31)

require "../../core/config.php";
require "../../core/functions.php";
//require "../../core/security.php";

if (isset($_GET["sign"])) { $itemSignature = $_GET["sign"]; } else { echo "Signature Required!"; exit; }

// GET CAMERA DATA
openDB();

    $d = select("*", "appcameras", "where ItemSignature='" . $itemSignature . "'");
    $itemCode = $d[0]["ItemCode"];
    $mediaServerID = $d[0]["MediaServerID"];
    $itemRTSP = $d[0]["ItemRTSP"];
    $itemMJPG = $d[0]["ItemMJPG"];

closeDB();

$mediaServerIP = "10.50.3.51"; $mediaServerIP1 = "10.50.3.51";

if ($mediaServerIP != '') {

    if (trim($itemMJPG) == '') {
        
        $video = "http://" . $mediaServerIP1 . ":" . $itemCode . "/videostream";
        
    } else {
        
        $video = $itemMJPG;
        
    }
    
    $video = "http://" . $mediaServerIP . ":9001/?ID=" . $itemCode . "&URL=" . $video;
    
} else {

    echo "No Media Server!"; exit;
    
}

//echo ">>" . $video; exit;

?>
<!DOCTYPE html>
  <html>
  <head>
      <title>Video Window 1.0</title>
  </head>
  <body style='padding:0; margin:0; border:0; overflow:hidden' onload="document.getElementById('vd').style.display = ''; document.getElementById('videot').src = '<?php echo $video; ?>';"  onunload="document.getElementById('play').innerHTML = '';">
      <div id='play' style='padding:0; margin:0; border:0; background-color:#000'>
          <img id='gf' style='position:absolute; top:0; left:0' src='assets/images/video.gif' style='width:100%' />
          <div id='vd' style='display:none; position:absolute; top:0; left:0; border:0'><img id='videot' lowsrc='assets/images/point.png' src='assets/images/point.png' style='width:100%' /></div>
      </div>
  </body>
</html>