<?php
print '<h3>' . htmlspecialchars($header) . '</h3>';
print '<ul>';
foreach($documents as $d)
	{
	print '<li><a href="/a/document/' . $d->id . '">' . htmlspecialchars($d->name()) . '</a></li>';
	}
print '</ul>';
?>
