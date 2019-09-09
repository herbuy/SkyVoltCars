<?php

class StyleSheetForDevice{
    //==== some static storage for all media queries
    //-- we can use it to render all at once as a style tag
    private static $arr_of_all_device_specifc_stylesheets= array();
    public static function getAllDeviceSpecificStyleSheetsAsStyleTag(){
        $queries_as_string = join(" ",self::$arr_of_all_device_specifc_stylesheets);
        $tag = new SmartStyleTag();
        $tag->set_inner_html($queries_as_string);
        return $tag;
    }
    //================================
    private $where;
    private $css_selector_settings;
    public function __construct()
    {
        self::$arr_of_all_device_specifc_stylesheets[] = $this;
    }
    public static function instance(){
        return new self();
    }
    public function set_media_type($css_media_settings){
        $this->where = $css_media_settings;
        return $this;
    }
    public function set_stylesheet($css_selector_settings){
        $this->css_selector_settings = $css_selector_settings;
        return $this;
    }
    public function __toString()
    {
        return sprintf("@media %s {%s}",$this->where,$this->css_selector_settings);
    }
}

class BaseClassForCSSSettings{
    private $arr_of_settings = array();
    protected $delimiter = ";";
    protected $opening_bracket = "";
    protected $closing_bracket = "";

    public function set($property, $value){
        $this->arr_of_settings[] = sprintf("%s%s:%s%s",
            $this->opening_bracket,$property,$value,$this->closing_bracket);
        return $this;
    }
    public function __toString()
    {
        return join($this->delimiter,$this->arr_of_settings);
    }
}
class CSSStyleSpecs extends BaseClassForCSSSettings{
    protected $delimiter = ";";
    public static function instance(){
        return new self();
    }
}

class CSSMediaType extends BaseClassForCSSSettings{
    protected $delimiter = " and ";
    protected $opening_bracket = "(";
    protected $closing_bracket = ")";

    public static function create(){
        return new self();
    }
    public function min_width($value){
        $this->set("min-width",$value);
        return $this;
    }
    public function max_width($value){
        $this->set("max-width",$value);
        return $this;
    }
    public function min_height($value){
        $this->set("min-height",$value);
        return $this;
    }
    public function max_height($value){
        $this->set("max-height",$value);
        return $this;
    }
    public function orientation_portrait(){
        $this->set("orientation","portrait");
        return $this;
    }
    public function orientation_landscape(){
        $this->set("orientation","landscape");
        return $this;
    }
    public function and_min_width($value){
        $this->set("min-width",$value);
        return $this;
    }
    public function and_max_width($value){
        $this->set("max-width",$value);
        return $this;
    }
    public function and_min_height($value){
        $this->set("min-height",$value);
        return $this;
    }
    public function and_max_height($value){
        $this->set("max-height",$value);
        return $this;
    }
    public function and_orientation_portrait(){
        $this->set("orientation","portrait");
        return $this;
    }
    public function and_orientation_landscape(){
        $this->set("orientation","landscape");
        return $this;
    }
}


class ContentStylesheet{
    private $arr_of_settings = array();
    public function set_and_format($selector_as_string, $css_property_settings){
        $string_to_add_to_final_array = sprintf("%s{%s}",$selector_as_string,$css_property_settings);
        return $this->add_to_final_array($string_to_add_to_final_array);
    }
    private function add_to_final_array($css_string){
        $this->arr_of_settings[] = $css_string;
        return $this;
    }
    /** @param StylesSpecsForSelector $css_selector_settings_pair
     * @return ContentStylesheet */
    public function add($css_selector_settings_pair){
        if(!is_a($css_selector_settings_pair, "StylesSpecsForSelector")){
            throw new Exception("expected StylesSpecsForSelector");
        }
        $this->set_and_format(
            $css_selector_settings_pair->selector(),
            $css_selector_settings_pair->property_settings()
        );
        return $this;

    }
    public function __toString()
    {
        return join(" ",$this->arr_of_settings);
    }
    public static function instance(){
        return new self();
    }

    public function addStyleSpecsFromString($string)
    {
        return $this->add_to_final_array($string."");
    }
}


