<?php
class APIResultCache{
    private static $cache_as_array = array();
    public static function add_result($key_to_uniquely_identify_result, $value){
        self::$cache_as_array[$key_to_uniquely_identify_result] = $value;
    }
    public static function get_result($key){
        return self::contains($key) ? self::$cache_as_array[$key] : null;
    }
    public static function contains($key)
    {
        return array_key_exists($key, self::$cache_as_array);
    }

    public static function add_result_if($condition_to_test, $value_to_add,$key_to_uniquely_identify_result)
    {
        if($condition_to_test){
            self::add_result($key_to_uniquely_identify_result,$value_to_add);
        }
    }
}

abstract class CmdBaseClass{

    private static $last_responses = array();
    /** @param \ReaderForValuesStoredInArray $reader */
    protected function setLastResponse($reader){
        self::$last_responses[$this->procedure_name()] = $reader;
    }
    /** @return ReaderForValuesStoredInArray */
    public function readerForlastResponse(){
        return
            array_key_exists($this->procedure_name(),self::$last_responses) ?
            self::$last_responses[$this->procedure_name()] : app::reader(array());
    }

    private static $last_errors = array();
    protected function setLastError($error){        
        self::$last_errors[$this->procedure_name()] = $error;
    }
    public function lastError(){
        return
            array_key_exists($this->procedure_name(),self::$last_errors) ?
                self::$last_errors[$this->procedure_name()] : "";
    }
    public function lastErrorNotEmpty(){
        return strlen(trim($this->lastError())) > 0;
    }

    ///==================

    abstract protected function procedure_name();
    abstract protected function unpackError($error);
    /** @param \ReaderForValuesStoredInArray $reader_for_content */
    protected function unpackContent($reader_for_content){
        return $reader_for_content;
    }
    abstract public function result_array();

    public function unpackThisContent($items){
        return $this->unpackContent($items);
    }

    protected function setRemoteProcedureArgument($key,$value){
        $_REQUEST[$key] = $value;
    }

    //can be overidden
    protected function cacheable(){
        return false;
    }

    protected function is_duplicate_request(){
        return false;
    }
    /** @return ReaderForValuesStoredInArray */
    public function execute()
    {
        //test duplicate requests - designed for requests that insert or update data
        if($this->is_duplicate_request()){
            return "";
        }

        //fetch from cache if possible
        $data = null;
        if($this->cacheable() && APIResultCache::contains(__CLASS__)){
            $data = APIResultCache::get_result(__CLASS__);
        }
        else{
            //fetch the data from the api
            $this->packRemoteProcedureArguments();
            $data = $this->fetchData();
            APIResultCache::add_result_if($this->cacheable(),$data,__CLASS__);
        }
        $unpacked_data = $this->unpackData($data);
        return $unpacked_data;
        //===========

        //if not cached, fetch and cache if necessary
        /*$this->packRemoteProcedureArguments();
        $data = $this->fetchData();
        $unpacked_data = $this->unpackData($data);
        return $unpacked_data;*/
    }

    private function fetchData()
    {
        $_REQUEST[$this->fieldNameForCmd()] = $this->procedure_name();
        ob_start();
        include($this->api_file_name());
        $data = ob_get_contents();
        ob_end_clean();
        $array_of_data = json_decode($data,true);
        return $array_of_data;
    }

    private function packRemoteProcedureArguments()
    {

        $_REQUEST[$this->fieldNameForCmd()] = $this->procedure_name();
        $this->packMoreRemoteProcedureArguments();
    }
    protected function packMoreRemoteProcedureArguments(){

    }

    private $was_successful = false;
    public function wasSuccessful(){
        return $this->was_successful;
    }

    private function unpackData($array_of_data)
    {
        $reader = app::reader($array_of_data);
        //$error_field_name = 
        $error = $reader->at(app::values()->error());
        $content = $reader->at(app::values()->content());
        if ($error) {
            return $this->unpackError($error);
        } else {
            $this->was_successful = true;
            return $this->unpackContent(app::reader($content));
        }
    }

    protected function api_file_name()
    {
        return "api.php";
    }

