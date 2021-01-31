/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require('../css/player.css');
var jsmediatags = window.jsmediatags;

$(document).ready(function () {
    // Playlist array
    var playlist = [
        "https://blog.franckruer.fr/uploads/medias/KajmereSoundDistribution/01.The_Rebirth.Walk_Talkin_Mizell.mp3",
        "https://blog.franckruer.fr/uploads/medias/KajmereSoundDistribution/02.Sharon_Jones.What_If_We_All_Stopped_Paying_Taxes.mp3",
        "https://blog.franckruer.fr/uploads/medias/KajmereSoundDistribution/03.Deloris_Ealy.Deloris_Is_Back_W_Jerome_&_His_Band.mp3",
        "https://blog.franckruer.fr/uploads/medias/KajmereSoundDistribution/04.Chuck_Womack_And_the_Sweet_Souls.Ham_Hocks_And_Beans.mp3",
        "https://blog.franckruer.fr/uploads/medias/KajmereSoundDistribution/05.The_Rebirth.Shake_It.mp3"
    ];
    var pLen = playlist.length;
    
    // traitement des media tags 
        for (i = 0; i < pLen; i++) {
            jsmediatags.read(playlist[i],{
            onSuccess: function (tag) {
                console.log(tag);
                $("#info").text(tag.tags.title);
            },
            onError: function (error) {
                console.log(error);
            }
        });
            
        }

    $('.track').on('click', function () {
        var track_no = parseInt($(this).data('track-index'));
        player(track_no, 'play_track');
        if (track_no > 0)
            $('.prev').data('track-no', track_no - 1);
        if (track_no < playlist.length)
        {
            $('.next').data('track-no', track_no + 1);
        }

    });
    $('.prev').on('click', function (e) {
        e.preventDefault();
        var track_no = parseInt($(this).data('track-no'));
        player(track_no, 'prev');
        if (track_no > 0)
            $(this).data('track-no', track_no - 1);
        if (track_no < playlist.length)
            $('.next').data('track-no', track_no + 1);
    });
    $('.next').on('click', function (e) {
        e.preventDefault();
        var track_no = $(this).data('track-no');
        player(track_no, 'next');
        if (track_no > 0)
            $('.prev').data('track-no', track_no - 1);
        if (track_no < playlist.length - 1)
            $(this).data('track-no', track_no + 1);
    });

    var xA = document.getElementById("xAudio");
    xA.controls = true;

    function player(x, type = '') {

        $('#playlist ul').find('li').children('a').removeClass('label-player active');
        $('#playlist ul').find('li').children('a[data-track-index="' + x + '"]').addClass('label-player active');
        var i = 0;
        xA.src = playlist[x]; // x is the index number of the playlist array 
        xA.load(); // use the load method when changing src
        if (type !== '')
            xA.play();
        xA.onended = function () { // Once the initial file is played it plays the rest of the files
            /* This would be better as a 'for' loop */
            i++;
            if (i > playlist.length) { // ... Repeat
                i = 0;
            }
            if (i > 0)
                $('.prev').data('track-no', i - 1);
            if (i < playlist.length - 1)
                $('.next').data('track-no', i + 1);
            xA.src = playlist[i];
            xA.load();
            xA.play();
        };
    }
    player(0); // Call the player() function at 0 index of playlist array
});


