<?php

session_start();

// require('access.php');

?>

<html>
	<head>
		<title>POPSnyc</title> 
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
				{'enableHighAccuracy':true,'timeout':20000,'maximumAge':0}
			);

		$(document).ready(function () { 
            $("#refreshb").click(refres_location); 
		});

			var watchProcess = null;  

			function refres_location() {
				navigator.geolocation.getCurrentPosition(
					gotPosition,
					errorGettingPosition,
					{'enableHighAccuracy':true,'timeout':20000,'maximumAge':0}
				);
			}

			$('#popstoura').live('pageshow', function () {
   				get_pops(<?php echo $_GET['id']; ?>);
			});

			function handle_pops_query(position) {
			    var text = "You are within "  + Math.round(position.coords.accuracy)  + "m of the . . . <br/>";
				text = "";
   				$("#Output").html(text);
				$("#msgs").html('');
				$('#SqlStatus').show().html('scanning...');
				$.ajax({
					type: 'POST',
					url: 'fetch_spaces_sansfq.php',
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
						var aid = jQuery.parseJSON(response[6]);
						var responseCnt = response[7];
						var caddy = '';
						var ctype = '';
						var xusers = '';
						var cstat = '';
						var px = 0;
						if(responseType=="success"){
							for(var i = 0; i<responseCnt; i++) {

								caddy = aaddy[i];
								cname = aname[i];
								cloc = aloc[i];
								cyear = ayear[i];
								ctype = atype[i];
								caid = aid[i];

								$('#SqlStatus').show().html('');

								$('#Output').append('<h3 style="clear:both;">' + caddy + '</h3>');
								$('#Output').append('<p>' + cname
															 + '<br/> ' + cloc
															 + '<br/> ' + cyear
															 + '<br/> ' + ctype
															 + '<br/> ' + cstat
															 + '<a href="addFieldReport.php?id=' + caid + '" data-role="button" data-prefetch="true" data-icon="arrow-r" >submit field report</a>'
															 + '</p>');

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

			function get_pops(id) {
				$('#popstouraOutput').show().html('scanning...');
				$.ajax({
					type: 'POST',
					url: 'fetch_spaces_sansfq.php',
					data: 'get_spaces=pops&pid='+id,
					cache: true,
					success: function(response){

		   				$("#popstouraOutput").html('');

						response = unescape(response);
						var response = response.split("|");
						var responseType = response[0];
						var aaddy = jQuery.parseJSON(response[1]);
						var aname = jQuery.parseJSON(response[2]);
						var aloc = jQuery.parseJSON(response[3]);
						var ayear = jQuery.parseJSON(response[4]);
						var atype = jQuery.parseJSON(response[5]);
						var asize = jQuery.parseJSON(response[6]);
						var aid = jQuery.parseJSON(response[7]);
						var responseCnt = response[8];
						var caddy = '';
						var ctype = '';
						var xusers = '';
						var cstat = '';
						var px = 0;
						if(responseType=="success"){
							for(var i = 0; i<responseCnt; i++) {

								caddy = aaddy[i];
								cname = aname[i];
								cloc = aloc[i];
								cyear = ayear[i];
								ctype = atype[i];
								caid = aid[i];
								csize = asize[i];

								$('#popstouraOutput').append('<h3 style="clear:both;">Are you at the ' + caddy + ' POPS?</h3>');
								$('#popstouraOutput').append('<p>According to the City it is located ... \"' + cloc + '</p>');

								$('#popstouraLink').html('<a href="addFieldReport.php?id=' + aid + '" data-role="button" data-icon="arrow-r">yes, I am here!</a>');

							}

						}else{
							$('#popstouraOutput').show().html('<b>Unexpected Error</b><br/> <p>Please try again</p>'+response);
						}
					}
				});
			}

		</script>
<script type="text/javascript">
    var _gaq = _gaq || [];

    (function() {
      var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
      ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
      var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();
</script>

	</head>
	<body>
		<div data-role="page" id="mainpage">
			<div data-role="header" data-id="fool">
				<a href="#about" data-icon="gear" data-rel="dialog" data-transition="flip" class="ui-btn-right">About</a>
				<h2 style="margin:0.6em 1em .8em; text-align:left;">POPSnyc</h2>
			</div>
			<div data-role="content">
     	 		<a href="#" id="refreshb" data-icon="refresh" class="button" data-role="button"  data-theme="b">refresh</a>
				<div id="msgs" style="height:20px;text-align:center;"></div>
				<span id="Output" style="display: block;"></span>
				<span id="SqlStatus" style="display: block;clear:both;"></span>
				<span id="gpsStatus"></span>
			</div>
		</div>
		<div data-role="page" id="popstoura">
			<div data-role="header" data-id="fool">
				<a href="#about" data-icon="gear" data-rel="dialog" data-transition="flip" class="ui-btn-right">About</a>
				<h2 style="margin:0.6em 1em .8em; text-align:left;">Welcome to this week's POPS</h2>
			</div>
			<div data-role="content">
				<span id="popstouraOutput" style="display: block;"></span>
				<div id="popstouraLink" style="display: block;"></div>
			</div>
		</div>
		<div data-role="page" id="about">
			<div data-role="header" data-id="fool">
				<h2 style="margin:0.6em 5px .8em;"><?php echo $headline; ?></h2>
			</div>
			<div data-role="content">
			<img src="icon.png" class="ui-overlay-shadow"/><br/>
			<p><b>POPS</b>nyc is a public-making data project that interfaces with City data on privately-owned public spaces in the built environment.</p>
			<p>This project aims to contribute usability and experience sampling data to public understanding of these spaces.</p>
			<p>by cdk</p>
			</div>
		</div>
<script type="text/javascript">
$('[data-role=page]').live('pageshow', function (event, ui) {
    try {
        _gaq.push(['_setAccount', 'UA-2343767-1']);

        hash = location.hash;

        if (hash) {
            _gaq.push(['_trackPageview', hash.substr(1)]);
        } else {
            _gaq.push(['_trackPageview']);
        }
    } catch(err) {

    }

});
</script>
	</body>
</html>