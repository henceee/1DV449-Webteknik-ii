"using strict"

var Maps =
{
	//---------------------------------------------------
	//  Properties
	//---------------------------------------------------
	

	/**
	 *	Reference to the map.
	 *
	 *	@default null
	 */
	map : null,
	
	/**
	 *	Reference to the information window displayed on 
	 *	the map.
	 *
	 *	@default null
	 */
	infowindow : null,
	
	/**
	 *	Reference to the marker on the map that shows 
	 *	the user's geographical position.
	 *
	 *	@default null
	 */
	markers : null,

	
	/**
	 *	Array to save the info for the infobox
	 */
	info: null,

	/**
	 *	The default position (latitude, longitude), if the 
	 *	browser can not use geolocation to detect the user's 
	 *	geographical position
	 */
	DEFAULT_LOCATION_LAT : 56.6634447,
	DEFAULT_LOCATION_LNG : 16.356779,
	
	//---------------------------------------------------
	//  Methods
	//---------------------------------------------------
	
	/**
	 *	
	 */
	init: function()
	{
		Maps.info 			= [];
		Maps.markers 		= [];		
		
	},
	/**
	 *	Method that creates maps object.
	 *
	 *	@return undefined
	 */
	initMap : function() 
	{	
		map = new google.maps.Map(document.getElementById('map'), {
		center: {lat: -34.397, lng: 150.644},
		zoom: 8
		});
	},
	
	
}

document.onLoad = Maps.init();

