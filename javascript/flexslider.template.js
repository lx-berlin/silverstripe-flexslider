$(document).ready(function() {
    $('.flexslider_$sliderID').flexslider({
        animation: "$settings_animationType",           // funktioniert
        easing: "$settings_easing",                     // man sieht irgendwie keinen Unterschied zwischen den verschiedenen Optionen
        direction: "$settings_direction",               // funktioniert
        reverse: $settings_reverse,                     // funktioniert
        animationLoop: $settings_animationLoop,         // funktioniert
        slideshow: $settings_doSlideshow,               // funktioniert
        slideshowSpeed: "$settings_slideshowSpeed",     // funktioniert
        animationSpeed: "$settings_animationSpeed",     // funktioniert
        randomize: $settings_randomize,                 // funktioniert
        controlNav: $settings_showControlNav            // funktioniert
        //before: FLXslideBeforeHandler
    });
    
});

FLXslideBeforeHandler = function() {
    //console.log("before!");
    //FLXaddContentToSlides();
}

//FLXaddContentToSlides = function() {}