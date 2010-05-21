<?php
class PayPalTxn extends SongworkDB
	{
	public $tablename = 'paypaltxns';
	public $fields = array('id', 'payment_id', 'created_at', 'txn_id', 'txn_type', 'info', 'reconciled');

	function payment()
		{
		return (intval($this->me['payment_id']) > 0) ? new Payment($this->me['payment_id']) : false;
		}

	function reconciled()
		{
		return ($this->me['reconciled'] == 't') ? true : false;
		}

	function reconcile()
		{
		$this->set(array('reconciled' => 't'));
		}

	# return array of key=>value pairs from info textbox
	function infoarray()
		{
		$a = array();
		foreach(explode("\n", $this->me['info']) as $line)
			{
			preg_match('/(^[^=]*) = (.*$)/', trim($line), $matches);
			if(count($matches) > 2)
				{
				$a[$matches[1]] = $matches[2];
				}
			}
		return $a;
		}

	# take "custom = PERSON_ID=21534 STUDENT_ID=53 TEACHER_ID=false" string, if any, and parse it into k=>v array  (keys lowercased, values as false or integer)
	function custom_parsed()
		{
		$custom = array();
		$info = $this->infoarray();
		if(!isset($info['custom'])) { return $custom(); }
		foreach(explode(' ', $info['custom']) as $pair)
			{
			list($k, $v) = explode('=', $pair);
			$custom[strtolower($k)] = ($v == 'false') ? false : intval($v);
			}
		return $custom;
		}

	# return the value (currency+amount) of this as Money object
	function money()
		{
		$info = $this->infoarray();
		$currency = (isset($info['mc_currency']) && Currency::is_valid($info['mc_currency'])) ? $info['mc_currency'] : 'USD';	# TODO: reject if wrong, not just switch to USD!
                $float = (isset($info['mc_gross'])) ? floatval($info['mc_gross']) : 0;  # TODO: ditto
                $c = new Currency($currency);
                $millicents = intval($float * $c->exponent);  # convert $5.25 into 52500 millicents
		return new Money($millicents, $currency);
		}

	# get or create a student_id from the info in the PayPalTxn. return FALSE if unable
	function student_id()
		{
		$custom = $this->custom_parsed();
		if(isset($custom['student_id']))
			{
			return $custom['student_id'];
			}
		elseif(isset($custom['person_id']))
			{
			$p = new Person($custom['person_id']);
			return Student::addperson($p);
			}
		else
			{
			$info = $this->infoarray();
			if(isset($info['payer_email']))
				{
				$p = Person::get_by_email($info['payer_email']);
				if($p === false)
					{
					$name = $info['first_name'] . ' ' . $info['last_name'];
					$person_id = Person::add2($info['payer_email'], $name);
					return Student::addperson(new Person($person_id));
					}
				}
			}
		return false;
		}

	# VERY LOCALIZED! UPDATE FOR EACH NEW SITE/COMPANY.  CREATES PAYMENT ENTRY, TOO!
	static function create_from_post($postfields, $payment_id = false)
		{
		$infoarray = $_POST;
		$set = array();
		$set['info'] = PayPal::post2string();
		if(isset($infoarray['txn_id']))
			{
			$set['txn_id'] = $infoarray['txn_id'];
			}
		}

	static function for_payment_id($payment_id)
		{
		$db = Get::db('songwork');
		$res = array();
		$db->query("SELECT * FROM paypaltxns WHERE payment_id=" . intval($payment_id) . " ORDER BY id ASC");
		while($x = $db->next_record())
			{
			$res[$x['id']] = new PayPalTxn($x);
			}
		return $res;
		}

	static function without_payment_id()
		{
		$db = Get::db('songwork');
		$res = array();
		$db->query("SELECT * FROM paypaltxns WHERE payment_id IS NULL ORDER BY id ASC");
		while($x = $db->next_record())
			{
			$res[$x['id']] = new PayPalTxn($x);
			}
		return $res;
		}

	static function not_reconciled()
		{
		$db = Get::db('songwork');
		$res = array();
		$db->query("SELECT * FROM paypaltxns WHERE reconciled='f' ORDER BY id ASC");
		while($x = $db->next_record())
			{
			$res[$x['id']] = new PayPalTxn($x);
			}
		return $res;
		}

	static function all()
		{
		$db = Get::db('songwork');
		$res = array();
		$db->query("SELECT * FROM paypaltxns ORDER BY id DESC");
		while($x = $db->next_record())
			{
			$res[$x['id']] = new PayPalTxn($x);
			}
		return $res;
		}

	}
?>
