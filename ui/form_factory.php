<?php
class FormFactory{
    public function add_post($reader_for_categories,$reader_for_pages,$reader_for_sections){
        return new FormForAddPost($reader_for_categories,$reader_for_pages,$reader_for_sections);
    }

    public function add_picture()
    {
        return new FormForAddPicture();
    }
    public function attach_picture_to_post()
    {
        return new FormForAttactPictureToPost();
    }
    public function login()
    {
        return new FormForLogin();
    }
    public function logout()
    {
        return new FormForLogout();
    }
    public function start_new_post($reader_for_sections)
    {
        return new FormForStartNewPost($reader_for_sections);
    }
    public function start_new_car_exporter()
    {
        return new FormForStartNewCarExporter();
    }
    public function start_new_car_news()
    {
        return new FormForStartNewCarNews();
    }
    public function start_new_job_opportunity()
    {
        return new FormForStartNewJobOpportunity();
    }
    public function start_new_car_maintenance()
    {
        return new FormForStartNewCarMaintenance();
    }
    public function start_new_car()
    {
        return new FormForStartNewCar();
    }

    public function start_new_car_review($reader_for_cars)
    {
        return new FormForStartNewCarReview($reader_for_cars);
    }
    public function start_new_car_video($reader_for_cars)
    {
        return new FormForStartNewCarVideo($reader_for_cars);
    }
    public function start_new_car_picture($reader_for_cars)
    {
        return new FormForStartNewCarPicture($reader_for_cars);
    }
    
    public function start_new_exporter_review($reader_for_exporter_review)
    {
        return new FormForStartNewExporterReview($reader_for_exporter_review);
    }

    public function edit_post_content($reader_for_post)
    {
        return new FormForEditPostContent($reader_for_post);
    }
    public function edit_extended_post_content($reader_for_post)
    {
        return new FormForEditExtendedPostContent($reader_for_post);
    }
    public function edit_post_title($reader_for_post,$reader_for_sections)
    {
        return new FormForEditPostTitle($reader_for_post,$reader_for_sections);
    }
    public function edit_car_selected($reader_for_post, $reader_for_cars)
    {
        return new FormForEditCarSelected($reader_for_post,$reader_for_cars);
    }

    public function edit_car_exporter_selected($reader_for_post, $reader_for_car_exporters)
    {
        return new FormForEditCarExporterSelected($reader_for_post,$reader_for_car_exporters);
    }

    public function edit_post_picture($reader_for_post)
    {
        return new FormForEditPostPicture($reader_for_post);
    }
    
    public function edit_post_video($reader_for_post)
    {
        return new FormForEditPostVideo($reader_for_post);
    }

    public function delete_post($file_name)
    {
        return new FormForDeletePost($file_name);
    }

    public function publish_post($file_name)
    {
        return new FormForPublishPost($file_name);
    }
    public function unpublish_post($file_name)
    {
        return new FormForUnPublishPost($file_name);
    }

    public function publish_all_posts()
    {
        return new FormForPublishAllPosts();
    }

    public function create_multiple_posts($num_records,$reader_for_sections)
    {
        return new FormForInsertBulkPosts($num_records,$reader_for_sections);
    }

    
}