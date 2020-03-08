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

            $.getJSON(
                    "https://maps.googleapis.com/maps/api/geocode/json?latlng=" + latitude + "," + longitude + "&key=AIzaSyBXnjaWQ1FinxrqxbwT34v90O0qxm2S9ZI",
                    function (data) {
                        location.innerHTML = data.plus_code.compound_code;
                    }
            );

            $.getJSON(
                    url + apiKey + "/" + latitude + "," + longitude + "?lang=fr&units=auto&callback=?",
                    function (data) {
                        $("#temp").html("Température extérieure: " + data.currently.temperature + "° C");
                        $("#icon").html(data.currently.icon);
                        $("#minutely").html(data.minutely.summary);
                    }
            );
        }

        function error(err) {
            process.innerHTML = "Unable to retrieve your location";
            console.warn(`ERROR(${err.code}): ${err.message}`);
        }

        //process.innerHTML = "Locating...";
    }

    weather();
});