abstract class ResponsiveCSSForComponent{
    private static $all_responsive_css = array();
    public function __construct()
    {
        self::$all_responsive_css[] = $this;
    }
    public static function getAllAsOneString(){
        return join(" ",self::$all_responsive_css);
    }

    public static function getAllAsStyleTag(){
        $tag = new SmartStyleTag();
        $tag->add_child(self::getAllAsOneString());
        return $tag;
    }
    //========

    public function __toString()
    {

        return join(
            " "
            ,
            array(
                $this->preProcess(
                    $this->CSSStringOrElementWithFullDeclarationAtDefault()
                )
            ,
                $this->getMediaQueryString()
            )
        );
    }
    private function getMediaQueryString()
    {
        return join(
            " ",
            [

                $this->getDeviceStylesheet("240px", $this->preProcess($this->CSSStringOrElementWithFullDeclarationAt240())),
                $this->getDeviceStylesheet("320px", $this->preProcess($this->CSSStringOrElementWithFullDeclarationAt320())),
                $this->getDeviceStylesheet("360px", $this->preProcess($this->CSSStringOrElementWithFullDeclarationAt360())),
                $this->getDeviceStylesheet("480px", $this->preProcess($this->CSSStringOrElementWithFullDeclarationAt480())),
                $this->getDeviceStylesheet("540px",$this->preProcess($this->CSSStringOrElementWithFullDeclarationAt540())),
                $this->getDeviceStylesheet("600px", $this->preProcess($this->CSSStringOrElementWithFullDeclarationAt600())),
                $this->getDeviceStylesheet("720px", $this->preProcess($this->CSSStringOrElementWithFullDeclarationAt720())),
                $this->getDeviceStylesheet("768px", $this->preProcess($this->CSSStringOrElementWithFullDeclarationAt768())),
                $this->getDeviceStylesheet("800px", $this->preProcess($this->CSSStringOrElementWithFullDeclarationAt800())),
                $this->getDeviceStylesheet("854px", $this->preProcess($this->CSSStringOrElementWithFullDeclarationAt854())),
                $this->getDeviceStylesheet("960px", $this->preProcess($this->CSSStringOrElementWithFullDeclarationAt960())),
                $this->getDeviceStylesheet("1200px", $this->preProcess($this->CSSStringOrElementWithFullDeclarationAt1200())),
                $this->getDeviceStylesheet("1280px", $this->preProcess($this->CSSStringOrElementWithFullDeclarationAt1280())),
                $this->getDeviceStylesheet("1320px", $this->preProcess($this->CSSStringOrElementWithFullDeclarationAt1320()))
            ]
        );
    }
    /** @param \CSSElement $cssStringOrElementWithFullDeclaration
     * @return string */
    private function preProcess($cssStringOrElementWithFullDeclaration)
    {
        if(is_string($cssStringOrElementWithFullDeclaration)){
            return $cssStringOrElementWithFullDeclaration;
        }
        else if(is_a($cssStringOrElementWithFullDeclaration,"CSSElement")){
            if($cssStringOrElementWithFullDeclaration->hasStyles()){
                return $cssStringOrElementWithFullDeclaration->getFullDeclarationAsString();
            }
            else{
                return "";
            }
        }
        else{
            throw new Exception("expected a string for CSSElement");
        }
    }

    /**
     * @return StyleSheetForDevice
     */
    private function getDeviceStylesheet($min_width,$css_string_or_element_with_full_declaration)
    {

        $css_string_or_element_with_full_declaration = trim($css_string_or_element_with_full_declaration."");
        if(strlen($css_string_or_element_with_full_declaration) < 1){
            return "";
        }

        $device_stylesheet = StyleSheetForDevice::instance();

        $device_stylesheet->set_media_type(CSSMediaType::create()->min_width($min_width));



        $device_stylesheet->set_stylesheet(
            ContentStylesheet::instance()->
            addStyleSpecsFromString($css_string_or_element_with_full_declaration)
        );
        return $device_stylesheet;
    }



