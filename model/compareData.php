<?php

class compareData
{
	/**
	* Establish if there is a day
	* witch matches for all people
	* @param caldates
	* @return Day 
	*/
	public function compareCommonDates(CalendarDateRepository $calDates)
	{
		$dates =$calDates->getDates();

		//get the number of peope
		$numberOfPeople = count($dates);
		$dateArr = array();
		$dayNameArr = array();
		//flatten the array...
		foreach($dates as $date)
		{
			foreach($date as $value) {
			$dateArr[] = $value;
			$dayNameArr[] = $value->getName();
			}
		}
		
		//check if there is a value with the same amount of matches as people
		$countArr = array_count_values($dayNameArr);
	
		if(in_array($numberOfPeople,$countArr))
		{
			//get the value of the index
			$day = array_search($numberOfPeople, $countArr);

			foreach ($dateArr as $date) {
				if($date->getName() == $day)
				{
					//TODO how handle multiple days matching? new calendarDateRepo?
					return $date;
				}
			}
			
		}
		return null;
	}


}