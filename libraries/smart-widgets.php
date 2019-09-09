<?php
/*

CROSS-APPLICATION CLASSES FOR RENDERING GUI

You can use them to build GUI modules that render
application data into a user interface

*/

//@@@@@@@@@@@@@@@@@@@@@@@@@@ MEDIA QUERY RELATED @@@@@@@@@@@@@@@@@@@@@@@@@222222222@@@@@@@@@@
/** THE MEDIA QUERY SUB-PROJECT
 *THE GOAL:
 *=======
 *The goal of this SUB project is to provide a means of building media queries
 *straight from an html component
 *i.e when i create an html element, i shd be able to specify
 *how it displays at various screen sizes and orientation
 *For instance, there are many cases where you might want to hide a component below a given width
 * e.g. you might want to hide the list of categories below a given screen width
 * and only display it above a given width.
 *
 * USE CASES
 * ---------
 * -hiding components below a given screen width
 * -displaying additional components above a given width or height
 *
 */

//get list of rules to apply


class StylesSpecsForSelector{
    private $selector;
    private $property_settings;
    public function __construct($selector_as_string)
    {
        $this->selector = $selector_as_string;
        $this->property_settings = new CSSStyleSpecs();
    }
    public function set_style($property, $value){
        $this->property_settings->set($property,$value);
        return $this;
    }
    public function selector(){
        return $this->selector;
    }
    public function property_settings(){
        return $this->property_settings;
    }

    public function background_color($value){
        return $this->set_style('background-color',$value);
    }
    public function background_image($value){
        return $this->set_style('background-image',$value);
    }
    public function background_image_url($value){
        return $this->set_style('background-image',"url($value)");
    }

    public function background_repeat($value){
        return $this->set_style('background-repeat',$value);
    }
    public function background_repeat_no_repeat(){
        return $this->background_repeat('no-repeat');
    }
    public function background_position($value){
        return $this->set_style('background-position',$value);
    }
    public function color($value){
        return $this->set_style('color',$value);
    }
    public function color_brown(){
        return $this->color("brown");
    }
    public function color_chocolate(){
        return $this->color("chocolate");
    }
    public function color_crimson(){
        return $this->color("crimson");
    }
    public function color_darkcyan(){
        return $this->color("darkcyan");
    }
    public function color_darkgray(){
        return $this->color("darkgray");
    }
    public function color_darkgreen(){
        return $this->color("darkgreen");
    }
    public function color_darkmagenta(){
        return $this->color("darkmagenta");
    }
    public function color_darkolivegreen(){
        return $this->color("darkolivegreen");
    }
    public function color_darkorange(){
        return $this->color("darkorange");
    }
    public function color_firebrick(){
        return $this->color("firebrick");
    }
    public function color_forestgreen(){
        return $this->color("forestgreen");
    }
    public function color_goldenrod(){
        return $this->color("goldenrod");
    }
    public function color_indianred(){
        return $this->color("indianred");
    }
    public function color_mediumorchid(){
        return $this->color("mediumorchid");
    }
    public function color_mediumpurple(){
        return $this->color("mediumpurple");
    }
    public function color_mediumseagreen(){
        return $this->color("mediumseagreen");
    }
    public function color_mediumslateblue(){
        return $this->color("mediumslateblue");
    }
    public function color_olivedrab(){
        return $this->color("olivedrab");
    }
    public function color_gray(){
        return $this->color("gray");
    }
    public function color_peru(){
        return $this->color("peru");
    }
    public function color_siena(){
        return $this->set_style('color',"siena");
    }
    public function color_slategray(){
        return $this->color("slategray");
    }
    public function color_teal(){
        return $this->color("teal");
    }
    public function color_tomato(){
        return $this->color("tomato");
    }

    public function padding($value){
        return $this->set_style('padding',$value);
    }
    public function padding_top($value){
        return $this->set_style('padding-top',$value);
    }
    public function padding_bottom($value){
        return $this->set_style('padding-bottom',$value);
    }
    public function padding_left($value){
        return $this->set_style('padding-left',$value);
    }
    public function padding_right($value){
        return $this->set_style('padding-right',$value);
    }
    public function margin($value){
        return $this->set_style('margin',$value);
    }
    public function margin_top($value){
        return $this->set_style('margin-top',$value);
    }
    public function margin_bottom($value){
        return $this->set_style('margin-bottom',$value);
    }
    public function margin_left($value){
        return $this->set_style('margin-left',$value);
    }
    public function margin_right($value){
        return $this->set_style('margin-right',$value);
    }
    public function border($value){
        return $this->set_style('border',$value);
    }
    public function border_width($value){
        return $this->set_style('border-width',$value);
    }
    public function border_color($value){
        return $this->set_style('border-color',$value);
    }

    public function border_top($value){
        return $this->set_style('border-top',$value);
    }
    public function border_top_width($value){
        return $this->set_style('border-top-width',$value);
    }
    public function border_top_color($value){
        return $this->set_style('border-top-color',$value);
    }

    public function border_bottom($value){
        return $this->set_style('border-bottom',$value);
    }
    public function border_bottom_width($value){
        return $this->set_style('border-bottom-width',$value);
    }
    public function border_bottom_color($value){
        return $this->set_style('border-bottom-color',$value);
    }


    public function border_left($value){
        return $this->set_style('border-left',$value);
    }
    public function border_left_width($value){
        return $this->set_style('border-left-width',$value);
    }
    public function border_left_color($value){
        return $this->set_style('border-left-color',$value);
    }


    public function border_right($value){
        return $this->set_style('border-right',$value);
    }
    public function border_right_width($value){
        return $this->set_style('border-right-width',$value);
    }
    public function border_right_color($value){
        return $this->set_style('border-right-color',$value);
    }

    public function border_zero(){
        return $this->border('0');
    }

    public function position($value){
        return $this->set_style('position',$value);
    }
    public function postion_fixed(){
        return $this->position('fixed');
    }
    public function postion_absolute(){
        return $this->position('absolute');
    }
    public function position_relative(){
        return $this->position('relative');
    }

    public function left($value){
        return $this->set_style('left',$value);
    }
    public function top($value){
        return $this->set_style('top',$value);
    }
    public function width($value){
        return $this->set_style('width',$value);
    }
    public function min_width($value){
        return $this->set_style('min-width',$value);
    }
    public function min_height($value){
        return $this->set_style('min-height',$value);
    }
    public function max_width($value){
        return $this->set_style('max-width',$value);
    }
    public function max_height($value){
        return $this->set_style('max-height',$value);
    }

    public function width_100percent(){
        return $this->width('100%');
    }
    public function height($value){
        return $this->set_style('height',$value);
    }
    public function height_100percent(){
        return $this->height('100%');
    }


    public function float($value){
        return $this->set_style('float',$value);
    }
    public function float_left(){
        return $this->float('left');
    }
    public function float_right(){
        return $this->float('right');
    }
    public function float_none(){
        return $this->float('none');
    }
    public function clear($value){
        return $this->set_style('clear',$value);
    }
    public function clear_left(){
        return $this->clear('left');
    }
    public function clear_right(){
        return $this->clear('right');
    }
    public function clear_both(){
        return $this->clear('both');
    }

    public function text_align($value){
        return $this->set_style('text-align',$value);
    }
    public function text_align_left(){
        return $this->text_align('left');
    }
    public function text_align_middle(){
        return $this->text_align('middle');
    }
    public function text_align_right(){
        return $this->text_align('right');
    }
    public function text_decoration($value){
        return $this->set_style('text-decoration',$value);
    }
    public function text_decoration_none(){
        return $this->text_decoration('none');
    }
    public function vertical_align($value){
        return $this->set_style('vertical-align',$value);
    }
    public function vertical_align_top(){
        return $this->vertical_align('top');
    }
    public function vertical_align_middle(){
        return $this->vertical_align('middle');
    }
    public function vertical_align_bottom(){
        return $this->vertical_align('bottom');
    }
    public function opacity($value){
        return $this->set_style('opacity',$value);
    }
    public function z_index($value){
        return $this->set_style('z-index',$value);
    }
    public function display($value){
        return $this->set_style('display',$value);
    }
    public function display_block(){
        return $this->display('block');
    }
    public function display_inline_block(){
        return $this->display('inline-block');
    }
    public function display_inline(){
        return $this->display('inline');
    }
    public function display_none(){
        return $this->display('none');
    }
    public function visibility($value){
        return $this->set_style('visibility',$value);
    }
    public function visibility_visible(){
        return $this->visibility('visible');
    }
    public function visibility_hidden(){
        return $this->visibility('hidden');
    }

    public function font_family($value){
        return $this->set_style('font-family',$value);
    }
    public function font_family_monospace(){
        return $this->font_family('monospace');
    }
    public function font_family_sans_serif(){
        return $this->font_family('san_serif');
    }
    public function font_family_serif(){
        return $this->font_family('serif');
    }
    public function font_size($value){
        return $this->set_style('font-size',$value);
    }
    public function font_size_100percent($value){
        return $this->font_size('100%');
    }
    public function font_weight($value){
        return $this->set_style('font-weight',$value);
    }
    public function font_weight_normal(){
        return $this->font_weight('normal');
    }
    public function font_weight_bold(){
        return $this->font_weight('bold');
    }
    public function font_style($value){
        return $this->set_style('font-style',$value);
    }
    public function font_variant($value){
        return $this->set_style('font-variant',$value);
    }
    public function line_height($value){
        return $this->set_style('line-height',$value);
    }
    //#list related
    public function list_style($value){
        return $this->set_style('list-style',$value);
    }
    public function list_style_none(){
        return $this->list_style('none');
    }

    public function overflow($value){
        return $this->set_style('overflow',$value);
    }
    public function overflow_hidden(){
        return $this->overflow('hidden');
    }
    public function overflow_visible(){
        return $this->overflow('visible');
    }
    public function oveflow_scroll(){
        return $this->overflow('scroll');
    }

    public function overflow_y($value){
        return $this->set_style('overflow-y',$value);
    }
    public function overflow_y_hidden(){
        return $this->overflow_y('hidden');
    }
    public function overflow_y_visible(){
        return $this->overflow_y('visible');
    }
    public function oveflow_y_scroll(){
        return $this->overflow_y('scroll');
    }

    public function overflow_x($value){
        return $this->set_style('overflow-x',$value);
    }
    public function overflow_x_hidden(){
        return $this->overflow_x('hidden');
    }
    public function overflow_x_visible(){
        return $this->overflow_x('visible');
    }
    public function overflow_x_scroll(){
        return $this->overflow_x('scroll');
    }


    public function zoom($value){
        return $this->set_style('zoom',$value);
    }
    public function cursor($value){
        return $this->set_style('cursor',$value);
    }
    public function cursor_pointer(){
        return $this->cursor('pointer');
    }
}

class MediaQueriesForSmartHtmlElement{
    private $media_queries_for_element = array();

