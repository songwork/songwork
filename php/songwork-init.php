<?php
# define('WE_ARE_TESTING', true);
require_once 'init.php';

$qq = new QQ;
$server = $qq->server();
$qv = array();
$qv['server'] = $server;
$qv['flash'] = $qq->flash();
$qv['pagetitle'] = '';
$qv['basehref'] = 'http://' . (($server['host'] == 'songwork.dev') ? 'songwork.dev' : 'songwork.com');
$qv['bodyid'] = str_replace('/', '-', $server['script']);
if(strpos($qv['bodyid'], '-') === false)
	{
	$qv['bodyid'] = 'x-' . $qv['bodyid'];
	}

########### IDENTIFICATION
$p = Person::get_by_cookie();

# lopass interception: http://songwork.com/home/1234/ab8f
# not full auth, only to have existing client's name & address pre-filled from database for forms
if($p === false && isset($_SERVER['PATH_INFO']) && preg_match('/\/(\d*)\/([0-9a-zA-Z]{4})$/', $_SERVER['PATH_INFO'], $matches))
	{
	$possible_id = $matches[1];
	$possible_lopass = $matches[2];
	$pp = Person::get_by_id_lopass($possible_id, $possible_lopass);
	if($pp !== false)
		{
		$pp->set_welcome_cookie();
		$p = $pp;
		}
	}
$student = Student::get_by_person($p);
$teacher = Teacher::get_by_person($p);
$admin = Administrator::get_by_person($p);
$qv['p'] = $p;
$qv['student'] = $student;
$qv['teacher'] = $teacher;
$qv['admin'] = $admin;
$currency = 'USD';  # TODO: dynamic. set in Person?
$qv['currency'] = $currency;

# name / email - FOR PRE-FILLING FORMS AND HEADERS
if($p)
	{
	$qv['name'] = $p->name();
	$qv['email'] = $p->email();
	}
else
	{
	$qv['name'] = (isset($_COOKIE['name'])) ? $_COOKIE['name'] : '';
	$qv['email'] = (isset($_COOKIE['email'])) ? $_COOKIE['email'] : '';
	}

########### NAVIGATION BAR:  key = URL to link to,  value = word to show
$nav = array();
if(isset($admin) && $admin !== false)
	{
	$nav['a/'] = 'ADMIN MENU';
	# $nav['logout'] = 'log out';
	}
elseif(isset($teacher) && $teacher !== false)
	{
	$nav['t/'] = 'home';
	$nav['t/document'] = 'videos';
	$nav['t/student'] = 'students';
	$nav['t/payment'] = 'payments';
	# $nav['logout'] = 'log out';
	}
elseif(isset($student) && $student !== false)
	{
	$nav['s/'] = $p->name() . ' home';
	$nav['document'] = 'videos';
	$nav['teacher'] = 'teachers';
	$nav['about'] = 'about';
	# $nav['s/payment'] = 'payments';
	# $nav['logout'] = 'log out';
	}
else
	{
	# if home page needs links, they'd go here
	$nav['document'] = 'videos';
	$nav['teacher'] = 'teachers';
	$nav['about'] = 'about';
	$nav['signup'] = 'sign up';
	$nav['login'] = 'log in';
	}
$qv['nav'] = $nav;
?>
