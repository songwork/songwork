<?php
print '<h2>' . htmlspecialchars($d->fullname()) . '</h2>';
print '<p>';
print nl2br(htmlspecialchars($d->description()));
print '</p>';
