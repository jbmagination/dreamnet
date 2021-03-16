<?php

// Simple script for get JSON data from an iTunes store
// Made by dreamnet (GKI) on 2013-02-26

function crawl_data($id) { // cycles trough stores and returns JSON data array for given iTunes ID if found, else returns false

	$stores = array( // array with all known iTunes stores and there country codes
	// Main english stores
	array("", "International"),		// International (English)
	array("us", "United States"),	// United States (English)
	array("gb", "United Kingdom"),	// United Kingdom (English)
	array("hk", "Hong Kong"),		// Hong Kong (English)
	array("au", "Australia"),		// Australia (English)
	array("ca", "Canada"),			// Canada (English)
	array("ie", "Ireland"),			// Ireland (English)
	// Europe stores
	array("de", "Germany"),			// Germany
	array("at", "Austria"),			// Austria
	array("ch", "Switzerland"),		// Switzerland
	array("fr", "France"),			// France
	array("cz", "Czech Republic"),	// Czech Republic
	array("se", "Sweden"),	// Sweden
	array("dk", "Denmark"),	// Denmark
	array("fi", "Finland"),	// Finland
	array("be", "Belgium"),	// Belgium
	array("is", "Iceland"),	// Iceland
	array("nz", "New Zealand"),	// New Zealand
	array("no", "Norway"),	// Norway
	array("hr", "Croatia"),	// Croatia
	array("it", "Italy"),	// Italy
    array("es", "Spain"),	// Spain
	// Latin America
	array("mx", "Mexico"),	// Mexico
	// Asian stores
	array("cn", "China"),		// China
	array("jp", "Japan"),		// Japan
	array("th", "Thailand"),	// Thailand
	array("ph", "Philippines"),	// Philippines
	array("tw", "Taiwan"),		// Taiwan
	array("vn", "Vietnam"),		// Vietnam
	// the rest of the world
	array("ag", "Antigua & Barbuda"),	// Antigua & Barbuda
	array("ai", "Anguilla"),	// Anguilla
	array("ar", "Argentina"),	// Argentina
	array("am", "Armenia"),	// Armenia
	array("ao", "Angola"),	// Angola
	array("dz", "Algeria"),	// Algeria
	array("az", "Azerbaijan"),	// Azerbaijan
	array("al", "Albania"),	// Albania
	array("bs", "Bahamas"),	// Bahamas
	array("bh", "Bahrain"),	// Bahrain
	array("bb", "Barbados"),	// Barbados
	array("bd", "Bangladesh"),	// Bangladesh
	array("by", "Belarus"),	// Belarus
	array("bz", "Belize"),	// Belize
	array("bm", "Bermuda"),	// Bermuda
	array("bj", "Benin"),	// Benin
	array("bt", "Bhutan"),	// Bhutan
	array("bw", "Botswana"),	// Botswana
	array("bo", "Bolivia"),	// Bolivia
	array("br", "Brazil"),	// Brazil
	array("vg", "British Virgin Islands"),	// British Virgin Islands
	array("bn", "Brunei Darussalam"),	// Brunei Darussalam
	array("bg", "Bulgaria"),	// Bulgaria
	array("bf", "Burkina Faso"),	// Burkina Faso
	array("kh", "Cambodia"),	// Cambodia
	array("cv", "Cape Verde"),	// Cape Verde
	array("ky", "Cayman Islands"),	// Cayman Islands
	array("co", "Colombia"),	// Colombia
	array("cl", "Chile"),	// Chile
	array("td", "Chad"),	// Chad
	array("cr", "Costa Rica"),	// Costa Rica
	array("cg", "Congo"),	// Congo
	array("cy", "Cyprus"),	// Cyprus
	array("ci", "Cote D Ivoire"),	// Cote D Ivoire
	array("dm", "Dominica"),	// Dominica
	array("ec", "Ecuador"),	// Ecuador
	array("sv", "El Salvador"),	// El Salvador
	array("do", "Dominican Republic"),	// Dominican Rep.
	array("ee", "Estonia"),	// Estonia
	array("fj", "Fiji"),	// Fiji
	array("eg", "Egypt"),	// Egypt
	array("gm", "Gambia"),	// Gambia
	array("gd", "Grenada"),	// Grenada
	array("gr", "Greece"),	// Greece
	array("gh", "Ghana"),	// Ghana
	array("gw", "Guinea-Bissau"),	// Guinea-Bissau
	array("gt", "Guatemala"),	// Guatemala
	array("hu", "Hungary"),	// Hungary
	array("hn", "Honduras"),	// Honduras
	array("gy", "Guyana"),	// Guyana
	array("in", "India"),	// India
	array("id", "Indonesia"),	// Indonesia
	array("il", "Israel"),	// Israel
	array("jm", "Jamaica"),	// Jamaica
	array("kz", "Kazakstan"),	// Kazakstan
	array("ke", "Kenya"),	// Kenya
	array("jo", "Jordan"),	// Jordan
	array("kg", "Kyrgyzstan"),	// Kyrgyzstan
	array("la", "Lao People's Democratic Republic"),	// Lao People's Democratic Republic
	array("kw", "Kuwait"),	// Kuwait
	array("lv", "Latvia"),	// Latvia
	array("lb", "Lebanon"),	// Lebanon
	array("lt", "Lithuania"),	// Lithuania
	array("lr", "Liberia"),	// Liberia
	array("mo", "Macao"),	// Macao
	array("li", "Liechtenstein"),	// Liechtenstein
	array("lu", "Luxembourg"),	// Luxembourg
	array("my", "Malaysia"),	// Malaysia
	array("mg", "Madagascar"),	// Madagascar
	array("mk", "Macedonia"),	// Macedonia
	array("mt", "Malta"),	// Malta
	array("mw", "Malawi"),	// Malawi
	array("mv", "Maldives"),	// Maldives
	array("ml", "Mali"),	// Mali
	array("mu", "Mauritius"),	// Mauritius
	array("mn", "Mongolia"),	// Mongolia
	array("fm", "Micronesi"),	// Micronesi
	array("mr", "Mauritania"),	// Mauritania
	array("mz", "Mozambique"),	// Mozambique
	array("np", "Nepal"),	// Nepal
	array("ms", "Montserrat"),	// Montserrat
	array("ni", "Nicaragua"),	// Nicaragua
	array("na", "Namibia"),	// Namibia
	array("nl", "Netherlands"),	// Netherlands
	array("ng", "Nigeria"),	// Nigeria
	array("ne", "Niger"),	// Niger
	array("pk", "Pakistan"),	// Pakistan
	array("om", "Oman"),	// Oman
	array("pa", "Panama"),	// Panama
	array("pg", "Papua New Guinea"),	// Papua New Guinea
	array("py", "Paraguay"),	// Paraguay
	array("pe", "Peru"),	// Peru
	array("pw", "Palau"),	// Palau
	array("pt", "Portugal"),	// Portugal
	array("qa", "Qatar"),	// Qatar
	array("kr", "Republic of Korea"),	// Republic of Korea
	array("md", "Republic of Moldova"),	// Republic of Moldova
	array("ru", "Russia"),	// Russia
	array("pl", "Poland"),	// Poland
	array("ro", "Romania"),	// Romania
	array("sa", "Saudi Arabia"),	// Saudi Arabia
	array("st", "Sao Tome and Principe"),	// Sao Tome and Principe
	array("rs", "Serbia"),	// Serbia
	array("sk", "Slovakia"),	// Slovakia
	array("sg", "Singapore"),	// Singapore
	array("sn", "Senegal"),	// Senegal
	array("si", "Slovenia"),	// Slovenia
	array("za", "South Africa"),	// South Africa
	array("lk", "Sri Lanka"),	// Sri Lanka
	array("sc", "Seychelles"),	// Seychelles
	array("sb", "Solomon Islands"),	// Solomon Islands
	array("sr", "Suriname"),	// Suriname
	array("sz", "Swaziland"),	// Swaziland
	array("sl", "Sierra Leone"),	// Sierra Leone
	array("vc", "St. Vincent & The Grenadines"),	// St. Vincent & The Grenadines
	array("lc", "St. Lucia"),	// St. Lucia
	array("tj", "Tajikistan"),	// Tajikistan
	array("kn", "St. Kitts & Nevis"),	// St. Kitts & Nevis
	array("tz", "Tanzania"),	// Tanzania
	array("tt", "Trinidad & Tobago"),	// Trinidad & Tobago
	array("tn", "Tunisia"),	// Tunisia
	array("tr", "Turkey"),	// Turkey
	array("tm", "Turkmenistan"),	// Turkmenistan
	array("ug", "Uganda"),	// Uganda
	array("ua", "Ukraine"),	// Ukraine
	array("ae", "United Arab Emirates"),	// United Arab Emirates
	array("uz", "Uzbekistan"),	// Uzbekistan
	array("ve", "Venezuela"),	// Venezuela
	array("uy", "Uruguay"),	// Uruguay
	array("ye", "Yemen"),	// Yemen
	array("zw", "Zimbabwe"),	// Zimbabwe
	array("tc", "Turks & Caicos")		// Turks & Caicos
);

	foreach ($stores as $store) { // cycle trough stores array until one of them returns valid data

		if($store[0] != '') {
		 $url = 'https://itunes.apple.com/'.$store[0].'/lookup?id='.$id;
		} else {
		 $url = 'https://itunes.apple.com/lookup?id='.$id;
		}

		$json = file_get_contents($url, 0, null, null);
		$data = json_decode($json, true);
		// var_dump($output); // just for debugging
		if (($data['resultCount'] == 1) && ($data['results'][0]['kind'] == 'software')) { // make sure we are getting data for an app

			// writing fond data in vars
			$trackName		= trim($data['results'][0]['trackName']);
			$bundleId		= trim($data['results'][0]['bundleId']);
			$trackId		= trim($data['results'][0]['trackId']);
			$trackViewUrl	= trim($data['results'][0]['trackViewUrl']);
			$version		= trim($data['results'][0]['version']);
			$sellerName		= trim($data['results'][0]['sellerName']);
			$artistName		= '&copy;&nbsp;'.trim($data['results'][0]['artistName']);
			// $devices_arr	= $data['results'][0]['supportedDevices'];
			// $devices = "";
			// foreach ( $devices_arr as $device ) { $devices .= $device.', '; }
			// $devices		= trim($devices);
			// $devices		= rtrim($devices,',');
			$artwork		= $data['results'][0]['artworkUrl512'];
			$server			= $store[1];

			$data['server'] = $server;

			$ret = $data; break; // valid data found
		} else {
			$ret = false; // no valid data found
		}
	}
	return $ret;
}

