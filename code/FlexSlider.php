<?php

/**
 * A slider for Silverstripe 3 (uses FlexSlider2: https://github.com/woothemes/FlexSlider)
 *
 * @package silverstripe-flexslider
 */
class FlexSlider extends DataObject
{

	public static $singular_name = 'Slider';
	public static $plural_name = 'Sliders';

	public $imageWidth = 1280; // if no imageWidth is set, images will by default be resized to 1280x800, which should be big enough for all websites
	public $imageHeight = 800;
	public $FieldMapping = null;
	public $cssWidth = false; // when a FlexSlider is added by a Shortcode, we want to add style="width: 1280px"

	/**
	 * @var $extraClasses array Extra CSS-classes for the flexslider
	 */
	protected $extraClasses;

	public static $db = array('Title' => 'Varchar',
		'animation' => 'Enum("slide,fade","slide")',
		'easing' => 'Enum("linear,swing,easeInQuad,easeOutQuad,easeOutExpo","linear")',
		'direction' => 'Enum("horizontal,vertical","horizontal")',
		'reverse' => 'Boolean',
		'dynamicLoading' => 'Boolean',
		'animationLoop' => 'Boolean',
		'slideshow' => 'Boolean',
		'slideshowSpeed' => 'Int(7000)',
		'animationSpeed' => 'Int(600)',
		'randomize' => 'Boolean',
		'showControlNav' => 'Boolean'
	);

	public static $has_many = array('FlexSlides' => 'FlexSlide');

	static $defaults = array(
		'slideshowSpeed' => 7000,
		'animationSpeed' => 600,
	);

	// defaults
	public function populateDefaults()
	{
		parent::populateDefaults();
		$this->animation = "slide";
		$this->easing = "linear";
		$this->direction = "horizontal";
		$this->reverse = 0;
		$this->animationLoop = 0;
		$this->slideshow = 0;
		$this->slideshowSpeed = 7000;
		$this->animationSpeed = 600;
		$this->randomize = 0;
		$this->showControlNav = 1;
	}

	// column names of grid (translated to the current admin language)
	static $field_labels = array('NoSlides' => 'Number of Slides');

	// columns in Grid
	static $summary_fields = array(
		'ID',
		'Title',
		//'NoSlides'
	);

	public function getCMSFields()
	{

		$fields = parent::getCMSFields();

		$fields->removeByName("Title");
		$fields->removeByName("animation");
		$fields->removeByName("easing");
		$fields->removeByName("direction");
		$fields->removeByName("reverse");
		$fields->removeByName("dynamicLoading");
		$fields->removeByName("animationLoop");
		$fields->removeByName("slideshow");
		$fields->removeByName("slideshowSpeed");
		$fields->removeByName("animationSpeed");
		$fields->removeByName("randomize");
		$fields->removeByName("showControlNav");

		$animationTypes = $this->dbObject("animation")->enumValues();
		$easingTypes = $this->dbObject("easing")->enumValues();
		$directions = $this->dbObject("direction")->enumValues();

		// Main
		$field_Title = new TextField("Title", _t("FlexSlider.Title"));
		$field_Title->setRightTitle(_t("FlexSlider.TitleDescription"));
		$field_slideshow = new CheckboxField("slideshow", _t("FlexSlider.slideshow"));
		$field_animationLoop = new CheckboxField("animationLoop", _t("FlexSlider.animationLoop"));
		$field_animation = new Dropdownfield("animation", _t("FlexSlider.animationType"), $animationTypes);
		$field_direction = new Dropdownfield("direction", _t("FlexSlider.direction"), $directions);
		$field_easing = new Dropdownfield("easing", _t("FlexSlider.easingType"), $easingTypes);
		$field_easing->setRightTitle(_t("FlexSlider.easingTypeDescription"));

		$field_slideshowSpeedvalue = (!$this->ID) ? FlexSlider::$defaults["slideshowSpeed"] : $this->slideshowSpeed;
		$field_slideshowSpeed = new NumericField("slideshowSpeed", _t("FlexSlider.slideshowSpeed"), $field_slideshowSpeedvalue);
		$field_slideshowSpeed->setRightTitle(_t("FlexSlider.slideshowSpeedDescription"));

		$field_animationSpeedvalue = (!$this->ID) ? FlexSlider::$defaults["animationSpeed"] : $this->animationSpeed;
		$field_animationSpeed = new NumericField("animationSpeed", _t("FlexSlider.animationSpeed"), $field_animationSpeedvalue);
		$field_animationSpeed->setRightTitle(_t("FlexSlider.animationSpeedDescription"));

		$field_randomize = new CheckboxField("randomize", _t("FlexSlider.randomize"));
		$field_showControlNav = new CheckboxField("showControlNav", _t("FlexSlider.showControlNav"));

		$FieldsArray = array(
			$field_Title,
			$field_slideshow,
			$field_animationLoop,
			$field_animation,
			$field_direction,
			$field_easing,
			$field_slideshowSpeed,
			$field_animationSpeed,
			$field_randomize,
			$field_showControlNav
		);

		$fields->addFieldsToTab('Root.Main', $FieldsArray);

		return $fields;
	}

