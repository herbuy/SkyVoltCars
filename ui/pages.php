<?php

abstract class HomePageAccessingData extends CmdForGetDataPage{

    
    public function header()
    {
        return ui::page_headers()->home();
    }
    
    private $reader_for_current_user;
    public function readerForCurrentUser(){
        if($this->reader_for_current_user){
            return $this->reader_for_current_user;
        }
        return app::reader(array());
    }

    public function get_background_color()
    {
        return ui::colors()->bg();
        //return ui::colors()->white();
    }
    public function get_overflow_y()
    {
        return "auto";
    }
    public function get_header_height_percent()
    {
        return "9%";
    }

    /** @param \ReaderForValuesStoredInArray $reader_for_current_user */
    protected function storeReaderForCurrentUser($reader_for_current_user)
    {
        ui::exception()->throwIfNotReader($reader_for_current_user);
        $this->reader_for_current_user = $reader_for_current_user->get_reader_for_item_1();
    }

    protected function packMoreRemoteProcedureArguments(){
        $_REQUEST[app::values()->function_names()] = join(",",
            $this->array_of_procedures()
        );
    }
    abstract protected function array_of_procedures();
    protected function unpackError($error)
    {
        $this->html = $error;
        return $error;
    }
    protected function unpackContent($reader_for_content)
    {
        $this->html = $this->getHtmlFromReaderForContent($reader_for_content);
        return parent::unpackContent($reader_for_content);
    }
    /** @param \ReaderForValuesStoredInArray $reader_for_content */
    abstract protected function getHtmlFromReaderForContent($reader_for_content);

    private $html = '';
    public function __toString()
    {
        $this->execute();
        return $this->html."";
    }
    
}
abstract class HomePageForAdminAccessingData extends HomePageAccessingData{
    public function header()
    {
        return ui::page_headers()->admin();
    }

    public function get_background_color()
    {
        return ui::colors()->admin_page_bg();
    }
    
    protected function storeReaderForCurrentUser($reader_for_current_user)
    {
        parent::storeReaderForCurrentUser($reader_for_current_user);
        
        ui::urls()->loginPage()->gotoAddressIf(
            $reader_for_current_user->count() <= 0
        );        
    }
    
}
abstract class BaseClassForAdminHomePage extends HomePageForAdminAccessingData{
    private $page_layout;
    private $left_col_layout;
    protected function pageLayout(){
        return $this->page_layout;
    }
    protected function leftColumnLayout(){
        return $this->left_col_layout;
    }
    public function __construct()
    {
        $this->page_layout = ui::page_layouts()->home_obsolete();
        $this->left_col_layout = new LayoutForTwoColumns();

        $this->left_col_layout->leftColumn()->width("20%");
        $this->left_col_layout->rightColumn()->width("80%");
    }

    protected function array_of_procedures()
    {
        return array(
            app::values()->get_categories(),
            app::values()->get_pages(),
            app::values()->get_sections()
        );
    }

    protected function getHtmlFromReaderForContent($reader_for_content)
    {
        $reader_for_categories = $reader_for_content->get_reader_for_item_1();
        $reader_for_pages = $reader_for_content->get_reader_for_item_2();
        $reader_for_sections = $reader_for_content->get_reader_for_item_3();

        $this->leftColumnLayout()->leftColumn()->add_child(ui::sections()->total_content());
        $this->leftColumnLayout()->leftColumn()->add_child(ui::sections()->total_discussions());


        $this->leftColumnLayout()->rightColumn()->add_child(
            $this->middle_content($reader_for_categories, $reader_for_pages, $reader_for_sections)
        );

        $this->pageLayout()->leftColumn()->add_child($this->leftColumnLayout());
        $this->pageLayout()->rightColumn()->add_child(ui::sections()->admin_navigation_box());
        return $this->pageLayout()."";
    }
    abstract protected function middle_content($reader_for_categories, $reader_for_pages, $reader_for_sections);
}
class HomePageForAdmin extends HomePageForAdminAccessingData{
    protected function array_of_procedures()
    {
        return array(
            app::values()->get_current_user(),
            app::values()->get_sections(),
            app::values()->get_cars(),
            app::values()->get_posts_for_car_exporters(),

            app::values()->admin_get_stats_per_section(),
            app::values()->admin_get_stats_per_year(),
            app::values()->admin_get_stats_per_month(),
            app::values()->admin_get_stats_per_week(),
            app::values()->admin_get_stats_per_day()
        );
    }

