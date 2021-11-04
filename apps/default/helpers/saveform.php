<?php

// SAVEFORM.PHP 1.0 (2019/05/08)

    // DEBUG
    //foreach(array_reverse($_GET) as $key=>$value) { echo "[" . $key . "] = [" . $value . "]<br />\n"; }; //exit;  

    $t = array(); $error = ''; $field = ''; $temp;

    foreach(array_reverse($_GET) as $key=>$value) {
    
        if (strlen($key) > 1) {
        
			// REMOVE '
            $value = str_replace("'", "´", $value);
            
            if (is_array($value)) {
            
				// ARRAY VALUE
                $t[$key] = "#". implode("#", $value) . "#";
                
            } else {
            
                switch ($key) {                    
                    
                    // ----------- MOBILITY -----------
					
					case "CustomerBirthDate":
						if ($value == '') {
							$temp = explode("/", $value);
							if (strlen($temp[0]) == 1) { $temp[0] = "0" . $temp[0]; }
							if (strlen($temp[1]) == 1) { $temp[1] = "0" . $temp[1]; }
							$value = $temp[2] . "-" . $temp[1] . "-" . $temp[0];
							$t[$key] = $value;
						}
						break;
						
					case "StartTime":
					case "EndTime":
						if ($value == '') {
							$temp = explode("/", left($value, 10));
							if (strlen($temp[0]) == 1) { $temp[0] = "0" . $temp[0]; }
							if (strlen($temp[1]) == 1) { $temp[1] = "0" . $temp[1]; }
							$value = $temp[2] . "-" . $temp[1] . "-" . $temp[0] . " " . right($value, 8);
							$t[$key] = $value;
						}
						break;
					
                    case "CustomerName":
                                              if ($value == '') { $error = translate('TheField') . " \"" . translate($key) . "\" " . translate('IsRequired'); $field = $key; } else { $t[$key] = strtoupper($value); } 
                                break;
                                
                                case "SocketName":
                                              if ($value == '') { $error = translate('TheField') . " \"" . translate($key) . "\" " . translate('IsRequired'); $field = $key; } else { $t[$key] = strtoupper($value); } 
                                break;

                    // ----------- /MOBILITY -----------
                    
					// MORE CASES GOES HERE...				
   
                    default: $t[$key] = $value; break;
                } 
            }
        }    
    }
   
    // DEBUG
    //print_r($t); exit;
	//foreach($t as $key=>$value) { echo "[" . $key . "] = [" . $value . "]<br />\n"; }; //exit;