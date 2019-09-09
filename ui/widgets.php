<?php
class VerticalEngagementStatisticsForStreamOfPostsAt50Percent extends SmartDiv{

    /** @param ReaderForValuesStoredInArray $item_reader */
    public function __construct($item_reader)
    {
        parent::__construct();

        $views = app::utils()->nulls_to_zero($item_reader->views());
        $likes = app::utils()->nulls_to_zero($item_reader->likes());
        $comments = app::utils()->nulls_to_zero($item_reader->comments());

        $label_for_views = app::utils()->choose_label_for_total($views,"View", "Views");
        $label_for_likes = app::utils()->choose_label_for_total($likes,"Like", "Likes");
        $label_for_comments = app::utils()->choose_label_for_total($comments,"Comment", "Comments");

        $this->add_child(
            $this->render_item($label_for_views,$views).
            $this->render_item($label_for_likes,$likes).
            $this->render_item($label_for_comments,$comments)
        );

    }

    protected function render_item($label,$value){
        return ui::html()->span()->add_child(
            ui::html()->div()->add_child($value)->font_size("1.5em")
            .
            ui::html()->div()->add_child($label)
        )->width_auto()->padding_right("1.0em")->text_align_left()

            ;
    }
}

class HorizontalEngagementStatistics extends VerticalEngagementStatisticsForStreamOfPostsAt50Percent{
    protected function render_item($label,$value){
        $widget_bg_color = ui::colors()->header_bg();
        $host = ui::html()->div()->add_child(
            ui::html()->span_auto($value)->font_size("1.2em")->font_weight_bold()->opacity(0.8)->vertical_align_baseline()->margin_right("4px")
            .
            ui::html()->span_auto($label)->vertical_align_baseline()
        )->background_color($widget_bg_color)->color("#fff")->padding("0px 0.5em")->margin_right("4px")->border_radius("5px 5px 5px 0px");

        $pointer = ui::html()->span_auto("");
        $pointer->border("8px solid transparent")->border_top_color($widget_bg_color);

        return ui::html()->span_auto(
            $host .$pointer
        );
    }
}


class CallsToAction extends SmartDiv{

    /** @param ReaderForValuesStoredInArray $item_reader */
    public function __construct($item_reader)
    {
        parent::__construct();

        $view = "View";
        $this->add_child($this->wrap_item($view));
        $this->add_child($this->wrap_item("Like"));
        $this->add_child($this->wrap_item("Comment"));

        $this->background_color(ui::colors()->header_bg())->color(ui::colors()->white());

    }

    private function wrap_item($view)
    {
        return ui::html()->span()->add_child(
            ui::html()->div()->add_child($view)->border_right(
                sprintf("1px solid %s",ui::colors()->header_bg()->mix(ui::colors()->white(),10))
            )->
            border_left(
                sprintf("1px solid %s",ui::colors()->header_bg()->mix(ui::colors()->white(),20))
            )->padding("4px 0px")
        )->width("33.3333%")->text_align_center()
        ;
    }


}