    //========================= TO OVERRIDE ===========================
    abstract protected function cssElement();
    /** @return CSSElement */
    abstract protected function CSSStringOrElementWithFullDeclarationAtDefault();
    /** @return CSSElement */
    protected function CSSStringOrElementWithFullDeclarationAt240(){
        return "";//return $this->CSSStringOrElementWithFullDeclarationAtDefault();
    }
    /** @return CSSElement */
    protected function CSSStringOrElementWithFullDeclarationAt320(){
        return "";//return $this->CSSStringOrElementWithFullDeclarationAt240();
    }
    /** @return CSSElement */
    protected function CSSStringOrElementWithFullDeclarationAt360(){
        return "";//return $this->CSSStringOrElementWithFullDeclarationAt320();
    }
    /** @return CSSElement */
    protected function CSSStringOrElementWithFullDeclarationAt480(){
        return "";//return $this->CSSStringOrElementWithFullDeclarationAt360();
    }
    /** @return CSSElement */
    protected function CSSStringOrElementWithFullDeclarationAt540(){
        return "";//return $this->CSSStringOrElementWithFullDeclarationAt480();
    }
    /** @return CSSElement */
    protected function CSSStringOrElementWithFullDeclarationAt600(){
        return "";//return $this->CSSStringOrElementWithFullDeclarationAt540();
    }
    /** @return CSSElement */
    protected function CSSStringOrElementWithFullDeclarationAt720(){
        return "";//return $this->CSSStringOrElementWithFullDeclarationAt600();
    }
    /** @return CSSElement */
    protected function CSSStringOrElementWithFullDeclarationAt768(){
        return "";//return $this->CSSStringOrElementWithFullDeclarationAt720();
    }
    /** @return CSSElement */
    protected function CSSStringOrElementWithFullDeclarationAt800(){
        return "";//return $this->CSSStringOrElementWithFullDeclarationAt768();
    }
    /** @return CSSElement */
    protected function CSSStringOrElementWithFullDeclarationAt854(){
        return "";//return $this->CSSStringOrElementWithFullDeclarationAt800();
    }
    /** @return CSSElement */
    protected function CSSStringOrElementWithFullDeclarationAt960(){
        return "";//return $this->CSSStringOrElementWithFullDeclarationAt854();
    }
    /** @return CSSElement */
    protected function CSSStringOrElementWithFullDeclarationAt1200(){
        return "";//return $this->CSSStringOrElementWithFullDeclarationAt960();
    }
    /** @return CSSElement */
    protected function CSSStringOrElementWithFullDeclarationAt1280(){
        return "";//return $this->CSSStringOrElementWithFullDeclarationAt1200();
    }
    /** @return CSSElement */
    protected function CSSStringOrElementWithFullDeclarationAt1320(){
        return "";//return $this->CSSStringOrElementWithFullDeclarationAt1280();
    }
    /** @return CSSElement */
    protected function CSSStringOrElementWithFullDeclarationAt1440(){
        return "";//return $this->CSSStringOrElementWithFullDeclarationAt1320();
    }
    /** @return CSSElement */
    protected function CSSStringOrElementWithFullDeclarationAt1560(){
        return "";//return $this->CSSStringOrElementWithFullDeclarationAt1440();
    }
    /** @return CSSElement */
    protected function CSSStringOrElementWithFullDeclarationAt1680(){
        return "";//return $this->CSSStringOrElementWithFullDeclarationAt1560();
    }
    /** @return CSSElement */
    protected function CSSStringOrElementWithFullDeclarationAt1800(){
        return "";//return $this->CSSStringOrElementWithFullDeclarationAt1680();
    }
    /** @return CSSElement */
    protected function CSSStringOrElementWithFullDeclarationAt1920(){
        return "";//return $this->CSSStringOrElementWithFullDeclarationAt1800();
    }

}

//todo: helper class
class ResponsiveValuesForCSSProperty{
    private $css_element;
    public function setCSSElement($css_element){
        if(!is_a($css_element,"CSSElement")){
            throw new Exception("expected a CSSElement");
        }
        $this->css_element = $css_element;
        return $this;
    }
    public function setCSSClass($className){
        $this->setCSSElement(CSSElementOfClass($className));
        return $this;
    }
    /** @return CSSElement */
    public function getCSSElement(){
        return $this->css_element;
    }

    private $default_ = null;

    public function setForDefault($value){
        $this->default_ = $value;
        return $this;
    }
    public function getForDefault(){
        return $this->default_;
    }

