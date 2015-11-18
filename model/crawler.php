<?php

require_once('calendarDateRepository.php');
require_once('availableTime.php');
require_once('availableTimeRepository.php');
require_once('day.php');

class Crawler
{
	private $curl;
	private $baseURL;

	private static $username = "zeke";
	private static $password = "coys";

	function __construct(Curl $curl, $url)
	{
		$this->curl = $curl;
		$this->baseURL = $url;
		$this->getLinks();
	}

	/**
	* Get links to sub sections of the sites
	* such as calendar, 
	* @return void
	*/
	public function getLinks()
	{
		$data = $this->curl->curlGetReq($this->baseURL);
		$query = "//a/@href";
		$links = $this->curl->getDOMData($data,$query);
		

		//var_dump($links[0]);

		foreach ($links as $link)
		{
			
			if($link->value == "/calendar")
			{
				$this->calendarLink = $this->baseURL. str_replace("/", "", $link->value)."/";
				
			}
			if($link->value == "/cinema")
			{
				$this->cinemaLink = $this->baseURL. str_replace("/", "", $link->value)."/";
			}
			if($link->value == "/dinner")
			{
				$this->dinnerLink = $this->baseURL. str_replace("/", "", $link->value)."/";
				$this->dinnerLoginLink = $this->dinnerLink."login";
			}

		
		
		}
	}
	/**
	* Get info from each persons calendar
	* @return CalendarDates $calendarDates
	*/
	public function getCalendarInfo()
	{
		
		//get URL to calendar page
		$url = $this->calendarLink;
		//Get the sourcecode			
		$data = $this->curl->curlGetReq($url);
		//find all links to each person
		$query = "//a";
		$aTagNodes = $this->curl-> getDOMData($data,$query);
		
		$calendarDates = new CalendarDateRepository();
		//loop through each link, representing a person
		foreach ($aTagNodes as $at)
		{
			//get the href to that persons calendar
			$calURL =$at->getAttribute("href");
			//get the sourcecode of that page
			$data = $this->curl->curlGetReq($url.$calURL);
			//get the table header containing name of the days
			$query = "//th";
			$days = $this->curl->getDOMData($data,$query);
			//get the table data containing availability
			$query = "//td";
			$availibility = $this->curl->getDOMData($data,$query);			

			$dates = array();
			/*
			* loop table data, if the availability of that day is ok
			* save that data.
			*/
			for ($i=0; $i < $days->length ; $i++)
			{ 
				$availibilityStr =$availibility[$i]->nodeValue;
	
				if(strtolower($availibilityStr) === "ok")
				{	
					//$calendarDates->add($days[$i]->nodeValue);
					$dates[] = new Day($days[$i]->nodeValue);
				}				
			}
			$calendarDates->add($dates);
			
		 }
		
		return $calendarDates; 
	}

	/**
	* Get info about the films
	* with available seats of spec. day
	* @param Day day
	* @return 
	*/
	public function getFilmInfo($day)
	{

		switch ($day->getName())
		{
			case 'Friday':
				$day ="01";
				break;
			case 'Saturday':
				$day ="02";
				break;
			case 'Sunday':
				$day ="03";
				break;
			default:
				//TODO : Do something.
				break;
		}


		$url =$this->cinemaLink;
		$data = $this->curl->curlGetReq($url);
		//Get all selects of movie exc. disabled w text instruction.
		$query = "//select[@id ='movie']/option[not(text() = '--- Välj film ---')]";
		$select = $this->curl->getDOMData($data,$query);	
		
		//

		//Get the number of movies
		$numberOfMovies = $select->length;
		
		//add date query to the URL
		$url .="check?day=".$day;
		/* Iterate through all movies, add them to url, get
		*  and extract times 
		*/
		$movieTimeRepository = new availableTimeRepository();

		for($i =1; $i < $numberOfMovies+1; $i++)
		{
			//get the name of the movie
			$movieName = $select[$i-1]->nodeValue;
			//create a new movieTime object
			$movieTime = new availableTime($movieName);
			//get the times and availability for each movie
			//example of URL with movieQuery: http://localhost:8080/cinema/check?day=02&movie=01
			$movieQuery= ($i <10)? "&movie=0".$i: "&movie=".$i;
			$data = $this->curl->curlGetReq($url.$movieQuery);
			//decode it and iterate the data
			$movieData =json_decode($data,true);
			
			foreach ($movieData as $movieInfo)
			{	
				//if the status ==1 ==true,it's available
				if($movieInfo['status'])
				{	//save the available time to corresponding movie obj.
					$movieTime->addTime($movieInfo['time']);

				}
			}
			
			$movieTimeRepository->add($movieTime);


		}		
	
		return $movieTimeRepository;
		
	}

	/**
	* Get info about if there are 
	* available seats of spec. day
	* and time at the resturant
	* @param Day day
	* @return 
	*/
	public function getDinnerInfo($day,$time)
	{		
		switch ($day)
		{
			case 'fredag':
				$section =2;
				break;
			case 'lordag':
				$section = 4;
				break;
			case 'sondag':
				$section = 6;
				break;
			default:
				//TODO : Do something.
				break;
		}
		//The movie lasts no longer than 2 hrs
		$timeAfterMovie = $time +2;
		//The seats are in an interval of 2 hrs
		$timeAfterSupper = $timeAfterMovie +2;
		//Get the html nodes from the site
		$url = $this->dinnerLink;
		$data = $this->curl->curlGetReq($url);

		//Get the section with the times for the matching day
		$query = "//div[@class='WordSection".$section."']/p[@class='MsoNormal']";
		$availability = $this->curl->getDOMData($data,$query);

		$group1 = null;

		foreach ($availability as $available)
		{	

			if($available->nodeValue === $timeAfterMovie."-".$timeAfterSupper." Ledigt")
			{				
				$partialDay = substr($day, 0,3);
				//example of group1 string: lor2022
				$group1 = $partialDay.$timeAfterMovie.$timeAfterSupper;
				
				return $group1;
				
			}			

			
		}

		return $group1;
	
	}

	/**
	* Make post to the resturants
	* site by creating an array
	* faking input.
	* @param string group1 - day and time to book
	* @return 
	*/
	public function getReservations($group1)
	{
		$url = $this->dinnerLoginLink;
	

		$postArr = array(
					"group1"=>$group1,
					"username"=>self::$username,
					"password"=>self::$password,
					"submit"=> "login"
					);
				/*
					group1:lor2022
					username:zeke
					password:coys
					submit:login
				*/
		return $this->curl->doPost($url,$postArr);
	}
}