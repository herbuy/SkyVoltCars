<?php

class ui{
    
    public static function kickers(){
        return new KickerFactory();
    }
    
    public static function pages(){
        return new PageFactory();
    }

    public static function sections()
    {
        return new SectionFactory();
    }

    public static function links()
    {
        return new LinkFactory();
    }

    public static function urls()
    {
        return new UrlFactory();
    }

    public static function browser_fields()
    {
        return app::browser_fields();
    }

    public static function page_layouts()
    {
        return new PageLayoutFactory();
    }

    public static function row_layouts()
    {
        return new RowLayoutFactory();
    }

    
    public static function html()
    {
        //todo: we dont need more than one instance
        return new HtmlElementFactory();
    }

    public static function forms()
    {
        return new FormFactory();
    }

    public static function buttons()
    {
        return new ButtonFactory();
    }

    public static function cmds()
    {
        return new CmdFactory();
    }

    public static function exception()
    {
        return new UIExceptionFactory();
    }

    public static function images()
    {
        return new ImageFactory();
    }

    public static function pickers()
    {
        return new PickerFactory();
    }

    public static function lists()
    {
        return new ListFactory();
    }

    public static function time_in_seconds($timestamp)
    {
        return new TimeInSeconds($timestamp);
    }
   public static function time_posted($timestamp){       
       return sprintf(
           "%s - %s",
           ui::time_in_seconds($timestamp)->asTimeAgo(),date('D M d, Y',$timestamp) );
   }
    public static function time_posted_from_reader($item_reader){
        return self::time_posted($item_reader->timestamp());
    }
    
    public static function css_classes()
    {
        return new CSSClassFactory();
    }

    public static function error_html()
    {
        $html = new SmartDiv();
        $html
            ->add_class(ui::css_classes()->error_message_host());
        return $html;
    }

    public static function form_feedback()
    {
        return new FormFeedbackFactory();
    }

    public static function date_from_timestamp($timestamp)
    {
        return date("D d M, Y", intval($timestamp));
    }

    public static function colors()
    {
        return new ColorFactory();
    }

    public static function external_urls()
    {
        return new ExternalUrls();
    }

    public static function section_banners()
    {
        return new SectionBanners();
    }

    public static function section_ids()
    {
        return new StaticSectionIds();
    }

    public static function text_with_contrast_colors($string, $string1)
    {
        $container = new SmartSpan();

        $item1 = new SmartSpan();
        $item1->add_child($string)->width_auto()->color(ui::colors()->header_bg());

        $container->add_child($item1."&nbsp;");

        $item2 = new SmartSpan();
        $item2->add_child($string1)->width_auto();
        $container->add_child($item2);

        return $container;
    }
    public static function h2_with_contrast_colors($string, $string1){
        return ui::html()->heading2()->add_child(ui::text_with_contrast_colors($string,$string1));
    }

    public static function page_headers()
    {
        return new PageHeaderFactory();
    }

    public static function _1px_dashed_ddd()
    {
        return "1px dashed #ddd";
    }

    public static function height_of_careers()
    {
        return "5.0em";
    }

    public static function _1px_solid_form_border()
    {
        return "1px solid ".ui::colors()->form_border();
    }

    public static function responsive_css()
    {
        return new FactoryForResponsiveCSS();
    }

    public static function borders()
    {
        return new FactoryForBorders();
    }

    public static function css_nth_child()
    {
        return new CSSQueryForNthChild();
    }

}

class FactoryForResponsiveCSS{

    public function body()
    {

        new ResponsiveFontSizeForElement(
            (new ResponsiveValuesForCSSProperty())->
            setCSSElement(
                CSSElementOfType("body")
            )->
            setFor240(app::utils()->linear_interpolation(240))->
            setFor320(app::utils()->linear_interpolation(320))->
            setFor360(app::utils()->linear_interpolation(360))->
            setFor480(app::utils()->linear_interpolation(480))->
            setFor540(app::utils()->linear_interpolation(540))->
            setFor600(app::utils()->linear_interpolation(600))->
            setFor720(app::utils()->linear_interpolation(720))->
            setFor768(app::utils()->linear_interpolation(768))->
            setFor800(app::utils()->linear_interpolation(800))->
            setFor854(app::utils()->linear_interpolation(854))->
            setFor960(app::utils()->linear_interpolation(960))->
            setFor1200(app::utils()->linear_interpolation(1200))->
            setFor1280(app::utils()->linear_interpolation(1280))->
            setFor1320(app::utils()->linear_interpolation(1320))
        );


    }
    
