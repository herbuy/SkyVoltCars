<?php
class QueryFactory{
    public function addPost($file_name,$id,$title,$content,$category_id,$page_id,$section_id){
        return new QueryForAddPost($file_name,$id,$title,$content,$category_id,$page_id,$section_id);
    }


    public function getPost($file_name){
        return new QueryForGetPost($file_name);
    }

    public function create_tables(){
        return new QueryForCreateTables();
    }

    public function getPosts()
    {
        return new QueryForGetPosts();
    }

    public function getCars()
    {
        return new QueryForGetCars();
    }
    public function getCarVideos()
    {
        return new QueryForGetCarVideos();
    }
    public function getCarPictures()
    {
        return new QueryForGetCarPictures();
    }
       
    public function getMostRecentPostPerCategory()
    {
        return new QueryForGetMostRecentPostPerCategory();
    }

    public function getMostRecentPost()
    {
        return new QueryForGetMostRecentPost();
    }

    public function getCategories()
    {
        return new QueryForGetCategories();
    }

    public function getSections()
    {
        return new QueryForGetSections();
    }
    public function getPages()
    {
        return new QueryForGetPages();
    }

    public function update_post_picture($post_file_name, $image_file_name)
    {
        return new QueryForUpdatePostPicture($post_file_name,$image_file_name);
    }
    public function edit_post_picture($post_file_name, $image_file_name)
    {
        return new QueryForEditPostPicture($post_file_name,$image_file_name);
    }

    public function getPostsInMainSection()
    {
        return new QueryForGetPostsInMainSection();
    }

    public function getPostsForExporterReviews(){
        return new QueryForGetPostsForExporterReviews();
    }

    public function getPostsForCarExporters()
    {
        return new QueryForGetPostsForCarExporters();
        
    }

    public function getPostsForNews()
    {
        return new QueryForGetPostsForNews();
    }

    public function getPostsForCareers()
    {
        return new QueryForGetPostsForCareers();
    }
    public function getPostsForCarMaintenance()
    {
        return new QueryForGetPostsForCarMaintenance();
    }

    public function getFileContent()
    {
        return new QueryForGetFileContent();
    }

    public function start_new_post($file_name, $id, $title,$content='',$extended_post_content='',$section_id=0,$car_id=0,$car_exporter_id=0)
    {
        return new QueryForStartNewPost($file_name,$id,$title,$content,$extended_post_content,$section_id,$car_id,$car_exporter_id);
    }
    public function create_multiple_posts($title_array,$content_array,$extended_post_content_array,$section_id_array){
        return new QueryForCreateMultiplePosts($title_array,$content_array,$extended_post_content_array,$section_id_array);
    }
    public function getDraftPost($file_name){
        return new QueryForGetDraftPost($file_name);
    }
    public function getDraftPosts()
    {
        return new QueryForGetDraftPosts();
    }


    public function edit_post_title($file_name, $title,$section_id)
    {
        return new QueryForEditPostTitle($file_name,$title,$section_id);
    }
    public function edit_post_video($file_name, $youtube_video_id)
    {
        return new QueryForEditPostVideo($file_name,$youtube_video_id);
    }

    public function edit_post_car_id($file_name, $new_car_id)
    {
        return new QueryForEditPostCarId($file_name,$new_car_id);
    }

    public function edit_post_car_exporter_id($file_name, $new_car_exporter_id)
    {
        return new QueryForEditPostCarExporterId($file_name,$new_car_exporter_id);
    }

    public function edit_post_content($file_name, $content)
    {
        return new QueryForEditPostContent($file_name,$content);
    }

    public function edit_extended_post_content($file_name, $content)
    {
        return new QueryForEditExtendedPostContent($file_name,$content);
    }

    public function delete_post($file_name)
    {
        return new QueryForDeleteDraftPost($file_name);
    }

    public function publish_post($file_name)
    {
        return new QueryForPublishPost($file_name);
    }
    public function unpublish_post($file_name)
    {
        return new QueryForUnPublishPost($file_name);
    }

    public function publish_all_posts()
    {
        return new QueryForPublishAllPosts();
    }

    public function getExtendedPostContent($file_name)
    {
        return new QueryForGetExtendedPostContent($file_name);
    }
    public function getExtendedPostTokens($file_name)
    {
        return new QueryForGetExtendedPostTokens($file_name);
    }

    public function get_stats_per_section()
    {
        return new QueryForGetStatsPerSection();
    }
    public function get_stats_per_year()
    {
        return new QueryForGetStatsPerYear();
    }
    public function get_stats_per_month()
    {
        return new QueryForGetStatsPerMonth();
    }
    public function get_stats_per_week()
    {
        return new QueryForGetStatsPerWeek();
    }
    public function get_stats_per_day()
    {
        return new QueryForGetStatsPerDay();
    }

    public function create_account($email, $password)
    {
        return new QueryForCreateAccount($email,$password);
    }
    public function login($email, $password)
    {
        return new QueryForLogin($email,$password);
    }
    public function logout()
    {
        return new QueryForLogout();
    }

    public function get_current_user()
    {
        return new QueryForCurrentUser();
    }

    public function comments()
    {
        return new FactoryForComments();
    }
    public function views()
    {
        return new FactoryForViews();
    }
    public function likes()
    {
        return new FactoryForLikes();
    }

    public function posts(){
        return new FactoryOfQueriesForPosts();
    }


}

class FactoryOfQueriesForPosts{
    public function published()
    {
        return new FactoryOfQueriesForPublishedPosts();
    }
    public function draft()
    {
        return new FactoryOfQueriesForDraftPosts();
    }
}

class FactoryOfQueriesForPublishedPosts{
    public function all_types(){
        return new QueryForGetPosts();
    }

    public function find_by_file_name($file_name)
    {
        return new QueryForGetPost($file_name);
    }
    public function most_recent()
    {
        return new QueryForMostRecentPages();
    }

    public function oldest()
    {
        return new QueryForOldestPages();
    }
}

class FactoryOfQueriesForDraftPosts{
    public function all_types(){
        return new QueryForGetDraftPosts();
    }

    public function find_by_file_name($file_name)
    {
        return new QueryForGetDraftPost($file_name);
    }
}



class FactoryForViews{

    public function add($file_name)
    {
        return new QueryForRegisterViewForThePost($file_name);
    }

    public function most()
    {
        return new QueryForPagesWithMostViews();
    }
}
class FactoryForLikes{
    public function add($file_name)
    {
        return new QueryForLikeThePost($file_name);
    }

    public function most()
    {
        return new QueryForPagesWithMostLikes();
    }
}
class FactoryForComments{

    public function all()
    {
        return new QueryForGetComments();
    }

    public function for_post($file_name)
    {
        return new QueryForGetCommentForPost($file_name);
    }

    public function post($file_name,$content,$full_name,$email)
    {
        return new QueryForPostComment($file_name,$content,$full_name,$email);
    }

    public function approve($entity_id)
    {
        return new QueryForApproveComment($entity_id);
    }
    public function moveToTrash($entity_id)
    {
        return new QueryForMoveCommentToTrash($entity_id);
    }
    public function moveToSpam($entity_id)
    {
        return new QueryForMoveCommentToSpam($entity_id);
    }

    public function most()
    {
        return new QueryForPagesWithMostComments();
    }

}