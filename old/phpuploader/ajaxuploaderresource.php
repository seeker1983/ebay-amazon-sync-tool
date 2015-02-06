<?php

//TODO:write the file with cache header. 
//TODO:case sensitive issue

$cd = dirname($_SERVER['SCRIPT_NAME'])."/resources";

$type=$_GET["type"];


$scriptfile=@$_SERVER['SCRIPT_FILENAME'];
if(!$scriptfile)$scriptfile=$_SERVER['ORIG_SCRIPT_FILENAME'];
	

if($type=="emptyhtml")
{
	header("Last-Modified: " . gmdate('D, d M Y H:i:s', time()) . 'GMT');
	header("Expires: " . gmdate('D, d M Y H:i:s', time() + 2592000) . 'GMT');
	header("Content-Type: text/html");
	echo("<html><head><title>EMPTY</title></head><body></body></html>");
	exit(200);
}
else if($type=="script")
{
	header("Content-Type: application/oct-stream");
	
	readfile(dirname($scriptfile)."/resources/uploader.js");
	
	?>
	
	if(!window.CuteWebUI_AjaxUploader_OnPostback)
	window.CuteWebUI_AjaxUploader_OnPostback=function()
	{
		var uploader=this;
		for(var e=uploader;e!=null;e=e.parentNode)
		{
			if(e.nodeName=="FORM")
			{
				e.submit();
				return;
			}
		}
	}
	
	<?php
	
	
}
else if($type=="license")
{
	header("Content-Type: application/oct-stream"); 
	$licensefile=dirname($scriptfile)."/license/phpuploader.lic";
	$size=filesize($licensefile);
	$mqr=get_magic_quotes_runtime();
	//set_magic_quotes_runtime(0);
	$handle=fopen($licensefile,"rb");
	$data=fread($handle,$size);
	fclose($handle);
	//set_magic_quotes_runtime($mqr);
	echo(bin2hex($data));
}
else if($type=="serverip")
{
	$ip=@$_SERVER['SER'.'VER_AD'.'DR'];
    if($ip==null)$ip=@$_SERVER['LOC'.'AL_AD'.'DR'];
    header("Content-Type: text/plain"); 
    echo($ip);
}
else
{
	$file=$_GET["file"];
	$lower=strtolower($file);
	if($lower=="silverlight.xap"||$lower=="uploader.swf"||$lower=="uploader10.swf")
	{
		//the server may do not understand the xap file
		//show just render it to client directly.
		$filepath=dirname($scriptfile)."/resources/$lower";
		header("Content-Type: application/oct-stream");
		header("Content-Length: " . filesize($filepath) );
		readfile($filepath);
	}
	else if($lower=="continuous.gif"||$lower=="blocks.gif")
	{
		header("Last-Modified: " . gmdate('D, d M Y H:i:s', time()) . 'GMT');
		header("Expires: " . gmdate('D, d M Y H:i:s', time() + 2592000) . 'GMT');
		header("Cache-Control: public");
		header("Content-Type: image/gif");
		readfile(dirname($scriptfile)."/resources/$file");
	}
	else
	{
		header("Cache-Control: public");
		header("Content-Type: application/oct-stream"); 
		header("Location: $cd/$file");
	}
}

exit(200);

?>