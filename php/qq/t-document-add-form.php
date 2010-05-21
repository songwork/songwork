<h2>Add New Video</h2>
<form action="/t/document" method="POST"><fieldset>
<input type="hidden" name="action" value="ADD" />
<?php
print '<input type="hidden" name="teacher_id" value="' . intval($defaultteacher) . '" />';
?>
<label for="name">Name:</label>
<input type="text" name="name" id="name" value="" size="30" />
<label for="pricecode">Price: (<a href="/t/priceref">set new prices here</a>)</label>
<?php
print formselect('pricecode', $pricecodes);
?>
<br /><input type="submit" name="submit" value="ADD VIDEO" />
</fieldset></form>
