<?php
print '<h4>status: ' . $d->status() . '. Teachers:</h4>';
print '<form action="/a/document/' . $d->id . '" method="POST"><div><input type="hidden" name="action" value="REMOVETEACHER" />';
print formselect('teacher_id', $removeteachers, '', 'remove_teacher_id');
print '<input type="submit" name="submit" value="remove" />';
print '</div></form>';
print '<form action="/a/document/' . $d->id . '" method="POST"><div><input type="hidden" name="action" value="ADDTEACHER" />';
print formselect('teacher_id', $addteachers, '', 'add_teacher_id');
print '<input type="submit" name="submit" value="add" />';
print '</div></form>';
?>
<form action="/a/document/<?php print $d->id; ?>" method="POST"><fieldset>
<input type="hidden" name="action" value="UPDATE" />
<label for="name">Name:</label>
<input type="text" name="name" id="name" value="<?php print htmlspecialchars($d->name()); ?>" size="30" />
<label for="mediatype">Media type: (mov, pdf, doc, etc)</label>
<input type="text" name="mediatype" id="mediatype" value="<?php print htmlspecialchars($d->mediatype()); ?>" size="5" />
<label for="url">Filename:</label>
<input type="text" name="url" id="url" value="<?php print htmlspecialchars($d->url()); ?>" size="50" />
<label for="youtube">YouTube code for preview clip: (ONLY this bit of the URL, as shown)</label>
http://www.youtube.com/watch?v=<input type="text" name="youtube" id="youtube" value="<?php print htmlspecialchars($d->youtube()); ?>" size="12" />
<label for="bytes">Bytes:</label>
<input type="text" name="bytes" id="bytes" value="<?php print $d->bytes(); ?>" size="10" />
<label for="length">Length: (example: “94 minutes”)</label>
<input type="text" name="length" id="length" value="<?php print htmlspecialchars($d->length()); ?>" size="16" />
<label for="pricecode">Price: (<a href="/t/priceref">set new prices here</a>)</label>
<?php
print formselect('pricecode', $pricecodes, $d->pricecode());
?>
<label for="sentence">Short one-sentence description</label>
<input type="text" name="sentence" id="sentence" value="<?php print htmlspecialchars($d->sentence()); ?>" size="50" />
<label for="description">Description:</label>
<textarea name="description" id="description" cols="70" rows="10"><?php print htmlspecialchars($d->description()); ?></textarea>
<br />
<p>created_at: <?php print $d->created_at(); ?></p>
<p>added_at: <?php print $d->added_at(); ?></p>
<p>removed_at: <?php print $d->removed_at(); ?></p>
<input type="submit" name="submit" value="UPDATE" />
</fieldset></form>
