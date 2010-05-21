<?php
class Document extends SongworkDB
	{
	public $tablename = 'documents';
	public $fields = array('id', 'created_at', 'added_at', 'removed_at', 'name', 'bytes', 'mediatype', 'url', 'youtube', 'sentence', 'description', 'pricecode', 'length');
	public $limits = array('name' => 127, 'mediatype' => 32, 'url' => 127, 'youtube' => 16, 'pricecode' => 1, 'length' => 24);

	function kill()
		{
		# can't kill if someone's paid for it.
		$this->db->query("SELECT id FROM payments WHERE document_id=" . $this->id);
		if($this->db->num_rows())
			{
			return false;
			}
		$this->db->query("DELETE FROM documents_teachers WHERE document_id=" . $this->id);
		$this->db->query("DELETE FROM documents WHERE id=" . $this->id);
		return true;
		}

	function add_teacher($teacher_id)
		{
		$teacher_id = intval($teacher_id);
		$t = new Teacher($teacher_id);
		if($t->failed) { return false; }
		$document_id = $this->id;
		$this->db->query("SELECT * FROM documents_teachers WHERE document_id=$document_id AND teacher_id=$teacher_id");
		if($this->db->num_rows() == 0)
			{
			$this->db->query("INSERT INTO documents_teachers (document_id, teacher_id) VALUES ($document_id, $teacher_id)");
			}
		}

	function remove_teacher($teacher_id)
		{
		$this->db->query("DELETE FROM documents_teachers WHERE document_id=" . $this->id . " AND teacher_id=" . intval($teacher_id));
		}

	function teachers()
		{
		$ret = array();
		$this->db->query("SELECT * FROM teachers WHERE id IN (SELECT teacher_id FROM documents_teachers WHERE document_id=" . $this->id . ") ORDER BY id ASC");
		while($x = $this->db->next_record())
			{
			$ret[$x['id']] = new Teacher($x);
			}
		return $ret;
		}

	function add_tag($tag_id)
		{
		$tag_id = intval($tag_id);
		$t = new Tag($tag_id);
		if($t->failed) { return false; }
		$document_id = $this->id;
		$this->db->query("SELECT * FROM documents_tags WHERE document_id=$document_id AND tag_id=$tag_id");
		if($this->db->num_rows() == 0)
			{
			$this->db->query("INSERT INTO documents_tags (document_id, tag_id) VALUES ($document_id, $tag_id)");
			}
		}

	function remove_tag($tag_id)
		{
		$this->db->query("DELETE FROM documents_tags WHERE document_id=" . $this->id . " AND tag_id=" . intval($tag_id));
		}

	function tags()
		{
		$ret = array();
		$this->db->query("SELECT * FROM tags WHERE id IN (SELECT tag_id FROM documents_tags WHERE document_id=" . $this->id . ")");
		while($x = $this->db->next_record())
			{
			$ret[$x['id']] = new Tag($x);
			}
		return $ret;
		}

	function status()
		{
		if(strval($this->me['added_at']) == '')
			{
			return 'WAITING';
			}
		if(strval($this->me['removed_at']) == '')
			{
			return 'ACTIVE';
			}
		return 'REMOVED';
		}

	function activate()
		{
		$this->set(array('added_at' => 'NOW()', 'removed_at' => 'NULL'));
		}

	function remove()
		{
		if(strval($this->me['removed_at']) == '')
			{
			$this->set(array('removed_at' => 'NOW()'));
			return true;
			}
		return false;
		}

	function payments()
		{
		return Payment::for_document($this->id);
		}

	function payment_from_student($student_id)  # or FALSE if none
		{
		$this->db->query(sprintf("SELECT * FROM payments WHERE student_id=%d AND document_id=%d LIMIT 1", $student_id, $this->id));
		return ($this->db->num_rows() == 0) ? false : new Payment($this->db->next_record());
		}

	# Steve Seskin & Pat Pattison (if 2. or just name if one)
	function teachernames()
		{
		$teacher_names = array();
		foreach($this->teachers() as $t)
			{
			$teacher_names[] = $t->name();
			}
		return join(' & ', $teacher_names);
		}

	# each name links to /teacher/$id - ALREADY HTML ESCAPED!
	function linked_teachernames()
		{
		$teacher_names = array();
		foreach($this->teachers() as $t)
			{
			$teacher_names[] = '<a href="/teacher/' . $t->id . '">' . htmlspecialchars($t->name()) . '</a>';
			}
		return join(' &amp; ', $teacher_names);
		}

	# Steve Seskin & Pat Pattison: We Show You Skills
	function fullname()
		{
		return ($this->teachernames() . ': ' . $this->me['name']);
		}

	# ALREADY HTML ESCAPED - document links to /document/$id
	function linked_fullname()
		{
		return ($this->linked_teachernames() . ': <a href="/document/' . $this->id . '">' . htmlspecialchars($this->me['name']) . '</a>');
		}

	function filename_for($ext)
		{
		$filename = 'SONGWORK-';
		$filename .= preg_replace('/[^a-zA-Z0-9]/', '', $this->teachernames()) . '-';
		$filename .= preg_replace('/[^a-zA-Z0-9]/', '', $this->me['name']);
		$filename .= '.' . strtolower($ext);
		return $filename;
		}

	function showsize()
		{
		if(intval($this->me['bytes']) == 0) { return 'EMPTY FILE'; }
		$new = intval($this->me['bytes']);
		foreach(array('k', 'mb', 'gb') as $suffix)
			{
			$new = intval($new / 1024);
			if($new < 1024)
				{
				return "$new$suffix";
				}
			}
		return "$new$suffix";
		}

	# not including '/download', just '/5/FileName.mov'
	function download_relative_uri()
		{
		return sprintf('/%d/%s', $this->id, $this->me['url']);
		}

	# type = stream or download.  FALSE or file fullpath
	function file_found($type)
		{
		if(!in_array($type, array('stream', 'download'))) { return false; }
		if(strval($this->me['url']) == '') { return false; }
		return Video::fullpath($type, $this->me['url']);
		}

	# return Money - in USD if not in requested currency, though MIGHT BE FALSE if bad info given.
	function price_in($currency)
		{
		if(strval($this->me['pricecode']) == '')
			{
			return new Money(0, $currency);
			}
		return PriceRef::money_for_code_currency($this->me['pricecode'], $currency);
		}

	function paypal_button($currency, $objects = array())
		{
		$m = $this->price_in($currency);
		if($m->amount() < 0.01) { return ''; }
		$ppb = new PayPalButton('songwork@songwork.com', '/images/buynow-cc-122x47.gif');
		$ppb->currency = $m->code;
		$ppb->amount = $m->amount();
		$ppb->name = $this->me['name'];
		$ppb->number = $this->id;
		if(count($objects)) { $ppb->custom = PayPalButton::custom_from_objects($objects); }
		$ppb->notify_url = 'http://songwork.com/paypal';
		$ppb->return = 'http://songwork.com/paypal/thanks';
		$ppb->cancel_return = 'http://songwork.com/paypal/cancel';
		$ppb->encrypt('LDRBE6FGNFGNE');
		return $ppb->html();
		}

	static function for_query($query)
		{
		$db = Get::db('songwork');
		$res = array();
		$db->query($query);
		while($x = $db->next_record())
			{
			$res[$x['id']] = new Document($x);
			}
		return $res;
		}

	# NOTE: INCLUDES DELETED ONES!
	static function all()
		{
		return Document::for_query("SELECT * FROM documents ORDER BY id DESC");
		}

	# USE THIS for the site. Only shows active ones.
	static function active()
		{
		return Document::for_query("SELECT * FROM documents WHERE added_at IS NOT NULL and removed_at IS NULL ORDER BY id DESC");
		}

	static function waiting()
		{
		return Document::for_query("SELECT * FROM documents WHERE added_at IS NULL ORDER BY id ASC");
		}

	static function removed()
		{
		return Document::for_query("SELECT * FROM documents WHERE removed_at IS NOT NULL ORDER BY id DESC");
		}

	static function with_tag($tag_id)  # active
		{
		return Document::for_query("SELECT * FROM documents WHERE added_at IS NOT NULL and removed_at IS NULL AND id IN (SELECT DISTINCT document_id FROM documents_tags WHERE tag_id=" . intval($tag_id) . ") ORDER BY id DESC");
		}

	static function tagless()
		{
		return Document::for_query("SELECT * FROM documents WHERE id NOT IN (SELECT DISTINCT document_id FROM documents_tags) ORDER BY id ASC");
		}
	}
?>
