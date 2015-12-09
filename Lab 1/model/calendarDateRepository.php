<?php

class CalendarDateRepository
{
	private $dates;

	function __construct()
	{
		$this->dates = array();
	}

	/**
	* add a date to the repo
	* @param date
	* @return void
	*/
	public function add($date)
	{
		$this->dates[] = $date;	
	}

	/**
	* Get the repo
	* @return array dates
	*/
	public function getDates()
	{
		return $this->dates;
	}
}