    protected function fieldNameForCmd()
    {
        return app::values()->cmd();
    }

    protected function fieldNameForError()
    {
        return app::values()->error();
    }

    protected function fieldNameForContent()
    {
        return app::values()->content();
    }
}

class CmdForDoNothing extends CmdBaseClass{
    public function execute()
    {
        return app::reader(array());
    }
    protected function unpackError($error)
    {
        return "";
    }
    protected function procedure_name()
    {
        return "";
    }
    public function result_array()
    {
        return app::result_array()->do_nothing();
    }


}
abstract class CmdBaseClass2 extends CmdBaseClass{
    protected function unpackError($error)
    {
        $this->setLastError($error);
        return $error;
    }
    protected function unpackContent($reader_for_content)
    {
        $this->setLastResponse($reader_for_content);
        return parent::unpackContent($reader_for_content);
    }
}



class CmdForNotifyEmptyCmd extends CmdBaseClass{
    protected function unpackError($error)
    {
        return "";
    }
    protected function procedure_name()
    {
        return "";
    }
    protected function packMoreRemoteProcedureArguments(){

    }
    public function result_array()
    {
        return app::result_array()->notify_empty_cmd();
    }

}


class CmdForAddPost extends CmdBaseClass2{

    protected function procedure_name()
    {
        return app::values()->add_post();
    }
    protected function packMoreRemoteProcedureArguments(){

    }
    public function result_array()
    {
        return app::result_array()->add_post();
    }
    protected function unpackContent($reader_for_content)
    {
        ui::urls()->view_post($reader_for_content->file_name())->addToSitemap();
        return parent::unpackContent($reader_for_content);        
    }

}

class CmdForCreateAccount extends CmdBaseClass2{

    protected function procedure_name()
    {
        return app::values()->create_account();
    }
    protected function packMoreRemoteProcedureArguments(){

    }
    public function result_array()
    {
        return app::result_array()->create_account();
    }
    
}

class CmdForLogin extends CmdBaseClass2{

    protected function procedure_name()
    {
        return app::values()->login();
    }
    protected function packMoreRemoteProcedureArguments(){

    }
    public function result_array()
    {        
        return app::result_array()->login();
    }
    protected function unpackContent($reader_for_content)
    {
        ui::urls()->adminPage()->gotoAddressIfSubmittedForm();
        return parent::unpackContent($reader_for_content);
    }

}

class CmdForLogout extends CmdBaseClass2{

    protected function procedure_name()
    {
        return app::values()->logout();
    }
    protected function packMoreRemoteProcedureArguments(){

    }
    public function result_array()
    {
        return app::result_array()->logout();
    }
    protected function unpackContent($reader_for_content)
    {
        ui::urls()->home()->gotoAddressIfSubmittedForm();
        return parent::unpackContent($reader_for_content);
    }

}

class CmdForStartNewPost extends CmdBaseClass2{

    protected function procedure_name()
    {
        return app::values()->start_new_post();
    }
    protected function packMoreRemoteProcedureArguments(){

    }
    public function result_array()
    {
        return app::result_array()->start_new_post();
    }

    protected function unpackContent($reader_for_content)
    {
        $result = parent::unpackContent($reader_for_content);
        //switch to UI based on section posted
        $section_id_posted = $reader_for_content->section_id();

        switch ($section_id_posted){
            case app::section_ids()->car_reviews():
                ui::urls()->adminEditCarReview($reader_for_content->file_name())->gotoAddressIfSubmittedForm();
                break;
            case app::section_ids()->exporter_reviews():
                ui::urls()->adminEditPost($reader_for_content->file_name())->gotoAddressIfSubmittedForm();
                break;
            default:
                ui::urls()->adminEditPost($reader_for_content->file_name())->gotoAddressIfSubmittedForm();
                break;
        }

        return $result;
    }
}
class CmdForStartNewCarReview extends CmdBaseClass2{

    protected function procedure_name()
    {
        return app::values()->start_new_car_review();
    }
    protected function packMoreRemoteProcedureArguments(){

    }
    public function result_array()
    {
        return app::result_array()->start_new_car_review();
    }

