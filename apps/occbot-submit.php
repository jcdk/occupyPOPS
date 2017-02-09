<?php

require_once(__DIR__.'/../config.php');
// ESTABLISH DB CONNECTION TO DB
$connect_ID=mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
mysql_select_db(DB_NAME_ALT) or die ("Could not connect to database");

					$quote = "";
					$selAuth = "";
					$quoteAuth = "";
					$quoteTitle = "";
					$quoteHndl = "";

					$quote = addslashes($_POST['quote']);
					$selAuth = $_POST['selAuthor'];
					$quoteAuth = addslashes($_POST['quote_author']);
					$quoteTitle = addslashes($_POST['quote_title']);
					$quoteHndl = $_POST['quote_handle'];

		$query = "SELECT FLOOR(AVG(stat)) as stat FROM quoteTbl";
		$results = mysql_query($query) or die(mysql_error());

		while ($row = mysql_fetch_object($results)) {

			$avgstat = $row->stat;

		}

	if ($selAuth == "Other") {
		$query = "INSERT INTO srcTbl (authorAbr,fullSrc,authorTwitter) 
				VALUES ('".$quoteAuth."','".$quoteTitle."','".$quoteHndl."')";
		mysql_query($query) or die(mysql_error());	
		$selAuth = mysql_insert_id();	
	}

	if (!empty($quote) && !empty($selAuth)) {
		$query = "INSERT INTO quoteTbl (text,src,stat) 
				VALUES ('\"".$quote."\"','".$selAuth."',".$avgstat.")";
		mysql_query($query) or die(mysql_error());
	}

?>
		<div data-role="page" id="inserted">
			<div data-role="header" data-id="fool">
				<a href="#mainpage" data-rel="back" data-icon="arrow-l">Back</a>
				<h2 style="margin:0.6em 1em .8em; text-align:left;"></h2>
				<a href="occbot-input.php" data-rel="page" data-icon="arrow-l">New</a>
			</div>
			<div data-role="content">
				<?php
					echo "quote: ".$_POST['quote']."<br/>";
					echo "Author: ".$_POST['selAuthor']."<br/>";
					echo "new Author: ".$_POST['quote_author']."<br/>";
					echo "new Title: ".$_POST['quote_title']."<br/>";
					echo "new Handle: ".$_POST['quote_handle']."<br/>";
				?>
			</div>


		</div>
