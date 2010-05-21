<h3>Your Payments:</h3>
<table>
<tr>
<th>details</th>
<th>document</th>
<th>amount</th>
</tr>
<?php
foreach($payments as $y)
	{
	$ym = $y->money();
	$yd = $y->document();
	print '<tr>';
	print '<td><a href="/s/payment/' . $y->id . '">' . substr($y->created_at(), 0, 10) . '</a></td>';
	print '<td><a href="/s/document/' . $y->document_id() . '">' . htmlspecialchars($yd->fullname()) . '</a></td>';
	print '<td>' . $ym->show_with_code() . '</td>';
	print '</tr>';
	}
?>
</table>
