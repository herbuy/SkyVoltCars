<?php
class PageCSSBaseClass{
    private $css_element_array = array();
    private $media_queries_array = array();
    public function __toString()
    {
        return sprintf("%s %s",
            join(" ",$this->css_element_array), join(" ",$this->media_queries_array)
        );
    }

    public function __construct(){
        $this->callAddCSSForVariousCSSElements();
        $this->instantiateResponsiveCSSClasses();
    }
    /** @param \CSSElement $css_element */
    public function addCSS($css_element){
        $this->css_element_array[] = $css_element->getFullDeclarationAsString();
        return $this;
    }
    public function addCSSFor($css_element){
        $this->addCSS($css_element);
    }

    protected function callAddCSSForVariousCSSElements()
    {
    }

    protected function instantiateResponsiveCSSClasses()
    {
    }

}

class PageCSS extends PageCSSBaseClass{
    protected function callAddCSSForVariousCSSElements(){

        $this->addCSSForNthChild();

        //=========
        $this->addBasicCSS();
        $this->addCardCSS();
        new CSSForListOfReviews($this);
        new CSSForListOfCarExporters($this);
        new CSSForListOfNews($this);
        new CSSForListOfCareers($this);
        new CSSForListOfCarMaintenance($this);
        new CSSForAdminListOfPostsPublished($this);
        new CSSForListOfItemsInExporterReviews($this);
        new CSSForListOfItemsInCarVideos($this);
        new CSSForListOfPictureGallery_Item_All($this);

        $this->responsiveCSS();
        
    }

    private function addBasicCSS()
    {
        $this->addCSS(
            CSSElementOfType("*")->
            width_100percent()->
            margin("0px")->
            border("0px")->
            padding("0px")->
            line_height("1.2em")
        );

        $this->addCSS(
            CSSElementOfType("a")->text_decoration_none()->color(ui::colors()->link_fg())->
            font_variant("initial")
        );

        $this->headerLinks();
        $this->addCSS(
            CSSElementOfType("a")->and_class(ui::css_classes()->footer_link())->            
            color(ui::colors()->white())->
            font_variant("all-small-caps")->border_bottom("1px solid orange")
        );
        
        $this->addCSS(
            CSSElementOfType("a")->subtype("hover")->color(ui::colors()->link_complement())->font_weight_bold()
        );

        $this->addCSS(
            CSSElementOfType("div")->or_type("p")->
            or_type("h1")->or_type("h2")->or_type("h3")->or_type("h4")->or_type("h5")->or_type("h6")->
            width_auto()
        );

        $this->addCSS(
            CSSElementOfType("h1")->or_type("h2")->or_type("h3")->or_type("h4")->or_type("h5")->or_type("h6")->
            margin("0.3em 0px")
        );


        //alternative style for body
        $this->addCSS(
            CSSElementOfType("body")->
            margin("auto")->
            font_family("roboto,helvetica,arial,sans-serif")->
            font_size("17px")->            
            font_variant("all-small-caps")->
            color(ui::colors()->body_color())->
            overflow_x_hidden()
        );

        $this->addCSS(
            CSSElementOfType("iframe")->
            border("0px solid grey")->background_color("#000")->color(ui::colors()->white())
        );

        $this->addCSS(
            CSSElementOfType("span")->
            display_inline_block()->vertical_align_top()->width_100percent()
        );
        $this->addCSS(
            CSSElementOfClass(ui::css_classes()->paragraph_text())->
            font_variant("initial")
        );

        $this->addCSS(
            CSSElementOfClass(ui::css_classes()->time_posted())                
                ->font_weight_normal()->color(ui::colors()->link_complement())->font_variant("initial")->margin_bottom("4px")->font_weight_bold()
        );


        $this->addCSS(
            CSSElementOfType("span")->after_type("br")->after_type("br")->nth_child(2)->
            background_color("#eee")
        );

        $this->addCSS(
            CSSElementOfType("img")->
            border_radius("5px")
        );

        $this->inputs();

        $this->addCSS(
            CSSElementOfType("select")->inside_type("*")->
            cursor_pointer()->height("3.0em")
        );

        $this->addCSS(
            CSSElementOfType("h1")->inside_type("*")->
            width_auto()->
            margin("0px")->
            color(ui::colors()->h1())            
        );


        $this->forms();

        $this->addCSS(
            CSSElementOfClass(ui::css_classes()->element_with_box_shadow())->inside_type("*")
                ->box_shadow("0px 0px 15px ".ui::colors()->footer_bg()->mix(ui::colors()->white(),60))
        );

        $this->addCSS(
            CSSElementOfClass(ui::css_classes()->post_kicker())->inside_type("*")
                ->color(ui::colors()->header_bg())->width_auto()
                ->padding("0px 0.0em")->font_weight_bold()
        );

        $this->addCSS(
            CSSElementOfClass(ui::css_classes()->error_message_host())->inside_type("*")
                ->background_color("#ff7575")->border("1px solid #dd5555")
                ->padding("8px 1.0em")->border_radius("5px")
                ->margin_bottom("1.0em")->box_shadow("5px 5px 5px #aaa")
        );

        $this->addCSS(
            CSSElementOfClass(ui::css_classes()->item_host_for_admin_navigation())->inside_type("*")
                ->width("33.3%")->min_width("200px")

        );

        $this->addCSS(
            CSSElementOfClass(ui::css_classes()->page_footer())->
            background_color(ui::colors()->footer_bg())->
            color("white")->
            padding("1.0em 0.5em")->
            font_variant("initial")->
            font_family("arial,sans-serif")
        );

    }

