<h2>Payment details:</h2>
<table>
<?php
$ys = $y->student();
$ym = $y->money();
$yd = $y->document();
$x = $y->paypaltxn();
$xx = (count($x)) ? array_pop($x) : false;
$show_x = ($x === false) ? '' : $xx->info();

print '<tr><th>ID</th><td>' . $y->id . '</td></tr>';
print '<tr><th>date</th><td>' . substr($y->created_at(), 0, 10) . '</td></tr>';
print '<tr><th>student</th><td><a href="/a/student/' . $y->student_id() . '">' . htmlspecialchars($ys->name()) . '</a></td></tr>';
print '<tr><th>document</th><td><a href="/a/document/' . $y->document_id() . '">' . htmlspecialchars($yd->name()) . '</a></td></tr>';
print '<tr><th>money</th><td>' . $ym->show_with_code() . '</td></tr>';
print '<tr><th>details</th><td>' . htmlspecialchars($y->details()) . '</td></tr>';
print '<tr><th>PayPal</th><td>' . nl2br(htmlspecialchars($show_x)) . '</td></tr>';
?>
</table>
