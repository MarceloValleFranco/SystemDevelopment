<?php

// JQUERYUPLOAD.PHP 1.0 (2017/01/18)

$assets = "../../../../assets/";

require $assets . "php/config.php";
require $assets . "php/functions.php";
require $assets . "php/security.php";

// UPLOAD ROOT FOLDER
$files = base64_encode("../../../../files/");
if (isset($_GET['folder'])) { $files = $_GET['folder']; }

// TEXT CAPTIONS
$select = "Select";
if (isset($_GET['select'])) { $select = $_GET['select']; }
$start = "Start";
if (isset($_GET['start'])) { $start = $_GET['start']; }
$cancel = "Cancel";
if (isset($_GET['cancel'])) { $cancel = $_GET['cancel']; }
$remove = "Remove";
if (isset($_GET['remove'])) { $remove = $_GET['remove']; }
$processing = "Processing";
if (isset($_GET['processing'])) { $processing = $_GET['processing']; }
$conclude = "Refresh Folder";
if (isset($_GET['conclude'])) { $conclude = $_GET['conclude']; }

// PERMITED EXTENSIONS
$fileTypes = "gif,jpg,png";
$extensions = base64_encode("/(\.|\/)(gif|jpe?g|png)$/i");
if (isset($_GET['extensions'])) { 
    $extensions = $_GET['extensions'];
    $fileTypes = '';
    $temp = explode(",", base64_decode($extensions));
        foreach($temp as $value) {
            $fileTypes .= "." . $value . ",";
        }    
        $fileTypes = left($fileTypes, strlen($fileTypes) - 1);
}

// DEBUG
//echo $extensions; exit;

echo "<!DOCTYPE html>\n";
echo "<html>\n";

echo "  <head>\n";

echo "    <!--[if IE]>\n";
echo "        <meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'>\n";
echo "    <![endif]-->\n";

echo "    <meta charset='utf-8'>\n";
echo "    <meta name='viewport' content='width=device-width, initial-scale=1.0'>\n";
echo "    <title>" . SYSTITLE . "</title>\n";

// FONT
echo "      <link href='https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900' rel='stylesheet'>\n";

// FONT ICONS
echo addCode($assets . "icons/icomoon/styles.css");

// CSS
echo addCode($assets . "others/bootstrap/bootstrap.min.css");
echo addCode($assets . "others/limitness/css/bootstrap_limitless.min.css");
echo addCode($assets . "others/limitness/css/layout.min.css");
echo addCode($assets . "others/limitness/css/components.min.css");
echo addCode($assets . "others/limitness/css/colors.min.css");
echo addCode($assets . "css/custom.css");
echo "    <link rel='stylesheet' href='css/jquery.fileupload.css'>\n";
echo "    <link rel='stylesheet' href='css/jquery.fileupload-ui.css'>\n";

echo "  </head>\n";

echo "  <body>\n";

echo "    <form id='fileupload' action='server/php/' method='POST' enctype='multipart/form-data' style='overflow:hidden'>\n";

// UPLOAD ROOT FOLDER
echo "        <input type='hidden' name='u' value='" . $files . "' />\n";

// PERMITED EXTENSIONS
echo "        <input type='hidden' name='e' value='" . $extensions . "' />\n";

echo "        <div class='row fileupload-buttonbar'>\n";

// BUTTON BAR
echo "            <div class='col-lg-7 toolbar'>\n";

echo "                <span class='btn btn-primary fileinput-button btn-select'><i class='icon-file-plus'></i><span class='button-left-title'>" . $select . "...</span><input type='file' name='files[]' accept='" . $fileTypes . "' multiple alt='directory webkitdirectory mozdirectory'></span>\n";
echo "                <button type='submit' class='btn btn-success start' disabled><i class='icon-upload7'></i><span class='button-left-title'>" . $start . "</span></button>\n";
echo "                <button type='reset' class='btn btn-default cancel' disabled><i class='icon-file-minus'></i><span class='button-left-title'>" . $cancel . "</span></button>\n";

// UPLOAD PROCESS
echo "                <span class='fileupload-process'></span>\n";

echo "            </div>\n";

// PROGRESS BAR
echo "            <div class='col-lg-5 fileupload-progress fade'>\n";
echo "                <div class='progress progress-striped active' role='progressbar' aria-valuemin='0' aria-valuemax='100'>\n";
echo "                    <div class='progress-bar progress-bar-success' style='width:0%;'></div>\n";
echo "                </div>\n";
echo "                <div class='progress-extended'>&nbsp;</div>\n";
echo "            </div>\n";

