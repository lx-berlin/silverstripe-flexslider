<?php
class FlexSliderExtension extends DataExtension {
    
    /**
    * @example in template: $FlexSlider(2, 960, 450)
    */
    public function FlexSlider($ID=1, $imageWidth=null, $imageHeight=null) {
        
        $FlexSlider = FlexSlider::get()->byID($ID);
        if (!$FlexSlider) return false;
        
        // set imageWidth and imageHeight
        if ($imageWidth) $FlexSlider->setImageWidth($imageWidth);
        if ($imageHeight) $FlexSlider->setImageHeight($imageHeight);
        
        return $FlexSlider;
    }
    
}