    protected function unpackContent($reader_for_content)
    {
        $result = parent::unpackContent($reader_for_content);        
        ui::urls()->adminEditPost($reader_for_content->file_name())->gotoAddressIfSubmittedForm();
        return $result;
    }
}

class CmdForStartNewCarVideo extends CmdBaseClass2{

    protected function procedure_name()
    {
        return app::values()->start_new_car_video();
    }
    protected function packMoreRemoteProcedureArguments(){

    }
    public function result_array()
    {
        return app::result_array()->start_new_car_video();
    }

    protected function unpackContent($reader_for_content)
    {
        $result = parent::unpackContent($reader_for_content);
        ui::urls()->adminEditPost($reader_for_content->file_name())->gotoAddressIfSubmittedForm();
        return $result;
    }
}


class CmdForStartNewCarNews extends CmdBaseClass2{

    protected function procedure_name()
    {
        return app::values()->start_new_car_news();
    }
    protected function packMoreRemoteProcedureArguments(){

    }
    public function result_array()
    {
        return app::result_array()->start_new_car_news();
    }

    protected function unpackContent($reader_for_content)
    {
        $result = parent::unpackContent($reader_for_content);
        ui::urls()->adminEditPost($reader_for_content->file_name())->gotoAddressIfSubmittedForm();
        return $result;
    }
}

class CmdForStartNewCarPicture extends CmdBaseClass2{

    protected function procedure_name()
    {
        return app::values()->start_new_car_picture();
    }
    protected function packMoreRemoteProcedureArguments(){

    }
    public function result_array()
    {
        return app::result_array()->start_new_car_picture();
    }

    protected function unpackContent($reader_for_content)
    {
        $result = parent::unpackContent($reader_for_content);
        ui::urls()->adminEditPost($reader_for_content->file_name())->gotoAddressIfSubmittedForm();
        return $result;
    }
}

class CmdForStartNewExporterReview extends CmdForStartNewCarReview{

    protected function procedure_name()
    {
        return app::values()->start_new_exporter_review();
    }
   
    public function result_array()
    {
        return app::result_array()->start_new_exporter_review();
    }
}

class CmdForCreateMultiplePosts extends CmdBaseClass2{

    protected function procedure_name()
    {
        return app::values()->create_multiple_posts();
    }
    protected function packMoreRemoteProcedureArguments(){

    }
    public function result_array()
    {
        return app::result_array()->create_multiple_posts();
    }

    protected function unpackContent($reader_for_content)
    {        
        $result = parent::unpackContent($reader_for_content);
        ui::urls()->adminPage()->gotoAddressIfSubmittedForm();
        return $result;
    }
}

class CmdForDeletePost extends CmdBaseClass2{

    protected function procedure_name()
    {
        return app::values()->delete_post();
    }
    protected function packMoreRemoteProcedureArguments(){

    }
    public function result_array()
    {
        return app::result_array()->delete_post();
    }
    protected function unpackContent($reader_for_content)
    {
        $result = parent::unpackContent($reader_for_content);
        ui::urls()->adminViewPosts()->gotoAddressIfSubmittedForm();
        return $result;
    }
}

class CmdForPublishPost extends CmdBaseClass2{

    protected function procedure_name()
    {
        return app::values()->publish_post();
    }
    protected function packMoreRemoteProcedureArguments(){

    }
    public function result_array()
    {
        return app::result_array()->publish_post();
    }

    protected function unpackContent($reader_for_content)
    {
        app::sitemap()->addUrlFromString(
            ui::urls()->view_post($reader_for_content->file_name()).""
        );
        
        $result = parent::unpackContent($reader_for_content);
        ui::urls()->adminViewPostsPublished()->gotoAddressIfSubmittedForm();
        return $result;
    }
}

class CmdForUnPublishPost extends CmdBaseClass2{

    protected function procedure_name()
    {
        return app::values()->unpublish_post();
    }
    protected function packMoreRemoteProcedureArguments(){

    }
    public function result_array()
    {
        return app::result_array()->unpublish_post();
    }

