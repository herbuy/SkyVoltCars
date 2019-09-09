<?php


class PageHeaderForHome extends SmartDiv{
    public function __construct()
    {
        parent::__construct();
        $this->add_child($this->column_list());
        //some black separator
        $this->add_child(ui::html()->div()->background_color(
            //ui::colors()->header_bg()->LTimes(0.9)
            ui::colors()->footer_bg()
        )->height("16px")->box_shadow("-5px -5px 15px ".ui::colors()->header_bg()->LTimes(0.8))
        );
    }

    private function icon_and_text($icon,$text)
    {
        $layout = new LayoutForNColumns();
        $layout->addNewColumn()->add_child($icon)->width_auto()->margin_right("1.0em")->vertical_align_middle();
        $layout->addNewColumn()->add_child($text)->width_auto()->vertical_align_middle();
        return $layout;
    }

    private function column_list()
    {
        $layout = new LayoutForNColumns();

        $layout->addNewColumn()->add_child($this->getHtmlForLogo())->margin_top("0.7em")->
        font_size("1.3em")->
        width_auto()->padding("0px 1.0em")->font_variant("initial");

        $this->addLink1($layout);
        $this->addLink2($layout);
        $this->addLink3($layout);
        $this->addLink4($layout);
        $this->addLink5($layout);
        $this->addLink6($layout);
        $this->addLink7($layout);
        
        return $layout->
        background_color(ui::colors()->header_bg())->
        color(ui::colors()->white())->
        font_weight_bold()->width("80em")
            ;
    }

    /**
     * @param \LayoutForNColumns $layout
     * @param \UrlForResource $url_object
     *  @param \SmartImage $icon_object
     */
    protected function addLink($layout,$url_object,$icon_object,$text){
        $layout->addNewColumn()->add_child(
            $url_object->toLink()->add_child(
                $this->icon_and_text(
                    $icon_object->width("2.0em"),
                    $text
                )
            )->add_class(ui::css_classes()->header_nav_link())
        )->width_auto();
    }

    private function logo()
    {
        return ui::urls()->home()->toLink()->add_child(
            ui::images()->logo()->width("20em")
        )->add_class(ui::css_classes()->header_nav_link());
    }



    protected function addLink1($layout)
    {
        $this->addLink($layout, ui::urls()->home(), ui::images()->home_icon_white(), "HOME");
    }

    protected function addLink2($layout)
    {
        $this->addLink($layout, ui::urls()->news(), ui::images()->contact_icon_white(), "NEWS");
    }

    protected function addLink3($layout)
    {
        $this->addLink($layout, ui::urls()->reviews(), ui::images()->contact_icon_white(), "REVIEWS");
    }
    protected function addLink4($layout)
    {
        $this->addLink($layout, ui::urls()->videos(), ui::images()->contact_icon_white(), "VIDEOS");
    }
    protected function addLink5($layout)
    {
        $this->addLink($layout, ui::urls()->gallery(), ui::images()->contact_icon_white(), "GALLERY");
    }

    protected function addLink6($layout)
    {
        $this->addLink($layout, ui::urls()->tips(), ui::images()->contact_icon_white(), "MAINTENANCE TIPS");
    }
    protected function addLink7($layout)
    {
        $this->addLink($layout, ui::urls()->jobs(), ui::images()->contact_icon_white(), "JOBS");
    }

    private function getHtmlForLogo()
    {
        //return $this->logo()

        $logo = ui::html()->span()->add_child("skyvolt")->width_auto() .
        ui::html()->span()->add_child("cars")->width_auto()->color("orange") .
        ui::html()->span()->add_child(".com")->width_auto();

        return ui::urls()->home()->toLink()->add_child(
            $logo
        )->color(ui::colors()->white())->font_family("georgia,serif");
    }

}

class PageHeaderForAdmin extends PageHeaderForHome{
    protected function addLink1($layout)
    {
        $this->addLink($layout, ui::urls()->adminPage(), ui::images()->home_icon_white(), "Admin");
    }

    protected function addLink2($layout)
    {
        $this->addLink($layout, ui::urls()->adminViewPosts(), ui::images()->about_icon_white(), "Drafts");
    }

    protected function addLink3($layout)
    {
        $this->addLink($layout, ui::urls()->adminViewPostsPublished(), ui::images()->about_icon_white(), "Published");
    }

    protected function addLink4($layout)
    {
        $this->addLink($layout, ui::urls()->statistics(), ui::images()->about_icon_white(), "Statistics");
    }
    protected function addLink5($layout)
    {
    }
    protected function addLink6($layout)
    {
    }
    protected function addLink7($layout)
    {
    }
}
