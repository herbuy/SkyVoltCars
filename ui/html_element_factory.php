<?php
class HtmlElementFactory{
    public function div(){
        return new SmartDiv();
    }
    public function span(){
        return new SmartSpan();
    }
    public function span_auto($content){
        $span = $this->span();
        $span->width_auto();
        $span->add_child($content);
        return $span;
    }
    public function heading3(){
        return new SmartHeading3();
    }
    public function heading1(){
        return new SmartHeading1();
    }
    public function heading2()
    {
        return new SmartHeading2();
    }
    public function heading4(){
        return new SmartHeading4();
    }

    public function anchor()
    {
        return new SmartLink();
    }

    public function paragraph()
    {
        return new SmartParagraph();
    }

    public function about($string)
    {
        return $this->heading1()->add_child($string)->opacity("0.54")->padding("1.0em")->font_variant("initial");
    }

    public function header()
    {
        return new SmartHeader();
    }

    public function custom_tag($string)
    {
        return new SmartCustomTag($string);
    }
}