    protected function unpackContent($reader_for_content)
    {
        app::sitemap()->removeUrlWithString(
            ui::urls()->view_post($reader_for_content->file_name()).""
        );

        $result = parent::unpackContent($reader_for_content);
        ui::urls()->adminViewPosts()->gotoAddressIfSubmittedForm();
        return $result;
    }
}

class CmdForPublishAllPosts extends CmdBaseClass2{

    protected function procedure_name()
    {
        return app::values()->publish_all_posts();
    }
    protected function packMoreRemoteProcedureArguments(){

    }
    public function result_array()
    {
        return app::result_array()->publish_all_posts();
    }

    protected function unpackContent($reader_for_content)
    {
        $result = parent::unpackContent($reader_for_content);
        ui::urls()->adminViewPostsPublished()->gotoAddressIfSubmittedForm();
        return $result;
    }
}

class CmdForEditPostTitle extends CmdBaseClass2{

    protected function procedure_name()
    {
        return app::values()->admin_edit_post_title();
    }
    protected function packMoreRemoteProcedureArguments(){

    }
    public function result_array()
    {
        return app::result_array()->edit_post_title();
    }

    protected function unpackContent($reader_for_content)
    {
        $result = parent::unpackContent($reader_for_content);
        ui::urls()->adminEditPost($reader_for_content->file_name())->gotoAddressIfSubmittedForm();
        return $result;
    }
}

class CmdForEditCarSelected extends CmdBaseClass2{

    protected function procedure_name()
    {
        return app::values()->admin_edit_post_car_selected();
    }
    protected function packMoreRemoteProcedureArguments(){

    }
    public function result_array()
    {
        return app::result_array()->edit_car_selected();
    }

    protected function unpackContent($reader_for_content)
    {
        $result = parent::unpackContent($reader_for_content);
        ui::urls()->adminEditPost($reader_for_content->file_name())->gotoAddressIfSubmittedForm();
        return $result;
    }
}

class CmdForAdminEditPostCarExporterSelected extends CmdBaseClass2{

    protected function procedure_name()
    {
        return app::values()->admin_edit_post_car_exporter_selected();
    }
    protected function packMoreRemoteProcedureArguments(){

    }
    public function result_array()
    {
        return app::result_array()->admin_edit_post_car_exporter_selected();
    }

    protected function unpackContent($reader_for_content)
    {
        $result = parent::unpackContent($reader_for_content);
        ui::urls()->adminEditPost($reader_for_content->file_name())->gotoAddressIfSubmittedForm();
        return $result;
    }
}

class CmdForEditPostContent extends CmdBaseClass2{

    protected function procedure_name()
    {
        return app::values()->admin_edit_post_content();
    }
    protected function packMoreRemoteProcedureArguments(){

    }
    public function result_array()
    {
        return app::result_array()->edit_post_content();
    }

    protected function unpackContent($reader_for_content)
    {
        $result = parent::unpackContent($reader_for_content);
        ui::urls()->adminEditPost($reader_for_content->file_name())->gotoAddressIfSubmittedForm();
        return $result;
    }
}

class CmdForEditExtendedPostContent extends CmdBaseClass2{

    protected function procedure_name()
    {
        return app::values()->admin_edit_extended_post_content();
    }
    protected function packMoreRemoteProcedureArguments(){

    }
    public function result_array()
    {
        return app::result_array()->edit_extended_post_content();
    }

    protected function unpackContent($reader_for_content)
    {
        $result = parent::unpackContent($reader_for_content);
        ui::urls()->adminEditPost($reader_for_content->file_name())->gotoAddressIfSubmittedForm();
        return $result;
    }
}

class CmdForEditPostPicture extends CmdBaseClass2{

    protected function procedure_name()
    {
        return app::values()->admin_edit_post_picture();
    }
    protected function packMoreRemoteProcedureArguments(){

    }
    public function result_array()
    {
        return app::result_array()->edit_post_picture();
    }

    protected function unpackContent($reader_for_content)
    {
        $result = parent::unpackContent($reader_for_content);
        ui::urls()->adminEditPost($reader_for_content->file_name())->gotoAddressIfSubmittedForm();
        return $result;
    }
}

