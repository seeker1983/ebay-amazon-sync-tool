<?php

class Cache
{
	public static function get($id, $callback, $timeout = 300, $options = array())
	{
		if(isset($_GET['refresh']))
			$timeout = -1;

		$file = "cache/$id.txt";

		if(file_exists($file) && time() - filemtime($file) < $timeout )
			return unserialize(file_get_contents($file));

		$data = call_user_func($callback, $options);		

		@mkdir(pathinfo($file, PATHINFO_DIRNAME), 0777, true);

		file_put_contents($file, serialize($data));

		return $data;
	}
}

