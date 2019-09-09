<?php

class ResultArrayFactory{
    public function do_nothing(){
        return new ResultArrayForDoNothing();
    }
    public function notify_empty_cmd(){
        return new ResultArrayForNotifyEmptyCmd();
    }
    
    public function add_post(){
        return new ResultArrayForAddPost();
    }
    
    public function get_post(){
        return new ResultArrayForGetPost();
    }

    public function get_posts()
    {
        return new ResultArrayForGetPosts();
    }
    
    public function get_cars()
    {
        return new ResultArrayForGetCars();
    }

    public function get_data_page()
    {
        return new ResultArrayForGetDataPage();
    }

    public function add_image()
    {
        return new ResultArrayForAddImage();
    }

    public function attach_image_to_post()
    {
        return new ResultArrayForAttachImageToPost();
    }

    public function start_new_post()
    {
        return new ResultArrayForStartNewPost();
    }
    public function start_new_car_review()
    {
        return new ResultArrayForStartNewCarReview();
    }
    public function start_new_car_video()
    {
        return new ResultArrayForStartNewCarVideo();
    }
    public function start_new_car_news()
    {
        return new ResultArrayForStartNewCarNews();
    }

    public function start_new_car_picture()
    {
        return new ResultArrayForStartNewCarPicture();
    }

    public function start_new_exporter_review()
    {
        return new ResultArrayForStartNewCarVideo();
    }
    public function create_multiple_posts()
    {
        return new ResultArrayForCreateMultiplePosts();
    }
    
    public function delete_post()
    {
        return new ResultArrayForDeletePost();
    }

    public function publish_post()
    {
        return new ResultArrayForPublishPost();
    }
    public function unpublish_post()
    {
        return new ResultArrayForUnPublishPost();
    }

    public function publish_all_posts()
    {
        return new ResultArrayForPublishAllPosts();
    }
    public function edit_post_title()
    {
        return new ResultArrayForEditPostTitle();
    }

    public function edit_post_content()
    {
        return new ResultArrayForEditPostContent();
    }

    public function edit_post_picture()
    {
        return new ResultArrayForEditPostPicture();
    }
    public function edit_post_video()
    {
        return new ResultArrayForEditPostVideo();
    }
    public function edit_extended_post_content()
    {
        return new ResultArrayForEditExtendedPostContent();
    }

    public function edit_car_selected()
    {
        return new ResultArrayForEditCarSelected();
    }

    public function admin_edit_post_car_exporter_selected()
    {
        return new ResultArrayForAdminEditPostCarExporterSelected();
    }

    public function create_account()
    {
        return new ResultArrayForCreateAccount();
    }
    public function login()
    {
        return new ResultArrayForLogin();
    }
    public function logout()
    {
        return new ResultArrayForLogout();
    }

    public function like_the_post()
    {
        return new ResultArrayForLikeThePost();
    }
    public function register_the_view()
    {
        return new ResultArrayForRegisterViewForThePost();
    }

    public function post_comment()
    {
        return new ResultArrayForPostComment();
    }
    public function approve_comment()
    {
        return new ResultArrayForApproveComment();
    }
    public function move_comment_to_trash()
    {
        return new ResultArrayForMoveCommentToTrash();
    }
    public function move_comment_to_spam()
    {
        return new ResultArrayForMoveCommentToSpam();
    }

}
