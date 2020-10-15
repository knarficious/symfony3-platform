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

        navigator.geolocation.getCurrentPosition(success, error);

        function success(position) {
            latitude = position.coords.latitude;
            longitude = position.coords.longitude;
            console.log(latitude, longitude);
            
// get address by reverse geocoding

            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState === 4 && this.status === 200) {
                    
                    var myObj = JSON.parse(this.responseText);
                    
                    for (var i = 0; i < myObj.results.length; i++)
                    {
                        var results = myObj.results[i];
                
                        for (var iter = 0; iter < results.locations.length; iter++)
                        {
                            var area = results.locations[iter];
                
                            location.innerHTML = area.adminArea5;
                        }
                    }                    
                };
            };
            
            xmlhttp.open("GET", "https://open.mapquestapi.com/geocoding/v1/reverse?key=4gNCAu0aZMSWHvaUaV56xvD846pUXFix&location=" + latitude + "," + longitude, true );
            xmlhttp.send();
            
// get weather forecast

            $.getJSON(
                    url + apiKey + "/" + latitude + "," + longitude + "?lang=fr&units=auto&callback=?",
                    function (data) {
                        $("#temp").html("Température extérieure: " + data.currently.temperature + "° C");
                        $("#currently").html(data.currently.summary);
                        $("#minutely").html(data.minutely.summary);
                    }
            );
        }

        function error(err) {
            process.innerHTML = "Impossible de récupérer votre position";
            console.warn(`ERROR(${err.code}): ${err.message}`);
        }

        //process.innerHTML = "Locating...";
    }
    
    weather();
    
    function myFunction(x) {
//        const image = document.getElementById("weather-image");
//        const content = document.getElementById("weather-content");
      if (x.matches) { // If media query matches
        $("#weather-image").addClass("col-xs-4");
        $("#weather-content").addClass("col-xs-8");
      }
      else {
        $("#weather-image").removeClass("col-xs-4");
        $("#weather-content").removeClass("col-xs-8");  
      }
    }

    var x = window.matchMedia("(max-width: 800px)")
    myFunction(x) // Call listener function at run time
    x.addListener(myFunction) // Attach listener function on state changes 


});




