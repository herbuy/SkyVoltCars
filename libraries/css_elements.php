<?php
class CSSBuilder{
    private $array = array();
    /** @param \CSSElement $css_element */
    public function addCSSElement($css_element){
        if(!is_a($css_element,"CSSElement")){
            throw new Exception("expects a CSSElement for addCSSElement");
        }
        $this->array[] = $css_element->getFullDeclarationAsString();  
        return $this;
    }
    protected function addMediaQuery($param)
    {
        $this->array[] = $param;
        return $this;
    }
    public function __toString()
    {
        return join(" ",$this->array);
    }
}

abstract class CSSElement{
    
    private $assoc_array_for_styles = array();

    abstract public function __toString();

    #AND
    /** @return CSSElementMatchingAllCriteria */
    public function and_($css_element){
        //TODO: could make this method abstract
        return new CSSElementMatchingAllCriteria($this,$css_element);
    }    
    public function and_class($name){
        $this->throwExceptionIfNotString($name);
        return $this->and_(new CSSElementOfClass($name));
    }    
    public function and_id($value){
        $this->throwExceptionIfNotString($value);
        return $this->and_(CSSElementWithId($value));
    }
    public function and_attribute($value){
        $this->throwExceptionIfNotString($value);
        return $this->and_(CSSAttribute($value));
    }
    

    public function with($css_element){
        return $this->and_($css_element);
    }
    public function with_class($css_element){
        return $this->and_class($css_element);
    }
    public function with_id($css_element){
        return $this->and_id($css_element);
    }
    public function with_attribute($name){
        return $this->and_attribute($name);
    }
    
    #OR
    public function or_($css_element){
        //TODO: could make this method abstract
        $css_element_matching_one_or_more_criteria = new CSSElementMatchingOneOrMoreCriteria($this);
        return $css_element_matching_one_or_more_criteria->or_($css_element);
    }
    public function or_class($name){
        return $this->or_(CSSElementOfClass($name));
    }
    public function or_id($value){
        return $this->or_(CSSElementWithId($value));
    }
    public function or_type($value){
        return $this->or_(CSSElementOfType($value));
    }

    #INSIDE
    public function inside($css_element){
        //TODO: could make this method abstract
        return new CSSElementInsideElement($this,$css_element);
    }
    public function inside_nth_child_of_class($class, $number){
        return $this->inside(
            CSSElementOfClass($class)->nth_child($number)
        );
    }
    public function inside_class($name){
        return $this->inside(CSSElementOfClass($name));
    }
    public function inside_id($value){
        return $this->inside(CSSElementWithId($value));
    }
    public function inside_type($value){
        return $this->inside(CSSElementOfType($value));
    }

    #INSIDE
    public function child_of($css_element){
        //TODO: could make this method abstract
        return new CSSElementChildOfElement($this,$css_element);
    }
    public function child_of_class($name){
        return $this->child_of(CSSElementOfClass($name));
    }
    public function child_of_id($value){
        return $this->child_of(CSSElementWithId($value));
    }
    public function child_of_type($value){
        return $this->child_of(CSSElementOfType($value));
    }

    #AFTER
    public function after_element($css_element){
        //TODO: could make this method abstract
        return new CSSElementAfterElement($this,$css_element);
    }
    public function after_class($name){
        return $this->after_element(CSSElementOfClass($name));
    }
    public function after_id($value){
        return $this->after_element(CSSElementWithId($value));
    }
    public function after_type($value){
        return $this->after_element(CSSElementOfType($value));
    }
    #BEFORE
    public function before_element($css_element){
        //TODO: could make this method abstract
        return new CSSElementBeforeElement($this,$css_element);
    }
    public function before_class($name){
        return $this->before_element(CSSElementOfClass($name));
    }
    public function before_id($value){
        return $this->before_element(CSSElementWithId($value));
    }
    public function before_type($value){
        return $this->before_element(CSSElementOfType($value));
    }

    public function subtype($name){
        return new CSSPseudoElementOfElement(CSSElementOfType($name),$this);
    }

    public function first_of_type(){
        return $this->subtype("first-of-type");
    }
    public function last_of_type(){
        return $this->subtype("last-of-type");
    }
    public function only_of_type(){
        return $this->subtype("only-of-type");
    }
    public function nth_of_type($number){
        return $this->subtype(sprintf("nth-of-type(%s)",$number));
    }
    public function nth_last_of_type($number){
        return $this->subtype(sprintf("nth-last-of-type(%s)",$number));
    }
    public function empty_(){
        return $this->subtype("empty");
    }

