<h2>Teachers</h2>
<table>
<tr>
<th>id</th>
<th>added</th>
<th>name</th>
<th>email</th>
</tr>
<?php
foreach($teachers as $s)
	{
	print '<tr>';
	print '<td>' . $s->id . '</td>';
	print '<td>' . $s->created_at() . '</td>';
	print '<td><a href="/a/teacher/' . $s->id . '">' . htmlspecialchars($s->name()) . '</a></td>';
	print '<td>' . htmlspecialchars($s->email()) . '</td>';
	print '</tr>';
	}
?>
</table>