echo "        </div>\n";

// FILE LIST
echo "        <div class='fileListContainer'><div class='fileList'><table role='presentation' class='table table-striped'><tbody class='files'></tbody></table></div></div>\n";

echo "    </form>\n";

// UPLOAD TEMPLATE SCRIPT
echo "    <script id='template-upload' type='text/x-tmpl'>\n";

echo "        {% for (var i=0, file; file=o.files[i]; i++) { %}\n";

echo "        <tr class='template-upload fade' style='border-top:0'>\n";

echo "            <td style='padding:8px; border-top:0'>\n";
echo "                <div class='preview'></div>\n";
echo "            </td>\n";

echo "            <td style='padding:8px; border-top:0'>\n";
echo "                <p class='name'>{%=file.name%}</p>\n";
echo "                <strong class='error text-danger'></strong>\n";
echo "            </td>\n";

echo "            <td style='padding:8px; border-top:0'>\n";
echo "                <div class='size'>" . $processing . "...</div>\n";
echo "                <div class='progress progress-striped active' role='progressbar' aria-valuemin='0' aria-valuemax='100' aria-valuenow='0'><div class='progress-bar progress-bar-success' style='width:0'></div></div>\n";
echo "            </td>\n";

echo "            <td class='button-td' style='padding:4px 8px 4px 0; border-top:0; text-align:right'>\n";
echo "                {% if (!i && !o.options.autoUpload) { %}\n";
echo "                  <button class='btn btn-success start' title='" . $start . "'><i class='icon-upload7'></i></button>\n";
echo "                {% } %}\n";
echo "                {% if (!i) { %}\n";
echo "                  <button class='btn btn-default cancel' title='" . $remove . "'><i class='icon-file-minus'></i></button>\n";
echo "                {% } %}\n";
echo "            </td>\n";

echo "        </tr>\n";

echo "        {% } %}\n";

echo "    </script>\n";

// DOWNLOAD TEMPLATE SCRIPT
echo "    <script id='template-download' type='text/x-tmpl'>\n</script>\n";

// JS
echo addCode($assets . "others/jquery/jquery.min.js");
echo "    <script src='js/vendor/jquery.ui.widget.js'></script>\n";
echo "    <script src='js/cors/tmpl.min.js'></script>\n";
echo "    <script src='js/cors/load-image.all.min.js'></script>\n";
echo "    <script src='js/cors/canvas-to-blob.min.js'></script>\n";
echo "    <script src='js/jquery.iframe-transport.js'></script>\n";
echo "    <script src='js/jquery.fileupload.js'></script>\n";
echo "    <script src='js/jquery.fileupload-process.js'></script>\n";
echo "    <script src='js/jquery.fileupload-image.js'></script>\n";
echo "    <script src='js/jquery.fileupload-audio.js'></script>\n";
echo "    <script src='js/jquery.fileupload-video.js'></script>\n";
echo "    <script src='js/jquery.fileupload-validate.js'></script>\n";
echo "    <script src='js/jquery.fileupload-ui.js'></script>\n";
echo "    <script src='js/main.js'></script>\n";

// NICESCROLL
echo addCode($assets . "plugins/ui/nicescroll.min.js");

// AUX JS
echo "    <script>\n";
echo "        $(document).ready(function() {\n";
echo "            $('.btn-select').click(function () { $('.start').prop('disabled', false); $('.cancel').prop('disabled', false); });\n";
echo "            $('.start').click(function () { $('.start').prop('disabled', true); $('.cancel').prop('disabled', true); window.parent.$('.btn-default').html('<i class=\"icon-check position-left\"></i>" . $conclude . "'); window.parent.$('.btn-default').removeClass('btn-default').addClass('btn-success'); });\n";
echo "            $('.cancel').click(function () { $('.start').prop('disabled', true); $('.cancel').prop('disabled', true); });\n";
echo "            var nicesx = $('.fileList').niceScroll({ cursorcolor:'#cccccc', railpadding: { top: 0, right: 0, left: 4, bottom: 0 }, cursoropacitymax: 0.6, cursorwidth: 6 });\n";
echo "        });\n";
echo "    </script>\n";

// OLD IE HACK
echo "    <!--[if (gte IE 8)&(lt IE 10)]>\n";
echo "      <script src='js/cors/jquery.xdr-transport.js'></script>\n";
echo "    <![endif]-->\n";

echo "  </body>\n";
echo "</html>";