    public function add($media_query){
        //todo: type check
        $this->media_queries_for_element[] = $media_query;
        return $this;
    }
    public function __toString()
    {
        //get style_tag
        return join(" ",$this->media_queries_for_element);
    }
    public static function instance(){
        return new self();
    }
    public function toStyleTag(){
        $tag = new SmartStyleTag();
        $tag->add_child($this->__toString());
        return $tag;
    }
}

//@@@@@@@@@@@@@@@@@@@@@@@@ basic behavior of all html elements @@@@@@@@@@22
abstract class SmartHTMLElement
{
    //---------- microdata - ITEM TYPES
    public function itemtype($value){
        $url = sprintf("http://schema.org/%s",$value);
        $this->set_attribute(__FUNCTION__,$url);
        return $this;
    }
    public function itemtypePerson(){
        return $this->itemtype("Person");
    }
    //----------- ITEM PROPS
    public function itemprop($value){
        $this->set_attribute(__FUNCTION__,$value);
        return $this;
    }
    public function itemref($value){
        $this->set_attribute(__FUNCTION__,$value);
        return $this;
    }
    public function itemid($value){
        $this->set_attribute(__FUNCTION__,$value);
        return $this;
    }
    //===================================================
    public function toLink($url){
        $link = new SmartLink();
        $link->add_child($this);
        $link->set_href($url);
        return $link;
    }
    public function asLink($url){
        return $this->toLink($url);
    }
    public function toDiv(){
        $element = new SmartDiv();
        $element->add_child($this);        
        return $element;
    }
    public function toSpan(){
        $element = new SmartSpan();
        $element->add_child($this);
        return $element;
    }
    //---media query related
    private $media_queries;
    private function mediaQueries(){
        if(!$this->media_queries){
            $this->media_queries = new MediaQueriesForSmartHtmlElement();
        }
        return $this->media_queries;
    }
    public function add_media_query($css_media_query_revised_edition){
        $this->mediaQueries()->add($css_media_query_revised_edition);
        return $this;
    }
    /** @return SmartLink */
    public function on_click_goto($dest_url)
    {
        $smartLink = new SmartLink();
        $smartLink->set_href($dest_url);
        $smartLink->add_child($this);
        return $smartLink;
    }
    /** @return SmartListOfHtmlElements */
    public function repeatNtimes($num_times){
        $list_of_html_elements = new SmartListOfHtmlElements();
        if(!is_numeric($num_times)){
            throw new Exception("expects a positive number for number of times to repeat");
        }
        $total_times = max(0,intval($num_times));
        for($i = 0; $i < $total_times;$i++){
            $list_of_html_elements->add($this);
        }
        return $list_of_html_elements;
    }
  
    //============== THE ORIGINAL CODE ===
    
    private $tag_name = 'span';
    private $arr_classes = array();
    private $arr_styles = array();
    private $arr_attributes = array();

    public static function line_break()
    {
        return new SmartLineBreak();
    }

    abstract protected function create_html_string($tag, $class_string, $style_string, $inner_html, $attribute_string);

    protected function get_class_string()
    {
        if (count($this->arr_classes) < 1) {
            return "";
        }
        $classes = join(" ", $this->arr_classes);
        return "class='{$classes}'";
    }

    protected function get_style_string()
    {
        if (count($this->arr_styles) < 1) {
            return "";
        }

        $result = '';
        $sep = "";
        foreach ($this->arr_styles as $property => $value) {
            $style_string = "{$sep}{$property}:{$value}";
            $result .= $style_string;
            $sep = ";";
        }
        return "style='{$result}'";
    }

    protected function get_attribute_string()
    {

        if (count($this->arr_attributes) < 1) {
            return "";
        }

        $result = '';
        $sep = "";
        foreach ($this->arr_attributes as $property => $value) {
            $property = htmlspecialchars($property);

            //escape any guotes in the attribute
            //$value = htmlspecialchars($value);
            $value = str_replace("'","&#39;",$value);
            $value = str_replace("\"","&quot;",$value);

            $key_value_string = "{$sep}{$property}='{$value}'";
            $result .= $key_value_string;
            $sep = " ";
        }
        return $result;
    }

    protected function get_inner_html()
    {
        $inner_html = '';
        if (isset($this->inner_html)) {
            $inner_html = $this->inner_html;
        }
        return $inner_html;
    }

    protected function get_tag_name()
    {
        return $this->tag_name;
    }

    /** @return SmartHTMLElement */
    public function add_class($class_string)
    {
        if(is_string($class_string) && strlen(trim($class_string)) > 0){
            $class_string = trim($class_string);
            $this->arr_classes[] = $class_string;
        }
        return $this;
    }
    public function add_class_if($condition,$class_string){
        if($condition){
           $this->add_class($class_string);
        }
        return $this;
    }

    public function onclick($content){
        $this->set_attribute(__FUNCTION__,$content);
        return $this;
    }
    
    /*
    public function default_class_name(){
        return md5(get_class($this));
    }*/

    
    public function set_style($property, $value)
    {
        $value = $this->pre_process_property_value($property, $value);
        $this->arr_styles[$property] = $value;
        return $this;
    }
    public function set_styles($assoc_array){
        if(is_array($assoc_array)){
            foreach($assoc_array as $property=>$value){
                $this->set_style($property,$value);
            }
        }
        return $this;
    }

    private function pre_process_property_value($property, $value)
    {
        return $property == 'width' && is_numeric($value) ? $value . "px" : $value;
    }


    public function set_inner_html($inner_html)
    {
        $this->inner_html = $inner_html;
        return $this;
    }

    protected function set_tag_name($tag_name)
    {
        $this->tag_name = $tag_name;
    }

    public function set_attribute($property, $value)
    {
        $this->arr_attributes[$property] = $value;
        return $this;
    }
    
    public function to_html()
    {
        $tag = htmlspecialchars($this->get_tag_name());
        $class_string = htmlspecialchars($this->get_class_string());
        $style_string = $this->get_style_string();
        $attribute_string = $this->get_attribute_string();
        $inner_html = $this->get_inner_html();
        $html = $this->create_html_string($tag, $class_string, $style_string, $inner_html, $attribute_string);
        return $html;
    }

    private static $enable_auto_display_of_media_queries_near_element = true;
    public static function disable_auto_display_of_media_queries_near_element(){
        self::$enable_auto_display_of_media_queries_near_element = false;
    }
    public function __toString()
    {

        //add default class
        //$this->add_class($this->default_class_name());

        ///modified to allow attaching of media queries
        $html = $this->to_html();
        if($this->media_queries && self::$enable_auto_display_of_media_queries_near_element){
            $html = $this->mediaQueries()->toStyleTag() . $html;
        }
        return $html;
    }

    public function toString()
    {
        return $this->__toString();
    }

    public function set_id($element_id)
    {
        $this->set_attribute('id', $element_id);
        return $this;
    }
    #@@@@@@@@@@@@@@@@ INLINE STYLING SHORTCUTS  @@@@@@@@@@@@@@@@@@@@@@
    public function background_color($value){
        return $this->set_style('background-color',$value);
    }
    public function background_color_inherit(){
        return $this->background_color('inherit');
    }
    public function background_color_white(){
        return $this->background_color('white');
    }
    public function background_image($value){
        return $this->set_style('background-image',$value);
    }
    public function background_image_url($value){
        return $this->set_style('background-image',"url($value)");
    }
    
    public function background_repeat($value){
        return $this->set_style('background-repeat',$value);
    }
    public function background_repeat_no_repeat(){
        return $this->background_repeat('no-repeat');
    }
    public function background_position($value){
        return $this->set_style('background-position',$value);
    }
    public function background_position_center(){
        return $this->background_position('center');
    }
    public function background_position_left(){
        return $this->background_position('left');
    }
    public function background_position_right(){
        return $this->background_position('right');
    }
    public function background_position_top(){
        return $this->background_position('top');
    }
    public function background_position_bottom(){
        return $this->background_position('bottom');
    }

    
    public function color($value){
        return $this->set_style('color',$value);
    }
    public function color_inherit(){
        return $this->color("inherit");
    }
    public function color_brown(){
        return $this->color("brown");
    }
    public function color_chocolate(){
        return $this->color("chocolate");
    }
    public function color_crimson(){
        return $this->color("crimson");
    }
    public function color_darkcyan(){
        return $this->color("darkcyan");
    }
    public function color_darkgray(){
        return $this->color("darkgray");
    }
    public function color_darkgreen(){
        return $this->color("darkgreen");
    }
    public function color_darkmagenta(){
        return $this->color("darkmagenta");
    }
    public function color_darkolivegreen(){
        return $this->color("darkolivegreen");
    }
    public function color_darkorange(){
        return $this->color("darkorange");
    }
    public function color_firebrick(){
        return $this->color("firebrick");
    }
    public function color_forestgreen(){
        return $this->color("forestgreen");
    }
    public function color_goldenrod(){
        return $this->color("goldenrod");
    }
    public function color_indianred(){
        return $this->color("indianred");
    }
    public function color_mediumorchid(){
        return $this->color("mediumorchid");
    }
    public function color_mediumpurple(){
        return $this->color("mediumpurple");
    }
    public function color_mediumseagreen(){
        return $this->color("mediumseagreen");
    }
    public function color_mediumslateblue(){
        return $this->color("mediumslateblue");
    }
    public function color_olivedrab(){
        return $this->color("olivedrab");
    }
    public function color_gray(){
        return $this->color("gray");
    }
    public function color_peru(){
        return $this->color("peru");
    }
    public function color_siena(){
        return $this->set_style('color',"siena");
    }
    public function color_slategray(){
        return $this->color("slategray");
    }
    public function color_teal(){
        return $this->color("teal");
    }
    public function color_tomato(){
        return $this->color("tomato");
    }
    
    public function padding($value){
        return $this->set_style('padding',$value);
    }
    public function padding_top($value){
        return $this->set_style('padding-top',$value);
    }
    public function padding_bottom($value){
        return $this->set_style('padding-bottom',$value);
    }
    public function padding_left($value){
        return $this->set_style('padding-left',$value);
    }
    public function padding_right($value){
        return $this->set_style('padding-right',$value);
    }
    public function margin($value){
        return $this->set_style('margin',$value);
    }
    public function margin_auto(){
        return $this->set_style('margin',"auto");
    }
    public function margin_top($value){
        return $this->set_style('margin-top',$value);
    }
    public function margin_bottom($value){
        return $this->set_style('margin-bottom',$value);
    }
    public function margin_left($value){
        return $this->set_style('margin-left',$value);
    }
    public function margin_right($value){
        return $this->set_style('margin-right',$value);
    }
    public function border($value){
        return $this->set_style('border',$value);
    }
    public function border_radius($value){
        return $this->set_style('border-radius',$value);
    }
    public function border_width($value){
        return $this->set_style('border-width',$value);
    }
    public function border_color($value){
        return $this->set_style('border-color',$value);
    }
    public function border_style($value){
        return $this->set_style('border-style',$value);
    }
    public function border_style_solid(){
        return $this->border_style('solid');
    }
    
