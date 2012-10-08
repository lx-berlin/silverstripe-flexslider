# silverstripe-flexslider

A module for Silverstripe 3.0 that uses the [FlexSlider 2 library](https://github.com/woothemes/FlexSlider) to create sliders. Sliders can be created from within the cms or by instantiating a new FlexSlider Object in php. 

## Requirements

Silverstripe 3.0

## Features

- create sliders in the cms (also configure flexslider options in the cms)
- create sliders of existing objects
- multiple sliders per page
- comes with a shortcode to embed cms defined sliders inside the website content

## Install

- download the module and unzip it. Make sure the folder name is "flexslider".
- run /dev/build
- add to your mysite/_config.php: `Object::add_extension('Page', 'FlexSliderExtension');`

## Usage

### a) embedding a slider that was setup in the cms

Sliders that are setup in the cms, can be output right from the template.
For example in Page.ss: `$FlexSlider(2,960,450)`
This will output the slider with the id=2 in your page. 
The values for width (960) and height (450) in this example are just to scale the images in the slides.
All images of slider 2 will be cropped to these dimensions. 
The slider itself will by default use 100% of the avaialble width in the template.
So make sure you set some styles in your css, like `.flexslider_2 { width: 400px }` or wrap it in an additional element, like `<div class="slider_wrapper">$FlexSlider(2,960,450)</div>`

### b) using the shortcode

Sliders that are setup in the cms, can also be displayed by using a shortcode.
Shortcode example: `[FlexSlider id="2" width="400" height="300"]`. This allowes the website administrator to create and embed sliders everywhere. Unlike a) this shortcode will also add style="width: 400px" to the html

### c) make a slider of already existing objects

Lets say you already have many products in your database (with an image). You can output them in a slider by adding a function in your Page.php, like:

`
public function productSlider() {

	$productSlider = new FlexSlider();
	$productSlider->ID = "allProducts"; // a cheap trick to identify this slider. will be added as class="flexslider_allProducts" to the template
	$productSlider->setDatalist(Product::get());
	$productSlider->setFieldMapping(array(	"Picture" => "Photo",
											"Title" => "Created", 
											"Description" => "SlideDesc",
											"InternalLink" => "URL"       // function URL() must return a Page Object. Or use ExternalLink which must return a string
									));
	// optional
	$productSlider->setImageWidth(119);
	// optional
	$productSlider->setImageHeight(100);
	// optional
	$productSlider->setOptions(array("animation" => "slide",
                                         "easing" => "linear",
                                         "direction" => "horizontal",
                                         "reverse" => 0,
                                         "animationLoop" => 0,
                                         "slideshow" => 0,
                                         "slideshowSpeed" => 7000,
                                         "animationSpeed" => 400,
                                         "randomize" => 0,
                                         "showControlNav" => 1));
    // optional
	$productSlider->addExtraClass("employees");
    
	return $productSlider;
}`

In your template file you just need to call `$productSlider`

## Changelog

v1.0.1 (2012-10-08) : 
Bugfixes, resetted some CSS values that is used in the simple-theme

v1.0.0 (2012-10-06) : 
initial version