    protected function getHtmlFromReaderForContent($reader_for_content)
    {
        $reader_for_current_user = $reader_for_content->get_reader_for_item_1();
        $this->storeReaderForCurrentUser($reader_for_current_user);

        $reader_for_sections = $reader_for_content->get_reader_for_item_2();
        $reader_for_cars = $reader_for_content->get_reader_for_item_3();
        $reader_for_car_exporters = $reader_for_content->get_reader_for_item_4();

        $reader_for_stats_per_section = $reader_for_content->get_reader_for_item_5();
        $reader_for_stats_per_year = $reader_for_content->get_reader_for_item_6();
        $reader_for_stats_per_month = $reader_for_content->get_reader_for_item_7();
        $reader_for_stats_per_week = $reader_for_content->get_reader_for_item_8();
        $reader_for_stats_per_day = $reader_for_content->get_reader_for_item_9();

        #======================== stats
        //load any saved data
        $layout_daily_stats = new LayoutForNRows();
        $layout_daily_stats->addNewRow()->add_child(ui::lists()->stats_per_year($reader_for_stats_per_year));
        $layout_daily_stats->addNewRow()->add_child(ui::lists()->stats_per_month($reader_for_stats_per_month));
        $layout_daily_stats->addNewRow()->add_child(ui::lists()->stats_per_week($reader_for_stats_per_week));
        $layout_daily_stats->addNewRow()->add_child(ui::lists()->stats_per_day($reader_for_stats_per_day));
        $layout_daily_stats->addNewRow()->add_child(ui::lists()->stats_per_section($reader_for_stats_per_section));


        return new PageLayoutForAdminSection(
            new GlobalNavigationForAdmin(),
            ui::html()->about(
                "Welcome! Use the various Admin pages to manage content for your site"
                .
                ui::lists()->stats_per_month($reader_for_stats_per_month)->font_size("0.7em")
            )
        );
    }
    public function get_overflow_y()
    {
        return app::values()->hidden();
    }

    protected function linksToAddOtherObjects()
    {
        return new GlobalNavigationForAdmin();
    }

}

abstract class HomePageForAdminSection extends HomePageForAdminAccessingData{
    protected $reader_for_cars;
    protected $reader_for_sections;
    protected $reader_for_car_exporters;

    protected function array_of_procedures()
    {
        return array(
            app::values()->get_current_user(),
            app::values()->get_sections(),
            app::values()->get_cars(),
            app::values()->get_posts_for_car_exporters(),
            $this->procedure_for_posts()

        );
    }
    public function get_overflow_y()
    {
        return app::values()->hidden();
    }

    protected function getHtmlFromReaderForContent($reader_for_content)
    {


        $reader_for_current_user = $reader_for_content->get_reader_for_item_1();
        $this->storeReaderForCurrentUser($reader_for_current_user);

        $this->reader_for_sections = $reader_for_content->get_reader_for_item_2();
        $this->reader_for_cars = $reader_for_content->get_reader_for_item_3();
        $this->reader_for_car_exporters = $reader_for_content->get_reader_for_item_4();

        $reader_for_current_posts = $reader_for_content->get_reader_for_item_5();

        return new PageLayoutForAdminSection(
            new GlobalNavigationForAdmin(),//"right"
            $this->form_for_add_post()
            .
            ui::html()->div()->add_child(
                ui::html()->heading3()->add_child("RECENTLY")->border_bottom("0px solid orange")->margin_bottom("0.5em")->color("666")
                .
                new LayoutForSmallItems($reader_for_current_posts)
            )->padding("0.5em")
        );

        return new PageLayoutForAdminSection(
            new GlobalNavigationForAdmin(),//"right"
            $this->form_for_add_post()
            .
            new ListOfPostsLayout2($reader_for_current_posts)
        );
        

    }



    private function stats($reader_for_stats_per_year, $reader_for_stats_per_month, $reader_for_stats_per_week, $reader_for_stats_per_day, $reader_for_stats_per_section)
    {
        $layout_daily_stats = new LayoutForNRows();
        $layout_daily_stats->addNewRow()->add_child(ui::lists()->stats_per_year($reader_for_stats_per_year));
        $layout_daily_stats->addNewRow()->add_child(ui::lists()->stats_per_month($reader_for_stats_per_month));
        $layout_daily_stats->addNewRow()->add_child(ui::lists()->stats_per_week($reader_for_stats_per_week));
        $layout_daily_stats->addNewRow()->add_child(ui::lists()->stats_per_day($reader_for_stats_per_day));
        $layout_daily_stats->addNewRow()->add_child(ui::lists()->stats_per_section($reader_for_stats_per_section));
        return $layout_daily_stats;
    }


    abstract protected function form_for_add_post();
    abstract protected function procedure_for_posts();

}

class HomePageForAdminCarReviews extends HomePageForAdminSection{
    protected function form_for_add_post()
    {
        return ui::forms()->start_new_car_review($this->reader_for_cars);
    }
    protected function procedure_for_posts(){
        return app::values()->get_posts_for_reviews();
    }
}


class HomePageForAdminCarVideos extends HomePageForAdminSection{
    protected function form_for_add_post()
    {
        return ui::forms()->start_new_car_video($this->reader_for_cars);
    }
    protected function procedure_for_posts(){
        return app::values()->get_posts_for_car_videos();
    }
}

