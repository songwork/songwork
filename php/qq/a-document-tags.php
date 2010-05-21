<?php
print '<h3>Tags:</h3>';
print '<form action="/a/document/' . $d->id . '" method="POST"><div><input type="hidden" name="action" value="ADDTAG" />';
print formselect('tag_id', $addtags, '', 'add_tag_id');
print '<input type="submit" name="submit" value="add" />';
print '</div></form>';
if(count($removetags))
	{
	print '<form action="/a/document/' . $d->id . '" method="POST"><div><input type="hidden" name="action" value="REMOVETAG" />';
	print formselect('tag_id', $removetags, '', 'remove_tag_id');
	print '<input type="submit" name="submit" value="remove" />';
	print '</div></form>';
	}
?>
