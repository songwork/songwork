<?php
/* PayPal class with central (non-database) knowledge in /shared/
Use localized PayPalTxn class that goes to paypaltxns table in local database. Standard CRUD.  */
class PayPal
	{
	static function ipn_verified()
		{
		$poststring = 'cmd=_notify-validate';
		foreach($_POST as $k => $v)
			{
			$poststring .= "&$k=$v";
			}
		$ch = curl_init('https://www.paypal.com/cgi-bin/webscr');
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $poststring);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true); 
		curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . '/ca-bundle.crt'); 
		$response = curl_exec($ch);
		return (strpos($response, 'VERIFIED') !== false) ? true : false;
		}

	static function post2string($array)
		{
		$lines = array();
		foreach($array as $k => $v)
			{
			$lines[] = "$k = $v";
			}
		return join("\n", $lines);
		}

	# requires local /php/ directory to have PayPalTxn class.
	static function logit($payment_id = false)
		{
		$set = array();
		$set['info'] = PayPal::post2string($_POST);
		$separate_fields = array('txn_id', 'txn_type');
		foreach($separate_fields as $fieldname)
			{
			if(isset($_POST[$fieldname])) { $set[$fieldname] = $_POST[$fieldname]; }
			}
		$ppt = new PayPalTxn(false);
		return $ppt->add($set);
		}
	}
?>
