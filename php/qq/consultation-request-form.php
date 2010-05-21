<?php
print '<h3>Schedule a consultation with ' . htmlspecialchars($t->name()) . '</h3>';

print '<form action="/s/consultation-request/' . $t->id . '" method="post"><fieldset>';
print '<label for="notes">When\'s good for you? <br />(Example: “any evening next week, New York time, except Wednesday. Sunday would be best.”)</label>';
print '<textarea name="notes" id="notes" cols="60" rows="2"></textarea>';
print '<input type="submit" name="submit" value="SEND REQUEST" />';
print '</fieldset></form>';
