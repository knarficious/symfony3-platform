/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require('../css/player.css');

$(document).ready(function() {

var audio;
var playlist;
var tracks;
var current;

init();
function init() {
    current = 0;
    audio = $('#audio');
    playlist = $('#playlist');
    tracks = playlist.find('li a');
    len = tracks.length - 1;
    audio[0].volume = .10;
    audio[0].play();
    playlist.on('click','a', function (e) {
        e.preventDefault();
        link = $(this);
        current = link.parent().index();
        run(link, audio[0]);
    })
    audio[0].addEventListener('ended', function() {
        current++;
        if (current > len) {
            current = 0;
            link = playlist.on('a')[0];
        } else {
            link = playlist.on('a')[current];
        }
        run($(link), audio[0]);
    });
    $('#previous').on('click', function () {
            current--
            if (current === -1) {
                current = len - 1;
            }
            link = playlist.find('a')[current];
            run($(link), audio[0]);
        });
        $('#next').on('click', function () {
            current++
            if (current === len) {
                current = 0
            }
            link = playlist.find('a')[current];
            run($(link), audio[0]);
        })
}
function run(link, player) {
    player.src = link.attr('href');
    par = link.parent();
    par.addClass('label active').siblings().removeClass('active');
    audio[0].load();
    audio[0].play();
    }
});


