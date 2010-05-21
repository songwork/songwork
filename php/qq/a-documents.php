<h2>Videos:</h2>
<table>
<tr>
<th>id</th>
<th>status</th>
<th>name</th>
</tr>
<?php
foreach($documents as $d)
	{
	$showstatus = ($d->status() == 'WAITING') ? '<span class="highlight">WAITING</span>' : $d->status();
	print '<tr>';
	print '<td>' . $d->id . '</td>';
	print '<td>' . $showstatus . '</td>';
	print '<td><a href="/a/document/' . $d->id . '">' . htmlspecialchars($d->fullname()) . '</a></td>';
	print '</tr>';
	}
?>
</table>
