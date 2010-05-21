<?php
class Consultation extends SongworkDB
	{
	public $tablename = 'consultations';
	public $fields = array('id', 'starts_at', 'instructions', 'currency', 'millicents', 'student_notes', 'teacher_notes', 'done');

	function students()
		{
		$res = array();
		$this->db->query("SELECT * FROM students WHERE id IN (SELECT student_id FROM consultations_students WHERE consultation_id=" . $this->id . ")");
		while($x = $this->db->next_record())
			{
			$res[$x['id']] = new Student($x);
			}
		return $res;
		}

	function teachers()
		{
		$res = array();
		$this->db->query("SELECT * FROM teachers WHERE id IN (SELECT teacher_id FROM consultations_teachers WHERE consultation_id=" . $this->id . ")");
		while($x = $this->db->next_record())
			{
			$res[$x['id']] = new Teacher($x);
			}
		return $res;
		}

	function money()
		{
		return new Money($this->me['millicents'], $this->me['currency']);
		}

	function paid()
		{
		return (count($this->payments()) > 0) ? true : false;
		}

	function payments()
		{
		return Payment::for_consultation($this->id);
		}

	function request()
		{
		return ConsultationRequest::for_consultation($this->id);
		}

	static function for_query($query)
		{
		$db = Get::db('songwork');
		$res = array();
		$db->query($query);
		while($x = $db->next_record())
			{
			$res[$x['id']] = new Consultation($x);
			}
		return $res;
		}

	static function all()
		{
		return Consultation::for_query("SELECT * FROM consultations ORDER BY id DESC");
		}

	static function undone()
		{
		return Consultation::for_query("SELECT * FROM consultations WHERE done='f' ORDER BY id ASC");
		}

	static function unpaid()
		{
		return Consultation::for_query("SELECT * FROM consultations WHERE id NOT IN (SELECT DISTINCT consultation_id FROM payments WHERE consultation_id IS NOT NULL) ORDER BY id ASC");
		}

	static function for_student($student_id)
		{
		return Consultation::for_query(sprintf("SELECT * FROM consultations WHERE id IN (SELECT DISTINCT consultation_id FROM consultations_students WHERE student_id=%d) ORDER BY id DESC", $student_id));
		}

	static function for_teacher($teacher_id)
		{
		return Consultation::for_query(sprintf("SELECT * FROM consultations WHERE id IN (SELECT DISTINCT consultation_id FROM consultations_teachers WHERE teacher_id=%d) ORDER BY id DESC", $teacher_id));
		}
	}
?>
