<?php
class FlexSliderAdmin extends ModelAdmin {
    
    public static $managed_models = array('FlexSlider'); 
    
    // disable the importer
    public static $model_importers = array();
    
    // Linked as /admin/slides/ 
    static $url_segment = 'slides'; 
    
    // title in cms navigation
    static $menu_title = 'Slider';
    
    // menu icon
    static $menu_icon = 'flexslider/images/icons/flexslider_icon.png';    
    
}
