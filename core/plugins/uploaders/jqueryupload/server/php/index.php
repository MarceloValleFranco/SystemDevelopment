<?php

/*
* jQuery File Upload Plugin PHP Example
* https://github.com/blueimp/jQuery-File-Upload
*
* Copyright 2010, Sebastian Tschan
* https://blueimp.net
*
* Licensed under the MIT license:
* http://www.opensource.org/licenses/MIT
*/

if (isset($_POST['e'])) {

    error_reporting(E_ALL | E_STRICT);
    require('UploadHandler.php');

    $extensions = base64_decode($_POST['e']);
    $temp = explode(",", $extensions);
    $extensions = "/(\.|\/)(";
        foreach($temp as $value) {
            $extensions .= $value . "|";
        }    
        $extensions = left($extensions, strlen($extensions) - 1);
    $extensions .= ")$/i";

    $options = array('upload_dir'=>"../../" . utf8_encode(base64_decode($_POST['u'])), 'upload_url'=>base64_decode($_POST['u']), 'accept_file_types'=>$extensions);
    $upload_handler = new UploadHandler($options);

}

function left($str, $length) { return substr($str, 0, $length); }