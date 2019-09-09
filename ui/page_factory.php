<?php
class PageFactory{
    public function home(){
        return new HomePageForWebsite();
    }

    public function login()
    {
        return  new HomePageForLogin();
    }

    public function admin()
    {
        return  new HomePageForAdmin();
    }

    public function get_post()
    {
        return  new HomePageForPost();
    }

    public function admin_add_images()
    {
        return new PageForAdminAddImages();
    }

    public function attach_image_to_post()
    {
        return new PageForAdminAttachImageToPost();
    }



    public function admin_view_posts()
    {
        return new HomePageForAdminViewPosts();
    }
    public function admin_statistics()
    {
        return new HomePageForAdminStatistics();
    }
    public function admin_edit_post()
    {
        return new HomePageForAdminEditPost();
    }
    public function admin_edit_car_review()
    {
        return new HomePageForAdminEditCarReview();
    }

    public function admin_edit_post_car_description()
    {
        return new HomePageForAdminEditPostCarDescription();
    }

    public function admin_edit_post_car_exporter_selected()
    {
        return new HomePageForAdminEditPostCarExporterSelected();
    }
    
    public function admin_edit_post_title()
    {
        return new HomePageForAdminEditPostTitle();
    }

    public function admin_edit_post_content()
    {
        return new HomePageForAdminEditPostContent();
    }
    public function admin_edit_extended_post_content()
    {
        return new HomePageForAdminEditExtendedPostContent();
    }
    public function admin_edit_post_picture()
    {
        return new HomePageForAdminEditPostPicture();
    }

    public function admin_view_posts_published()
    {
        return new HomePageForAdminViewPostsPublished();
    }

    public function news()
    {
        return new HomePageForNews();
    }
    public function reviews()
    {
        return new HomePageForReviews();
    }
    public function videos()
    {
        return new HomePageForVideos();
    }
    public function gallery()
    {
        return new HomePageForGallery();
    }
    public function tips()
    {
        return new HomePageForTips();
    }
    public function jobs()
    {
        return new HomePageForJobs();
    }
    public function about_us()
    {
        return new HomePageForAboutUs();
    }
    public function contact_us()
    {
        return new HomePageForContactUs();
    }

    public function admin_car_reviews()
    {
        return new HomePageForAdminCarReviews();
    }

    public function admin_exporter_reviews()
    {
        return new HomePageForAdminExporterReviews();
    }
    public function admin_car_videos()
    {
        return new HomePageForAdminCarVideos();
    }
    public function admin_car_pictures()
    {
        return new HomePageForAdminCarPictures();
    }



    public function admin_car_exporters()
    {
        return new HomePageForAdminCarExporters();
    }

    public function admin_news()
    {
        return new HomePageForAdminCarNews();
    }

    public function admin_jobs()
    {
        return new HomePageForAdminJobs();
    }

    public function admin_car_maintenance()
    {
        return new HomePageForAdminCarMaintenance();
    }

    public function admin_cars()
    {
        return new HomePageForAdminCars();
    }

}