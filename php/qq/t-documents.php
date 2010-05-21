<h2>Your Videos:</h2>
<ul>
<?php
foreach($documents as $d)
	{
	print '<li><a href="/t/document/' . $d->id . '">' . htmlspecialchars($d->fullname()) . '</a></li>';
	}
?>
</ul>