    public function border_top($value){
        return $this->set_style('border-top',$value);
    }
    public function border_top_width($value){
        return $this->set_style('border-top-width',$value);
    }
    public function border_top_color($value){
        return $this->set_style('border-top-color',$value);
    }
    public function border_top_style($value){
        return $this->set_style('border-top-style',$value);
    }
    public function border_top_style_solid(){
        return $this->border_top_style("solid");
    }
    
    public function border_bottom($value){
        return $this->set_style('border-bottom',$value);
    }
    public function border_bottom_width($value){
        return $this->set_style('border-bottom-width',$value);
    }
    public function border_bottom_color($value){
        return $this->set_style('border-bottom-color',$value);
    }
    public function border_bottom_style($value){
        return $this->set_style('border-bottom-style',$value);
    }
    public function border_bottom_style_solid(){
        return $this->border_bottom_style("solid");
    }
    
    
    public function border_left($value){
        return $this->set_style('border-left',$value);
    }
    public function border_left_width($value){
        return $this->set_style('border-left-width',$value);
    }
    public function border_left_color($value){
        return $this->set_style('border-left-color',$value);
    }
    public function border_left_style($value){
        return $this->set_style('border-left-style',$value);
    }
    public function border_left_style_solid(){
        return $this->border_left_style("solid");
    }
    
    
    public function border_right($value){
        return $this->set_style('border-right',$value);
    }
    public function border_right_width($value){
        return $this->set_style('border-right-width',$value);
    }
    public function border_right_color($value){
        return $this->set_style('border-right-color',$value);
    }
    public function border_right_style($value){
        return $this->set_style('border-right-style',$value);
    }
    public function border_right_style_solid(){
        return $this->border_right_style("solid");
    }
    
    public function border_zero(){
        return $this->border('0');
    }

    public function position($value){
        return $this->set_style('position',$value);
    }
    public function position_fixed(){
        return $this->position('fixed');
    }
    public function position_absolute(){
        return $this->position('absolute');
    }
    public function position_relative(){
        return $this->position('relative');
    }
    
    public function left($value){
        return $this->set_style('left',$value);
    }
    public function top($value){
        return $this->set_style('top',$value);
    }
    public function right($value){
        return $this->set_style('right',$value);
    }
    public function bottom($value){
        return $this->set_style('bottom',$value);
    }
    public function width($value){
        return $this->set_style('width',$value);
    }
    public function width_auto(){
        return $this->width('auto');
    }
    public function min_width($value){
        return $this->set_style('min-width',$value);
    }
    public function min_height($value){
        return $this->set_style('min-height',$value);
    }
    public function max_width($value){
        return $this->set_style('max-width',$value);
    }
    public function max_height($value){
        return $this->set_style('max-height',$value);
    }
    
    public function width_100percent(){
        return $this->width('100%');
    }
    public function height($value){
        return $this->set_style('height',$value);
    }
    public function height_100percent(){
        return $this->height('100%');
    }
    

    public function float($value){
        return $this->set_style('float',$value);
    }
    public function float_left(){
        return $this->float('left');
    }
    public function float_right(){
        return $this->float('right');
    }
    public function float_none(){
        return $this->float('none');
    }
    public function clear($value){
        return $this->set_style('clear',$value);
    }
    public function clear_left(){
        return $this->clear('left');
    }
    public function clear_right(){
        return $this->clear('right');
    }
    public function clear_both(){
        return $this->clear('both');
    }
    
    public function text_align($value){
        return $this->set_style('text-align',$value);
    }
    public function text_align_left(){
        return $this->text_align('left');
    }
    public function text_align_center(){
        return $this->text_align('center');
    }
    public function text_align_right(){
        return $this->text_align('right');
    }
    public function text_decoration($value){
        return $this->set_style('text-decoration',$value);
    }
    public function text_decoration_none(){
        return $this->text_decoration('none');
    }
    public function text_decoration_underline(){
        return $this->text_decoration('underline');
    }
    public function vertical_align($value){
        return $this->set_style('vertical-align',$value);
    }    
    public function vertical_align_top(){
        return $this->vertical_align('top');
    }
    public function vertical_align_middle(){
        return $this->vertical_align('middle');
    }
    public function vertical_align_bottom(){
        return $this->vertical_align('bottom');
    }
    public function vertical_align_baseline(){
        return $this->vertical_align('baseline');
    }
    public function opacity($value){
        return $this->set_style('opacity',$value);
    }
    public function z_index($value){
        return $this->set_style('z-index',$value);
    }
    public function display($value){
        return $this->set_style('display',$value);
    }
    public function display_block(){
        return $this->display('block');
    }
    public function display_inline_block(){
        return $this->display('inline-block');
    }
    public function display_inline(){
        return $this->display('inline');
    }
    public function display_none(){
        return $this->display('none');
    }
    public function visibility($value){
        return $this->set_style('visibility',$value);
    }
    public function visibility_visible(){
        return $this->visibility('visible');
    }
    public function visibility_hidden(){
        return $this->visibility('hidden');
    }

    public function font_family($value){
        return $this->set_style('font-family',$value);
    }
    public function font_family_monospace(){
        return $this->font_family('monospace');
    }
    public function font_family_sans_serif(){
        return $this->font_family('san_serif');
    }
    public function font_family_serif(){
        return $this->font_family('serif');
    }
    public function font_size($value){
        return $this->set_style('font-size',$value);
    }
    public function font_size_100percent(){
        return $this->font_size('100%');
    }
    public function font_weight($value){
        return $this->set_style('font-weight',$value);
    }
    public function font_weight_normal(){
        return $this->font_weight('normal');
    }
    public function font_weight_bold(){
        return $this->font_weight('bold');
    }
    public function font_style($value){
        return $this->set_style('font-style',$value);
    }
    public function font_variant($value){
        return $this->set_style('font-variant',$value);
    }        
    public function line_height($value){
        return $this->set_style('line-height',$value);
    }    
    //#list related
    public function list_style($value){
        return $this->set_style('list-style',$value);
    }
    public function list_style_none(){
        return $this->list_style('none');
    }
    public function list_style_position($value){
        return $this->set_style('list-style-position',$value);
    }
    public function list_style_position_outside(){
        return $this->list_style_position('outside');
    }
    public function list_style_position_inside(){
        return $this->list_style_position('inside');
    }
    public function list_style_image($value){
        return $this->set_style('list-style-image',$value);
    }
    public function list_style_image_url($url){
        return $this->list_style_image(sprintf('url(%s)',$url));
    }
    public function background($value){
        return $this->set_style('background',$value);
    }
    public function linear_gradient($angle,$color1,$color2){
        return $this->background(sprintf('linear-gradient(%s,%s,%s)',$angle,$color1,$color2));
    }

    public function overflow($value){
        return $this->set_style('overflow',$value);
    }
    public function overflow_hidden(){
        return $this->overflow('hidden');
    }
    public function overflow_visible(){
        return $this->overflow('visible');
    }
    public function oveflow_scroll(){
        return $this->overflow('scroll');
    }

    public function overflow_y($value){
        return $this->set_style('overflow-y',$value);
    }
    public function overflow_y_hidden(){
        return $this->overflow_y('hidden');
    }
    public function overflow_y_visible(){
        return $this->overflow_y('visible');
    }
    public function oveflow_y_scroll(){
        return $this->overflow_y('scroll');
    }

    public function overflow_x($value){
        return $this->set_style('overflow-x',$value);
    }
    public function overflow_x_hidden(){
        return $this->overflow_x('hidden');
    }
    public function overflow_x_visible(){
        return $this->overflow_x('visible');
    }
    public function overflow_x_scroll(){
        return $this->overflow_x('scroll');
    }
        

    public function zoom($value){
        return $this->set_style('zoom',$value);
    }
    public function cursor($value){
        return $this->set_style('cursor',$value);
    }
    public function cursor_pointer(){
        return $this->cursor('pointer');
    }

    public function box_shadow($value)
    {
        return $this->set_style('box-shadow',$value);
    }

    public function background_size($value)
    {
        return $this->set_style('background-size',$value);
    }
    public function background_size_cover()
    {
        return $this->background_size("cover");
    }
    public function background_size_contain()
    {
        return $this->background_size("contain");
    }

    public function background_clip($value)
    {
        return $this->set_style('background-clip',$value);
    }
    public function background_clip_padding_box()
    {
        return $this->background_clip("padding-box");
    }
    public function background_clip_content_box()
    {
        return $this->background_clip("content-box");
    }
    public function background_clip_border_box()
    {
        return $this->background_clip("border-box");
    }

    public function background_origin($value)
    {
        return $this->set_style('background-origin',$value);
    }
    public function background_origin_padding_box()
    {
        return $this->background_origin("padding-box");
    }
    public function background_origin_content_box()
    {
        return $this->background_origin("content-box");
    }
    public function background_origin_border_box()
    {
        return $this->background_origin("border-box");
    }

    public function white_space($value)
    {
        return $this->set_style("white-space",$value);
    }
    public function white_space_nowrap()
    {
        return $this->white_space("nowrap");
    }
    public function text_overflow($value)
    {
        return $this->set_style("text-overflow",$value);
    }
    public function text_overflow_ellipsis()
    {
        return $this->text_overflow("ellipsis");
    }

    public function line_height_initial()
    {
        return $this->line_height("initial");
    }


}

//@@@@@@@@@@@@@@@@@@@@@@@@@@@ 2 VARIANTS OF HTML ELEMENTS
abstract class SmartNonReplacedHTMLElement extends SmartHTMLElement
{
    protected function create_html_string($tag, $class_string, $style_string, $inner_html, $attribute_string)
    {
        $additional_children = join("", $this->children);
        $inner_html .= $additional_children;
        //-------------------
        $html = "<$tag {$class_string} {$style_string} {$attribute_string} >{$inner_html}</$tag>";
        return $html;
    }

    private $children = array();
    
    public function hasChildren(){
        return count($this->children) > 0 || trim($this->get_inner_html());
    }

    public function add_child($smart_element)
    {
        $this->children[] = $smart_element . "";
        return $this;
    }
    public function add_child_if($condition,$smart_element)
    {
        if($condition){
            return $this->add_child($smart_element);
        }
        return $this;        
    }

    public function add_child_if_else($condition, $to_add_if_true, $to_add_if_false)
    {
        if($condition){
            return $this->add_child($to_add_if_true);
        }
        else{
            return $this->add_child($to_add_if_false);
        }        
    }

