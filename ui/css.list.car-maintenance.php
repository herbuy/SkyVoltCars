<?php

class CSSForListOfCarMaintenance extends CSSForListOfMainPosts_Item_2_to_5{
    protected function default_item_width()
    {
        return "20%";
    }
    protected function item_class()
    {
        return ui::css_classes()->item_in_section_for_car_maintenance();
    }
    protected function get_array_of_indexes()
    {
        return array("n+1");
    }

    public function __construct(PageCSSBaseClass $page_css_base_class)
    {
        //parent::__construct($page_css_base_class);
        $arr_indexes = $this->get_array_of_indexes();
        
        new ResponsiveWidthForElement(
            (new ResponsiveValuesForCSSProperty())->
            setCSSElement(
                CSSElementOfClass($this->item_class())
            )->
            setForDefault("100%")->setFor720("50%")
        );

        $page_css_base_class->addCSSFor(
            CSSElementOfClassIfAncestorOfClassAndNthChild(
                $this->image_class(),
                $this->item_class(),$arr_indexes
            )->width("28%")->margin_left("2%")
        );
        $page_css_base_class->addCSSFor(
            CSSElementOfClassIfAncestorOfClassAndNthChild(
                $this->video_class(),
                $this->item_class(),$arr_indexes
            )->display_none()
        );
        $page_css_base_class->addCSSFor(
            CSSElementOfClassIfAncestorOfClassAndNthChild(
                $this->title_class(),
                $this->item_class(),$arr_indexes
            )->width("70%")
        );

        //restrict height
        $page_css_base_class->addCSSFor(
            CSSElementOfClassIfAncestorOfClassAndNthChild(
                $this->height_class(),
                $this->item_class(),$arr_indexes
            )->height(ui::height_of_careers())
        );

        $page_css_base_class->addCSSFor(
            CSSElementOfClassIfAncestorOfClassAndNthChild(
                $this->text_class(),
                $this->item_class(),$arr_indexes
            )->width("75%")
        );

        $page_css_base_class->addCSSFor(
            CSSElementOfClassIfAncestorOfClassAndNthChild(
                $this->padding_class(),
                $this->item_class(),$arr_indexes
            )
        );
        $page_css_base_class->addCSSFor(
            CSSElementOfClassIfAncestorOfClassAndNthChild(
                $this->content_class(),
                $this->item_class(),$arr_indexes
            )->display_none()
        );

        $page_css_base_class->addCSS(
            CSSElementOfClassAndNthChild(
                $this->item_class(),array("n+".app::start_index_for_hiding_home_items()->car_maintenance())
            )->display_none()
        );


    }
}

class CSSForAdminListOfPostsPublished extends CSSForListOfCarMaintenance{
    protected function item_class()
    {
        return ui::css_classes()->item_in_section_for_admin_posts_published();
    }

    public function __construct(PageCSSBaseClass $page_css_base_class)
    {
        parent::__construct($page_css_base_class);

        $page_css_base_class->addCSSFor(
            CSSElementOfClassIfAncestorOfClassAndNthChild(
                $this->image_class(),
                $this->item_class(),$this->get_array_of_indexes()
            )->max_width("50%")
        );

        $page_css_base_class->addCSSFor(
            CSSElementOfClassIfAncestorOfClassAndNthChild(
                ui::css_classes()->actions_after_image_of_item_in_section_for_main_posts(),
                $this->item_class(),$this->get_array_of_indexes()
            )->max_width("50%")->width("10em")->margin_left("1.0em")
        );
    }
}
