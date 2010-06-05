<?php
class Person extends SongworkDB
	{
	public $attributes = array(); # settable to anything
	public $tablename = 'persons';
	public $fields = array('id', 'email', 'hashpass', 'lopass', 'newpass', 'name', 'fullname', 'address', 'city', 'state', 'postalcode', 'country', 'phone', 'changes', 'notes', 'confirmed', 'listype', 'ip', 'categorize_as', 'created_at');
	public $user_can_change = array('email', 'name', 'fullname', 'address', 'city', 'state', 'postalcode', 'country', 'phone', 'password');
	public $limits = array('email' => 127, 'name' => 127, 'fullname' => 127, 'address' => 64, 'city' => 32, 'state' => 16, 'postalcode' => 12, 'country' => 2,'phone' => 18, 'categorize_as' => 16);
	# KEY = name of database  VALUE = array of tables that have 'person_id' field linked to this id
	private $linked_dbs_tables = array(
		'songwork' => array('students', 'teachers'));
	# TODO: musicthoughts. make it person_id

	function set($array)
		{
		$array['hashpass'] = $array['password'];
		parent::set($array);
		}

	function add($array)
		{
		$array['hashpass'] = $array['password'];
		parent::set($array);
		}

	function firstname()
		{
		list($firstname) = explode(' ', $this->me['name']);
		return $firstname;
		}

	# like set but for when users are updating their own info
	function userset($posted)
		{
		$set = array();
		foreach($this->user_can_change as $fieldname)
			{
			if(isset($posted[$fieldname]) && strlen($posted[$fieldname]))
				{
				$set[$fieldname] = $posted[$fieldname];
				}
			}
		if(count($set))
			{
			$this->set($set);
			}
		}

	function merge_into_me($person_ids)
		{
		foreach($person_ids as $person_id)
			{
			if($person_id == $this->id) { continue; }
			$this->db->query("DELETE FROM logins WHERE person_id=" . $person_id);
			$this->db->query("DELETE FROM persons WHERE id=" . $person_id);
			foreach($this->linked_dbs_tables as $database => $tables)
				{
				$xdb = Get::db($database);
				foreach($tables as $table)
					{
					$xdb->query("UPDATE $table SET person_id=" . $this->id . " WHERE person_id=$person_id");
					}
				}
			}
		}

	# IMPORTANT TODO : go through other databases that reference this person_id !!!
	function killall()
		{
		$this->db->query("DELETE FROM logins WHERE person_id=" . $this->id);
		$this->db->query("DELETE FROM persons WHERE id=" . $this->id);
		foreach($this->linked_dbs_tables as $database => $tables)
			{
			$xdb = Get::db($database);
			foreach($tables as $table)
				{
				$xdb->query("DELETE FROM $table WHERE person_id=$person_id");
				}
			}
		}

	function gravatar($size=80)
		{
		$url = sprintf('http://www.gravatar.com/avatar/%s.jpg?d=404&amp;size=%d', md5($this->me['email']), $size);
		$headers = get_headers($url);
		if(strpos($headers[0], '404'))
			{
			return '';
			}
		return sprintf('<img class="gravatar" src="%s" width="%d" height="%d" alt="%s" />', $url, $size, $size, htmlspecialchars($this->me['name']));
		}

	private function set_newpass()
		{
		if(strlen($this->me['newpass']) == 8) { return $this->me['newpass']; }
		while(true)
			{
			$newpass = randstring(8);
			$this->db->query("SELECT id FROM persons WHERE newpass='$newpass' LIMIT 1");
			if($this->db->num_rows() == 0)
				{
				$this->db->query("UPDATE persons SET newpass='$newpass' WHERE id=" . $this->id);
				return $newpass;
				}
			}
		}

	function email_newpass()
		{
		$newpass = $this->set_newpass();
		$from = 'songwork@songwork.com';
		$subject = 'songwork.com - password reset link';
		$body = "Please click this link to make a new password\n\n";
		$body .= 'http://songwork.com/reset/' . $newpass;
		$body .= "\n\nThanks and sorry for the trouble!";
		$body .= "\n\n--\n";
		$body .= 'Songwork - http://songwork.com - ' . $from;
		$headers = "From: Songwork <$from>\n";
		$headers .= "Reply-To: $from\n";
		$headers .= "Return-Path: $from\n";
		$headers .= "Sender: $from\n";
		$headers .= 'Message-Id: <' . date('Ymdhi') . '.' . $this->id . '@songwork.com>';
		return mail($this->me['email'], $subject, $body, $headers);
		}

	# not for auth, but only name & email in cookie for convenience
	function set_welcome_cookie()
		{
		$timeout = time() + (60 * 60 * 24 * 365);   # 1 year
		setcookie('name', $this->me['name'], $timeout, '/');
		$_COOKIE['name'] = $this->me['name'];
		setcookie('email', $this->me['email'], $timeout, '/');
		$_COOKIE['email'] = $this->me['email'];
		}

	# from book Essential PHP Security : Authentication and Authorization > Persistent Logins
	function set_auth($override_domain=false)
		{
		$domain = ($override_domain) ? $override_domain : Person::domain();
		$identifier = Person::cookie_id_for($this->id, $domain);
		$token = md5(uniqid(rand(), true));
		$timeout = time() + (60 * 60 * 24 * 365);   # 1 year
		$ip = (isset($_SERVER['REMOTE_ADDR'])) ? "'" . $this->db->escape($_SERVER['REMOTE_ADDR']) . "'" : 'NULL';
		if($domain !== 'shell')   # this if only here for phpunit.  TODO: find out how to make phpunit not complain when setcookie, then take away this if
			{
			setcookie('ok', "$identifier:$token", $timeout, '/');
			$_COOKIE['ok'] = "$identifier:$token";
			# also remember their name and email, since we know it!
			$this->set_welcome_cookie();
			}
		# $this->db->query("DELETE FROM logins WHERE cookie_id='$identifier'");  # this prevents multiple logins on multiple browsers. DISABLE FOR NOW.
		$this->db->query("INSERT INTO logins (domain, person_id, cookie_id, cookie_tok, cookie_exp, last_login, ip) VALUES ('$domain', {$this->id}, '$identifier', '$token', $timeout, '" . date('Y-m-d') . "', $ip)");
		}

	# SEMI-PRIVATE. Use Person::destroy_auth() instead
	function delete_logins()
		{
		if(isset($_COOKIE['ok']) && strpos($_COOKIE['ok'], ':'))
			{
			list($identifier, $token) = explode(':', $_COOKIE['ok']);
			$this->db->query("DELETE FROM logins WHERE cookie_id='" . $this->db->escape($identifier) . "' AND cookie_tok='" . $this->db->escape($token) . "'");
			}
		}

	# from book Essential PHP Security : Authentication and Authorization > Persistent Logins
	static function get_by_cookie()
		{
		if(!isset($_COOKIE['ok'])) { return false; }
		if(!strpos($_COOKIE['ok'], ':')) { return false; }
		list($identifier, $token) = explode(':', $_COOKIE['ok']);
		if (!ctype_alnum($identifier) || !ctype_alnum($token)) { return false; }
		if (strlen($identifier) != 32 || strlen($token) != 32) { return false; }
		$db = Get::db('songwork');
		$db->query("SELECT * FROM logins WHERE cookie_id='" . $db->escape($identifier) . "' AND cookie_tok='" . $db->escape($token) . "'");
		if($db->num_rows() == 0)
			{
			# Failure::log(array('okcookie' => $_COOKIE['ok']));
			return false;
			}
		$x = $db->next_record();
		if($x['cookie_exp'] < time()) { return false; }
		if(Person::cookie_id_for($x['person_id'], $x['domain']) != $identifier) { return false; }
		return new Person(intval($x['person_id']));
		}

	static function domain()
		{
		return (isset($_SERVER['SERVER_NAME'])) ? $_SERVER['SERVER_NAME'] : 'shell';
		}

	static function cookie_id_for($id, $domain)
		{
		$salt = 'bjork';
		return md5($domain . md5(strval($id) . $salt));
		}

	static function destroy_auth()
		{
		$p = Person::get_by_cookie();
		if($p !== false)
			{
			$p->delete_logins();
			}
		unset($_COOKIE['ok']);
		unset($_COOKIE['name']);
		unset($_COOKIE['email']);
		setcookie('ok', '', time(), '/');
		setcookie('name', '', time(), '/');
		setcookie('email', '', time(), '/');
		}

	static function get_by_email_pass($email, $password)
		{
		$email = trim(strtolower($email));
		if(!is_valid_email_address($email)) { return false; }
		$password = trim($password);
		if($password == '') { return false; }
		$db = Get::db('songwork');
		$query = sprintf("SELECT * FROM persons WHERE LOWER(email)='%s' AND hashpass=CRYPT('%s', hashpass) LIMIT 1", $db->escape($email), $db->escape($password));
		$db->query($query);
		if($db->num_rows() == 0)
			{
			# Failure::log(array('email' => $email, 'password' => $password));
			return false;
			}
		return new Person($db->next_record());
		}

	static function get_by_id_lopass($id, $lopass)
		{
		$lopass = trim($lopass);
		if(intval($id) < 1 || strlen($lopass) !== 4) { return false; }
		$db = Get::db('songwork');
		$db->query("SELECT * FROM persons WHERE id=" . intval($id) . " AND lopass='" . $db->escape($lopass) . "' LIMIT 1");
		if($db->num_rows() == 0)
			{
			# Failure::log(array('person_id' => $id, 'lopass' => $lopass));
			return false;
			}
		return new Person($db->next_record());
		}

	static function get_by_newpass($newpass)
		{
		if(!ctype_alnum($newpass) || strlen($newpass) !== 8) { return false; }
		$p = false;
		$db = Get::db('songwork');
		$db->query("SELECT * FROM persons WHERE newpass='" . $db->escape($newpass) . "' LIMIT 1");
		if($db->num_rows() == 0)
			{
			# Failure::log(array('newpass' => $newpass));
			return false;
			}
		# $db->query("UPDATE persons SET newpass=NULL WHERE newpass='" . $db->escape($newpass) . "'");
		return new Person($db->next_record());
		}

	static function get_by_email($email)
		{
		$email = trim(strtolower($email));
		if(!is_valid_email_address($email)) { return false; }
		$db = Get::db('songwork');
		$db->query("SELECT * FROM persons WHERE LOWER(email)='" . $db->escape($email) . "' LIMIT 1");
		return ($db->num_rows() == 0) ? false : new Person($db->next_record());
		}

	static function search4($q)
		{
		$results = array();
		if(strlen($q) < 2) { return $results; }
		$db = Get::db('songwork');
		$db->query("SELECT * FROM persons WHERE name ILIKE '%" . $db->escape($q) . "%' OR fullname ILIKE '%" . $db->escape($q) . "%' OR email ILIKE '%" . $db->escape($q) . "%'");
		while($c = $db->next_record())
			{
			$results[$c['id']] = new Person($c);
			}
		return $results;
		}

	static function search_field4($field, $q)
		{
		$results = array();
		if(strlen($q) < 2) { return $results; }
		$db = Get::db('songwork');
		$db->query("SELECT * FROM persons WHERE $field ILIKE '%" . $db->escape($q) . "%'");
		while($c = $db->next_record())
			{
			$results[$c['id']] = new Person($c);
			}
		return $results;
		}

	# INSERT NEW CLIENT
	static function add2($email, $name, $confirmed=true)
		{
		$db = Get::db('songwork');
		$set = array('email' => strtolower(trim($email)), 'name' => trim($name));
		if(isset($_SERVER['REMOTE_ADDR']) && preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/', $_SERVER['REMOTE_ADDR']))
			{
			$set['ip'] = $_SERVER['REMOTE_ADDR'];
			}
		return $db->insert('persons', $set);
		}

	static function newest($limit=100)
		{
		$db = Get::db('songwork');
		$res = array();
		$db->query("SELECT * FROM persons ORDER BY created_at DESC LIMIT " . intval($limit));
		while($x = $db->next_record())
			{
			$res[$x['id']] = new Person($x);
			}
		return $res;
		}

	static function with_ids($array_of_ids)
		{
		# clean incoming array, just in case of bad info
		$real_ids = array();
		foreach($array_of_ids as $maybe_id)
			{
			if(is_integer($maybe_id) || (is_string($maybe_id) && ctype_digit($maybe_id)))
				{
				$real_ids[] = intval($maybe_id);
				}
			}
		if(count($real_ids) == 0) { return array(); }
		$db = Get::db('songwork');
		$res = array();
		$db->query("SELECT * FROM persons WHERE id IN (" . join(',', $real_ids) . ")");
		while($x = $db->next_record())
			{
			$res[$x['id']] = new Person($x);
			}
		return $res;
		}
	}
?>