    public function add_javascript($inner_content)
    {
        $script = new SmartJavascriptTag();
        $script->set_inner_html($inner_content);
        $this->add_child($script);
        return $this;
    }
    public function add_javascript_from_file($file_name){
        $file_content = file_get_contents($file_name);
        $this->add_javascript($file_content);
        return $this;
    }

    public function set_inner_html($inner_html)
    {
        $this->children = array();
        parent::set_inner_html($inner_html);
        return $this;
    }

}

abstract class SmartReplacedHTMLElement extends SmartHTMLElement
{

    protected function create_html_string($tag, $class_string, $style_string, $inner_html, $attribute_string)
    {
        $html = "<$tag {$class_string} {$style_string} {$attribute_string} />";
        return $html;
    }

}

//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ COMMON HTML ELEMENTS @@@@@@@@@@@@@@@@@@
class SmartSpan extends SmartNonReplacedHTMLElement
{
    public function __construct()
    {
        $this->set_tag_name("span");
    }
}

class SmartOption extends SmartNonReplacedHTMLElement
{
    public function __construct()
    {
        $this->set_tag_name("option");
    }

    public function set_value($value)
    {
        $this->set_attribute('value', $value);
        return $this;
    }
}

class SmartSelect extends SmartNonReplacedHTMLElement
{
    public function __construct()
    {
        $this->set_tag_name("select");
    }

    public function set_name($name)
    {
        $this->set_attribute('name', $name);
        return $this;
    }

    public function add_option($label, $value,$option_class ='')
    {
        $option = new SmartOption();
        $option->set_value($value);
        $option->set_inner_html($label);
        if($option_class){
            $option->add_class($option_class);
        }
        $this->add_child($option);
        return $this;
    }
}

//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@2
class SmartListItem extends SmartNonReplacedHTMLElement
{
    public function __construct()
    {
        $this->set_tag_name("li");
    }

}

class SmartList extends SmartNonReplacedHTMLElement
{
    public function __construct()
    {
        $this->set_list_type_unordered();
    }

    public function set_list_type_ordered()
    {
        $this->set_tag_name("ol");
        return $this;
    }

    public function set_list_type_unordered()
    {
        $this->set_tag_name("ul");
        return $this;
    }


    public function add_list_item($content)
    {
        $this->add_child(
            (new SmartListItem())->set_inner_html($content)
        );
        return $this;
    }
}


//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@2222
class SmartCustomTag extends SmartNonReplacedHTMLElement
{
    public function __construct($name)
    {
        $this->set_tag_name($name);
    }
}

class SmartDiv extends SmartNonReplacedHTMLElement
{
    public function __construct()
    {
        $this->set_tag_name("div");
    }
}
class SmartParagraph extends SmartNonReplacedHTMLElement
{
    public function __construct()
    {
        $this->set_tag_name("p");
    }
}
class SmartHeading1 extends SmartNonReplacedHTMLElement
{
    public function __construct()
    {
        $this->set_tag_name("h1");
    }
}
class SmartHeading2 extends SmartNonReplacedHTMLElement
{
    public function __construct()
    {
        $this->set_tag_name("h2");
    }
}
class SmartHeading3 extends SmartNonReplacedHTMLElement
{
    public function __construct()
    {
        $this->set_tag_name("h3");
    }
}
class SmartHeading4 extends SmartNonReplacedHTMLElement
{
    public function __construct()
    {
        $this->set_tag_name("h4");
    }
}
class SmartHeading5 extends SmartNonReplacedHTMLElement
{
    public function __construct()
    {
        $this->set_tag_name("h5");
    }
}
class SmartHeading6 extends SmartNonReplacedHTMLElement
{
    public function __construct()
    {
        $this->set_tag_name("h6");
    }
}


class SmartTextarea extends SmartNonReplacedHTMLElement
{
    public function __construct()
    {
        $this->set_tag_name("textarea");
    }

    public function set_name($name)
    {
        $this->set_attribute('name', $name);
        return $this;
    }
}


class SmartLink extends SmartNonReplacedHTMLElement
{
    public function __construct()
    {
        $this->set_tag_name("a");
    }

    public function set_href($href)
    {
        $this->set_attribute('href', $href);
        return $this;
    }

}

class SmartDynamicLink
{
    private $arr_link_parameters = array();
    private $url;
    private $link = null;
    public static function create(){
        return new self();
    }
    public function __construct()
    {
        $this->link = new SmartLink();
    }
    public function inner_link(){
        return $this->link;
    }

    public function set_url($url)
    {
        $this->url = $url;
        return $this;
    }
    public function url(){
        return $this->url;
    }

    public function set_parameter($property, $value)
    {
        $this->arr_link_parameters[$property] = $value;
        return $this;
    }

    private function get_href()
    {
        $href = $this->url;
        if (is_array($this->arr_link_parameters) &&
            count($this->arr_link_parameters)
        ) {
            $href .= "?";
            $sep = "";
            foreach ($this->arr_link_parameters as $property => $value) {
                $href .= join("", array($sep, $property, "=", $value));
                $sep = "&";
            }
        }
        return $href;
    }

    public function __toString()
    {
        $this->link->set_href($this->get_href());
        return $this->link->toString() . "";
    }

    public function toString()
    {
        return $this->__toString();
    }

    public function add_class($class_name)
    {
        $this->link->add_class($class_name);
        return $this;
    }

    public function add_child($child_element)
    {
        $this->link->add_child($child_element);
        return $this;
    }

    public function set_inner_html($inner_html)
    {
        $this->link->set_inner_html($inner_html);
        return $this;
    }

    public function set_style($property, $value)
    {
        $this->link->set_style($property, $value);
        return $this;
    }

    public function set_attribute($property, $value)
    {
        $this->link->set_attribute($property, $value);
        return $this;
    }
}

class SmartForm extends SmartNonReplacedHTMLElement
{
    public function __construct()
    {
        $this->set_tag_name('form');
        $this->set_attribute('enctype', 'multipart/form-data');
        $this->use_post_method();
    }

    public function use_get_method()
    {
        $this->set_attribute('method', 'GET');
        return $this;
    }

    public function use_post_method()
    {
        $this->set_attribute('method', 'POST');
        return $this;
    }

    public function set_url($url)
    {
        $this->set_attribute('action', $url);
        return $this;
    }

}

class SmartTaskForm extends SmartForm
{
    public function add_hidden_input($name, $value)
    {
        $this->add_child(HiddenInput::create()->set_name($name)->set_value($value));
        return $this;
    }

    private $text;

    public function set_text($text)
    {
        $this->text = $text;
        return $this;
    }

    protected function create_html_string($tag, $class_string, $style_string, $inner_html, $attribute_string)
    {
        $this->add_child($this->get_submit_button());
        return parent::create_html_string($tag, $class_string, $style_string, $inner_html, $attribute_string);
    }

    private function get_submit_button()
    {
        $submit_btn = SubmitInput::create()->set_value($this->text);
        $submit_btn->set_styles($this->assoc_submit_btn_styles);
        return $submit_btn;
    }
    private $assoc_submit_btn_styles = array();
    public function add_style_to_submit_button($property,$value){
        $this->assoc_submit_btn_styles[$property] = $value;
        return $this;
    }
}

class SmartLabel extends SmartNonReplacedHTMLElement
{
    public function __construct($content='')
    {
        $this->set_tag_name("label");
        $this->set_inner_html($content);
    }    
}

abstract class SmartInput extends SmartReplacedHTMLElement
{
    public function __construct()
    {
        $this->set_tag_name("input");
    }

    public function disable()
    {
        $this->set_attribute('disabled', 'true');
        return $this;
    }

    public static function create(){

    }

    public function set_type($type)
    {
        $this->set_attribute('type', $type);
        return $this;
    }

    public function set_name($name)
    {
        $this->set_attribute('name', $name);
        return $this;
    }

    public function set_value($value)
    {
        $this->set_attribute('value', $value);
        return $this;
    }
    public function autofocus(){
        $this->set_attribute("autofocus","autofocus");
        return $this;
    }

    public function onchange($content){
        $this->set_attribute(__FUNCTION__,$content);
        return $this;
    }
}

class TextInput extends SmartInput
{
    public function __construct()
    {
        parent::__construct();
        $this->set_type($this->get_type());
    }

    public static function create()
    {
        return new self();
    }

    public function placeholder($string)
    {
        $this->set_attribute("placeholder",$string);
        return $this;
    }

    protected function get_type()
    {
        return 'text';
    }
}

class PasswordInput extends TextInput
{    
    public static function create()
    {
        return new self();
    }
    protected function get_type()
    {
        return "password";
    }
}

class SearchInput extends TextInput
{
    public static function create()
    {
        return new self();
    }
    protected function get_type()
    {
        return "search";
    }
}

class SubmitInput extends SmartInput
{
    public function __construct()
    {
        parent::__construct();
        $this->set_type('submit');
    }

    public static function create()
    {
        return new self();
    }
}

class ButtonInput extends SmartInput
{
    public function __construct()
    {
        parent::__construct();
        $this->set_type('button');
    }

    public static function create()
    {
        return new self();
    }
}

class HiddenInput extends SmartInput
{
    public function __construct()
    {
        parent::__construct();
        $this->set_type('hidden');
    }

    public static function create()
    {
        return new self();
    }
}

class RadioInput extends SmartInput
{
    public function __construct()
    {
        parent::__construct();
        $this->set_type('radio');
    }

    public static function create()
    {
        return new self();
    }
}

class CheckboxInput extends SmartInput
{
    public function __construct()
    {
        parent::__construct();
        $this->set_type('checkbox');
    }

    public static function create()
    {
        return new self();
    }
}


class FileInput extends SmartInput
{
    public function __construct()
    {
        parent::__construct();
        $this->set_type('file');
    }

    public static function create()
    {
        return new self();
    }
}

class RadioSet
{
    private $name = '';
    private $assoc_array_value_and_label = array();

    protected function __construct($name)
    {
        $this->name = $name;
    }

    public static function from_name($name)
    {
        return new RadioSet($name);
    }

    public function add_radio_button($label, $value)
    {
        $this->assoc_array_value_and_label[$value] = $label;
        return $this;
    }

    public function __toString()
    {
        $html = '';
        foreach ($this->assoc_array_value_and_label as $value => $label) {
            $radio_btn = $this->wrap_radio_button(
                RadioInput::create()->set_name($this->name)->set_value($value)->add_class($this->radio_class)->toString()
            );
            $label = $this->wrap_label($label);

            $item = $this->wrap_item(
                $radio_btn . $label
            );
            $html .= $item;
        }
        return $html . "";
    }

    public function toString()
    {
        return $this->__toString();
    }

    protected function wrap_radio_button($radio_button)
    {
        return $radio_button;
    }

    protected function wrap_label($label)
    {
        return $label;
    }

    protected function wrap_item($item_html)
    {
        return $item_html;
    }

    private $radio_class = '';

    public function set_radio_class($radio_class)
    {
        $this->radio_class = $radio_class;
        return $this;
    }

}

