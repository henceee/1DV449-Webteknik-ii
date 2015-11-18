<?php 
require_once('constantsForView.php');

class FormView extends ConstantsForView
{

	public $response='';

	function __construct()
	{
		$this->createResponse();
	}

	/**
	* renders HTML output
	* @return void
	*/
	private function createResponse()
	{
		$this->response = "<form method='POST' action='?check'>
								<label for='".self::$urlInput."'>URL:</label>
								<input type='text' id='".self::$urlInput."' name='".self::$urlInput."' />
								<input type='submit' id='".self::$submit."' name='".self::$submit."' value='Crawl' />
						  </form>";
	}
}