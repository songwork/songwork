<?php
print '<h2>' . htmlspecialchars($d->name()) . '</h2>';
print '<h4>Teacher: ' . $d->linked_teachernames() . '</h4>';
print '<div class="description">';
print nl2br(htmlspecialchars($d->description()));
print '</div>';
print '<div class="details">';
print 'Type: ' . $d->mediatype() . '<br />';
if(strlen($d->length()))
	{
	print 'Length: ' . htmlspecialchars($d->length()) . '<br />';
	}
print 'Size: ' . $d->showsize();
print '</div>';
print '<h4>Price: ' . $d->price_in($currency) . '</h4>';
if(isset($paypal_button))
	{
	print $paypal_button;
	}
?>
