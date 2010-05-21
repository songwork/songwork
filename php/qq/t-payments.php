<?php
if(count($payments) == 0)
	{
	print '<p>';
	print "As students purchase your Songwork lessons, you'll see the payment details here.  Sorry as of now, there are none.";
	print '</p>';
	}
else
	{
	print '<h2>Your Payments</h2>';
	print '<ul>';
	foreach($payments as $y)
		{
		$m = $y->money();
		print '<li>';
		print '<a href="/t/payment/' . $y->id . '">' . substr($y->created_at(), 0, 10) . ': ' . $m->show_with_code() . '</a>';
		print '</li>';
		}
	print '</ul>';
	}
?>
