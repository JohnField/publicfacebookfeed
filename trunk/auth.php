<?php


/**
 * Facebook Public Status Feed
 */

require_once ('includes.php');

$feedObj = new PublicFacebookFeed($appapikey, $appsecret, $adoConnectString);

//Using extended permissions, get from the stream a user's activity marked as public only  

//This just authorizes numerous permissions. It's a seperate URL to keep any future index.php functionality seperate
//the intent is that a feed can then get data without the wallbanging of being forced to go via by the FB site itself 

//http://wiki.developers.facebook.com/index.php/Extended_permissions

//TODO: use http://www.facebook.com/authorize.php?api_key=YOUR_API_KEY&v=1.0&ext_perm=offline_access in the link

$feedObj->setLocalSessionKey();