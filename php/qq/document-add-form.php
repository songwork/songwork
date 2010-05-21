<h2>Add New Video</h2>
<form action="/a/document" method="POST"><fieldset>
<input type="hidden" name="action" value="ADD" />
<?php
print '<label for="teacher_id">Teacher:</label>';
print formselect('teacher_id', $allteachers);
?>
<label for="name">Name:</label>
<input type="text" name="name" id="name" value="" size="30" />
<label for="mediatype">Media type: (mov, pdf, doc, etc)</label>
<input type="text" name="mediatype" id="mediatype" value="mov" size="5" />
<label for="url">Filename:</label>
<input type="text" name="url" id="url" value="" size="30" />
<label for="pricecode">Price: (<a href="/t/priceref">set new prices here</a>)</label>
<?php
print formselect('pricecode', $pricecodes);
?>
<br /><input type="submit" name="submit" value="ADD VIDEO" />
</fieldset></form>
