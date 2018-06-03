<?php

class Singleton
{
	private static $single;
	protected $say = 'Hello World!';

	public function getSay()
	{
		echo $this->say;
	}
	public function getSingle()
	{
		if(self::$single === null)
		{
			$class = __CLASS__;
			self::$single = new Singleton;
			return self::$single;
		}
		else{
			return self::$single;
		}
	}
	private function __construct(){}
	private function __clone(){}
	private function __wakeup(){}
}

Singleton::getSingle()->getSay();
