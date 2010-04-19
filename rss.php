<?php


/**
 * Public Facebook Status Feed
 * http://vodex.net/public-facebook-feed/
 * http://code.google.com/p/publicfacebookfeed/
 */

require_once ('includes.php');
require_once ('rss.class.php');

$feedObj = new PublicFacebookFeed($appapikey, $appsecret, $adoConnectString);
$feedObj->getUserID();
$items = $feedObj->getItems();
//$feedObj->dump($items);

/**
 * Truncate titles over this length in chars
 */
$titleTruncateLength = 100;

//fallback
if (!is_array($items)) {
	$items = array (
		array (
			'created_time' => time(),
			'url' => '',
			'name' => 'No content found; possible error.',
			'message' => 'No content found; possible error.'
		)
	);
}

//bah, hosting...
$rssItems = array ();
foreach ($items as $item) {

	$items['message'] = iconv('UTF-8', 'ASCII//TRANSLIT', $items['message']);
	$items['headline'] = iconv('UTF-8', 'ASCII//TRANSLIT', $items['headline']);

	$rssItem = array ();

	$date = $status['created_time'];
	$rssItem['dateShort'] = date('l, jS F Y', $date);
	$rssItem['dateLong'] = date('r', $date);
	$rssItem['url'] = htmlentities($item['permalink']);
	$rssItem['headline'] = $item['message'];

	//override 'name' for custom situations
	if (isset ($item['name'])) {
		//in event of e.g. photo albums
		$rssItem['headline'] = $item['name'];
	}
	if (isset ($status['attachment']['name'])) {
		//in event of e.g. shared URLs
		$rssItem['headline'] = $item['attachment']['name'];
	}

	$rssItem['headline'] = htmlentities($rssItem['headline']);
	$rssItem['description'] = htmlentities($rssItem['message']);

	if (strlen($rssItem['headline']) > $truncateLength) {
		$rssItem['headline'] = substr($rssItem['headline'], $truncateLength) . '&hellip;';
	}

	$rssItems[] = $rssItem;

}

//$feedObj->dump($rssItems);

header('Content-Type:application/rss');

echo make_rss($rssItems, $userID);