class SmartImage extends SmartReplacedHTMLElement
{
    public function __construct($src, $width = '', $height = '')
    {
        $this->set_tag_name("img");
        $this->set_attribute("src", $src);
        if ($width) {
            $this->set_attribute('width', $width);
        }
        if ($height) {
            $this->set_attribute('height', $height);
        }
    }
    public function set_alt($value){
        $this->set_attribute("alt",$value);
        return $this;
    }

}
class SmartLineBreak extends SmartReplacedHTMLElement
{
    public function __construct()
    {
        $this->set_tag_name("br");       
    }    
}

//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
class SmartBlock extends SmartDiv
{
}

class SmartInlineBlock extends SmartSpan
{
    public function __construct()
    {
        parent::__construct();
        $this->add_class(SmartCSSClass::$smt_inline_block);
    }
}

//@@@@@@@@@@@@@@@ PAGE, HTML HEADER, BODY, TITLE, LINK, META
class SmartHTMLTag extends SmartNonReplacedHTMLElement
{
    public function __construct()
    {
        $this->set_tag_name("html");
    }
    public function set_xml_namespace($xmlns){
        $this->set_attribute('xmlns',$xmlns);
        return $this;
    }

    public static function create()
    {
        return new self();
    }
}

class SmartBodyTag extends SmartNonReplacedHTMLElement
{
    public function __construct()
    {
        $this->set_tag_name("body");
    }

    public static function create()
    {
        return new self();
    }
}

class SmartHeaderTag extends SmartNonReplacedHTMLElement
{
    public function __construct()
    {
        $this->set_tag_name("head");
    }

    public static function create()
    {
        return new self();
    }
}

class SmartTitleTag extends SmartNonReplacedHTMLElement
{
    public function __construct()
    {
        $this->set_tag_name("title");
    }

    public static function create()
    {
        return new self();
    }
}

abstract class SmartLinkTag extends SmartReplacedHTMLElement
{
    protected function __construct()
    {
        $this->set_tag_name("link");
    }

    protected function set_relationship($relationship)
    {
        $this->set_attribute('rel', $relationship);
        return $this;
    }

    public function set_type($type)
    {
        $this->set_attribute('type', $type);
        return $this;
    }

    public function set_href($href)
    {
        $this->set_attribute('href', $href);
        return $this;
    }

}

class SmartCSSLink extends SmartLinkTag
{
    public function __construct()
    {
        parent::__construct();
        $this->set_relationship('stylesheet');
        $this->set_type('text/css');
    }

    public static function create()
    {
        return new self();
    }

}
class SmartOtherLink extends SmartLinkTag
{
    public function __construct()
    {
        parent::__construct();
    }
    public function set_relationship($relationship){
        parent::set_relationship($relationship);
        return $this;
    }
    public function set_type($type){
        parent::set_type($type);
        return $this;
    }
    public function set_href($href){
        parent::set_href($href);
        return $this;
    }

    public static function create()
    {
        return new self();
    }

}

abstract class SmartMetaTag extends SmartReplacedHTMLElement
{
    protected function __construct()
    {
        $this->set_tag_name("meta");
    }

    protected function set_http_equivalent($http_equivalent)
    {
        $this->set_attribute('http-equivalent', $http_equivalent);
        return $this;
    }

    protected function set_content($content)
    {
        $this->set_attribute('content', $content);
        return $this;
    }
    protected function set_name($name)
    {
        $this->set_attribute('name', $name);
        return $this;
    }

}
class SmartDescriptionMetaTag extends SmartMetaTag
{
    public function __construct($description)
    {
        parent::__construct();
        $this->set_name("description");
        $this->set_content($description);
    }

}
class SmartKeywordsMetaTag extends SmartMetaTag
{
    public function __construct($keywords)
    {
        parent::__construct();
        $this->set_name("keywords");
        $this->set_content($keywords);
    }

}
class SmartRobotsMetaTag extends SmartMetaTag
{
    public function __construct($value)
    {
        parent::__construct();
        $this->set_name("robots");
        $this->set_content($value);
    }

}
class SmartViewportMetaTag extends SmartMetaTag
{
    public function __construct()
    {
        parent::__construct();
        $this->set_name("viewport");
        $this->set_content("width=device-width, height=device-height, initial-scale=1");
    }

}

class SmartMetadataTag_XUACompatible extends SmartMetaTag
{
    public function __construct()
    {
        parent::__construct();
        $this->set_xua_compatible('');
    }

    public static function create()
    {
        return new self();
    }

    public function set_xua_compatible($content)
    {
        $this->set_http_equivalent('X-UA-Compatible');
        $this->set_content($content);
        return $this;
    }

    public function set_xua_compatible_ie8()
    {
        return $this->set_xua_compatible('IE-8');
    }


}

class SmartMetadataTag_ContentType extends SmartMetaTag
{
    public function __construct()
    {
        parent::__construct();
        $this->set_content_type('');
    }

    public static function create()
    {
        return new self();
    }

    public function set_content_type($content)
    {
        $this->set_http_equivalent('Content-Type');
        $this->set_content($content);
        return $this;
    }

    public function set_content_type_html_utf8()
    {
        return $this->set_content_type('text/html;charset=utf-8');
    }
}

abstract class SmartScriptTag extends SmartNonReplacedHTMLElement
{
    protected function __construct()
    {
        $this->set_tag_name("script");
    }

    protected function set_type($type)
    {
        $this->set_attribute('type', $type);
        return $this;
    }

    public function set_src($src)
    {
        $this->set_attribute('src', $src);
        return $this;
    }

}

class SmartJavascriptTag extends SmartScriptTag
{
    public function __construct()
    {
        parent::__construct();
        $this->set_type('text/javascript');
    }
    public function set_async($async=false){
        $async = $async ? "true" : "false";
        $this->set_attribute("async",$async);
        return $this;
    }

    public static function create()
    {
        return new self();
    }

}

class SmartNoScriptTag extends SmartNonReplacedHTMLElement
{
    public function __construct()
    {
        $this->set_tag_name("noscript");
    }

    public static function create()
    {
        return new self();
    }
}
class SmartStyleTag extends SmartNonReplacedHTMLElement
{
    public function __construct()
    {
        $this->set_tag_name("style");
        $this->set_type_to_text_css();
        $this->set_attribute("rel","stylesheet");
    }
    public function set_type($type){
        $this->set_attribute("type",$type);
        return $this;
    }
    public function set_type_to_text_css(){
        $this->set_attribute("type","text/css");
        return $this;
    }
}

class SmartSvg extends SmartNonReplacedHTMLElement
{
    public function __construct()
    {
        $this->set_tag_name("svg");
    }
}
class SmartRect extends SmartReplacedHTMLElement
{
    public function __construct()
    {
        $this->set_tag_name("rect");
    }
}
class SmartText extends SmartNonReplacedHTMLElement
{
    public function __construct()
    {
        $this->set_tag_name("text");
    }
}
//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
class SmartHTMLPage
{
    private $doc_type_string = '', $head, $body, $title_tag, $metadata_compatible_tag, $metadata_content_type,$html_tag;

    private $metadata_robots = "index, follow", $metadata_keywords = "", $metadata_description = "";
    
    public function set_description($text){
        $this->metadata_description = $text;
        return $this;
    }
    public function set_keywords($text){
        $this->metadata_keywords = $text;
        return $this;
    }

    public function __construct()
    {
        $this->html_tag = SmartHTMLTag::create();
        $this->title_tag = SmartTitleTag::create();
        $this->head = SmartHeaderTag::create();
        $this->body = SmartBodyTag::create();
        //======
        $this->metadata_compatible_tag = SmartMetadataTag_XUACompatible::create()->set_xua_compatible_ie8();
        $this->metadata_content_type = SmartMetadataTag_ContentType::create()->set_content_type_html_utf8();
        $this->set_view_port_to_device_width();

    }
    
    public function body(){
        return $this->body;
    }

    public function set_doc_type_string($doc_type_string)
    {
        $this->doc_type_string = $doc_type_string;
        return $this;
    }

    public function set_xua_compatible_value($value)
    {
        $this->metadata_compatible_tag = SmartMetadataTag_XUACompatible::create()->set_xua_compatible($value);
    }

    public static function create()
    {
        return new self();
    }

    public function set_title($title)
    {
        $this->title_tag->set_inner_html($title);
        return $this;
    }

    protected function add_child_to_head($child_element)
    {
        $this->head->add_child($child_element);
        return $this;
    }
    private function set_view_port_to_device_width()
    {
        $this->head->add_child(new SmartViewportMetaTag());
        return $this;
    }
    public function add_header_link($relationship,$type,$href){
        $this->add_child_to_head(SmartOtherLink::create()
            ->set_relationship($relationship)->set_type($type)->set_href($href));
        return $this;

    }

    public function add_child_to_body($child_element)
    {
        $this->body->add_child($child_element);
        return $this;
    }
    public function add_style_to_body($property,$value)
    {
        $this->body->set_style($property,$value);
        return $this;
    }

    public function add_class_to_body($class)
    {
        $this->body->add_class($class);
        return $this;
    }

    public function add_css_link($href)
    {
        return $this->add_child_to_head(SmartCSSLink::create()->set_href($href));
    }

    public function add_javascript_link_to_head($src,$async=false)
    {
        return $this->add_child_to_head(SmartJavascriptTag::create()->set_src($src)->set_async($async));
    }

    public function add_inline_javascript_to_head($inner_html)
    {
        return $this->add_child_to_head(SmartJavascriptTag::create()->set_inner_html($inner_html));
    }

    public function add_javascript_link_to_body($src,$async=false)
    {
        return $this->add_child_to_body(SmartJavascriptTag::create()->set_src($src)->set_async($async));
    }

    public function add_inline_javascript_to_body($inner_html)
    {
        return $this->add_child_to_body(SmartJavascriptTag::create()->set_inner_html($inner_html));
    }
    public function add_inline_css_to_body($inner_html)
    {
        $tag = new SmartStyleTag();
        $tag->set_type_to_text_css();
        $tag->set_inner_html($inner_html);
        return $this->add_child_to_body($tag);
    }

    public function set_xml_namespace($xmlns){
        $this->html_tag->set_xml_namespace($xmlns);
        return $this;
    }

    public function __toString()
    {
        $this->add_child_to_head($this->title_tag);
        $this->add_child_to_head(new SmartDescriptionMetaTag($this->metadata_description));
        $this->add_child_to_head(new SmartKeywordsMetaTag($this->metadata_keywords));
        $this->add_child_to_head(new SmartRobotsMetaTag($this->metadata_robots));
        $this->add_child_to_head($this->metadata_content_type);
        $this->add_child_to_head($this->metadata_compatible_tag);

        $html_tag = $this->html_tag
            ->add_child($this->head)
            ->add_child($this->body);


        return $this->doc_type_string . $html_tag->toString() . "";
    }

