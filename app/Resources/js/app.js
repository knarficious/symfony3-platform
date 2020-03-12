/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require('../css/app.css');

$(document).ready(function () {

    function weather() {
        var process = document.getElementById("process");
        var location = document.getElementById("location");
        var apiKey = "da6ae541c95e65f571c4274c3588cb5f";
        var url = "https://api.darksky.net/forecast/";
        var latitude;
        var longitude;
        if (navigator.connection.type === 'ethernet')
        {
     
            $.getJSON("https://www.googleapis.com/geolocation/v1/geolocate?key=AIzaSyBXnjaWQ1FinxrqxbwT34v90O0qxm2S9ZI",
                function(data)
                {
                   latitude = data.location.lat;
                   longitude = data.location.lng;
                });
        }
        else
        {
            navigator.geolocation.getCurrentPosition(success, error);
            
            function success(position) {
            latitude = position.coords.latitude;
            longitude = position.coords.longitude;
            }
        }
        
        function error(err) {
            process.innerHTML = "Impossible de récupérer votre position";
            console.warn(`ERROR(${err.code}): ${err.message}`);
        }
        
        $.getJSON(
                "https://maps.googleapis.com/maps/api/geocode/json?latlng=" + latitude + "," + longitude + "&key=AIzaSyBXnjaWQ1FinxrqxbwT34v90O0qxm2S9ZI",
                function (data)
                {
                    location.innerHTML = data.plus_code.compound_code.substring(data.plus_code.compound_code.indexOf(" "));
                }
        );

        $.getJSON(
                url + apiKey + "/" + latitude + "," + longitude + "?lang=fr&units=auto&callback=?",
                function (data) {
                    $("#temp").html("Température extérieure: " + data.currently.temperature + "° C");
                    $("#currently").html(data.currently.summary);
                    $("#minutely").html(data.minutely.summary);
                }
        );

        



        //process.innerHTML = "Locating...";
    }

    weather();
    
    deviceInfo: function () {
		var isMobile = false;
		var os = '';
		var device = '';
		var ua = navigator.userAgent;
		var p = navigator.platform;
		var matched;
		
		if ( /bot|spider/i.test( ua ) ) {
			os = 'spider';
		} else {
			if ( /iPhone|iPod|iPad/.test( p ) ) { 
				os = 'iOS';
				device = p;
				isMobile = true; 
			} else {
				var matched = /Android|BlackBerry|IEMobile|Opera Mini|Mobi|Tablet/.exec( ua );
				if ( matched ) {
					device = ( matched[0] === 'Mobi' ) ? 'Mobile' : matched[0];
					isMobile = true;
				}
			}
			if ( ! isMobile ) {
				if ( /Mac/.test( p ) ) {
					os = 'Mac';
					device = 'Desk/Laptop';
				} else if ( /Linux/.test( p ) ) {
					os = 'Linux';
					device = 'Desk/Laptop';
				} else if ( /Win|Pocket PC/.test( p ) ) {
					os = 'Windows';
					device = 'Desk/Laptop';
				}
			}
		}
		return { os:os, device:device, isMobile:isMobile }; 
	}
});




