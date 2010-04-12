<?php


/**
 * Facebook Public Status Feed
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/facebook-platform/facebook.php';

require_once ($_SERVER['DOCUMENT_ROOT'] . '/lib/adodb/adodb.inc.php');
//require_once ($_SERVER['DOCUMENT_ROOT'] . '/lib/adodb/adodb-active-record.inc.php');
//require_once ($_SERVER['DOCUMENT_ROOT'] . '/lib/adodb/adodb-exceptions.inc.php');

$appapikey = null;
$appsecret = null;
$adoConnectString = null;

require_once ('facebookfeed.class.php');