	public function NoSlides()
	{
		return $this->FlexSlides()->Count();
	}

	public function setDatalist($datalist)
	{
		if (!$datalist) user_error("you need to pass a datalist to setDatalist()", E_USER_NOTICE);
		$this->DataList = $datalist;
	}

	/**
	 * Add a CSS-class to the fieldset
	 *
	 * @param $class String
	 */
	public function addExtraClass($class)
	{
		$this->extraClasses[$class] = $class;
		return $this;
	}

	/**
	 * Compiles all CSS-classes. Optionally includes a "nolabel"-class
	 * if no title was set on the formfield.
	 * Uses {@link Message()} and {@link MessageType()} to add validatoin
	 * error classes which can be used to style the contained tags.
	 *
	 * @return string CSS-classnames
	 */
	public function extraClass()
	{
		$classnames = "";
		if ($this->extraClasses) {
			foreach ($this->extraClasses AS $classname) {
				$classnames .= " flexslider_" . $classname;
			}
		}
		return $classnames;
	}

	/**
	 * adds style="width: 123" to the FlexSlider
	 *
	 * @param mixed $width (either boolean yes/no or int)
	 */
	public function setCSSWidth($width)
	{
		if (!$width) $this->cssWidth = false;
		elseif ($width > 1) $this->cssWidth = $width;
		elseif ($width == true) $this->cssWidth = $this->imageWidth;
	}

	/**
	 * @param array $fieldmapping (e.g. array("Title" => "Fullname", "Description" => "Text"))
	 */
	public function setFieldMapping($fieldmapping)
	{
		if (!isset($fieldmapping["Picture"])) user_error("you need specify at least 'Picture'", E_USER_NOTICE);
		$this->FieldMapping = $fieldmapping;
	}

	/**
	 * @param array $options
	 */
	public function setOptions($options)
	{
		foreach ($options AS $option => $value) {
			$this->$option = $value;
		}
	}

	public function setImageWidth($width)
	{
		if ((int)$width > 0) $this->imageWidth = $width;
	}

	public function setImageHeight($height)
	{
		if ((int)$height > 0) $this->imageHeight = $height;
	}

	public function forTemplate()
	{

		// flex slider configuration
		$flexslider_config = array("sliderID" => $this->ID,
			"settings_animationType" => $this->animation,
			"settings_easing" => $this->easing,
			"settings_dynamicLoading" => $this->dynamicLoading,
			"settings_direction" => $this->direction,
			"settings_reverse" => $this->reverse,
			"settings_animationLoop" => $this->animationLoop,
			"settings_doSlideshow" => $this->slideshow,
			"settings_slideshowSpeed" => $this->slideshowSpeed,
			"settings_animationSpeed" => $this->animationSpeed,
			"settings_randomize" => $this->randomize,
			"settings_showControlNav" => $this->showControlNav
		);

		// load all needed Javascript
		Requirements::javascript(THIRDPARTY_DIR . '/jquery/jquery.js');
		Requirements::javascript('flexslider/javascript/jquery.easing.1.3.js');
		Requirements::javascript('flexslider/thirdparty/FlexSlider/jquery.flexslider.js');
		Requirements::javascriptTemplate('flexslider/javascript/flexslider.template.js', $flexslider_config);

		// load css
		Requirements::css('flexslider/thirdparty/FlexSlider/flexslider.css');   // original css of flexslider
		Requirements::css('flexslider/css/flexslider.css');                     // additional css for ss flexslider module
		Requirements::css($this->ThemeDir() . '/css/flexslider.css');             // look for a custom flexslider.css in the projects themes folder


		return $this->renderWith('FlexSlider');
	}