    public function home_page()
    {

        new ResponsiveWidthForElement(
            (new ResponsiveValuesForCSSProperty())->
            setCSSElement(
                CSSElementOfClass(ui::css_classes()->home_row1_left_col())->
                or_class(ui::css_classes()->home_row1_right_col())
            )->
            setFor480("80%")->setFor960("25%")
        );

        new ResponsiveMarginForElement(
            (new ResponsiveValuesForCSSProperty())->
            setCSSElement(
                CSSElementOfClass(ui::css_classes()->home_row1_left_col())->
                or_class(ui::css_classes()->home_row1_right_col())
            )->
            setFor480("0px 10%")->setFor960("0px")
        );

        //middle column of row1
        new ResponsiveWidthForElement(
            (new ResponsiveValuesForCSSProperty())->
            setCSSElement(
                CSSElementOfClass(ui::css_classes()->home_row1_middle_col())
            )->
            setFor480("80%")->setFor960("50%")
        );
        new ResponsiveMarginForElement(
            (new ResponsiveValuesForCSSProperty())->
            setCSSElement(
                CSSElementOfClass(ui::css_classes()->home_row1_middle_col())
            )->
            setFor480("0px 10%")->setFor960("0%")
        );

        //ROW 2
        //middle column of row1
        new ResponsiveWidthForElement(
            (new ResponsiveValuesForCSSProperty())->
            setCSSElement(
                CSSElementOfClass(ui::css_classes()->home_row2_left_col())->
                or_class(ui::css_classes()->home_row2_right_col())
            )->
            setForDefault("100%")->setFor480("50%")
        );
        
    }

    public function post_details_page(){
        //row1-left col
        new ResponsiveDisplayForElement(
            (new ResponsiveValuesForCSSProperty())->
            setCSSClass(ui::css_classes()->post_details_page_row1_left_col())->
            setForDefault("none")->setFor960("inline-block")
        );
        
        new ResponsiveWidthForElement(
            (new ResponsiveValuesForCSSProperty())->
            setCSSClass(ui::css_classes()->post_details_page_row1_left_col())->
            setForDefault("100%")->setFor960("25%")
        );

        //row1-middle col

        new ResponsiveWidthForElement(
            (new ResponsiveValuesForCSSProperty())->
            setCSSClass(ui::css_classes()->post_details_page_row1_middle_col())->
            setForDefault("100%")->setFor960("50%")
        );

        //row1-right
        new ResponsiveWidthForElement(
            (new ResponsiveValuesForCSSProperty())->
            setCSSClass(ui::css_classes()->post_details_page_row1_right_col())->
            setForDefault("100%")->setFor960("25%")
        );

        //overall page layout
        new ResponsiveMarginForElement(
            (new ResponsiveValuesForCSSProperty())->
            setCSSClass(ui::css_classes()->layout_for_post_details_page())->
            setForDefault("0%")->setFor480("0% 10%")->setFor960("0%")
        );

        //article details
        new ResponsiveMarginForElement(
            (new ResponsiveValuesForCSSProperty())->
            setCSSClass(ui::css_classes()->post_details_page_center_stage())->
            setForDefault("4px 8px")->setFor960("4px 1.0em")
        );

    }

    public function page_footer()
    {
        //row1-col1
        new ResponsiveWidthForElement(
            (new ResponsiveValuesForCSSProperty())->
            setCSSClass(ui::css_classes()->page_footer_row1_col1())->
            setForDefault("100%")->setFor480("50%")->setFor600("40%")->setFor720("30%")->setFor960("25%")
        );
        //row1-col2
        new ResponsiveWidthForElement(
            (new ResponsiveValuesForCSSProperty())->
            setCSSClass(ui::css_classes()->page_footer_row1_col2())->
            setForDefault("100%")->setFor480("50%")->setFor600("60%")->setFor720("70%")->setFor960("75%")
        );
    }

