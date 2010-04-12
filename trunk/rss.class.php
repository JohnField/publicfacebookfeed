<?php


// via http://www.w3schools.com/rss/rss_item.asp
function make_rss($items, $user_id) {
	$now = date('r');

	$return =<<<HTML
<?xml version="1.0"?>
<rss version="2.0">
   <channel>
      <title>Public Facebook Statuses</title>
      <link>http://vodex.net/publicstatusfeed/rss.php?uid=$user_id</link>
      <description>Public Facebook Statuses for http://www.facebook.com/profile.php?id=$user_id</description>
      <language>en-gb</language>
	  <pubDate>$now</pubDate>
HTML;
	foreach ($items as $item) {
		$return .= make_rss_item($item);
	}
	$return .=<<<HTML

   </channel>
</rss>
HTML;

	return $return;
}

function make_rss_item($item) {
	//<description>saafddssd<a href="http://commentisfree.guardian.co.uk/stevebell/index.html><img alt="" src="{$item['url']}"/></a></description>
	$return =<<<HTML
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
	return $return;
}


