<?php
require_once 'test.php';

class ConsultationTest extends PHPUnit_Framework_TestCase
	{

	function setUp() { shell_exec('sh regen-tests.sh'); }

	function testConsultationGet()
		{
		$x = new Consultation(1);
		$this->assertEquals('t', $x->done());
		$m = $x->money();
		$this->assertEquals('$100.00 USD', $m->show_with_code());
		}

	function testStudents()
		{
		$x = new Consultation(1);
		$y = $x->students();
		$this->assertType('array', $y);
		$this->assertEquals(1, count($y));
		$z = array_shift($y);
		$this->assertType('Student', $z);
		$this->assertEquals(1, $z->id);
		}

	function testTeachers()
		{
		$x = new Consultation(1);
		$y = $x->teachers();
		$this->assertType('array', $y);
		$this->assertEquals(1, count($y));
		$z = array_shift($y);
		$this->assertType('Teacher', $z);
		$this->assertEquals(2, $z->id);
		}

	function testPaid()
		{
		$x = new Consultation(1);
		$this->assertTrue($x->paid());
		$x = new Consultation(2);
		$this->assertFalse($x->paid());
		}

	function testPayments()
		{
		$x = new Consultation(1);
		$y = $x->payments();
		$this->assertType('array', $y);
		$this->assertEquals(1, count($y));
		$z = array_shift($y);
		$this->assertType('Payment', $z);
		$this->assertEquals(5, $z->id);
		$x = new Consultation(2);
		$y = $x->payments();
		$this->assertEquals(0, count($y));
		}

	function testAll()
		{
		$y = Consultation::all();
		$this->assertType('array', $y);
		$this->assertEquals(2, count($y));
		$z = array_shift($y);
		$this->assertType('Consultation', $z);
		$this->assertEquals(2, $z->id);
		}

	function testUndone()
		{
		$y = Consultation::undone();
		$this->assertType('array', $y);
		$this->assertEquals(1, count($y));
		$z = array_shift($y);
		$this->assertType('Consultation', $z);
		$this->assertEquals(2, $z->id);
		}

	function testUnpaid()
		{
		$y = Consultation::unpaid();
		$this->assertType('array', $y);
		$this->assertEquals(1, count($y));
		$z = array_shift($y);
		$this->assertType('Consultation', $z);
		$this->assertEquals(2, $z->id);
		}

	function testForStudent()
		{
		$x = new Student(1);
		$y = $x->consultations();
		$this->assertType('array', $y);
		$this->assertEquals(1, count($y));
		$z = array_shift($y);
		$this->assertType('Consultation', $z);
		$this->assertEquals(1, $z->id);
		$y = $x->consultation_requests();
		$this->assertType('array', $y);
		$this->assertEquals(2, count($y));
		$z = array_shift($y);
		$this->assertType('ConsultationRequest', $z);
		$this->assertEquals(3, $z->id);
		}

	function testForTeacher()
		{
		$x = new Teacher(2);
		$y = $x->consultations();
		$this->assertType('array', $y);
		$this->assertEquals(2, count($y));
		$z = array_shift($y);
		$this->assertType('Consultation', $z);
		$this->assertEquals(2, $z->id);
		$y = $x->consultation_requests();
		$this->assertType('array', $y);
		$this->assertEquals(3, count($y));
		$z = array_shift($y);
		$this->assertType('ConsultationRequest', $z);
		$this->assertEquals(3, $z->id);
		}

	}
?>
