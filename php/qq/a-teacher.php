<?php
print '<h3>' . htmlspecialchars($t->name());
print '<br />' . htmlspecialchars($t->email()) . '</h3>';
print '<p>Added: ' . $t->created_at() . '</p>';
print '<form action="/a/teacher/' . $t->id . '" method="post">';
print '<label for="profile">edit profile: HTML</label>';
print '<textarea name="profile" id="profile" cols="100" rows="10" class="small">' . htmlspecialchars($t->profile()) . '</textarea>';
print '<label for="available">Available for consultations?</label>';
print formselect('available', array('t' => 'YES', 'f' => 'NO'), $t->available());
print '<label for="consultation_rate">Consultation rate (Example: “$75/hr” or “$100/song”.)</label>';
print '<input type="text" name="consultation_rate" id="consultation_rate" value="' . htmlspecialchars($t->consultation_rate()) . '" />';
print '<br /><br />';
print '<input type="submit" name="action" value="UPDATE" />';
print '</form>';
?>
