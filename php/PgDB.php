<?php
# same as MySQL class
class PgDB
	{
	private $link;
	private $result;
	
	function __construct($database, $user)
		{
		$connection_string = "dbname=$database user=$user";
		$this->link = pg_connect($connection_string) or $this->nicedie(pg_last_error($this->link));
		}

	function nicedie($error)
		{
		$body = $error . "\n\n";
		foreach($_SERVER as $k => $v)
			{
			$body .= $k . ' = ' . $v . "\n";
			}
		file_put_contents('/tmp/PG_ERRORS', $body . "\n===========================================================================\n", FILE_APPEND);
		die("<html><body><h3>Ooops.  Sorry I made a mistake.</h3><p>I've logged the error, to get fixed immediately.</p><p>Please use your back-arrow to continue, with my apologies.</p></body></html>");
		}

	function escape($string)
		{
		return pg_escape_string($this->link, $string);
		}

	function query($query)
		{ 
		if(defined('WE_ARE_TESTING'))
			{
			file_put_contents('/tmp/pg-query', $query . ";\n\n", FILE_APPEND);
			}
		$this->result = pg_query($this->link, $query) or $this->nicedie($query . "\n\n" . pg_last_error($this->link));
		}
	
	function next_record()
		{
		return pg_fetch_assoc($this->result);
		}
	
	function num_rows()
		{
		return pg_num_rows($this->result);
		}
	
	# INPUT: tablename, array of ('fieldname'=>'value'), and if primary key is not 'id', pass primary key fieldname as 3rd value
	function update($tablename, $infoarray, $idfield='id')
		{
		if(!isset($infoarray[$idfield]))
			{
			return $this->insert($tablename, $infoarray, $idfield);
			}
		$query_loop_started = false;
		$query = "UPDATE $tablename SET ";
		foreach($infoarray as $k => $v)
			{
			if($k == $idfield) { continue; }
			if($query_loop_started==true) { $query .= ', '; }
			$query .= $k . '=';
			$query .= $this->escaped_value_from_key_value($k, $v);
			$query_loop_started = true;
			}
		$query .= " WHERE $idfield='" . $infoarray[$idfield] . "'";
		$this->query($query);
		return $infoarray[$idfield];
		}

	# INPUT: tablename, array of ('fieldname'=>'value'), and if primary key is not 'id', pass primary key fieldname as 3rd value
	# NOTE: if value is nothing, skips the field!  prevents PostgreSQL complaining if '' is inserted as integer
	function insert($tablename, $infoarray, $idfield='id')
		{
		$query = "INSERT INTO $tablename ($idfield";
		foreach($infoarray as $k => $v)
			{
			if(strlen($v) == 0) { continue; }  # skip if no value
			$query .= ", $k";
			}
		$query .= ") VALUES (DEFAULT";
		foreach($infoarray as $k => $v)
			{
			if(strlen($v) == 0) { continue; }  # skip if no value
			$query .= ', ';
			$query .= $this->escaped_value_from_key_value($k, $v);
			}
		$query .= ") RETURNING $idfield";
		$this->query($query);
		return $this->onevalue();
		}

	# return array of the only fieldname from the query results.  (used if ONLY one fieldname was in query)
	function simple_array()
		{
		$array = array();
		while($x = pg_fetch_row($this->result))
			{
			$array[] = $x[0];
			}
		return $array;
		}
	
	# smart (overload) function to return hash/array of values with this key.
	# if 2 fieldnames were in query, then the one that's not the key is the value
	# if more than 2 fieldnames in query, they are returned as hash array
	function hash_with_key($key)
		{
		$all = array();
		while($x = $this->next_record())
			{
			if(count($x) == 2)
				{
				$key_value = $x[$key];
				unset($x[$key]);
				$leftover = array_values($x);
				$all[$key_value] = $leftover[0];
				}
			else
				{
				$all[$x[$key]] = $x;
				}
			}
		return $all;
		}
	
	function onevalue()
		{
		$x = pg_fetch_row($this->result);
		return $x[0];
		}

	# return array of this fieldname from the query results.  (used if more than one fieldname was in query)
	function array_of($fieldname)
		{
		$array = array();
		while($x = $this->next_record())
			{
			$array[] = $x[$fieldname];
			}
		return $array;
		}

	private function escaped_value_from_key_value($k, $v)
		{
		# special fieldname "hashpass" has its incoming value crypted. requires postgresql-contrib & pgcrypto functions installed
		if($k == 'hashpass')
			{
			return sprintf("CRYPT('%s', GEN_SALT('bf', 8))", $this->escape($v));
			}
		# NULL and NOW() pass through unquoted
		elseif($v == 'NULL' || $v == 'NOW()')
			{
			return $v;
			}
		else
			{
			return sprintf("'%s'", $this->escape($v));
			}
		}
	}
?>
