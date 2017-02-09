<?php
// Create our twitter API object
require_once('../config.php');
require_once("twitteroauth/twitteroauth.php");
$oauth = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, TOKEN_KEY, TOKEN_SECRET);

// Send an API request to verify credentials
$credentials = $oauth->get("account/verify_credentials");
echo "Connected as @" . $credentials->screen_name;
// Post our new "hello world" status
$oauth->post('statuses/update', array('status' => "hello world"));
?>