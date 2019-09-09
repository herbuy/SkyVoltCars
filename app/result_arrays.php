<?php

interface IResultArray{
    public function get();
}

class ResultArrayForNotifyEmptyCmd implements IResultArray{
    public function get()
    {
        throw new Exception("specify value for ".app::values()->cmd());
    }
}
class ResultArrayForDoNothing implements IResultArray{
    public function get()
    {
        return ["did nothing"];
    }
}


class ResultArrayForCreateAccount implements IResultArray{
    public function get()
    {

        #=====
        throw new Exception("account creation not yet supported");

        $email = app::argument()->email_address()->getValue();
        $password = app::argument()->password()->getValue();


        $array = Db::queries()->create_account($email,$password)->
        result()->
        to_array();

        return $array;

    }
}
class ResultArrayForLogin implements IResultArray{
    public function get()
    {
        /*print "<pre>";
        print htmlspecialchars(
            new QueryForUpdateOldPostsWithDateInformationFromTimestamp()
        );
        print "</pre>";
        exit;*/
        #=========
        $email_address = app::argument()->email_address()->getValue();
        $password = app::argument()->password()->getValue();
        
        $array = Db::queries()->login($email_address,$password)->
        result()->
        to_array();

        return array(
            app::values()->email_address()=>$email_address
        );

    }
}
class ResultArrayForLogout implements IResultArray{
    public function get()
    {
        //throw new Exception("logout not yet implemented");
        
        $array = Db::queries()->logout()->
        result()->
        to_array();

        return $array;

    }
}

class ResultArrayForAddImage implements IResultArray{
    public function get()
    {

        //save the uploaded image
        $uploaded_picture_file_to_save = new UploadedPictureFileToSave(app::values()->file_to_upload());        
        $saved_picture_file = $uploaded_picture_file_to_save->saveAsPictureToFolder(dirname(__FILE__)."/pictures_uploaded");

        //todo: wrap around try catch, and add code to delete all versions of pic [including original] if exception occurs - before re-throwing the exception
        $thumbnail = $saved_picture_file->getThumbnail(600,600);        
        $file_path = $thumbnail->renameFileTo(EntityIdGenerator::newId());

        return array(app::values()->file_name() => $file_path);
    }
}



class ResultArrayForAttachImageToPost implements IResultArray{
    public function get()
    {

        $post_file_name = app::argument()->file_name()->getValue();
        
        $results_from_upload_image = (new ResultArrayForAddImage())->get();
        $reader = new ReaderForValuesStoredInArray($results_from_upload_image);
        if($reader->file_name()){
            $image_file_name = $reader->file_name();
            
            $this->queryForChangePicture($post_file_name, $image_file_name)->result()->to_array();
            return array(
                app::values()->file_name()=>$post_file_name,
                app::values()->picture_file_name()=>$image_file_name
            );
            
            
        }
        else{
            throw new Exception("file could not be uploaded");
        }
    }

    protected function queryForChangePicture($post_file_name, $image_file_name)
    {
        return Db::queries()->update_post_picture($post_file_name, $image_file_name);
    }
}

class ResultArrayForEditPostPicture extends ResultArrayForAttachImageToPost{
    protected function queryForChangePicture($post_file_name, $image_file_name)
    {
        return Db::queries()->edit_post_picture($post_file_name, $image_file_name);
    }
}

class FileNameGenerator{
    public static function generate($id,$title,$keywords){
        $file_name = sprintf("%s-%s-%s-%s",$id,date("D-d-M-Y"),$title,$keywords);
        $file_name = preg_replace("/\W/i","-",$file_name);
        return $file_name;
    }
}
class ResultArrayForAddPost implements IResultArray{
    public function get()
    {
        $title = app::argument()->title()->getValue();
        $content = app::argument()->content()->getValue();
        $category_id = app::argument()->category_id()->getValue();
        $page_id = app::argument()->target_page_id()->getValue();
        $section_id = app::argument()->section_id()->getValue();

        $keywords = app::argument()->keywords()->getValue();
        $id = EntityIdGenerator::newId();

        $file_name = FileNameGenerator::generate($id,$title,$keywords);
        //$file_name = urlencode($file_name);

        $array = Db::queries()->addPost($file_name,$id,$title,$content,$category_id,$page_id,$section_id)->
        result()->
        /*do_not_commit()->*/
        to_array();
        return array(
            app::values()->file_name() => $file_name
        );
    }
}

class ResultArrayForStartNewPost implements IResultArray{
    protected $id, $title,$section_id,$file_name,$keywords,$car_id;