    /** @param \PageCSSBaseClass $page_css_base_class */
    public function motoka_item($page_css_base_class)
    {
        //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@

        //all items
        $page_css_base_class->addCSSFor(
            ui::css_nth_child()->
            parent(ui::css_classes()->motoka_item())->
            all()->
            css()->
            background_color(ui::colors()->white())->
            //border(ui::borders()->panel())->
            padding("1.0em")->
            margin("0px 4px")
        );
        //every 2 elemt, starting from 3
        $page_css_base_class->addCSSFor(
            ui::css_nth_child()->
            parent(ui::css_classes()->motoka_item())->
            indexes(array("2n+2"))->
            css()->margin_top("8px")->border_bottom_width("0px")
        );
        $page_css_base_class->addCSSFor(
            ui::css_nth_child()->
            parent(ui::css_classes()->motoka_item())->
            indexes(array("2n+3"))->
            css()->border_top("1px dashed #ddd")
        );

        //title of middle item
        $page_css_base_class->addCSSFor(
            ui::css_nth_child()->
            parent(ui::css_classes()->motoka_middle_item())->
            all()->
            css()->padding_left("1.5em")->padding_right("1.5em")
        );

        //middle title
        $page_css_base_class->addCSSFor(
            ui::css_nth_child()->
            parent(ui::css_classes()->motoka_middle_item())->
            all()->
            child(ui::css_classes()->title_of_item_in_section_for_main_posts())->
            all()->
            css()->font_size("1.5em")
        );

        //middle kicker
        /*$page_css_base_class->addCSSFor(
            ui::css_nth_child()->
            parent(ui::css_classes()->motoka_middle_item())->
            all()->
            child(ui::css_classes()->post_kicker())->
            all()->
            css()->font_size("1.5em")->border_bottom("1px dotted #ddd")->display_block()
        );*/
    }

    public function landing_page_layout()
    {
        //SIDE BARS
        new ResponsiveWidthForElement(
            (new ResponsiveValuesForCSSProperty())->
            setCSSElement(
                CSSElementOfClass(ui::css_classes()->motoka_landing_page_sidebar())
            )
                ->setForDefault("100%")
                ->setFor600("28%")


        );
        //positioning
        new ResponsivePositionForElement(
            (new ResponsiveValuesForCSSProperty())->
            setCSSElement(
                CSSElementOfClass(ui::css_classes()->motoka_landing_page_sidebar())
            )
                ->setForDefault("static")
                ->setFor600("fixed")
        );
        
        //MIDDLE CONTENT
        new ResponsiveWidthForElement(
            (new ResponsiveValuesForCSSProperty())->
            setCSSElement(
                CSSElementOfClass(ui::css_classes()->motoka_landing_page_middle_content())
            )
                ->setForDefault("100%")
                ->setFor600("44%")
        );
        new ResponsiveMarginLeftForElement(
            (new ResponsiveValuesForCSSProperty())->
            setCSSElement(
                CSSElementOfClass(ui::css_classes()->motoka_landing_page_middle_content())
            )
                ->setForDefault("0%")
                ->setFor600("28%")
        );
        
    }

    public function admin_page_layout()
    {

        //SIDE BARS
        new ResponsiveWidthForElement(
            (new ResponsiveValuesForCSSProperty())->
            setCSSElement(
                CSSElementOfClass(ui::css_classes()->motoka_admin_page_sidebar())
            )
                ->setForDefault("100%")
                ->setFor600("30%")

        );

        new ResponsiveDisplayForElement(
            (new ResponsiveValuesForCSSProperty())->
            setCSSElement(
                CSSElementOfClass(ui::css_classes()->motoka_admin_page_sidebar())
            )
                ->setForDefault("none")
                ->setFor600("inline-block")

        );

        //MIDDLE CONTENT
        new ResponsiveWidthForElement(
            (new ResponsiveValuesForCSSProperty())->
            setCSSElement(
                CSSElementOfClass(ui::css_classes()->motoka_admin_page_middle_content())
            )
                ->setForDefault("100%")
                ->setFor600("70%")
        );
        
    }

    public function header()
    {

        //SIDE BARS
        new ResponsiveOverflowXForElement(
            (new ResponsiveValuesForCSSProperty())->
            setCSSElement(
                CSSElementOfType("header")
            )
                ->setForDefault("scroll")
                ->setFor600("hidden")

        );
        

    }

    /** @param \PageCSSBaseClass $page_css_base_class */
    public function picture_gallery_item($page_css_base_class)
    {
        $page_css_base_class->addCSSFor(
            ui::css_nth_child()->
            parent(ui::css_classes()->motoka_gallery_pic_host())->
            all()->
            css()->
            display_inline_block()->
            postion_absolute()->
            width("33.3%")->
            height("33.3%")->
            overflow_hidden()/*->
            background_color("#ddd")->
            background_position_center()->
            background_size_cover()->
            background_repeat_no_repeat()*/
        );
        
        $page_css_base_class->addCSSFor(
            ui::css_nth_child()->
            parent(ui::css_classes()->motoka_gallery_pic_host())->
            indexes(2)->
            css()->left("33.3%")->top("0%")//->background_color("#ccc")
        );

        $page_css_base_class->addCSSFor(
            ui::css_nth_child()->
            parent(ui::css_classes()->motoka_gallery_pic_host())->
            indexes(3)->
            css()->left("66.6%")->top("0%")//->background_color("#bbb")
        );
        $page_css_base_class->addCSSFor(
            ui::css_nth_child()->
            parent(ui::css_classes()->motoka_gallery_pic_host())->
            indexes(4)->
            css()->left("0%")->top("33.3%")//->background_color("#aaa")
        );
        $page_css_base_class->addCSSFor(
            ui::css_nth_child()->
            parent(ui::css_classes()->motoka_gallery_pic_host())->
            indexes(5)->
            css()->left("0%%")->top("66.6%")//->background_color("#999")
        );
        $page_css_base_class->addCSSFor(
            ui::css_nth_child()->
            parent(ui::css_classes()->motoka_gallery_pic_host())->
            indexes(6)->
            css()->left("33.3%")->top("33.3%")->width("66.6%")->height("66.6%")//->background_color("#888")
        );
    }

