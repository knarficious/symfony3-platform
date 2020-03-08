/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require('../css/app.css');

$(document).ready(function(){
    
  function weather() {
  var coordonnees = document.getElementById("coordonnees");
  var location = document.getElementById("location");
  var apiKey = "da6ae541c95e65f571c4274c3588cb5f";
  var url = "https://api.darksky.net/forecast/";

  navigator.geolocation.getCurrentPosition(success, error);

  function success(position) {
    latitude = position.coords.latitude;
    longitude = position.coords.longitude;
    
    var geocode = "https://maps.googleapis.com/maps/api/geocode/json?latlng=" + latitude + "," + longitude + "&key=AIzaSyBXnjaWQ1FinxrqxbwT34v90O0qxm2S9ZI";
    console.log(geocode);
    var geocode_length = geocode.length;
    for (var i = 0; i < geocode_length; i++)
    {
        location.html(geocode[i]["plus_code"]["compound_code"]);
    }
    coordonnees.innerHTML =
      "Latitude is " + latitude + "° Longitude is " + longitude + "°";

    $.getJSON(
      url + apiKey + "/" + latitude + "," + longitude + "?lang=fr&units=auto&callback=?",
      function(data) {
//          var fahrenheit = data.currently.temperature;
//          var celsius;
//          if(fahrenheit !== '')
//          {
//              celsius = (fahrenheit - 32) * 5/9;
//          }
        $("#temp").html("Température extérieure: " + data.currently.temperature + "° C");
        $("#icon").html(data.currently.icon);
        $("#minutely").html(data.minutely.summary);
      }
    );
  }

  function error() {
    coordonnees.innerHTML = "Unable to retrieve your location";
  }

  coordonnees.innerHTML = "Locating...";
}

weather();
});




