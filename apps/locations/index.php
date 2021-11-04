<?php

// INDEX.PHP 1.0 (2019/10/24)

require "../../core/config.php";
require "../../core/functions.php";
require "../../core/security.php";

// MAP DEFAULT DATA
$lat = "-43.204707"; $lon = "-22.980128"; $zoom = "15"; $type = "GD2"; $mapID = "1";

// DATABASE OPEN
openDB();

	// GET MAP DATA
	if (isset($_GET["m"])) {
        
        $mapID = $_GET["m"];
        
        $r = select("*", "applocations", "where ItemID=" . $mapID);
        if ($r) {
            $lat = $r[0]["Latitude"];
            $lon = $r[0]["Longitude"];
            $zoom = $r[0]["Zoom"];
            $type = $r[0]["LocationType"];
        }
    }

	// PAGE HEADER
	pageHeader("default");

		echo "  <body>\n";
		
			echo "      <div class='map' id='map'></div>\n";

			// SHOW MAP
			if (left($type, 1) == "G") { showGoogleMaps($lat, $lon, $zoom, $type); } else { showMapBox($lat, $lon, $zoom, $type); }
		 
		echo "  </body>\n";
        
	// PAGE FOOTER
	pageFooter();

// DATABASE CLOSE
closeDB();

function showGoogleMaps($lat, $lon, $zoom, $type) {
	
	// GOOGLE API KEY
	$googleAPI = "AIzaSyCKDif2pmf1LShDRPzO-dZr7W-nf8h9T-g";
	
	$t1 = left($type, 1); $t2 = substr($type, 1, 1); $t3 = right($type, 1);
	
	echo "	<script>\n"; 
 
	// MAP INICIALIZATION
	echo "		function initMap() {\n"; 
 
	echo "			var map = new google.maps.Map(document.getElementById('map'), {\n"; 
	
	// DISABLE DEFAULT COMMANDS
	echo "				disableDefaultUI: true,\n"; 
	
	// MAP STYLES
	switch ($t2) {
	
		case "B":
			echo "				styles: [{'featureType':'water','stylers':[{'visibility':'on'},{'color':'#acbcc9'}]},{'featureType':'landscape','stylers':[{'color':'#f2e5d4'}]},{'featureType':'road.highway','elementType':'geometry','stylers':[{'color':'#c5c6c6'}]},{'featureType':'road.arterial','elementType':'geometry','stylers':[{'color':'#e4d7c6'}]},{'featureType':'road.local','elementType':'geometry','stylers':[{'color':'#fbfaf7'}]},{'featureType':'poi.park','elementType':'geometry','stylers':[{'color':'#c5dac6'}]},{'featureType':'administrative','stylers':[{'visibility':'on'},{'lightness':33}]},{'featureType':'road'},{'featureType':'poi.park','elementType':'labels','stylers':[{'visibility':'on'},{'lightness':20}]},{},{'featureType':'road','stylers':[{'lightness':20}]}],\n";
			break;
		
		case "G":
			echo "				styles: [{featureType: 'landscape', stylers: [{saturation: -100}, {lightness: 65}, {visibility: 'on'}]}, {featureType: 'poi', stylers: [{saturation: -100}, {lightness: 51}, {visibility: 'simplified'}]}, {featureType: 'road.highway', stylers: [{saturation: -100}, {visibility: 'simplified'}]}, {featureType: 'road.arterial', stylers: [{saturation: -100}, {lightness: 30}, {visibility: 'on'}]}, {featureType: 'road.local', stylers: [{saturation: -100}, {lightness: 40}, {visibility: 'on'}]}, {featureType: 'transit', stylers: [{saturation: -100}, {visibility: 'simplified'}]}, {featureType: 'administrative.province', stylers: [{visibility: 'off'}]/**/}, {featureType: 'administrative.locality', stylers: [{visibility: 'off'}]}, {featureType: 'administrative.neighborhood', stylers: [{visibility: 'on'}]/**/}, {featureType: 'water', elementType: 'labels', stylers: [{visibility: 'on'}, {lightness: -25}, {saturation: -100}]}, {featureType: 'water', elementType: 'geometry', stylers: [{hue: '#ffff00'}, {lightness: -25}, {saturation: -97}]}],\n";
			break;
			
		case "S";
			echo "				mapTypeId: 'satellite',\n";
			break;
			
		case "H";
			echo "				mapTypeId: 'hybrid',\n";
			break;

		case "T";
			echo "				mapTypeId: 'terrain',\n";
			break;			
	}
	
	echo "				center: new google.maps.LatLng(" . $lon . ", " . $lat . "), zoom: " . $zoom . "\n"; 
	echo "			});\n"; 

	echo "			var infoWindow = new google.maps.InfoWindow;\n"; 
	
	// DATE TO PREVENT CACHE
	echo "            var newDate = new Date();\n"; 
	echo "            var datetime = newDate.getSeconds()\n"; 
 
	// LOAD XML DATA
	echo "            downloadUrl('markers.php?t=G&d=' + datetime, function(data) {\n"; 
 
	echo "				var xml = data.responseXML;\n"; 
	echo "				var markers = xml.documentElement.getElementsByTagName('marker');\n"; 
 
	echo "				Array.prototype.forEach.call(markers, function(markerElem) {\n"; 

	echo "					var id = markerElem.getAttribute('id');\n"; 
	echo "					var icon = markerElem.getAttribute('icon');\n"; 
	echo "					var name = markerElem.getAttribute('name');\n"; 
	echo "					var description = markerElem.getAttribute('description');\n"; 
	echo "					var type = markerElem.getAttribute('type');\n"; 
	echo "					var point = new google.maps.LatLng(\n"; 
	echo "						parseFloat(markerElem.getAttribute('lat')),\n"; 
	echo "						parseFloat(markerElem.getAttribute('lng')));\n"; 
 
	echo "					var infowincontent = document.createElement('div');\n"; 
	echo "					var strong = document.createElement('strong');\n"; 
	echo "					strong.textContent = name\n"; 
	echo "					infowincontent.appendChild(strong);\n"; 
	echo "					infowincontent.appendChild(document.createElement('br'));\n";

 
	echo "					var text = document.createElement('text');\n"; 
	echo "					text.textContent = description\n"; 
	echo "					infowincontent.appendChild(text);\n"; 
 
	echo "					var marker = new google.maps.Marker({\n"; 
	echo "						map: map,\n"; 
	echo "						icon: icon,\n"; 
	echo "						position: point,\n"; 
	echo "					});\n"; 
 
    //echo "					var markerCluster = new MarkerClusterer( map, markers, { imagePath: 'assets/images/clusters' } );\n"; 
 
	// MOUSE OVER MARKERS
	echo "					marker.addListener('mouseover', function() {\n"; 
	echo "						infoWindow.setContent(infowincontent);\n"; 
	echo "						infoWindow.open(map, marker);\n"; 
	echo "					});\n"; 
	
	// CLICK ON MARKERS
	echo "					marker.addListener('click', function() {\n"; 
	//echo "						alert('Device [' + id + ']');\n";
	echo "						parent.mWindow(name, \"<iframe class='video-frame' src='apps/locations/video.php?sign=\" + id + \"'></iframe>\");\n";	
	echo "					});\n"; 
 
	echo "				});\n";
	
	echo "			});\n";
	
	echo "		}\n"; 
 
	echo "		function downloadUrl(url, callback) {\n"; 
	echo "			var request = window.ActiveXObject ? new ActiveXObject('Microsoft.XMLHTTP') : new XMLHttpRequest;\n"; 
	echo "			request.onreadystatechange = function() {\n"; 
	echo "				if (request.readyState == 4) {\n"; 
	echo "					request.onreadystatechange = doNothing;\n"; 
	echo "					callback(request, request.status);\n"; 
	echo "				}\n"; 
	echo "			};\n"; 
	echo "			request.open('GET', url, true);\n"; 
	echo "			request.send(null);\n"; 
	echo "		}\n"; 
 
	echo "		function doNothing() {}\n"; 
 
	echo "	</script>\n";
	
	//echo "	<script src='assets\js\markerclusterer.js'></script>\n";

	echo "	<script async defer src='https://maps.googleapis.com/maps/api/js?key=" . $googleAPI . "&callback=initMap'></script>\n";	
	
}

