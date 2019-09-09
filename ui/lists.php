<?php
abstract class CollectionOfItems extends SmartDiv{
    /** @param \ReaderForValuesStoredInArray $reader */
    public function __construct($reader)
    {
        ui::exception()->throwIfNotReader($reader);
        parent::__construct();

        $parent_class = $this->get_parent_class();
        $child_class = $this->get_child_class();
        $child_class2 = $this->get_child_class2();
        $count = $reader->count();




        for ($i = 0; $i < $count; $i++) {

            $item_reader = $reader->get_reader_for_item_at($i);
            $item_container = $this->getItemContainerElement();
            $item = $this->get_html_from_item_reader($item_reader);

            if($item_container){
                $this->add_child(
                    $item_container->
                    add_child(
                        ui::html()->div()->add_child(
                            $this->get_html_from_item_reader($item_reader)
                        )->
                        add_class_if($child_class,$child_class)->add_class_if($child_class2,$child_class2)
                    )
                );
            }
            else{
                $this->add_child($item);
            }



        }
        $this->add_class_if($parent_class,$parent_class);
    }

    /** @param \ReaderForValuesStoredInArray $item_reader */
    abstract protected function get_html_from_item_reader($item_reader);

    protected function getItemContainerElement(){
        return ui::html()->div();
    }
    protected function get_parent_class()
    {
    }
    protected function get_child_class()
    {
    }
    protected function get_child_class2()
    {
    }

}

abstract class ListOfItems extends LayoutForNColumns{
    /** @param \ReaderForValuesStoredInArray $reader */
    public function __construct($reader)
    {
        ui::exception()->throwIfNotReader($reader);
        parent::__construct();

        $class = $this->get_class();
        $count = $reader->count();

        for ($i = 0; $i < $count; $i++) {
            $item_reader = $reader->get_reader_for_item_at($i);
            $item = $this->get_html_from_item_reader($item_reader);
            $this->addNewColumn()->add_child(
                ui::html()->div()->add_child(
                    //the overflow div
                    ui::html()->div()->add_child(
                        $item
                    )->add_class(ui::css_classes()->height_limiter())
                )->
                add_class(ui::css_classes()->card_background())->
                add_class(ui::css_classes()->card_padding())->
                add_class(ui::css_classes()->card_margin())->
                add_class(ui::css_classes()->card_border_bottom())->
                add_class(ui::css_classes()->card_border_right())
            )->add_class_if($class,$class);
        }
    }
    
    /** @param \ReaderForValuesStoredInArray $item_reader */
    abstract protected function get_html_from_item_reader($item_reader);

    protected function get_class()
    {
    }

}

class ListOfMostRecentPostPerCategory extends ListOfItems{
    protected function get_class()
    {
        return ui::css_classes()->item_in_most_recent_items_per_category();
    }
    protected function get_html_from_item_reader($item_reader)
    {

        $img = ui::urls()->view_image($item_reader->picture_file_name())->toImage()->width_100percent();
        //=========
        $item = new LayoutForNColumns();
        $item->addNewColumn()->add_child($img->margin_top("8px"))->display_inline_block()->width("30%")->vertical_align_top();
        $item->addNewColumn()->add_child($this->rightContent($item_reader)->padding_left("8px"))->display_inline_block()->width("70%")->vertical_align_top();
        $item->border_bottom("1px solid #eee")->margin_bottom("8px");

        $wrapper = new LayoutForNRows();
        $wrapper->addNewRow()->add_child(
            ui::kickers()->custom(ui::html()->heading3()->
            add_child(strtoupper($item_reader->category()))->
            background_color("tomato")->
            color(ui::colors()->white())->
            padding("8px 1.0em")->display_inline_block()));
        $wrapper->addNewRow()->add_child($item);
        return $wrapper;
    }

    /** @param ReaderForValuesStoredInArray  $item_reader */
    protected function rightContent($item_reader)
    {
        $layout = new LayoutForNRows();
        //$layout->addNewRow()->add_child(ui::links()->view_post($item_reader->file_name())->add_child($item_reader->title()));
        $layout->addNewRow()->add_child(ui::html()->heading3()->add_child($item_reader->title())->color("#334448")->toLink(ui::urls()->view_post($item_reader->file_name()))->text_decoration_none());

        $layout->addNewRow()->add_child(
            sprintf("Posted %s - %s",ui::time_in_seconds($item_reader->timestamp())->asTimeAgo(),date('D M d, Y',$item_reader->timestamp()) ) //ui::html()->heading4()->add_child("Posted ". ui::time_in_seconds($item_reader->timestamp())->asTimeAgo())->opacity("0.7")
        );
        $layout->addNewRow()->add_child(SmartUtils::limit_text_to_length($item_reader->content(),255));
        return $layout;
    }
}

