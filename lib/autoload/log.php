<?php

class Log
{
	private static $log_file='./log.txt';

	public function __construct()
	{
	}

	public static function clear()
	{
		unlink(self::$log_file);
	}

	public static function push($msg)
	{
		$fid = fopen(self::$log_file, "a");

		fwrite($fid, '' . date('d.m.y H:i:s') . " $msg \n");

		fclose($fid);
	}
}

