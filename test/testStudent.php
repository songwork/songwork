<?php
require_once 'test.php';

class StudentTest extends PHPUnit_Framework_TestCase
	{

	function setUp() { shell_exec('sh regen-tests.sh'); }

	function testGetByPerson()
		{
		$p = Person::get_by_email_pass('veruca@salt.com', 'veruca');
		$this->assertType('Person', $p);
		$this->assertEquals(7, $p->id);
		$x = Student::get_by_person($p);
		$this->assertType('Student', $x);
		$this->assertEquals(2, $x->id);
		}

	function testBadGet()
		{
		$x = Student::get_by_person(false);
		$this->assertFalse($x);
		$x = Student::get_by_person(new Person(array('id' => 999)));
		$this->assertFalse($x);
		}

	function testRemembersPerson()
		{
		$fakename = 'Bob Dobalina';
		$p = new Person(array('id' => 6, 'name' => $fakename));
		$x = Student::get_by_person($p);
		$this->assertEquals(1, $x->id);
		$this->assertEquals($fakename, $x->name());
		}

	function testPayments()
		{
		$x = new Student(2);
		$y = $x->payments();
		$this->assertType('array', $y);
		$this->assertEquals(3, count($y));
		$z = array_shift($y);
		$this->assertType('Payment', $z);
		$this->assertEquals(3, $z->id);
		}

	function testPaidDocuments()
		{
		$x = new Student(2);
		$y = $x->paid_documents();
		$this->assertType('array', $y);
		$this->assertEquals(3, count($y));
		$z = array_shift($y);
		$this->assertType('Document', $z);
		$this->assertEquals(3, $z->id);
		}

	function testPaidForDocument()
		{
		$x = new Student(1);
		$this->assertTrue($x->paid_for_document(1));
		$this->assertFalse($x->paid_for_document(3));
		$this->assertFalse($x->paid_for_document(999));
		$this->assertFalse($x->paid_for_document('dog'));
		}
	}
?>