    public function get()
    {        
        $id = EntityIdGenerator::newId();
        $title = app::argument()->title()->getValue();
        $section_id = $this->getInputForSectionId();
        $car_id = $this->getInputForCarId();
        $car_exporter_id = $this->getInputForCarExporterId();
        $keywords = "";
        $file_name = FileNameGenerator::generate($id,$title,$keywords);
               
        
        $array = Db::queries()->
        start_new_post($file_name, $id, $title, "", "", $section_id,$car_id,$car_exporter_id)->
        result()->
        //do_not_commit()->
        to_array();
        
        
        return array(
            app::values()->section_id() => $section_id,
            app::values()->file_name() => $file_name
        );
    }

    #=========
    protected function getInputForSectionId()
    {
        return app::argument()->section_id()->getValue();
    }

    protected function getInputForCarId()
    {
        return 0;
    }

    protected function getInputForCarExporterId()
    {
        return 0;
    }

}
class ResultArrayForStartNewCarReview extends ResultArrayForStartNewPost{
    protected function getInputForSectionId()
    {
        return app::section_ids()->car_reviews();
    }
    protected function getInputForCarId()
    {
        return app::argument()->car_id();
    }
}

class ResultArrayForStartNewCarVideo extends ResultArrayForStartNewPost{
    protected function getInputForSectionId()
    {
        return app::section_ids()->car_videos();
    }
    protected function getInputForCarId()
    {
        return app::argument()->car_id();
    }

}

class ResultArrayForStartNewCarNews extends ResultArrayForStartNewPost{
    protected function getInputForSectionId()
    {
        return app::section_ids()->news();
    }    
}

class ResultArrayForStartNewCarPicture extends ResultArrayForStartNewPost{
    protected function getInputForSectionId()
    {
        return app::section_ids()->car_pictures();
    }
    protected function getInputForCarId()
    {
        return app::argument()->car_id();
    }

}

class ResultArrayForStartNewExporterReview extends ResultArrayForStartNewPost{
    protected function getInputForSectionId()
    {
        return app::section_ids()->exporter_reviews();
    }
    protected function getInputForCarExporterId()
    {
        return app::argument()->car_exporter_id();
    }
}

class ResultArrayForCreateMultiplePosts implements IResultArray{
    public function get()
    {
        //new StatusMessage("getting the values submitted under title[]",__CLASS__,__FUNCTION__);
        $title_array = app::argument()->title_array()->getValue();
        //new StatusMessage("getting the values submitted under title[]",__CLASS__,__FUNCTION__);
        $content_array = app::argument()->content_array()->getValue();
        $extended_post_content_array = app::argument()->extended_post_content_array()->getValue();
        $section_id_array = app::argument()->section_id_array()->getValue();

        $query = Db::queries()->create_multiple_posts($title_array,$content_array,$extended_post_content_array,$section_id_array);

        $array = $query->
        result()->
        //do_not_commit()->
        to_array();
        return array(
            
        );
    }
}

class ResultArrayForEditPostTitle implements IResultArray{
    public function get()
    {        
        $title = app::argument()->title()->getValue();
        $section_id = app::argument()->section_id()->getValue();
        $file_name = app::argument()->file_name()->getValue();
        
        $array = Db::queries()->edit_post_title($file_name,$title,$section_id)->
        result()->
        to_array();

        return array(
            app::values()->file_name() => $file_name
        );
        
    }
}

class ResultArrayForEditPostVideo implements IResultArray{
    public function get()
    {  
        $file_name = app::argument()->file_name()->getValue();
        $youtube_video_id = app::argument()->youtube_video_id()->getValue();

        $array = Db::queries()->edit_post_video($file_name,$youtube_video_id)->
        result()->
        to_array();

        return array(
            app::values()->file_name() => $file_name
        );

    }
}


class ResultArrayForEditCarSelected implements IResultArray{
    public function get()
    {
        $new_car_id = app::argument()->car_id()->getValue();
        $file_name = app::argument()->file_name()->getValue();

        $array = Db::queries()->edit_post_car_id($file_name,$new_car_id)->
        result()->
        to_array();

        return array(
            app::values()->file_name() => $file_name
        );

    }
}

class ResultArrayForAdminEditPostCarExporterSelected implements IResultArray{
    public function get()
    {
        $new_car_id = app::argument()->car_exporter_id()->getValue();
        $file_name = app::argument()->file_name()->getValue();

        $array = Db::queries()->edit_post_car_exporter_id($file_name,$new_car_id)->
        result()->
        to_array();

        return array(
            app::values()->file_name() => $file_name
        );

    }
}

class ResultArrayForEditPostContent implements IResultArray{
    public function get()
    {
        $content = app::argument()->content()->getValue();
        $file_name = app::argument()->file_name()->getValue();

        $array = Db::queries()->edit_post_content($file_name,$content)->
        result()->
        to_array();

        return array(
            app::values()->file_name() => $file_name
        );

    }
}