class HomePageForAdminCarPictures extends HomePageForAdminSection{
    protected function form_for_add_post()
    {
        return ui::forms()->start_new_car_picture($this->reader_for_cars);
    }
    protected function procedure_for_posts(){
        return app::values()->get_posts_for_car_pictures();
    }
}
class HomePageForAdminExporterReviews extends HomePageForAdminSection{
    protected function form_for_add_post()
    {
        return ui::forms()->start_new_exporter_review($this->reader_for_car_exporters);
    }
    protected function procedure_for_posts(){
        return app::values()->get_posts_for_exporter_reviews();
    }
}



class HomePageForAdminCarExporters extends HomePageForAdminSection{
    protected function form_for_add_post()
    {
        return ui::forms()->start_new_car_exporter();
    }
    protected function procedure_for_posts(){
        return app::values()->get_posts_for_car_exporters();
    }
}
class HomePageForAdminCarNews extends HomePageForAdminSection{
    protected function form_for_add_post()
    {
        return ui::forms()->start_new_car_news();
    }
    protected function procedure_for_posts(){
        return app::values()->get_posts_for_news();
    }
}
class HomePageForAdminJobs extends HomePageForAdminSection{
    protected function form_for_add_post()
    {
        return ui::forms()->start_new_job_opportunity();
    }
    protected function procedure_for_posts(){
        return app::values()->get_posts_for_careers();
    }
}
class HomePageForAdminCarMaintenance extends HomePageForAdminSection{
    protected function form_for_add_post()
    {
        return ui::forms()->start_new_car_maintenance();
    }
    protected function procedure_for_posts(){
        return app::values()->get_posts_for_car_maintenance();
    }
}
class HomePageForAdminCars extends HomePageForAdminSection{
    protected function form_for_add_post()
    {
        return ui::forms()->start_new_car();
    }
    protected function procedure_for_posts(){
        return app::values()->get_cars();
    }
}

class HomePageForLogin extends HomePageForAdminAccessingData{
    protected function array_of_procedures()
    {
        return array(
            app::values()->get_sections()
        );
    }

    protected function getHtmlFromReaderForContent($reader_for_content)
    {
        $reader_for_sections = $reader_for_content->get_reader_for_item_1();

        return ui::forms()->login();
    }
}


class HomePageForAdminViewPosts000 extends HomePageForAdminAccessingData{
    protected function array_of_procedures()
    {
        return array(
            app::values()->get_current_user(),
            app::values()->get_draft_posts(),
            app::values()->get_posts()
        );
    }

    protected function getHtmlFromReaderForContent($reader_for_content)
    {
        $reader_for_current_user = $reader_for_content->get_reader_for_item_1();
        $this->storeReaderForCurrentUser($reader_for_current_user);

        $reader_for_draft_posts = $reader_for_content->get_reader_for_item_2();
        $reader_for_posts = $reader_for_content->get_reader_for_item_3();

        //load any saved data
        $layout = new LayoutForNRows();
        //$layout->addNewRow()->add_child(ui::forms()->new_post());
        $layout->addNewRow()->add_child(ui::html()->heading2()->add_child(ui::text_with_contrast_colors("Posts you are","working on")));
        $layout->addNewRow()->add_child(ui::lists()->admin_draft_posts($reader_for_draft_posts));
        //$layout->addNewRow()->add_child(ui::forms()->publish_all_posts());

        $final_layout = ui::page_layouts()->admin();
        $final_layout->leftColumn()->add_child(
            $layout->padding("1.0em")->margin("1.0em")->border(ui::_1px_solid_form_border())->background_color(ui::colors()->form_bg())->border_radius("0.5em")->
            add_class(ui::css_classes()->element_with_box_shadow())
        );

        $right_content = new LayoutForNRows();
        $right_content->addNewRow()->add_child(ui::html()->heading2()->add_child(ui::text_with_contrast_colors("RECENTLY","PUBLISHED")));
        $right_content->addNewRow()->add_child(ui::lists()->news($reader_for_posts));

        $final_layout->rightColumn()->add_child(
            $right_content->padding("1.0em")->margin("1.0em")->border(ui::_1px_solid_form_border())->background_color(ui::colors()->form_bg())->border_radius("0.5em")->
            add_class(ui::css_classes()->element_with_box_shadow())
        );
        return $final_layout;
    }
}

class HomePageForAdminViewPosts extends HomePageForAdminAccessingData{
    protected function array_of_procedures()
    {
        return array(
            app::values()->get_current_user(),
            app::values()->get_draft_posts(),
            app::values()->get_posts()
        );
    }

    protected function getHtmlFromReaderForContent($reader_for_content)
    {
        $reader_for_current_user = $reader_for_content->get_reader_for_item_1();
        $this->storeReaderForCurrentUser($reader_for_current_user);

        $reader_for_draft_posts = $reader_for_content->get_reader_for_item_2();
        $reader_for_posts = $reader_for_content->get_reader_for_item_3();

        return new PageLayoutForAdminSection(
            new GlobalNavigationForAdmin(),
            ui::html()->div()->add_child(new LayoutForDraftPosts5($reader_for_draft_posts))->padding("0px 0.25em")
        );

    }
    public function get_overflow_y()
    {
        return app::values()->hidden();
    }
}

