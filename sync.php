<?
$dir = __DIR__;

$ignores = array(
	'^cache/',
	'^log.txt',
);


function xd($v)
{
    var_dump($v);
    exit;
}

if(preg_match('%^D:\\\\dev\\\\denwer%', $dir))
{
	/* Windows enviroment */
	$source = 'd:/dev/denwer/home/ezonsync.ru/www';	
	$target = 'j:/public_html/ezonsync';
	$id_file = $target . '/' . 'git.id';

	chdir($source);

	if(file_exists($id_file))
		$last_id = file_get_contents($id_file);
	else
		die("Please create file $id_file with current git version on ftp server.");

	$new_id = trim(`git rev-parse HEAD`);

	foreach(explode("\n",`git diff --name-status $last_id`) as $line)
	{
		$file = preg_replace('%^\w\s(.*)%', '\1', $line);
		$ignore = false;
		foreach ($ignores as $pattern) {
			if(preg_match("%$pattern%", $file))
				$ignore = true;
		}
		if(!$ignore && $line)
		{
			$dest = $target . '/' . $file;

			if(is_file($file))
			{
				@mkdir(pathinfo($dest, PATHINFO_DIRNAME), 0777, true);
				if($line[0] == 'M' || $line[0] == 'A')
				{
				if(copy($file, $dest))
					echo("$file -> $dest OK\n");
				else
					echo("$file -> $dest FAIL\n");				
				}

				if($line[0] == 'D' && file_exists($dest))
				{
					if(unlink($dest))
						echo("$dest DEL OK\n");
					else
						echo("$dest DEL FAIL\n");
				}			
			}
			else
			{
				if($line[0] == 'D')
					@rmdir($dest);			
				if($line[0] == 'M' || $line[0] == 'A')
					@mkdir($dest, 0777, true);			
			}
		}
	}
}
