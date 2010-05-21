<h2>Videos</h2>
<?php
print '<h4>Categories: ';
foreach($tags as $t)
	{
	print '<a href="/document#tag-' . $t->id . '">' . htmlspecialchars($t->name()) . '</a> &nbsp; ';
	}
print '</h4>';

foreach(array_reverse($tags, true) as $t)
	{
	print '<div id="tag-' . $t->id . '">';
	print '<h3>' . htmlspecialchars($t->name()) . '</h3>';
	print '<ul class="documents">';
	foreach($t->documents() as $d)
		{
		print '<li>';
		print '<h3><a href="/document/' . $d->id . '">' . htmlspecialchars($d->name()) . '</a></h3>';
		print htmlspecialchars($d->sentence());
		print '<h4>Teacher: ' . $d->linked_teachernames() . '</h4>';
		# print $d->showsize() . ' ' . $d->mediatype();
		# print '. Price: ' . $d->price_in('USD');  # TODO: var
		print '</li>';
		}
	print '</ul>';
	print '</div>';
	}
?>
