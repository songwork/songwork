<?php
mb_internal_encoding('UTF-8');

function __autoload($class_name)
	{
	require_once $class_name . '.php';
	}

function nolinebreak($text)
	{
	return str_replace(array("\r", "\n", "\t"), ' ', $text);
	}

function undoubles($text)
	{
	foreach(array(' ', '!', "\n\n") as $x)
		{
		while(strpos($text, str_repeat($x, 2)) !== false)
			{
			$text = str_replace(str_repeat($x, 2), $x, $text);
			}
		}
	return $text;
	}

function case_sentences($text)  # a little ugly
	{
	$sentences = explode('.', $text);
	foreach($sentences as $k => $v)
		{
		if(strlen(trim($v)) == 0) { unset($sentences[$k]); continue; }
		$sentences[$k] = ucfirst(trim(strtolower($v)));
		}
	$str = join('. ', $sentences);
	if(substr(trim($text), -1) == '.') { $str .= '.'; }
	return $str;
	}

function seconds_to_words($seconds)
	{
	if(!defined('D_SECONDS')) { define('D_SECONDS', '%d seconds'); }
	if(!defined('D_MINUTES')) { define('D_MINUTES', '%d minutes'); }
	if(!defined('D_HOURS')) { define('D_HOURS', '%d hours'); }
	if($seconds < 120)
		{
		return sprintf(D_SECONDS, $seconds);
		}
	elseif($seconds < 7200)
		{
		$minutes = floor($seconds / 60);
		$seconds = $seconds % ($minutes * 60);
		return (sprintf(D_MINUTES, $minutes) . ', ' . sprintf(D_SECONDS, $seconds));
		}
	else
		{
		$hours = floor($seconds / 3600);
		$minutes = floor(($seconds % ($hours * 3600)) / 60);
		$seconds = $seconds - (($hours * 3600) + ($minutes * 60));
		return (sprintf(D_HOURS, $hours) . ', ' . sprintf(D_MINUTES, $minutes) . ', ' . sprintf(D_SECONDS, $seconds));
		}
	}

function howlong_ago($timestamp)
	{
	$seconds = time() - strtotime($timestamp);
	return (seconds_to_words($seconds) . ' ago');
	}

function numonly($text)
	{
	return preg_replace('/[^0-9]/', '', $text);
	}

function formselect($fieldname, $options_array, $value='', $different_id=false)
	{
	$id = ($different_id) ? $different_id : $fieldname;
	$html = '<select id="' . $id . '" name="' . $fieldname . '">';
	foreach($options_array as $k => $v)
		{
		$html .= '<option value="' . $k . '"';
		if($k == $value) { $html .= ' selected="selected"'; }
		$html .= '>' . htmlspecialchars($v) . '</option>';
		}
	$html .= '</select>';
	return $html;
	}

function formselect_country_poptop($fieldname, $options_array, $value)
	{
	if(!isset($options_array['']))
		{
		$options_array[''] = ' ... please choose ...';
		}
	$pop1 = array('', 'US', 'GB', 'CA', 'AU', 'JP');
	$pop2 = array('', 'DE', 'IT', 'SE', 'FR', 'IE', 'NL', 'CH', 'NZ', 'IL', 'ES', 'NO', 'DK', 'AT', 'ZA', 'BE', 'BR', 'MX', 'FI', 'GR', 'NG', 'IN', 'PR', 'RU', 'PT', 'JM', 'AR', 'SG', 'HK');
	$top = array();
	asort($options_array);  # sort all, first
	# make top-list
	foreach($options_array as $k => $v)
		{
		if(in_array($k, $pop1))
			{
			$top[$k] = $v;
			}
		if($k == '') { unset($options_array[$k]); }  # only show "please choose.." once, at top
		}
	foreach($options_array as $k => $v)
		{
		if(in_array($k, $pop2))
			{
			$top[$k] = $v;
			}
		if($k == '') { unset($options_array[$k]); }  # only show "please choose.." once, at top
		}
	
	$selected_shown = false;  # only show selected once, though may appear twice
	$html = '<select id="' . $fieldname . '" name="' . $fieldname . '">';
	# first do the tops
	foreach($top as $k => $v)
		{
		$html .= '<option value="' . $k . '"';
		if($selected_shown == false && $k == $value) { $html .= ' selected="selected"'; $selected_shown = true; }
		$html .= '>' . htmlspecialchars($v) . '</option>';
		}
	# now the rest (the tops will appear again, in order this time)
	foreach($options_array as $k => $v)
		{
		$html .= '<option value="' . $k . '"';
		if($selected_shown == false && $k == $value) { $html .= ' selected="selected"'; $selected_shown = true; }
		$html .= '>' . htmlspecialchars($v) . '</option>';
		}
	$html .= '</select>';
	return $html;
	}

