<?
/**
* Mysql Database Wrapper
*/
class DB
{
	private $db;
	private $tables = array();

	function __construct($db_name, $host='localhost', $user = 'root', $password = '')
	{
		$db = mysql_connect($host, $user, $password);

		if(!$db)
			die ("Cannot connect to locahost: " . mysql_error());

		if(! mysql_select_db($db_name))
			die ("Cannot select database '$db_name': " . mysql_error());


		mysql_query("SET NAMES 'utf8'");

/*
		mysql_query ("set character_set_client='cp1251'");
		mysql_query ("set character_set_results='cp1251'");
		mysql_query ("set collation_connection='cp1251_general_ci'");
*/
	}

	public static function safe_query($q)
	{
		$result = mysql_query($q);
		if(! $result)
			die ("Error in query '$q': " . mysql_error());
		return $result;

	}

	public static function query($q)
	{
		return self::safe_query($q);
	}

	public static function query_row($q)
	{
		$result = self::safe_query($q);
		return mysql_fetch_assoc($result);
	}

	public static function query_rows($q)
	{
		$result = self::safe_query($q);
		$rows = array();
		while($row = mysql_fetch_assoc($result))
			$rows[] = $row;
		return $rows;
	}

	public static function query_value($q)
	{
		$result = self::safe_query($q);
		$row = mysql_fetch_row($result);
		if(is_array($row))
			return reset($row);
		return false;

	}

	public static function query_values($q)
	{
		$result = self::safe_query($q);
		$values = array();
		while($row = mysql_fetch_row($result))
			$values[] = reset($row);
		return $values;

	}

	public static function insert($table, $fields, $type="INSERT")
	{
		$query = "$type INTO $table (`".join("`,`", array_keys($fields)) . "`) VALUES ('" .
			join("','", $fields) . "')";
		self::safe_query($query);
	}

	public static function update($table, $fields, $where = '')
	{
		$query = "update `$table` set ";
		foreach ($fields as $key => $value) {
			$query .= "`$key` = '$value',";
		}
		$query = rtrim($query, ",") . $where;
		self::safe_query($query);
	}


	public static function replace($table, $fields)
	{
		self::insert($table, $fields, 'REPLACE');
	}
}


