<?php

require_once('model/curl.php');
require_once('model/crawler.php');
require_once('model/compareData.php');
require_once('view/formView.php');
require_once('view/crawlerResultViewSwe.php');
	
class webBookingController
{
	/*
	1. url till startsida
	2. Kolla allas kalendrar (paul,peter,mary) loopa a taggar => calendar => hitta dagar, spara
	3.Välj dag utifrån vilken dag är ok för alla, ex. lörd. välj samtl. film, spara data om tillgängl. platser/tider
	4.Jämför m. tider för zekes 2 h efter film
	(5. boka bord med formulär zeke coys)
*/
	 
	public function handleCrawling()
	{
		$view = new CrawlerResultViewSwe();

		if(isset($_SESSION['url']))
		{
				$url = $_SESSION['url'];
				$curl = new Curl();

				$crawler = new Crawler($curl,$url);

		}	
		//If user wants to book
		if($view->bookParamExists() &&isset($crawler))
		{	
			$group1 = $view->getBookInfo();

			$reservationInfo =$crawler->getReservations($group1);
			$view->outPutReservationResult($reservationInfo);
			//destroy session
			$_SESSION= array();
			session_destroy();
			session_unset();
		}
		//if user wants to check availiblty at the resturant
		else if($view->timeParamExists() && $view->dayParamExists() && $view->movieParamExists() &&isset($crawler))
		{		
				$day = $view->getDay();
				$time = $view->getTime();
				$movie = $view->getMovie();

				$dinnerInfo = $crawler->getDinnerInfo($day,$time);
				
				$view->outPutDinnerAlts($dinnerInfo,$time,$movie);		
			
		}
		//if the user wants to check movies
		else if($view->userHasSubmittedURL())
		{
			//$url ="http://localhost:8080/";	
			$url = $view->getURL();
			$curl = new Curl();
			$crawler = new Crawler($curl,$url);

			$crawler->getLinks();
			$dates = $crawler->getCalendarInfo();

			$comparer = new compareData();
			$matchingDay = $comparer->compareCommonDates($dates);

			if(!is_null($matchingDay))
			{				
				$filmDates = $crawler->getFilmInfo($matchingDay);
				$view->outPutMovieResult($filmDates,$matchingDay);
			
			}
			else
			{
				$view->noMatchingDays();
			}

			$_SESSION['url'] = $url;

		}
		
		else
		{
			$view = new formView();
			
		}
		return $view;
	}
}