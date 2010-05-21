
<div class="videobox">
<?php
if(isset($download) && $download == true)
	{
	print '<h4><a href="/download' . $d->download_relative_uri() . '">Click here to download the video</a> <span class="small">' . $d->showsize() . ' ' . $d->mediatype() . '</span></h4>';
	}
?>
<object classid="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B" codebase="http://www.apple.com/qtactivex/qtplugin.cab" width="640" height="480">
	<param name="src" value="/stream/<?php print $d->id; ?>" />
	<param name="controller" value="true" />
	<param name="autoplay" value="false" />
	<param name="type" value="video/quicktime" width="640" height="480" />
<!--[if !IE]>-->
	<object type="video/quicktime" data="/stream/<?php print $d->id; ?>" width="640" height="480">
		<param name="autoplay" value="false" />
		<param name="controller" value="true" />
	</object>
<!--<![endif]-->
</object>
</div>

