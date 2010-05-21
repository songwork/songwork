<?php
require_once 'test.php';

class ConsultationRequestTest extends PHPUnit_Framework_TestCase
	{

	function setUp() { shell_exec('sh regen-tests.sh'); }

	function testStudent()
		{
		$x = new ConsultationRequest(1);
		$z = $x->student();
		$this->assertType('Student', $z);
		$this->assertEquals(1, $z->id);
		}

	function testTeacher()
		{
		$x = new ConsultationRequest(1);
		$z = $x->teacher();
		$this->assertType('Teacher', $z);
		$this->assertEquals(2, $z->id);
		}

	function testConsultation()
		{
		$x = new ConsultationRequest(1);
		$this->assertType('Consultation', $x->consultation());
		$x = new ConsultationRequest(3);
		$this->assertFalse($x->consultation());
		}

	function testAll()
		{
		$y = ConsultationRequest::all();
		$this->assertType('array', $y);
		$this->assertEquals(3, count($y));
		$z = array_shift($y);
		$this->assertType('ConsultationRequest', $z);
		$this->assertEquals(3, $z->id);
		}

	function testUnanswered()
		{
		$y = ConsultationRequest::unanswered();
		$this->assertType('array', $y);
		$this->assertEquals(1, count($y));
		$z = array_shift($y);
		$this->assertType('ConsultationRequest', $z);
		$this->assertEquals(3, $z->id);
		}

	function testUnclosed()
		{
		$y = ConsultationRequest::unclosed();
		$this->assertType('array', $y);
		$this->assertEquals(2, count($y));
		$z = array_shift($y);
		$this->assertType('ConsultationRequest', $z);
		$this->assertEquals(2, $z->id);
		}

	function testForStudent()
		{
		$x = new Student(1);
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
		$y = $x->consultation_requests();
		$this->assertType('array', $y);
		$this->assertEquals(3, count($y));
		$z = array_shift($y);
		$this->assertType('ConsultationRequest', $z);
		$this->assertEquals(3, $z->id);
		}

	}
?>
