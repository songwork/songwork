<?php
# TODO:  client_id?
# https://cms.paypal.com/us/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_html_Appx_websitestandard_htmlvariables
/* USAGE:
$ppb = new PayPalButton('songwork@songwork.com', '/images/buynow.png');
$ppb->currency = 'EUR';
$ppb->amount = 15.50;
$ppb->name = 'Willy Wonka Teaches You the Blues';
$ppb->number = 153;
$ppb->custom = PayPalButton::custom_from_objects(array('person' => $p, 'student' => $student, 'teacher' => $teacher));
$ppb->notify_url = 'http://songwork.com/paypal';
$ppb->return = 'http://songwork.com/paypal/thanks';
$ppb->cancel_return = 'http://songwork.com/paypal/cancel';
$ppb->encrypt('5AMVCVBDY7LUS'); # certificate ID
$qv['paypal_button'] = $ppb->html();
*/
class PayPalButton
	{
	private $action = 'https://www.paypal.com/cgi-bin/webscr';
	private $business;
	private $buttonimagepath;
	private $encrypted = false;
	public $type = 'buynow';  # OPTIONS: buynow, donate  TODO: subscribe
	public $notify_url = '';
	public $return = '';
	public $return_method = '2'; # OPTIONS: 0=GET+vars, 1=GET no vars, 2=POST+vars
	public $cancel_return = '';
	public $currency = 'USD';
	public $amount = 0.00;   # not including shipping, handling, tax
	public $name = '';
	public $number = 0;
	public $invoice = 0;
	public $quantity = 1;
	public $shipping = 0;
	public $tax = 0;
	public $custom = '';
	public $image_url = '';  # (for PayPal.com checkout page)
	public $lang = 'US';  # OPTIONS: COUNTRY CODE (not lang!)
	public $no_note = '1';  # OPTIONS: 0 if you want a text box for them to leave a note
	public $no_shipping = '1';  # OPTIONS: 0=address optional, 1=address not asked, 2=address required

	function __construct($business, $buttonimagepath)
		{
		$this->business = $business;
		$this->buttonimagepath = $buttonimagepath;
		}

	function html()
		{
		$html = '<form action="' . $this->action . '" method="post">';
		if($this->encrypted !== false)
			{
			$html .= '<input type="hidden" name="cmd" value="_s-xclick" />';
			$html .= '<input type="hidden" name="encrypted" value="' . $this->encrypted . '" />';
			}
		else
			{
			foreach($this->postvars() as $k => $v)
				{
				$html .= '<input type="hidden" name="' . $k . '" value="' . htmlspecialchars($v) . '" />';
				}
			}
		$html .= '<input type="image" src="' . $this->buttonimagepath . '" name="submit" />';
		$html .= '</form>';
		return $html;
		}

	# https://cms.paypal.com/us/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_html_encryptedwebpayments#id08A3I0P017Q
	# save keys in /php/paypal/ as named, below
	function encrypt($certificate_id)
		{
		# since this is a shared class, but certs are site-specific, go through include_paths to find realpath
		foreach(explode(':', ini_get('include_path')) as $path)
			{
			if(file_exists($path . '/paypal/paypal.cert'))
				{
				$public_file = realpath($path . '/paypal/public.cert');
				$private_file = realpath($path . '/paypal/private.cert');
				$paypal_file = realpath($path . '/paypal/paypal.cert');
				$public_cert = openssl_x509_read(file_get_contents($public_file));
				$private_cert = openssl_get_privatekey(file_get_contents($private_file));
				if(openssl_x509_check_private_key($public_cert, $private_cert) === false) { return false; }
				$paypal_cert = openssl_x509_read(file_get_contents($paypal_file));
				break;
				}
			}
		$clear_text = 'cert_id=' . $certificate_id;
		foreach($this->postvars() as $k => $v)
			{
			$clear_text .= "\n" . $k . '=' . $v;
			}
		$clear_file = tempnam('/tmp/', 'clear_');  # alt: sys_get_temp_dir()
		$signed_file = preg_replace('/clear/', 'signed', $clear_file);
		$encrypted_file = preg_replace('/clear/', 'encrypted', $clear_file);
		file_put_contents($clear_file, $clear_text);
		if(!openssl_pkcs7_sign($clear_file, $signed_file, $public_cert, $private_cert, array(), PKCS7_BINARY)) { return false; }
		list($x, $signed_text) = explode("\n\n", file_get_contents($signed_file));  #?
		file_put_contents($signed_file, base64_decode($signed_text));
		if(!openssl_pkcs7_encrypt($signed_file, $encrypted_file, $paypal_cert, array(), PKCS7_BINARY)) { return false; }
		list($x, $encrypted_text) = explode("\n\n", file_get_contents($encrypted_file));  #?
		$this->encrypted = "\n-----BEGIN PKCS7-----\n$encrypted_text\n-----END PKCS7-----\n";
		@unlink($clear_file);
		@unlink($signed_file);
		@unlink($encrypted_file);
		}

	function testing()
		{
		$this->action = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
		}

	private function postvars()
		{
		$vars = array('business' => $this->business);
		switch($this->type)
			{
			case 'donate': $vars['cmd'] = '_donations';
			case 'subscribe': $vars['cmd'] = '_xclick-subscriptions';
			case 'buynow': default: $vars['cmd'] = '_xclick';
			}
		# best to set these, but here are defaults:
		if($this->notify_url == '') { $this->notify_url = 'http://' . $_SERVER['HTTP_HOST'] . '/paypal'; }
		if($this->return == '') { $this->return = 'http://' . $_SERVER['HTTP_HOST'] . '/paypal/thanks'; }
		if($this->cancel_return == '') { $this->cancel_return = 'http://' . $_SERVER['HTTP_HOST'] . '/paypal/cancel'; }
		$vars['notify_url'] = $this->notify_url;
		$vars['return'] = $this->return;
		$vars['rm'] = $this->return_method;
		$vars['cancel_return'] = $this->cancel_return;
		$vars['currency_code'] = $this->currency;
		$vars['amount'] = $this->amount;
		$vars['item_name'] = $this->name;
		if($this->number) { $vars['item_number'] = $this->number; }
		if($this->invoice) { $vars['invoice'] = $this->invoice; }
		$vars['quantity'] = $this->quantity;
		if($this->shipping) { $vars['shipping'] = $this->shipping; }
		if($this->tax) { $vars['tax'] = $this->tax; }
		if($this->custom) { $vars['custom'] = $this->custom; }
		if($this->image_url) { $vars['image_url'] = $this->image_url; }
		$vars['lc'] = $this->lang;
		$vars['no_note'] = $this->no_note;
		$vars['no_shipping'] = $this->no_shipping;
		return $vars;
		}

	# assumes any object you pass it is either FALSE or has a property/var 'id'
	# returns string like 'PERSON_ID=21534 STUDENT_ID=53 TEACHER_ID=false'
	static function custom_from_objects($objects)
		{
		$custom = '';
		foreach($objects as $name => $object)
			{
			$custom .= strtoupper($name) . '_ID=';
			if($object === false)
				{
				$custom .= 'false ';
				}
			else
				{
				$custom .= $object->id . ' ';
				}
			}
		return trim($custom);
		}
	}
?>
