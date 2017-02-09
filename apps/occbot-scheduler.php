<?php 

require_once(__DIR__.'/../config.php');
// ESTABLISH DB CONNECTION TO DB
$connect_ID=mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
mysql_select_db(DB_NAME_ALT) or die ("Could not connect to database");

	// $next_week_day = rand(2,6);

	// SELECT LAST EVENT
	// $querye = "SELECT DATE_ADD( eventDate, INTERVAL ".$next_week_day." DAY ) as eDate  
	//			FROM calendar c 
	//			ORDER BY eventDate DESC LIMIT 1";
	// $resulte = mysql_query($querye) or die(mysql_error().$querye);


	// if ($resulte && mysql_num_rows($resulte) > 0) {

	//	while ($rowe = mysql_fetch_object($resulte)) {

	//		$idate = $rowe->eDate;

	//	}
	// }

	$idate = date('Y-m-d', strtotime( '+'.mt_rand(2,5).' days'));

	// SELECT A POPS NOT YET COORDINATED
	$querys = "SELECT count(distinct(popsID)) as completed, np.id AS pops, BuildingAddress 
				FROM calendar c 
				RIGHT JOIN nyc_pops np ON popsID=np.id 
				GROUP BY np.id 
				ORDER BY c.id ASC LIMIT 10";
	$results = mysql_query($querys) or die(mysql_error().$querys);

	$c=0;

	if ($results && mysql_num_rows($results) > 0) {

		while ($row = mysql_fetch_object($results)) {

			$apop[] = $row->pops;

			$c++;

		}

	$c--;

	$rpops = rand ( 0 , $c );
	$ipop = $apop[$rpops];

	$queryi = "INSERT INTO calendar (eventDate, eventTime, popsID) VALUES ('".$idate."', '12:00:00', ".$ipop." )";
	mysql_query($queryi) or die(mysql_error().$queryi);

	echo "done";

	}

?>
