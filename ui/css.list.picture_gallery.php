<?php

class CSSForListOfCarExporters extends CSSForListOfReviews{
    public function __construct($page_css_base_class)
    {
        new CSSForListOfCarExporters_Item_All($page_css_base_class);
    }

}

class CSSForListOfCarExporters_Item_All extends CSSForListOfReviews{
    protected function item_class()
    {
        return ui::css_classes()->item_in_section_for_car_exporters();
    }
    public function __construct(PageCSSBaseClass $page_css_base_class)
    {
        parent::__construct($page_css_base_class);

        new ResponsiveWidthForElement(
            (new ResponsiveValuesForCSSProperty())->
            setCSSElement(
                CSSElementOfClassAndNthChild($this->item_class(),array("n+1"))
            )->
            setForDefault("100%")->setFor720("50%")
        );

        $page_css_base_class->addCSS(
            CSSElementOfClassIfAncestorOfClassAndNthChild(
                $this->content_class(),$this->item_class(),array("n+1")
            )->display_none()
        );

        $page_css_base_class->addCSS(
            CSSElementOfClassIfAncestorOfClassAndNthChild(
                $this->video_class(),$this->item_class(),array("n+1")
            )->display_none()
        );

        //set height
        $page_css_base_class->addCSS(
            CSSElementOfClassIfAncestorOfClassAndNthChild(
                $this->height_class(),$this->item_class(),array("n+1")
            )/*->height("21.0em")*/
        );

        //hide after 6
        parent::__construct($page_css_base_class);
        $page_css_base_class->addCSS(
            CSSElementOfClassAndNthChild(
                $this->item_class(),array("n+".app::start_index_for_hiding_home_items()->car_exporters())
            )->display_none()
        );
    }
}

class CSSForListOfPictureGallery_Item_All extends CSSForListOfCarExporters_Item_All{
    protected function item_class()
    {
        return ui::css_classes()->item_in_section_for_car_pictures();
    }
}


