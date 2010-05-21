<?php
class SongworkDB
	{
	public $dbname = 'songwork';
	public $tablename;		# MUST SET IN CHILD!
	public $fields = array();	# MUST SET IN CHILD!
	public $limits = array();	# optional set in child (key=fieldname, value=maxlength)
	public $failed = false;
	protected $db;
	protected $me = array();
	public $id = false;
	function __construct($id_or_array)
		{
		$this->db = Get::db($this->dbname);
		if(is_array($id_or_array))
			{
			$this->me = $id_or_array;
			$this->id = intval($id_or_array['id']);
			}
		elseif(is_int($id_or_array) || (is_string($id_or_array) && ctype_digit($id_or_array)))
			{
			$this->id = intval($id_or_array);
			$this->db->query("SELECT * FROM {$this->tablename} WHERE id=" . $this->id);
			if($this->db->num_rows() == 0)
				{
				$this->failed = true;
				}
			$this->me = $this->db->next_record();
			}
		}

	function __call($key, $args=array())
		{
		if(!array_key_exists($key, $this->me)) { throw new Exception("no key $key in me for class " . get_class($this)); }
		return $this->me[$key];
		}

	function get()
		{
		return $this->me;
		}

	function set($array)
		{
		$set = ($this->id) ? array('id' => $this->id) : array();
		foreach($this->fields as $key)
			{
			if($key == 'id') { continue; }
			if(isset($array[$key]))
				{
				# maxlength of fields?
				if(isset($this->limits) && isset($this->limits[$key]))
					{
					$array[$key] = mb_substr($array[$key], 0, $this->limits[$key]);
					}
				$set[$key] = trim(str_replace("\r", '', $array[$key]));
				$this->me[$key] = $set[$key];
				}
			}
		if(count($set) > 1)
			{
			return $this->db->update($this->tablename, $set);
			}
		}

	function add($array)
		{
		$set = array();
		foreach($this->fields as $key)
			{
			if($key == 'id') { continue; }
			if(isset($array[$key]))
				{
				# maxlength of fields?
				if(isset($this->limits) && isset($this->limits[$key]))
					{
					$array[$key] = mb_substr($array[$key], 0, $this->limits[$key]);
					}
				$set[$key] = trim(str_replace("\r", '', $array[$key]));
				}
			}
		if(count($set))
			{
			return $this->db->insert($this->tablename, $set);
			}
		}

	function kill()
		{
		$this->db->query("DELETE FROM {$this->tablename} WHERE id={$this->id}");
		}

	function failed()
		{
		return $this->failed;
		}

	}
?>