class ResultArrayForEditExtendedPostContent implements IResultArray{
    public function get()
    {
        $content = app::argument()->extended_post_content()->getValue();
        $file_name = app::argument()->file_name()->getValue();

        $array = Db::queries()->edit_extended_post_content($file_name,$content)->
        result()->
        //do_not_commit()->
        to_array();

        return array(
            app::values()->file_name() => $file_name
        );

    }
}

class ResultArrayForLikeThePost implements IResultArray{
    public function get()
    {

        $file_name = app::argument()->file_name()->getValue();
        $query = Db::queries()->likes()->add($file_name);

        $array = $query->
        result()->
        //do_not_commit()->
        to_array();

        return array(
            app::values()->file_name() => $file_name
        );
    }
}

class ResultArrayForRegisterViewForThePost implements IResultArray{
    public function get()
    {

        $file_name = app::argument()->file_name()->getValue();
        $query = Db::queries()->views()->add($file_name);

        $array = $query->
        result()->
        //do_not_commit()->
        to_array();

        return array(
            app::values()->file_name() => $file_name
        );
    }
}


class ResultArrayForPostComment implements IResultArray{
    public function get()
    {
        $file_name = app::argument()->file_name()->getValue();

        $query = Db::queries()->comments()->post(
            app::argument()->file_name()->getValue(),
            app::argument()->content()->getValue(),
            app::argument()->full_name()->getValue(),
            app::argument()->email_address()->getValue()
        );

        $array = $query->
        result()->
        //do_not_commit()->
        to_array();

        return array(
            app::values()->file_name() => $file_name
        );
    }
}

class ResultArrayForApproveComment implements IResultArray{
    public function get()
    {
        $entity_id = app::argument()->entity_id()->getValue();
        $query = Db::queries()->comments()->approve($entity_id);
        $array = $query->
        result()->
        //do_not_commit()->
        to_array();

        return array(
            app::values()->entity_id() => $entity_id
        );
    }
}
class ResultArrayForMoveCommentToTrash implements IResultArray{
    public function get()
    {
        $entity_id = app::argument()->entity_id()->getValue();
        $query = Db::queries()->comments()->moveToTrash($entity_id);
        $array = $query->
        result()->
        //do_not_commit()->
        to_array();

        return array(
            app::values()->entity_id() => $entity_id
        );
    }
}

class ResultArrayForMoveCommentToSpam implements IResultArray{
    public function get()
    {
        $entity_id = app::argument()->entity_id()->getValue();
        $query = Db::queries()->comments()->moveToSpam($entity_id);
        $array = $query->
        result()->
        //do_not_commit()->
        to_array();

        return array(
            app::values()->entity_id() => $entity_id
        );
    }
}

class ResultArrayForDeletePost implements IResultArray{
    public function get()
    {        
        $file_name = app::argument()->file_name()->getValue();

        $array = Db::queries()->delete_post($file_name)->
        result()->
        //do_not_commit()->
        to_array();

        return array(
            app::values()->file_name() => $file_name
        );
    }
}

class ResultArrayForPublishPost implements IResultArray{
    public function get()
    {
        $file_name = app::argument()->file_name()->getValue();
        $query = Db::queries()->publish_post($file_name);

        $array = $query->
        result()->
        //do_not_commit()->
        to_array();

        return array(
            app::values()->file_name() => $file_name
        );
    }
}

class ResultArrayForUnPublishPost implements IResultArray{
    public function get()
    {

        $file_name = app::argument()->file_name()->getValue();
        $query = Db::queries()->unpublish_post($file_name);

        /*print "<pre>";
        print htmlspecialchars($query);
        print "</pre>";
        exit;*/

        $array = $query->
        result()->
        //do_not_commit()->
        to_array();

        return array(
            app::values()->file_name() => $file_name
        );
    }
}

class ResultArrayForPublishAllPosts implements IResultArray{
    public function get()
    {
        //print "working";exit;
        throw new Exception("Temporarily disabled");

        /*print "<pre>";
        print htmlspecialchars( Db::queries()->publish_all_posts() );
        print "</pre>";
        exit;*/

        $array = Db::queries()->publish_all_posts()->
        result()->
        do_not_commit()->
        to_array();

        return array(            
        );
    }
}



class ResultArrayForGetPost implements IResultArray{
    public function get()
    {
        $file_name = app::argument()->file_name()->getValue();
        return Db::queries()->getPost($file_name)->result()->
        to_array()
            /*child_at_index(BackgroundQueries::instance()->count())*/;
        
    }
}

class ResultArrayForGetPosts implements IResultArray{
    public function get()
    {
        return Db::queries()->getPosts()->result()->
        to_array()
            /*child_at_index(BackgroundQueries::instance()->count())*/;

    }
}

class ResultArrayForGetCars implements IResultArray{
    public function get()
    {
        return Db::queries()->getCars()->result()->
        to_array()
            /*child_at_index(BackgroundQueries::instance()->count())*/;

    }
}

