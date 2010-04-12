<?php


/**
 * Facebook Public Status Feed
 */

require_once ('includes.php');

$feedObj = new PublicFacebookFeed($appapikey, $appsecret, $adoConnectString);
$feedObj->getUserID();
$items = $feedObj->getItems();
$feedObj->dump($items);