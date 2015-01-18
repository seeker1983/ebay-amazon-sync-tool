<?php

class Crypter
{
	public static $default_instance;
	private $key;
	private $method;
	private $iv;

	function __construct($key, $method) 
	{
		$this->key = $key;
		$this->method = $method;
	    $this->iv = md5(md5($key));
	}

	function encrypt($data)
	{
	    return base64_encode(mcrypt_encrypt($this->method, md5($this->key), $data, MCRYPT_MODE_CFB, $this->iv));
	}

	function decrypt($data)
	{
	    return mcrypt_decrypt($this->method, md5($this->key), base64_decode($data), MCRYPT_MODE_CFB, $this->iv);
	}

}

function encrypt($data)
{
	return Crypter::$default_instance->encrypt($data);
}

function decrypt($data)
{
	return Crypter::$default_instance->decrypt($data);
}

Crypter::$default_instance = new Crypter("My Very Strong Random Secret Key", MCRYPT_RIJNDAEL_256);
