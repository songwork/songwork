<h2>Tags</h2>
<?php
# update remove add
foreach($tags as $t)
	{
	print '<form action="/a/tag/' . $t->id . '" method="post">';
	print '<input type="text" name="name" value="' . htmlspecialchars($t->name()) . '" size="12" />';
	print '<input type="submit" name="action" value="update" />';
	if(count($t->documents()) == 0)
		{
		print '<input type="submit" name="action" value="remove" />';
		}
	print '</form>';
	}
?>
<h4>or create a new tag:</h4>
<form action="/a/tag" method="post">
<input type="text" name="name" value="" size="12" />
<input type="submit" name="action" value="add" />
</form>

