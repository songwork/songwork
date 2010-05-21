<?php
class Payment extends SongworkDB
	{
	public $tablename = 'payments';
	public $fields = array('id', 'student_id', 'document_id', 'consultation_id', 'currency', 'millicents', 'details', 'created_at');

	function student()
		{
		return new Student($this->me['student_id']);
		}

	function document()
		{
		return (intval($this->me['document_id']) == 0) ? false : new Document($this->me['document_id']);
		}

	function consultation()
		{
		return (intval($this->me['consultation_id']) == 0) ? false : new Consultation($this->me['consultation_id']);
		}

	function money()
		{
		return new Money($this->me['millicents'], $this->me['currency']);
		}

	function paypaltxn() # array
		{
		return PayPalTxn::for_payment_id($this->id);
		}

	static function for_query($query)
		{
		$db = Get::db('songwork');
		$res = array();
		$db->query($query);
		while($x = $db->next_record())
			{
			$res[$x['id']] = new Payment($x);
			}
		return $res;
		}

	static function all()
		{
		return Payment::for_query("SELECT * FROM payments ORDER BY id DESC");
		}

	static function for_student($student_id)
		{
		return Payment::for_query(sprintf("SELECT * FROM payments WHERE student_id=%d ORDER BY id DESC", $student_id));
		}

	static function for_document($document_id)
		{
		return Payment::for_query(sprintf("SELECT * FROM payments WHERE document_id=%d ORDER BY id DESC", $document_id));
		}

	static function for_consultation($consultation_id)
		{
		return Payment::for_query(sprintf("SELECT * FROM payments WHERE consultation_id=%d ORDER BY id DESC", $consultation_id));
		}

	# takes already-saved PayPal transaction in database, gleans info from it to create payment
	# when done, updates PayPal transaction in database with its payment_id. returns payment_id.
	# TODO: make sure it's a complete order?
	static function create_from_pptxn($pptxn_id)
		{
		$t = new PayPalTxn($pptxn_id);
		# if it already has a payment_id, stop here and just return that id
		if($t->payment_id() > 0) { return $t->payment_id(); }
		$student_id = $t->student_id();
		if($student_id === false) { return false; }
		$set = array('student_id' => $student_id, 'created_at' => 'NOW()');
		$money = $t->money();
		$set['currency'] = $money->code;
		$set['millicents'] = $money->millicents;
		$info = $t->infoarray();
		if(!isset($info['item_number'])) { return false; }
		$d = new Document($info['item_number']);
		if($d->failed()) { return false; }
		$set['document_id'] = $d->id;
		$p = new Payment(false);
		$payment_id = $p->add($set);
		$t->set(array('payment_id' => $payment_id));
		return $payment_id;
		}

	}
?>
