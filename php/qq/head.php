<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<title><?php print htmlspecialchars(strip_tags($pagetitle)); ?> | SongWork</title>
<meta http-equiv="Content-Type" content="<?php print $server['content_type']; ?>; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="/css/style.css" />
<base href="<?php print $basehref; ?>" />
<?php /*  If I go back to Typekit:
<script type="text/javascript" src="http://use.typekit.com/wqh3fmh.js"></script>
<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
*/ ?>
</head>
<body id="<?php print $bodyid; ?>">
<div id="container">
<div id="header">
<div id="navigation">
<h1><a href="/">SongWork</a></h1>
<p id="slogan">work on your songs until your songs work for you</p>
<?php
print '<ul>';
foreach($nav as $navlink => $navshow)
	{
	print "\n" . '<li><a href="/' . $navlink . '">' . htmlspecialchars($navshow) . '</a></li>';
	}
print "\n</ul>\n";
?>
</div>
</div>
<div id="content">
<?php
if($flash)
	{
        print '<h1 id="flash">' . $flash . '</h1>';
	}
?>
