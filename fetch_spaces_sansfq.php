<?php
	ini_set('memory_limit', '150M');

// Load secret config settings.
require('config.php');

//Connect to Database
$connect_ID=mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
mysql_select_db("DB_NAME") or die ("Could not connect to database");

ob_start();

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
			HAVING distance < 1 ORDER BY distance LIMIT 7; ";
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

########################################################
#	JSON OUTPUT IF POSTED							   #
########################################################

	if ($_GET["get_spaces"]=="nyc") {
		?><pre><?php var_dump($busers); ?></pre><?php
		?><pre><?php var_dump($bstat);  ?></pre><?php
	} else {
 		echo "success|".json_encode($aaddy)."|".json_encode($aname)."|".json_encode($aloc)."|".json_encode($ayear)."|
				".json_encode($atype)."|".json_encode($aid)."|".$ncnt;
	}
}

########################################################
#	GET A POPS										   #
########################################################
if ($_POST["get_spaces"]=="pops" || $_GET["get_spaces"]=="pops") { 
	//Get the new coordinates to crop the image.

	// FORMAT THE DATA PRIOR TO INSERT
	if ($_POST["get_spaces"]=="pops") {
		$pid = addslashes($_POST["pid"]);
	} else {
		$pid = addslashes($_GET["pid"]);
	}

	$altype = array();
	$adesc = array();
	$incd = array();

	// GET LAST LOCATION
	$querys = "SELECT nyc_pops.id, BuildingAddress, BuildingName, BuildingLocation, YearCompleted, PublicSpace1, fq_venue, Lat, Lng 
			FROM nyc_pops 
			LEFT JOIN nyc_partipops np on nyc_pops.id = np.pops_id 
			WHERE nyc_pops.id = ".$pid."; ";
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
			$asize[] = $row->PS1Size;
			$afqvenu[] = $row->fq_venue;

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
				".json_encode($atype)."|".json_encode($asize)."|".json_encode($aid)."|".$ncnt;
	}
}