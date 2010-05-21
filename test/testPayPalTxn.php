<?php
require_once 'test.php';

class PayPalTxnTest extends PHPUnit_Framework_TestCase
	{

	function setUp() { shell_exec('sh regen-tests.sh'); }

	function testGet()
		{
		$x = new PayPalTxn(1);
		$this->assertEquals('1313a878bf', $x->txn_id());
		}

	function testPayment()
		{
		$x = new PayPalTxn(1);
		$p = $x->payment();
		$this->assertType('Payment', $p);
		$x = new PayPalTxn(4);
		$this->assertFalse($x->payment());
		}

	function testReconciled()
		{
		$x = new PayPalTxn(1);
		$this->assertTrue($x->reconciled());
		$x = new PayPalTxn(4);
		$this->assertFalse($x->reconciled());
		}

	function testReconcile()
		{
		$x = new PayPalTxn(4);
		$this->assertFalse($x->reconciled());
		$x->reconcile();
		$this->assertTrue($x->reconciled());
		}

	function testInfoArray()
		{
		$x = new PayPalTxn(1);
		$a = $x->infoarray();
		$this->assertEquals('Veruca', $a['first_name']);
		$this->assertEquals('Completed', $a['payment_status']);
		}

	function testForPayment()
		{
		$xx = PayPalTxn::for_payment_id(1);
		$this->assertType('array', $xx);
		$this->assertEquals(1, count($xx));
		$x = array_shift($xx);
		$this->assertType('PayPalTxn', $x);
		$this->assertEquals(1, $x->id);
		}

	function testWithoutPayment()
		{
		$xx = PayPalTxn::without_payment_id();
		$this->assertType('array', $xx);
		$this->assertEquals(1, count($xx));
		$x = array_shift($xx);
		$this->assertType('PayPalTxn', $x);
		$this->assertEquals(4, $x->id);
		}

	function testNotReconciled()
		{
		$xx = PayPalTxn::not_reconciled();
		$this->assertType('array', $xx);
		$this->assertEquals(1, count($xx));
		$x = array_shift($xx);
		$this->assertType('PayPalTxn', $x);
		$this->assertEquals(4, $x->id);
		}

	function testAll()
		{
		$xx = PayPalTxn::all();
		$this->assertType('array', $xx);
		$this->assertEquals(5, count($xx));
		$x = array_shift($xx);
		$this->assertType('PayPalTxn', $x);
		$this->assertEquals(5, $x->id);
		}

	function testCustomParsed()
		{
		$x = new PayPalTxn(1);
		$this->assertTrue(strpos($x->info(), 'custom = PERSON_ID=7 STUDENT_ID=2 TEACHER_ID=false') > 0);
		$info = $x->infoarray();
		$this->assertEquals('PERSON_ID=7 STUDENT_ID=2 TEACHER_ID=false', $info['custom']);
		$y = $x->custom_parsed();
		$this->assertEquals(array('person_id' => 7, 'student_id' => 2, 'teacher_id' => false), $y);
		}

	function testMoney()
		{
		$x = new PayPalTxn(3);
		$this->assertEquals(new Money(50000, 'GBP'), $x->money());
		$x = new PayPalTxn(4);
		$this->assertEquals(new Money(15000, 'EUR'), $x->money());
		}
	}
?>