class ResultArrayForGetDataPage implements IResultArray{

    public function get()
    {

       /*print "<pre>";
       print htmlspecialchars(Db::queries()->create_tables() );//print htmlspecialchars( $query );
       print "</pre>";
       exit;*/


        $function_names = app::argument()->function_names();

        $array_of_values = $function_names->getValueAsArray();
        $count = count($array_of_values);

        //create a list where to add the queries
        $query_list = new SQLCommandList();
        //$query_list->add_item_or_list(BackgroundQueries::instance());

        //add the queries
        for($i = 0;$i < $count;$i++){ //todo: put a limit to the number of items that cab be passed and the max length of each and overall max length
            $function = $this->listOfFunctions($array_of_values[$i]);
            if($function){
                $query_list->add($function);
            }
        }

        //run the query list
        $result = Db::results()->multi_query($query_list);
        //$array_of_results = $result->children_starting_at(BackgroundQueries::instance()->count());
        $array_of_results = $result->to_array();
        //print json_encode($array_of_results);exit;
        return $array_of_results;
    }

    private function listOfFunctions($function_name){
        $result = null;
        switch ($function_name){
            case app::values()->get_posts():
                $result = Db::queries()->getPosts();
                break;
            case app::values()->get_post():
                $result = Db::queries()->getPost(app::argument()->file_name());
                break;
            case app::values()->get_draft_post():                
                $result = Db::queries()->getDraftPost(app::argument()->file_name());
                break;
            case app::values()->get_draft_posts():
                $result = Db::queries()->getDraftPosts();
                break;
            case app::values()->get_most_recent_post():
                $result = Db::queries()->getMostRecentPost();
                break;
            case app::values()->get_most_recent_post_per_category():
                $result = Db::queries()->getMostRecentPostPerCategory();
                break;
            case app::values()->get_categories():
                $result = Db::queries()->getCategories();
                break;
            case app::values()->get_sections():
                $result = Db::queries()->getSections();
                break;
            case app::values()->get_pages():
                $result = Db::queries()->getPages();
                break;
            case app::values()->get_posts_for_reviews():
                $result = Db::queries()->getPostsInMainSection();
                break;
            case app::values()->get_posts_for_exporter_reviews():
                $result = Db::queries()->getPostsForExporterReviews();
                break;
            case app::values()->get_posts_for_car_exporters():
                $result = Db::queries()->getPostsForCarExporters();
                break;
            case app::values()->get_posts_for_news():
                $result = Db::queries()->getPostsForNews();
                break;
            case app::values()->get_posts_for_careers():
                $result = Db::queries()->getPostsForCareers();
                break;
            case app::values()->get_posts_for_car_maintenance():
                $result = Db::queries()->getPostsForCarMaintenance();
                break;
            case app::values()->get_cars():
                $result = Db::queries()->getCars();
                break;
            case app::values()->get_posts_for_car_videos():
                $result = Db::queries()->getCarVideos();
                break;
            case app::values()->get_posts_for_car_pictures():
                $result = Db::queries()->getCarPictures();
                break;
            case app::values()->get_file_content():
                $result = Db::queries()->getFileContent();
                break;
            case app::values()->get_extended_post_content():
                $result = Db::queries()->getExtendedPostContent(app::argument()->file_name());
                break;
            case app::values()->get_extended_post_tokens():
                $result = Db::queries()->getExtendedPostTokens(app::argument()->file_name());
                break;

            case app::values()->get_comments():
                $result = Db::queries()->comments()->all();
                break;
            case app::values()->get_comments_for_post():
                $result = Db::queries()->comments()->for_post(app::argument()->file_name()->getValue());
                break;

            case app::values()->admin_get_stats_per_section():
                $result = Db::queries()->get_stats_per_section();
                break;
            case app::values()->admin_get_stats_per_year():
                $result = Db::queries()->get_stats_per_year();
                break;
            case app::values()->admin_get_stats_per_month():
                $result = Db::queries()->get_stats_per_month();
                break;
            case app::values()->admin_get_stats_per_week():
                $result = Db::queries()->get_stats_per_week();
                break;
            case app::values()->admin_get_stats_per_day():
                $result = Db::queries()->get_stats_per_day();
                break;

            case app::values()->get_current_user():
                $result = Db::queries()->get_current_user();
                break;

            case app::values()->most_views():
                $result = Db::queries()->views()->most();
                break;
            case app::values()->most_likes():
                $result = Db::queries()->likes()->most();
                break;
            case app::values()->most_comments():
                $result = Db::queries()->comments()->most();
                break;
            case app::values()->most_recent():
                $result = Db::queries()->posts()->published()->most_recent();
                break;
            case app::values()->oldest_pages():
                $result = Db::queries()->posts()->published()->oldest();
                break;
                
        }
        return $result;
    }
}
