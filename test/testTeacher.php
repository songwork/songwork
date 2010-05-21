<?php
require_once 'test.php';

class TeacherTest extends PHPUnit_Framework_TestCase
	{

	function setUp() { shell_exec('sh regen-tests.sh'); }

	function testGetByPerson()
		{
		$p = Person::get_by_email_pass('tiny@tim.com', 'tiny');
		$this->assertType('Person', $p);
		$this->assertEquals(4, $p->id);
		$x = Teacher::get_by_person($p);
		$this->assertType('Teacher', $x);
		$this->assertEquals(1, $x->id);
		}

	function testBadGet()
		{
		$x = Teacher::get_by_person(false);
		$this->assertFalse($x);
		$x = Teacher::get_by_person(new Person(array('id' => 999)));
		$this->assertFalse($x);
		}

	function testRemembersPerson()
		{
		$fakename = 'Bob Dobalina';
		$p = new Person(array('id' => 4, 'name' => $fakename));
		$x = Teacher::get_by_person($p);
		$this->assertEquals(1, $x->id);
		$this->assertEquals($fakename, $x->name());
		}

	function testHasDocumentId()
		{
		$x = new Teacher(1);
		$this->assertTrue($x->has_document_id(3));
		$this->assertFalse($x->has_document_id(1));
		$this->assertFalse($x->has_document_id('xxx'));
		}

	function testDocuments()
		{
		$x = new Teacher(2);
		$y = $x->documents();
		$d = array_shift($y);
		$this->assertType('Document', $d);
		$this->assertEquals('Willy_Wonka-Rhyming_Dictionary.doc', $d->url());
		}

	function testPayments()
		{
		$x = new Teacher(2);
		$y = $x->incoming_payments();
		$z = array_shift($y);
		$this->assertType('Payment', $z);
		$this->assertEquals(4, $z->id);
		}

	function testStudents()
		{
		$x = new Teacher(2);
		$y = $x->students();
		$this->assertEquals(2, count($y));
		$z = array_shift($y);
		$this->assertType('Student', $z);
		$x = new Teacher(1);
		$y = $x->students();
		$this->assertEquals(1, count($y));
		}

	function testHasStudent()
		{
		$x = new Teacher(2);
		$this->assertTrue($x->has_student_id(1));
		$this->assertTrue($x->has_student_id(2));
		$this->assertFalse($x->has_student_id(99));
		$this->assertFalse($x->has_student_id(''));
		$x = new Teacher(1);
		$this->assertTrue($x->has_student_id(2));
		$this->assertFalse($x->has_student_id(1));
		}

	function testHasPayment()
		{
		$x = new Teacher(2);
		$this->assertFalse($x->has_payment_id(1));
		$this->assertTrue($x->has_payment_id(2));
		$this->assertTrue($x->has_payment_id(3));
		$this->assertTrue($x->has_payment_id(4));
		$this->assertFalse($x->has_payment_id(''));
		$x = new Teacher(1);
		$this->assertTrue($x->has_payment_id(1));
		$this->assertFalse($x->has_payment_id(2));
		}

	function testConsultable()
		{
		$y = Teacher::consultable();
		$this->assertType('array', $y);
		$this->assertEquals(1, count($y));
		$x = array_pop($y);
		$this->assertType('Teacher', $x);
		$this->assertEquals(2, $x->id);
		}
	}
?>