class ListOfPostsLayout1 extends CollectionOfItems{
    protected function get_child_class()
    {
        return ui::css_classes()->motoka_item();
    }

    /** @param ReaderForValuesStoredInArray  $item_reader */
    protected function get_html_from_item_reader($item_reader)
    {
        //return "Item";

        $photo = $item_reader->picture_file_name() ? ui::urls()->view_image($item_reader->picture_file_name())->toImage() : "NO PHOTO";
        $video = new LinkToYoutubeVideoUsingIFrame($item_reader->youtube_video_id());

        $final_photo = $item_reader->section_id() == app::section_ids()->car_videos() ? $video : $photo;

        //=========
        $item = new LayoutForNColumns();

        $item->addNewColumn()->
        add_child(
            $this->htmlFromMonthDescription($item_reader->month_description())
        )->add_class(ui::css_classes()->post_kicker());

        $item->addNewColumn()->
        add_child(
            $this->link_to_spoke($item_reader->file_name())->
            add_child(
                $item_reader->title())/*.
            ui::html()->div()->add_child(
                ui::time_posted_from_reader($item_reader)
            )->add_class(ui::css_classes()->time_posted())*/

        )->
        add_class(ui::css_classes()->title_of_item_in_section_for_main_posts());

        $item->addNewColumn()->add_child(
            new HorizontalEngagementStatistics($item_reader)
        )->margin_bottom("4px");


        $item->addNewColumn()->add_child(
        //todo: if it doesnt work, revert to using $photo instead of $final_photo
        //$photo
            $this->link_to_spoke($item_reader->file_name())->
            add_child($final_photo)->display_block()
        );

        $item->addNewColumn()->add_child(
            new CallsToAction($item_reader)
        )->margin_top("-6px");

        $item->addNewColumn()->add_child(
            $this->getContentForActionsAfterImageOfItem($item_reader)
        )->add_class(ui::css_classes()->actions_after_image_of_item_in_section_for_main_posts());


        $item->addNewColumn()->add_child(
            ui::html()->div()->
            add_child(
                $this->content_for_column3($item_reader)
            )->
            add_class(ui::css_classes()->text_padding_for_item_for_main_posts())
        )->add_class(ui::css_classes()->text_for_item_in_section_for_main_posts());



        return $item;
    }

    private function content_for_column3($item_reader)
    {

        return
            //ui::html()->span()->add_child(ui::time_posted_from_reader($item_reader))->add_class(ui::css_classes()->time_posted()).
            ui::html()->span()->add_child(SmartUtils::limit_text_to_length($item_reader->content(), 255))->
            add_class(ui::css_classes()->content_of_item_in_section_for_main_posts())->add_class(ui::css_classes()->paragraph_text());
    }
    protected function link_to_spoke($file_name){
        return ui::urls()->view_post($file_name)->toLink();
    }

    /** @param ReaderForValuesStoredInArray  $item_reader */
    protected function getContentForActionsAfterImageOfItem($item_reader)
    {
        return "";
    }

    private function htmlFromMonthDescription($month_description)
    {
        $parts = explode(" ",$month_description);
        $part1 = ui::html()->span()->add_child($parts[0])->width_auto();
        $part2 = ui::html()->span()->add_child($parts[1])->width_auto()->color(ui::colors()->orange());
        return ui::html()->span()->add_child($part1."&nbsp;".$part2);
    }

}

class ListOfPostsLayout2 extends ListOfPostsLayout1{
    protected function getItemContainerElement()
    {
        return ui::html()->span()->width("33%")->height("300px")->overflow_y_hidden();
    }
}

class ListOfMiddleItems extends ListOfPostsLayout1{
    protected function get_child_class2()
    {
        return ui::css_classes()->motoka_middle_item();
    }
}

