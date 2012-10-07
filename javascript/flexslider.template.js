$(document).ready(function() {
    $('.flexslider_$sliderID').flexslider({
        animation:      "$settings_animationType",           
        easing:         "$settings_easing",                     // doesnt seem to make a difference
        direction:      "$settings_direction",               
        reverse:         $settings_reverse,                     
        animationLoop:   $settings_animationLoop,         
        slideshow:       $settings_doSlideshow,               
        slideshowSpeed: "$settings_slideshowSpeed",     
        animationSpeed: "$settings_animationSpeed",     
        randomize:       $settings_randomize,                 
        controlNav:      $settings_showControlNav            
        //before: FLXslideBeforeHandler
    });
    
});

FLXslideBeforeHandler = function() {
    //console.log("before!");
    //FLXaddContentToSlides();
}

//FLXaddContentToSlides = function() {}