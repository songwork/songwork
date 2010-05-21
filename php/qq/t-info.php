<?php # form for teacher to edit their info (profile, available, consultation_rate) ?>
<form action="/t/teacher" method="post"><fieldset>
<input type="hidden" name="action" value="UPDATE" />
<label for="profile">Your main profile: (use HTML)</label>
<textarea name="profile" id="profile" rows="10" cols="120" class="small"><?php print htmlspecialchars($teacher->profile()); ?></textarea>
<label for="available">Available for consultations?</label>
<?php
print formselect('available', array('t' => 'YES', 'f' => 'NO'), $teacher->available());
?>
<label for="consultation_rate">If yes, what's your rate? (Example: “$75/hr” or “$100/song”.)</label>
<input type="text" name="consultation_rate" id="consultation_rate" value="<?php print htmlspecialchars($teacher->consultation_rate()); ?>" />
<br /><br />
<input type="submit" name="submit" value="UPDATE" />
</fieldset></form>
