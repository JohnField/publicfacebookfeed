<?php


/**
 * Public Facebook Status Feed
 * http://vodex.net/public-facebook-feed/
 * http://code.google.com/p/publicfacebookfeed/
 */

require_once ('includes.php');

$feedObj = new PublicFacebookFeed($appapikey, $appsecret, $adoConnectString);
$feedObj->getUserID();
$items = $feedObj->getItems();
$feedObj->dump($items);