class HomePageForAdminStatistics extends HomePageForAdminAccessingData{
    protected function array_of_procedures()
    {
        return array(
            app::values()->get_current_user(),
            app::values()->admin_get_stats_per_section(),
            app::values()->admin_get_stats_per_year(),
            app::values()->admin_get_stats_per_month(),
            app::values()->admin_get_stats_per_week(),
            app::values()->admin_get_stats_per_day()
        );
    }

    protected function getHtmlFromReaderForContent($reader_for_content)
    {
        $reader_for_current_user = $reader_for_content->get_reader_for_item_1();
        $this->storeReaderForCurrentUser($reader_for_current_user);

        $reader_for_stats_per_section = $reader_for_content->get_reader_for_item_2();
        $reader_for_stats_per_year = $reader_for_content->get_reader_for_item_3();
        $reader_for_stats_per_month = $reader_for_content->get_reader_for_item_4();
        $reader_for_stats_per_week = $reader_for_content->get_reader_for_item_5();
        $reader_for_stats_per_day = $reader_for_content->get_reader_for_item_6();
        

        //load any saved data
        $layout = new LayoutForNRows();

        $layout->addNewRow()->add_child(ui::lists()->stats_per_section($reader_for_stats_per_section));
        $layout->addNewRow()->add_child(ui::lists()->stats_per_week($reader_for_stats_per_week));

        $layout_daily_stats = new LayoutForNRows();
        $layout_daily_stats->addNewRow()->add_child(ui::lists()->stats_per_year($reader_for_stats_per_year));
        $layout_daily_stats->addNewRow()->add_child(ui::lists()->stats_per_month($reader_for_stats_per_month));
        $layout_daily_stats->addNewRow()->add_child(ui::lists()->stats_per_day($reader_for_stats_per_day));
        

        $final_layout = ui::page_layouts()->admin();
        $final_layout->leftColumn()->add_child($layout);

        $final_layout->rightColumn()->add_child($layout_daily_stats);

        return new PageLayoutForAdminSection(
            new GlobalNavigationForAdmin(),
            ui::html()->about(
                $layout_daily_stats
            )->font_size("0.9em")


        );
    }
    public function get_overflow_y()
    {
        return app::values()->hidden();
    }
}
class HomePageForAdminViewPostsPublished extends HomePageForAdminAccessingData{
    protected function array_of_procedures()
    {
        return array(
            app::values()->get_current_user(),
            app::values()->get_posts()
        );
    }

    protected function getHtmlFromReaderForContent($reader_for_content)
    {
        $reader_for_current_user = $reader_for_content->get_reader_for_item_1();
        $this->storeReaderForCurrentUser($reader_for_current_user);

        $reader_for_posts = $reader_for_content->get_reader_for_item_2();

        return new PageLayoutForAdminSection(
            new GlobalNavigationForAdmin(),
            ui::lists()->admin_published_posts($reader_for_posts)
        );

    }
    public function get_overflow_y()
    {
        return app::values()->hidden();
    }

}


class PageForAdminAddImages extends BaseClassForAdminHomePage{
    protected function middle_content($reader_for_categories, $reader_for_pages, $reader_for_sections)
    {
        return ui::forms()->add_picture();
    }
}

class HomePageForAdminEditPost extends HomePageForAdminAccessingData{
    protected function array_of_procedures()
    {
        return array(
            app::values()->get_current_user(),
            app::values()->get_draft_post(),
            app::values()->get_extended_post_tokens(),
            app::values()->get_draft_posts()
        );
    }

    protected function getHtmlFromReaderForContent($reader_for_content)
    {
        $reader_for_current_user = $reader_for_content->get_reader_for_item_1();
        $this->storeReaderForCurrentUser($reader_for_current_user);

        $reader_for_post = $reader_for_content->get_reader_for_item_2();
        $reader_for_post = $reader_for_post->get_reader_for_item_1();
        
        $reader_for_extended_post_tokens = $reader_for_content->get_reader_for_item_3();
        $reader_for_draft_posts = $reader_for_content->get_reader_for_item_4();

        return new PageLayoutForAdminSection(
            ui::sections()->actions_for_edit_post($reader_for_post)->padding("0px 1.0em")
            ,
            ui::html()->div()->add_child(
                ui::sections()->article_details_in_preview_mode($reader_for_post).
                ui::sections()->extended_post_tokens($reader_for_extended_post_tokens)
            )->padding("0px 1.0em"),
            'SHOW/HIDE EDITING OPTIONS'
            //new LayoutForDraftPosts1($reader_for_draft_posts),
            //ui::sections()->edit_post($reader_for_post,$reader_for_extended_post_tokens)

        );

    }
    public function get_overflow_y()
    {
        return app::values()->hidden();
    }
}

class HomePageForAdminEditCarReview extends HomePageForAdminEditPost{
    
}

