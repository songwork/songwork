<?php
class Teacher extends SongworkDB
	{
	public $tablename = 'teachers';
	public $fields = array('id', 'person_id', 'created_at', 'profile', 'available', 'consultation_rate');
	private $p;

	function kill()
		{
		$this->db->query("SELECT * FROM documents_teachers WHERE teacher_id=" . $this->id);
		if($this->db->num_rows()) { return false; }
		$this->db->query("SELECT * FROM consultations_teachers WHERE teacher_id=" . $this->id);
		if($this->db->num_rows()) { return false; }
		$this->db->query("DELETE FROM teachers WHERE id=" . $this->id);
		return true;
		}

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

	function has_document_id($document_id)
		{
		$this->db->query("SELECT * FROM documents_teachers WHERE teacher_id=" . $this->id . " AND document_id=" . intval($document_id));
		return ($this->db->num_rows()) ? true : false;
		}

	function documents()
		{
		$res = array();
		$this->db->query("SELECT * FROM documents WHERE id IN (SELECT document_id FROM documents_teachers WHERE teacher_id=" . $this->id . ") ORDER BY id DESC");
		while($x = $this->db->next_record())
			{
			$res[$x['id']] = new Document($x);
			}
		return $res;
		}

	function active_documents()
		{
		$res = array();
		$this->db->query("SELECT * FROM documents WHERE id IN (SELECT document_id FROM documents_teachers WHERE teacher_id=" . $this->id . ") AND added_at IS NOT NULL and removed_at IS NULL ORDER BY id DESC");
		while($x = $this->db->next_record())
			{
			$res[$x['id']] = new Document($x);
			}
		return $res;
		}

	function consultations()
		{
		return Consultation::for_teacher($this->id);
		}

	function consultation_requests()
		{
		return ConsultationRequest::for_teacher($this->id);
		}

	# TODO: add students from consultations, even if no documents?
	function students()
		{
		$res = array();
		$this->db->query("SELECT * FROM students WHERE id IN (SELECT DISTINCT student_id FROM payments WHERE document_id IN (SELECT document_id FROM documents_teachers WHERE teacher_id=" . $this->id . "))");
		while($x = $this->db->next_record())
			{
			$res[$x['id']] = new Student($x);
			}
		return $res;
		}

	function has_student_id($student_id)
		{
		$this->db->query("SELECT id FROM payments WHERE student_id=" . intval($student_id) . " AND document_id IN (SELECT document_id FROM documents_teachers WHERE teacher_id=" . $this->id . ") LIMIT 1");
		return ($this->db->num_rows()) ? true : false;
		}

	function incoming_payments()
		{
		$res = array();
		$this->db->query("SELECT * FROM payments WHERE document_id IN (SELECT document_id FROM documents_teachers WHERE teacher_id=" . $this->id . ") ORDER BY id DESC");
		while($x = $this->db->next_record())
			{
			$res[$x['id']] = new Payment($x);
			}
		return $res;
		}

	function has_payment_id($payment_id)
		{
		$this->db->query("SELECT id FROM payments WHERE id=" . intval($payment_id) . " AND document_id IN (SELECT document_id FROM documents_teachers WHERE teacher_id=" . $this->id . ")");
		return ($this->db->num_rows()) ? true : false;
		}

	static function get_by_person($p)
		{
		if(!is_a($p, 'Person') || intval($p->id) == 0) { return false; }
		$db = Get::db('songwork');
		$db->query("SELECT * FROM teachers WHERE person_id=" . $p->id);
		if ($db->num_rows() == 0) { return false; }
		$x = new Teacher($db->next_record());
		$x->set_person($p);
		return $x;
		}

	static function all()
		{
		$db = Get::db('songwork');
		$res = array();
		$db->query("SELECT * FROM teachers ORDER BY id ASC");
		while($x = $db->next_record())
			{
			$res[$x['id']] = new Teacher($x);
			}
		return $res;
		}

	static function consultable()
		{
		$db = Get::db('songwork');
		$res = array();
		$db->query("SELECT * FROM teachers WHERE available='t' ORDER BY id ASC");
		while($x = $db->next_record())
			{
			$res[$x['id']] = new Teacher($x);
			}
		return $res;
		}

	}
?>
