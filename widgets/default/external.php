<?php

// EXTERNAL.PHP 1.0 (2019/11/07)

// INITIAL PANEL
$activePanel = '1';

if (isset($_POST['p'])) { $activePanel = $_POST['p']; }

// APP URL
$URL = $z["ItemTargetURL"];
if ($z["AddUserID"] ) { $URL .= "?userid=" . USERID; }

$border = ''; if (!$z["ItemBordered"]) { $border = " style='border:0; -webkit-box-shadow:none; -moz-box-shadow:none; box-shadow:none; width:100%'"; }

echo "                              <div class='card'" . $border . ">\n";

// SPACING BORDER
if ($z["ItemBordered"]) {
    
    echo "                                  <div class='card-header'>\n";
    
    echo "                                      <h5 class='card-title'>\n";
    
    if ($z["ItemIcon"] != '') { echo "                                          <i class='icon-" . $z["ItemIcon"] . " mr-2 width-20'></i>\n"; }
    
    echo "                                          " . $z["ItemName"] . "\n";
    echo "                                      </h5>\n";
    
    echo "                                  </div>\n";
    
    echo "                                  <div class='card-body' style='padding:0 16px 12px 16px'>\n";
    
} else {
    
    echo "                                  <div class='card-body' style='padding:0'>\n";
}

// APP MEASURES
$width = $z["ItemWidth"]; if ($width == '') { $width = "640"; }
$height = $z["ItemHeight"]; if ($height == '') { $height = "480"; }

echo "                                      <iframe id='appframe' style='border:0; border-radius:4px; overflow:hidden; width:" . $width . "px; height:" . $height . "px' src='" . $URL . "'></iframe>\n";

// FULL SCREEN
if ($z["ItemFullScreen"]) { 
    echo "                                      <script>$('.desk-body').css('overflow', 'hidden'); var width = $(window).width() - 30; $('#appframe').width(width); var height = $(window).height() - 44; $('#appframe').height(height);</script>\n"; 
}

echo "                                  </div>\n"; // /card-body

echo "                              </div>\n"; // /card