class HomePageForAdminEditPostCarDescription extends HomePageForAdminAccessingData{
    protected function array_of_procedures()
    {
        return array(
            app::values()->get_current_user(),
            app::values()->get_draft_post(),
            app::values()->get_cars()
        );
    }

    protected function getHtmlFromReaderForContent($reader_for_content)
    {
        $reader_for_current_user = $reader_for_content->get_reader_for_item_1();
        $this->storeReaderForCurrentUser($reader_for_current_user);

        $reader_for_post = $reader_for_content->get_reader_for_item_2();
        $reader_for_post = $reader_for_post->get_reader_for_item_1();

        $reader_for_cars = $reader_for_content->get_reader_for_item_3();


        $final_layout = ui::page_layouts()->admin_edit_post();
        $final_layout->add_child(
            ui::html()->div()->add_child(
                ui::forms()->edit_car_selected($reader_for_post,$reader_for_cars)
            )->max_width("960px")->margin_auto()
        );
        return $final_layout;
    }
}

class HomePageForAdminEditPostCarExporterSelected extends HomePageForAdminAccessingData{
    protected function array_of_procedures()
    {
        return array(
            app::values()->get_current_user(),
            app::values()->get_draft_post(),
            app::values()->get_posts_for_exporter_reviews()
        );
    }

    protected function getHtmlFromReaderForContent($reader_for_content)
    {
        $reader_for_current_user = $reader_for_content->get_reader_for_item_1();
        $this->storeReaderForCurrentUser($reader_for_current_user);

        $reader_for_post = $reader_for_content->get_reader_for_item_2();
        $reader_for_post = $reader_for_post->get_reader_for_item_1();

        $reader_for_car_exporters = $reader_for_content->get_reader_for_item_3();


        $final_layout = ui::page_layouts()->admin_edit_post();
        $final_layout->add_child(
            ui::html()->div()->add_child(
                ui::forms()->edit_car_exporter_selected($reader_for_post,$reader_for_car_exporters)
            )->max_width("960px")->margin_auto()
        );
        return $final_layout;
    }
}

abstract class HomePageForAdminEditPostComponent extends HomePageForAdminAccessingData{
    public function get_overflow_y()
    {
        return app::values()->hidden();
    }
    protected function menuHintText()
    {
        return "SHOW/HIDE CURRENT POST";
    }
}

class HomePageForAdminEditPostTitle extends HomePageForAdminEditPostComponent{
    protected function array_of_procedures()
    {
        return array(
            app::values()->get_current_user(),
            app::values()->get_draft_post(),
            app::values()->get_sections()
        );
    }

    protected function getHtmlFromReaderForContent($reader_for_content)
    {
        $reader_for_current_user = $reader_for_content->get_reader_for_item_1();
        $this->storeReaderForCurrentUser($reader_for_current_user);

        $reader_for_post = $reader_for_content->get_reader_for_item_2();

        $reader_for_sections = $reader_for_content->get_reader_for_item_3();


        return new PageLayoutForAdminSection(
            new LayoutForDraftPosts1($reader_for_content->get_reader_for_item_2()).
            ui::html()->about("Provide a Suitable Title for your post")
            ,
            ui::forms()->edit_post_title($reader_for_post,$reader_for_sections)
            ,
            $this->menuHintText()
        );
    }

}




class HomePageForAdminEditPostContent extends HomePageForAdminEditPostComponent{
    protected function array_of_procedures()
    {
        return array(
            app::values()->get_current_user(),
            app::values()->get_draft_post()
        );
    }

    protected function getHtmlFromReaderForContent($reader_for_content)
    {
        $reader_for_current_user = $reader_for_content->get_reader_for_item_1();
        $this->storeReaderForCurrentUser($reader_for_current_user);

        $reader_for_post = $reader_for_content->get_reader_for_item_2();

        return new PageLayoutForAdminSection(
            new LayoutForDraftPosts1($reader_for_content->get_reader_for_item_2()).
            ui::html()->about("Provide a fitting introduction or background to your post")
        ,
            ui::forms()->edit_post_content($reader_for_post)
            ,
            $this->menuHintText()

        );
    }
}

class HomePageForAdminEditExtendedPostContent extends HomePageForAdminEditPostComponent{
    protected function array_of_procedures()
    {
        return array(
            app::values()->get_current_user(),
            app::values()->get_extended_post_content(),
            app::values()->get_draft_post()
        );
    }

    protected function getHtmlFromReaderForContent($reader_for_content)
    {
        $reader_for_current_user = $reader_for_content->get_reader_for_item_1();
        $this->storeReaderForCurrentUser($reader_for_current_user);

        $reader_for_tokens = $reader_for_content->get_reader_for_item_2();
        $reader_for_tokens = $reader_for_tokens->get_reader_for_item_1();

        return new PageLayoutForAdminSection(
            new LayoutForDraftPosts1($reader_for_content->get_reader_for_item_3()).
            ui::html()->about("Provide the body of your post")
            ,
            ui::forms()->edit_extended_post_content($reader_for_tokens)
            ,
            $this->menuHintText()
        );
    }
}
class HomePageForAdminEditPostPicture extends HomePageForAdminAccessingData{
    protected function array_of_procedures()
    {
        return array(
            app::values()->get_current_user(),
            app::values()->get_draft_post()
        );
    }

