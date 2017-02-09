<?php

session_start();

require_once(__DIR__.'/../config.php');
// ESTABLISH DB CONNECTION TO DB
$connect_ID=mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
mysql_select_db(DB_NAME_ALT) or die ("Could not connect to database");

?>

<html>
	<head>
		<title>POPSnyc</title> 
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no"> 
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta name="apple-mobile-web-app-status-bar-style" content="black" />
		<link rel="stylesheet" href="http://code.jquery.com/mobile/1.0.1/jquery.mobile-1.0.1.min.css" />
		<link rel="apple-touch-icon-precomposed" href="icon.png">
		<link rel="apple-touch-startup-image" href="startup.png">
		<script src="http://code.jquery.com/jquery-1.6.4.min.js"></script>
	    <script type="text/javascript" charset="utf-8">


		</script>
		<script src="http://code.jquery.com/mobile/1.0.1/jquery.mobile-1.0.1.min.js"></script>
	    <script type="text/javascript" charset="utf-8">


$(function () {
   var maxchar = 140;

$("textarea[name='quote']").live('keyup', function() { 

    if ($("#selAuthor option:selected").val() == "Other") {
        var cnt = $(this).val().length + $("input[name='quote_author']").val().length + 5;
	} else {
        var cnt = $(this).val().length + $("#selAuthor option:selected").text().length + 5;
	}
        var remainingchar = maxchar - cnt;
        $(this).next().html(remainingchar);
        if(remainingchar > 0){
            $(this).next().css('color', 'green');
        }else{
            $(this).next().css('color', 'red');
        }

});
});

$(document).ready(function () {

$('#selAuthor').change(function() {

    var authS = $("#selAuthor option:selected").val();
    $("#add_new_src").hide();
    if (authS == "Other") {
        $("#add_new_src").show();
    }
});

});



		</script>
	</head>
	<body>
		<div data-role="page" id="mainpage">
			<div data-role="header" data-id="fool">
				<a href="#about" data-icon="gear" data-rel="dialog" data-transition="flip" class="ui-btn-right">About</a>
				<h2 style="margin:0.6em 1em .8em; text-align:left;"><?php echo $headline; ?></h2>
			</div>
			<form action="occbot-submit.php" method="post"> 
			<div data-role="fieldcontain" style="margin:5px;"> 
                <div id="add_new_src" style="display:none;margin:5px;">
                    <div data-role="fieldcontain" class="ui-hide-label no-field-separator">
                        <label for="quote_name">Select Source</label>
                        <input type="text" id="quote_author" name="quote_author" placeholder="Author" maxlength="50" />
                        <input type="text" id="quote_title" name="quote_title" placeholder="Title" maxlength="50" />
                        <input type="text" id="quote_handle" name="quote_handle" placeholder="Handle" maxlength="50" />
                    </div>
                </div>             
				<select id="selAuthor" name="selAuthor">
                    <option>Select Source</option>
					<?php 

						$querybc = "SELECT ID, authorAbr, fullSrc, authorTwitter FROM srcTbl ORDER BY authorAbr ASC";
						$resultsbc = mysql_query($querybc);

						if ($resultsbc) {
							while ($rowbc = mysql_fetch_object($resultsbc)) {
								echo "<option value=\"".$rowbc->ID."\">".stripslashes($rowbc->authorAbr)."</option>";
							}
						}						
					?>
                    <option value="Other">Add a new source</option>
                </select>
			</div>
			<div data-role="fieldcontain" style="margin:5px;"><br/>
			    <textarea name="quote" id="quote" placeholder="Enter new quote"></textarea>
				<div id="charsLeft">140</div>
			</div>
			<div data-role="fieldcontain" style="margin:5px;">               
				<input type="submit" name="submit" class="button" value="submit" id="save_form" />
			</div>
		</div>
		</form>
		<div data-role="page" id="about">
			<div data-role="header" data-id="fool">
				<h2 style="margin:0.6em 5px .8em;">Add new quote to bot</h2>
			</div>
			<div data-role="content">
			<img src="icon.png" class="ui-overlay-shadow"/><br/>
			<p>privately owned public spaces in nyc</p>
			<p>by cdk</p>
			</div>
		</div>
	</body>
</html>