    /** @param \PageCSSBaseClass $page_css_base_class */
    public function motoka_small_item($page_css_base_class)
    {
        new ResponsiveCSSForMotokaSmallItem($page_css_base_class);
    }

}

class ResponsiveCSSForMotokaSmallItem{
    /** @param \PageCSSBaseClass $page_css_base_class */
    public function __construct($page_css_base_class)
    {
        $page_css_base_class->addCSSFor(
            ui::css_nth_child()
                ->
                parent(ui::css_classes()->motoka_small_item())->
                all()->
                css()->
                display_inline_block()->
                width("99%")->overflow_hidden()->
                vertical_align_top()->background_color("transparent")
        );


        new ResponsiveHeightForElement(
            (new ResponsiveValuesForCSSProperty())->
            setCSSElement(
                CSSElementOfClass(ui::css_classes()->motoka_small_item_image())
            )->
            setFor240($this->calculateForWindowSize(240))->
            setFor320($this->calculateForWindowSize(320))->
            setFor360($this->calculateForWindowSize(360))->
            setFor480($this->calculateForWindowSize(480))->
            setFor540($this->calculateForWindowSize(540))->
            setFor600($this->calculateForWindowSize(600))->
            setFor720($this->calculateForWindowSize(720))->
            setFor768($this->calculateForWindowSize(768))->
            setFor800($this->calculateForWindowSize(800))->
            setFor854($this->calculateForWindowSize(854))->
            setFor960($this->calculateForWindowSize(960))->
            setFor1200($this->calculateForWindowSize(1200))->
            setFor1280($this->calculateForWindowSize(1280))->
            setFor1320($this->calculateForWindowSize(1320))
        );

    }
    private function calculateForWindowSize($number){
        return app::utils()->linear_interpolation($number,240,1200,8,10,3)."em";
    }
}

class PageHeaderFactory{
    public static function home(){
        return new PageHeaderForHome();
    }
    public static function admin(){

        return new PageHeaderForAdmin();
    }
}

class StaticSectionIds{
    public function contact_info(){
        return __FUNCTION__;
    }    
}

class ExternalUrls{
    public function our_facebook_page(){
        return "https://www.facebook.com/SkyVoltCars";
    }
    public function our_twitter_page(){
        return "https://twitter.com/SkyVoltCars";
    }
    public function our_youtube_channel(){
        return "https://www.youtube.com/channel/_UCXGFCVIasnNTMZvxRIjJ1uw";
    }
    
    public function motoka_ug(){
        return "http://www.motoka.ug";
    }
}

class SectionBanners0000{
    private function icon_for_proposition(){
        $icon = new SmartSpan();
        $icon->width_auto();
        $icon->border("0.5em solid ".ui::colors()->section_descriptor())->margin_left("0.5em");

        $icon->border_bottom_color("transparent")->border_right_color("transparent")->border_left_color("transparent");
        return $icon;
    }
    
    private function createBanner($title, $proposition)
    {
        $layout = new SmartDiv();
        $layout->add_child(ui::html()->heading2()->add_child($title)->color("auto"));
        $layout->add_child(
            ui::html()->div()->add_child(
                //$this->icon_for_proposition().
                $proposition
            )->
            //background_color("darkslategrey")->
            background_color(ui::colors()->section_descriptor())->
            color("white")->border_radius("5px")->padding("0.5em")
        );
        $layout->
        background_color(ui::colors()->white())->
        color("#444")->font_variant("initial")->margin_bottom("-0.8em");

        $layout->add_child(
            ui::html()->div()->add_child(
                $this->icon_for_proposition()
            )
        );

        $layout->padding("4px 0.5em")->margin_top("8px")->
        border("4px solid gray")->add_class(ui::css_classes()->card_margin());
        return $layout;
    }
    #----------------
    public function exporter_reviews(){
        return
            $this->createBanner(
                ui::text_with_contrast_colors("EXPORTER","REVIEW"),"Read what others have said about the different car exporters"
            );
    }

