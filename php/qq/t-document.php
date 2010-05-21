<h2>Edit Video</h2>
<form action="/t/document/<?php print $d->id; ?>" method="POST"><fieldset>
<input type="hidden" name="action" value="UPDATE" />
<label for="name">Name:</label>
<input type="text" name="name" id="name" value="<?php print htmlspecialchars($d->name()); ?>" size="50" />
<label for="pricecode">Price: (<a href="/t/priceref">set new prices here</a>)</label>
<?php
print formselect('pricecode', $pricecodes, $d->pricecode());
?>
<label for="description">Description:</label>
<textarea name="description" id="description" cols="60" rows="20"><?php print htmlspecialchars($d->description()); ?></textarea>
<br /><input type="submit" name="submit" value="UPDATE" />
</fieldset></form>
