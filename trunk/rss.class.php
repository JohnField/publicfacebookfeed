<?php


/**
 * Public Facebook Status Feed
 * http://vodex.net/public-facebook-feed/
 * http://code.google.com/p/publicfacebookfeed/
 */

/**
 * TODO: use Zend Framework or similar mature platform to create feeds,
 * with caching to avoid polling Facebook
 */
function make_rss($items, $user_id) {
	$now = date('r');

	$return =<<<HTML
<?xml version="1.0"?>
<rss version="2.0">
   <channel>
      <title>Public Facebook Status Feed for user ID $user_id</title>
      <link>http://vodex.net/publicfacebookfeed/rss.php?uid=$user_id</link>
      <description>Public Facebook Status Feed for http://www.facebook.com/profile.php?id=$user_id</description>
      <language>en-gb</language>
	  <pubDate>$now</pubDate>
HTML;
	foreach ($items as $item) {
		$return .=<<<HTML
      <item>
         <title>{$item['headline']}</title>
         <link>{$item['url']}</link>
         <pubDate>{$item['dateLong']}</pubDate>
         <guid>{$item['url']}</guid>
         <description> 
			{$item['description']}
         </description>
      </item>
HTML;
	}
	$return .=<<<HTML
   </channel>
</rss>
HTML;

	return $return;
}