class CmdForEditPostVideo extends CmdBaseClass2{

    protected function procedure_name()
    {
        return app::values()->admin_edit_post_video();
    }
    protected function packMoreRemoteProcedureArguments(){

    }
    public function result_array()
    {
        return app::result_array()->edit_post_video();
    }

    protected function unpackContent($reader_for_content)
    {
        $result = parent::unpackContent($reader_for_content);
        ui::urls()->adminEditPost($reader_for_content->file_name())->gotoAddressIfSubmittedForm();
        return $result;
    }
}
class CmdForAddImage extends CmdBaseClass2{

    protected function procedure_name()
    {
        return app::values()->add_image();
    }
    protected function packMoreRemoteProcedureArguments(){

    }
    public function result_array()
    {
        return app::result_array()->add_image();
    }
    protected function unpackContent($reader_for_content)
    {
        //ui::urls()->view_image($reader_for_content->file_name())->addToSitemap();
        return parent::unpackContent($reader_for_content);
    }

}


class CmdForAttachImageToPost extends CmdBaseClass2{

    protected function procedure_name()
    {
        return app::values()->attach_image_to_post();
    }
    protected function packMoreRemoteProcedureArguments(){

    }
    public function result_array()
    {
        return app::result_array()->attach_image_to_post();
    }  

}
class CmdForGetPost extends CmdBaseClass2{

    protected function procedure_name()
    {
        return app::values()->get_post();
    }
    protected function packMoreRemoteProcedureArguments(){

    }
    public function result_array()
    {
        return app::result_array()->get_post();
    }

}
class CmdForGetPosts extends CmdBaseClass2{

    protected function procedure_name()
    {
        return app::values()->get_posts();
    }
    protected function packMoreRemoteProcedureArguments(){

    }
    public function result_array()
    {
        return app::result_array()->get_posts();
    }

}

class CmdForGetCars extends CmdBaseClass2{

    protected function procedure_name()
    {
        return app::values()->get_cars();
    }
    protected function packMoreRemoteProcedureArguments(){

    }
    public function result_array()
    {
        return app::result_array()->get_cars();
    }

}
class CmdForGetDataPage extends CmdBaseClass2{

    protected function procedure_name()
    {
        return app::values()->get_data_page();
    }
    protected function packMoreRemoteProcedureArguments(){

    }
    public function result_array()
    {
        return app::result_array()->get_data_page();
    }

}


class CmdForLikeThePost extends CmdBaseClass2{

    protected function procedure_name()
    {
        return app::values()->like_the_post();
    }
    protected function packMoreRemoteProcedureArguments(){

    }
    public function result_array()
    {
        return app::result_array()->like_the_post();
    }
}
class CmdForRegisterTheView extends CmdBaseClass2{

    protected function procedure_name()
    {
        return app::values()->register_the_view();
    }
    protected function packMoreRemoteProcedureArguments(){

    }
    public function result_array()
    {        
        return app::result_array()->register_the_view();
    }
}


class CmdForPostComment extends CmdBaseClass2{

    protected function procedure_name()
    {
        return app::values()->post_comment();
    }
    protected function packMoreRemoteProcedureArguments(){

    }
    public function result_array()
    {
        return app::result_array()->post_comment();
    }
}


class CmdForApproveComment extends CmdBaseClass2{

    protected function procedure_name()
    {
        return app::values()->approve_comment();
    }
    protected function packMoreRemoteProcedureArguments(){

    }
    public function result_array()
    {
        return app::result_array()->approve_comment();
    }
}


class CmdForMoveCommentToTrash extends CmdBaseClass2{

    protected function procedure_name()
    {
        return app::values()->move_comment_to_trash();
    }
    protected function packMoreRemoteProcedureArguments(){

    }
    public function result_array()
    {
        return app::result_array()->move_comment_to_trash();
    }
}

class CmdForMoveCommentToSpam extends CmdBaseClass2{

    protected function procedure_name()
    {
        return app::values()->move_comment_to_spam();
    }
    protected function packMoreRemoteProcedureArguments(){

    }
    public function result_array()
    {
        return app::result_array()->move_comment_to_spam();
    }
}