    #link related
    public function visited(){
        return $this->subtype("visited");
    }
    public function not_visited(){
        return $this->subtype("link");
    }
    public function active(){
        return $this->subtype("active");
    }
    public function hover(){
        return $this->subtype("hover");
    }


    #input related
    public function checked(){
        return $this->subtype("checked");
    }
    public function disabled(){
        return $this->subtype("disabled");
    }
    public function enabled(){
        return $this->subtype("enabled");
    }
    public function focus(){
        return $this->subtype("focus");
    }
    public function invalid(){
        return $this->subtype("invalid");
    }
    public function valid(){
        return $this->subtype("valid");
    }
    public function required(){
        return $this->subtype("required");
    }
    public function optional(){
        return $this->subtype("optional");
    }
    public function read_only(){
        return $this->subtype("read-only");
    }
    public function read_write(){
        return $this->subtype("read-write");
    }

    public function root(){
        return $this->subtype("root");
    }
    public function first_child(){
        return $this->subtype("first-child");
    }
    public function last_child(){
        return $this->subtype("last-child");
    }
    public function only_child(){
        return $this->subtype("only-child");
    }
    public function nth_child($number){
        return $this->subtype(sprintf("nth-child(%s)",$number));
    }
    public function nth_last_child($number){
        return $this->subtype(sprintf("nth-last-child(%s)",$number));
    }
    public function target(){
        return $this->subtype("target");
    }




    
    private function get_selector(){
        return $this->__toString();
    }
    
    public function hasStyles(){
        return count($this->assoc_array_for_styles) > 0;
    }
    public function hasNoStyles(){
        return !$this->hasStyles();
    }
    private function get_declarations(){
        $values = join(";",array_values($this->assoc_array_for_styles));
        return sprintf("{%s}",$values);
    }
    public function getFullDeclarationAsString(){
        return sprintf("%s %s",$this->get_selector(),$this->get_declarations());
    }
    
    public function set_style($property,$value){
        if(!is_null($value) && strlen(trim($value)) > 0){
            //TODO: it is possible that the value is not a string but a function i.e pattern x() -- we dont quote it!!
            $this->assoc_array_for_styles[$property] = sprintf("%s:%s", $property, $value."");
        }        
        return $this;
    }
    
