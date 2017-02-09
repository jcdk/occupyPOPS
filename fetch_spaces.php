<?php
	ini_set('memory_limit', '150M');

// Load secret config settings.
require('config.php');

//Connect to Database
$connect_ID=mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
mysql_select_db(DB_NAME) or die ("Could not connect to database");

ob_start();

require_once 'EpiCurl.php';
require_once 'EpiFoursquare.php';
$clientId = 'YSXNCMH5K5WCFI5OJT12Q4SXVCORTZFPULMSBUM0XQP2KSNL';
$clientSecret = '1OYJVUX14CKPBIG5KR3KEG1WQWLAHY5PVQRMOZ25U05FZN4Y';
// $code = 'BFVH1JK5404ZUCI4GUTHGPWO3BUIUTEG3V3TKQ0IHVRVGVHS';
$accessToken = 'DT32251AY1ED34V5ADCTNURTGSNHWXCNTOMTQM5ANJLBLO2O';
$redirectUri = 'http://projectwith.us/a_space/index.php';
// $userId = '5763863';
$fsObj = new EpiFoursquare($clientId, $clientSecret, $accessToken);
$fsObjUnAuth = new EpiFoursquare($clientId, $clientSecret);

########################################################
#	GET SOME POPS									   #
########################################################
if ($_POST["get_spaces"]=="nyc" || $_GET["get_spaces"]=="nyc") { 
	//Get the new coordinates to crop the image.

	// FORMAT THE DATA PRIOR TO INSERT
	if ($_POST["get_spaces"]=="nyc") {
		$lat = addslashes($_POST["lat"]);
		$lng = addslashes($_POST["lng"]);
		$acc = addslashes($_POST["accuracy"]);
	} else {
		$lat = addslashes($_GET["lat"]);
		$lng = addslashes($_GET["lng"]);
		$acc = addslashes($_GET["accuracy"]);
	}

	$altype = array();
	$adesc = array();
	$incd = array();

	// GET LAST LOCATION
	$querys = "SELECT nyc_pops.id, BuildingAddress, BuildingName, BuildingLocation, YearCompleted, PublicSpace1, fq_venue, Lat, Lng, SQRT(
			    POW(69.1 * (Lat - ".$lat."), 2) +
			    POW(69.1 * (".$lng." - Lng) * COS(Lat / 57.3), 2)) AS distance
			FROM nyc_pops 
			LEFT JOIN nyc_partipops np on nyc_pops.id = np.pops_id 
			HAVING distance < 1 ORDER BY distance LIMIT 3; ";
	$results = mysql_query($querys) or die(mysql_error());

	if ($results && mysql_num_rows($results) > 0) {

		$ncnt = mysql_num_rows($results);

		while ($row = mysql_fetch_object($results)) {

			$aid[] = $row->id;
			$aaddy[] = $row->BuildingAddress;
			$aname[] = $row->BuildingName;
			$aloc[] = $row->BuildingLocation;
			$ayear[] = $row->YearCompleted;
			$atype[] = $row->PublicSpace1;
			$afqvenu[] = $row->fq_venue;

		}
	} 

	// CHEAT - ADD ZUCCOTI
	$aid[] = 26;
	$aaddy[] = '1 Liberty Plaza';
	$aname[] = 'One Liberty Plaza';
	$aloc[] = 'Full block bounded by Broadway, Church Street, Liberty Street, and Cortlandt Street';
	$ayear[] = 1972;
	$atype[] = 'Special Permit Plaza';
	$afqvenu[] = '4a663f7cf964a52046c81fe3';

########################################################
#	GET FOURSQUARE DETAILS							   #
########################################################

	$busers = array();
	$bstat = array();

	for($i = 0; $i<=$ncnt; $i++) {

		if ($afqvenu[$i] != null) {

			$busersName = array();
			$busersPhoto = array();
			$busersGen = array();
			$busersTwit = array();
			$busersFb = array();

			$venue = $fsObj->get('/venues/'.$afqvenu[$i].'/herenow');
			// var_dump($venue->response->hereNow->items);
		
			$bstat[] = 'GOOD';

			foreach ( $venue->response->hereNow->items as $checkin )
			{
				foreach(get_object_vars($checkin->{'user'}) as $key => $value){
					if ($key == 'id') {

						$twitteru = $fsObj->get('/users/'.$value);

						foreach(get_object_vars($twitteru->{'response'}) as $ckey => $cvalue){
							foreach($cvalue as $bkey => $bvalue){
								if ($bkey == 'contact') {
									foreach($bvalue as $dkey => $dvalue){
										if ($dkey == 'twitter') {
											$busersTwit[] = $dvalue;
										}
										if ($dkey == 'facebook') {
											$busersFb[] = $dvalue;
										}
									}
								}
							}
						}	
					}
					if ($key == 'firstName') {
						$busersName[] = $value;
					}
					if ($key == 'photo') {
						$busersPhoto[] = $value;
					}
					if ($key == 'gender') {
						$busersGen[] = $value;
					}
				}
			}
			$busers[] = array($busersName, $busersPhoto, $busersGen, $busersTwit, $busersFb);
		} else { // if afqvenu null
			$busers[] = array('', '', '', '', '');
			$bstat[] = 'NO DATA';
		}

	}

########################################################
#	JSON OUTPUT IF POSTED							   #
########################################################

	if ($_GET["get_spaces"]=="nyc") {
		?><pre><?php var_dump($busers); ?></pre><?php
		?><pre><?php var_dump($bstat);  ?></pre><?php
	} else {
 		echo "success|".json_encode($aaddy)."|".json_encode($aname)."|".json_encode($aloc)."|".json_encode($ayear)."|
				".json_encode($atype)."|".json_encode($busers)."|".json_encode($bstat)."|".$ncnt;
	}
}
