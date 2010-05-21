<?php
require_once 'test.php';

class PriceRefTest extends PHPUnit_Framework_TestCase
	{

	function setUp() { shell_exec('sh regen-tests.sh'); }

	function testPriceRefGet()
		{
		$p = new PriceRef(1);
		$this->assertEquals(10000, $p->millicents());
		}

	function testPriceRefAdd()
		{
		$p = new PriceRef(false);
		$set = array('code' => 'c', 'currency' => 'USD', 'millicents' => 12300);
		$new_id = $p->add($set);
		$p = new PriceRef($new_id);
		$m = $p->money();
		$this->assertEquals('$1.23 USD', $m->show_with_code());
		}

	function testPricesForCode()
		{
		$ps = PriceRef::prices_for_code('b');
		$this->assertType('array', $ps);
		$this->assertEquals(4, count($ps));
		$p = $ps['8'];
		$this->assertType('PriceRef', $p);
		$this->assertEquals(150000, $p->millicents());
		}

	function testPricesForCode2()
		{
		$p = new PriceRef(false);
		$set = array('code' => 'b', 'currency' => 'AUD', 'millicents' => 12300);
		$new_id = $p->add($set);
		$ps = PriceRef::prices_for_code('b');
		$this->assertEquals(5, count($ps));
		$p = $ps[$new_id];
		$m = $p->money();
		$this->assertEquals('$1.23 AUD', $m->show_with_code());
		}

	function testMoneyForCodeCurrency()
		{
		$m = PriceRef::money_for_code_currency('a', 'EUR');
		$this->assertEquals('€0.70 EUR', $m->show_with_code());
		$m = PriceRef::money_for_code_currency('a', 'GBP');
		$this->assertEquals('£0.50 GBP', $m->show_with_code());
		}

	function testPulldown()
		{
		$y = PriceRef::pulldown_for_currency('USD');
		$this->assertEquals('$1.00 USD', $y['a']);
		$this->assertEquals('$2.00 USD', $y['b']);
		}

	function testAll()
		{
		$y = PriceRef::all();
		$this->assertType('array', $y);
		$this->assertEquals(2, count($y));
		$this->assertType('array', $y['a']);
		$this->assertType('array', $y['b']);
		$z = $y['a'];
		$this->assertEquals(4, count($z));
		$this->assertType('Money', $z['1']);
		$this->assertEquals(new Money(10000, 'USD'), $z['1']);
		$z = $y['b'];
		$this->assertEquals(new Money(150000, 'JPY'), $z['8']);
		}

	function testCurrencies()
		{
		$y = PriceRef::currencies();
		$this->assertEquals(6, count($y));
		$this->assertTrue(in_array('JPY', $y));
		}
	}
?>
