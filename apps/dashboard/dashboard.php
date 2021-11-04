<?php

// DASHBOARD.PHP 1.1 (2019/05/02)

require "../../core/config.php";
require "../../core/functions.php";
require "../../core/security.php";

// DATABASE OPEN
openDB();

	// PAGE HEADER
	pageHeader("dashboard");
	
		echo "	<body>\n";
		
		// PLUGINS
		echo addCode("../../core/plugins/forms/selects/select2.min.js");		
		
		echo "		<div class='page-content'>\n";
		
		echo "			<div class='content-wrapper'>\n";
		
		// PAGE HEADER
		echo "				<div class='page-header'>\n";
		
		echo "					<div class='breadcrumb-table-around'>\n";
		echo "						<table class='breadcrumb-table'>\n";
		echo "							<tr>\n";
		echo "								<td class='breadcrumb-td-title'>" . translate("CapacitorsBank") . "</td>\n";
		echo "								<td class='breadcrumb-td-divider'></td>\n";
		echo "								<td class='breadcrumb-td-active'>" . translate("Dashboard") . "</td>\n";
		echo "								<td class='breadcrumb-td-divider'></td>\n";
		echo "								<td class='breadcrumb-td' id='datasend.php'>" . translate("EntryForm") . " <a href='../default/global1.html'>.</a></td>\n";
		echo "								<td>\n";
		
        echo "									<form id='mainForm' method='get'>\n";
        
        $stateId = "25";
		if (isset($_GET["StateID"])) { $stateId = $_GET["StateID"]; } 
        echo "										<input type='hidden' name='StateID' value='" . $stateId . "' />\n";
        
		echo "									    <table class='table-selectors'>\n";
		echo "										    <tr>\n";

		// CAPEX FILTER
		$capex = "50000";
		if (isset($_GET["Capex"])) { $capex = $_GET["Capex"]; }
		echo "												<td class='td-caption'>Capex</td>\n";
		echo "												<td class='td-combo'>\n";
		echo "													<select class='form-control form-control-select2 select2combo' name='Capex' onchange=\"parent.block('body', '32', '#000', '8000'); document.getElementById('mainForm').submit();\">\n";
		echo "														<option value='300000'"; if($capex == "300000") { echo " selected"; } echo ">> 300.000 R$</option>\n";	
		echo "														<option value='150000'"; if($capex == "150000") { echo " selected"; } echo ">> 150.000 R$</option>\n";
		echo "														<option value='100000'"; if($capex == "100000") { echo " selected"; } echo ">> 100.000 R$</option>\n";
		echo "														<option value='50000'"; if($capex == "50000") { echo " selected"; } echo ">> 50.000 R$</option>\n";
		//echo "														<option value='a'"; if($capex == "a") { echo " selected"; } echo ">" . translate("All") . " </option>\n";
		echo "													</select>\n";
		echo "												</td>\n";
		
		// PAYBACK FILTER
		$payback = "10";
		if (isset($_GET["Payback"])) { $payback = $_GET["Payback"]; }
		echo "												<td class='td-caption'>Payback</td>\n";
		echo "												<td class='td-combo'>\n";
		echo "													<select class='form-control form-control-select2 select2combo' name='Payback' onchange=\"parent.block('body', '32', '#000', '8000'); document.getElementById('mainForm').submit();\">\n";
		echo "														<option value='1'"; if($payback == "1") { echo " selected"; } echo ">< 1 " . translate("Year") . "</option>\n";	
		echo "														<option value='2'"; if($payback == "2") { echo " selected"; } echo ">< 2 " . translate("Years") . "</option>\n";
		echo "														<option value='3'"; if($payback == "3") { echo " selected"; } echo ">< 3 " . translate("Years") . "</option>\n";
		echo "														<option value='4'"; if($payback == "4") { echo " selected"; } echo ">< 4 " . translate("Years") . "</option>\n";
		echo "														<option value='5'"; if($payback == "5") { echo " selected"; } echo ">< 5 " . translate("Years") . "</option>\n";
		echo "														<option value='6'"; if($payback == "6") { echo " selected"; } echo ">< 6 " . translate("Years") . "</option>\n";
		echo "														<option value='7'"; if($payback == "7") { echo " selected"; } echo ">< 7 " . translate("Years") . "</option>\n";
		echo "														<option value='8'"; if($payback == "8") { echo " selected"; } echo ">< 8 " . translate("Years") . "</option>\n";
		echo "														<option value='9'"; if($payback == "9") { echo " selected"; } echo ">< 9 " . translate("Years") . "</option>\n";
		echo "														<option value='10'"; if($payback == "10") { echo " selected"; } echo ">< 10 " . translate("Years") . " </option>\n";
		//echo "														<option value='a'"; if($payback == "a") { echo " selected"; } echo ">" . translate("All") . " </option>\n";
		echo "													</select>\n";
		echo "												</td>\n";
	
		// EXPORT
		//echo "												<td class='td-button'>\n";
		//echo "												<button type='button' class='btn btn-info bg-grey send-button'>" . translate("ExportConsolidated") . "</button>\n"; 
		//echo "													<a href='#' class='list-icons-item dropdown-toggle' data-toggle='dropdown'><i class='icon-file-text2 mr-2'></i>" . translate("ExportConsolidated") . "</a>\n";
		//echo "													<div class='dropdown-menu dropdown-menu-right'>\n";
		//echo "													<a href='#' class='dropdown-item'><i class='icon-printer'></i> " . translate("Print") . "</a>\n";
		//echo "													<a href='#' class='dropdown-item'><i class='icon-file-pdf'></i> " . translate("ExportPDF") . "</a>\n";
		//echo "														<a href='autoexcel.php?i=XXXXX' class='dropdown-item'><i class='icon-file-excel'></i> " . translate("ExportExcel") . "</a>\n";
		//echo "													<div class='dropdown-divider'></div>\n";
		//echo "												<a href='#' class='dropdown-item'><i class='icon-gear'></i> 123... 4</a>\n";
		//echo "													</div>\n";
		
		echo "												</td>\n";
		
		echo "										    </tr>\n";
		echo "									    </table>\n";
        
        echo "									</form>\n";

		echo "								</td>\n";
		echo "							</tr>\n";
		echo "						</table>\n";
		echo "					</div>\n";

		echo "				</div>\n"; // / page-header	
			
		// MAIN CONTENT
		echo "				<div class='content'>\n";
		
			if (!isset($_SESSION["UF"])) { $_SESSION["UF"] = "SP"; }
		
			// GET DATA
			$curl = curl_init();
                           
                $url = "https://engiedigital.com.br/api/bank-capacitor/states/proposal-status/metrics?payback=" . $payback . "&capex=" . $capex;
                
                // DEBUG
                echo "				    <!-- " . $url . " -->\n";

				curl_setopt_array($curl, array(
					CURLOPT_URL => $url,
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_ENCODING => "",
					CURLOPT_MAXREDIRS => 10,
					CURLOPT_TIMEOUT => 30,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST => "GET",
					CURLOPT_POSTFIELDS => "",
					CURLOPT_HTTPHEADER => array(
						"Authorization: Basic RGlnaXRhbDpFODg0V3MyYmFq",
						"Postman-Token: 83b46791-cea9-4961-a63e-62fcae4de47d",
						"cache-control: no-cache"
					),
					CURLOPT_SSL_VERIFYPEER => false
				));

				$response = curl_exec($curl); $err = curl_error($curl);
				
				$state = ''; if (isset($_GET['s'])) { $state = "/" . strtoupper($_GET['s']); $_SESSION["UF"] = strtoupper($_GET['s']); }
				
				curl_setopt_array($curl, array(
					CURLOPT_URL => "https://engiedigital.com.br/api/bank-capacitor/history/all" . $state,
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_ENCODING => "",
					CURLOPT_MAXREDIRS => 10,
					CURLOPT_TIMEOUT => 30,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST => "GET",
					CURLOPT_POSTFIELDS => "",
					CURLOPT_HTTPHEADER => array(
						"Authorization: Basic RGlnaXRhbDpFODg0V3MyYmFq",
						"Postman-Token: 30c78f56-36fb-4fe1-a7f2-f3d1496ffded",
						"cache-control: no-cache"
					),
					CURLOPT_SSL_VERIFYPEER => false
				));

				$historic = curl_exec($curl); $err = curl_error($curl);

			curl_close($curl);
					
			// CHECK FOR ERRORS		
			if (json_decode($response) == '') { getError("ErrorGettingData", "Metrics"); exit; }		
			if (json_decode($historic) == '') { getError("ErrorGettingData", "Historic"); exit; }

			if ($err) {

				// ERROR GETTING DATA
				getError("ErrorGettingData", $err);

			} else {

				// WORK WITH DATA
				$c = json_decode($response); $c = object2array($c);	
				
				// DEBUG
				//print_r($c);
				
				$brnaun = 0;
				$breeun = 0;
				$bracun = 0;
				$brreun = 0;				
				$brnaca = 0;
				$breeca = 0;
				$bracca = 0;
				$brreca = 0;				

				$x = array();
				
				// NORTE	
				
				// ac 
				$ac = @$c['AC']['NotPresented']['capex'] + @$c['AC']['Waiting']['capex'];
				$x[0]['sigla'] = 'ac';
				$x[0]['capex'] = $ac;
				$x[0]['color'] = '#6ebeaa';
				$brnaun = $brnaun + @$c['AC']['NotPresented']['units'];
				$breeun = $breeun + @$c['AC']['Waiting']['units'];
				$bracun = $bracun + @$c['AC']['Accepted']['units'];
				$brreun = $brreun + @$c['AC']['Declined']['units'];				
				$brnaca = $brnaca + @$c['AC']['NotPresented']['capex'];
				$breeca = $breeca + @$c['AC']['Waiting']['capex'];
				$bracca = $bracca + @$c['AC']['Accepted']['capex'];
				$brreca = $brreca + @$c['AC']['Declined']['capex'];
				
				// am 
				$am = @$c['AM']['NotPresented']['capex'] + @$c['AM']['Waiting']['capex'];
				$x[1]['sigla'] = 'am';
				$x[1]['capex'] = $am;
				$x[1]['color'] = '#6ebeaa';
				$brnaun = $brnaun + @$c['AM']['NotPresented']['units'];
				$breeun = $breeun + @$c['AM']['Waiting']['units'];
				$bracun = $bracun + @$c['AM']['Accepted']['units'];
				$brreun = $brreun + @$c['AM']['Declined']['units'];				
				$brnaca = $brnaca + @$c['AM']['NotPresented']['capex'];
				$breeca = $breeca + @$c['AM']['Waiting']['capex'];
				$bracca = $bracca + @$c['AM']['Accepted']['capex'];
				$brreca = $brreca + @$c['AM']['Declined']['capex'];
				
				// ap 
				$ap = @$c['AP']['NotPresented']['capex'] + @$c['AP']['Waiting']['capex'];
				$x[2]['sigla'] = 'ap';
				$x[2]['capex'] = $ap;
				$x[2]['color'] = '#6ebeaa';
				$brnaun = $brnaun + @$c['AP']['NotPresented']['units'];
				$breeun = $breeun + @$c['AP']['Waiting']['units'];
				$bracun = $bracun + @$c['AP']['Accepted']['units'];
				$brreun = $brreun + @$c['AP']['Declined']['units'];				
				$brnaca = $brnaca + @$c['AP']['NotPresented']['capex'];
				$breeca = $breeca + @$c['AP']['Waiting']['capex'];
				$bracca = $bracca + @$c['AP']['Accepted']['capex'];
				$brreca = $brreca + @$c['AP']['Declined']['capex'];

				// pa 
				$pa = @$c['PA']['NotPresented']['capex'] + @$c['PA']['Waiting']['capex'];
				$x[3]['sigla'] = 'pa';
				$x[3]['capex'] = $pa;
				$x[3]['color'] = '#6ebeaa';
				$brnaun = $brnaun + @$c['PA']['NotPresented']['units'];
				$breeun = $breeun + @$c['PA']['Waiting']['units'];
				$bracun = $bracun + @$c['PA']['Accepted']['units'];
				$brreun = $brreun + @$c['PA']['Declined']['units'];				
				$brnaca = $brnaca + @$c['PA']['NotPresented']['capex'];
				$breeca = $breeca + @$c['PA']['Waiting']['capex'];
				$bracca = $bracca + @$c['PA']['Accepted']['capex'];
				$brreca = $brreca + @$c['PA']['Declined']['capex'];
				
				// ro 
				$ro = @$c['RO']['NotPresented']['capex'] + @$c['RO']['Waiting']['capex'];
				$x[4]['sigla'] = 'ro';
				$x[4]['capex'] = $ro;
				$x[4]['color'] = '#6ebeaa';
				$brnaun = $brnaun + @$c['RO']['NotPresented']['units'];
				$breeun = $breeun + @$c['RO']['Waiting']['units'];
				$bracun = $bracun + @$c['RO']['Accepted']['units'];
				$brreun = $brreun + @$c['RO']['Declined']['units'];				
				$brnaca = $brnaca + @$c['RO']['NotPresented']['capex'];
				$breeca = $breeca + @$c['RO']['Waiting']['capex'];
				$bracca = $bracca + @$c['RO']['Accepted']['capex'];
				$brreca = $brreca + @$c['RO']['Declined']['capex'];
				
				
				// rr
				$rr = @$c['RR']['NotPresented']['capex'] + @$c['RR']['Waiting']['capex'];
				$x[5]['sigla'] = 'rr';
				$x[5]['capex'] = $rr;
				$x[5]['color'] = '#6ebeaa';
				$brnaun = $brnaun + @$c['RR']['NotPresented']['units'];
				$breeun = $breeun + @$c['RR']['Waiting']['units'];
				$bracun = $bracun + @$c['RR']['Accepted']['units'];
				$brreun = $brreun + @$c['RR']['Declined']['units'];				
				$brnaca = $brnaca + @$c['RR']['NotPresented']['capex'];
				$breeca = $breeca + @$c['RR']['Waiting']['capex'];
				$bracca = $bracca + @$c['RR']['Accepted']['capex'];
				$brreca = $brreca + @$c['RR']['Declined']['capex'];
				
				// to
				$to = @$c['TO']['NotPresented']['capex'] + @$c['TO']['Waiting']['capex'];
				$x[6]['sigla'] = 'to';
				$x[6]['capex'] = $to;
				$x[6]['color'] = '#6ebeaa';
				$brnaun = $brnaun + @$c['TO']['NotPresented']['units'];
				$breeun = $breeun + @$c['TO']['Waiting']['units'];
				$bracun = $bracun + @$c['TO']['Accepted']['units'];
				$brreun = $brreun + @$c['TO']['Declined']['units'];				
				$brnaca = $brnaca + @$c['TO']['NotPresented']['capex'];
				$breeca = $breeca + @$c['TO']['Waiting']['capex'];
				$bracca = $bracca + @$c['TO']['Accepted']['capex'];
				$brreca = $brreca + @$c['TO']['Declined']['capex'];
				
				$norte = @$c['AC']['NotPresented']['units'] + @$c['AC']['Waiting']['units'] + @$c['AM']['NotPresented']['units'] + @$c['AM']['Waiting']['units'] + @$c['AP']['NotPresented']['units'] + @$c['AP']['Waiting']['units'] + @$c['PA']['NotPresented']['units'] + @$c['PA']['Waiting']['units'] + @$c['RO']['NotPresented']['units'] + @$c['RO']['Waiting']['units'] + @$c['RR']['NotPresented']['units'] + @$c['RR']['Waiting']['units'] + @$c['TO']['NotPresented']['units'] + @$c['TO']['Waiting']['units'];
				$norteCapex = @$c['AC']['NotPresented']['capex'] + @$c['AC']['Waiting']['capex'] + @$c['AM']['NotPresented']['capex'] + @$c['AM']['Waiting']['capex'] + @$c['AP']['NotPresented']['capex'] + @$c['AP']['Waiting']['capex'] + @$c['PA']['NotPresented']['capex'] + @$c['PA']['Waiting']['capex'] + @$c['RO']['NotPresented']['capex'] + @$c['RO']['Waiting']['capex'] + @$c['RR']['NotPresented']['capex'] + @$c['RR']['Waiting']['capex'] + @$c['TO']['NotPresented']['capex'] + @$c['TO']['Waiting']['capex'];
				
				// NORDESTE

				// al:
				$al = @$c['AL']['NotPresented']['capex'] + @$c['AL']['Waiting']['capex'];
				$x[7]['sigla'] = 'al';
				$x[7]['capex'] = $al;
				$x[7]['color'] = '#6ebeaa';
				$brnaun = $brnaun + @$c['AL']['NotPresented']['units'];
				$breeun = $breeun + @$c['AL']['Waiting']['units'];
				$bracun = $bracun + @$c['AL']['Accepted']['units'];
				$brreun = $brreun + @$c['AL']['Declined']['units'];				
				$brnaca = $brnaca + @$c['AL']['NotPresented']['capex'];
				$breeca = $breeca + @$c['AL']['Waiting']['capex'];
				$bracca = $bracca + @$c['AL']['Accepted']['capex'];
				$brreca = $brreca + @$c['AL']['Declined']['capex'];
				
				// ba: 
				$ba = @$c['BA']['NotPresented']['capex'] + @$c['BA']['Waiting']['capex'];
				$x[8]['sigla'] = 'ba';
				$x[8]['capex'] = $ba;
				$x[8]['color'] = '#6ebeaa';
				$brnaun = $brnaun + @$c['BA']['NotPresented']['units'];
				$breeun = $breeun + @$c['BA']['Waiting']['units'];
				$bracun = $bracun + @$c['BA']['Accepted']['units'];
				$brreun = $brreun + @$c['BA']['Declined']['units'];				
				$brnaca = $brnaca + @$c['BA']['NotPresented']['capex'];
				$breeca = $breeca + @$c['BA']['Waiting']['capex'];
				$bracca = $bracca + @$c['BA']['Accepted']['capex'];
				$brreca = $brreca + @$c['BA']['Declined']['capex'];
				
				// ce: 
				$ce = @$c['CE']['NotPresented']['capex'] + @$c['CE']['Waiting']['capex'];
				$x[9]['sigla'] = 'ce';
				$x[9]['capex'] = $ce;
				$x[9]['color'] = '#6ebeaa';
				$brnaun = $brnaun + @$c['CE']['NotPresented']['units'];
				$breeun = $breeun + @$c['CE']['Waiting']['units'];
				$bracun = $bracun + @$c['CE']['Accepted']['units'];
				$brreun = $brreun + @$c['CE']['Declined']['units'];				
				$brnaca = $brnaca + @$c['CE']['NotPresented']['capex'];
				$breeca = $breeca + @$c['CE']['Waiting']['capex'];
				$bracca = $bracca + @$c['CE']['Accepted']['capex'];
				$brreca = $brreca + @$c['CE']['Declined']['capex'];
				
				// ma: 
				$ma = @$c['MA']['NotPresented']['capex'] + @$c['MA']['Waiting']['capex'];
				$x[10]['sigla'] = 'ma';
				$x[10]['capex'] = $ma;
				$x[10]['color'] = '#6ebeaa';
				$brnaun = $brnaun + @$c['MA']['NotPresented']['units'];
				$breeun = $breeun + @$c['MA']['Waiting']['units'];
				$bracun = $bracun + @$c['MA']['Accepted']['units'];
				$brreun = $brreun + @$c['MA']['Declined']['units'];				
				$brnaca = $brnaca + @$c['MA']['NotPresented']['capex'];
				$breeca = $breeca + @$c['MA']['Waiting']['capex'];
				$bracca = $bracca + @$c['MA']['Accepted']['capex'];
				$brreca = $brreca + @$c['MA']['Declined']['capex'];
				
				// pb:
				$pb = @$c['PB']['NotPresented']['capex'] + @$c['PB']['Waiting']['capex'];
				$x[11]['sigla'] = 'pb';
				$x[11]['capex'] = $pb;
				$x[11]['color'] = '#6ebeaa';
				$brnaun = $brnaun + @$c['PB']['NotPresented']['units'];
				$breeun = $breeun + @$c['PB']['Waiting']['units'];
				$bracun = $bracun + @$c['PB']['Accepted']['units'];
				$brreun = $brreun + @$c['PB']['Declined']['units'];				
				$brnaca = $brnaca + @$c['PB']['NotPresented']['capex'];
				$breeca = $breeca + @$c['PB']['Waiting']['capex'];
				$bracca = $bracca + @$c['PB']['Accepted']['capex'];
				$brreca = $brreca + @$c['PB']['Declined']['capex'];
				
				// pe:
				$pe = @$c['PE']['NotPresented']['capex'] + @$c['PE']['Waiting']['capex'];
				$x[12]['sigla'] = 'pe';
				$x[12]['capex'] = $pe;
				$x[12]['color'] = '#6ebeaa';
				$brnaun = $brnaun + @$c['PE']['NotPresented']['units'];
				$breeun = $breeun + @$c['PE']['Waiting']['units'];
				$bracun = $bracun + @$c['PE']['Accepted']['units'];
				$brreun = $brreun + @$c['PE']['Declined']['units'];				
				$brnaca = $brnaca + @$c['PE']['NotPresented']['capex'];
				$breeca = $breeca + @$c['PE']['Waiting']['capex'];
				$bracca = $bracca + @$c['PE']['Accepted']['capex'];
				$brreca = $brreca + @$c['PE']['Declined']['capex'];
				
				// pi:
				$pi = @$c['PI']['NotPresented']['capex'] + @$c['PI']['Waiting']['capex'];
				$x[13]['sigla'] = 'pi';
				$x[13]['capex'] = $pi;
				$x[13]['color'] = '#6ebeaa';
				$brnaun = $brnaun + @$c['PI']['NotPresented']['units'];
				$breeun = $breeun + @$c['PI']['Waiting']['units'];
				$bracun = $bracun + @$c['PI']['Accepted']['units'];
				$brreun = $brreun + @$c['PI']['Declined']['units'];				
				$brnaca = $brnaca + @$c['PI']['NotPresented']['capex'];
				$breeca = $breeca + @$c['PI']['Waiting']['capex'];
				$bracca = $bracca + @$c['PI']['Accepted']['capex'];
				$brreca = $brreca + @$c['PI']['Declined']['capex'];
				
				// rn: 
				$rn = @$c['RN']['NotPresented']['capex'] + @$c['RN']['Waiting']['capex'];
				$x[14]['sigla'] = 'rn';
				$x[14]['capex'] = $rn;
				$x[14]['color'] = '#6ebeaa';
				$brnaun = $brnaun + @$c['RN']['NotPresented']['units'];
				$breeun = $breeun + @$c['RN']['Waiting']['units'];
				$bracun = $bracun + @$c['RN']['Accepted']['units'];
				$brreun = $brreun + @$c['RN']['Declined']['units'];				
				$brnaca = $brnaca + @$c['RN']['NotPresented']['capex'];
				$breeca = $breeca + @$c['RN']['Waiting']['capex'];
				$bracca = $bracca + @$c['RN']['Accepted']['capex'];
				$brreca = $brreca + @$c['RN']['Declined']['capex'];				
				
				// se:
				$se = @$c['SE']['NotPresented']['capex'] + @$c['SE']['Waiting']['capex'];
				$x[15]['sigla'] = 'se';
				$x[15]['capex'] = $se;
				$x[15]['color'] = '#6ebeaa';
				$brnaun = $brnaun + @$c['SE']['NotPresented']['units'];
				$breeun = $breeun + @$c['SE']['Waiting']['units'];
				$bracun = $bracun + @$c['SE']['Accepted']['units'];
				$brreun = $brreun + @$c['SE']['Declined']['units'];				
				$brnaca = $brnaca + @$c['SE']['NotPresented']['capex'];
				$breeca = $breeca + @$c['SE']['Waiting']['capex'];
				$bracca = $bracca + @$c['SE']['Accepted']['capex'];
				$brreca = $brreca + @$c['SE']['Declined']['capex'];
				
				$nordeste = @$c['AL']['NotPresented']['units'] + @$c['AL']['Waiting']['units'] + @$c['BA']['NotPresented']['units'] + @$c['BA']['Waiting']['units'] + @$c['CE']['NotPresented']['units'] + @$c['CE']['Waiting']['units'] + @$c['MA']['NotPresented']['units'] + @$c['MA']['Waiting']['units'] + @$c['PB']['NotPresented']['units'] + @$c['PB']['Waiting']['units'] + @$c['PE']['NotPresented']['units'] + @$c['PE']['Waiting']['units'] + @$c['PI']['NotPresented']['units'] + @$c['PI']['Waiting']['units'] + @$c['RN']['NotPresented']['units'] + @$c['RN']['Waiting']['units'] + @$c['SE']['NotPresented']['units'] + @$c['SE']['Waiting']['units'];
				$nordesteCapex = @$c['AL']['NotPresented']['capex'] + @$c['AL']['Waiting']['capex'] + @$c['BA']['NotPresented']['capex'] + @$c['BA']['Waiting']['capex'] + @$c['CE']['NotPresented']['capex'] + @$c['CE']['Waiting']['capex'] + @$c['MA']['NotPresented']['capex'] + @$c['MA']['Waiting']['capex'] + @$c['PB']['NotPresented']['capex'] + @$c['PB']['Waiting']['capex'] + @$c['PE']['NotPresented']['capex'] + @$c['PE']['Waiting']['capex'] + @$c['PI']['NotPresented']['capex'] + @$c['PI']['Waiting']['capex'] + @$c['RN']['NotPresented']['capex'] + @$c['RN']['Waiting']['capex'] + @$c['SE']['NotPresented']['capex'] + @$c['SE']['Waiting']['capex'];

				// CENTRO-OESTE 

				// df:
				$df = @$c['DF']['NotPresented']['capex'] + @$c['DF']['Waiting']['capex'];
				$x[16]['sigla'] = 'df';
				$x[16]['capex'] = $df;
				$x[16]['color'] = '#6ebeaa';
				$brnaun = $brnaun + @$c['DF']['NotPresented']['units'];
				$breeun = $breeun + @$c['DF']['Waiting']['units'];
				$bracun = $bracun + @$c['DF']['Accepted']['units'];
				$brreun = $brreun + @$c['DF']['Declined']['units'];				
				$brnaca = $brnaca + @$c['DF']['NotPresented']['capex'];
				$breeca = $breeca + @$c['DF']['Waiting']['capex'];
				$bracca = $bracca + @$c['DF']['Accepted']['capex'];
				$brreca = $brreca + @$c['DF']['Declined']['capex'];
				
				// go:
				$go = @$c['GO']['NotPresented']['capex'] + @$c['GO']['Waiting']['capex'];
				$x[17]['sigla'] = 'go';
				$x[17]['capex'] = $go;
				$x[17]['color'] = '#6ebeaa';
				$brnaun = $brnaun + @$c['GO']['NotPresented']['units'];
				$breeun = $breeun + @$c['GO']['Waiting']['units'];
				$bracun = $bracun + @$c['GO']['Accepted']['units'];
				$brreun = $brreun + @$c['GO']['Declined']['units'];				
				$brnaca = $brnaca + @$c['GO']['NotPresented']['capex'];
				$breeca = $breeca + @$c['GO']['Waiting']['capex'];
				$bracca = $bracca + @$c['GO']['Accepted']['capex'];
				$brreca = $brreca + @$c['GO']['Declined']['capex'];
				
				// ms:
				$ms = @$c['MS']['NotPresented']['capex'] + @$c['MS']['Waiting']['capex'];
				$x[18]['sigla'] = 'ms';
				$x[18]['capex'] = $ms;
				$x[18]['color'] = '#6ebeaa';
				$brnaun = $brnaun + @$c['MS']['NotPresented']['units'];
				$breeun = $breeun + @$c['MS']['Waiting']['units'];
				$bracun = $bracun + @$c['MS']['Accepted']['units'];
				$brreun = $brreun + @$c['MS']['Declined']['units'];				
				$brnaca = $brnaca + @$c['MS']['NotPresented']['capex'];
				$breeca = $breeca + @$c['MS']['Waiting']['capex'];
				$bracca = $bracca + @$c['MS']['Accepted']['capex'];
				$brreca = $brreca + @$c['MS']['Declined']['capex'];
				
				// mt:
				$mt = @$c['MT']['NotPresented']['capex'] + @$c['MT']['Waiting']['capex'];
				$x[19]['sigla'] = 'mt';
				$x[19]['capex'] = $mt;
				$x[19]['color'] = '#6ebeaa';
				$brnaun = $brnaun + @$c['MT']['NotPresented']['units'];
				$breeun = $breeun + @$c['MT']['Waiting']['units'];
				$bracun = $bracun + @$c['MT']['Accepted']['units'];
				$brreun = $brreun + @$c['MT']['Declined']['units'];				
				$brnaca = $brnaca + @$c['MT']['NotPresented']['capex'];
				$breeca = $breeca + @$c['MT']['Waiting']['capex'];
				$bracca = $bracca + @$c['MT']['Accepted']['capex'];
				$brreca = $brreca + @$c['MT']['Declined']['capex'];				
				
				$centro = @$c['DF']['NotPresented']['units'] + @$c['DF']['Waiting']['units'] + @$c['GO']['NotPresented']['units'] + @$c['GO']['Waiting']['units'] + @$c['MS']['NotPresented']['units'] + @$c['MS']['Waiting']['units'] + @$c['MT']['NotPresented']['units'] + @$c['MT']['Waiting']['units'];
				$centroCapex = @$c['DF']['NotPresented']['capex'] + @$c['DF']['Waiting']['capex'] + @$c['GO']['NotPresented']['capex'] + @$c['GO']['Waiting']['capex'] + @$c['MS']['NotPresented']['capex'] + @$c['MS']['Waiting']['capex'] + @$c['MT']['NotPresented']['capex'] + @$c['MT']['Waiting']['capex'];

				// SUDESTE
				
				// es:
				$es = @$c['ES']['NotPresented']['capex'] + @$c['ES']['Waiting']['capex'];
				$x[20]['sigla'] = 'es';
				$x[20]['capex'] = $es;
				$x[20]['color'] = '#6ebeaa';
				$brnaun = $brnaun + @$c['ES']['NotPresented']['units'];
				$breeun = $breeun + @$c['ES']['Waiting']['units'];
				$bracun = $bracun + @$c['ES']['Accepted']['units'];
				$brreun = $brreun + @$c['ES']['Declined']['units'];				
				$brnaca = $brnaca + @$c['ES']['NotPresented']['capex'];
				$breeca = $breeca + @$c['ES']['Waiting']['capex'];
				$bracca = $bracca + @$c['ES']['Accepted']['capex'];
				$brreca = $brreca + @$c['ES']['Declined']['capex'];
				
				// mg:
				$mg = @$c['MG']['NotPresented']['capex'] + @$c['MG']['Waiting']['capex'];
				$x[21]['sigla'] = 'mg';
				$x[21]['capex'] = $mg;
				$x[21]['color'] = '#6ebeaa';
				$brnaun = $brnaun + @$c['MG']['NotPresented']['units'];
				$breeun = $breeun + @$c['MG']['Waiting']['units'];
				$bracun = $bracun + @$c['MG']['Accepted']['units'];
				$brreun = $brreun + @$c['MG']['Declined']['units'];				
				$brnaca = $brnaca + @$c['MG']['NotPresented']['capex'];
				$breeca = $breeca + @$c['MG']['Waiting']['capex'];
				$bracca = $bracca + @$c['MG']['Accepted']['capex'];
				$brreca = $brreca + @$c['MG']['Declined']['capex'];
				
				// rj:
				$rj = @$c['RJ']['NotPresented']['capex'] + @$c['RJ']['Waiting']['capex'];
				$x[22]['sigla'] = 'rj';
				$x[22]['capex'] = $rj;
				$x[22]['color'] = '#6ebeaa';
				$brnaun = $brnaun + @$c['RJ']['NotPresented']['units'];
				$breeun = $breeun + @$c['RJ']['Waiting']['units'];
				$bracun = $bracun + @$c['RJ']['Accepted']['units'];
				$brreun = $brreun + @$c['RJ']['Declined']['units'];				
				$brnaca = $brnaca + @$c['RJ']['NotPresented']['capex'];
				$breeca = $breeca + @$c['RJ']['Waiting']['capex'];
				$bracca = $bracca + @$c['RJ']['Accepted']['capex'];
				$brreca = $brreca + @$c['RJ']['Declined']['capex'];
				
				// sp:
				$sp = @$c['SP']['NotPresented']['capex'] + @$c['SP']['Waiting']['capex'];
				$x[23]['sigla'] = 'sp';
				$x[23]['capex'] = $sp;
				$x[23]['color'] = '#6ebeaa';
				$brnaun = $brnaun + @$c['SP']['NotPresented']['units'];
				$breeun = $breeun + @$c['SP']['Waiting']['units'];
				$bracun = $bracun + @$c['SP']['Accepted']['units'];
				$brreun = $brreun + @$c['SP']['Declined']['units'];				
				$brnaca = $brnaca + @$c['SP']['NotPresented']['capex'];
				$breeca = $breeca + @$c['SP']['Waiting']['capex'];
				$bracca = $bracca + @$c['SP']['Accepted']['capex'];
				$brreca = $brreca + @$c['SP']['Declined']['capex'];
				
				$sudeste = @$c['ES']['NotPresented']['units'] + @$c['ES']['Waiting']['units'] + @$c['MG']['NotPresented']['units'] + @$c['MG']['Waiting']['units'] + @$c['RJ']['NotPresented']['units'] + @$c['RJ']['Waiting']['units'] + @$c['SP']['NotPresented']['units'] + @$c['SP']['Waiting']['units'];
				$sudesteCapex = @$c['ES']['NotPresented']['capex'] + @$c['ES']['Waiting']['capex'] + @$c['MG']['NotPresented']['capex'] + @$c['MG']['Waiting']['capex'] + @$c['RJ']['NotPresented']['capex'] + @$c['RJ']['Waiting']['capex'] + @$c['SP']['NotPresented']['capex'] + @$c['SP']['Waiting']['capex'];

				// SUL
				
				// pr:
				$pr = @$c['PR']['NotPresented']['capex'] + @$c['PR']['Waiting']['capex'];
				$x[24]['sigla'] = 'pr';
				$x[24]['capex'] = $pr;
				$x[24]['color'] = '#6ebeaa';
				$brnaun = $brnaun + @$c['PR']['NotPresented']['units'];
				$breeun = $breeun + @$c['PR']['Waiting']['units'];
				$bracun = $bracun + @$c['PR']['Accepted']['units'];
				$brreun = $brreun + @$c['PR']['Declined']['units'];				
				$brnaca = $brnaca + @$c['PR']['NotPresented']['capex'];
				$breeca = $breeca + @$c['PR']['Waiting']['capex'];
				$bracca = $bracca + @$c['PR']['Accepted']['capex'];
				$brreca = $brreca + @$c['PR']['Declined']['capex'];
				
				// rs:
				$rs = @$c['RS']['NotPresented']['capex'] + @$c['RS']['Waiting']['capex'];
				$x[25]['sigla'] = 'rs';
				$x[25]['capex'] = $rs;
				$x[25]['color'] = '#6ebeaa';
				$brnaun = $brnaun + @$c['RS']['NotPresented']['units'];
				$breeun = $breeun + @$c['RS']['Waiting']['units'];
				$bracun = $bracun + @$c['RS']['Accepted']['units'];
				$brreun = $brreun + @$c['RS']['Declined']['units'];				
				$brnaca = $brnaca + @$c['RS']['NotPresented']['capex'];
				$breeca = $breeca + @$c['RS']['Waiting']['capex'];
				$bracca = $bracca + @$c['RS']['Accepted']['capex'];
				$brreca = $brreca + @$c['RS']['Declined']['capex'];				
				
				// sc:
				$sc = @$c['SC']['NotPresented']['capex'] + @$c['SC']['Waiting']['capex'];
				$x[26]['sigla'] = 'sc';
				$x[26]['capex'] = $sc;
				$x[26]['color'] = '#6ebeaa';
				$brnaun = $brnaun + @$c['SC']['NotPresented']['units'];
				$breeun = $breeun + @$c['SC']['Waiting']['units'];
				$bracun = $bracun + @$c['SC']['Accepted']['units'];
				$brreun = $brreun + @$c['SC']['Declined']['units'];				
				$brnaca = $brnaca + @$c['SC']['NotPresented']['capex'];
				$breeca = $breeca + @$c['SC']['Waiting']['capex'];
				$bracca = $bracca + @$c['SC']['Accepted']['capex'];
				$brreca = $brreca + @$c['SC']['Declined']['capex'];
				
				$sul = @$c['SC']['NotPresented']['units'] + @$c['SC']['Waiting']['units'] + $rs = @$c['RS']['NotPresented']['units'] + @$c['RS']['Waiting']['units'] + $sc = @$c['SC']['NotPresented']['units'] + @$c['SC']['Waiting']['units'];
				$sulCapex = @$c['SC']['NotPresented']['capex'] + @$c['SC']['Waiting']['capex'] + $rs = @$c['RS']['NotPresented']['capex'] + @$c['RS']['Waiting']['capex'] + $sc = @$c['SC']['NotPresented']['capex'] + @$c['SC']['Waiting']['capex'];
				
				// UNITS
				$regions = $sudeste . "-" . $sul . "-" . $centro . "-" . $nordeste . "-" . $norte; 
				
				// CAPEX
				$capex = $sudesteCapex . "-" . $sulCapex . "-" . $centroCapex . "-" . $nordesteCapex . "-" . $norteCapex;
				
				// UNITS BY STATE
				$state = translate("Brazil");
				$units = $brnaun . "-" . $breeun . "-" . $bracun . "-" . $brreun;
				if (isset($_GET['s'])) {
					$state = $_GET['s'];
					$units = @$c[strtoupper($state)]['NotPresented']['units'] . "-" . @$c[strtoupper($state)]['Waiting']['units'] . "-" . @$c[strtoupper($state)]['Accepted']['units'] . "-" . @$c[strtoupper($state)]['Declined']['units']; //exit;
				}

				// VALUES BY CAPEX
				$state = translate("Brazil");
				$values = $brnaca . "-" . $breeca . "-" . $bracca . "-" . $brreca;
				if (isset($_GET['s'])) {
					$state = $_GET['s'];
					$values = @$c[strtoupper($state)]['NotPresented']['capex'] . "-" . @$c[strtoupper($state)]['Waiting']['capex'] . "-" . @$c[strtoupper($state)]['Accepted']['capex'] . "-" . @$c[strtoupper($state)]['Declined']['capex']; //exit;
				}					

				// DEBUG
				//print_r($x);
				
				// SORT STATES BY CAPEX
				usort($x, function($a, $b) {
					return $a['capex'] - $b['capex'];
				});
				
				// DEBUG
				//print_r($x);
				
				// MIN & MAX COLORS
				$x[0]['color'] = "#37b9c3";
				$x[26]['color'] = "#69af23";
				
				$y = 0;
				
				// COLOR '0' STATES
				for ($i = 0; $i <= 26; $i++) {
					if ($x[$i]['capex'] == '0') { $y++; $x[$i]['color'] = "#37b9c3"; }
				} 	
				
				// MID COLORS (TODO)
				//echo 27-$y;
				// #becd00
				// #e1dc00
				// #91c896
				// #6ebeaa	
				
				// DEBUG
				//for ($i = 0; $i <= 26; $i++) { echo $x[$i]['sigla'] . " - " . $x[$i]['capex'] . " - " . $x[$i]['color'] . "<br />\n"; } //exit;
				
				// DASHBOARD GRID
				echo "					<div class='row'>\n";

				// POTENCIAL DE VENDAS (MAPA DO BRASIL)
				echo "						<div class='col-sm-12 col-xl-4'><iframe src='widgets/brazilmap.php?s=" . $state . "&x=" . urlencode(serialize($x)) . "' class='brazil-map'></iframe></div>\n";
				
				echo "						<div class='col-sm-12 col-xl-8'>\n";
				
				// HISTORICO - MULTA/ECONOMIA (historic)
				echo "							<div class='row'>\n";
				
					$c = json_decode($historic); $c = object2array($c);

					// DEBUG
					//print_r($c);		
					
					$y = array(); $i = 0;
					
					foreach ($c as $k =>$v) {
						foreach($v as $x) {
							//echo $x['date']. "<br>";
							$y[$i]['date'] = $x['date'];
							$v = $x['saving']; if ($x['saving'] == '') { $v = 0; }
							$y[$i]['saving'] = $v;
							$y[$i]['fine'] = $x['fine'];
							$i++;				
						}
					}	
					
					// DEBUG
					//print_r($y);
						
					$dates = ''; $savings = ''; $fines = '';
					foreach($y as $x) {
						$dates = "'" . left($x['date'], 2) . "/" . right($x['date'], 2) . "', " . $dates;	
						$savings = $x['saving'] . ", " . $savings;
						$fines = $x['fine'] . ", " . $fines;
						//$savings = "'" . number_format($x['saving'], 2, ',', '.') . "M', " . $savings;
						//$fines = "'" . number_format($x['fine'], 2, ',', '.') . "M', " . $fines;
					}
					
					$dates = left($dates, strlen($dates) - 2); 
					$savings = left($savings, strlen($savings) - 2);
					$fines = left($fines, strlen($fines) - 2);				
				
				echo "								<div class='col-sm-12 col-xl-6'><iframe src='widgets/historic.php?d=" . urlencode($dates) . "&s=" . urlencode($savings) . "&f=" . urlencode($fines) . "&t=" . $state . "' class='historic'></iframe></div>\n";
				
				// STATUS DAS PROPOSTAS / NUMERO DE UNIDADES (units)
				echo "								<div class='col-sm-12 col-xl-6'><iframe src='widgets/units.php?s=" . $state . "&d=" . $units . "' class='units'></iframe></div>\n";
				
				echo "							</div>\n";
				
				echo "							<div class='row'>\n";
				
				// PROPOSTAS NAO APRESENTADAS - BRASIL (regions)
				echo "								<div class='col-sm-12 col-xl-6'><iframe src='widgets/regions.php?r=" . $regions . "' class='regions'></iframe></div>\n";
				
				// STATUS DAS PROPOSTAS / VALOR EM CAPEX (values)
				echo "								<div class='col-sm-12 col-xl-6'><iframe src='widgets/values.php?s=" . $state . "&d=" . $values . "' class='values'></iframe></div>\n";
				
				echo "							</div>\n";
				
				echo "						</div>\n";
				
				echo "					</div>\n";
				
				echo "					<div class='row'>\n";
				
				// TOTAL DE CLIENTES / PROPOSTAS NAO APRESENTADAS
				echo "						<div class='col-sm-12 col-xl-4'><iframe src='widgets/labels.php?s=" . $state . "' class='labels'></iframe></div>\n";
				
				// PROPOSTAS NAO APRESENTADAS / POR CAPEX (capex)
				echo "						<div class='col-sm-12 col-xl-4'><iframe src='widgets/capex.php?r=" . $capex . "' class='regions'></iframe></div>\n";
				
				// PROPOSTAS NAO APRESENTADAS / POR SETOR (sectors)
				echo "						<div class='col-sm-12 col-xl-4'><iframe src='widgets/sectors.php' class='sectors'></iframe></div>\n";
				
				echo "					</div>\n";	

			}
	 
		echo "				</div>\n"; // /content
		
		echo "			</div>\n"; // /page-wrapper
		
		echo "		</div>\n"; // /page-content
		
		echo "	</body>\n";	
		
		// SAVE EVENT LOG
		logWrite("View", "CapacitorsDashboard");	

	// PAGE FOOTER
	pageFooter();

// DATABASE CLOSE
closeDB();

function getError($message, $err) {
	
	echo "					<div class='alert alert-danger border-0'>\n";
	echo "						<button type='button' class='close' data-dismiss='alert'><span class='alert-x'>&times;</span><span class='sr-only'>" . translate("Close") . "</span></button>\n";
	echo "						" . translate($message) . " (" . $err . ")\n";
	echo "					</div>\n";
				
}