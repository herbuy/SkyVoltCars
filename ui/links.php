<?php
class LinkToPage extends SmartLink{
    
}
class LinkToLoginPage extends LinkToPage{
    public function __construct()
    {
        parent::__construct();
        $this->set_href(ui::urls()->loginPage());
    }
}

class LinkToAdminPage extends LinkToPage{
    public function __construct()
    {
        parent::__construct();
        $this->set_href(ui::urls()->adminPage());
    }
}
//todo: edit the urls
class LinkToAdminCarReviewsPage extends LinkToPage{
    public function __construct()
    {
        parent::__construct();
        $this->set_href(ui::urls()->adminCarReviews());
    }
}

class LinkToAdminExporterReviewsPage extends LinkToPage{
    public function __construct()
    {
        parent::__construct();
        $this->set_href(ui::urls()->adminExporterReviews());
    }
}
class LinkToAdminCarExportersPage extends LinkToPage{
    public function __construct()
    {
        parent::__construct();
        $this->set_href(ui::urls()->adminCarExporters());
    }
}
class LinkToAdminCarNewsPage extends LinkToPage{
    public function __construct()
    {
        parent::__construct();
        $this->set_href(ui::urls()->adminCarNews());
    }
}

class LinkToAdminJobsPage extends LinkToPage{
    public function __construct()
    {
        parent::__construct();
        $this->set_href(ui::urls()->adminJobs());
    }
}
class LinkToAdminCarMaintenancePage extends LinkToPage{
    public function __construct()
    {
        parent::__construct();
        $this->set_href(ui::urls()->adminCarMaintenance());
    }
}
class LinkToAdminCarsPage extends LinkToPage{
    public function __construct()
    {
        parent::__construct();
        $this->set_href(ui::urls()->adminCars());
    }
}

class LinkToAdminCarVideosPage extends LinkToPage{
    public function __construct()
    {
        parent::__construct();
        $this->set_href(ui::urls()->adminCarVideos());
    }
}

class LinkToAdminCarPicturesPage extends LinkToPage{
    public function __construct()
    {
        parent::__construct();
        $this->set_href(ui::urls()->adminCarPictures());
    }
}

class SocialMediaLinks extends SmartDiv{
    public function __construct(){
        parent::__construct();

        $this->add_child($this->fbLink())->width_auto();
        $this->add_child($this->twitterLink())->width_auto();
        $this->add_child($this->youtubeLink())->width_auto();
    }

    private function fbLink()
    {
        return (new SmartLink())->
        set_href(ui::external_urls()->our_facebook_page())->
        add_child(
            ui::images()->fb_icon()->width("3.0em")->margin("4px")
        );
    }

    private function twitterLink()
    {
        return (new SmartLink())->
        set_href(ui::external_urls()->our_twitter_page())->
        add_child(
            ui::images()->twitter_icon()->width("3.0em")->margin("4px")
        );
    }

    private function youtubeLink()
    {
        return (new SmartLink())->
        set_href(ui::external_urls()->our_youtube_channel())->
        add_child(
            ui::images()->youtube_icon()->width("3.0em")->margin("4px")
        );
    }
}
