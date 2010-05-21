<h2>Files Overview</h2>
<?php
print '<table>';
print '<tr><th>document</th><th>download</th><th>stream</th><th>YouTube</th></tr>';
foreach(Document::all() as $d)
	{
	print '<tr>';
	print '<td><a href="/a/document/' . $d->id . '">' . htmlspecialchars($d->fullname()) . '</a></td>';
	$ff = ($d->file_found('download') === false) ? '<span class="highlight">NONE</span>' : '<a href="/download' . $d->download_relative_uri() . '">dl</a>';
	print '<td>' . $ff . '</td>';
	$ff = ($d->file_found('stream') === false) ? '<span class="highlight">NONE</span>' : '<a href="/stream/' . $d->id . '">see</a>';
	print '<td>' . $ff . '</td>';
	$ff = ($d->youtube()) ? '<a href="http://www.youtube.com/watch?v=' . $d->youtube() . '">view</a>' : '<span class="highlight">NONE</span>';
	print '<td>' . $ff . '</td>';
	print '</tr>';
	}
print '</table>';
?>
