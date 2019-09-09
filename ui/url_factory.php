<?php
class UrlFactory{
    public function asset($file_name){
        return new UrlForAsset($file_name);
    }
    public function adminPage(){
        return new UrlForAdminPage();
    }
    public function adminCarReviews(){
        return new UrlForAdminCarReviewsPage();
    }
    public function adminExporterReviews(){
        return new UrlForAdminExporterReviewsPage();
    }
    public function adminCarExporters(){
        return new UrlForAdminCarExportersPage();
    }
    public function adminCarNews(){
        return new UrlForAdminCarNewsPage();
    }
    public function adminJobs(){
        return new UrlForAdminJobsPage();
    }
    public function adminCarMaintenance(){
        return new UrlForAdminCarMaintenancePage();
    }
    public function adminCars(){
        return new UrlForAdminCarsPage();
    }
    public function adminCarVideos(){
        return new UrlForAdminCarVideosPage();
    }
    public function adminCarPictures(){
        return new UrlForAdminCarPicturesPage();
    }

    public function loginPage(){
        return new UrlForLoginPage();
    }
    public function adminEditPost($file_name){
        return new UrlForAdminEditPost($file_name);
    }
    public function adminEditCarReview($file_name){
        return new UrlForAdminEditCarReview($file_name);
    }
    public function adminEditPostTitle($file_name)
    {
        return new UrlForAdminEditPostTitle($file_name);
    }

    public function adminEditCarDescription($file_name)
    {
        return new UrlForAdminEditCarDescription($file_name);
    }
    public function adminEditCarExporterSelected($file_name)
    {
        return new UrlForAdminEditCarExporterSelected($file_name);
    }

    public function adminEditPostContent($file_name)
    {
        return new UrlForAdminEditPostContent($file_name);
    }
    public function adminEditExtendedPostContent($file_name)
    {
        return new UrlForAdminEditExtendedPostContent($file_name);
    }

    public function adminChangePostPicture($file_name)
    {
        return new UrlForAdminChangePostPicture($file_name);
    }

    public function manage_posts()
    {
        return new UrlForAdminViewPosts();
    }
    

    public function view_post($file_name)
    {
        return new UrlForViewPost($file_name);
    }

    public function view_image($file_name)
    {
        return new UrlForViewImage($file_name);
    }

    public function home()
    {
        return new UrlForHome();
    }

    public function add_pictures()
    {
        return new UrlForAddPictures();
    }
    public function attach_picture_to_post($file_name)
    {
        return new UrlForAttachPictureToPost($file_name);
    }
    public function manage_pictures()
    {
        return new UrlForManagePictures();
    }

    public function adminViewPosts()
    {
        return new UrlForAdminViewPosts();
    }

    public function adminViewPostsPublished()
    {
        return new UrlForAdminViewPostsPublished();
    }

    public function about()
    {
        return new UrlForAbout();
    }
    public function privacy_policy()
    {
        return new UrlForPrivacyPolicy();
    }

    public function news()
    {
        return new UrlForNews();
    }
    public function reviews()
    {
        return new UrlForReviews();
    }
    public function videos()
    {
        return new UrlForVideos();
    }
    public function gallery()
    {
        return new UrlForGallery();
    }
    public function tips()
    {
        return new UrlForTips();
    }
    public function jobs()
    {
        return new UrlForJobs();
    }
    
    public function contacts_us()
    {
        return new UrlForContactUs();
    }

    public function statistics()
    {
        return new UrlForStatistics();
    }
}