    public function toString()
    {
        return $this->__toString();
    }
}

//@@@@@@@@@@@@@@@@@@@@@@@@@@ CREATOR CLASSES @@@@@@@@@@@@@@@@@22@

abstract class CreateSmartElement
{


    protected static function  __create_blue_background($element, $content)
    {
        $element->set_inner_html($content);
        $element->add_class(SmartCSSClass::$smt_background_color__blue);
        return $element;
    }

    protected static function  __create_wallpost_background($element, $content)
    {
        $element->set_inner_html($content);
        $element->add_class(SmartCSSClass::$smt_background_color__wallpost);
        return $element;
    }

    protected static function  __text_align_center($element, $content)
    {
        $element->set_inner_html($content);
        $element->add_class(SmartCSSClass::$smt_center_aligned);
        return $element;
    }

    protected static function  __text_align_right($element, $content)
    {
        $element->set_inner_html($content);
        $element->add_class(SmartCSSClass::$smt_right_aligned);
        return $element;
    }

    protected static function  __vertical_align_top($element, $content)
    {
        $element->set_inner_html($content);
        $element->add_class(SmartCSSClass::$smt_top_aligned);
        return $element;
    }

    protected static function  __vertical_align_middle($element, $content)
    {
        $element->set_inner_html($content);
        $element->add_class(SmartCSSClass::$smt_middle_aligned);
        return $element;
    }

    protected static function  __create_tooltip_background($element, $content)
    {
        $element->set_inner_html($content);
        $element->add_class(SmartCSSClass::$smt_background_color__tooltip);
        return $element;
    }

    protected static function  __create_white_background($element, $content)
    {
        $element->set_inner_html($content);
        $element->add_class(SmartCSSClass::$smt_background_color__white);
        return $element;
    }

    protected static function  __create_border($element, $content)
    {
        $element->set_inner_html($content);
        $element->add_class(SmartCSSClass::$smt_border);
        return $element;
    }

    protected static function  __create_border_top($element, $content)
    {
        $element->set_inner_html($content);
        $element->add_class(SmartCSSClass::$smt_border__top);
        return $element;
    }

    protected static function  __create_border_bottom($element, $content)
    {
        $element->set_inner_html($content);
        $element->add_class(SmartCSSClass::$smt_border__bottom);
        return $element;
    }

    protected static function  __create_spacer($element, $content)
    {
        $element->set_inner_html($content);
        $element->add_class(SmartCSSClass::$smt_spacer);
        return $element;
    }

    protected static function  __create_horizontal_spacer($element, $content)
    {
        $element->set_inner_html($content);
        $element->add_class(SmartCSSClass::$smt_horizontal_spacer);
        return $element;
    }

    protected static function  __create_blue_header($element, $content)
    {
        $element->set_inner_html($content);
        $element->add_class(SmartCSSClass::$smt_header . " " . SmartCSSClass::$smt_color_blue);
        return $element;
    }

    protected static function  __create_white_header($element, $content)
    {
        $element->set_inner_html($content);
        $element->add_class(SmartCSSClass::$smt_header . " " . SmartCSSClass::$smt_color_white);
        return $element;
    }

    protected static function  __create_black_header($element, $content)
    {
        $element->set_inner_html($content);
        $element->add_class(SmartCSSClass::$smt_header . " " . SmartCSSClass::$smt_color_black);
        return $element;
    }

    protected static function  __create_blue_descriptor($element, $content)
    {
        $element->set_inner_html($content);
        $element->add_class(SmartCSSClass::$smt_descriptor . " " . SmartCSSClass::$smt_color_blue);
        return $element;
    }

    protected static function  __create_paragraph_descriptor($element, $content)
    {
        $element->set_inner_html($content);
        $element->add_class(SmartCSSClass::$smt_descriptor . " " . SmartCSSClass::$smt_color_paragraph);
        return $element;
    }

    protected static function  __create_gray_header($element, $content)
    {
        $element->set_inner_html($content);
        $element->add_class(SmartCSSClass::$smt_header . " " . SmartCSSClass::$smt_color_gray);
        return $element;
    }

    protected static function  __create_dark_gray_header($element, $content)
    {
        $element->set_inner_html($content);
        $element->add_class(SmartCSSClass::$smt_header . " " . SmartCSSClass::$smt_color_dark_gray);
        return $element;
    }

    protected static function  __create_main_descriptor($element, $content)
    {
        $element->set_inner_html($content);
        $element->add_class(SmartCSSClass::$smt_descriptor . " " . SmartCSSClass::$smt_color_main_descriptor);
        return $element;
    }

    protected static function  __create_gray_descriptor($element, $content)
    {
        $element->set_inner_html($content);
        $element->add_class(SmartCSSClass::$smt_descriptor . " " . SmartCSSClass::$smt_color_gray_descriptor);
        return $element;
    }

    protected static function  __create_button_background__gray($element, $content)
    {
        $element->set_inner_html($content);
        $element->add_class(SmartCSSClass::$smt_button_background__gray);
        return $element;
    }

    protected static function  __create_button_background__green($element, $content)
    {
        $element->set_inner_html($content);
        $element->add_class(SmartCSSClass::$smt_button_background__green);
        return $element;
    }

    protected static function  __create_button_border__gray($element, $content)
    {
        $element->set_inner_html($content);
        $element->add_class(SmartCSSClass::$smt_button_border__gray);
        return $element;
    }
}


abstract class BaseCreateBlockElement extends CreateSmartElement
{

}

class CreateInlineElement extends CreateSmartElement
{
    public static function create_base_element()
    {
        return new SmartSpan();
    }

    public static function image($src, $width = '', $height = '')
    {
        $element = new SmartImage($src, $width, $height);
        return $element;
    }

    public static function  blue_header($content)
    {
        $element = new SmartSpan();
        return parent::__create_blue_header($element, $content);
    }

    public static function  white_header($content)
    {
        $element = new SmartSpan();
        return parent::__create_white_header($element, $content);
    }

    public static function  black_header($content)
    {
        $element = new SmartSpan();
        return parent::__create_black_header($element, $content);
    }

    public static function  blue_descriptor($content)
    {
        $element = new SmartSpan();
        return parent::__create_blue_descriptor($element, $content);
    }

    public static function  paragraph($content)
    {
        $element = new SmartSpan();
        return parent::__create_paragraph_descriptor($element, $content);
    }

    public static function  gray_header($content)
    {
        $element = new SmartSpan();
        return parent::__create_gray_header($element, $content);
    }

    public static function  dark_gray_header($content)
    {
        $element = new SmartSpan();
        return parent::__create_dark_gray_header($element, $content);
    }

    public static function  gray_descriptor($content)
    {
        $element = new SmartSpan();
        return parent::__create_gray_descriptor($element, $content);
    }

    public static function  main_descriptor($content)
    {
        $element = new SmartSpan();
        return parent::__create_main_descriptor($element, $content);
    }
}

class CreateBlockElement extends BaseCreateBlockElement
{
    public static function create_base_element()
    {
        return new SmartBlock();
    }

    public static function information_header($content)
    {

        $html = CreateBlockElement::wallpost_background(
            CreateBlockElement::spacer(
                CreateInlineElement::black_header($content)
            )
        );
        return $html;
    }

    public static function centered_information_header($content)
    {
        return CreateBlockElement::wallpost_background(CreateBlockElement::text_align_center(CreateBlockElement::spacer($content)));
    }

    public static function white_bordered_background($content)
    {
        return CreateBlockElement::border(
            CreateBlockElement::white_background(
                CreateBlockElement::spacer(
                    $content
                )
            )
        );

    }

    public static function two_column($left_content,$right_content, $left_percentage = 50,$right_percentage = 50,$left_column_class=null,$right_column_class=null){
        $left = (new SmartSpan())->add_class(SmartCSSClass::$smt_inline_block)->add_class(SmartCSSClass::$smt_top_aligned)->add_class($left_column_class)->set_style('width',"{$left_percentage}%");
        $right = (new SmartSpan())->add_class(SmartCSSClass::$smt_inline_block)->add_class(SmartCSSClass::$smt_top_aligned)->add_class($right_column_class)->set_style('width',"{$right_percentage}%");


        $left->set_inner_html($left_content);
        $right->set_inner_html($right_content);
        $div = (new SmartDiv())->add_child($left)->add_child($right);
        return $div->toString();

    }

    public static function  wallpost_background($content)
    {
        $element = new SmartBlock();
        return parent::__create_wallpost_background($element, $content);
    }

    public static function  blue_background($content)
    {
        $element = new SmartBlock();
        return parent::__create_blue_background($element, $content);
    }

    public static function div($content)
    {
        $element = new SmartBlock();
        $element->set_inner_html($content);
        return $element;
    }

    public static function  white_background($content)
    {
        $element = new SmartBlock();
        return parent::__create_white_background($element, $content);
    }

    public static function  border($content)
    {
        $element = new SmartBlock();
        return parent::__create_border($element, $content);
    }

    public static function  border_top($content)
    {
        $element = new SmartBlock();
        return parent::__create_border_top($element, $content);
    }

    public static function  border_bottom($content)
    {
        $element = new SmartBlock();
        return parent::__create_border_bottom($element, $content);
    }

    public static function  spacer($content)
    {
        $element = new SmartBlock();
        return parent::__create_spacer($element, $content);
    }

    public static function  horizontal_spacer($content)
    {
        $element = new SmartBlock();
        return parent::__create_horizontal_spacer($element, $content);
    }

    public static function text_align_center($content)
    {
        $element = new SmartBlock();
        return parent::__text_align_center($element, $content);
    }

    public static function text_align_right($content)
    {
        $element = new SmartBlock();
        return parent::__text_align_right($element, $content);
    }

    public static function fixed_width($width, $content)
    {
        $element = new SmartBlock();
        $element->set_inner_html($content);
        $element->set_style("width", $width);
        return $element;
    }
}

class CreateInlineBlock extends BaseCreateBlockElement
{

    public static function create_base_element()
    {
        return new SmartInlineBlock();
    }

    public static function span($content)
    {
        $element = new SmartInlineBlock();
        $element->set_inner_html($content);
        return $element;
    }

    public static function vertical_align_top($content)
    {
        $element = new SmartInlineBlock();
        return parent::__vertical_align_top($element, $content);
    }

    public static function vertical_align_middle($content)
    {
        $element = new SmartInlineBlock();
        return parent::__vertical_align_middle($element, $content);
    }

    public static function  wallpost_background($content)
    {
        $element = new SmartInlineBlock();
        return parent::__create_wallpost_background($element, $content);
    }

    public static function  blue_background($content)
    {
        $element = new SmartInlineBlock();
        return parent::__create_blue_background($element, $content);
    }

    public static function  tooltip_background($content)
    {
        $element = new SmartInlineBlock();
        return parent::__create_tooltip_background($element, $content);
    }

