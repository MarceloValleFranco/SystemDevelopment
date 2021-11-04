<?php

// TABLEBODY.PHP 1.0 (2019/01/30)
echo "							<tbody>\n";

// RECORDS LOOP
foreach ($d as $r) {

    echo "								<tr class='table-tr' id='" . $r[$columns[0]['COLUMN_NAME']] . "#" . $r[$columns[2]['COLUMN_NAME']] . "#" . $origin . "#" . $appID . "'>\n";

    for ($f = 1; $f <= count($columns) - 1; $f ++) {

        switch ($columns[$f]['COLUMN_NAME']) {

            // NO SHOW
            case "CompanyID":
            case "CustomerAddress":
            case "CustomerCity":
            case "CustomerState":
            case "CustomerPassword":
            case "CustomerPostalCode":
            case "CustomerNameOnCard":
            case "CustomerCardNumber":
            case "CustomerCardValidate":
            case "CustomerCardCVV":
            case "ItemColumns":
            case "ItemLocations":
            case "ItemDescription":
            case "ItemOrder":
            case "ItemScript":
            case "ItemScriptFile":
            case "ItemSignature":
            case "ItemTable":
            case "ItemURL":
            case "ItemLocations":
            case "Latitude":
            case "Longitude":
            case "LocationType":
            case "LocationGroupID":
            case "SaveOnMarkers":
            case "ChargerOperatorAddress":
            case "ChargerOperatorCity":
            case "ChargerOperatorState":
            case "ChargerOperatorPassword":
            case "ChargerOperatorPostalCode":
            case "StationOwnerAddress":
            case "StationOwnerCity":
            case "StationOwnerState":
            case "StationOwnerPassword":
            case "StationOwnerPostalCode":
            case "ChargerActive":
            case "ChargerManufacturer":
            case "ChargerModel":
            case "ChargerCapacity":
            case "ChargerAmount":
            case "SocketActive";    

                break;

            // NO SHOW ON MOBILE
            case "ItemOrder":
                echo "                                      <td class='table-td d-none d-sm-table-cell r-50'>" . $r[$columns[$f]['COLUMN_NAME']] . "°</td>\n";
                break;

            // SHOW ON
            case "ShowOnMobile":
            case "ShowOnTablet":
            case "ShowOnDesktop":
                $co = "danger";
                $no = translate("Hidden");
                $ic = "cross3";
                if ($r[$columns[$f]['COLUMN_NAME']] == '1') {
                    $co = "primary";
                    $no = translate("Visible");
                    $ic = "checkmark";
                }
                echo "                                      <td class='table-td d-none d-sm-table-cell r-120'><span title='" . $no . "' class='icon-" . $ic . " text-" . $co . " item-icon'></span></td>\n";
                break;

            // ITEM NAME
            case "ItemName":
                if (($appTable == "sysapps") || ($appTable == "sysmodules")) {
                    echo "                                      <td class='table-td'>" . $r[$columns[$f]['COLUMN_NAME']] . "</td>\n";
                }
                echo "                                      <td class='table-td'>" . translate($r[$columns[$f]['COLUMN_NAME']], 0) . "</td>\n";
                break;

            // ITEM ACTIVE
            case "CompanyActive":
            case "ItemActive":
                $co = "danger";
                $no = translate("Inactive");
                if ($r[$columns[$f]['COLUMN_NAME']] == '1') {
                    $co = "success";
                    $no = translate("Active");
                }
                echo "                                      <td class='table-td r-50'><span title='" . $no . "' class='badge badge-" . $co . " text-uppercase w-50'>" . left($no, 1) . "</span></td>\n";
                break;

            // ITEM ICON
            case "ItemIcon":
                echo "                                      <td class='table-td l-50'><span class='icon-" . $r[$columns[$f]['COLUMN_NAME']] . "'></span></td>\n";
                break;

            // APP GROUP ID
            case "AppGroupID":
                echo "                                      <td class='table-td'>" . translate(getAppGroupName($r["AppGroupID"])) . "</td>\n";
                break;

            // ACTIVE
            case "AppActive":
                switch ($r["AppActive"]) {
                    case "0":
                        $c = "danger";
                        $n = translate("Inactive");
                        break;
                    case "2":
                        $c = "warning";
                        $n = translate("Disabled");
                        break;
                    default:
                        $c = "success";
                        $n = translate("Active");
                        break;
                }
                echo "                                      <td class='table-td r-50'><span title='" . $n . "' class='badge badge-" . $c . " text-uppercase w-50'>" . left($n, 1) . "</span></td>\n";
                break;

            // COMPANY CODE
            case "CompanyCode":
                echo "                                      <td class='table-td d-none d-sm-table-cell l-200'>" . $r[$columns[$f]['COLUMN_NAME']] . "</span></td>\n";
                break;

            // ITEM STATUS
            case "ItemStatus":
                switch ($r["ItemStatus"]) {
                    case "0":
                        $c = "danger";
                        $n = translate("Inactive");
                        break;
                    case "2":
                        $c = "warning";
                        $n = translate("Disabled");
                        break;
                    default:
                        $c = "success";
                        $n = translate("Active");
                        break;
                }
                echo "                                      <td class='table-td r-50'><span title='" . $n . "' class='badge badge-" . $c . " text-uppercase w-50'>" . left($n, 1) . "</span></td>\n";
                break;

            // MOBILITY -----------------

            case "ChargerActive":
                switch ($r["ChargerActive"]) {
                    case "0":
                        $c = "danger";
                        $n = translate("Inactive");
                        break;
                    default:
                        $c = "success";
                        $n = translate("Active");
                        break;
                }
                echo "                                      <td class='table-td r-50'><span title='" . $n . "' class='badge badge-" . $c . " text-uppercase w-50'>" . left($n, 1) . "</span></td>\n";
                break;

            case "ChargerStatus":
                switch ($r["ChargerStatus"]) {
                    case "1":
                        $n = translate("ReadyToUse-Green");
                        $a = "1";
                        break;
                    case "2":
                        $n = translate("Charging-Blue");
                        $a = "2";
                        break;
                    case "3":
                        $n = translate("ScheduledCharge-DarkBlue");
                        $a = "3";
                        break;
                    case "4":
                        $n = translate("EmergencyButton-Yellow");
                        $a = "4";
                        break;
                    case "5":
                        $n = translate("InternalProblem-Orange");
                        $a = "5";
                        break;
                    case "6":
                        $n = translate("ChargerOff-Grey");
                        $a = "6";
                        break;
                    case "7":
                        $n = translate("ChargerUnknow-DarkGrey");
                        $a = "7";
                        break;
                    case "8":
                        $n = translate("ChargerManut-DarkGrey");
                        $a = "8";
                        break;
                }
                echo "                                      <td class='table-td l-50 text-right'><img src='assets/images/chargers/" . $a . ".png' alt='" . $n . "' title='" . $n . "' class='status-icon' /></td>\n";

                // CHARGER SOCKET STATUS
                echo "                                      <td class='table-td r-150'>";
                $d = select("*", "appsockets", "where ChargerID=" . $r["ChargerID"]);
                if ($d) {
                    foreach ($d as $s) {
                        switch ($s["SocketStatus"]) {
                            case "0":
                                $c = "danger";
                                $i = "cancel-square2";
                                $n = translate("Inactive");
                                break;
                            case "2":
                                $c = "warning";
                                $i = "warning2";
                                $n = translate("MalFunction");
                                break;
                            default:
                                $c = "success";
                                $i = "checkmark-circle";
                                $n = translate("Active");
                                break;
                        }
                        echo "<span class='text-" . $c . "' title='" . $s["SocketName"] . ": " . $n . "'><i class='icon-" . $i . " icon-size-20'></i>&nbsp;</span>";
                    }
                }
                echo "                                      </td>\n";

                break;

            case "ChargerID":
                echo "<td class='table-td'>";
                $d = select("*", "appchargers", "where ChargerID=" . $r["ChargerID"]);

                if ($d) {
                    echo $d[0]["ChargerName"];
                }
                echo "</span></td>\n";

                echo "<td class='table-td'>";
                $dd = select("*", "appstations", "where StationID=" . $d[0]["StationID"]);

                if ($dd) {
                    echo $dd[0]["StationName"];
                }
                echo "</span></td>\n";
                break;

            // CHARGER NAME
            case "ChargerName":
                echo "                                      <td class='table-td'>";
                $d = select("*", "appchargers", "where ChargerID=" . $r["ChargerID"]);
                if ($d) {
                    echo "Cód.:" . $d[0]["ChargerID"] . " | " . $d[0]["ChargerName"];
                }
                echo "</span></td>\n";
                break;

            case "CustomerActive":
                switch ($r["CustomerActive"]) {
                    case "0":
                        $c = "danger";
                        $n = translate("Inactive");
                        break;
                    default:
                        $c = "success";
                        $n = translate("Active");
                        break;
                }
                echo "                                      <td class='table-td r-50'><span title='" . $n . "' class='badge badge-" . $c . " text-uppercase w-50'>" . left($n, 1) . "</span></td>\n";
                break;

            case "CustomerID":
                echo "                                      <td class='table-td'>";
                $d = select("*", "appcustomers", "where CustomerID=" . $r["CustomerID"]);
                if ($d) {
                    echo $d[0]["CustomerName"] . " (" . $d[0]["CustomerCpf"] . ")";
                }
                echo "</span></td>\n";
                break;

            case "CustomerType":
                $sType = "Station";
                if ($r[$columns[$f]['COLUMN_NAME']] == "C") {
                    $sType = "Customer";
                }
                echo "                                      <td class='table-td l-150'>" . translate($sType) . "</td>\n";
                break;

            case "ServiceStatus":
                switch ($r["ServiceStatus"]) {
                    case "1":
                        $c = "secondary";
                        $i = "watch2";
                        $n = translate("Waiting");
                        break;
                    case "2":
                        $c = "primary";
                        $i = "battery-charging";
                        $n = translate("Charging");
                        break;
                    case "3":
                        $c = "success";
                        $i = "battery-6";
                        $n = translate("Completed");
                        break;
                    case "4":
                        $c = "danger";
                        $i = "warning2";
                        $n = translate("Interrupted");
                        break;
                    case "5":
                        $c = "success";
                        $i = "coin-dollar";
                        $n = translate("Paid");
                        break;
                    case "6":
                        $c = "danger";
                        $i = "blocked";
                        $n = translate("Unpaid");
                        break;
                    case "7":
                        $c = "success";
                        $i = "checkmark4";
                        $n = translate("FinalizedWithSuccess");
                        break;
                    case "8":
                        $c = "danger";
                        $i = "notification2";
                        $n = translate("FinalizedWithoutSuccess");
                        break;
                    // default: $c = "success"; $i = "watch2"; $n = translate("Waiting"); break;
                }
                echo "                                      <td class='table-td r-50 text-" . $c . "' title='" . $n . "'><i class='icon-" . $i . " icon-size-20'></i></td>\n";
                break;

            case "ServicePrice":
            case "SocketPrice":
                $price = translate("NoDefined");
                if ($r[$columns[$f]['COLUMN_NAME']] != '') {
                    $price = number_format(($r[$columns[$f]['COLUMN_NAME']] / 100), 2, ",", ".");
                }
                echo "                                      <td class='table-td r-100'>R$ " . $price . "</td>\n";
                break;

            case "RfidActive":
            case "ChargerOperatorActive":
            case "StationOwnerActive":
            case "StationActive":
            case "CustomerActive":
                switch ($r[$columns[$f]['COLUMN_NAME']]) {
                    case "0":
                        $c = "danger";
                        $n = translate("Inactive");
                        break;
                    default:
                        $c = "success";
                        $n = translate("Active");
                        break;
                }
                echo "                                      <td class='table-td r-50'><span title='" . $n . "' class='badge badge-" . $c . " text-uppercase w-50'>" . left($n, 1) . "</span></td>\n";
                break;

            case "EndTime":
                $start_time = new DateTime($r["StartTime"]);
                $end_time = new DateTime($r["EndTime"]);
                $total_time = $start_time->diff($end_time);
                echo "                                      <td class='table-td'>" . $r["EndTime"] . "</td>\n";
                echo "                                      <td class='table-td'>" . str_pad($total_time->h, 2, '0', STR_PAD_LEFT) . ":" . str_pad($total_time->i, 2, '0', STR_PAD_LEFT) . ":" . str_pad($total_time->s, 2, '0', STR_PAD_LEFT) . "</td>\n";
                break;
				
			case "MeterStart":
                $total_consumption = round(($r["MeterStop"] - $r["MeterStart"])/1000, 2);
                echo "                                      <td class='table-td'>" . $total_consumption . " kW</td>\n";
                break;

            // SOCKET ID
            case "SocketID":
                echo "                                      <td class='table-td'>";
                $d = select("*", "appsockets", "where SocketID=" . $r["SocketID"]);
                if ($d) {
                    echo $d[0]["SocketName"];
                }
                echo "</span></td>\n";
                break;
                
                // SOCKET GRID
            case "SocketGrid":
                echo "                                      <td class='table-td'>";
                $d = select("*", "appsocketsgrid", "where SocketGridID=" . $r["SocketGrid"]);
                if ($d) {
                    echo $d[0]["SocketGrid"];
                }
                echo "</span></td>\n";
                break;
                
                // SOCKET PHASE
            case "SocketPhase":
                echo "                                      <td class='table-td'>";
                $d = select("*", "appsocketsphase", "where SocketPhaseID=" . $r["SocketGrid"]);
                if ($d) {
                    echo $d[0]["SocketPhase"];
                }
                echo "</span></td>\n";
                break;
                
                // SOCKET AMP
            case "SocketAmp":
                echo "                                      <td class='table-td'>";
                $d = select("*", "appsocketsamp", "where SocketAmpID=" . $r["SocketAmp"]);
                if ($d) {
                    echo $d[0]["SocketAmp"];
                }
                echo "</span></td>\n";
                break;
                
                // SOCKET POWER
            case "SocketPower":
                echo "                                      <td class='table-td'>";
                $d = select("*", "appsocketspower", "where SocketPowerID=" . $r["SocketPower"]);
                if ($d) {
                    echo $d[0]["SocketPower"];
                }
                echo "</span></td>\n";
                break;
                
                // SOCKET CABLE TYPE
            case "SocketCableType":
                echo "                                      <td class='table-td'>";
                $d = select("*", "appsocketscabletype", "where SocketCableTypeID=" . $r["SocketCableType"]);
                if ($d) {
                    echo $d[0]["SocketCableType"];
                }
                echo "</span></td>\n";
                break;
                
                // SOCKET CABLE LENGHT
            case "SocketCableLenght":
                echo "                                      <td class='table-td'>";
                $d = select("*", "appsocketscablelenght", "where SocketCableLenghtID=" . $r["SocketCableLenght"]);
                if ($d) {
                    echo $d[0]["SocketCableLenght"];
                }
                echo "</span></td>\n";
                break;
                
                // SOCKET TYPE
            case "SocketType":
                echo "                                      <td class='table-td'>";
                $d = select("*", "appsocketstype", "where SocketTypeID=" . $r["SocketType"]);
                if ($d) {
                    echo $d[0]["SocketType"];
                }
                echo "</span></td>\n";
                break;

            case "SocketStatus":
                echo "                                      <td class='table-td'>";
                $d = select("*", "appsocketstatus", "where SocketStatusNum=" . $r["SocketType"]);
                if ($d) {
                    echo $d[0]["SocketStatusName"];
                }
                if ($r["SocketActive"] == '0') {
                    $c = "grey";
                    $i = "cancel-square2";
                    $n = translate("Inactive");
                } else {
                    $c = "success";
                    $i = "checkmark-circle";
                    $n = translate("Active");
                }
                echo "                                      <td class='table-td r-50 text-" . $c . "' title='" . $n . "'><i class='icon-" . $i . " icon-size-20'></i></td>\n";
                break;

            case "SocketType":
                echo " <td class='table-td'>";
                $d = select("*", "appsocketstype", "where SocketTypeID=" . $r["SocketType"]);
                if ($d) {
                    echo $d[0]["SocketType"];
                }
                echo "</span></td>\n";
                break;

            case "StationStatus":
                switch ($r["StationStatus"]) {
                    case "0":
                        $c = "danger";
                        $i = "cancel-square2";
                        $n = translate("Inactive");
                        break;
                    case "2":
                        $c = "warning";
                        $i = "warning2";
                        $n = translate("MalFunction");
                        break;
                    default:
                        $c = "success";
                        $i = "checkmark-circle";
                        $n = translate("Active");
                        break;
                }
                if ($r["StationActive"] == '0') {
                    $c = "grey";
                    $i = "cancel-square2";
                    $n = translate("Inactive");
                }
                echo "                                      <td class='table-td r-50 text-" . $c . "' title='" . $n . "'><i class='icon-" . $i . " icon-size-20'></i></td>\n";

                // STATION CHARGER STATUS
                echo "                                      <td class='table-td r-150'>";
                $d = select("*", "appchargers", "where StationID=" . $r["StationID"]);
                if ($d) {
                    foreach ($d as $s) {
                        switch ($s["ChargerStatus"]) {
                            case "1":
                                $n = translate("ReadyToUse-Green");
                                $a = "1";
                                break;
                            case "2":
                                $n = translate("Charging-Blue");
                                $a = "2";
                                break;
                            case "3":
                                $n = translate("ScheduledCharge-DarkBlue");
                                $a = "3";
                                break;
                            case "4":
                                $n = translate("EmergencyButton-Yellow");
                                $a = "4";
                                break;
                            case "5":
                                $n = translate("InternalProblem-Orange");
                                $a = "5";
                                break;
                            case "6":
                                $n = translate("ChargerOff-Grey");
                                $a = "6";
                                break;
                            case "7":
                                $n = translate("ChargerUnknow-DarkGrey");
                                $a = "7";
                                break;
                            case "8":
                                $n = translate("ChargerManut-DarkGrey");
                                $a = "8";
                                break;
                        }
                        echo "<img src='assets/images/chargers/" . $a . ".png' alt='" . $s["ChargerName"] . ": " . $n . "' title='" . $s["ChargerName"] . ": " . $n . "' class='status-icon' />";
                    }
                }
                echo "                                      </td>\n";
                break;

            case "SocketVoltage":
                switch ($r["SocketVoltage"]) {
                    case "220":
                        $c = "warning";
                    default:
                        $c = "info";
                }
                echo "                                      <td class='table-td r-50'><span title='" . $r[$columns[$f]['COLUMN_NAME']] . "V' class='badge badge-" . $c . " text-uppercase w-100'>" . $r[$columns[$f]['COLUMN_NAME']] . "V</span></td>\n";
                break;

            case "StationID":
                echo "                                      <td class='table-td'>";
                $d = select("*", "appstations", "where StationID=" . $r["StationID"]);
                if ($d) {
                    echo $d[0]["StationName"];
                }
                echo "</span></td>\n";
                break;

            // MOBILITY -----------------

            case "CustomerPasswordFlag":
			case "MeterStop":
                break;

            // DEFAULT
            default:
                echo "                                      <td class='table-td'>" . $r[$columns[$f]['COLUMN_NAME']] . "</td>\n";
                break;
        }
    }

    echo "								</tr>\n";
}

echo "							</tbody>\n";