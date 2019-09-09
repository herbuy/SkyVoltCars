<?php
class CSSForListOfReviews{
       
    /** @param \PageCSSBaseClass $page_css_base_class */
    public function __construct($page_css_base_class)
    {       
        
        new CSSForListOfMainPosts_Item_1($page_css_base_class);
        new CSSForListOfMainPosts_Item_2_to_5($page_css_base_class);
        //new CSSForListOfReviews_Item_6_to_9($page_css_base_class);
        new CSSForListOfReviews_StartingFrom10($page_css_base_class);

    }
    protected function get_array_of_indexes()
    {
        return array("n+1");
    }
    protected function image_class()
    {
        return ui::css_classes()->image_of_item_in_section_for_main_posts();
    }

    protected function video_class()
    {
        return ui::css_classes()->video_of_item_in_section_for_main_posts();
    }

    protected function item_class()
    {
        return ui::css_classes()->item_in_section_for_main_posts();
    }

    protected function text_class()
    {
        return ui::css_classes()->text_for_item_in_section_for_main_posts();
    }

    protected function content_class()
    {
        return ui::css_classes()->content_of_item_in_section_for_main_posts();
    }

    protected function title_class()
    {
        return ui::css_classes()->title_of_item_in_section_for_main_posts();
    }

    protected function padding_class()
    {
        return ui::css_classes()->text_padding_for_item_for_main_posts();
    }
    protected function time_class()
    {
        return ui::css_classes()->time_posted();
    }

    protected function height_class()
    {
        return ui::css_classes()->height_limiter();
    }

}
class CSSForListOfMainPosts_Item_1 extends CSSForListOfReviews{
    protected function get_array_of_indexes()
    {
        return array(1,4,7,10);
    }
    public function __construct($page_css_base_class){
        //parent::__construct($page_css_base_class);
        $arr_indexes = $this->get_array_of_indexes();


        $page_css_base_class->addCSSFor(
            CSSElementOfClassAndNthChild($this->item_class(),$arr_indexes)->
            width("100%")
        );

        $page_css_base_class->addCSSFor(
            CSSElementOfClassIfAncestorOfClassAndNthChild(
                $this->title_class(),
                $this->item_class(),$arr_indexes
            )->
            font_size("1.5em")->padding_bottom("16px")->
            font_weight_bold()
        );

        $page_css_base_class->addCSSFor(
            CSSElementOfClassIfAncestorOfClassAndNthChild(
                $this->image_class(),
                $this->item_class(),$arr_indexes
            )->width("100%")
        );
        $page_css_base_class->addCSSFor(
            CSSElementOfClassIfAncestorOfClassAndNthChild(
                $this->video_class(),
                $this->item_class(),$arr_indexes
            )->display_none()
        );

        $page_css_base_class->addCSSFor(
            CSSElementOfClassIfAncestorOfClassAndNthChild(
                $this->text_class(),
                $this->item_class(),$arr_indexes
            )->display_none()/*->width("50%")*/
        );

        $page_css_base_class->addCSSFor(
            CSSElementOfClassIfAncestorOfClassAndNthChild(
                $this->content_class(),
                $this->item_class(),$arr_indexes
            )->font_variant("auto")
        );

        $page_css_base_class->addCSSFor(
            CSSElementOfClassIfAncestorOfClassAndNthChild(
                $this->padding_class(),
                $this->item_class(),$arr_indexes
            )->padding_left("16px")
        );
    }
}

class CSSForListOfMainPosts_Item_2_to_5 extends CSSForListOfReviews{
    protected function get_array_of_indexes()
    {
        //return array(2,3,4,5,  10,11,12,13);
        return array(2,3,5,6,8,9,11,12);
    }
    public function __construct($page_css_base_class){
        //parent::__construct($page_css_base_class);
        $arr_indexes = $this->get_array_of_indexes();

        $page_css_base_class->addCSSFor(
            CSSElementOfClassAndNthChild($this->item_class(),$arr_indexes)->
            width("50%")
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


    }
}

class CSSForListOfReviews_StartingFrom10 extends CSSForListOfReviews{
    protected function get_array_of_indexes()
    {
        return array("n+".app::start_index_for_hiding_home_items()->reviews());
    }
    public function __construct($page_css_base_class){
        //parent::__construct($page_css_base_class);
        $arr_indexes = $this->get_array_of_indexes();

        $page_css_base_class->addCSSFor(
            CSSElementOfClassAndNthChild($this->item_class(),$arr_indexes)->
            display_none()
        );

    }
}
