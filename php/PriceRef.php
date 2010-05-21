<?php
class PriceRef extends SongworkDB
	{
	public $tablename = 'pricerefs';
	public $fields = array('id', 'code', 'currency', 'millicents');
	public $limits = array('code' => 1, 'currency' => 3);

	function money()
		{
		return new Money($this->me['millicents'], $this->me['currency']);
		}

	# simple array of all used currencies (for creating new priceref)
	static function currencies()
		{
		return array('USD', 'CAD', 'EUR', 'GBP', 'AUD', 'JPY');
		}

	# array of code => array(money)
	static function all()
		{
		$db = Get::db('songwork');
		$res = array();
		$db->query("SELECT DISTINCT code FROM pricerefs ORDER BY code");
		foreach($db->simple_array() as $code)
			{
			$res[$code] = array();
			$db->query("SELECT id, currency, millicents FROM pricerefs WHERE code='$code' ORDER BY id ASC");
			while($x = $db->next_record())
				{
				$res[$code][$x['id']] = new Money($x['millicents'], $x['currency']);
				}
			}
		return $res;
		}

	# smart-ass way of adding a new priceref code:
	# just give me letter and USD millicents
	# I'll fill in all the currency used currencies with that exact amount
	# then you can tweak it later to round it out
	static function add_code_usd($code, $millicents)
		{
		if(strlen($code) != 1 || !preg_match('/^[a-z]$/', $code)) { return false; } # code valid?
		if(intval($millicents) == 0) { return false; }
		$db = Get::db('songwork');
		$db->query("SELECT code FROM pricerefs WHERE code='$code'");
		if($db->num_rows()) { return false; }  # code available?
		$db->insert('pricerefs', array('code' => $code, 'currency' => 'USD', 'millicents' => intval($millicents)));
		$usd = new Money($millicents, 'USD');
		foreach(PriceRef::currencies() as $currency)
			{
			if($currency == 'USD') { continue; }
			$x = $usd->converted_to($currency);
			$db->insert('pricerefs', array('code' => $code, 'currency' => $currency, 'millicents' => $x->millicents));
			}
		return true;
		}

	static function prices_for_code($code)
		{
		if(!preg_match('/^[a-z]$/', $code)) { return array(); }
		$db = Get::db('songwork');
		$res = array();
		$db->query("SELECT * FROM pricerefs WHERE code='$code'");
		while($x = $db->next_record())
			{
			$res[$x['id']] = new PriceRef($x);
			}
		return $res;
		}

	# if not in requested currency, return in USD
	static function money_for_code_currency($code, $currency)
		{
		if(!preg_match('/^[a-z]$/', $code)) { return false; }
		if(!Currency::is_valid($currency)) { return false; }
		$db = Get::db('songwork');
		$db->query("SELECT millicents FROM pricerefs WHERE code='$code' AND currency='$currency' LIMIT 1");
		if($db->num_rows() == 0)
			{
			$currency = 'USD';
			$db->query("SELECT millicents FROM pricerefs WHERE code='$code' AND currency='$currency' LIMIT 1");
			if($db->num_rows() == 0) { return false; }
			}
		return new Money($db->onevalue(), $currency);
		}

	# array of code => showmoney, for use in pulldown choice of what priceref to give something
	static function pulldown_for_currency($currency)
		{
		if(!Currency::is_valid($currency)) { return false; }
		$res = array();
		$db = Get::db('songwork');
		$db->query("SELECT code, millicents FROM pricerefs WHERE currency='$currency' ORDER BY millicents ASC");
		while($x = $db->next_record())
			{
			$m = new Money($x['millicents'], $currency);
			$res[$x['code']] = $m->show_with_code();
			}
		return $res;
		}
	}
?>