function dbConnect() { // connect to mysql
	include 'config.php';
	$db = new mysqli($config['mysqlhost'],$config['mysqluser'],$config['mysqlpwd'],$config['mysqldbname']);
	if ($db->connect_errno) {
		printf("Connect failed: %s\n", $db->connect_error);
		exit();
	} else {
		return $db;
	}
}

function checkiTunesID($db,$id) {
	$result = $db->query("SELECT trackName, issue FROM theicons WHERE trackId = $id LIMIT 1");
	if($result->num_rows) {
		$row = $result->fetch_assoc();
		if($row['issue'] != '') {
			return $row;
		} else {
			return false;
		}
	} else {
		return false;
	}
}

function assembleData($data) {
	// disassembling $data var
	$trackName		= trim($data['results'][0]['trackName']);
	$bundleId 		= trim($data['results'][0]['bundleId']);
	$trackId		= trim($data['results'][0]['trackId']);
	$trackViewUrl	= trim($data['results'][0]['trackViewUrl']);
	$version		= trim($data['results'][0]['version']);
	$seller			= trim($data['results'][0]['sellerName']);
	$artistViewUrl	= trim($data['results'][0]['artistViewUrl']);
	$artistName		= '&copy;&nbsp;'.trim($data['results'][0]['artistName']);
	// $devices_arr	= $data['results'][0]['supportedDevices'];
	// $devices = "";
	// foreach ( $devices_arr as $device ) { $devices .= $device.', '; }
	// $devices 		= trim($devices);
	// $devices 		= rtrim($devices,',');
	// trying to get the highest resolution artwork
	$artwork 		= $data['results'][0]['artworkUrl512'];
	$bigjpg = str_replace('512x512','1024x1024',$artwork);
	$bigpng = str_replace('.jpg','.png',$bigjpg);
	if(url_exists($bigpng)) {
		$artwork = $bigpng;
	} elseif(url_exists($bigjpg)) {
		$artwork = $bigjpg;
	}
	// end disassembling
	// putting strings together
	$returnData  = "";
	$returnData .= '**App Name:** '.$trackName."\n";
	$returnData .= '**Bundle ID:** '.$bundleId."\n";
	$returnData .= '**iTunes ID:** <a target="_blank" href="http://getart.dreamnet.at?id='.$trackId.'">'.$trackId.'</a>'."\n";
	$returnData .= '**iTunes URL:** <a target="_blank" href="'.$trackViewUrl.'">'.$trackViewUrl.'</a>'."\n";
	$returnData .= '**App Version:** '.$version."\n";
	$returnData .= '**Seller:** '.$seller."\n";
	$returnData .= '**Developer:** <a target="_blank" href="'.$artistViewUrl.'">'.$artistName.'</a>'."\n";
	// $returnData .= '**Supported Devices:** '.$devices."\n";
	$returnData .= '**Original Artwork:**'."\n".'<img src="'.$artwork.'" width="180" height="180" />'."\n";
	$returnData .= '**Accepted Artwork:**'."\n".'\#\#\# THIS IS FOR GLASKLART MAINTAINERS DO NOT MODIFY THIS LINE OR WRITE BELOW IT. CONTRIBUTIONS AND COMMENTS SHOULD BE IN A SEPARATE COMMENT. \#\#\#';
	return $returnData;
}

function url_exists ($url) { // checks if a remote file exists
	if (@file_get_contents($url,0,NULL,0,1)) {
		return 1;
	} else {
		return 0;
	}
}
