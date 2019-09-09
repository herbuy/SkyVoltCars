<?php
class CmdFactory{
    public function doNothing(){
        return new CmdForDoNothing();
    }
    public function notifyEmptyCmd()
    {
        return new CmdForNotifyEmptyCmd();
    }

    public function createAccount()
    {
        return new CmdForCreateAccount();
    }
    public function login()
    {
        return new CmdForLogin();
    }
    public function logout()
    {
        return new CmdForLogout();
    }

    public function startNewPost()
    {
        return new CmdForStartNewPost();
    }
    public function createMultiplePosts()
    {
        return new CmdForCreateMultiplePosts();
    }

    public function deletePost()
    {
        return new CmdForDeletePost();
    }

    public function publishPost()
    {
        return new CmdForPublishPost();
    }

    public function unpublish_post()
    {
        return new CmdForUnPublishPost();
    }

    public function publishAllPosts()
    {
        return new CmdForPublishAllPosts();
    }

    public function addPost(){
        return new CmdForAddPost();
    }
    public function addImage()
    {
        return new CmdForAddImage();
    }
    public function attachImageToPost()
    {
        return new CmdForAttachImageToPost();
    }
    public function GetPost()
    {
        return new CmdForGetPost();
    }

    public function GetPosts()
    {
        return new CmdForGetPosts();
    }

    public function GetCars()
    {
        return new CmdForGetCars();
    }

    public function GetDataPage()
    {
        return new CmdForGetDataPage();
    }

    public function start_new_post()
    {
        return new CmdForStartNewPost();
    }

    public function start_new_car_review()
    {
        return new CmdForStartNewCarReview();
    }
    public function start_new_car_video()
    {
        return new CmdForStartNewCarVideo();
    }
    public function start_new_car_news()
    {
        return new CmdForStartNewCarNews();
    }

    public function start_new_car_picture()
    {
        return new CmdForStartNewCarPicture();
    }

    public function start_new_exporter_review()
    {
        return new CmdForStartNewExporterReview();
    }

    public function edit_post_title()
    {
        return new CmdForEditPostTitle();
    }
    public function edit_post_content()
    {
        return new CmdForEditPostContent();
    }

    public function edit_post_picture()
    {
        return new CmdForEditPostPicture();
    }

    public function edit_post_video()
    {
        return new CmdForEditPostVideo();
    }

    public function edit_extended_post_content()
    {
        return new CmdForEditExtendedPostContent();
    }

    public function edit_car_selected()
    {
        return new CmdForEditCarSelected();
    }

    public function admin_edit_car_exporter_selected()
    {
        return new CmdForAdminEditPostCarExporterSelected();
    }

    public function like_the_post()
    {
        return new CmdForLikeThePost();
    }
    public function register_the_view()
    {
        return new CmdForRegisterTheView();
    }

    public function post_comment()
    {
        return new CmdForPostComment();
    }
    public function approve_comment()
    {
        return new CmdForApproveComment();
    }
    public function move_comment_to_trash()
    {
        return new CmdForMoveCommentToTrash();
    }
    public function move_comment_to_spam()
    {
        return new CmdForMoveCommentToSpam();
    }

}