<?php
class LayoutForHomePage_Obsolete extends LayoutForTwoColumns{
    public function __construct()
    {
        parent::__construct();

        $this->leftColumn()->width("70%")->display_inline_block()->vertical_align_top();
        $this->rightColumn()->width("30%")->display_inline_block()->vertical_align_top()->margin_left("-1px");

        //$this->leftColumn()->add_child(ui::sections()->preNav()->add_class("pre_nav"));
    }
}
class LayoutForAdminPage extends LayoutForHomePage_Obsolete{
    public function __construct()
    {
        parent::__construct();
    }
}


class PageLayoutForAdminEditPost extends SmartDiv{
    public function __construct()
    {
        parent::__construct();
             
    }
}



class LayoutForHomePage extends SmartDiv{
    private $row1;
    private $row2;
    private $row3;
    private $content_for_reviews ='content for reviews';


    public function __construct()
    {
        parent::__construct();

        $this->row1 = new LayoutForThreeColumns();
        $this->row2 = new LayoutForTwoColumns();
        $this->row3 = new SmartDiv();

        $this->row1->leftColumn()->add_class(ui::css_classes()->home_row1_left_col());
        $this->row1->middleColumn()->add_class(ui::css_classes()->home_row1_middle_col());
        $this->row1->rightColumn()->add_class(ui::css_classes()->home_row1_right_col());

        $this->row2->leftColumn()->add_class(ui::css_classes()->home_row2_left_col());
        $this->row2->rightColumn()->add_class(ui::css_classes()->home_row2_right_col());

        $this->row2->border_top("1px solid #888")->margin_top("1.0em");
        $this->row3->border_top("1px solid #888")->margin_top("1.0em");
                
        $this->add_some_content_before_main_content();        

    }

    
    public function section_for_reviews(){
        return $this->row1->middleColumn();
    }
    public function section_for_exporter_reviews(){
        return $this->row1->rightColumn();
    }
    public function section_for_news(){
        return $this->row1->leftColumn();
    }
    public function section_for_car_maintenance(){
        return $this->row2->leftColumn();
    }
    public function section_for_car_exporters(){
        return $this->row2->rightColumn();
    }
    public function section_for_careers(){
        return $this->row3;
    }
    
    private $rendered = false;
    public function __toString()
    {
        if(!$this->rendered){
            $this->add_child($this->row1);
            $this->add_child($this->row2);
            $this->add_child($this->row3);
        }        
        return parent::__toString();
    }

    protected function add_some_content_before_main_content()
    {
        //===== add some content before reviews

        //first is the tag line
        $vision = new SmartDiv();
        $vision->add_child(
            $this->tagline()
        );
        $this->section_for_reviews()->add_child(
            ui::html()->div()->add_child(
                $vision
            )->add_class(ui::css_classes()->card_background())->
            background_color(ui::colors()->header_bg())->
            color(ui::colors()->white())->
            border_radius("0px 0px 5px 5px")->
            margin_bottom("4px")->border_top_width("0px")
        );

        //second is the welcome video
        $welcome_video = new LinkToYoutubeVideoUsingIFrame("CxC4mzmttwY", "100%", "50%");
        $welcome_video->border("0px solid transparent")->
        background_color(ui::colors()->white())->
        color(ui::colors()->white())->
        border_radius("0px 0px 5px 5px");
        $this->section_for_reviews()->add_child($welcome_video);
    }

    private function tagline()
    {
        $text = "Africa's number one source of latest automobile news and information from across the world";
        $layout = new SmartDiv();
        $layout/*->color(
            //"slategrey"
            ui::colors()->secondary_text()
        )*/->font_family("georgia,serif")->font_size("1.5em")->text_align_center();
        $layout->add_child($text);
        return $layout;
    }
}

class PageLayoutForLandingPage extends SmartDiv{
    public function __construct($left_content,$middle_content,$right_content)
    {
        parent::__construct();
        
        $this->add_child(
            ui::html()->span()->add_child(
                $left_content
            )->add_class(ui::css_classes()->motoka_landing_page_sidebar())
            .
            ui::html()->span()->add_child(
                $middle_content
            )->add_class(ui::css_classes()->motoka_landing_page_middle_content())
            .
            ui::html()->span()->add_child(
                $right_content
            )->add_class(ui::css_classes()->motoka_landing_page_sidebar())
        )->position_relative();
    }
}

class LayoutForOtherPage extends LayoutForHomePage{
    protected function add_some_content_before_main_content(){
        
    }
}


class LayoutForHomePageOfPost extends SmartDiv{
    private $row1;
    
    public function __construct()
    {
        parent::__construct();

        $this->row1 = new LayoutForThreeColumns();
       
        $this->row1->leftColumn()->add_class(ui::css_classes()->post_details_page_row1_left_col());
        $this->row1->middleColumn()->add_class(ui::css_classes()->post_details_page_row1_middle_col());
        $this->row1->rightColumn()->add_class(ui::css_classes()->post_details_page_row1_right_col());

        $this->add_class(ui::css_classes()->layout_for_post_details_page());
        
    }

    public function section_for_news(){
        return $this->row1->leftColumn();
    }
    public function section_for_article_details(){
        return $this->row1->middleColumn();
    }
    public function section_for_exporter_reviews(){
        return $this->row1->rightColumn();
    }
    
    private $rendered = false;
    public function __toString()
    {
        if(!$this->rendered){
            $this->add_child($this->row1);
        }
        return parent::__toString();
    }
}

class PageLayoutForAdminSection extends SmartDiv{
    
    public function __construct($left_content,$right_content,$menu_text = "CLICK TO SHOW/HIDE MENU"){
        parent::__construct();

        $close_button = ui::html()->div()->add_child(
            $menu_text)->width_auto()->font_size("1.5em")->font_weight_bold()->padding("4px 1.0em 8px 1.0em")->cursor_pointer()->background_color("#444")->color("#fff")->text_decoration_underline()->
        set_id(app::values()->cmp_admin_menu_toggle_button())->
        add_javascript_from_file("js/menu_toggle.js");


        $this->add_child(
            ui::html()->div()->add_child(
                $close_button
            )->position_absolute()->z_index("5")->width_100percent()
            .
            ui::html()->span()->add_child(
                ui::html()->div()->add_child(
                    $left_content
                )->padding_bottom("2.0em")->padding_top("3.0em")
            )->add_class(ui::css_classes()->motoka_admin_page_sidebar())->height_100percent()->overflow_y("scroll")->overflow_x_hidden()->set_id(app::values()->cmp_admin_menu())->position_relative()
            .
            ui::html()->span()->add_child(
                ui::html()->div()->add_child(
                    $right_content
                )->padding_bottom("2.0em")->padding_top("3.0em")
            )->add_class(ui::css_classes()->motoka_admin_page_middle_content())->height_100percent()->overflow_y("scroll")->overflow_x_hidden()
        );
    }
}