class ListOfPostsLayout4 extends ListOfPostsLayout1{
    protected function getItemContainerElement()
    {
        return ui::html()->span()->width("25%");
    }
}

class ListOfGalleryPics extends CollectionOfItems{

    protected function getItemContainerElement(){

    }
    public function __construct(ReaderForValuesStoredInArray $reader)
    {
        parent::__construct($reader);

        $this
            ->max_width("600px")
            ->min_height("600px")
            ->margin_auto()
            //->background_color("#eee")
            ->position_relative();
    }

    /** @param ReaderForValuesStoredInArray  $item_reader */
    protected function get_html_from_item_reader($item_reader)
    {
        //return "Item";

        $item_host = ui::html()->anchor();
        $item_host->add_class(ui::css_classes()->motoka_gallery_pic_host());
        $item_host->set_href(ui::urls()->view_post($item_reader->file_name()));

        $item_host->add_child(
            ui::html()->div()
                ->background_image_url(ui::urls()->view_image($item_reader->picture_file_name()))
                ->width("98%")->height("98%")->margin_left("1%")->margin_top("1%")
                ->background_position_center()
                ->background_repeat_no_repeat()->
                background_size_cover()
        );
        return $item_host;

        $photo = $item_reader->picture_file_name() ? ui::urls()->view_image($item_reader->picture_file_name())->toImage() : "NO PHOTO";
        $video = new LinkToYoutubeVideoUsingIFrame($item_reader->youtube_video_id());

        $final_photo = $item_reader->section_id() == app::section_ids()->car_videos() ? $video : $photo;

    }


    protected function link_to_spoke($file_name){
        return ui::urls()->view_post($file_name)->toLink();
    }

}

class ListOfPublishedPosts extends ListOfPostsLayout2{
    protected function get_class()
    {
        return ui::css_classes()->item_in_section_for_admin_posts_published();
    }

    protected function getContentForActionsAfterImageOfItem($item_reader)
    {
        return ui::forms()->unpublish_post($item_reader->file_name());
    }
}

class ListOfPostsLayout3 extends CollectionOfItems{
    protected function get_child_class()
    {
        return ui::css_classes()->motoka_item();
    }

    /** @param ReaderForValuesStoredInArray  $item_reader */
    protected function get_html_from_item_reader($item_reader)
    {
        //return "Item";

        $photo = $item_reader->picture_file_name() ? ui::urls()->view_image($item_reader->picture_file_name())->toImage() : "NO PHOTO";
        $video = new LinkToYoutubeVideoUsingIFrame($item_reader->youtube_video_id());

        $final_photo = $item_reader->section_id() == app::section_ids()->car_videos() ? $video : $photo;

        return new LayoutSideBySide1(
            $final_photo,
            $this->link_to_spoke($item_reader->file_name())->
            add_child(
                $item_reader->title()
            )

        );
    }

    protected function link_to_spoke($file_name){
        return ui::urls()->view_post($file_name)->toLink();
    }
}
class LayoutForDraftPosts1 extends ListOfPostsLayout3{
    protected function link_to_spoke($file_name){
        return ui::urls()->adminEditPost($file_name)->toLink();
    }
    
}

class LayoutForDraftPosts2 extends ListOfPostsLayout2{
    protected function link_to_spoke($file_name){
        return ui::urls()->adminEditPost($file_name)->toLink();
    }

}
class LayoutForDraftPosts4 extends LayoutForDraftPosts2{
    protected function getItemContainerElement()
    {
        return ui::html()->span()->width("50%")->max_width("200px");
    }
}

class LayoutForSmallItems extends CollectionOfItems{
    protected function getItemContainerElement()
    {

    }

