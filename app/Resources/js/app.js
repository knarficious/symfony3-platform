/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require('../css/app.css');

$(document).ready(function(){
    
  function weather() {
  var location = document.getElementById("location");
  var apiKey = "da6ae541c95e65f571c4274c3588cb5f";
  var url = "https://api.darksky.net/forecast/";

  navigator.geolocation.getCurrentPosition(success, error);

  function success(position) {
    latitude = position.coords.latitude;
    longitude = position.coords.longitude;
    
    var position = "https://maps.googleapis.com/maps/api/geocode/json?latlng=latitude,longitude&key=AIzaSyA2K-avkDmpftUPLab9r9oqBca8vjM1-i4";
    console.log(position);
    location.innerHTML =
      "Latitude is " + latitude + "° Longitude is " + longitude + "°";

    $.getJSON(
      url + apiKey + "/" + latitude + "," + longitude + "?units=se?lang=fr?callback=?",
      function(data) {
          var fahrenheit = data.currently.temperature;
//          var celsius;
//          if(fahrenheit !== '')
//          {
//              celsius = (fahrenheit - 32) * 5/9;
//          }
        $("#temp").html("Température extérieure: " + fahrenheit + "° C");
        $("#minutely").html(data.minutely.summary);
      }
    );
  }

  function error() {
    location.innerHTML = "Unable to retrieve your location";
  }

  location.innerHTML = "Locating...";
}

weather();
});




