<?php

class availableTimeRepository
{
	private $times;

	function __construct()
	{
		$this->times = array();
	}

	/**
	* add a time to the repo
	* @param time
	* @return void
	*/
	public function add($time)
	{
		$this->times[] = $time;
	}

	/**
	* Get the repo
	* @return array times
	*/
	public function getTimes()
	{
		return $this->times;
	}
}