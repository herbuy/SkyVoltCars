<?php

require_once ("smart-widgets.php");
SmartHTMLElement::disable_auto_display_of_media_queries_near_element();

//we can have preset device sizes
class MediaSettingsForSmartcash{
    public static function small(){
        return CSSMediaType::create()->
        set("max-width","400px")-> //add support for funtions like max_width, min_width, and_max_width,_and_min_width
        set("min-width","150px");
    }
    public static function large(){
        return CSSMediaType::create()->
        set("max-width","2000px")-> //add support for funtions like max_width, min_width, and_max_width,_and_min_width
        set("min-width","401px");
    }
}


//===== and html element that creates the media query
print "<h1>Demo on creating media queries on the fly - at point of creating html element</h1>";

print (new SmartBodyTag())->
add_child("my simple div")->
add_class("my_class")->
add_media_query(
    StyleSheetForDevice::instance()->
    set_media_type(
        MediaSettingsForSmartcash::small()
    )->
    set_stylesheet(
        ContentStylesheet::instance()->
        set_and_format(
            ".my_class",
            CSSStyleSpecs::instance()->
            set("font-size","3mm")->
            set("background-color","seagreen")
        )
    )
)->
add_media_query(
    StyleSheetForDevice::instance()->
    set_media_type(
        MediaSettingsForSmartcash::large()
    )->
    set_stylesheet(
        ContentStylesheet::instance()->
        set_and_format(
            ".my_class",
            CSSStyleSpecs::instance()->
            set("font-size","150%")->
            set("background-color","gray")

        )
    )
);


print StyleSheetForDevice::getAllDeviceSpecificStyleSheetsAsStyleTag();

