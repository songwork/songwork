<?php
class Tag extends SongworkDB
	{
	public $tablename = 'tags';
	public $fields = array('id', 'name');
	public $limits = array('name' => 32);

	function add($array)
		{
		if(!isset($array['name'])) { return false; }
		$this->db->query("SELECT id FROM tags WHERE name='" . $this->db->escape($array['name']) . "'");
		if($this->db->num_rows())
			{
			$x = $this->db->next_record();
			return intval($x['id']);
			}
		return $this->db->insert('tags', $array);
		}

	function documents()
		{
		return Document::with_tag($this->id);
		}

	static function all()
		{
		$db = Get::db('songwork');
		$res = array();
		$db->query("SELECT id, name FROM tags ORDER BY id ASC");
		while($x = $db->next_record())
			{
			$res[$x['id']] = new Tag($x);
			}
		return $res;
		}

	# sorted by popularity - includes attribute 'howmany'
	static function popular()
		{
		$db = Get::db('songwork');
		$res = array();
		$db->query("SELECT id, name, COUNT(*) FROM documents_tags LEFT JOIN tags ON documents_tags.tag_id=tags.id GROUP BY id, name ORDER BY COUNT(*) DESC");
		while($x = $db->next_record())
			{
			$res[$x['id']] = new Tag($x);
			}
		return $res;
		}

	}
?>