function compare_by_length($a, $b)
	{
	if(strlen($a) == strlen($b)) { return 0; }
	return (strlen($a) < strlen($b)) ? -1 : 1;
	}

function sort_array_of_objects_by_method($array, $methodname)
	{
	$sorter = array();
	$result = array();
	foreach($array as $k => $v)
		{
		$sorter[$k] = $v->$methodname();
		}
	asort($sorter);
	foreach($sorter as $k => $v)
		{
		$result[$k] = $array[$k];
		}
	return $result;
	}

# example ($posts, array('one', 'two', 'three')): 2nd array has to be keys of first
function sort_array_by_array($array, $keyname_array)
	{
	$result = array();
	foreach($keyname_array as $key)
		{
		$result[$key] = $array[$key];
		}
	return $result;
	}

function url_exists($url)
	{
	$h = @get_headers($url);
	return (isset($h[0]) && strpos($h[0], '200')) ? true : false;
	}

# http://www.faqs.org/rfcs/rfc3339.html
function rfc3339($date)
	{
	$date = date('Y-m-d\TH:i:s', strtotime($date));
	if(preg_match('/^([\-+])(\d{2})(\d{2})$/', date('O', time()), $matches))
		{
		$date .= $matches[1] . $matches[2] . ':' . $matches[3];
		}
	else
		{
		$date .= 'Z';
		}
	return $date;
	}

function join_english($array, $glue=', ', $final=' and ')
	{
	$count = count($array);
	if((!is_array($array)) || ($count == 0)) { return ''; }
        $end = array_pop($array);
        if($count == 1) { return $end; }
	return join($glue, $array) . $final . $end;
	}

function highlight($text, $substr)
	{
	return str_ireplace($substr, '<span class="highlight">' . $substr . '</span>', $text);
	}

function randstring($length=8)
	{
	$a = array_merge(range('a', 'z'), range('0', '9'));
	$string = '';
	for($i=0; $i<$length; $i++)
		{
		$string .= $a[array_rand($a)];
		}
	return $string;
	}

