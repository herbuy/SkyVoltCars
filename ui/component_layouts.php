<?php
class LayoutSideBySide1 extends SmartDiv{
    public function __construct($left_content,$right_content)
    {
        parent::__construct();

        $this->add_child(
            ui::html()->span()->add_child(
                ui::html()->div()->add_child($left_content)->margin_right("1.0em")
            )->width("33%")
            .
            ui::html()->span()->add_child(
                $right_content
            )->width("66%")
        );
    }
}