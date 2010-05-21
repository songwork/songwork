<?php
# http://www.oanda.com/convert/fxdaily
class Currency
	{
	public $code;
	public $exponent;
	public $rate;
	public $sprintf;
	public $name;
	# tab delimited: code, exponent, rate, sprintf, name
	static $attributes = '
AUD	10000	1.12613	$%0.2f	Australian Dollars
BRL	10000	1.83750	R$%0.2f	Brazilian Real
CAD	10000	1.04280	$%0.2f	Canadian Dollars
EUR	10000	0.787216	€%0.2f	Euro
GBP	10000	0.675402	£%0.2f	Pounds Sterling
INR	100	45.55	%d₨	Indian Rupee
JPY	100	91.56	¥%d	Japanese Yen
USD	10000	1	$%0.2f	U.S. Dollars
';
	static $currencies = array('USD' => 'U.S. Dollars', 'CAD' => 'Canada Dollars', 'EUR' => 'Euro', 'GBP' => 'Pounds Sterling', 'AUD' => 'Australia Dollars', 'JPY' => 'Japan Yen', 'BRL' => 'Brazil Real', 'INR' => 'India Rupee');
	function __construct($code)
		{
		foreach(explode("\n", Currency::$attributes) as $line)
			{
			if(trim($line) == '') { continue; }
			list($thiscode, $exponent, $rate, $sprintf, $name) = explode("\t", trim($line));
			$currencies[$thiscode] = array('exponent' => intval($exponent), 'rate' => floatval($rate), 'sprintf' => $sprintf, 'name' => $name);
			}
		if(!isset($currencies[$code]))
			{
			throw new Exception('bad currency code: ' . $code);
			}
		extract($currencies[$code]);
		$this->code = $code;
		$this->exponent = $exponent;
		$this->rate = $rate;
		$this->sprintf = $sprintf;
		$this->name = $name;
		}

	# note: only used for making new Money! don't use as conversion rate, since it's off by 100 for Yen!
	function multiply_to_get($new_code)
		{
		if($new_code == $this->code) { return 1; }
		$nc = new Currency($new_code);
		$exponent_convert = $nc->exponent / $this->exponent;  # mainly for JPY
		if($new_code == 'USD') { return ((1 / $this->rate) * $exponent_convert); }
		if($this->code == 'USD') { return $nc->rate * $exponent_convert; }
		return (($nc->rate * (1 / $this->rate)) * $exponent_convert);
		}

	static function is_valid($code)
		{
		try
			{
			$c = new Currency($code);
			return true;
			}
		catch(Exception $e)
			{
			return false;
			}
		}
	}

?>
