<?php
print '<h3>Payment id# ' . $y->id . '</h3>';
print '<dl>';
print '<dt>Date:</dt>';
print '<dd>' . substr($y->created_at(), 0, 10) . '</dd>';
$s = $y->student();
print '<dt>Student:</dt>';
print '<dd><a href="/t/student/' . $s->id . '">' . htmlspecialchars($s->name()) . '</a></dd>';
$d = $y->document();
print '<dt>Document:</dt>';
print '<dd><a href="/t/document/' . $d->id . '">' . htmlspecialchars($d->name()) . '</a></dd>';
$m = $y->money();
print '<dt>Amount:</dt>';
print '<dd>' . htmlspecialchars($m->show_with_code()) . '</dd>';
print '<dt>Details:</dt>';
print '<dd>' . htmlspecialchars($y->details()) . '</dd>';
print '</dl>';
?>
