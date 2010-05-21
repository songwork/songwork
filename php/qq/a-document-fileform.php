<?php
# needs filetype and fileinfo
print '<form enctype="multipart/form-data" action="/a/upload/' . $d->id . '" method="POST">';
print '<label for="userfile-' . $filetype . '">video for ' . $filetype . 'ing: ';
if($fileinfo == false)
	{
	print 'NONE';
	}
else
	{
	# printf('<a href="/%s/%d/%s">%s</a>', $filetype, $d->id, $fileinfo, $fileinfo);
	print '<a href="/' . $filetype . '/' . $d->id . '/' . basename($fileinfo) . '">' . $fileinfo . '</a>';
	}
print '</label>';
print '<input id="userfile-' . $filetype . '" name="userfile" type="file" />';
print '<input type="hidden" name="filetype" value="' . $filetype . '" />';
print '<input type="submit" value="upload" />';
print '</form>';
?>
