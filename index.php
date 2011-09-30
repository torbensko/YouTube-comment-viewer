<?php

// This code is provided under a Creative Commons Attribution license.
// Details: http://creativecommons.org/licenses/by/3.0/
// Basically you are free to use the code for any purpose as long as you 
// remember to mention my name (Torben Sko) at some point.
// 
// Please also note that my code is provided AS IS with NO WARRANTY 
// OF ANY KIND, INCLUDING THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS
// FOR A PARTICULAR PURPOSE.

// For more info, please see:
// http://code.google.com/apis/youtube/2.0/developers_guide_php.html

set_include_path(getcwd().'/library'.PATH_SEPARATOR.get_include_path()); // add the google library
require_once 'Zend/Loader.php'; // the Zend dir must be in your include_path
Zend_Loader::loadClass('Zend_Gdata_YouTube');

echo "<html><head><style> body {font-family:Arial; font-size:10pt} .wrap { padding-bottom:10px; }</style>";
if(isset($_GET['vidID'])) {
	getAndPrintCommentFeed($_GET['vidID']);
} else { ?>
	</head>
	<body>
		<form type="GET" action="<?= $_SERVER['PHP_SELF'] ?>">
			Video ID: <input type="text" name="vidID" value="dQw4w9WgXcQ" />
			<p>
			<input type="checkbox" name="showComment" checked /> Comment<br>
			<input type="checkbox" name="showDate" checked /> Date<br>
			<input type="checkbox" name="showAuthor" checked /> Author<br>
			<input type="checkbox" name="showCount" /> Count
			<p>
			<input type='submit' />
		</form> 
	</body> <?php
}
echo "</html>";

function getAndPrintCommentFeed($videoId)
{
	$yt = new Zend_Gdata_YouTube();
	$yt->setMajorProtocolVersion(2);
	$videoEntry = $yt->getVideoEntry($videoId);
	echo "<title>{$videoEntry->getVideoTitle()}</title>";
	echo "</head><body>";
	$commentFeed = $yt->getVideoCommentFeed($videoId);
	try {
		do {
			printCommentFeed($commentFeed);
		} while($commentFeed = $commentFeed->getNextFeed());
	} catch (Zend_Gdata_App_Exception $e) {}
	echo "</body>";
}

$count = 1;
function printCommentFeed($commentFeed) 
{
	global $count;
	foreach ($commentFeed as $commentEntry) {
		printCommentEntry($commentEntry, $count++);
	}
}

function printCommentEntry($commentEntry, $count) 
{
	echo "<div class='wrap'>";
	if($_GET['showCount'])
		echo "<div class='count'>{$count}</div>";
	if($_GET['showDate'])
		echo "<div class='time'>{$commentEntry->published->text}</div>";
	if($_GET['showComment'])
		echo "<div class='comment'>{$commentEntry->content->text}</div>";
	if($_GET['showAuthor'])
		echo "<div class='author'>{$commentEntry->author[0]->name->text}</div>";
	echo "</div>";
}

	?>
	</body>
</html>