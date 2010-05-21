<?php
require_once 'test.php';

class PaymentTest extends PHPUnit_Framework_TestCase
	{

	function setUp() { shell_exec('sh regen-tests.sh'); }

	function testPaymentGet()
		{
		$p = new Payment(1);
		$m = $p->money();
		$this->assertEquals('£1.00 GBP', $m->show_with_code());
		}

	function testLinked()
		{
		$p = new Payment(4);
		$this->assertType('Document', $p->document());
		$this->assertFalse($p->consultation());
		$p = new Payment(5);
		$this->assertType('Consultation', $p->consultation());
		$this->assertFalse($p->document());
		}

	function testPaymentAdd()
		{
		$p = new Payment(false);
		$set = array('student_id' => 1, 'document_id' => 3, 'currency' => 'EUR', 'millicents' => 15000, 'details' => 'nah', 'created_at' => '2008-11-01');
		$new_id = $p->add($set);
		$p = new Payment($new_id);
		$m = $p->money();
		$this->assertEquals('€1.50 EUR', $m->show_with_code());
		}

	function testFromPPTXN()
		{
		# (id, student_id, document_id, currency, millicents, details, created_at) VALUES (4, 1, 1, 'EUR', 15000, 'PayPal transaction ID#: whatever', NOW());
		# to test, delete existing payment, null out payment_id in pptxn, and see if it'll recreate it 
		$x = new PayPalTxn(4);
		$x->set(array('payment_id' => 'NULL'));
		$z = new Payment(4);
		$z->kill();
		$payment_id = Payment::create_from_pptxn($x->id);
		$z = new Payment($payment_id);
		$this->assertEquals(1, $z->student_id());
		$this->assertEquals(1, $z->document_id());
		$this->assertEquals('EUR', $z->currency());
		$this->assertEquals(15000, $z->millicents());
		$x = new PayPalTxn(4);
		$this->assertEquals($x->payment_id(), $z->id);
		}
	}
?>
