<h2>Payments:</h2>
<table>
<tr>
<th>details</th>
<th>date</th>
<th>student</th>
<th>document</th>
<th>money</th>
</tr>
<?php
foreach($payments as $y)
	{
	$ys = $y->student();
	$ym = $y->money();
	$yd = $y->document();
	print '<tr>';
	print '<td><a href="/a/payment/' . $y->id . '">' . $y->id . '</a></td>';
	print '<td>' . substr($y->created_at(), 0, 10) . '</td>';
	print '<td><a href="/a/student/' . $y->student_id() . '">' . htmlspecialchars($ys->name()) . '</a></td>';
	print '<td><a href="/a/document/' . $y->document_id() . '">' . htmlspecialchars($yd->name()) . '</a></td>';
	print '<td>' . $ym->show_with_code() . '</td>';
	print '</tr>';
	}
?>
</table>
