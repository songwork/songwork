<?php
print '<h4>Consulting rate: ' . htmlspecialchars($t->consultation_rate()) . '</h4>';
print '<h5>To schedule a consultation with ' . htmlspecialchars($t->name()) . ', ';
if($student)
	{
	print '<a href="/consultation/' . $t->id . '">click here</a>.';
	}
else
	{
	print '<a href="/signup">sign up</a> or <a href="/login">log in</a>.';
	}
print '</h5>';
?>