function showMapBox($lat, $lon, $zoom, $type) {

	echo "		<script src='../../core/plugins/mapbox/mapbox-gl.js'></script>\n";
	echo "		<link href='../../core/plugins/mapbox/mapbox-gl.css' rel='stylesheet' />\n";
	
	$t1 = left($type, 1); $t2 = substr($type, 1, 1); $t3 = right($type, 1);
 
	echo "		<script>\n"; 
 
	// MAP DEFINITION 
	echo "            mapboxgl.accessToken = 'pk.eyJ1IjoibWFyY2Vsb3ZhbGxlZnJhbmNvIiwiYSI6ImNqazJuYnVqNzB1MHkzdm10Z3M0cHo1a2UifQ.rTN7gz-U0QNGGTYV6-6xYQ';\n"; 
	echo "            var map = new mapboxgl.Map({\n";

	// MAP STYLES
	switch ($t2) {
        
        case "O":
			echo "                style: 'mapbox://styles/mapbox/outdoors-v11',\n"; 
			break;
		
		case "G":
			echo "                style: 'mapbox://styles/mapbox/light-v9',\n"; 
			break;
		
		case "D":
			echo "                style: 'mapbox://styles/mapbox/streets-v10',\n";
			break;

	}
	
	echo "                center: [" . $lat . ", " . $lon . "],\n"; 
	echo "                zoom: " . ($zoom - 1) . ",\n"; 
	
	// 3D ROTATION
	if ($t3 == "3") {
		echo "                pitch: 45,\n";
		echo "                bearing: -17.6,\n"; 
	} else {
		echo "                pitch: 0,\n";
		echo "                bearing: 0,\n"; 
	} 
	
	echo "                container: 'map'\n"; 
	echo "            });\n"; 
	
	// DISABLE ROTATION IN 2D
	if ($t3 == "2") {
		echo "            map.dragRotate.disable();\n";
		echo "            map.touchZoomRotate.disableRotation();\n";
	}	
 
	// DATE TO PREVENT CACHE
	echo "            var newDate = new Date();\n"; 
	echo "            var datetime = newDate.getSeconds()\n"; 
 
	// LOAD MARKERS JSON 
	echo "            var url = 'markers.php?t=M&d=' + datetime;\n"; 
 
	// CREATE MAP 
	echo "            map.on('load', function () {\n"; 
 
	// KEEP REAL TIME 
	echo "                window.setInterval(function () {\n"; 
	echo "                    map.getSource('genmarkers').setData(url);\n"; 
	echo "                }, 2000);\n"; 
 
	// LAYERS MANAGER 
	echo "                var layers = map.getStyle().layers;\n"; 
	echo "                var labelLayerId;\n"; 
	echo "                for (var i = 0; i < layers.length; i++) {\n"; 
	echo "                    if (layers[i].type === 'symbol' && layers[i].layout['text-field']) {\n"; 
	echo "                        labelLayerId = layers[i].id;\n"; 
	echo "                        break;\n"; 
	echo "                    }\n"; 
	echo "                }\n"; 
 
	// 3D EXTRUSIONS 
	if ($t3 == "3") {
	
		echo "                map.addLayer({\n"; 
		echo "                    'id': '3d-buildings',\n"; 
		echo "                    'source': 'composite',\n"; 
		echo "                    'source-layer': 'building',\n"; 
		echo "                    'filter': ['==', 'extrude', 'true'],\n"; 
		echo "                    'type': 'fill-extrusion',\n"; 
		echo "                    'minzoom': 15,\n"; 
		echo "                    'paint': {\n"; 
		echo "                        'fill-extrusion-color': '#aaa',\n"; 
		echo "                        'fill-extrusion-height': [\n"; 
		echo "                            'interpolate', ['linear'], ['zoom'],\n"; 
		echo "                            15, 0,\n"; 
		echo "                            15.05, ['get', 'height']\n"; 
		echo "                        ],\n"; 
		echo "                        'fill-extrusion-base': [\n"; 
		echo "                            'interpolate', ['linear'], ['zoom'],\n"; 
		echo "                            15, 0,\n"; 
		echo "                            15.05, ['get', 'min_height']\n"; 
		echo "                        ],\n"; 
		echo "                        'fill-extrusion-opacity': .6\n"; 
		echo "                    }\n"; 
		echo "                }, labelLayerId);\n";
	
	}
 
	// MAP LANGUAGE 
	echo "                map.setLayoutProperty('country-label-lg', 'text-field', ['get', 'name_pt']);\n"; 
 
	// ADD AN MARKER 
	echo "                map.loadImage('assets/images/markers/cameras.png?t=' + datetime, function (error, image) {\n"; 
	echo "                    if (error) throw error;\n"; 
	echo "                    map.addImage('cat', image);\n"; 
	echo "                });\n"; 
 
	// LOAD MARKERS 
	echo "                map.addSource('genmarkers', { type: 'geojson', data: url });\n"; 
	echo "                map.addLayer({\n"; 
	echo "                    'id': 'points',\n"; 
	echo "                    'type': 'symbol',\n"; 
	echo "                    'source': 'genmarkers',\n"; 
	echo "                    'layout': {\n"; 
	echo "                        'icon-image': 'cat',\n"; 
	echo "                        'icon-size': 1\n"; 
	echo "                    }\n"; 
	echo "                });\n"; 
 
	// POPUP 
	echo "                var popup = new mapboxgl.Popup({\n"; 
	echo "                    closeButton: false,\n"; 
	echo "                    closeOnClick: false\n"; 
	echo "                });\n"; 
 
	// MOUSE CLICK 
	echo "                map.on('click', 'points', function (e) {\n"; 
	//echo "                    map.flyTo({ center: e.features[0].geometry.coordinates });\n"; 
	echo "                    parent.vWindow(e.features[0].properties.name, \"<iframe class='video-frame' src='apps/locations/video.php?sign=\"+e.features[0].properties.sign+\"'></iframe>\");\n";
	//echo "                    alert('Coords: ' + e.features[0].geometry.coordinates + 'Device: ' + e.features[0].properties.device + ' ID: ' + e.features[0].properties.id + ' Code: ' + e.features[0].properties.code);\n"; 
	echo "                });\n"; 
 
	// MOUSE OVER 
	echo "                map.on('mouseenter', 'points', function (e) {\n"; 
	echo "                    map.getCanvas().style.cursor = 'pointer';\n"; 
	echo "                    var coordinates = e.features[0].geometry.coordinates.slice();\n"; 
	echo "                    var description = e.features[0].properties.name;\n"; 
	echo "                    while (Math.abs(e.lngLat.lng - coordinates[0]) > 180) {\n"; 
	echo "                        coordinates[0] += e.lngLat.lng > coordinates[0] ? 360 : -360;\n"; 
	echo "                    }\n"; 
	echo "                    popup.setLngLat(coordinates)\n"; 
	echo "                        .setHTML(description)\n"; 
	echo "                        .addTo(map);\n"; 
	echo "                });\n"; 
 
	// MOUSE OUT 
	echo "                map.on('mouseleave', 'points', function () {\n"; 
	echo "                    map.getCanvas().style.cursor = '';\n"; 
	echo "                    popup.remove();\n"; 
	echo "                });\n"; 
 
	echo "            });\n"; 

	echo "		</script>\n";

}