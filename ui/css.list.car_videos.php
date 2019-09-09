<?php
class CSSForListOfItemsInExporterReviews extends CSSForListOfReviews{
    protected function item_class()
    {
        return ui::css_classes()->item_in_section_for_exporter_reviews();
    }

    public function __construct($page_css_base_class)
    {

        $this->defaultStyling($page_css_base_class);
        $this->itemsWithBigPics($page_css_base_class);
        $this->hideAfterMax($page_css_base_class);
    }

    /**
     * @param $page_css_base_class
     * @param $arr_indexes
     * @return mixed
     * @throws Exception
     */
    protected function default_for_image($page_css_base_class, $arr_indexes)
    {
        return $page_css_base_class->addCSSFor(
            CSSElementOfClassIfAncestorOfClassAndNthChild(
                $this->image_class(),
                $this->item_class(), $arr_indexes
            )->width("28%")->margin_left("2%")
        );
    }

    private function default_for_title($page_css_base_class, $arr_indexes)
    {
        $page_css_base_class->addCSSFor(
            CSSElementOfClassIfAncestorOfClassAndNthChild(
                $this->title_class(),
                $this->item_class(), $arr_indexes
            )->width("70%")
        );

    }

    private function default_for_text($page_css_base_class, $arr_indexes)
    {
        $page_css_base_class->addCSSFor(
            CSSElementOfClassIfAncestorOfClassAndNthChild(
                $this->text_class(),
                $this->item_class(), $arr_indexes
            )->width("70%")
        );
    }

    private function default_for_content($page_css_base_class, $arr_indexes)
    {
        $page_css_base_class->addCSSFor(
            CSSElementOfClassIfAncestorOfClassAndNthChild(
                $this->content_class(),
                $this->item_class(), $arr_indexes
            )->display_none()
        );
    }

    private function hideAfterMax($page_css_base_class)
    {
        $page_css_base_class->addCSSFor(
            CSSElementOfClassAndNthChild(
                $this->item_class(), array("n+" . app::start_index_for_hiding_home_items()->exporter_reviews())
            )->
            display_none()
        );
    }

    private function itemsWithBigPics($page_css_base_class)
    {
//image of first item
        $arr_items_with_big_images = array(1, 3, 5, 7, 9, 11, 13, 15);
        $page_css_base_class->addCSSFor(
            CSSElementOfClassIfAncestorOfClassAndNthChild(
                $this->image_class(),
                $this->item_class(),
                $arr_items_with_big_images)->
            width_100percent()->margin_left("auto")->border_top(ui::_1px_dashed_ddd())->margin_top("0.3em")->padding_top("0.5em")
        );
        
        //title of first item
        $page_css_base_class->addCSSFor(
            CSSElementOfClassIfAncestorOfClassAndNthChild(
                $this->title_class(), $this->item_class(), $arr_items_with_big_images
            )->
            width_100percent()
        );

        //height of first item
        $page_css_base_class->addCSSFor(
            CSSElementOfClassIfAncestorOfClassAndNthChild(
                $this->height_class(), $this->item_class(), $arr_items_with_big_images
            )/*->
            height("17.0em")*/
        );
    }

    private function defaultStyling($page_css_base_class)
    {
        $arr_indexes = $this->get_array_of_indexes();

        $this->default_for_title($page_css_base_class, $arr_indexes);
        $this->default_for_image($page_css_base_class, $arr_indexes);
        $this->default_for_text($page_css_base_class, $arr_indexes);
        $this->default_for_content($page_css_base_class, $arr_indexes);
        $this->default_for_time($page_css_base_class, $arr_indexes);
        $this->default_for_video($page_css_base_class, $arr_indexes);
    }

    private function default_for_time($page_css_base_class, $arr_indexes)
    {
        $page_css_base_class->addCSSFor(
            CSSElementOfClassIfAncestorOfClassAndNthChild(
                $this->time_class(),
                $this->item_class(), $arr_indexes
            )/*->margin_top("-1.0em")*/
        );
    }

    protected function default_for_video($page_css_base_class, $arr_indexes)
    {
        $page_css_base_class->addCSSFor(
            CSSElementOfClassIfAncestorOfClassAndNthChild(
                $this->video_class(),
                $this->item_class(), $arr_indexes
            )->display_none()
        );
    }
}

class CSSForListOfItemsInCarVideos extends CSSForListOfItemsInExporterReviews{
    protected function item_class()
    {
        return ui::css_classes()->item_in_section_for_car_videos();
    }

    protected function default_for_image($page_css_base_class, $arr_indexes)
    {
        return $page_css_base_class->addCSSFor(
            CSSElementOfClassIfAncestorOfClassAndNthChild(
                $this->image_class(),
                $this->item_class(), $arr_indexes
            )->width("28%")->margin_left("2%")->display_none()
        );
    }
    protected function default_for_video($page_css_base_class, $arr_indexes)
    {
        $page_css_base_class->addCSSFor(
            CSSElementOfClassIfAncestorOfClassAndNthChild(
                $this->video_class(),
                $this->item_class(), $arr_indexes
            )->border_top(ui::_1px_dashed_ddd())->margin_top("0.3em")->padding_top("0.3em")
        );
    }
}