    private function addCardCSS()
    {
      
        $this->addCSS(
            CSSElementOfClass(ui::css_classes()->card_background())->
            background_color(ui::colors()->white())->
            border(ui::borders()->panel())

        );
        $this->addCSS(
            CSSElementOfClass(ui::css_classes()->card_margin())->
            margin("4px")
        );
        $this->addCSS(
            CSSElementOfClass(ui::css_classes()->card_padding())->
            padding("8px 1.0em")
        );
        $this->addCSS(
            CSSElementOfClass(ui::css_classes()->height_limiter())->
            overflow_hidden()
        );
    }

    private function headerLinks()
    {
        $css_element = CSSElementOfType("a")->and_class(ui::css_classes()->header_nav_link());

        $this->addCSS(
            $css_element->
            //color(ui::colors()->link_fg())->
            color(ui::colors()->white())->            
            padding("8px 1.0em")->
            border_right("1px solid " . ui::colors()->header_bg()->LTimes(0.7))->
            border_left("1px solid " . ui::colors()->header_bg()->LTimes(1.1))->
            set_style("border-radius", "5px 5px 0px 0px")->
            //margin("4px 16px 0px 16px")->
            margin("4px 0px 0px 0px")->
            border_radius("0px")->
            display_inline_block()->
            width_auto()->
            background_color("orange")
        );


        $this->addCSS(
            $css_element->hover()->
            color("#ffa")
        );
    }

    private function forms()
    {
        $this->addCSS(
            CSSElementOfType("form")->inside_type("*")->margin("auto")
        );

        $this->addCSS(
            CSSElementOfType("form")->and_class(ui::css_classes()->form_with_only_button())->
            set_style("box-shadow", "0px 0px 0px transparent")->

            margin_bottom("auto")->
            margin_top("auto")->
            border_radius("0px")->
            background_color("inherit")->border_width("0px")
        );


        $this->addCSS(
            CSSElementOfClass(ui::css_classes()->form_field_host())->inside_type("*")->
            //border(ui::_1px_solid_form_border())->
            margin_top("-1px")->
            margin_bottom("1.0em")->
            //padding("1.0em")->
            background_color("white")->
            //border_radius("0.5em")->
            font_weight_bold()->color(ui::colors()->footer_bg())

        );

        $this->addCSS(
            CSSElementOfClass(ui::css_classes()->form_items_host())->inside_type("*")
                ->margin("1.0em")
                ->padding("0px 1.0em")
        );
    }

    private function inputs()
    {
        $this->addCSS(
            CSSElementOfType("input")->inside_type("*")->
            or_(CSSElementOfType("select")->inside_type("*"))->
            border_radius("0.5em")->
            border(ui::_1px_solid_form_border())->

            background_color(ui::colors()->input_field_bg())->
            padding("0.5em 0px")->text_indent("1.0em")
        );

        $this->addCSS(
            CSSElementOfType("input")->and_(CSSAttribute("type")->set_to_value("submit"))->inside_type("*")->
            cursor_pointer()->border_radius("1.0em")->font_weight_bold()->
            border("1px solid transparent")->background_color("#FFC04C")->
            color(ui::colors()->white())->
            set_style("background", "linear-gradient(180deg,#FFC04C,orange)")
        );

        $this->addCSS(CSSElementOfType("textarea")->padding("1.0em"));
    }

    private function addCSSForNthChild()
    {
        ui::responsive_css()->motoka_item($this);
    }

    /**
     *
     */
    private function responsiveCSS()
    {

        ui::responsive_css()->home_page();
        ui::responsive_css()->post_details_page();
        ui::responsive_css()->page_footer();
        ui::responsive_css()->body();

        ui::responsive_css()->landing_page_layout();
        ui::responsive_css()->admin_page_layout();
        ui::responsive_css()->header();

        ui::responsive_css()->picture_gallery_item($this);
        ui::responsive_css()->motoka_small_item($this);

    }

    
}