<?php
print '<h2>' . htmlspecialchars($t->name()) . '</h2>';
print '<div class="headshot"><img src="/images/teacher-' . $t->id . '.jpg" width="200" height="200" alt="' . htmlspecialchars($t->name()) . '" /></div>';
print '<div class="description">';
print $t->profile();
print '</div>';

print '<h3>Videos:</h3>';
print '<ul class="documents">';
foreach($documents as $d)
	{
	print '<li>';
	print '<h4><a href="/document/' . $d->id . '">' . htmlspecialchars($d->name()) . '</a></h4>';
	print $d->showsize() . ' ' . $d->mediatype();
	print '. Price: ' . $d->price_in('USD');  # TODO: var
	print '</li>';
	}
print '</ul>';
?>
