<?php

class Log
{
	public function __construct()
	{
	}

	public static function get_default_log_file()
	{
		$user_id = isset($GLOBALS['user']['user_id']) ? $GLOBALS['user']['user_id'] : "system";

		$file = 'log/' . $user_id . '/' . 'log.txt';

		return $file;
	}

	public static function clear($file = null)
	{
		if(is_null($file))
			$file = self::get_default_log_file();
		
		@unlink($file);
	}

	public static function tail($lines = 0)
	{
		return self::tail_custom('log.txt', $lines);
	}

	public static function tail_custom($file, $lines = 0)
	{
		$user_id = isset($GLOBALS['user']['user_id']) ? $GLOBALS['user']['user_id'] : "system";

		$file = 'log/' . $user_id . '/' . $file;

		$data = file($file);
		
		if($lines)
			$data = array_slice($data, -$lines);

		return $data;
	}

	public static function push($msg)
	{
		self::custom('log.txt', $msg);
	}

	public static function custom($file, $msg)
	{
		$user_id = isset($GLOBALS['user']['user_id']) ? $GLOBALS['user']['user_id'] : "system";

		$file = 'log/' . $user_id . '/' . $file;

		if(!file_exists($file))
		{
			@mkdir(pathinfo($file, PATHINFO_DIRNAME), 0777, true);
		}

		$fid = fopen($file, "a");

		fwrite($fid, '' . date('d.m.y H:i:s') . " $msg \n");

		fclose($fid);
	}
}

