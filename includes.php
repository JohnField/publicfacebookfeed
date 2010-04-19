<?php


/**
 * Public Facebook Status Feed
 * http://vodex.net/public-facebook-feed/
 * http://code.google.com/p/publicfacebookfeed/
 */

require_once ($_SERVER['DOCUMENT_ROOT'] . '/lib/adodb/adodb.inc.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/lib/adodb/adodb-active-record.inc.php');
//require_once ($_SERVER['DOCUMENT_ROOT'] . '/lib/adodb/adodb-exceptions.inc.php');

require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/facebook-platform/facebook.php';

$appapikey = null;
$appsecret = null;
$adoConnectString = null;

require_once ('facebookfeed.class.php');