    private $_240;
    public function setFor240($value){
        $this->_240 = $value;
        return $this;
    }
    public function getFor240(){
        return $this->val_or_val($this->_240,$this->getForDefault());
    }

    private $_320;
    public function setFor320($value){
        $this->_320 = $value;
        return $this;
    }
    public function getFor320(){
        return $this->val_or_val($this->_320,$this->getFor240());
    }

    private $_360;
    public function setFor360($value){
        $this->_360 = $value;
        return $this;
    }
    public function getFor360(){
        return $this->val_or_val($this->_360,$this->getFor320());
    }

    private $_480;
    public function setFor480($value){
        $this->_480 = $value;
        return $this;
    }
    public function getFor480(){
        return $this->val_or_val($this->_480,$this->getFor360());
    }

    private $_540;
    public function setFor540($value){
        $this->_540 = $value;
        return $this;
    }
    public function getFor540(){
        return $this->_540;
    }

    private $_600;
    public function setFor600($value){
        $this->_600 = $value;
        return $this;
    }
    public function getFor600(){
        return $this->val_or_val($this->_600,$this->getFor240());
    }

    private $_720;
    public function setFor720($value){
        $this->_720 = $value;
        return $this;
    }
    public function getFor720(){
        return $this->val_or_val($this->_720,$this->getFor600());
    }

    private $_768;
    public function setFor768($value){
        $this->_768 = $value;
        return $this;
    }
    public function getFor768(){
        return $this->val_or_val($this->_768,$this->getFor720());
    }

    private $_800;
    public function setFor800($value){
        $this->_800 = $value;
        return $this;
    }
    public function getFor800(){
        return $this->val_or_val($this->_800,$this->getFor768());
    }

    private $_854;
    public function setFor854($value){
        $this->_854 = $value;
        return $this;
    }
    public function getFor854(){
        return $this->val_or_val($this->_854,$this->getFor800());
    }

    private $_960;
    public function setFor960($value){
        $this->_960 = $value;
        return $this;
    }
    public function getFor960(){
        return $this->val_or_val($this->_960,$this->getFor854());
    }

    private $_1200;
    public function setFor1200($value){
        $this->_1200 = $value;
        return $this;
    }
    public function getFor1200(){
        return $this->val_or_val($this->_1200,$this->getFor960());
    }

    private $_1280;
    public function setFor1280($value){
        $this->_1280 = $value;
        return $this;
    }
    public function getFor1280(){
        return $this->val_or_val($this->_1280,$this->getFor1200());
    }

    private $_1320;
    public function setFor1320($value){
        $this->_1320 = $value;
        return $this;
    }
    public function getFor1320(){
        return $this->val_or_val($this->_1320,$this->getFor1280());
    }

    private function val_or_val($prefered, $alternative)
    {
        //return strlen($prefered) > 0 ? $prefered : $alternative;
        return $prefered;
    }


}
//todo: expand concept to allow setting say 3 elements side by side - for laying out header items, etc
abstract class ResponsivePropertyForElement extends ResponsiveCSSForComponent{
    /** @var  ResponsiveValuesForCSSProperty $rcss_key_value_pairs */
    private $rcss_key_value_pairs;

    public function __construct($rcss_key_value_pairs)
    {
        $this->rcss_key_value_pairs = $rcss_key_value_pairs;
        parent::__construct();
    }

    abstract protected function get_property();

