<?php


/**
 * Facebook Public Status Feed
 */

require_once ('includes.php');

//Using extended permissions, get from the stream a user's activity marked as public only  

//This just authorizes numerous permissions. It's a seperate URL to keep any future index.php functionality seperate
//the intent is that a feed can then get data without the wallbanging of being forced to go via by the FB site itself 

//http://wiki.developers.facebook.com/index.php/Extended_permissions

//TODO: use http://www.facebook.com/authorize.php?api_key=YOUR_API_KEY&v=1.0&ext_perm=offline_access in the link

$user_id = $facebook->require_login($required_permissions = 'offline_access,read_stream');
if ($user_id) {
	dump("\$user_id of $user_id found");
} else {
	halt("no \$user_id found - permission not given?");
}

//now, get the session key, store, & re-use

$sessionKey = $facebook->api_client->session_key;

$db->execute("replace into offline_access set uid=?, session_key=?", array (
	$user_id,
	$sessionKey
));

if ($user_id) {
	dump("session key for \$user_id $user_id stored - this looks like the only way we can then re-use the offline permission.");
} else {
	halt("culd not save session key for \$user_id $user_id");
}