# RFC(2)822 Email Parser
# By Cal Henderson <cal@iamcal.com>
# This code is licensed under a Creative Commons Attribution-ShareAlike 2.5 License
# http://creativecommons.org/licenses/by-sa/2.5/
# $Revision: 1.2 $
function is_valid_email_address($email)
	{
	####################################################################################
	#
	# NO-WS-CTL       =       %d1-8 /         ; US-ASCII control characters
	#                         %d11 /          ;  that do not include the
	#                         %d12 /          ;  carriage return, line feed,
	#                         %d14-31 /       ;  and white space characters
	#                         %d127
	# ALPHA          =  %x41-5A / %x61-7A   ; A-Z / a-z
	# DIGIT          =  %x30-39

	$no_ws_ctl	= "[\\x01-\\x08\\x0b\\x0c\\x0e-\\x1f\\x7f]";
	$alpha		= "[\\x41-\\x5a\\x61-\\x7a]";
	$digit		= "[\\x30-\\x39]";
	$cr		= "\\x0d";
	$lf		= "\\x0a";
	$crlf		= "($cr$lf)";


	####################################################################################
	#
	# obs-char        =       %d0-9 / %d11 /          ; %d0-127 except CR and
	#                         %d12 / %d14-127         ;  LF
	# obs-text        =       *LF *CR *(obs-char *LF *CR)
	# text            =       %d1-9 /         ; Characters excluding CR and LF
	#                         %d11 /
	#                         %d12 /
	#                         %d14-127 /
	#                         obs-text
	# obs-qp          =       "\" (%d0-127)
	# quoted-pair     =       ("\" text) / obs-qp

	$obs_char	= "[\\x00-\\x09\\x0b\\x0c\\x0e-\\x7f]";
	$obs_text	= "($lf*$cr*($obs_char$lf*$cr*)*)";
	$text		= "([\\x01-\\x09\\x0b\\x0c\\x0e-\\x7f]|$obs_text)";
	$obs_qp		= "(\\x5c[\\x00-\\x7f])";
	$quoted_pair	= "(\\x5c$text|$obs_qp)";


	####################################################################################
	#
	# obs-FWS         =       1*WSP *(CRLF 1*WSP)
	# FWS             =       ([*WSP CRLF] 1*WSP) /   ; Folding white space
	#                         obs-FWS
	# ctext           =       NO-WS-CTL /     ; Non white space controls
	#                         %d33-39 /       ; The rest of the US-ASCII
	#                         %d42-91 /       ;  characters not including "(",
	#                         %d93-126        ;  ")", or "\"
	# ccontent        =       ctext / quoted-pair / comment
	# comment         =       "(" *([FWS] ccontent) [FWS] ")"
	# CFWS            =       *([FWS] comment) (([FWS] comment) / FWS)

	#
	# note: we translate ccontent only partially to avoid an infinite loop
	# instead, we'll recursively strip comments before processing the input
	#

	$wsp		= "[\\x20\\x09]";
	$obs_fws	= "($wsp+($crlf$wsp+)*)";
	$fws		= "((($wsp*$crlf)?$wsp+)|$obs_fws)";
	$ctext		= "($no_ws_ctl|[\\x21-\\x27\\x2A-\\x5b\\x5d-\\x7e])";
	$ccontent	= "($ctext|$quoted_pair)";
	$comment	= "(\\x28($fws?$ccontent)*$fws?\\x29)";
	$cfws		= "(($fws?$comment)*($fws?$comment|$fws))";
	$cfws		= "$fws*";


	####################################################################################
	#
	# atext           =       ALPHA / DIGIT / ; Any character except controls,
	#                         "!" / "#" /     ;  SP, and specials.
	#                         "$" / "%" /     ;  Used for atoms
	#                         "&" / "'" /
	#                         "*" / "+" /
	#                         "-" / "/" /
	#                         "=" / "?" /
	#                         "^" / "_" /
	#                         "`" / "{" /
	#                         "|" / "}" /
	#                         "~"
	# atom            =       [CFWS] 1*atext [CFWS]

	$atext		= "($alpha|$digit|[\\x21\\x23-\\x27\\x2a\\x2b\\x2d\\x2f\\x3d\\x3f\\x5e\\x5f\\x60\\x7b-\\x7e])";
	$atom		= "($cfws?$atext+$cfws?)";


	####################################################################################
	#
	# qtext           =       NO-WS-CTL /     ; Non white space controls
	#                         %d33 /          ; The rest of the US-ASCII
	#                         %d35-91 /       ;  characters not including "\"
	#                         %d93-126        ;  or the quote character
	# qcontent        =       qtext / quoted-pair
	# quoted-string   =       [CFWS]
	#                         DQUOTE *([FWS] qcontent) [FWS] DQUOTE
	#                         [CFWS]
	# word            =       atom / quoted-string

	$qtext		= "($no_ws_ctl|[\\x21\\x23-\\x5b\\x5d-\\x7e])";
	$qcontent	= "($qtext|$quoted_pair)";
	$quoted_string	= "($cfws?\\x22($fws?$qcontent)*$fws?\\x22$cfws?)";
	$word		= "($atom|$quoted_string)";


	####################################################################################
	#
	# obs-local-part  =       word *("." word)
	# obs-domain      =       atom *("." atom)

	$obs_local_part	= "($word(\\x2e$word)*)";
	$obs_domain	= "($atom(\\x2e$atom)*)";


	####################################################################################
	#
	# dot-atom-text   =       1*atext *("." 1*atext)
	# dot-atom        =       [CFWS] dot-atom-text [CFWS]

	$dot_atom_text	= "($atext+(\\x2e$atext+)*)";
	$dot_atom	= "($cfws?$dot_atom_text$cfws?)";


	####################################################################################
	#
	# domain-literal  =       [CFWS] "[" *([FWS] dcontent) [FWS] "]" [CFWS]
	# dcontent        =       dtext / quoted-pair
	# dtext           =       NO-WS-CTL /     ; Non white space controls
	# 
	#                         %d33-90 /       ; The rest of the US-ASCII
	#                         %d94-126        ;  characters not including "[",
	#                                         ;  "]", or "\"

	$dtext		= "($no_ws_ctl|[\\x21-\\x5a\\x5e-\\x7e])";
	$dcontent	= "($dtext|$quoted_pair)";
	$domain_literal	= "($cfws?\\x5b($fws?$dcontent)*$fws?\\x5d$cfws?)";


	####################################################################################
	#
	# local-part      =       dot-atom / quoted-string / obs-local-part
	# domain          =       dot-atom / domain-literal / obs-domain
	# addr-spec       =       local-part "@" domain

	$local_part	= "($dot_atom|$quoted_string|$obs_local_part)";
	$domain		= "($dot_atom|$domain_literal|$obs_domain)";
	$addr_spec	= "($local_part\\x40$domain)";


	# we need to strip comments first (repeat until we can't find any more)
	$done = false;
	while(!$done)
		{
		$new = preg_replace("!$comment!", '', $email);
		if(strlen($new) == strlen($email))
			{
			$done = true;
			}
		$email = $new;
		}

	# now match what's left
	return preg_match("!^$addr_spec$!", $email) ? true : false;
	}

?>
