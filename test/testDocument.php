<?php
require_once 'test.php';

class DocumentTest extends PHPUnit_Framework_TestCase
	{

	function setUp() { shell_exec('sh regen-tests.sh'); }

	function testDocumentGet()
		{
		$d = new Document(1);
		$this->assertEquals('Songwriting tips from Willy Wonka himself.', $d->description());
		$this->assertFalse($d->failed());
		$d = new Document(999);
		$this->assertTrue($d->failed());
		}

	function testFullName()
		{
		$d = new Document(1);
		$this->assertEquals('Songwriting Tips', $d->name());
		$this->assertEquals('Willy Wonka: Songwriting Tips', $d->fullname());
		}

	function testFilenameFor()
		{
		$d = new Document(1);
		$this->assertEquals('SONGWORK-WillyWonka-SongwritingTips.mov', $d->filename_for('mov'));
		$d->add_teacher(1);
		$d = new Document(1);
		$this->assertEquals('SONGWORK-TinyTimWillyWonka-SongwritingTips.mov', $d->filename_for('mov'));
		}

	function testShowsize()
		{
		$d = new Document(1);
		$this->assertEquals('671k', $d->showsize());
		$d = new Document(3);
		$this->assertEquals('365mb', $d->showsize());
		}

	function testDocumentAdd()
		{
		$d = new Document(false);
		$set = array('teacher_id' => 1, 'mediatype' => 'wma', 'url' => 'newsong.wma', 'description' => 'new song', 'pricecode' => 'b');
		$new_id = $d->add($set);
		$d = new Document($new_id);
		$d->add_teacher($set['teacher_id']);
		$this->assertEquals($set['description'], $d->description());
		$t = new Teacher($set['teacher_id']);
		$ds = $t->documents();
		$this->assertType('array', $ds);
		$this->assertArrayHasKey($new_id, $ds);
		$d = $ds[$new_id];
		$this->assertEquals($set['description'], $d->description());
		}

	function testDocumentForFree()
		{
		$d = new Document(false);
		$set = array('mediatype' => 'flac', 'url' => 'freesong.flac', 'description' => 'free song');
		$new_id = $d->add($set);
		$d = new Document($new_id);
		$this->assertEquals($set['description'], $d->description());
		$m = $d->price_in('AUD');
		$this->assertEquals('$0.00 AUD', $m->show_with_code());
		}

	function testDocumentPayments()
		{
		$d = new Document(1);
		$ps = $d->payments();
		$this->assertType('array', $ps);
		$this->assertEquals(2, count($ps));
		$this->assertArrayHasKey('3', $ps);
		$p = $ps['3'];
		$this->assertType('Payment', $p);
		$m = $p->money();
		$this->assertEquals('£0.50 GBP', $m->show_with_code());
		}

	function testPayPalButton()
		{
		$d = new Document(1);
		$this->assertTrue(strpos($d->paypal_button('USD'), 'END PKCS7') > 0);
		}

	function testAddTeacher()
		{
		$d = new Document(1);
		$this->assertEquals(1, count($d->teachers()));
		$d->add_teacher(1);
		$this->assertEquals(2, count($d->teachers()));
		$d->add_teacher(1);  # double-add doesn't change anything
		$this->assertEquals(2, count($d->teachers()));
		$this->assertEquals('Tiny Tim & Willy Wonka: Songwriting Tips', $d->fullname());
		}

	function testRemoveTeacher()
		{
		$d = new Document(3);
		$this->assertEquals(1, count($d->teachers()));
		$d->remove_teacher(2);  # removing teacher not there doesn't change anything
		$this->assertEquals(1, count($d->teachers()));
		$d->remove_teacher(1);
		$this->assertEquals(0, count($d->teachers()));
		}

	function testDocumentLength()
		{
		$d = new Document(3);
		$d->set(array('pricecode' => 'this should be only t', 'youtube' => 'youtube is required to be 16 chars, max'));
		$d = new Document(3);
		$this->assertEquals('t', $d->pricecode());
		$this->assertEquals(16, strlen($d->youtube()));
		}

	function testPaymentFromStudent()
		{
		$d = new Document(3);
		$this->assertType('Payment', $d->payment_from_student(2));
		$this->assertFalse($d->payment_from_student(1));
		}

	}
?>
