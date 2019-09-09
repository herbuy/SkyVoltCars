<?php

abstract class UrlForResource{
    ///----- CHANGE THIS LINE TO TRUE IF APP IS ONLINE

    //===================
    /** @var WebAddress $web_address */
    private $web_address;
    public function __construct()
    {
        $this->web_address = app::settings()->build_web_address($this->getAddressObject());
        //$this->web_address->set_fragment(PageSectionId::top());

    }

    protected function add_path_part($string){
        $this->web_address->add_path_part($string);
    }
    protected function set_file_name($string){
        $this->web_address->set_file_name($string);
    }
    protected function set_query_parameter($key,$value){
        $this->web_address->set_query_parameter($key,$value);
    }
    protected function set_fragment($string){
        $this->web_address->set_fragment($string);
    }

    protected function getAddressObject()
    {
        return new HttpWebAddress();
    }
    public function __toString()
    {
        $full_url_as_String = $this->web_address->__toString();        
        //app::sitemap()->addUrlFromString($full_url_as_String);
        return $full_url_as_String;
    }
    public function addToSitemap(){
        app::sitemap()->addUrlFromString($this->web_address->__toString());
        return $this;
    }
    
    public function gotoAddress(){
        $address_string = $this->__toString();
        header("location: $address_string");
    }

    public function gotoAddressIf($condition)
    {
        if ($condition) {
            $this->gotoAddress();
        }
    }
    public function gotoAddressIfSubmittedForm(){        
        $this->gotoAddressIf(
            ContentTypeSentToServer::get()->is_multi_part_form_data()
        );
    }

    public function toImage(){
        $img = new SmartImage($this);
        return $img;
    }
    
    public function toLink(){
        $link = ui::html()->anchor();
        $link->set_href($this);
        return $link;
    }
}
class UrlForHome extends UrlForResource{
    
}

class UrlForAsset extends UrlForResource{
    public function __construct($file_name)
    {
        parent::__construct();
        $this->add_path_part("assets");
        $this->add_path_part($file_name);
    }
}
class UrlForAdminPage extends UrlForResource{
    public function __construct()
    {
        parent::__construct();
        $this->add_path_part(app::values()->admin());
    }
}
class UrlForAdminCarReviewsPage extends UrlForAdminPage{
    public function __construct()
    {
        parent::__construct();
        $this->add_path_part(app::values()->car_reviews());
    }
}
class UrlForAdminExporterReviewsPage extends UrlForAdminPage{
    public function __construct()
    {
        parent::__construct();
        $this->add_path_part(app::values()->exporter_reviews());
    }
}
class UrlForAdminCarExportersPage extends UrlForAdminPage{
    public function __construct()
    {
        parent::__construct();
        $this->add_path_part(app::values()->car_exporters());
    }
}
class UrlForAdminCarNewsPage extends UrlForAdminPage{
    public function __construct()
    {
        parent::__construct();
        $this->add_path_part(app::values()->car_news());
    }
}
class UrlForAdminJobsPage extends UrlForAdminPage{
    public function __construct()
    {
        parent::__construct();
        $this->add_path_part(app::values()->jobs());
    }
}
class UrlForAdminCarMaintenancePage extends UrlForAdminPage{
    public function __construct()
    {
        parent::__construct();
        $this->add_path_part(app::values()->car_maintenance());
    }
}
class UrlForAdminCarsPage extends UrlForAdminPage{
    public function __construct()
    {
        parent::__construct();
        $this->add_path_part(app::values()->cars());
    }
}

class UrlForAdminCarVideosPage extends UrlForAdminPage{
    public function __construct()
    {
        parent::__construct();
        $this->add_path_part(app::values()->car_videos());
    }
}

class UrlForAdminCarPicturesPage extends UrlForAdminPage{
    public function __construct()
    {
        parent::__construct();
        $this->add_path_part(app::values()->car_pictures());
    }
}

class UrlForLoginPage extends UrlForResource{
    public function __construct()
    {
        parent::__construct();
        $this->add_path_part(app::values()->login());
    }
}

class UrlForStatistics extends UrlForAdminPage{
    public function __construct()
    {
        parent::__construct();
        $this->add_path_part(app::values()->statistics());
    }
}

class UrlForAbout extends UrlForResource{
    public function __construct()
    {
        parent::__construct();
        $this->add_path_part(app::values()->about());
    }
}
class UrlForPrivacyPolicy extends UrlForResource{
    public function __construct()
    {
        parent::__construct();
        $this->add_path_part(app::values()->privacy_policy());
    }
}

