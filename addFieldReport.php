<?php

// Load secret config settings.
require("config.php");

//Connect to Database
$connect_ID=mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
mysql_select_db("DB_NAME") or die ("Could not connect to database");

	// FORMAT THE DATA PRIOR TO INSERT
	if ($_POST["get_spaces"]=="pops") {
		$pid = addslashes($_POST["id"]);
	} else {
		$pid = addslashes($_GET["id"]);
	}

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

?>
	<div data-role="page" id="<?php echo $pid; ?>">
			<div data-role="header" data-id="fool">
				<a href="#mainpage" data-rel="back" data-icon="arrow-l">Back</a>
				<h2 style="margin:0.6em 1em .8em; text-align:right;"><?php echo "POPS at ".$aaddy[0]; ?></h2>
			</div>
			<div data-role="content">
				<form action="insertFieldReport.php" method="post">

				<input type="hidden" name="pid" value="<?php echo $pid; ?>">

<div class="containing-element">
	<label for="flip-min">This POPS is currently...</label>
	<select name="ps_open" id="flip-min" data-role="slider">
		<option value="off">Closed</option>
		<option value="on">Open</option>
	</select>
</div>

<div class="containing-element" style="margin-top:20px;">
	<label for="flip-min">Is there a sign here indicating that this is a public space?</label>
	<select name="ps_sign" id="flip-min" data-role="slider">
		<option value="off">No</option>
		<option value="on">Yes</option>
	</select>
</div>


<div class="containing-element" style="margin-top:20px;">
	<label for="flip-min">Were you previously aware of this space?</label>
	<select name="ps_aware" id="flip-min" data-role="slider">
		<option value="off">No</option>
		<option value="on">Yes</option>
	</select>
</div>

<div class="containing-element" style="margin-top:20px;">
	<label for="flip-min">Do you feel welcome to be in this space?</label>
	<select name="ps_welc" id="flip-min" data-role="slider">
		<option value="off">No</option>
		<option value="on">Yes</option>
	</select>
</div>

<div class="containing-element" style="margin-top:20px;">
	<label for="flip-min">Is this space accessible for people with disabilities?</label>
	<select name="ps_disa" id="flip-min" data-role="slider">
		<option value="off">No</option>
		<option value="on">Yes</option>
	</select>
</div>

<div data-role="fieldcontain" data-type="horizontal" style="margin-top:20px;">
    <fieldset data-role="controlgroup">
    	<legend>If this was your first time here, was this POPS easy to find?</legend>
         	<input type="radio" name="ps_ease" id="radio-choice-1" value="1" checked="checked" />
         	<label for="radio-choice-1">Yes</label>

         	<input type="radio" name="ps_ease" id="radio-choice-2" value="2"  />
         	<label for="radio-choice-2">No</label>

         	<input type="radio" name="ps_ease" id="radio-choice-3" value="3"  />
         	<label for="radio-choice-3">I've been here before.</label>
    </fieldset>
</div>

<label for="slider-step" style="margin-top:20px;">How many people are in this space right now? (Exclude members of your group)</label>
<input type="range" name="ps_people" id="slider-step" value="0" min="0" max="100" />

<label for="slider-step" style="margin-top:20px;">How many people can be comfortably seated in this space at the same time?</label>
<input type="range" name="ps_seating" id="slider-step" value="0" min="0" max="100" />

<label for="slider-step" style="margin-top:20px;">How many trees are in this space?</label>
<input type="range" name="ps_trees" id="slider-step" value="0" min="0" max="50"/>

<div data-role="fieldcontain" style="margin-top:20px;">
    <fieldset data-role="controlgroup">
	   <legend>Which of the following activities would you likely select to conduct in this space?</legend>
		<?php 
				$querybc = "SELECT id, act_name FROM nyc_activities ORDER BY act_name ASC";
				$resultsbc = mysql_query($querybc);
				$cc = 1;
				if ($resultsbc) {
					while ($rowbc = mysql_fetch_object($resultsbc)) {
						echo "
		<input type=\"checkbox\" name=\"ps_activity[]\" id=\"checkbox-".$cc."\" class=\"custom\" value=\"".$rowbc->id."\"/>
		<label for=\"checkbox-".$cc."\">".$rowbc->act_name."</label>
						";
						$cc++;
					}
				}						
			?>
    </fieldset>
</div>

<label for="basic" style="margin-top:20px;">What other activities would you suggest for this space?</label>
<input type="text" name="ps_oactivity" id="basic" value="" data-mini="true" />

<label for="basic" style="margin-top:20px;">Describe this space in a single word?</label>
<input type="text" name="ps_word" id="basic" value="" data-mini="true" />

			<div data-role="fieldcontain" style="margin:5px;">               
				<input type="submit" name="submit" class="button" value="submit" id="save_form" />
			</div>

			   			</div>
					</div>
				</form>
			</div>
	</div>
