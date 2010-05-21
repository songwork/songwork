<?php
class Money
	{
	public $currency;
	public $millicents;
	public $code;
	function __construct($millicents, $code)
		{
		$this->millicents = intval($millicents);
		$this->currency = new Currency($code);
		$this->code = $code;
		}
	
	function show_no_code()
		{
		return sprintf($this->currency->sprintf, $this->millicents / $this->currency->exponent);
		}

	function show_with_code()
		{
		return ($this->show_no_code() . ' ' . $this->code);
		}

	function show_with_name()
		{
		return ($this->show_no_code() . ' ' . $this->currency->name);
		}

	function __toString()
		{
		return $this->show_with_code();
		}

	function code()
		{
		return $this->code;
		}

	function amount()
		{
		return round($this->millicents / $this->currency->exponent, 2);
		}

	function times($float)
		{
		return new Money($this->millicents * floatval($float), $this->code);
		}

	# add another money amount, but return answer in current currency
	function plus(Money $m)
		{
		$that = ($m->code == $this->code) ? $m : $m->converted_to($this->code);
		return new Money($this->millicents + $that->millicents, $this->code);
		}

	# subtract another money amount, but return answer in current currency
	function minus(Money $m)
		{
		$that = ($m->code == $this->code) ? $m : $m->converted_to($this->code);
		return new Money($this->millicents - $that->millicents, $this->code);
		}

	# boolean : equals close enough?
	function equals(Money $m)
		{
		$test = $this->minus($m);
		return (abs($test->millicents) < 100) ? true : false;
		}

	function converted_to($new_code)
		{
		if($new_code == $this->code) { return $this; }
		return new Money($this->millicents * $this->currency->multiply_to_get($new_code), $new_code);
		}

	# create new Money object from typed/imported money amounts as human-viewed numbers
	# input '$1.23', 'usd', output Money(12300, 'USD')  OR FALSE if bad currency
	static function from_float($float, $code)
		{
		$code = strtoupper(trim($code));
		if(Currency::is_valid($code) == false) { return false; }
		$c = new Currency($code);
		$float = floatval(ereg_replace('[^-0-9\.]', '' , $float));
		return new Money(intval($float * $c->exponent), $code);
		}

	# INPUT: array of Money objects OR objects with money() function that return Money.
	static function sum($array_of_money)
		{
		if(count($array_of_money) == 0)
			{
			return new Money(0, 'USD');
			}
		foreach($array_of_money as $m)
			{
			if(!is_a($m, 'Money') && method_exists($m, 'money'))
				{
				$m = $m->money();
				}
			if(!isset($sum))
				{
				$sum = $m;
				}
			else
				{
				$sum = $sum->plus($m);
				}
			}
		return $sum;
		}
	}

?>