	public function getSlides()
	{

		// getting the slides of a flexslider that was created in the cms
		if ($this->FlexSlides()->exists()) {
			$FlexSlides = $this->FlexSlides()->filter("isEnabled", 1);
			if (!$FlexSlides->exists()) return false;
		} // getting the slides of a flexslider that was instanced with "$Slider = new FlexSlider(); $Slider->setDatalist($datalist);"
		elseif ($this->DataList) {
			$FlexSlides = $this->createSlidesFromDataList();
		}

		return $FlexSlides;
	}

	/**
	 * If the slided Objects are created with $productSlider->setDatalist(Product::get()),
	 * wen need to build an ArrayList of them
	 */
	public function createSlidesFromDataList()
	{

		$slide_title = false;
		$slide_description = false;
		$slide_internallink = false;
		$slide_externallink = false;

		$slide_picture = $this->FieldMapping["Picture"];
		if (isset($this->FieldMapping["Title"])) $slide_title = $this->FieldMapping["Title"];
		if (isset($this->FieldMapping["Description"])) $slide_description = $this->FieldMapping["Description"];
		if (isset($this->FieldMapping["InternalLink"])) $slide_internallink = $this->FieldMapping["InternalLink"];
		if (isset($this->FieldMapping["ExternalLink"])) $slide_externallink = $this->FieldMapping["ExternalLink"];

		$FlexSlides = new ArrayList();
		foreach ($this->DataList AS $Obj) {
			$FlexSlide = new FlexSlide();

			// Picture
			if ($Obj->hasMethod($slide_picture)) $FlexSlide->PictureID = $Obj->$slide_picture()->ID;
			// SlideTitle
			if ($slide_title) {
				if (isset($Obj->$slide_title)) $FlexSlide->SlideTitle = $Obj->$slide_title;
				elseif ($Obj->hasMethod($slide_title)) $FlexSlide->SlideTitle = $Obj->$slide_title();
			}
			// SlideDescription
			if ($slide_description) {
				if (isset($Obj->$slide_description)) $FlexSlide->SlideDescription = $Obj->$slide_description;
				elseif ($Obj->hasMethod($slide_description)) $FlexSlide->SlideDescription = $Obj->$slide_description();
			}
			// InternalLink
			if ($slide_internallink) {
				if ($Obj->hasMethod($slide_internallink)) $FlexSlide->InternalLinkID = $Obj->$slide_internallink()->ID;
			}
			// ExternalLink
			if ($slide_externallink) {
				if (isset($Obj->$slide_externallink)) $FlexSlide->ExternalLink = $Obj->$slide_externallink;
				elseif ($Obj->hasMethod($slide_externallink)) $FlexSlide->ExternalLink = $Obj->$slide_externallink();
			}

			$FlexSlides->push($FlexSlide);
		}
		return $FlexSlides;
	}


	/**
	 * @example [FlexSlider id="2" width="400" height="300"]
	 */
	public static function FlexSliderShortCodeHandler($arguments)
	{

		if (!isset($arguments['id'])) return;

		$imageWidth = (isset($arguments['width'])) && ((int)$arguments['width'] > 0) ? (int)$arguments['width'] : null;
		$imageHeight = (isset($arguments['height'])) && ((int)$arguments['height'] > 0) ? (int)$arguments['height'] : null;

		$newPage = new Page();
		$fs = $newPage->FlexSlider($arguments['id'], $imageWidth, $imageHeight);
		if (isset($arguments['fixedWidth']) && $arguments['fixedWidth']) {
			$fs->setCSSWidth(true);
		}
		return $fs->forTemplate();
	}

}
