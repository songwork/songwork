<?php
class Administrator extends SongworkDB
	{
	public $tablename = 'admins';
	public $fields = array('id', 'person_id');
	private $p;

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

	static function get_by_person($p)
		{
		if(!is_a($p, 'Person') || intval($p->id) == 0) { return false; }
		$db = Get::db('songwork');
		$db->query("SELECT * FROM admins WHERE person_id=" . $p->id);
		if ($db->num_rows() == 0) { return false; }
		$x = new Administrator($db->next_record());
		$x->set_person($p);
		return $x;
		}

	}
?>
