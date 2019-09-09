<?php



class CSSForListOfCareers extends CSSForListOfReviews{
    protected function item_class()
    {
        return ui::css_classes()->item_in_section_for_careers();
    }
    public function __construct(PageCSSBaseClass $page_css_base_class)
    {

        new ResponsiveWidthForElement(
            (new ResponsiveValuesForCSSProperty())->
            setCSSElement(
                CSSElementOfClassAndNthChild($this->item_class(),array("n+1"))
            )->
            setForDefault("100%")->setFor480("50%")->setFor720("33%")->setFor960("25%")
        );


        $page_css_base_class->addCSS(
            CSSElementOfClassAndNthChild(
                $this->item_class(),array("n+".app::start_index_for_hiding_home_items()->careers())
            )->display_none()
        );

        //hide content
        $page_css_base_class->addCSS(
            CSSElementOfClassIfAncestorOfClassAndNthChild(
                $this->content_class(),$this->item_class(),array("n+1")
            )->display_none()
        );

        //image 40%
        $page_css_base_class->addCSS(
            CSSElementOfClassIfAncestorOfClassAndNthChild(
                $this->image_class(),$this->item_class(),array("n+1")
            )->width("28%")->margin_left("2%")
        );
        //video hidden
        $page_css_base_class->addCSS(
            CSSElementOfClassIfAncestorOfClassAndNthChild(
                $this->video_class(),$this->item_class(),array("n+1")
            )->display_none()
        );
        
        //title 60%
        $page_css_base_class->addCSS(
            CSSElementOfClassIfAncestorOfClassAndNthChild(
                $this->title_class(),$this->item_class(),array("n+1")
            )->width("70%")->font_variant("initial")
        );

        $page_css_base_class->addCSS(
            CSSElementOfClassIfAncestorOfClassAndNthChild(
                $this->height_class(),$this->item_class(),array("n+1")
            )->height(ui::height_of_careers())
        );

    }
}
