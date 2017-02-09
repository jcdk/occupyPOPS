<?php

// Load secret config settings.
require('config.php');

//Connect to Database
$connect_ID=mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
mysql_select_db("DB_NAME") or die ("Could not connect to database");

					$ps_open = "";
					$ps_sign = "";
					$ps_aware = "";
					$ps_oactivity = "";
					$ps_word = "";

					$pid = $_POST['pid'];
					$ps_disa = $_POST['ps_disa'];
					if ($ps_disa=="on") { $ps_disa = 1; } else { $ps_disa = 0; }
					$ps_open = $_POST['ps_open'];
					if ($ps_open=="on") { $ps_open = 1; } else { $ps_open = 0; }
					$ps_sign = $_POST['ps_sign'];
					if ($ps_sign=="on") { $ps_sign = 1; } else { $ps_sign = 0; }
					$ps_aware = $_POST['ps_aware'];
					if ($ps_aware=="on") { $ps_aware = 1; } else { $ps_aware = 0; }
					$ps_welc = $_POST['ps_welc'];
					if ($ps_welc=="on") { $ps_welc = 1; } else { $ps_welc = 0; }
					$ps_ease = $_POST['ps_ease'];
					$ps_people = $_POST['ps_people'];
					$ps_seating = $_POST['ps_seating'];
					$ps_trees = $_POST['ps_trees'];
					$ps_activity = $_POST['ps_activity'];

					if(empty($ps_activity))
					  {
					    $ps_actin = 0;
					  }
					  else
					  {
					    $ps_actin = count($ps_activity);
 					  }

					$ps_oactivity = addslashes($_POST['ps_oactivity']);
					$ps_word = addslashes($_POST['ps_word']);

		$query = "INSERT INTO nyc_reports (popsID,ps_open,ps_sign,ps_aware,ps_welc,ps_ease,ps_people,ps_seating,ps_trees,ps_activity,ps_oactivity,ps_word,ps_disa) 
				VALUES (".$pid.",".$ps_open.",".$ps_sign.",".$ps_aware.",".$ps_welc.",".$ps_ease.",".$ps_people.",".$ps_seating.",".$ps_trees.",".$ps_actin.",'".$ps_oactivity."','".$ps_word."','".$ps_disa."')";
		mysql_query($query) or die(mysql_error());	
		$newReport = mysql_insert_id();	

	if ($ps_actin>0) {
		for($i=0; $i < $ps_actin; $i++)
		    {
				$query = "INSERT INTO nyc_reports_act (reportID,activityID) 
						VALUES (".$newReport.",".$ps_activity[$i].")";
				mysql_query($query) or die(mysql_error()." ".$query);
		    }
	}

?>
		<div data-role="page" id="inserted">
			<div data-role="header" data-id="fool">
				<h2 style="margin:0.6em 1em .8em; text-align:left;"></h2>
			</div>
			<div data-role="content">
				<?php
					echo "Thanks for posting!<br/><br/>Collected figures on this space will be added soon. - cdk";
				?>
     	 		<a href="#mainpage" id="nextPls" data-icon="arrow-r" class="button" data-role="button"  data-theme="b">go to another POPS</a>
			</div>


		</div>