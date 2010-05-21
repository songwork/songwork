<h3>Your Paid Videos</h3>
<table class="vidlist">
<tr>
<th>Video</th>
<th>Date paid</th>
</tr>
<?php
foreach($documents as $d)
	{
	$dp = $d->payment_from_student($student->id);
	print '<tr>';
	print '<td class="big"><a href="/s/document/' . $d->id . '">' . htmlspecialchars($d->fullname()) . '</a></td>';
	print '<td><a href="/s/payment/' . $dp->id . '">' . substr($dp->created_at(), 0, 10) . '</a></td>';
	print '</tr>';
	}
?>
</table>
