<?php

#####################################################################

class CSSForListOfNews extends CSSForListOfReviews{
    public function __construct($page_css_base_class)
    {
        new CSSForListOfNews_Item_All($page_css_base_class);
        new CSSForListOfNews_Item_StartingWith_5($page_css_base_class);
        //new CSSForListOfNews_Item_1($page_css_base_class);
    }

}

class CSSForListOfNews_Item_All extends CSSForListOfItemsInExporterReviews{
    protected function item_class()
    {
        return ui::css_classes()->item_in_section_for_news();
    }
    protected function get_array_of_indexes()
    {
        return array("n+1");
    }

}

class CSSForListOfNews_Item_StartingWith_5 extends CSSForListOfNews_Item_All{

    protected function get_array_of_indexes()
    {
        return array("n+".app::start_index_for_hiding_home_items()->news());
    }
    public function __construct(PageCSSBaseClass $page_css_base_class)
    {
        //parent::__construct($page_css_base_class);
        $arr_indexes = $this->get_array_of_indexes();


        $page_css_base_class->addCSSFor(
            CSSElementOfClassAndNthChild($this->item_class(),$arr_indexes)->
            display_none()
        );
    }
}