    public function background_color($value){
        return $this->set_style('background-color',$value);
    }
    public function background_color_random(){
        return $this->background_color(
            sprintf("RGB(%s,%s,%s)",rand(0,255),rand(0,255),rand(0,255))
        );
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
    public function box_shadow($value){
        return $this->set_style('box-shadow',$value);
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
    public function right($value){
        return $this->set_style('right',$value);
    }
    public function bottom($value){
        return $this->set_style('bottom',$value);
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
    public function text_align_center(){
        return $this->text_align('center');
    }
    public function text_align_right(){
        return $this->text_align('right');
    }
    public function text_decoration($value){
        return $this->set_style('text-decoration',$value);
    }
    public function text_indent($value){
        return $this->set_style('text-indent',$value);
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
        return $this->font_family('sans-serif');
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
    public function line_height_initial(){
        return $this->line_height("initial");
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
    public function z_index($value){
        return $this->set_style('z-index',$value);
    }
    public function opacity($value){
        return $this->set_style('opacity',$value);
    }
    public function width_auto()
    {
        return $this->width("auto");
    }

    public function border_radius($value){
        return $this->set_style('border-radius',$value);
    }

    /**
     * @param $name
     * @throws Exception
     */
    private function throwExceptionIfNotString($name)
    {
        if (!is_string($name)) {
            throw new Exception("expects a string as input");
        }
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
        return $this->background_size("padding-box");
    }
    public function background_clip_content_box()
    {
        return $this->background_size("content-box");
    }
    public function background_clip_border_box()
    {
        return $this->background_size("border-box");
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

}

//=== FACTOR METHODS
function CSSElementOfType($name){
    return new CSSElementOfType($name);
}
function CSSElementOfClass($name){
    return new CSSElementOfClass($name);
}
function CSSElementWithId($value){
    return new CSSElementWithId($value);
}
function CSSElementWithAttribute($name){
    return new CSSElementWithAttribute($name);
}
function CSSAttribute($name){
    return new CSSAttribute($name);
}
function CSSElementOfClassAndNthChild($class,$arr_child_indexes){
    /*if(is_numeric($arr_child_indexes) || is_string($arr_child_indexes)){
        $arr_child_indexes = array($arr_child_indexes);
    }*/
    //------
    if(!is_array($arr_child_indexes)){
        $arr_child_indexes = array("".$arr_child_indexes);
        //throw new Exception("expects array of nth child");
    }
    $count_nth_children = count($arr_child_indexes);

    /** @var CSSElement $css_element */
    $css_element = null;
    for($i = 0; $i < $count_nth_children;$i++){
        if($i ==0){
            $css_element = CSSElementOfClass($class)->nth_child($arr_child_indexes[$i]);
        }
        else{
            $css_element = $css_element->or_(
                CSSElementOfClass($class)->nth_child($arr_child_indexes[$i])
            );
        }

    }
    return $css_element;
}
function CSSElementOfClassIfAncestorOfClassAndNthChild($item_class, $ancestor_class, $arr_ancestor_nth_child)
{
    if(is_numeric($arr_ancestor_nth_child)){
        $arr_ancestor_nth_child = array($arr_ancestor_nth_child);
    }
    //------
    if(!is_array($arr_ancestor_nth_child)){
        throw new Exception("expects array of nth child");
    }
    $count_nth_children = count($arr_ancestor_nth_child);

    /** @var CSSElement $css_element */
    $css_element = null;
    for($i = 0; $i < $count_nth_children;$i++){
        if($i ==0){
            $css_element = CSSElementOfClass($item_class)->inside_nth_child_of_class($ancestor_class,$arr_ancestor_nth_child[$i]);
        }
        else{
            $css_element = $css_element->or_(
                CSSElementOfClass($item_class)->inside_nth_child_of_class($ancestor_class,$arr_ancestor_nth_child[$i])
            );
        }

    }
    return $css_element;
}

############################################################
abstract class SimpleCSSElement extends CSSElement{
    private $name = "";
    protected function getName(){
        return $this->name;
    }
    public function __construct($name)
    {
        $this->name = $name;
    }
    public function __toString()
    {
        return sprintf("%s%s",htmlspecialchars($this->get_prefix()),htmlspecialchars($this->name));
    }
    protected function get_prefix(){
        return "";
    }
}
class CSSElementOfType extends SimpleCSSElement{
}class CSSElementOfClass extends SimpleCSSElement{

    protected function get_prefix(){
        return ".";
    }    
}
class CSSElementWithId extends SimpleCSSElement{
    protected function get_prefix(){
        return "#";
    }
}

class CSSElementWithAttribute extends SimpleCSSElement{
    
    public function __toString()
    {
        return sprintf("[%s]", $this->contentOfBrackets());
    }
    
    protected function contentOfBrackets()
    {
        return parent::__toString();
    }

    public function set_to_value($value){
        return new CSSElementWithAttributeSetToValue($this->getName(),$value);
    }
    public function containing_value($value){
        return new CSSElementWithAttributeContainingValue($this->getName(),$value);
    }
    public function beginning_with_value($value){
        return new CSSElementWithAttributeBeginningWithValue($this->getName(),$value);
    }
    public function ending_with_value($value){
        return new CSSElementWithAttributeEndingWithValue($this->getName(),$value);
    }
}
class CSSAttribute extends CSSElementWithAttribute{
    
}
class CSSElementWithAttributeSetToValue extends CSSElementWithAttribute{

    private $value;

    public function __construct($name, $value)
    {
        $this->value = $value;
        parent::__construct($name);
    }

    protected function contentOfBrackets()
    {
        return sprintf("%s%s%s", parent::getName(), $this->getOperator(),$this->value);
    }

    protected function getOperator()
    {
        return "=";
    }
}

class CSSElementWithAttributeContainingValue extends CSSElementWithAttributeSetToValue{

    protected function getOperator()
    {
        return "~=";
    }
}

class CSSElementWithAttributeBeginningWithValue extends CSSElementWithAttributeSetToValue{

    protected function getOperator()
    {
        return "^=";
    }
}
class CSSElementWithAttributeEndingWithValue extends CSSElementWithAttributeSetToValue{

    protected function getOperator()
    {
        return "$=";
    }
}


abstract class CompoundCSSElement extends CSSElement{
    protected $css_element_array = array();
    protected function add_css_element($css_element){
        if(!is_a($css_element,"CSSElement")){
            throw new Exception("expected a CSSElement FOR add_css_element");
        }
        $this->css_element_array[] = $css_element;
        return $this;
    }
}
class CSSElementMatchingAllCriteria extends CompoundCSSElement{

    public function __construct($css_element1,$css_element2)
    {
        $this->add_css_element($css_element1);
        $this->add_css_element($css_element2);
    }

    public function and_($css_element){
        $this->add_css_element($css_element);
        return $this;
    }
    public function __toString()
    {
        return join("",$this->css_element_array);
    }

}

class CSSElementMatchingOneOrMoreCriteria extends CompoundCSSElement{

    public function __construct($main_css_element)
    {
        $this->add_css_element($main_css_element);
    }

    public function or_($css_element){
        $this->add_css_element($css_element);
        return $this;
    }
    public function __toString()
    {
        return join(",",$this->css_element_array);
    }
}

class CSSElementInsideElement extends CompoundCSSElement{

    public function __construct($child_element,$parent_element)
    {
        $this->add_css_element($child_element);
        $this->inside($parent_element);
    }

    public function inside($css_element){
        $this->add_css_element($css_element);
        return $this;
    }
    public function __toString()
    {
        $array = array_reverse($this->css_element_array);
        return join(" ",$array);
    }
}
function CSSElementInsideElement($child_element,$parent_element){
    return new CSSElementInsideElement($child_element,$parent_element);
}
class CSSElementChildOfElement extends CompoundCSSElement{

    public function __construct($css_element1, $css_element2)
    {
        $this->add_css_element($css_element1);
        $this->add_css_element($css_element2);
    }

    public function child_of($css_element){
        $this->add_css_element($css_element);
        return $this;
    }
    public function __toString()
    {
        $array = array_reverse($this->css_element_array);
        return join(">",$array);
    }
}
class CSSElementAfterElement extends CompoundCSSElement{

    public function __construct($css_element1, $css_element2)
    {
        $this->add_css_element($css_element1);
        $this->add_css_element($css_element2);
    }

    public function after($css_element){
        $this->add_css_element($css_element);
        return $this;
    }
    public function __toString()
    {
        $array = array_reverse($this->css_element_array);
        return join("+",$array);
    }
}
class CSSElementBeforeElement extends CompoundCSSElement{

    public function __construct($css_element1, $css_element2)
    {
        $this->add_css_element($css_element1);
        $this->add_css_element($css_element2);
    }

    public function before($css_element){
        $this->add_css_element($css_element);
        return $this;
    }
    public function __toString()
    {
        $array = array_reverse($this->css_element_array);
        return join("~",$array);
    }
}

class CSSPseudoElementOfElement extends CompoundCSSElement{

    public function __construct($css_element1, $css_element2)
    {
        $this->add_css_element($css_element1);
        $this->add_css_element($css_element2);
    }

    public function __toString()
    {
        $array = array_reverse($this->css_element_array);
        return join(":",$array);
    }
}


class CSSMediaQuery{
    private $media="";
    private $restrictions = array();
    private $rules_array = array();

    public function __toString()
    {
        return sprintf("@media %s %s {%s}",
            $this->media,
            join(" and ",$this->restrictions),
            join(" ",$this->rules_array));
    }

    public function media_print()
    {
        $this->media = "print";
        return $this;
    }

    public function media_screen()
    {
        $this->media = "screen";
        return $this;
    }
    public function min_width($value)
    {
        $value = $this->add_units($value);
        $this->restrictions[] = $this->wrap_in_brackets("min-width:$value");
        return $this;
    }
    public function min_height($value)
    {
        $value = $this->add_units($value);
        $this->restrictions[] = $this->wrap_in_brackets("min-height:$value");
        return $this;
    }
    public function max_width($value)
    {
        $value = $this->add_units($value);
        $this->restrictions[] = $this->wrap_in_brackets("max-width:$value");
        return $this;
    }
    public function max_height($value)
    {
        $value = $this->add_units($value);
        $this->restrictions[] = $this->wrap_in_brackets("max-height:$value");
        return $this;
    }
    public function orientation_landscape()
    {
        $this->restrictions[] = $this->wrap_in_brackets("orientation:landscape");
        return $this;
    }
    public function orientation_portrait()
    {
        $this->restrictions[] = $this->wrap_in_brackets("orientation:portrait");
        return $this;
    }

    /** @param \CSSElement $css_element */
    public function add_css_element($css_element){
        if(!is_a($css_element, "CSSElement")){
            throw new Exception("expects a CSSElement for add_css_element");
        }
        $this->rules_array[] = $css_element->getFullDeclarationAsString();
        return $this;
    }

    private function wrap_in_brackets($param)
    {
        return sprintf("(%s)",$param);
    }

    private function add_units($value)
    {
        return $value;
        //$value = str_replace("in","",$value);
        //return $value."in";
    }
}