    protected function getHtmlFromReaderForContent($reader_for_content)
    {
        $reader_for_current_user = $reader_for_content->get_reader_for_item_1();
        $this->storeReaderForCurrentUser($reader_for_current_user);

        $reader_for_post = $reader_for_content->get_reader_for_item_2();
        $reader_for_post = $reader_for_post->get_reader_for_item_1();

        $final_layout = ui::page_layouts()->admin_edit_post();
        $final_layout->add_child(
        ui::html()->div()->add_child(
            ui::forms()->edit_post_picture($reader_for_post)
        )->max_width("960px")->margin_auto()
        );
        return $final_layout;
    }
}


class PageForAdminAttachImageToPost extends BaseClassForAdminHomePage{
    protected function middle_content($reader_for_categories, $reader_for_pages, $reader_for_sections)
    {
        return ui::forms()->attach_picture_to_post();
    }
}

class HomePageForPost extends HomePageAccessingData{
    protected function array_of_procedures(){
        return array(
            app::values()->get_post(),
            app::values()->get_extended_post_tokens(),
            app::values()->get_posts_for_car_videos(),
            app::values()->get_posts_for_news()
        );
    }

    protected function getHtmlFromReaderForContent($reader_for_content)
    {
        $reader_for_main_post = $reader_for_content->get_reader_for_item_1()->get_reader_for_item_1();
        $reader_for_extended_post_tokens = $reader_for_content->get_reader_for_item_2();
        $reader_for_car_videos = $reader_for_content->get_reader_for_item_3();
        $reader_for_news = $reader_for_content->get_reader_for_item_4();

        $layout = ui::page_layouts()->home_page_of_post();

        $layout->section_for_article_details()->add_child(
            $this->getCenterState($reader_for_main_post, $reader_for_extended_post_tokens)
        );
        
        $layout->section_for_exporter_reviews()->add_child(
            ui::lists()->car_videos($reader_for_car_videos)
        );
        $layout->section_for_news()->add_child(
            ui::lists()->news($reader_for_news)
        );
        return $layout;
    }

    private function getCenterState($reader_for_main_post, $reader_for_extended_post_tokens)
    {
        return ui::html()->div()->add_child(
            ui::sections()->article_details($reader_for_main_post) .
            ui::sections()->extended_post_tokens($reader_for_extended_post_tokens)
        )->padding("0px 1.0em");
    }
}

class HomePageForWebsite extends HomePageAccessingData{
    protected function array_of_procedures(){
        return array(
            app::values()->get_most_recent_post(),
            app::values()->get_posts_for_exporter_reviews(),
            app::values()->get_most_recent_post_per_category(),

            app::values()->get_posts_for_reviews(),
            app::values()->get_posts_for_car_exporters(),
            app::values()->get_posts_for_news(),
            app::values()->get_posts_for_careers(),
            app::values()->get_posts_for_car_maintenance(),
            app::values()->get_posts_for_car_videos(),
            app::values()->get_posts_for_car_pictures()


        );
    }

    protected function getHtmlFromReaderForContent($reader_for_content)
    {
        $reader_for_reviews = $reader_for_content->get_reader_for_item_4();

        $reader_for_news = $reader_for_content->get_reader_for_item_6();
        $reader_for_careers = $reader_for_content->get_reader_for_item_7();

        $reader_for_car_videos = $reader_for_content->get_reader_for_item_9();

        return new PageLayoutForLandingPage(
            //ui::section_banners()->news().
            ui::lists()->news($reader_for_news)
            ,
            //ui::section_banners()->car_reviews().
            new ListOfMiddleItems($reader_for_reviews)
            ,
            //ui::section_banners()->car_videos().
            ui::lists()->car_videos($reader_for_car_videos)

        );

    }
    /*public function get_overflow_y()
    {
        return app::values()->hidden();
    }*/
}

class HomePageForWebsite0 extends HomePageAccessingData{
    protected function array_of_procedures(){
        return array(
            app::values()->get_most_recent_post(),
            app::values()->get_posts_for_exporter_reviews(),
            app::values()->get_most_recent_post_per_category(),

            app::values()->get_posts_for_reviews(),
            app::values()->get_posts_for_car_exporters(),
            app::values()->get_posts_for_news(),
            app::values()->get_posts_for_careers(),
            app::values()->get_posts_for_car_maintenance(),
            app::values()->get_posts_for_car_videos(),
            app::values()->get_posts_for_car_pictures()


        );
    }

