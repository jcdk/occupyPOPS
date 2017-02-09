<?php

session_start();

require('access.php');

?>

<html>
	<head>
		<title>POPS in nyc</title> 
		<meta name="viewport" content="width=device-width, initial-scale=1"> 
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta name="apple-mobile-web-app-status-bar-style" content="black" />
		<link rel="stylesheet" href="http://code.jquery.com/mobile/1.0.1/jquery.mobile-1.0.1.min.css" />
		<link rel="apple-touch-icon-precomposed" href="icon.png">
		<link rel="apple-touch-startup-image" href="startup.png">
		<script src="http://code.jquery.com/jquery-1.6.4.min.js"></script>
		<script src="http://code.jquery.com/mobile/1.0.1/jquery.mobile-1.0.1.min.js"></script>
		<link type="text/css" rel="stylesheet" href="css/style.css"/>
	    <script type="text/javascript" charset="utf-8">

			navigator.geolocation.getCurrentPosition(
				gotPosition,
				errorGettingPosition,
				{'enableHighAccuracy':true,'timeout':20000,'maximumAge':Infinity}
			);

			function handle_pops_query(position) {
			    var text = "You are within "  + Math.round(position.coords.accuracy)  + "m of the . . . <br/>";
				text = "";
   				$("#Output").html(text);
				$("#msgs").html('');
				$('#SqlStatus').show().html('scanning...');
				$.ajax({
					type: 'POST',
					url: 'fetch_spaces.php',
					data: 'get_spaces=nyc&lat='+position.coords.latitude+'&lng='+position.coords.longitude+'&accuracy='+position.coords.accuracy,
					cache: true,
					success: function(response){

						response = unescape(response);
						var response = response.split("|");
						var responseType = response[0];
						var aaddy = jQuery.parseJSON(response[1]);
						var aname = jQuery.parseJSON(response[2]);
						var aloc = jQuery.parseJSON(response[3]);
						var ayear = jQuery.parseJSON(response[4]);
						var atype = jQuery.parseJSON(response[5]);
						var busers = jQuery.parseJSON(response[6]);
						var bstat = jQuery.parseJSON(response[7]);
						var responseCnt = response[8];
						var caddy = '';
						var ctype = '';
						var xusers = '';
						var cstat = '';
						var px = 0;
						if(responseType=="success"){
							for(var i = 0; i<=responseCnt; i++) {

								caddy = aaddy[i];
								cname = aname[i];
								cloc = aloc[i];
								cyear = ayear[i];
								ctype = atype[i];
								cstat = bstat[i];

								var userName = new Array();
								var userPhoto = new Array();
								var userGen = new Array();
								var userTw = new Array();
								var userFb = new Array();

								$('#SqlStatus').show().html('<br/><small>'+responseType+'</small>');

								$('#Output').append('<h3 style="clear:both;">' + caddy + '</h3>');
								$('#Output').append('<p>' + cname
															 + '<br/> ' + cloc
															 + '<br/> ' + cyear
															 + '<br/> ' + ctype
															 + '<br/> ' + cstat
															 + '</p>');

								if(cstat=="GOOD"){
									$.each(busers[i], function(key,value) {

										if (key == 0) {
											$.each(value, function(ikey,ivalue) {
												userName[ikey] = ivalue+'<br/>';
											});
										} else if (key == 1) {
											$.each(value, function(ikey,ivalue) {
												userPhoto[ikey] = '<img src="'+ivalue+'" width="100px"/><br/>';
											});
										} else if (key == 2) {
											$.each(value, function(ikey,ivalue) {
												userGen[ikey] = ivalue+'<br/>';
											});
										} else if (key == 3) {
											$.each(value, function(ikey,ivalue) {
												userTw[ikey] = 'twitter: '+ivalue+'<br/>';
											});
										} else if (key == 4) {
											$.each(value, function(ikey,ivalue) {
												userFb[ikey] = 'fb: '+ivalue+'<br/>';
											});
										}
									});
									$.each(userName, function(ikey,ivalue) {
										$('#Output').append('<div style="float:left;display:block;margin:0 5px;">'
												+ivalue
												+userPhoto[ikey]
												+userGen[ikey]
												+userTw[ikey]
												+userFb[ikey]+
											'</div>');
									});

								} else {
										$('#Output').append('<p>help link this location to a foursquare venue</p>');
								}

							}

						}else{
							$('#SqlStatus').show().html('<b>Unexpected Error</b><br/> <p>Please try again</p>'+response);
						}
					}
				});
			}

			function gotPosition(pos)
			{
				$("#msg").html("finding your location...");
				handle_pops_query(pos);
			}

			function errorGettingPosition(err)
			{
				if(err.code==1)
				{
					$("#Output").html("User denied geolocation.");
				}
				else if(err.code==2)
				{
				$("#Output").html("Position unavailable.");
				}
				else if(err.code==3)
				{
				$("#Output").html("Timeout expired.");
				}
				else
				{
				$("#Output").html("ERROR:"+ err.message);
				}
			}
		</script>
	</head>
	<body>
		<div data-role="page" id="mainpage">
			<div data-role="header" data-id="fool">
				<a href="#about" data-icon="gear" data-rel="dialog" data-transition="flip" class="ui-btn-right">About</a>
				<h2 style="margin:0.6em 1em .8em; text-align:left;"><?php echo $headline; ?></h2>
			</div>
			<div data-role="content">
				<div id="msgs" style="height:20px;text-align:center;"></div>
				<span id="Output" style="display: block;"></span>
				<span id="SqlStatus" style="display: block;clear:both;"></span>
				<span id="gpsStatus"></span>
			</div>
		</div>
		<div data-role="page" id="about">
			<div data-role="header" data-id="fool">
				<h2 style="margin:0.6em 5px .8em;"><?php echo $headline; ?></h2>
			</div>
			<div data-role="content">
			<img src="icon.png" class="ui-overlay-shadow"/><br/>
			<p>privately owned public spaces in nyc</p>
			<p>by cdk</p>
			</div>
		</div>
	</body>
</html>