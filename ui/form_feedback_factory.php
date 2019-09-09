<?php
class FormFeedbackFactory{
    public function addImage(){
        return new FormFeedbackForAddImage();
    }

    public function addPost()
    {
        return new FormFeedbackForAddPost();
    }
    public function createMultiplePosts()
    {
        return new FormFeedbackForCreateMultiplePosts();
    }


    public function attachImageToPost()
    {
        return new FormFeedbackForAttachImageToPost();
    }
    public function login()
    {
        return new FormFeedbackForLogin();
    }
    public function logout()
    {
        return new FormFeedbackForLogout();
    }
    public function start_new_post()
    {
        return new FormFeedbackForStartNewPost();
    }

    public function start_new_car_exporter()
    {
        return new FormFeedbackForStartNewCarExporter();
    }
    public function start_new_car_news()
    {
        return new FormFeedbackForStartNewCarNews();
    }
    public function start_new_job_opportunity()
    {
        return new FormFeedbackForStartNewJobOpportunity();
    }
    public function start_new_car_maintenance()
    {
        return new FormFeedbackForStartNewCarMaintenance();
    }
    public function start_new_car()
    {
        return new FormFeedbackForStartNewCar();
    }

    public function start_new_car_review()
    {
        return new FormFeedbackForStartNewCarReview();
    }
    public function start_new_car_video()
    {
        return new FormFeedbackForStartNewCarVideo();
    }
    public function start_new_car_picture()
    {
        return new FormFeedbackForStartNewCarPicture();
    }

    public function start_new_exporter_review()
    {
        return new FormFeedbackForStartNewExporterReview();
    }

    public function edit_post_title()
    {
        return new FormFeedbackForEditPostTitle();
    }
    public function delete_post()
    {
        return new FormFeedbackForDeletePost();
    }
    public function publish_post()
    {
        return new FormFeedbackForPublishPost();
    }
    public function unpublish_post()
    {
        return new FormFeedbackForUnPublishPost();
    }


    public function publish_all_posts()
    {
        return new FormFeedbackForPublishAllPosts();
    }

    public function edit_post_content()
    {
        return new FormFeedbackForEditPostContent();
    }
    public function edit_extended_post_content()
    {
        return new FormFeedbackForEditExtendedPostContent();
    }

    public function edit_post_picture()
    {
        return new FormFeedbackForEditPostPicture();
    }

    public function edit_post_video()
    {
        return new FormFeedbackForEditPostVideo();
    }

    public function edit_post_car_selected()
    {
        return new FormFeedbackForEditCarSelected();
    }

    public function edit_post_car_exporter_selected()
    {
        return new FormFeedbackForEditCarExporterSelected();
    }

}
