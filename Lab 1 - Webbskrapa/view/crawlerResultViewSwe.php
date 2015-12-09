<?php
require_once('constantsForView.php');


class CrawlerResultViewSwe extends ConstantsForView
{

	public $response='';

  	/**
	* Check if booking param exists
	* @return bool
	*/
	public function bookParamExists()
	{
		return isset($_GET['book']);
	}

	/**
	* Get booking parameter from URL 
	* @return string
	*/
	public function getBookInfo()
	{
		if($this->bookParamExists())
		{
			return $_GET['book'];
		}
	}
	
	/**
	* Check if movie parameter from URL is set
	* @return bool
	*/
	public function movieParamExists()
	{
		return isset($_GET['movie']);
	}
	public function getMovie()
	{
		if($this->movieParamExists())
		{
			return $_GET['movie'];
		}
	}

	/**
	* Check if user has submitted URL
	* @return bool
	*/
	public function userHasSubmittedURL()
	{
		return isset($_POST[self::$submit]);
	}

	/**
	* Get URL from input
	* @return string
	*/
	public function getURL()
	{
		if($this->userHasSubmittedURL())
		{
			return $_POST[self::$urlInput];
		}
	}

	/**
	* Check if time parameter from URL is set
	* @return bool
	*/
	public function timeParamExists()
	{
		return isset($_GET['time']);
	}

	/**
	* Get time from URL parameters
	* @return string
	*/
	public function getTime()
	{
		if($this->timeParamExists())
		{
			return $_GET['time'];
		}		
	}

	/**
	* Check if day parameter from URL is set
	* @return bool
	*/
	public function dayParamExists()
	{
		return isset($_GET['day']);
	}

	/**
	* Get day from URL parameters
	* @return string
	*/
	public function getDay()
	{
		if($this->dayParamExists())
		{
			return $_GET['day'];
		}
	}

	/**
	* add result to HTML output to be rendered
	* @param result
	* @return void
	*/
	public function outPutReservationResult($result)
	{
			$this->response = $result;
	}

	/**
	* add result to HTML output to be rendered
	* @param string dinnnerinfo
	* @param string time
	* @param string movie
	* @return void
	*/
	public function outPutDinnerAlts($dinnerInfo,$time,$movie)
	{
			if(empty($dinnerInfo) || is_null($dinnerInfo) )
			{
				$this->response ="Det fanns inga lediga tider :(";
			}
			else
			{				
				$dinnerTimes = substr($dinnerInfo, 3);
				$dinnerStart = substr($dinnerTimes, 0,2);
				$dinnerEnd =   substr($dinnerTimes, 2);				
				$timeRelToMovie= $time > $dinnerStart ? "före":"efter";
				$this->response="Det finns ett bord mellan ".$dinnerStart." och ".$dinnerEnd.
								" efter att ha sett ".$movie.".
								<a href='?book=".$dinnerInfo."''>Boka detta bord</a>";
			}
			
		
	}

	/**
	* add result to HTML output to be rendered
	* @return void
	*/
	public function noMatchingDays()
	{
		$this->response="Tyvärr.Inga matchanade dagar hittades.";
	}

	/**
	* Translate day to swedish, if for URL withoug åäö
	* @param Day day
	* @param bool forURL
	* @return void
	*/
	public function translateNameOfDay($day,$forURL=false)
	{
		switch ($day->getName()) {
			case 'friday':			
			case 'Friday':
				return 'fredag';

			case 'saturday':			
			case 'Saturday':
				return $forURL ? 'lordag' : 'lördag';
				
			case 'sunday':			
			case 'Sunday':
				return 'sondag';

			default:
				return '';
				break;
		}
	}

	/**
	* add result to HTML output to be rendered
	* @param movie
	* @param day
	* @return void
	*/
	public function outPutMovieResult(AvailableTimeRepository $movieRepo, Day $day)
	{
		$this->response="<h3>Följande Filmer Hittades</h3>";
		$dayName =$this->translateNameOfDay($day);
		foreach ($movieRepo->getTimes() as $timeInfo) {
			
			$movieName = $timeInfo->getName();
			 foreach ($timeInfo->getTimes() as $time)
			 {
			 	$this->response.= "Filmen <b>".$movieName."</b> klockan ".$time." på ".$dayName.".";

			 	$timeStr= urlencode(substr($time, 0,2));
				$urlDayName = $this->translateNameOfDay($day,true);
				
								
			 	$this->response .="<a href='?check&day=".$urlDayName."&time=".$timeStr."&movie=".urlencode($movieName)."'>
			 						Välj och boka bord</a><br>";
			 }
		}
	}
}