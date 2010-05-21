<?php
print '<h3>' . htmlspecialchars($s->name());
print '<br />' . htmlspecialchars($s->email()) . '</h3>';
if(strlen($s->their_notes()))
	{
	print '<h4>Their notes:</h4>';
	print '<p>' . nl2br(htmlspecialchars($s->their_notes())) . '</p>';
	}

print '<form action="/t/student/' . $s->id . '" method="post">';
print '<label for="our_notes">OUR (private) notes</label>';
print '<textarea id="our_notes" name="our_notes" cols="40" rows="4">' . htmlspecialchars($s->our_notes()) . '</textarea>';
print '<input type="submit" name="action" value="UPDATE" />';
print '</form>';

if(count($payments))
	{
	print '<h3>Payments</h3>';
	print '<table>';
	print '<tr><th>date</th><th>amount</th><th>document</th></tr>';
	foreach($payments as $y)
		{
		$yd = $y->document();
		$ym = $y->money();
		print '<tr>';
		print '<td>' . substr($y->created_at(), 0, 16) . '</td>';
		print '<td>' . $ym->show_with_code() . '</td>';
		print '<td><a href="/t/document/' . $y->document_id() . '">' . htmlspecialchars($yd->name()) . '</a></td>';
		print '</tr>';
		}
	print '</table>';
	}
?>
