<?php include("php/includes/start.inc.php"); ?>
<?php header("Content-Type: application/rss+xml; charset=utf-8"); ?>
<?php
	echo "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n";
?>
<rss version="2.0"
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:wfw="http://wellformedweb.org/CommentAPI/"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:atom="http://www.w3.org/2005/Atom"
	>
<?php

	$stmt_rss = "SELECT headline, object_name, filename, caption, country, state, location, photographer, city, DATE_FORMAT(timestamp,'%a, %d %b %Y %H:%i:%s +0200') AS datum, date FROM picture_data group by headline order by filename DESC LIMIT 0,20";
	$query_rss = mysql_query($stmt_rss);

	$rss  = "<channel>\n";
	$rss .= "<title>".$TEXT['meta-firma']."</title>\n";
	$rss .= "<link>http://".$_SERVER['HTTP_HOST']."/".INSTALLPATH."/</link>\n";
	$rss .= "<description>".utf8_decode(html_entity_decode($TEXT['meta-description']))."</description>\n";
	$rss .= "<pubDate>".date("D, d M Y H:i:s O")."</pubDate>\n";
	$rss .= "<generator>".$TEXT['meta-firma']."</generator>\n";
	$rss .= "<language>de</language>\n";
	$rss .= "<atom:link href=\"http://".$_SERVER['HTTP_HOST']."/".INSTALLPATH."/rss.php\" rel=\"self\" type=\"application/rss+xml\" />\n";

	while($result_rss = mysql_fetch_array($query_rss)){

		$year = substr($result_rss['date'],0,4);
		$month = substr($result_rss['date'],4,2);
		$day = substr($result_rss['date'],6,2);

		$rss .=	"<item>\n";
		$rss .= "<guid isPermaLink=\"false\">".md5($result_rss[headline])."</guid>\n";
		$rss .=	"<title><![CDATA[".utf8_encode($result_rss[object_name])."]]></title>\n";
		$rss .= "<link>http://".$_SERVER['HTTP_HOST']."/".INSTALLPATH."/index.php?q=$result_rss[object_name]&amp;year=".$year."</link>\n";
		$rss .=	"<description><![CDATA[<h1>".utf8_encode($result_rss[headline])."</h1>";
		$rss .= "<p><img src=\"http://".$_SERVER['HTTP_HOST']."/".INSTALLPATH."/thumbs/".$result_rss[filename]."\" align=\"left\"/>";
		$rss .= utf8_encode($result_rss[caption])."</p>";
		$rss .= "<p><strong>Ort:</strong> ".utf8_encode($result_rss[city])."<br />";
		$rss .= "<strong>Venue:</strong> ".utf8_encode($result_rss[location])."<br />";
		$rss .= "<strong>Datum:</strong> ".$day.".".$month.".".$year."<br />";
		$rss .= "<strong>Land:</strong> ".$result_rss[state]."/".$result_rss[country]."<br />";
		$rss .= "<strong>Fotograf:</strong> ".utf8_encode($result_rss[photographer])."<br />";
		$rss .= "</p><p>Bildanfrage an: ".$CONFIG['besitzer'].", E-Mail: <a href=\"mailto:".$CONFIG['email']."?subject=Bildanfrage zu ".$result_rss[object_name]."\">".$CONFIG['email']."</a>.<br />";
		$rss .= "</p>]]></description>\n";
		$rss .= "<pubDate>".$result_rss[datum]."</pubDate>\n";
		$rss .=	"</item>\n";
	}

	$rss .=	"</channel>\n";
	$rss .=	"</rss>\n";

	echo $rss;
	exit;
?>