class UrlForNews extends UrlForResource{
    public function __construct()
    {
        parent::__construct();
        $this->add_path_part(app::values()->news());
    }
}
class UrlForReviews extends UrlForResource{
    public function __construct()
    {
        parent::__construct();
        $this->add_path_part(app::values()->reviews());
    }
}
class UrlForVideos extends UrlForResource{
    public function __construct()
    {
        parent::__construct();
        $this->add_path_part(app::values()->videos());
    }
}
class UrlForGallery extends UrlForResource{
    public function __construct()
    {
        parent::__construct();
        $this->add_path_part(app::values()->gallery());
    }
}
class UrlForTips extends UrlForResource{
    public function __construct()
    {
        parent::__construct();
        $this->add_path_part(app::values()->tips());
    }
}
class UrlForJobs extends UrlForResource{
    public function __construct()
    {
        parent::__construct();
        $this->add_path_part(app::values()->jobs());
    }
}
class UrlForContactUs extends UrlForResource{
    public function __construct()
    {
        parent::__construct();
        $this->add_path_part(app::values()->contact_us());
    }
}
abstract class UrlForAdminEdit extends UrlForAdminPage{
    public function __construct()
    {
        parent::__construct();
        $this->add_path_part(app::values()->edit());
    }
}

abstract class UrlForAdminEditPostBaseClass extends UrlForAdminEdit{
    public function __construct($section_name,$file_name,$field_name = null)
    {
        parent::__construct();
        $this->add_path_part($section_name);
        $this->add_path_part($file_name);
        if(!is_null($field_name)){
            $this->add_path_part($field_name);
        }
    }
}

class UrlForAdminEditPost extends UrlForAdminEditPostBaseClass{
    public function __construct($file_name)
    {
        parent::__construct(app::values()->post(),$file_name);
    }
}

class UrlForAdminEditCarReview extends UrlForAdminEditPostBaseClass{
    public function __construct($file_name)
    {
        parent::__construct(app::values()->car_review(),$file_name);
    }
}

class UrlForAdminEditPostTitle extends UrlForAdminEditPostBaseClass{
    public function __construct($file_name)
    {
        parent::__construct(app::values()->post(),$file_name,app::values()->title());
    }
}
class UrlForAdminEditCarDescription extends UrlForAdminEditPostBaseClass{
    public function __construct($file_name)
    {
        parent::__construct(app::values()->post(),$file_name,app::values()->car_description());
    }
}
class UrlForAdminEditCarExporterSelected extends UrlForAdminEditPostBaseClass{
    public function __construct($file_name)
    {
        parent::__construct(app::values()->post(),$file_name,app::values()->car_exporter_selected());
    }
}


class UrlForAdminEditPostContent extends UrlForAdminEditPostBaseClass{
    public function __construct($file_name)
    {
        parent::__construct(app::values()->post(),$file_name,app::values()->content());
    }
}
class UrlForAdminEditExtendedPostContent extends UrlForAdminEditPostContent{
    public function __construct($file_name)
    {
        parent::__construct($file_name);
        $this->add_path_part(app::values()->extended());
    }
}

class UrlForAdminChangePostPicture extends UrlForAdminEditPost{
    public function __construct($file_name)
    {
        parent::__construct($file_name);
        $this->add_path_part(app::values()->picture());
    }
}


class UrlForViewPost extends UrlForResource{
    public function __construct($file_name)
    {
        parent::__construct();
        $this->add_path_part(app::values()->posts());
        $this->add_path_part($file_name);
    }
}
class UrlForViewImage extends UrlForResource{
    public function __construct($file_name)
    {
        parent::__construct();
        $this->add_path_part(app::values()->images());
        $this->add_path_part(app::values()->get());
        $this->add_path_part($file_name);
    }
}

abstract class UrlForAdminView extends UrlForAdminPage{
    public function __construct()
    {
        parent::__construct();
        $this->add_path_part(app::values()->view());
    }
}

class UrlForAdminViewPosts extends UrlForAdminView{
    public function __construct()
    {
        parent::__construct();
        $this->add_path_part(app::values()->posts());
    }
}

class UrlForAdminViewPostsPublished extends UrlForAdminViewPosts{
    public function __construct()
    {
        parent::__construct();
        $this->add_path_part(app::values()->published());
    }
}
class UrlForAdminManagePost extends UrlForAdminViewPosts{
    public function __construct($file_name)
    {
        parent::__construct();
        $this->add_path_part($file_name);
    }
}

class UrlForAttachPictureToPost extends UrlForAdminManagePost{
    public function __construct($file_name)
    {
        parent::__construct($file_name);
        $this->add_path_part(app::values()->add_image());
    }
}

class UrlForManagePictures extends UrlForAdminPage{
    public function __construct()
    {
        parent::__construct();
        $this->add_path_part(app::values()->images());
    }
}
class UrlForAddPictures extends UrlForManagePictures{
    public function __construct()
    {
        parent::__construct();
        $this->add_path_part(app::values()->add());
    }
}
