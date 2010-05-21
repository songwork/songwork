<h2>Students</h2>
<table>
<tr>
<th>id</th>
<th>added</th>
<th>name</th>
<th>email</th>
</tr>
<?php
foreach($students as $s)
	{
	print '<tr>';
	print '<td>' . $s->id . '</td>';
	print '<td>' . $s->created_at() . '</td>';
	print '<td><a href="/a/student/' . $s->id . '">' . htmlspecialchars($s->name()) . '</a></td>';
	print '<td>' . htmlspecialchars($s->email()) . '</td>';
	print '</tr>';
	}
?>
</table>
