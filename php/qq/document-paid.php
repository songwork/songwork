<?php
print '<h2>' . htmlspecialchars($d->name()) . '</h2>';
print '<p>Teacher: ' . $d->linked_teachernames();
print '. You bought this at <a href="/s/payment/' . $payment->id . '">' . substr($payment->created_at(), 0, 10) . '</a>.';
print '</p>';
?>
