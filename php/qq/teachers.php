<h2>Teachers</h2>
<ul class="teachers">
<?php
foreach($teachers as $t)
	{
	print "\n<li>";
	print '<h3><a href="/teacher/' . $t->id . '">' . htmlspecialchars($t->name()) . '</a></h3>';
	print '<div class="headshot"><img src="/images/teacher-' . $t->id . '.jpg" width="150" height="150" alt="' . htmlspecialchars($t->name()) . '" /></div>';
	print $t->profile();
	if($t->available() == 't')
		{
		require 'qq/teacher-consulting-link.php';
		}
	print '</li>';
	}
?>
</ul>
