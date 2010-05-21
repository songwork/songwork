<?php
print '<h3>' . htmlspecialchars($s->name());
print '<br />' . htmlspecialchars($s->email()) . '</h3>';
print '<p>Added: ' . $s->created_at() . '</p>';
print '<form action="/a/student/' . $s->id . '" method="post">';
print '<label for="their_notes">THEIR notes</label>';
print '<textarea id="their_notes" name="their_notes" cols="70" rows="2">' . htmlspecialchars($s->their_notes()) . '</textarea>';
print '<label for="our_notes">OUR (private) notes</label>';
print '<textarea id="our_notes" name="our_notes" cols="70" rows="2">' . htmlspecialchars($s->our_notes()) . '</textarea>';
print '<br /><br /><input type="submit" class="submit" name="action" value="UPDATE" />';
print '</form>';

if(count($payments))
	{
	print '<h3>Payments</h3>';
	print '<table>';
	print '<tr><th>id</th><th>date</th><th>amount</th><th>document</th><th>details</th></tr>';
	foreach($payments as $y)
		{
		$yd = $y->document();
		$ym = $y->money();
		print '<tr>';
		print '<td><a href="/a/payment/' . $y->id . '">' . $y->id . '</a></td>';
		print '<td>' . substr($y->created_at(), 0, 16) . '</td>';
		print '<td>' . $ym->show_with_code() . '</td>';
		print '<td><a href="/a/document/' . $y->document_id() . '">' . htmlspecialchars($yd->name()) . '</a></td>';
		print '<td>' . htmlspecialchars($y->details()) . '</td>';
		print '</tr>';
		}
	print '</table>';
	}
?>
