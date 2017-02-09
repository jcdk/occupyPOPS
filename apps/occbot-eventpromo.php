<?php
// Create our twitter API object
require_once(__DIR__.'/../config.php');
require_once("twitteroauth/twitteroauth.php");
// ESTABLISH DB CONNECTION TO DB
$connect_ID=mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
mysql_select_db(DB_NAME_ALT) or die ("Could not connect to database");
$oauth = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, TOKEN_KEY, TOKEN_SECRET);

// Send an API request to verify credentials
$credentials = $oauth->get("account/verify_credentials");
// echo "Connected as @" . $credentials->screen_name;

	// CONFIRM NO EVENT TODAY
	$quer = "SELECT * FROM calendar 
				LEFT JOIN nyc_partipops nypp ON popsID = nypp.pops_id LEFT JOIN nyc_pops nyp ON popsID = nyp.id WHERE datediff(eventDate,curdate()) = 0 
				ORDER BY eventDate ASC Limit 1 ";
	$resul = mysql_query($quer) or die(mysql_error().$quer);

	if ($resul && mysql_num_rows($resul) > 0) {

		exit('There is an event today.');

	}

	// SELECT A QUOTE NOT YET TWEETED
	$querys = "SELECT proTxt 
FROM promotxtTbl  
WHERE dayTo = DATEDIFF(
         (SELECT eventDate 
         FROM calendar 
         WHERE datediff(eventDate,curdate()) < 8 and 
               datediff(eventDate,curdate()) > 0 
         ORDER BY eventDate ASC Limit 1),CURDATE()) 
      AND dayTo > 0
LIMIT 10; ";
	$results = mysql_query($querys) or die(mysql_error().$querys);

	$c=0;

	if ($results && mysql_num_rows($results) > 0) {

		while ($row = mysql_fetch_object($results)) {

			$text[] = $row->proTxt;

			$c++;

		}
	} 

	$c--;

	$rkey = rand ( 0 , $c );

	$text_in = $text[$rkey];

	// SELECT THIS WEEKS POPS
	$querys = "SELECT * FROM calendar 
				LEFT JOIN nyc_partipops nypp ON popsID = nypp.pops_id LEFT JOIN nyc_pops nyp ON popsID = nyp.id WHERE datediff(eventDate,curdate()) < 8 and datediff(eventDate,curdate()) > 0
				ORDER BY eventDate ASC Limit 1 ";
	$results = mysql_query($querys) or die(mysql_error().$querys);

	$replacements = array();

	if ($results && mysql_num_rows($results) > 0) {

		while ($row = mysql_fetch_object($results)) {

			$replacements[0] = $row->BuildingAddress;
			$replacements[1] = date('l M j',strtotime($row->eventDate));
			$replacements[2] = date('g a',strtotime($row->eventTime));
			if (!empty($row->PublicSpace1)) {
				$replacements[3] = $row->PublicSpace1;
			} else {
				$replacements[3] = "Privately-Owned Public Space";
			}
			$replacements[4] = 'Manhattan';
			if (!empty($row->PS1Size)) {
				$replacements[5] = $row->PS1Size." sqft";
			} else {
				$replacements[5] = "of unknown size";
			}
			$replacements[6] = "n/a";
			if (!empty($row->YearCompleted)) {
				$replacements[7] = $row->YearCompleted;
			} else {
				$replacements[7] = "recently... um, is the public space at ".$row->BuildingAddress." even finished?";
			}
			if (!empty($row->BuildingName)) {
				$replacements[8] = $row->BuildingName;
			} else {
				$replacements[8] = 'that space at '.$row->BuildingAddress;
			}
			$replacements[9] = "http://projectwith.us/a_space/index.php?space=".$row->id;

		}
	} 

	if (!empty($text_in)) {

		$patterns = array();
		$patterns[0] = '/NEXTPLACE/';
		$patterns[1] = '/NEXTDATE/';
		$patterns[2] = '/NEXTTIME/';
		$patterns[3] = '/PSTYPE/';
		$patterns[4] = '/PSBOROUGH/';
		$patterns[5] = '/PSIZE/';
		$patterns[6] = '/XCHECKINS/';
		$patterns[7] = '/PSDATE/';
		$patterns[8] = '/PSBUILDING/';
		$patterns[9] = '/PSLINK/';
		$text_in = preg_replace($patterns, $replacements, $text_in);

		$rInd = rand(1,15);
		if ($rInd == 1) {
			$text_in .= " #civicmedia";
		} else if ($rInd == 2) {
			$text_in .= " #publicspace";
		} else if ($rInd == 3) {
			$text_in .= " #placemaking";
		} else if ($rInd == 4) {
			$text_in .= " #keeppublic";
		} else if ($rInd == 5) {
			$text_in .= " #publicspace";
		} else if ($rInd == 6) {
			$text_in .= " #publicspace";
		} else if ($rInd == 7) {
			$text_in .= " #nyc";
		} else if ($rInd == 8) {
			$text_in .= " #manhattan";
		}

		$statusT = $text_in;

		echo $statusT;
		$oauth->post('statuses/update', array('status' => $statusT));

	} else {

		echo "no posting today";

	}
?>
