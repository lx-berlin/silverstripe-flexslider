<?php
/**
* a single slide of a flexslider
* 
* @package silverstripe-flexslider
*/
class FlexSlide extends DataObject {
    
    public static $singular_name     = 'Slide'; 
    public static $plural_name       = 'Slides';  
   
    public static $db                = array('Position'         => 'Int(2)',
                                             'SlideTitle'       => 'Varchar',
                                             'SlideDescription' => 'Varchar',
                                             'ExternalLink'     => 'Varchar',
                                             'isEnabled'        => 'Boolean');
    
    public static $has_one           = array('FlexSlider'       => 'FlexSlider',
                                             'Picture'          => 'Image',
                                             'InternalLink'     => 'SiteTree');      
    
    public static $many_many         = array();
     
    public static $belongs_many_many = array(); 
    
    // gridfield sorting
    public static $default_sort = "Position ASC";
    
    static $searchable_fields = array(
      'SlideTitle'
    );
    
    // column names of grid (translated to the current admin language)
    static $field_labels = array('Picture.CMSThumbnail' => 'Image',
                                 'isEnabledText'        => 'enabled',
                                 'SlideTitle'           => 'Title',
                                 'SlideDescription'     => 'Description'
    );
    
    // columns in Grid
    static $summary_fields = array(
        'Picture.CMSThumbnail',
        'SlideTitle',
        'SlideDescription',
        'isEnabledText'
    );     
    
    // form to add/edit records
    public function getCMSFields() {
       
        $fields = parent::getCMSFields();
        $fields->removeByName("FlexSliderID");
        
        // Main
        $field_Picture = new UploadField('Picture', _t("FlexSlider.Picture"));
        $field_Picture->setFolderName("FlexSlides");
        $field_Picture->setConfig('allowedMaxFileNumber', 1);
        
        $field_Position = new NumericField("Position", _t("FlexSlider.Position"));
        $field_Position->RightTitle(_t("FlexSlider.PositionExplain"));
        $field_SlideTitle = new TextField("SlideTitle", _t("FlexSlider.Title"));
        $field_SlideDescription = new TextField("SlideDescription", _t("FlexSlider.Description"));
        $field_InternalLink = new TreeDropdownField("InternalLinkID", _t('FlexSlider.InternalLink'), 'SiteTree');
        $field_removeInternalLink = new CheckboxField("doRemoveInternalLink", _t("FlexSlider.doRemoveInternalLink"));
        $field_ExternalLink = new TextField("ExternalLink", _t("FlexSlider.ExternalLink"));
        $field_isEnabled = new CheckboxField("isEnabled", _t("FlexSlider.isEnabled"));
        
        $fields->addFieldToTab('Root.Main', $field_Position);
        $fields->addFieldToTab('Root.Main', $field_Picture);
        $fields->addFieldToTab('Root.Main', $field_SlideTitle);
        $fields->addFieldToTab('Root.Main', $field_SlideDescription);
        $fields->addFieldToTab('Root.Main', $field_InternalLink);
        $fields->addFieldToTab('Root.Main', $field_removeInternalLink);
        $fields->addFieldToTab('Root.Main', $field_ExternalLink);
        $fields->addFieldToTab('Root.Main', $field_isEnabled);

        return $fields;
    }
    
                                                // validator (requires https://github.com/lx-berlin/NetefxValidator)
                                                /*
                                                public function getCMSValidator() {
                                                    
                                                    $rule_DateUntil_empty                   = new NetefxValidatorRuleEMPTY      ("DateUntil");
                                                    $rule_DateUntil_After_DateFrom          = new NetefxValidatorRuleFUNCTION   ("DateUntil", '', 'error', array('NetefxValidatorLibraryDate', 'UntilIsMinDaysAfterFrom', array('dateFrom'  => 'DateFrom','dateUntil' => 'DateUntil', 'min' => 0)));
                                                    $rule_DateUntil_empty_OR_After_DateFrom = new NetefxValidatorRuleOR         ("DateUntil", 'Date Until must be equal or after DateFrom', 'error', array($rule_DateUntil_empty, $rule_DateUntil_After_DateFrom));
                                                    $rule_TitleEN_required                  = new NetefxValidatorRuleREQUIRED   ("TitleEN");
                                                    $rule_DescriptionEN_required            = new NetefxValidatorRuleREQUIRED   ("DescriptionEN");
                                                    $rule_CityEN_required                   = new NetefxValidatorRuleREQUIRED   ("CityEN");
                                                    
                                                    $validator = new NetefxValidator($rule_DateUntil_empty_OR_After_DateFrom,
                                                                                     $rule_TitleEN_required,
                                                                                     $rule_DescriptionEN_required,
                                                                                     $rule_CityEN_required);
                                                    return $validator;
                                                }
                                                */
    
    public function onBeforeWrite() {
       parent::onBeforeWrite();
       
       if ($this->doRemoveInternalLink) {
           $this->InternalLinkID = 0;
           $this->doRemoveInternalLink = false;
       }
   }
     
    public function onBeforeDelete() {
        parent::onBeforeDelete();         
    }
     
    public function onAfterWrite() {
        parent::onAfterWrite();
    }  
    
    // instead of 1/0 we want "yes"
    public function isEnabledText() {
        return ($this->isEnabled) ? _t("FlexSlider.Yes") : "";
    }
    
    public function flexCroppedImage($imageWidth,$imageHeight) {
        $Picture = $this->Picture();
        if (!$Picture->exists()) return false;
        return $Picture->croppedImage($imageWidth,$imageHeight);
    }
    
    /**
    * returns either an internal link, an external link or an empty string
    * 
    */
    public function getSlideTarget() {
        
        $link = "";
        if ($this->InternalLink()->ID > 0)  { $link = $this->InternalLink()->Link(); }
        else if ($this->ExternalLink != "") { $link = $this->ExternalLink; }
        
        return $link;
    }        
    
}