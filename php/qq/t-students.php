<?php
if(count($students) == 0)
	{
	print '<p>';
	print "As students purchase your Songwork lessons, they will appear here.  Sorry as of now, there are none.";
	print '</p>';
	}
else
	{
	print '<h2>Your Students</h2>';
	print '<ul>';
	foreach($students as $s)
		{
		print '<li>';
		print '<a href="/t/student/' . $s->id . '">' . htmlspecialchars($s->name()) . '</a>';
		print '</li>';
		}
	print '</ul>';
	}
?>
