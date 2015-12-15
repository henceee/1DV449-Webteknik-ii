"using strict"
var Main =
{
	/**
	 *	Reference to the map.
	 *	@default null
	 */
	map : null,	
	
	/**
	 *	Array to save the info for the infobox
	 */
	info: null,
	
	/**
	 *	The default position (latitude, longitude), if the 
	 *	browser can not use geolocation to detect the user's 
	 *	geographical position
	 *  Currently set to Stockholm, since its basically mid-sweden
	 */
	DEFAULT_LOCATION_LAT : 59.3294,
	DEFAULT_LOCATION_LNG : 18.0686,

	/**
	 *	Maximum number of markers allowed
	 *	@default null
	 */
	 markersCount: null,

	/**
	 *	Reference to the marker on the map that shows 
	 *	the user's geographical position.
	 *
	 *	@default null
	 */
	markers : null,

	/**
	 *	Reference to the marker on the map that shows 
	 *	the user's geographical position.
	 *
	 *	@default null
	 */
	toggledInfo : null,	


	/**
	 *	Cache name
	 */
	cacheName: 'trafficInfo',


	/**
	 *	Miliseconds to save in cache
	 *	@default {number}
	 *  (10 min = 10*60*1000= 600000)
	 */
	cacheTime: 600000,


	/**
	 *	Method that creates maps object.
	 *	@return undefined
	 */
	initMap:function()
	{
		Main.resetVariables();

		var map = document.getElementById('map'); 
		var options 		= new Object();
		options.zoom 		= 5;
		options.mapTypeId	= google.maps.MapTypeId.ROADMAP;

	 	Main.map = new google.maps.Map(map, options);

		Main.initLocation();			 

		var submit = document.getElementById('trafficSubmit');
		submit.onclick = Main.toggleInfoToShow;
	
	},

	/**
	 *	reset all the vars
	 *	@return void
	 */
	resetVariables: function()
	{	
		Main.info 			= [];
		Main.markers 		= [];
		Main.toggledInfo	= [];	
		Main.markersCount 	= 0;
		var div = document.getElementById("list").innerHTML="";
	},

	/**
	 *	clears markers, resets vars, saves
	 *	id of toggled checkbox to arr to be used later on.
	 *	@return void
	 */
	toggleInfoToShow: function()
	{	
		Main.clearMarkers();
		Main.resetVariables();
		
		var checkboxes = document.getElementsByClassName('toggle');

		for(var i =0; i <checkboxes.length; i++)
		{
			if(checkboxes[i].checked == true)
			{
				Main.toggledInfo.push(checkboxes[i].id);
			}			
		}

		Main.getTrafficInfo();	
	},

	/**
	 *	clears all markers by setting map to null
	 *	@return void
	 */
	clearMarkers: function()
	{
		for (var i = 0; i < Main.markers.length; i++) {
		Main.markers[i].setMap(null);
		}
	},


	/**
	 *	Method that finds the user's geographical location with 
	 *	HTML5 technology.
	 *	@return undefined
	 */
	initLocation: function()
	{
		//If geolocation API is availible in BOM
		//https://developer.mozilla.org/en-US/docs/Web/API/Geolocation/Using_geolocation
		if (navigator.geolocation) {
			//https://developer.mozilla.org/en-US/docs/Web/API/Geolocation/getCurrentPosition
			navigator.geolocation.getCurrentPosition(Main.onLocationFound, Main.onUnknownLocation);
			return;

		}
		//..otherwise fallback to error callback
		return Main.onLocationUnkown(); 
	},

	/**
	 *	Fallback if geolocation fails, using default coordinates to create new 
	 *	google maps LatLng obj.
	 *	@return undefined
	 */
	onLocationUnkown: function()
	{
		var location = new google.maps.LatLng(Main.DEFAULT_LOCATION_LAT,Main.DEFAULT_LOCATION_LNG);

		Main.map.setCenter(location);
	},

	/**
	 *	Method which is activated when the user's geographical location 
	 *	can not be found with HTML5.
	 *	@param	position	An position-objects from the google maps API.
	 *	@return undefined
	 */
	onLocationFound : function(position) 
	{	
		var lat =position.coords.latitude;
		var lng = position.coords.longitude;
		var location = new google.maps.LatLng(lat, lng);
		Main.map.setCenter(location);
	},

	/**
	*	Test if localStorage is supported, as in the example on MDN:
	*	https://developer.mozilla.org/en-US/docs/Web/API/Web_Storage_API/Using_the_Web_Storage_API
	*	@return bool
	*/
	LocalStorageSupported: function()
	{
		try {
			var storage = window['localStorage'],
				x = '__storage_test__';
			storage.setItem(x, x);
			storage.removeItem(x);
			return true;
		}
		catch(e) {
			return false;
		}
	},
	/*
	*	Method which checks if the data in storage is not to old
	*	and if so, use the cached data on client side. Otherwise
	*	make call to server.
	*	@return undefined
	*/
	getTrafficInfo: function()
	{	
		
		if (Main.LocalStorageSupported())
		{
			var dataInStorage = JSON.parse(localStorage.getItem(Main.cacheName));

			if(dataInStorage !==null && new Date().getTime() < dataInStorage.timestamp)
			{
				//if the data in storage is not to old, use it.
				
				Main.handleTrafficInfo(JSON.parse(dataInStorage.value));	
			}
			else
			{
				Main.getDataFromServer();	
			}
			
		}
		else
		{
			Main.getDataFromServer();
		}
		
	},

	/*
	*	Method which makes an ajax call to trafficInfo.php, to obtain
	*	the information about the blogposts from the database.
	*	Server works as proxy, caches data and only makes call to
	*	if that cache has expired.
	*	@return undefined
	*/
	getDataFromServer: function()
	{
		var ajax = new Ajax();
		ajax.get("trafficInfo.php", Main.AjaxCallback);	
	},

	AjaxCallback:function(responseData)
	{
		var response = JSON.parse(responseData.response).messages;
		//if localstorage is supported, chache trafficinfo
		if (Main.LocalStorageSupported())
		{
			var cacheData = {value: JSON.stringify(response), timestamp: new Date().getTime() + Main.cacheTime}
			localStorage.setItem(Main.cacheName, JSON.stringify(cacheData));
			
		}

		Main.handleTrafficInfo(response);
	},	
	/*
	*	Callback method for the ajax-call to trafficInfo.php,
	*	Loops through json object, push save it to be handled later
	*	@return undefined.
	*/
	handleTrafficInfo: function(response)
	{	
		/*	Iterate through response, check the category
		*	if cat. is toggled, it should be shown
		*/
		for(var i =0; i < response.length; i++)
		{
			for(var j =0; j<Main.toggledInfo.length; j++)
			{	
				
				if(Main.toggledInfo[j] == response[i].category)
				{
					//save the response and toggle marker.
					Main.info.push(response[i]);
					
					var lat 	= response[i].latitude;;
					var lng 	= response[i].longitude;
					var position = new google.maps.LatLng(lat, lng);
					Main.initMarker(position, i);	
				}		
				
			}
		
		}
	},

	/**
	 *	Method to create the marker on a map.
	 *	@param	position	An position-objects from the google maps API.
	 *	@return undefined
	 */
	initMarker : function(pos,id) 
	{
	  var color = Main.getMarkerColor(id);
	  var marker = new google.maps.Marker({
	    position: pos,
	    map: Main.map,
	    title:  Main.info[id].title,	
	    id: id,
	    icon: {
	      path: google.maps.SymbolPath.CIRCLE,
	      fillColor: color,
	      fillOpacity: 1.0,
	      scale: 8
	    }
	  });
	 
	  //add listner listening to click on the marker
	  marker.addListener('click', Main.toggleInfoWindow);
	  Main.markers.push(marker);	
	  var a = Main.addLink(id);

	  //Trigger marker click event when corresponding link is clicked
	  // opening infowindow.
	  a.onclick = function()
	  {
	  	new google.maps.event.trigger(marker, 'click' );
	  }

		
	},

	getMarkerColor: function(id)
	{	 
	 //priority - Meddelandets prioritet
	 //1 = Mycket allvarlig händelse, 2 = Stor händelse, 3 = Störning, 4 = Information, 5 = Mindre störning
	 switch(Main.info[id].priority)
	 {
	 	case 1:
	 	return "#FF0000";
	 	case 2:
	 	return "#FF9900";
	 	case 3:
	 	return "#FFFF00";
	 	case 4:
	 	return "#00CC00";
	 	case 5:
	 	return "#00FF00";

	 }
	},

	addLink: function(id)
	{
			
		//(0 = Vägtrafik, 1 = Kollektivtrafik, 2 = Planerad störning, 3 = Övrigt)
		//traffic 				collective			planned				other
		var div = document.getElementById("list");
		var a = document.createElement("a");
		a.href="#";
		var text = document.createTextNode(Main.info[id].title);
		a.appendChild(text);
		var li = document.createElement("li");
		li.appendChild(a);
		div.appendChild(li);
		
		return a;
	},

	/**
	 *	Event handler to toggle the information window.
	 *	@return undefined
	 */
	toggleInfoWindow : function(event) 
	{
		var createDate = Main.info[this.id].createddate;
		var date = Main.handleDateString(createDate);

		var contentString = '<div id="content">'+
			 '<h1>'+this.title+'</h1>'+
			 '<p>'+date+'<p>'+
			 '<p>'+Main.info[this.id].subcategory+'</p>'+
			 '<p>'+Main.info[this.id].exactlocation+'</p>'+
			  '<p>'+Main.info[this.id].description+'</p>'+
     
      '</div>';
      //if a info Window isn't open already, open one and add to the counter
      if(Main.markersCount <1)
      {
	     	 var infoWindow = new google.maps.InfoWindow({
		    content: contentString
		 	}); 	

	     	Main.markersCount ++;

      }	 
      //if the user clicks the close button, close it and count down the counter
	  infoWindow.addListener('closeclick', function(){ Main.markersCount--});
    	infoWindow.open(Main.map,this);
  				
	},

	/**
	 *	Handles the string representing a date
	 *	for the traffic info window.
	 *	@return string
	 */
	handleDateString: function(createDate)
	{
		var date = new Date(parseInt(createDate.substr(6,13)));
		var day = date.getDay();

			switch (day)
			{
					case 1:
						dateString = "Måndagen";
						break;
					case 2:
						dateString = "Tisdagen";
						break;
					case 3:
						dateString = "Onsdagen";
						break;
					case 4:
						dateString = "Torsdagen";
						break;
					case 5:
						dateString = "Fredagen";
						break;
					case 6:
						dateString = "Lördagen";
						break;
					case 7:
						dateString = "Söndagen";
						break;
			}
			
			return dateString + " den "+ date.getDate() +"/"+date.getMonth()+" "+date.getFullYear();				
	}
}

document.onload = Main.initMap;