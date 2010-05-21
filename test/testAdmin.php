<?php
require_once 'test.php';

class AdministratorTest extends PHPUnit_Framework_TestCase
	{

	function setUp() { shell_exec('sh regen-tests.sh'); }

	function testGetByPerson()
		{
		$p = Person::get_by_email_pass('songwork@songwork.com', 'songwork');
		$this->assertType('Person', $p);
		$this->assertEquals(1, $p->id);
		$x = Administrator::get_by_person($p);
		$this->assertType('Administrator', $x);
		$this->assertEquals(1, $x->id);
		}

	function testBadGet()
		{
		$x = Administrator::get_by_person(false);
		$this->assertFalse($x);
		$x = Administrator::get_by_person(new Person(array('id' => 999)));
		$this->assertFalse($x);
		}
	}
?>