    /** @param ReaderForValuesStoredInArray  $item_reader */
    protected function get_html_from_item_reader($item_reader)
    {
        $item = ui::html()->anchor();
        $item->add_class(ui::css_classes()->motoka_small_item());

        $image_host = ui::html()->span()->
        width("33%")->add_class(ui::css_classes()->motoka_small_item_image())->
        background_position_top()->background_size_cover()->
        background_repeat_no_repeat()->background_color("#ddd");

        $text_host = ui::html()->span();
        $text_host->width("62%");

        //add the data
        $image_host->background_image_url(
            ui::urls()->view_image($item_reader->picture_file_name())
        );
        $text_host->add_child(
            ui::html()->span()->
            add_child(ui::html()->div()->add_child($item_reader->title())->font_size("1.2em"))->
            add_child($this->htmlFromMonthDescription($item_reader->month_description())->font_variant("all-small-caps")->font_weight_bold())->
            width_auto()->margin_bottom("4px")
            .
            ui::html()->div()->add_child(
                new VerticalEngagementStatisticsForStreamOfPostsAt50Percent($item_reader)
            )->opacity("0.9")

        );

        //$this->link_to_spoke($item_reader->file_name())
        //choose what to display
        $photo = $item_reader->picture_file_name() ? $image_host : $image_host ->background_image_url("")->set_inner_html("NO PHOTO")->font_size("2.0em")->text_align_center();

        $final_photo = $item_reader->section_id() == app::section_ids()->car_videos() ? $image_host->set_inner_html(new LinkToYoutubeVideoUsingIFrame($item_reader->youtube_video_id()))->font_size("1.0em") /*new LinkToYoutubeVideoUsingIFrame($item_reader->youtube_video_id())*/ : $photo;

        $item->add_child($final_photo)->add_child($text_host->margin_left("0.5em"));
        return $item->set_href($this->getUrlForDetails($item_reader))->margin_bottom("8px");

    }

    
    private function htmlFromMonthDescription($month_description)
    {
        $parts = explode(" ",$month_description);
        $part1 = ui::html()->span()->add_child($parts[0])->width_auto();
        $part2 = ui::html()->span()->add_child($parts[1])->width_auto()->color(ui::colors()->orange());
        return ui::html()->span()->add_child($part1."&nbsp;".$part2);
    }

    protected function getUrlForDetails($item_reader)
    {
        return ui::urls()->view_post($item_reader->file_name());
    }

}
class LayoutForDraftPosts5 extends LayoutForSmallItems{
    protected function getUrlForDetails($item_reader)
    {
        return ui::urls()->adminEditPost($item_reader->file_name());
    }
}

class ListOfPostsUnderCarVideos extends ListOfPostsLayout1{
}
class ListOfPostsUnderReviews extends ListOfPostsLayout1{
}

class ListOfPostsUnderReviews00 extends ListOfItems{
    protected function get_class()
    {
        return ui::css_classes()->item_in_section_for_main_posts();
    }
    /** @param ReaderForValuesStoredInArray  $item_reader */
    protected function get_html_from_item_reader($item_reader)
    {
        //return "Item";

        $photo = ui::urls()->view_image($item_reader->picture_file_name())->toImage();        
        $video = new LinkToYoutubeVideoUsingIFrame($item_reader->youtube_video_id());

        $final_photo = $item_reader->section_id() == app::section_ids()->car_videos() ? $video : $photo;

        //=========
        $item = new LayoutForNColumns();

        $item->addNewColumn()->
        add_child(
            $this->htmlFromMonthDescription($item_reader->month_description())
        )->add_class(ui::css_classes()->post_kicker());

        $item->addNewColumn()->
        add_child(
            $this->link_to_spoke($item_reader->file_name())->
        add_child(
                $item_reader->title())/*.
            ui::html()->div()->add_child(
                ui::time_posted_from_reader($item_reader)
            )->add_class(ui::css_classes()->time_posted())*/

        )->
        add_class(ui::css_classes()->title_of_item_in_section_for_main_posts());

        $item->addNewColumn()->add_child(
            //todo: if it doesnt work, revert to using $photo instead of $final_photo
            //$photo
            $final_photo
        )->add_class(ui::css_classes()->image_of_item_in_section_for_main_posts());

        $item->addNewColumn()->add_child(
            $this->getContentForActionsAfterImageOfItem($item_reader)
        )->add_class(ui::css_classes()->actions_after_image_of_item_in_section_for_main_posts());


        $item->addNewColumn()->add_child(
            $video
        )->add_class(ui::css_classes()->video_of_item_in_section_for_main_posts());

        $item->addNewColumn()->add_child(
            ui::html()->div()->
            add_child(
                 $this->content_for_column3($item_reader)
            )->
            add_class(ui::css_classes()->text_padding_for_item_for_main_posts())
        )->add_class(ui::css_classes()->text_for_item_in_section_for_main_posts());
        return $item;
    }