    public function car_videos(){
        return
            $this->createBanner(
                ui::text_with_contrast_colors("CAR","VIDEOS"),"Watch these videos and see for yourself"
            );
    }

    public function news(){
        return
            $this->createBanner(
                ui::text_with_contrast_colors("NEWS","&amp; EVENTS"),"Check out the latest news in the car industry"
            );
    }

    public function car_reviews(){
        return
            $this->createBanner(
                ui::text_with_contrast_colors("CAR","REVIEWS"),"Read what people have said about these cars"
            );
    }
    public function seller_reviews(){
        return
            $this->createBanner(
                ui::text_with_contrast_colors("CAR","EXPORTERS"),"View information about the various car exporters"
            );
    }

    public function car_pictures(){
        return
            $this->createBanner(
                ui::text_with_contrast_colors("GALLERY","PICTURES"),"A selection of pictures from our picture gallery"
            );
    }
    
    public function careers(){
        return
            $this->createBanner(
                ui::text_with_contrast_colors("JOB","OPPORTUNITIES"),"Get access to opportunities to work in the car industry"
            );
    }

    public function car_maintenance()
    {
        return
            $this->createBanner(
                ui::text_with_contrast_colors("CAR","MAINTENANCE"),"Get professional advice on how to keep your car in best shape"
            );
    }

    public function about_us()
    {
        return
            $this->createBanner(
                "ABOUT US","Here is a brief about this website"
            );
    }

    public function taxes()
    {
        return
            $this->createBanner(
                "TAXES","Know how much tax you will have to pay"
            );
    }

    public function contact_us()
    {
        return
            $this->createBanner(
                "CONTACT US","Get in touch with us so we can help you import a car, make a purchase, review a car or seller of your interest"
            );
    }

}

class SectionBanners{
    private function icon_for_proposition(){
        $icon = new SmartSpan();
        $icon->width_auto();
        return $icon;
    }
    private function createBanner($title, $proposition)
    {
        $layout = new SmartDiv();
        $layout->add_child(ui::html()->div()->add_child($title));
        $layout->add_child(
            ui::html()->div()->add_child(
                $proposition
            )
        );

        $layout->add_child(
            ui::html()->div()->add_child(
                $this->icon_for_proposition()
            )
        );
        $layout->
        add_class(ui::css_classes()->card_margin())->
        add_class(ui::css_classes()->card_padding());
        $layout->
        background_color(ui::colors()->panel_header_bg())->
        border(ui::borders()->panel());
        return $layout;
    }
    #----------------
    public function exporter_reviews(){
        return
            $this->createBanner(
                ui::text_with_contrast_colors("EXPORTER","REVIEW"),"Read what others have said about the different car exporters"
            );
    }

    public function car_videos(){
        return
            $this->createBanner(
                ui::text_with_contrast_colors("CAR","VIDEOS"),"Watch these videos and see for yourself"
            );
    }

    public function news(){
        return
            $this->createBanner(
                ui::text_with_contrast_colors("NEWS","&amp; EVENTS"),"Check out the latest news in the car industry"
            );
    }

    public function car_reviews(){
        return
            $this->createBanner(
                ui::text_with_contrast_colors("CAR","REVIEWS"),"Read what people have said about these cars"
            );
    }
    public function seller_reviews(){
        return
            $this->createBanner(
                ui::text_with_contrast_colors("CAR","EXPORTERS"),"View information about the various car exporters"
            );
    }

    public function car_pictures(){
        return
            $this->createBanner(
                ui::text_with_contrast_colors("GALLERY","PICTURES"),"A selection of pictures from our picture gallery"
            );
    }

    public function careers(){
        return
            $this->createBanner(
                ui::text_with_contrast_colors("JOB","OPPORTUNITIES"),"Get access to opportunities to work in the car industry"
            );
    }

    public function car_maintenance()
    {
        return
            $this->createBanner(
                ui::text_with_contrast_colors("CAR","MAINTENANCE"),"Get professional advice on how to keep your car in best shape"
            );
    }

    public function about_us()
    {
        return
            $this->createBanner(
                "ABOUT US","Here is a brief about this website"
            );
    }

    public function taxes()
    {
        return
            $this->createBanner(
                "TAXES","Know how much tax you will have to pay"
            );
    }

    public function contact_us()
    {
        return
            $this->createBanner(
                "CONTACT US","Get in touch with us so we can help you import a car, make a purchase, review a car or seller of your interest"
            );
    }

}

