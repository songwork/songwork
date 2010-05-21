<?php
class ConsultationRequest extends SongworkDB
	{
	public $tablename = 'consultation_requests';
	public $fields = array('id', 'student_id', 'teacher_id', 'consultation_id', 'created_at', 'answered_at', 'closed_at', 'student_notes', 'teacher_notes');

	function student()
		{
		return new Student($this->me['student_id']);
		}

	function teacher()
		{
		return new Teacher($this->me['teacher_id']);
		}

	function consultation()
		{
		return (intval($this->me['consultation_id']) == 0) ? false : new Consultation($this->me['consultation_id']);
		}

	static function for_query($query)
		{
		$db = Get::db('songwork');
		$res = array();
		$db->query($query);
		while($x = $db->next_record())
			{
			$res[$x['id']] = new ConsultationRequest($x);
			}
		return $res;
		}

	static function all()
		{
		return ConsultationRequest::for_query("SELECT * FROM consultation_requests ORDER BY id DESC");
		}

	static function unanswered()
		{
		return ConsultationRequest::for_query("SELECT * FROM consultation_requests WHERE answered_at IS NULL ORDER BY id ASC");
		}

	static function unclosed()
		{
		return ConsultationRequest::for_query("SELECT * FROM consultation_requests WHERE closed_at IS NULL ORDER BY id ASC");
		}

	static function for_student($student_id)
		{
		return ConsultationRequest::for_query(sprintf("SELECT * FROM consultation_requests WHERE student_id=%d ORDER BY id DESC", $student_id));
		}

	static function for_teacher($teacher_id)
		{
		return ConsultationRequest::for_query(sprintf("SELECT * FROM consultation_requests WHERE teacher_id=%d ORDER BY id DESC", $teacher_id));
		}

	static function for_consultation($consultation_id)
		{
		return ConsultationRequest::for_query(sprintf("SELECT * FROM consultation_requests WHERE consultation_id=%d ORDER BY id DESC", $teacher_id));
		}

	}
?>
