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
 echo "Connected as @" . $credentials->screen_name;
// Post our new "hello world" status

	// SELECT A QUOTE NOT YET TWEETED
	$querys = "SELECT quoteTbl.ID, text, authorAbr 
				FROM quoteTbl LEFT JOIN srcTbl on src = srcTbl.ID 
				WHERE stat = (select min(stat) as minStat from quoteTbl); ";
	$results = mysql_query($querys) or die(mysql_error().$querys);

	$c=0;

	if ($results && mysql_num_rows($results) > 0) {

		while ($row = mysql_fetch_object($results)) {

			$id[] = $row->ID;
			$text[] = $row->text;
			$authorAbr[] = $row->authorAbr;

			$c++;

		}
	} 

	$c--;

	$rkey = rand ( 0 , $c );

	$text_in = $text[$rkey];
	$authorAbr_in = " - ".$authorAbr[$rkey];

	$textLen = strlen($text_in);
	$authorLen = strlen($authorAbr_in);
	$comLen = $textLen + $authorLen;

	if ($comLen>140) {

		$strtLen = 0;

		if ($textLen <= 140) {
			$statusText[] = $text_in;
			$statusText[] = $authorAbr_in;
		} else {
			while ($comLen > 140) {
				$statusText[] = substr($text_in,$strtLen,134);
				$comLen = $comLen - 134;
				$strtLen = $strtLen + 134;
			}
			$statusText[] = substr($text_in,$strtLen,134).$authorAbr_in;
		}
	} else {

		$statusText[] = $text_in.$authorAbr_in;

	}

	foreach($statusText as $key=>$statusT) {
		echo $statusT;
		$oauth->post('statuses/update', array('status' => $statusT));

	}

	//UPDATE TO REFLECT TWEET
	$querys = "UPDATE quoteTbl SET stat=stat+1 WHERE ID = ".$id[$rkey]."; ";
	$results = mysql_query($querys) or die(mysql_error().$querys);


?>
