/*

    INFINITE :) PROGRESS BAR
    BY MAREK STREICH

    HOW TO USE IT?



    STEP 1) INIT

    ProgressBar.init({
        element: yourElement
        // YOU CAN ADD HERE YOUR CUSTOM SETTINGS
        // FOR PROGRESS SPEED, DISPLAY OPTIONS
        // AND EVEN EASING FUNCTION
    });



    STEP 2) START WHEN NEEDED

    ProgressBar.start();



    STEP 3) FINISH WHEN DONE

    ProgressBar.end();

*/

window.ProgressBar = (function (me) {

    // SETTINGS

    var ratio = 4;
    var speed = 5;
    var frequency = 1;
    var ease = 'ease';

    // PRIVATE VARIABLES

    var timeout = null;
    var element = null;
    var overlay = null;
    var current = 0;

    var random = function (min, max) {

        return Math.floor(Math.random() * (max - min + 1) + min);

    };

    var time = function () {

        return random(1, 6) / speed;

    };

    var delay = function () {

        return random(1, 2) / speed;

    };

    var frequency = function () {

        return random(0, frequency);

    };

    var width = function () {

        var min = current;

        var remain = 100 - current;

        var distance = Math.floor(remain / ratio);

        var max = min + distance;

        current = random(min, max);

        return current;

    };

    var next = function () {

        var t = time();
        var d = delay();

        element.style.transitionDuration = t + 's';
        element.style.transitionDelay = d + 's';
        jQuery("#progress_color").css({"transitionDuration":t+"s","transitionDelay":d+'s'});
        timeout = setTimeout(function () {
            move();
        }, ((t * 1000) + (d * 1000)));

    };

    var move = function () {
        var w=width();
        // console.log(element.style.width);
        // element.style.width = w + '%';
        jQuery("#progress_color").css("width",w+"%");
        jQuery("#progress_percent").text("("+w+"%)");
        next();

    };

    me.init = function (params) {

        element = params.element;
        overlay = params.overlay;

        ease = params.ease || ease;
        ratio = params.ratio || ratio;
        speed = params.speed || speed;
        frequency = params.frequency || frequency;
        jQuery("#progress_color").css({"opacity":"0","transitionDuration":'1s','transitionTimingFunction':ease,'transitionDelay':"0s"});
        jQuery("#progress_percent").text("(1%)");
        overlay.style.opacity = '0';


    };

    me.start = function () {

        current = 1;
        jQuery("#progress_color").css({"width":current+"%","opacity":"1"});
        jQuery("#progress_percent").text("("+current+"%)");
        overlay.style.opacity = '1';
        overlay.style.width = '100%';

        next();

    };

    me.end = function () {

        clearTimeout(timeout);
        jQuery("#progress_color").css({"width":"100%",'transitionDuration':'0.5s'});

        setTimeout(function () {
            jQuery("#progress_color").css("opacity","0");
            overlay.style.opacity = '0';
        }, 1000);

        setTimeout(function () {
            jQuery("#progress_color").css("width","0%");
            overlay.style.width = '0%';
        }, 2000);

    };

    return me;

}(window.ProgressBar || {}));

// YOU CAN INIT THAT PROGRESS BARY WHEREVER YOU WANT

ProgressBar.init({
    element: document.getElementById('progress_color'),
    overlay: document.getElementById('progress_nocolor'),
    ratio: 4,               // DEFAULT, YOU CAN SKIP IT
    speed: 5,               // DEFAULT, YOU CAN SKIP IT
    frequency: 1,       // DEFAULT, YOU CAN SKIP IT
    ease: 'ease'        // DEFAULT, YOU CAN SKIP IT
});

jQuery(document).ready(function(){
    var options = {
        valueNames: [ 'lu-form-text' ]
    };

    var pluginList = new List("pluginsBox", options);
    $('.search').on('keyup', function() {
        console.log("asdasd");
      var searchString = $(this).val();
      pluginList.search(searchString);
    });

});
