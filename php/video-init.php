<?php
# require this file for downloading or streaming video, instead of songwork-init.php
# handles authentication and URL parsing, so /web/stream or web/download can carry on OK
require_once 'init.php';

$id = 0;
if(isset($_SERVER['PATH_INFO']))
	{
	list($id) = explode('/', substr($_SERVER['PATH_INFO'], 1));
	}
$id = intval($id);
$d = new Document($id);
if($d->failed())
	{
	die('document id not found');
	}

# only teachers or students who paid for this can download
$p = Person::get_by_cookie();
$student = Student::get_by_person($p);
$teacher = Teacher::get_by_person($p);
$admin = Administrator::get_by_person($p);

if($teacher || $admin || ($student && $student->paid_for_document($d->id)))
	{
	return true;
	}
else
	{
	$error = 'No permission to see that document. ';
/*
	$error .= sprintf('Document = %s. ', ($d === false ? 'false' : $d->id));
	$error .= sprintf('Person = %s. ', ($p === false ? 'false' : $p->id));
	$error .= sprintf('Student = %s. ', ($student === false ? 'false' : $student->id));
	$error .= sprintf('Teacher = %s. ', ($teacher === false ? 'false' : $teacher->id));
	$error .= sprintf('Admin = %s. ', ($admin === false ? 'false' : $admin->id));
*/
	die($error);
	}
?>
