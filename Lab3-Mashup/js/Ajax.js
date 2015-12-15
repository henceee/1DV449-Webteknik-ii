var Ajax = (function()
{  
	//-------------------------------------------------------------
	//  Private constants
	//-------------------------------------------------------------
	
	/**
	 *	Constant for undefined XMLHttpRequest objects, meant to be 
	 *	used internally in the class.
	 */
	var UNDEFINED = 'undefined';
	
	/**
	 *	Constant identifying GET-methods.
	 */
	var GET = 'GET';
	
	/**
	 *	Constant identifying POST-methods.
	 */
	var POST = 'POST';
	
	//-------------------------------------------------------------
	//  Constructor method
	//-------------------------------------------------------------
	
	/**
	 *	This private method will work as the class constructor.
	 *
	 *	@return	void
	 */
	function init()
	{
		
	}
	
	//-------------------------------------------------------------
	//  Public methods
	//-------------------------------------------------------------
	
	/**
	 *	Private method that creates a XMLhttpRequest object used 
	 *	for data transmission.
	 *
	 *	@return	Object
	 */
	this.getHTTPObject = (function()
	{
		if (typeof XMLHttpRequest == UNDEFINED){
		
			XMLHttpRequest = function(){
			
				try { return new ActiveXObject("Msxml2.XMLHTTP.6.0"); }
				catch (error) {}
				try { return new ActiveXObject("Msxml2.XMLHTTP.3.0"); }
				catch (error) {}
				try { return new ActiveXObject("Msxml2.XMLHTTP"); }
				catch (error) {}
				
				return false;
			}
		}
		
		return new XMLHttpRequest();
	})
	
	/**
	 *	Method to retrieve data asynchronously using JavaScript (AJAX).
	 *
	 *	@param	URL			The URL to the data source.
	 *	@param	callback	The callback method that is activated when 
	 *						the download is complete.
	 *
	 *	@return undefined
	 */
	this.get = (function(URL, callback){
		
		var request = this.getHTTPObject();
			request.open(GET, URL, true);
			request.onreadystatechange = function(){
				if (request.readyState == 4 && request.status == 200){
					callback(request);
					request = null;
				}
			}
			
			request.send();
	})
	
	/**
	 *	Method to post data asynchronously using JavaScript (AJAX).
	 *
	 *	@param	URL			The URL to the data source.
	 *	@param	parameters	The information that will be sent via the POST-call.
	 *	@param	callback	The callback method that is activated when 
	 *						the transmission is complete.
	 *
	 *	@return undefined
	 */
	this.post = (function(URL, parameters, callback){
			
			var request = this.getHTTPObject();
			request.open(POST, URL, true);
			request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			request.setRequestHeader("Content-length", parameters.length);
			request.setRequestHeader("Connection", "close");
			request.onreadystatechange = function(){
				if (request.readyState == request.DONE){
					callback(request);
					request = null;
				}
			}
			
			request.send(parameters);
	})
	
	/**
	 *	Constructor call
	 */
	init();
});