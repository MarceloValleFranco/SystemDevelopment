<?php

// CONFIG.PHP 1.0 (2019/01/04)

define ('SYSAUTHOR', "Engie Digital");
define ('SYSAUTHORURL', "http://www.engiedigital.com.br/");
define ('SYSUPDATEURL', "http://www.engiedigital.com.br/eiffel/update/");
define ('SYSMANUALURL', "http://www.engiedigital.com.br/eiffel/manual/");
define ('SYSMAIL', "marcelo.franco@engie.com");

// READ SYSTEM KEY
$path = str_replace("config.php", "system.key", __FILE__);

if (!file_exists($path)) { $path = str_replace("system.key", "system-template.key", $path); }

if (file_exists($path)) {

	// GET SYSTEM KEY DATA
	$pair = array(); $lines = file($path);
	
	foreach($lines as $line) {
		
		$data = explode('=', $line);
		$key = trim($data[0]);
		$val = trim($data[1]);
		$pair[$key] = $val;
		
		// CONSTANTS
		if ($key != "LICENSECODE") { define ($key, $val); }
	}
	
	// DEBUG
	//print_r($pair); //exit;

} else {
	
	// SYSTEM KEY NOT FOUND!
	echo loginErrorBox("SYSTEM.KEY not found!"); exit;		
}

// LICENSE
if (strpos(strtolower($_SERVER['SCRIPT_NAME']), "demo")) { 

	// DEMO VERSION
	define ('LICENSECODE', "DEMOLICENSE");
	define ('DEMO', true);
	
} else {
	
	// LICENSE CODE
	define ('LICENSECODE', $pair["LICENSECODE"]);
	define ('LICENSEDATA', "");
	define ('DEMO', false);
}

// LANGUAGES
if (isset($_COOKIE['sysLanguage'])) { 

    // CHOOSED LANGUAGE
    $lg = $_COOKIE['sysLanguage'];
    define ('DEFAULTLANGUAGE', $lg);
    
    switch ($lg) {
    
        case "en";
            define ('LOCALE', "en");
            define ('LONGLANGUAGE', "english");
            define ('CURRENCY', "U$");
            date_default_timezone_set('America/New_York');
            break;
            
        case "fr";
            define ('LOCALE', "fr");
            define ('LONGLANGUAGE', "french");
            define ('CURRENCY', "U$");
            date_default_timezone_set('Europe/Paris');
            break;
            
        case "es";
            define ('LOCALE', "es");
            define ('LONGLANGUAGE', "spanish");
            define ('CURRENCY', "U$");
            date_default_timezone_set('Europe/Barcelona');
            break;
            
        default;
            define ('LOCALE', "pt_BR");
            define ('LONGLANGUAGE', "portuguese");
            define ('CURRENCY', "R$");
            date_default_timezone_set('America/Sao_Paulo');
            break;            
    }  

} else {

    // DEFAUT LANGUAGE
    if (substr(@$_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2) == "pt") {
    
        define ('DEFAULTLANGUAGE', "br");
        define ('LOCALE', "pt_BR");
        define ('LONGLANGUAGE', "portuguese");
        define ('CURRENCY', "R$");
        date_default_timezone_set('America/Sao_Paulo');
    
    } else {
    
        define ('DEFAULTLANGUAGE', "en");
        define ('LOCALE', "en");
        define ('LONGLANGUAGE', "english");
        define ('CURRENCY', "U$");
    
    }

}

// LOCALIZATION
define ('TIMEZONE', "America/Sao_Paulo");
define ('CODIFIC1', "pt_BR.iso-8859-1");
define ('CODIFIC2', "pt_BR.utf-8");

// ACTUAL FOLDER
$temp = $_SERVER['SCRIPT_NAME'];
$c = strlen($temp) - 1;
if (substr($temp, 0, 1) == '/') { $temp = substr($temp, -$c); }
$temp = substr($temp, 0, strpos($temp, '/'));
define ('ACTUALFOLDER', $temp);

// SYSTEM URL
@define ('SYSURL', $_SERVER['HTTP_REFERER']);

// BROWSER DETECT
if (isset($_SERVER['HTTP_USER_AGENT'])) {

    // INTERNET EXPLORER
    if (stripos($_SERVER['HTTP_USER_AGENT'],"MSIE") or stripos($_SERVER['HTTP_USER_AGENT'],"Trident")) { define("isIE", "1"); } else { define("isIE", "0"); }
 
    // FIREFOX
    if (stripos($_SERVER['HTTP_USER_AGENT'],"Firefox")) { define("isFIREFOX", "1"); } else { define("isFIREFOX", "0"); }

    // IOS DETECT
    if (stripos($_SERVER['HTTP_USER_AGENT'],"iPad") or stripos($_SERVER['HTTP_USER_AGENT'],"iPhone")) { define("isIOS", "1"); } else { define("isIOS", "0"); }

    // ANDROID DETECT
    if (stripos(strtolower($_SERVER['HTTP_USER_AGENT']),"android")) { define("isAndroid", "1"); } else { define("isAndroid", "0"); }

} else {

    define("IOS", "0");
    define("ANDROID", "0");

}

// PHONE DETECT
// (http://detectmobilebrowsers.com/)
$useragent=$_SERVER['HTTP_USER_AGENT'];
if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))) {
	define("ISPHONE", "1");	
} else {
	define("ISPHONE", "0");
}