    protected function getHtmlFromReaderForContent($reader_for_content)
    {
        $reader_for_most_recent_post = $reader_for_content->get_reader_for_item_1()->get_reader_for_item_1();
        $reader_for_exporter_reviews = $reader_for_content->get_reader_for_item_2();
        $reader_for_most_recent_post_per_category = $reader_for_content->get_reader_for_item_3();
        $reader_for_reviews = $reader_for_content->get_reader_for_item_4();

        $reader_for_car_exporters = $reader_for_content->get_reader_for_item_5();
        $reader_for_news = $reader_for_content->get_reader_for_item_6();
        $reader_for_careers = $reader_for_content->get_reader_for_item_7();
        $reader_for_car_maintenance = $reader_for_content->get_reader_for_item_8();
        $reader_for_car_videos = $reader_for_content->get_reader_for_item_9();
        $reader_for_car_pictures = $reader_for_content->get_reader_for_item_10();


        //=======

        $overall_layout = ui::page_layouts()->home();

        $overall_layout->section_for_reviews()->add_child(
            ui::section_banners()->car_reviews().
            ui::lists()->reviews($reader_for_reviews)
        );


        $overall_layout->section_for_careers()->add_child(
            ui::html()->div()->add_child(
                ui::section_banners()->careers().
                ui::lists()->careers($reader_for_careers)
            )
        );


        $overall_layout->section_for_exporter_reviews()->add_child(
            ui::section_banners()->car_videos().
            ui::lists()->car_videos($reader_for_car_videos)
        );

        $overall_layout->section_for_car_exporters()->add_child(
            ui::section_banners()->car_pictures().
            ui::lists()->car_pictures($reader_for_car_pictures)
        );

        $overall_layout->section_for_news()->add_child(
            ui::section_banners()->news().
            ui::lists()->news($reader_for_news)
        );

        $overall_layout->section_for_car_maintenance()->add_child(
            ui::section_banners()->car_maintenance().
            ui::lists()->car_maintenance($reader_for_car_maintenance)
        );

        return $overall_layout."";

    }
}

class HomePageForAboutUs extends HomePageAccessingData{
    protected function array_of_procedures(){
        return array(

            app::values()->get_posts_for_car_videos(),
            app::values()->get_posts_for_car_pictures(),
            app::values()->get_posts_for_news(),
            app::values()->get_posts_for_careers(),
            app::values()->get_posts_for_car_maintenance()

        );
    }

    protected function getHtmlFromReaderForContent($reader_for_content)
    {

        $reader_for_car_videos = $reader_for_content->get_reader_for_item_1();
        $reader_for_car_pictures = $reader_for_content->get_reader_for_item_2();

        $reader_for_news = $reader_for_content->get_reader_for_item_3();
        $reader_for_careers = $reader_for_content->get_reader_for_item_4();
        $reader_for_car_maintenance = $reader_for_content->get_reader_for_item_5();


        //=======

        $overall_layout = ui::page_layouts()->other_page();

        $overall_layout->section_for_reviews()->add_child(
            ui::section_banners()->about_us().
            $this->contentForAboutUs()
        );


        $overall_layout->section_for_careers()->add_child(
            ui::html()->div()->add_child(
                ui::section_banners()->careers().
                ui::lists()->careers($reader_for_careers)
            )
        );

        $overall_layout->section_for_exporter_reviews()->add_child(
            ui::section_banners()->car_videos().
            ui::lists()->car_videos($reader_for_car_videos)
        );

        $overall_layout->section_for_car_exporters()->add_child(
            ui::section_banners()->car_pictures().
            ui::lists()->car_pictures($reader_for_car_pictures)
        );

        $overall_layout->section_for_news()->add_child(
            ui::section_banners()->news().
            ui::lists()->news($reader_for_news)
        );


        $overall_layout->section_for_car_maintenance()->add_child(
            ui::section_banners()->car_maintenance().
            ui::lists()->car_maintenance($reader_for_car_maintenance)
        );

        return $overall_layout."";

    }

    private function contentForAboutUs()
    {
        $text = "
        Motokaviews.com was founded in January 2017 toget opinions from different car owners in Africa and across the world.. The word â€œMotokaâ€ is derived from Luganda, a native language in Uganda to mean a Car or Vehicle. Founded by Mr. O.R. Desmond, a Ugandan national;Motokaviews.com has an intention to obtain the African experience on new and used cars acquired both locally or imported from outside Africa. He strives to keep Africa updated on all car information across the globe and keep the world updated with car views from Africa. Motokaviews.com also regularly updates its readership with cars suitable for the African market. Motokaviews.com offers reviews from different car owners and dealerships in Africa and latest car news across the world.This has enables theAfrican car enthusiast make informed decisions about their next car by keeping them posted with car facts, knowledge when buying a car both locally or through importation, hints on selling your vehicle andexperiences from different car users.Motokaviews.com does its best to operate with professionalism and integrity when producing its content and also seeksunbiasedopinionsfrom contributors. They also look towards meeting your car needs by working with different experts in the car industry. For more information regarding motokareveiws.com, do not hesitate to contact us. 
        ";

        $container = new SmartDiv();
        $container->font_variant("initial")->padding("1.0em")->background_color("white");
        $container->add_child($text);
        return $container;
    }


}