    protected function cssElement()
    {
        return $this->rcss_key_value_pairs->getCSSElement();
    }
    protected function CSSStringOrElementWithFullDeclarationAtDefault()
    {
        $css_element = $this->cssElement()->set_style($this->get_property(),$this->rcss_key_value_pairs->getForDefault());
        return is_null($this->rcss_key_value_pairs->getForDefault()) ? "":$css_element;

    }
    protected function CSSStringOrElementWithFullDeclarationAt240()
    {
        $css_element = $this->cssElement()->set_style($this->get_property(),$this->rcss_key_value_pairs->getFor240());
        return is_null($this->rcss_key_value_pairs->getFor240()) ? "":$css_element;
    }
    protected function CSSStringOrElementWithFullDeclarationAt320()
    {
        $css_element = $this->cssElement()->set_style($this->get_property(),$this->rcss_key_value_pairs->getFor320());
        return is_null($this->rcss_key_value_pairs->getFor320()) ? "":$css_element;
    }
    protected function CSSStringOrElementWithFullDeclarationAt360()
    {
        $css_element = $this->cssElement()->set_style($this->get_property(),$this->rcss_key_value_pairs->getFor360());
        return is_null($this->rcss_key_value_pairs->getFor360()) ? "":$css_element;
    }
    protected function CSSStringOrElementWithFullDeclarationAt480()
    {
        $css_element = $this->cssElement()->set_style($this->get_property(),$this->rcss_key_value_pairs->getFor480());
        return is_null($this->rcss_key_value_pairs->getFor480()) ? "":$css_element;
    }
    protected function CSSStringOrElementWithFullDeclarationAt540()
    {
        $css_element = $this->cssElement()->set_style($this->get_property(),$this->rcss_key_value_pairs->getFor540());
        return is_null($this->rcss_key_value_pairs->getFor540()) ? "":$css_element;
    }
    protected function CSSStringOrElementWithFullDeclarationAt600()
    {
        $css_element = $this->cssElement()->set_style($this->get_property(),$this->rcss_key_value_pairs->getFor600());
        return is_null($this->rcss_key_value_pairs->getFor600()) ? "":$css_element;
    }
    protected function CSSStringOrElementWithFullDeclarationAt720()
    {
        $css_element = $this->cssElement()->set_style($this->get_property(),$this->rcss_key_value_pairs->getFor720());
        return is_null($this->rcss_key_value_pairs->getFor720()) ? "":$css_element;
    }
    protected function CSSStringOrElementWithFullDeclarationAt768()
    {
        $css_element = $this->cssElement()->set_style($this->get_property(),$this->rcss_key_value_pairs->getFor768());
        return is_null($this->rcss_key_value_pairs->getFor768()) ? "":$css_element;
    }
    protected function CSSStringOrElementWithFullDeclarationAt800()
    {
        $css_element = $this->cssElement()->set_style($this->get_property(),$this->rcss_key_value_pairs->getFor800());
        return is_null($this->rcss_key_value_pairs->getFor800()) ? "":$css_element;
    }
    protected function CSSStringOrElementWithFullDeclarationAt854()
    {
        $css_element = $this->cssElement()->set_style($this->get_property(),$this->rcss_key_value_pairs->getFor854());
        return is_null($this->rcss_key_value_pairs->getFor854()) ? "":$css_element;
    }
    protected function CSSStringOrElementWithFullDeclarationAt960()
    {
        $css_element = $this->cssElement()->set_style($this->get_property(),$this->rcss_key_value_pairs->getFor960());
        return is_null($this->rcss_key_value_pairs->getFor960()) ? "":$css_element;
    }
    protected function CSSStringOrElementWithFullDeclarationAt1200()
    {
        $css_element = $this->cssElement()->set_style($this->get_property(),$this->rcss_key_value_pairs->getFor1200());
        return is_null($this->rcss_key_value_pairs->getFor1200()) ? "":$css_element;
    }
    protected function CSSStringOrElementWithFullDeclarationAt1280()
    {
        $css_element = $this->cssElement()->set_style($this->get_property(),$this->rcss_key_value_pairs->getFor1280());
        return is_null($this->rcss_key_value_pairs->getFor1280()) ? "":$css_element;
    }
    protected function CSSStringOrElementWithFullDeclarationAt1320()
    {
        $css_element = $this->cssElement()->set_style($this->get_property(),$this->rcss_key_value_pairs->getFor1320());
        return is_null($this->rcss_key_value_pairs->getFor1320()) ? "":$css_element;
    }

}

//todo: some properties that support responsive css
class ResponsiveWidthForElement extends ResponsivePropertyForElement{
    protected function get_property()
    {
        return "width";
    }
}
class ResponsiveHeightForElement extends ResponsivePropertyForElement{
    protected function get_property()
    {
        return "height";
    }
}