    private function content_for_column3($item_reader)
    {

        return
            //ui::html()->span()->add_child(ui::time_posted_from_reader($item_reader))->add_class(ui::css_classes()->time_posted()).
            ui::html()->span()->add_child(SmartUtils::limit_text_to_length($item_reader->content(), 255))->
            add_class(ui::css_classes()->content_of_item_in_section_for_main_posts())->add_class(ui::css_classes()->paragraph_text());
    }
    protected function link_to_spoke($file_name){
        return ui::urls()->view_post($file_name)->toLink();
    }

    /** @param ReaderForValuesStoredInArray  $item_reader */
    protected function getContentForActionsAfterImageOfItem($item_reader)
    {
        return "";
    }

    private function htmlFromMonthDescription($month_description)
    {
        $parts = explode(" ",$month_description);
        $part1 = ui::html()->span()->add_child($parts[0])->width_auto();
        $part2 = ui::html()->span()->add_child($parts[1])->width_auto()->color(ui::colors()->orange());
        return ui::html()->span()->add_child($part1."&nbsp;".$part2);
    }

}

class ListOfPostsUnderExporterReviews extends ListOfPostsUnderReviews{
    protected function get_class()
    {
        return ui::css_classes()->item_in_section_for_exporter_reviews();
    }
}

class ListOfPostsUnderCarExporters extends ListOfPostsUnderReviews{
    protected function get_class()
    {
        return ui::css_classes()->item_in_section_for_car_exporters();
    }
}

class ListOfPostsUnderCarPictures extends ListOfPostsUnderReviews{
    protected function get_class()
    {
        return ui::css_classes()->item_in_section_for_car_pictures();
    }
}




class ListOfPostsUnderNews000 extends ListOfPostsUnderReviews{
    protected function get_class()
    {
        return ui::css_classes()->item_in_section_for_news();
    }
}

class ListOfPostsUnderCareers extends ListOfPostsUnderReviews{
    protected function get_class()
    {
        return ui::css_classes()->item_in_section_for_careers();
    }
}
class ListOfPostsUnderCarMaintenance extends ListOfPostsUnderReviews{
    protected function get_class()
    {
        return ui::css_classes()->item_in_section_for_car_maintenance();
    }
}

abstract class ListOfStatsPerAttribute extends ListOfItems{
    public function __construct(ReaderForValuesStoredInArray $reader,$heading = "")
    {
        $this->addNewColumn()->add_child_if($heading,ui::html()->heading2()->add_child($heading)->padding("0px 4px"));
        parent::__construct($reader);

        $this->max_width("800px")->margin_auto()
            ->background_color(ui::colors()->form_bg())->padding("1.0em")->border(ui::_1px_solid_form_border())->margin_bottom("1.0em")
        ;
        $this->add_class(ui::css_classes()->element_with_box_shadow())->border_radius("0.5em")->margin_top("1.0em");

    }

    protected function get_html_from_item_reader($item_reader)
    {
        return $this->report_for_total_posts($item_reader);
    }

    private function report_for_total_posts($item_reader)
    {
        $count = intval($item_reader->total_posts());
        $noun = $count != 1 ? "posts" : "post";

        $total_posts = ui::html()->span()->
        add_child(sprintf("%s %s",$count,$noun))->
        width_auto()->color("#555");

        return sprintf("%s:- %s", $this->label_for_total_posts($item_reader), $total_posts);
    }

    /** @param \ReaderForValuesStoredInArray $item_reader */
    abstract protected function label_for_total_posts($item_reader);
}
class ListOfStatsPerSection extends ListOfStatsPerAttribute{
    protected function label_for_total_posts($item_reader)
    {
        return $item_reader->title();
    }
}
class ListOfStatsPerYear extends ListOfStatsPerAttribute{
    protected function label_for_total_posts($item_reader)
    {
        return $item_reader->year_number();
    }    
}
class ListOfStatsPerMonth extends ListOfStatsPerAttribute{
    protected function label_for_total_posts($item_reader)
    {
        return $item_reader->month_description();
    }
}
class ListOfStatsPerWeek extends ListOfStatsPerAttribute{
    protected function label_for_total_posts($item_reader)
    {
        return $item_reader->week_of_the_year_description();
    }
}
class ListOfStatsPerDay extends ListOfStatsPerAttribute{
    protected function label_for_total_posts($item_reader)
    {
        return $item_reader->day_of_the_year_description();
    }
}
