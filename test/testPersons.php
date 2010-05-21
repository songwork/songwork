<?php
require_once 'test.php';

class PersonTest extends PHPUnit_Framework_TestCase
	{

	function setUp() { shell_exec('sh regen-tests.sh'); }

	# in fixtures ONLY, all passwords are same as first part of email address. use this to test auth.
	function testPassword()
		{
		foreach(Person::newest() as $x)
			{
			list($pass) = explode('@', $x->email());
			$p = Person::get_by_email_pass($x->email(), $pass);
			$this->assertType('Person', $p);
			$this->assertEquals($x->id, $p->id);
			}
		}

	function testNewPass()
		{
		$p = new Person(1);
		$newpass = ' V€ry “long” änđ "inv°lved" \'pass\'我把 ';
		$p->set(array('password' => $newpass));
		$p = Person::get_by_email_pass('songwork@songwork.com', $newpass);
		$this->assertType('Person', $p);
		$this->assertEquals(1, $p->id);
		}

	function testGet()
		{
		$p = Person::get_by_email_pass('songwork@songwork.com', 'songwork');
		$this->assertType('Person', $p);
		$this->assertEquals('Songwork', $p->name());
		$p = Person::get_by_email_pass('x@x.com', 'x');
		$this->assertFalse($p);
		$p = Person::get_by_email_pass('songwork@songwork.com', '');
		$this->assertFalse($p);
		}

	function testLogin()
		{
		$p = new Person(3);
		$p->set_auth();
		$db = Get::db('songwork');
		$db->query("SELECT * FROM logins WHERE person_id=3");
		$this->assertEquals(1, $db->num_rows());
		$x = $db->next_record();
		$this->assertTrue($x['cookie_exp'] > 1249393574);
		$this->assertTrue(ctype_alnum($x['cookie_id']));
		$this->assertEquals(32, strlen($x['cookie_id']));
		$this->assertEquals('shell', $x['domain']);
		$this->assertEquals(Person::cookie_id_for(3, 'shell'), $x['cookie_id']);
		$this->assertTrue(ctype_alnum($x['cookie_tok']));
		$this->assertEquals(32, strlen($x['cookie_tok']));
		}

	function testCookie()
		{
		$p = new Person(3);
		$p->set_auth();
		# get what the cookie would have been
		$db = Get::db('songwork');
		$db->query("SELECT * FROM logins WHERE person_id=3");
		$x = $db->next_record();
		$cookie_value = $x['cookie_id'] . ':' . $x['cookie_tok'];
		$_COOKIE['ok'] = $cookie_value;
		$p = Person::get_by_cookie();
		$this->assertType('Person', $p);
		$this->assertEquals(3, $p->id);
		$this->assertEquals('Yoko', $p->firstname());
		$_COOKIE['ok'] = $cookie_value . 'x';
		$p = Person::get_by_cookie();
		$this->assertFalse($p);
		}

	function testWithIds()
		{
		$ids = array(1, 3, 5, 7, 9);  # 9 does not exist
		$y = Person::with_ids($ids);
		$this->assertType('array', $y);
		$this->assertEquals(4, count($y));
		$x = array_shift($y);
		$this->assertType('Person', $x);
		$ids = array('this', 'nothing');
		$y = Person::with_ids($ids);
		$this->assertType('array', $y);
		$this->assertEquals(0, count($y));
		}
	}
?>
