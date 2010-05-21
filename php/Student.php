<?php
class Student extends SongworkDB
	{
	public $tablename = 'students';
	public $fields = array('id', 'person_id', 'created_at', 'their_notes', 'our_notes');
	private $p;
	private $paid_document_cache;

	function set_person(Person $p)
		{
		$this->p = $p;
		}

	function person()
		{
		if(!is_a($this->p, 'Person'))
			{
			$this->p = new Person(intval($this->me['person_id']));
			}
		return $this->p;
		}

	function name()
		{
		$p = $this->person();
		return $p->name();
		}

	function email()
		{
		$p = $this->person();
		return $p->email();
		}

	function payments()
		{
		return Payment::for_student($this->id);
		}

	function consultations()
		{
		return Consultation::for_student($this->id);
		}

	function consultation_requests()
		{
		return ConsultationRequest::for_student($this->id);
		}

	function paid_documents()
		{
		if(is_array($this->paid_document_cache))
			{
			return $this->paid_document_cache;
			}
		$this->paid_document_cache = array();
		$this->db->query("SELECT * FROM documents WHERE id IN (SELECT DISTINCT(document_id) FROM payments WHERE student_id=" . $this->id . " AND document_id IS NOT NULL) ORDER BY id DESC");
		while($x = $this->db->next_record())
			{
			$this->paid_document_cache[$x['id']] = new Document($x);
			}
		return $this->paid_document_cache;
		}

	function paid_for_document($document_id)
		{
		return (in_array($document_id, array_keys($this->paid_documents()))) ? true : false;
		}

	# get OR MAKE new student (& therefore Person) from PayPal posts
	# only used if someone buys something but isn't logged in as a student already
	static function from_paypal($postfields)
		{
		if(!isset($postfields['payer_email'])) { return false; }
		$email = $postfields['payer_email'];
		$name = (isset($postfields['first_name'])) ? $postfields['first_name'] : '';
		if(isset($postfields['last_name']))
			{
			$name .= ' ' . $postfields['last_name'];
			}
		$p = Person::get_by_email($email);
		if($p === false)
			{
			$p = new Person(Person::add2($email, $name));
			}
		$s = Student::get_by_person($p);
		if($s === false)
			{
			$s = new Student(Student::addperson($p));
			}
		return $s;
		}

	static function get_by_person($p)
		{
		if(!is_a($p, 'Person') || intval($p->id) == 0) { return false; }
		$db = Get::db('songwork');
		$db->query("SELECT * FROM students WHERE person_id=" . $p->id);
		if ($db->num_rows() == 0) { return false; }
		$x = new Student($db->next_record());
		$x->set_person($p);
		return $x;
		}

	static function addperson(Person $p)
		{
		if(!is_a($p, 'Person') || intval($p->id) == 0) { return false; }
		$s = Student::get_by_person($p);
		if($s !== false)
			{
			return $s->id;
			}
		$db = Get::db('songwork');
		return $db->insert('students', array('person_id' => $p->id));
		}

	static function all()
		{
		$db = Get::db('songwork');
		$res = array();
		$db->query("SELECT * FROM students ORDER BY id DESC");
		while($x = $db->next_record())
			{
			$res[$x['id']] = new Student($x);
			}
		return $res;
		}

	}
?>
