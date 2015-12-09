<?php


class Day
{
	private $name;

	function __construct($name)
	{
		$this->name = $name;
	}

	/**
	* Get name of the day
	* @return string
	*/
	public function getName()
	{
		return $this->name;
	}
}