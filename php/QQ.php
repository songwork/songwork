<?php
/** Template class. URL parsing. Controller interface.
** 2009-08-23 update: removed tidy, cache, varclean. Make vars into $qv global. '.php' extension added to show */
class QQ
	{
	private $display;
	private $path;
	private $myvars = array();
	private $possible_langs = array('en', 'es', 'fr', 'de', 'it', 'pt', 'ja', 'ko', 'zh', 'ar', 'ru');  # copy from Lang::$langs
	function __construct($lang='en', $display=true)
		{
		$this->display = $display;
		$this->path = 'qq/';
		$this->myvars['lang'] = (in_array($lang, $this->possible_langs)) ? $lang : 'en';
		if(!defined('LANG'))
			{
			define('LANG', $lang);
			}
		$this->myvars['content_type'] = (isset($_SERVER['HTTP_ACCEPT']) AND (stristr($_SERVER['HTTP_ACCEPT'], 'application/xhtml+xml'))) ? 'application/xhtml+xml' : 'text/html';
		# TODO: http://us2.php.net/parse_url
		$this->myvars['ssl'] = (isset($_SERVER['HTTPS'])) ? true : false;
		$this->myvars['host'] = (isset($_SERVER['HTTP_HOST'])) ? $_SERVER['HTTP_HOST'] : false;
		$this->myvars['fullhost'] = (($this->myvars['ssl']) ? 'https://' : 'http://') . $this->myvars['host'];
		$this->myvars['script'] = substr($_SERVER['SCRIPT_NAME'], 1);
		$this->myvars['path'] = ($_SERVER['SCRIPT_NAME'] . ((isset($_SERVER['PATH_INFO'])) ? $_SERVER['PATH_INFO'] : ''));
		$this->myvars['params'] = (isset($_SERVER['PATH_INFO'])) ? explode('/', substr($_SERVER['PATH_INFO'], 1)) : array();
		$this->myvars['referer'] = (isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : false;
		# relative path only if it came from this same domain. else ''.
		$rr = '';
		if($this->myvars['referer'])
			{
			$rurl = parse_url($this->myvars['referer']);
			if(isset($rurl['host']) && $rurl['host'] == $_SERVER['HTTP_HOST'])
				{
				$rr = substr($rurl['path'], 1);
				}
			}
		$this->myvars['relative-referer'] = $rr;
		$this->myvars['flash'] = $this->flash();
		$this->getwords();
		# no host = no display (example: command-line scripts)
		if($this->myvars['host'] !== false)
			{
			ob_start();
			}
		}

	function show($template)
		{
		extract($this->myvars);
		extract($GLOBALS['qv']);
		require($this->path . $template . '.php');
		}

	function langpage($key)
		{
		if(!@include('lang/' . $this->myvars['lang'] . '/' . $key . '.html'))
			{
			include('lang/en/' . $key . '.html');
			}
		}

	function get()
		{
		return ob_get_contents();
		}

	function __destruct()
		{
		# no host = no display (example: command-line scripts)
		if($this->myvars['host'] === false)
			{
			return;
			}
		if($this->display)
			{
			header('Content-Type: ' . $this->myvars['content_type'] . '; charset=UTF-8');
			ob_end_flush();
			}
		else
			{
			ob_end_clean();
			}
		}

	function server()
		{
		return $this->myvars;
		}
		
	function redirect($url, $response_code=301)
		{
		header('Location: ' . $this->myvars['fullhost'] . '/' . $url, true, $response_code);
		die();
		}
	
	# double-use function : pass it a message, and it will set a cookie with that message
	# pass it nothing, and it will return the existing set value, and erase it.
	function flash($msg = false)
		{
		if($msg)
			{
			setcookie('flash', $msg, time() + 3600, '/');
			}
		elseif(isset($_COOKIE['flash']))
			{
			$f = $_COOKIE['flash'];
			setcookie('flash', '', time() - 3600, '/');
			# perhaps unset($_COOKIE['flash']); here?
			return $f;
			}
		else 
			{ 
			return false; 
			}
		}

	function force($content_type)
		{
		switch(strtolower($content_type))
			{
			case 'xhtml':
				$this->myvars['content_type'] = 'application/xhtml+xml; charset=utf-8';
				break;
			case 'html':
				$this->myvars['content_type'] = 'text/html; charset=utf-8';
				break;
			case 'xml':
				$this->myvars['content_type'] = 'application/xml; charset=utf-8';
				break;
			}
		}

	function sendfile($fullpath)
		{
		ob_end_clean();
		if(!is_file($fullpath)) { return false; }
		header('Cache-Control: no-store, no-cache, must-revalidate');
		header('Cache-Control: post-check=0, pre-check=0', false);
		header('Pragma: no-cache');
		header('Expires: ' . gmdate('D, d M Y H:i:s', mktime(date('H')+2, date('i'), date('s'), date('m'), date('d'), date('Y'))) . ' GMT');
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
		header('Content-Type: application/octet-stream');
		header('Content-Length: ' . filesize($fullpath));
		header('Content-Disposition: inline; filename=' . basename($fullpath));
		header('Content-Transfer-Encoding: binary' . "\n");
		$fp = fopen($fullpath, 'rb');
		while(!feof($fp) and connection_status() == 0)
			{
			print fread($fp, 1024*8);
			flush();
			}
		fclose($fp);
		return (connection_status()==0 && !connection_aborted()) ? true : false;
		}

	private function getwords()
		{
		if(!@include('lang/' . $this->myvars['lang'] . '.php'))
			{
			include 'lang/en.php';
			}
		$html_from = array('[b]', '[/b]', '|', '[i]', '[/i]');  # to allow basic markup in translations
		$html_to = array('<strong>', '</strong>', "<br />\n", '<em>', '</em>');
		foreach($words as $k => $v)
			{
			define($k, str_replace($html_from, $html_to, htmlspecialchars($v)));
			}
		}

	}
?>
