<?php
class Video
	{
	static function basedirs()
		{
		return array('/home/songwork/WEBSITE/', '/var/www/video/');
		}

	static function basedir()
		{
		foreach(Video::basedirs() as $b)
			{
			if(is_dir($b))
				{
				return $b;
				}
			}
		die('no basedirs?');
		}

	static function fullpath($type, $filename)
		{
		if(!in_array($type, array('stream', 'download', 'trailer'))) 
			{
			die("wrong Video type: $type");
			}
		$filename = basename($filename);  # TODO: regex to whitelist characters? A-Za-z0-9_.-
		$fullpath = Video::basedir() . $type . '/' . $filename;
		return (is_file($fullpath)) ? $fullpath : false;
		}

	static function download($filename)
		{
		$fullpath = Video::fullpath('download', $filename);
		if($fullpath === false) { return false; }
		if(connection_status() != 0) { return false; }
		header('Cache-Control: no-store, no-cache, must-revalidate');
		header('Cache-Control: post-check=0, pre-check=0', false);
		header('Pragma: no-cache');
		header('Expires: ' . gmdate('D, d M Y H:i:s', mktime(date('H')+2, date('i'), date('s'), date('m'), date('d'), date('Y'))).' GMT');
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
		header('Content-Type: application/octet-stream');
		header('Content-Length: ' . filesize($fullpath));
		header('Content-Disposition: inline; filename=' . $filename);
		header("Content-Transfer-Encoding: binary\n");
		readfile($fullpath);
		return ((connection_status()==0) and !connection_aborted());
		}

	static function stream($filename)
		{
		$fullpath = Video::fullpath('stream', $filename);
		if($fullpath === false) { return false; }
		if(connection_status() != 0) { return false; }
		header('Content-Type: video/quicktime');
		readfile($fullpath);
		return ((connection_status()==0) and !connection_aborted());
		}
	
	}
?>
