<?php

class MotokaPageWrapper extends SmartHTMLPage{
    /** @param HomePageAccessingData $page */
    public function __construct($page)
    {
        parent::__construct();
        $this->set_title("Sky Volt Cars");
        $this->add_inline_css_to_body(new PageCSS());
        $this->add_inline_css_to_body(ResponsiveCSSForComponent::getAllAsOneString());
        //$this->add_child_to_body(ui::sections()->header());
        $this->add_child_to_body(
            ui::html()->header()->add_child($page->header())->
            height($page->get_header_height_percent())->
            overflow_y_hidden()->
            position_fixed()->
            z_index(1)
        );
        $this->add_child_to_body(
            ui::html()->div()->add_child(
                ui::html()->div()->add_child($page)->padding_bottom("2.0em")
                .
                ui::sections()->footer($page->readerForCurrentUser())->position_relative()->z_index(1)
            )->top($page->get_header_height_percent())->position_absolute()->height("91%")->width_100percent()
        );


        $this->add_style_to_body("background-color", $page->get_background_color());
        $this->add_style_to_body("overflow-y", $page->get_overflow_y());
    }

}