class ResponsiveMarginForElement extends ResponsivePropertyForElement{
    protected function get_property()
    {
        return "margin";
    }
}
class ResponsiveMarginLeftForElement extends ResponsivePropertyForElement{
    protected function get_property()
    {
        return "margin-left";
    }
}
class ResponsiveMarginRightForElement extends ResponsivePropertyForElement{
    protected function get_property()
    {
        return "margin-right";
    }
}
class ResponsiveMarginTopForElement extends ResponsivePropertyForElement{
    protected function get_property()
    {
        return "margin-top";
    }
}
class ResponsiveMarginBottomForElement extends ResponsivePropertyForElement{
    protected function get_property()
    {
        return "margin-bottom";
    }
}

class ResponsiveDisplayForElement extends ResponsivePropertyForElement{
    protected function get_property()
    {
        return "display";
    }
}
class ResponsivePositionForElement extends ResponsivePropertyForElement{
    protected function get_property()
    {
        return "position";
    }
}

class ResponsiveZIndexForElement extends ResponsivePropertyForElement{
    protected function get_property()
    {
        return "z-index";
    }
}

class ResponsiveTopForElement extends ResponsivePropertyForElement{
    protected function get_property()
    {
        return "top";
    }
}
class ResponsiveLeftForElement extends ResponsivePropertyForElement{
    protected function get_property()
    {
        return "left";
    }
}
class ResponsiveRightForElement extends ResponsivePropertyForElement{
    protected function get_property()
    {
        return "right";
    }
}
class ResponsiveBottomForElement extends ResponsivePropertyForElement{
    protected function get_property()
    {
        return "bottom";
    }
}

class ResponsiveFloatForElement extends ResponsivePropertyForElement{
    protected function get_property()
    {
        return "float";
    }
}

class ResponsiveFontSizeForElement extends ResponsivePropertyForElement{
    protected function get_property()
    {
        return "font-size";
    }
}

class ResponsiveOverflowXForElement extends ResponsivePropertyForElement{
    protected function get_property()
    {
        return "overflow-x";
    }
}
class ResponsiveOverflowYForElement extends ResponsivePropertyForElement{
    protected function get_property()
    {
        return "overflow-y";
    }
}
class ResponsiveOverflowForElement extends ResponsivePropertyForElement{
    protected function get_property()
    {
        return "overflow";
    }
}
class ResponsiveBorderForElement extends ResponsivePropertyForElement{
    protected function get_property()
    {
        return "border";
    }
}
class ResponsiveBorderBottomForElement extends ResponsivePropertyForElement{
    protected function get_property()
    {
        return "border-bottom";
    }
}
class ResponsiveBorderTopForElement extends ResponsivePropertyForElement{
    protected function get_property()
    {
        return "border-top";
    }
}
class ResponsiveBorderRightForElement extends ResponsivePropertyForElement{
    protected function get_property()
    {
        return "border-right";
    }
}
class ResponsiveBorderLeftForElement extends ResponsivePropertyForElement{
    protected function get_property()
    {
        return "border-left";
    }
}
class ResponsiveBorderBottomWidthForElement extends ResponsivePropertyForElement{
    protected function get_property()
    {
        return "border-bottom-width";
    }
}
class ResponsiveBorderTopWidthForElement extends ResponsivePropertyForElement{
    protected function get_property()
    {
        return "border-top-width";
    }
}
class ResponsiveBorderRightWidthForElement extends ResponsivePropertyForElement{
    protected function get_property()
    {
        return "border-right-width";
    }
}
class ResponsiveBorderLeftWidthForElement extends ResponsivePropertyForElement{
    protected function get_property()
    {
        return "border-left-width";
    }
}

class ResponsivePropertiesForElement{
    private $width;
    private $margin;
    
    public function width(){
        return $this->width;
    }

    public function margin(){
        return $this->margin;
    }

    public function __construct($css_element)
    {
        $this->width = (new ResponsiveValuesForCSSProperty());
        $this->margin = (new ResponsiveValuesForCSSProperty());
        
        $this->width->setCSSElement($css_element);
        $this->margin->setCSSElement($css_element);
        
        new ResponsiveWidthForElement($this->width);
        new ResponsiveMarginForElement($this->margin);
    }
}
class ResponsivePropertiesForClass extends ResponsivePropertiesForElement{
    public function __construct($className)
    {
        parent::__construct(CSSElementOfClass($className));
    }
}

