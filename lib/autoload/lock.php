<?php

class Lock
{
	public function __construct()
	{
	}

	public static function unlock($file)
	{
		unlink($file);
	}

	public static function lock($file, $msg = 'Lock', $age = PHP_INT_MAX)
	{
		if(!file_exists($file) || self::get_age($file) >= $age)
			{
			file_put_contents($file, $msg);
			return true;
			}
		return false;
	}

	public static function get_msg($file)
	{
		if(file_exists($file))
		{
			$age = self::get_age($file);
			return file_get_contents($file) .  " Age: $age s";
		}

		return "Lock for '$file' is available";
	}

	public static function get_age($file)
	{
		if(file_exists($file))
		{
			return time() - filemtime($file);
		}

		return PHP_INT_MAX;
	}
}