    public static function  button_background__gray($content)
    {
        $element = new SmartInlineBlock();
        return parent::__create_button_background__gray($element, $content);
    }

    public static function  button_background__green($content)
    {
        $element = new SmartInlineBlock();
        return parent::__create_button_background__green($element, $content);
    }

    public static function  button_border__gray($content)
    {
        $element = new SmartInlineBlock();
        return parent::__create_button_border__gray($element, $content);
    }

    public static function  white_background($content)
    {
        $element = new SmartInlineBlock();
        return parent::__create_white_background($element, $content);
    }

    public static function  border($content)
    {
        $element = new SmartInlineBlock();
        return parent::__create_border($element, $content);
    }

    public static function  border_top($content)
    {
        $element = new SmartInlineBlock();
        return parent::__create_border_top($element, $content);
    }

    public static function  border_bottom($content)
    {
        $element = new SmartInlineBlock();
        return parent::__create_border_bottom($element, $content);
    }

    public static function  spacer($content)
    {
        $element = new SmartInlineBlock();
        return parent::__create_spacer($element, $content);
    }

    public static function  horizontal_spacer($content)
    {
        $element = new SmartInlineBlock();
        return parent::__create_horizontal_spacer($element, $content);
    }

    public static function gray_button($content)
    {
        return
            CreateInlineBlock::button_border__gray(
                CreateInlineBlock::button_background__gray(
                    CreateInlineBlock::spacer(
                        CreateInlineBlock::horizontal_spacer(
                            CreateInlineElement::black_header($content)
                        )
                    )
                )
            );
    }

    public static function green_button($content)
    {
        return
            CreateInlineBlock::button_border__gray(
                CreateInlineBlock::button_background__green(
                    CreateInlineBlock::spacer(
                        CreateInlineBlock::horizontal_spacer(
                            CreateInlineElement::white_header($content)
                        )
                    )
                )
            );
    }

    public static function fixed_width($width, $content)
    {
        $element = new SmartInlineBlock();
        $element->set_inner_html($content);
        $element->set_style("width", $width);
        $element->set_style("overflow-x", 'hidden');
        $element->set_style("display", 'inline-block');
        return $element;
    }

    public static function card_alert($context, $lens, $triggers)
    {
        return CreateInlineBlock::spacer(
            CreateInlineBlock::vertical_align_middle(CreateInlineBlock::spacer($context)) .
                CreateInlineBlock::vertical_align_middle(CreateInlineBlock::spacer($lens)) .
                CreateInlineBlock::vertical_align_middle(CreateInlineBlock::spacer($triggers))
        );
    }

    public static function card_record($context, $lens, $triggers)
    {
        return CreateInlineBlock::spacer(
            CreateBlockElement::spacer($context) .
                CreateBlockElement::spacer($lens) .
                CreateBlockElement::spacer($triggers)
        );
    }

    public static function card_teaser($context, $lens, $triggers)
    {
        return CreateInlineBlock::spacer(
            CreateBlockElement::spacer(
                CreateInlineBlock::spacer($context) . CreateInlineBlock::spacer($triggers)
            ) .
                CreateBlockElement::spacer($lens)
        );
    }

    public static function card_wallpost($context, $lens, $triggers)
    {
        return CreateInlineBlock::spacer(
            CreateInlineBlock::vertical_align_top(CreateInlineBlock::spacer($context))
                .
                CreateInlineBlock::vertical_align_top(
                    CreateInlineBlock::spacer(
                        CreateBlockElement::spacer($lens) .
                            CreateBlockElement::spacer($triggers)
                    )
                )

        );
    }

}

//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ DEMO WIDGETS

//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@2

abstract class UIContainer
{
    public abstract function __toString();

    public function toString()
    {
        return $this->__toString();
    }
}

class UITableLeftRight extends UIContainer
{
    private $left_content, $right_content, $far_right;

    protected function __construct($left_content, $right_content, $far_right = '')
    {
        $this->left_content = $left_content;
        $this->right_content = $right_content;
        $this->far_right = $far_right;
    }


    public function __toString()
    {
        $left_content = $this->left_content;
        $right_content = $this->right_content;
        $far_right_content = $this->far_right;

        $class_top_aligned = SmartCSSClass::$smt_top_aligned;
        $class_inline_block = SmartCSSClass::$smt_inline_block;

        $left = "<span class='{$class_inline_block} {$class_top_aligned}'>{$left_content}</span>";
        $right = "<span class='{$class_inline_block} {$class_top_aligned} resp_updates_width_490'>{$right_content}</span>";
        $far_right = "<span class='{$class_top_aligned}'>{$far_right_content}</span>";

        $html = "<div><div>{$left}{$right}{$far_right}</div></div>";
        return $html;
        //=====
        //return CreateBlockElement::two_column($left_content,$right_content,20,80);
    }

    public static function from_content($left, $right, $far_right = '')
    {
        return new UITableLeftRight($left, $right, $far_right);
    }
}


interface IStackPanel
{
    static function create();

    function add($item);

    function toString();

    function should_space_items($should_space_items);
}


abstract class UICBasePanel extends UIContainer implements IStackPanel
{
    private $arr_items = array();
    private $should_space_items = true;
    private $overall_container;

    public function __construct()
    {
        $this->overall_container = $this->getOverallContainerHtmlElement();
    }

    public function add_class($class)
    {
        $this->overall_container->add_class($class);
        return $this;
    }
    public function add_class_if($condition,$class){
        if($condition){
            $this->add_class($class);
        }
        return $this;
    }

    public function set_style($property, $value)
    {
        $this->overall_container->set_style($property, $value);
        return $this;
    }

    public function set_attribute($property, $value)
    {
        $this->overall_container->set_attribute($property, $value);
        return $this;
    }
    public function set_attribute_if($condition,$property, $value){
        if($condition){
            $this->set_attribute($property, $value);
        }
        return $this;
    }

    public function should_space_items($should_space_items)
    {
        if (!$should_space_items) {
            $this->should_space_items = false;
        }
        else{
            $this->should_space_items = true;
        }
        return $this;
    }

    public function add($item)
    {
        $this->arr_items[] = $item;
        return $this;
    }

    /** @return SmartHTMLElement */
    abstract protected function wrap_item($item);
    
    protected function wrap_panel($panel_html)
    {
        return $panel_html;
    }

    private $index_of_current_item = 0;

    protected function current_item_index()
    {
        return $this->index_of_current_item;
    }


    public function __toString()
    {

        $this->index_of_current_item = 0;

        //wrap each element in div
        $arr_transformed_elements = array();
        foreach ($this->arr_items as $item) {
            $item = $this->space_item($item);
            $wrapped_item = $this->wrap_item($item);
            $final_item_html = $this->get_html_before_item() . $wrapped_item;
            $arr_transformed_elements[] = $final_item_html;
            //................
            $this->index_of_current_item += 1;
        }
        $result = join("", $arr_transformed_elements);
        //--------
        $this->overall_container->set_inner_html($this->wrap_panel($result));
        return $this->overall_container->toString() . "";
    }

    protected function space_item($item)
    {
        $div = new SmartDiv();
        $div->set_inner_html($item);

        if ($this->should_space_items) {
            $div->padding($this->getItemSpacing());
        }
        return $div;
    }

    protected function get_html_before_item()
    {
        return "";
    }

    /**
     * @return SmartHTMLElement
     */
    protected function getOverallContainerHtmlElement()
    {
        return new SmartDiv();
    }

    private $item_spacing = "5px";
    protected function getItemSpacing()
    {
        return $this->item_spacing;
    }
    public function setItemSpacing($padding){
        $this->item_spacing = $padding;
        return $this;
    }
}


class UICStackPanel extends UICBasePanel
{

    private $item_class_name_array = array();    
    public function add_class_to_item($item_class){
        $this->item_class_name_array[] = $item_class;
    }

    private $assoc_array_for_row_classes = array();
    public function add_class_to_item_at_index($number, $class)
    {
        $number = intval($number);
        $current_class_string =
            array_key_exists($number,$this->assoc_array_for_row_classes) ?
                $this->assoc_array_for_row_classes[$number]." ": "";

        $current_class_string .= $class;
        $this->assoc_array_for_row_classes[$number] = $current_class_string;
    }

    protected function wrap_item($item)
    {
        $container_for_item = new SmartDiv();
        $container_for_item->set_inner_html($item);
        foreach($this->item_class_name_array as $item_class){
            $container_for_item->add_class($item_class);
        }
        $item_index = $this->current_item_index();
        if(array_key_exists($item_index,$this->assoc_array_for_row_classes)){
            $container_for_item->add_class($this->assoc_array_for_row_classes[$item_index]);
        }
        return $container_for_item;
    }

    public static function create()
    {
        return new UICStackPanel();
    }

}

class UICDropdownPanel extends UICBasePanel
{
    private $container_element;
    public function __construct()
    {
        $this->container_element = new SmartSelect();
        parent::__construct();
    }

    public function set_name($name)
    {
        $this->container_element->set_name($name);
        return $this;
    }

    protected function wrap_item($item)
    {

        return $item;
    }
    private $item_class_name_array = array();
    public function add_class_to_item($item_class){
        //todo: this class is actually not adding the specified classes as of now
        $this->item_class_name_array[] = $item_class;
    }

    public static function create()
    {
        return new UICDropdownPanel();
    }
    protected function getOverallContainerHtmlElement()
    {
        return $this->container_element;
    }

}

class UICColumnPanel extends UICBasePanel
{

    private $default_item_width = 160;
    private $item_width_map = array();
    private $column_width_map = array(); //stores width of each column, for columns whose width has been explicitly set
    private function is_unsigned_number($number)
    {
        return is_numeric($number) && $number >= 0;
    }

    public function set_item_width($item_index, $item_width)
    {
        if ($this->is_unsigned_number($item_index) && $this->is_unsigned_number($item_width)) {
            $this->item_width_map[intval($item_index)] = intval($item_width);
        }
        return $this;
    }

    public function set_column_width($zero_based_column_index, $column_width)
    {
        if ($this->is_unsigned_number($zero_based_column_index) && $this->is_unsigned_number($column_width)) {
            $this->column_width_map[intval($zero_based_column_index)] = intval($column_width);
        }
        return $this;
    }


    public function set_default_item_width($width)
    {
        $this->default_item_width = intval($width);
        return $this;
    }
    public function set_default_item_width_percent($width)
    {
        $width = str_replace("%","",$width);
        $this->default_item_width = $width."%";
        return $this;
    }

    private $vertical_align = 'top';
    public function set_vertical_align_top(){
        $this->vertical_align = 'top';
        return $this;
    }
    public function set_vertical_align_middle(){
        $this->vertical_align = 'middle';
        return $this;
    }
    protected function wrap_into_vertical_aligner($content){
        $result = $this->vertical_align == 'middle' ? CreateInlineBlock::vertical_align_middle($content) : CreateInlineBlock::vertical_align_top($content);
        return $result;
    }