class HomePageForNews extends HomePageAccessingData{
    protected function array_of_procedures(){
        return array(
            app::values()->get_posts_for_news()
        );
    }
    protected function getHtmlFromReaderForContent($reader_for_content)
    {
        return ui::html()->div()->add_child(
            ui::html()->div()->add_child(
                new LayoutForSmallItems($reader_for_content->get_reader_for_item_1())
            )->padding("0.5em")
        )->max_width("800px")->margin_auto();
    }    
}

class HomePageForReviews extends HomePageAccessingData{
    protected function array_of_procedures(){
        return array(
            app::values()->get_posts_for_reviews()
        );
    }
    protected function getHtmlFromReaderForContent($reader_for_content)
    {
        return ui::html()->div()->add_child(
            ui::html()->div()->add_child(
                new LayoutForSmallItems($reader_for_content->get_reader_for_item_1())
            )->padding("0.5em")
        )->max_width("800px")->margin_auto();
    }
}

class HomePageForVideos extends HomePageAccessingData{
    protected function array_of_procedures(){
        return array(
            app::values()->get_posts_for_car_videos()
        );
    }
    protected function getHtmlFromReaderForContent($reader_for_content)
    {
        //return new ListOfPostsLayout4($reader_for_content->get_reader_for_item_1());
        return ui::html()->div()->add_child(
            ui::html()->div()->add_child(
                new LayoutForSmallItems($reader_for_content->get_reader_for_item_1())
            )->padding("0.5em")
        )->max_width("800px")->margin_auto();
    }
}

class HomePageForGallery extends HomePageAccessingData{
    protected function array_of_procedures(){
        return array(
            app::values()->get_posts_for_car_pictures()
        );
    }
    protected function getHtmlFromReaderForContent($reader_for_content)
    {
        
        return new ListOfGalleryPics($reader_for_content->get_reader_for_item_1());
    }
}

class HomePageForTips extends HomePageAccessingData{
    protected function array_of_procedures(){
        return array(
            app::values()->get_posts_for_car_maintenance()
        );
    }
    protected function getHtmlFromReaderForContent($reader_for_content)
    {
        return ui::html()->div()->add_child(
            ui::html()->div()->add_child(
                new LayoutForSmallItems($reader_for_content->get_reader_for_item_1())
            )->padding("0.5em")
        )->max_width("800px")->margin_auto();
    }
}

class HomePageForJobs extends HomePageAccessingData{
    protected function array_of_procedures(){
        return array(
            app::values()->get_posts_for_careers()
        );
    }
    protected function getHtmlFromReaderForContent($reader_for_content)
    {
        //return new ListOfPostsLayout4($reader_for_content->get_reader_for_item_1());
        return ui::html()->div()->add_child(
            ui::html()->div()->add_child(
                new LayoutForSmallItems($reader_for_content->get_reader_for_item_1())
            )->padding("0.5em")
        )->max_width("800px")->margin_auto();
    }
}


class HomePageForContactUs extends HomePageAccessingData{
    protected function array_of_procedures(){
        return array(

            app::values()->get_posts_for_car_videos(),
            app::values()->get_posts_for_car_pictures(),
            app::values()->get_posts_for_news(),
            app::values()->get_posts_for_careers(),
            app::values()->get_posts_for_car_maintenance()

        );
    }

    protected function getHtmlFromReaderForContent($reader_for_content)
    {

        $reader_for_car_videos = $reader_for_content->get_reader_for_item_1();
        $reader_for_car_pictures = $reader_for_content->get_reader_for_item_2();

        $reader_for_news = $reader_for_content->get_reader_for_item_3();
        $reader_for_careers = $reader_for_content->get_reader_for_item_4();
        $reader_for_car_maintenance = $reader_for_content->get_reader_for_item_5();


        //=======

        $overall_layout = ui::page_layouts()->other_page();

        $overall_layout->section_for_reviews()->add_child(
            ui::section_banners()->contact_us().
            $this->main_content()
        );


        $overall_layout->section_for_careers()->add_child(
            ui::html()->div()->add_child(
                ui::section_banners()->careers().
                ui::lists()->careers($reader_for_careers)
            )
        );

        $overall_layout->section_for_exporter_reviews()->add_child(
            ui::section_banners()->car_videos().
            ui::lists()->car_videos($reader_for_car_videos)
        );

        $overall_layout->section_for_car_exporters()->add_child(
            ui::section_banners()->car_pictures().
            ui::lists()->car_pictures($reader_for_car_pictures)
        );

        $overall_layout->section_for_news()->add_child(
            ui::section_banners()->news().
            ui::lists()->news($reader_for_news)
        );


        $overall_layout->section_for_car_maintenance()->add_child(
            ui::section_banners()->car_maintenance().
            ui::lists()->car_maintenance($reader_for_car_maintenance)
        );

        return $overall_layout."";

    }

    private function main_content()
    {
        $layout = new SmartDiv();
        $layout->add_child(
            ui::sections()->contact_info()
        )->background_color("white")->margin("4px")->padding("1.0em");
        return $layout;
    }

}