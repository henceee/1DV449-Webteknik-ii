<?php

class AvailableTime
{
	private $name;
	private $times;

	function __construct($name)
	{
		$this->name = $name;
		$this->times = array();
	}

	/**
	* Get the name associated to time,
	* like movie name
	* @return string name
	*/
	public function getName()
	{
		return $this->name;
	}

	/**
	* Get the repo of times
	* @return array times
	*/
	public function getTimes()
	{
		return $this->times;
	}

	/**
	* add time 
	* @return void
	*/
	public function addTime($time)
	{
		$this->times[] = $time;
	}
}