    private $restricts_item_width = true;
    public function removeItemWidthRestriction(){
        $this->restricts_item_width = false;
        return $this;
    }
    public function permitItemWidthRestriction(){
        $this->restricts_item_width = true;
        return $this;
    }

    private $assoc_array_for_row_classes = array();
    public function add_class_to_item_at_index($number, $class)
    {
        $number = intval($number);
        $current_class_string =
            array_key_exists($number,$this->assoc_array_for_row_classes) ?
                $this->assoc_array_for_row_classes[$number]." ": "";

        $current_class_string .= $class;
        $this->assoc_array_for_row_classes[$number] = $current_class_string;
    }

    private $should_display_as_inline_block = true;
    private $should_vertical_align_top = true;
    public function disableDisplayAsInlineBlock(){
        $this->should_display_as_inline_block = false;
        return $this;
    }
    public function disableVerticalAlignTop(){
        $this->should_vertical_align_top = false;
        return $this;
    }
    
    protected function wrap_item($item)
    {
        $container_for_item = new SmartSpan();
        $container_for_item->set_inner_html($item);

        if($this->should_display_as_inline_block){
            $container_for_item->display_inline_block();
        }
        if($this->should_vertical_align_top){
            $container_for_item->vertical_align_top();
        }
        
        if($this->restricts_item_width){
            $container_for_item->width($this->choose_applicable_item_width());
        }
        foreach($this->item_class_name_array as $item_class){
            $container_for_item->add_class($item_class);
        }
        $item_index = $this->current_item_index();
        if(array_key_exists($item_index,$this->assoc_array_for_row_classes)){
            $container_for_item->add_class($this->assoc_array_for_row_classes[$item_index]);
        }
        return $container_for_item;
    }

    private $item_class_name_array = array();
    public function add_class_to_item($item_class){
        $this->item_class_name_array[] = $item_class;        
    }

    protected function choose_applicable_item_width()
    {
        $current_item_index = intval($this->current_item_index());
        $current_column_index = intval($this->current_column_index());


        $applicable_item_width = $this->default_item_width;
        if(count($this->item_width_map) > 0 && array_key_exists($current_item_index, $this->item_width_map)){
            $applicable_item_width = $this->item_width_map[$current_item_index];
        }
        else if(count($this->column_width_map) > 0 && array_key_exists($current_column_index, $this->column_width_map)){
            $applicable_item_width = $this->column_width_map[$current_column_index];
        }
        else{
            $applicable_item_width = $this->default_item_width;
        }
        return $applicable_item_width;

    }

    protected function iif($condition, $onTrueValue, $otherwiseValue)
    {
        return $condition ? $onTrueValue : $otherwiseValue;
    }

    protected function current_column_index()
    {
        return $this->current_item_index() % $this->max_items_per_line;
    }

    public static function create()
    {
        return new UICColumnPanel();
    }

    //===================
    protected $max_items_per_line = 10000;

    public function set_max_items_per_line($unsigned_number)
    {
        if ($this->is_unsigned_number($unsigned_number)) {
            $this->max_items_per_line = $unsigned_number;
        }
        return $this;
    }

    protected function get_html_before_item()
    {
        $break_html = '';
        //print json_encode($this->current_item_index());
        $should_prepend_space =
            ($this->current_item_index() % $this->max_items_per_line) == 0
                && $this->current_item_index() > 0;
        if ($should_prepend_space) {
            $break_html = SmartHTMLElement::line_break();
        }
        return $break_html;
    }

}

class UICHeadedStackPanel implements IStackPanel
{
    private $header = 'Panel Header';
    private $stack_panel = null;

    public function __construct($base_stack_panel)
    {
        $this->stack_panel = $base_stack_panel;
    }

    public function should_space_items($should_space_items)
    {
        $this->stack_panel->should_space_items($should_space_items);
        return $this;
    }

    public function set_header($header)
    {
        $this->header = $header;
        return $this;
    }

    public static function create()
    {
        return new UICHeadedStackPanel(new UICStackPanel());
    }

    public function add($item)
    {
        $this->stack_panel->add($item);
        return $this;
    }

    public function __toString()
    {

        $items = $this->stack_panel . "";
        $info_header = CreateBlockElement::information_header($this->header);
        $result = $info_header . $items;
        return CreateBlockElement::spacer($result)."";
    }

    public function toString()
    {
        return $this->__toString();
    }
}

class Namespace_UIDataStructure
{

}

class UIStoryDataStructure extends Namespace_UIDataStructure
{
    public $headline, $picture, $caption, $links, $author_name, $author_picture, $date;

    protected function __construct()
    {

    }

    public static function create()
    {
        return new UIStoryDataStructure();
    }
}

class UICard extends Namespace_UIDataStructure
{
    public $context, $lens, $triggers;

    public function __construct()
    {

    }

    public static function create()
    {
        return new UICard();
    }
}

class SmartTable extends SmartNonReplacedHTMLElement
{
    public function __construct()
    {
        $this->set_tag_name('table');
    }
    public static function create(){
        return new self();
    }
    
}

class SmartTableRow extends SmartNonReplacedHTMLElement
{
    public function __construct()
    {
        $this->set_tag_name('tr');
    }
    public static function create(){
        return new self();
    }

    
}

class SmartTableColumn extends SmartNonReplacedHTMLElement
{
    public function __construct()
    {
        $this->set_tag_name('td');
    }
    public static function create(){
        return new self();
    }
    public function set_column_span($unsigned_int){
        $this->set_attribute('colspan',$unsigned_int);
        return $this;
    }
    public function set_row_span($unsigned_int){
        $this->set_attribute('rowspan',$unsigned_int);
        return $this;
    }
}

class SmartTableHeader extends SmartNonReplacedHTMLElement
{
    public function __construct()
    {
        $this->set_tag_name('thead');
    }
    public static function create(){
        return new self();
    }
}

class SmartTableBody extends SmartNonReplacedHTMLElement
{
    public function __construct()
    {
        $this->set_tag_name('tbody');
    }
    public static function create(){
        return new self();
    }

}

class SmartBold extends SmartNonReplacedHTMLElement
{
    public function __construct()
    {
        $this->set_tag_name('b');
    }
    public static function create(){
        return new self();
    }

}

class SmartIFrame extends SmartNonReplacedHTMLElement
{
    public function __construct()
    {
        $this->set_tag_name("iframe");
    }
    public function set_src($value)
    {
        $this->set_attribute("src",$value);
        return $this;
    }
}


class SmartUrl
{
    private $arr_urlarameters = array();
    private $url;
    
    public function set_url($url)
    {
        $this->url = $url;
        return $this;
    }
    public function url(){
        return $this->url;
    }

    public function set_parameter($property, $value)
    {
        $this->arr_urlarameters[$property] = $value;
        return $this;
    }

    private function get_href()
    {
        $href = $this->url;
        if (is_array($this->arr_urlarameters) &&
            count($this->arr_urlarameters)
        ) {
            $href .= "?";
            $sep = "";
            foreach ($this->arr_urlarameters as $property => $value) {
                $href .= join("", array($sep, $property, "=", $value));
                $sep = "&";
            }
        }
        return $href;
    }

    public function __toString()
    {
        return $this->get_href()."";        
    }

    public function toString()
    {
        return $this->__toString();
    }

  
}





#============================= THIS SET OF FUNCTIONS IS USEFUL FOR PROTOTYPING - and making code readable
class SmartListOfHtmlElements{

    public static function createNew(){
        return new self();
    }

    private $list_of_smart_html_elements = array();
    public function starts_with($smartHtmlElement){
        $this->throwExceptionIfNotSmartHtmlElement($smartHtmlElement);
        $this->list_of_smart_html_elements[] = $smartHtmlElement;
        return $this;
    }
    public function followed_by($smartHtmlElement){
        return $this->starts_with($smartHtmlElement);
    }
    public function add($smartHtmlElement){
        return $this->starts_with($smartHtmlElement);
    }

    /** @return SmartListOfHtmlElements */
    public function replicateNtimes($int)
    {
        $list = new SmartListOfHtmlElements();
        for($i = 0; $i < $int;$i++){
            $list->add($this);
        }
        return $list;
    }

    public function __toString()
    {
        return join("",$this->list_of_smart_html_elements);
    }
    public function asDiv(){
        $div = new SmartDiv();
        $div->set_inner_html($this);
        return $div;
    }
    /** @return SmartList */
    public function asUnOrderedList(){
        $div = anUnorderedList();
        $div->set_inner_html($this);
        return $div;
    }
    /** @return SmartList */
    public function asOrderedList(){
        $div = anOrderedList();
        $div->set_inner_html($this);
        return $div;
    }

    public function asSpan(){
        $div = new SmartDiv();
        $div->set_inner_html($this);
        return $div;
    }
    public function asInlineBlock(){
        $div = new SmartInlineBlock();
        $div->set_inner_html($this);
        return $div;
    }

    /**
     * @param $entity
     * @throws Exception
     */
    private function throwExceptionIfNotSmartHtmlElement($entity)
    {
        if (! is_a($entity, "SmartHTMLElement")) {
            if (! is_a($entity, get_class($this))) {
                throw new Exception("expected a SmartHTMLElement or ".get_class($this));
            }
        }
    }
}

function aLink($content = ""){
    $element = new SmartLink();
    $element->set_inner_html($content);
    $element->set_href("");
    return $element;
}
function aDiv($content = ""){
    $element = new SmartDiv();
    $element->set_inner_html($content);
    return $element;
}
function aSpan(){
    return new SmartSpan();
}
function aStyle(){
    return new SmartStyleTag();
}
function aHeading1(){
    return new SmartHeading1();
}
function aHeading2(){
    return new SmartHeading2();
}
function aHeading3(){
    return new SmartHeading3();
}
function aHeading4(){
    return new SmartHeading4();
}
function aHeading5($content = ""){
    $element = new SmartHeading5();
    $element->set_inner_html($content);
    return $element;
}
function aHeading6(){
    return new SmartHeading6();
}
function aParagraph($content = ""){
    $element = new SmartParagraph();
    $element->set_inner_html($content);
    return $element;
}
function aTextBox($content = ""){
    $element = new TextInput();
    $element->set_inner_html($content);
    return $element;
}
function aSubmitButton($content = ""){
    $element = new SubmitInput();
    $element->set_value($content);
    return $element;
}


function anImageFromUrl($url){
    return new SmartImage($url);
}
function anInlineBlock($content = ""){
    $element = new SmartInlineBlock();
    $element->set_inner_html($content);
    return $element;
}
function anUnorderedList(){
    return (new SmartList())->set_list_type_unordered();
}
function anOrderedList(){
    return (new SmartList())->set_list_type_ordered();
}

function aListItem(){
    return new SmartListItem();
}


