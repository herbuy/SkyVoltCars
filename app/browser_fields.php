<?php

abstract class BrowserField{

    //====================================
    abstract protected function getName();
    
    protected function should_be_array(){
        return false;
    }
    
    public function getFinalName(){
        return $this->should_be_array() ? $this->getName()."[]" : $this->getName();
    }

    /** @return FileInput */
    public function toFileInput($default_value=''){
        return $this->setUpField(new FileInput(), $default_value);
    }

    public function is_array(){
        return is_array($this->value());
    }

    private function determineDefaultValue($default_value,$record_index = 0)
    {
        $value_from_browser = $this->value();
        return $this->is_array() ?
            (strlen(@$value_from_browser[$record_index]) > 0 ?
                @$value_from_browser[$record_index] : $default_value)
             :
            (strlen($value_from_browser) > 0 ? $value_from_browser : $default_value);
    }

    /** @return TextInput */
    public function toTextInput($default_value=null,$record_number = 0){
        $default_value = $this->determineDefaultValue($default_value, $record_number);
        return $this->setUpField(new TextInput(), $default_value);
    }
    public function toTextArea($default_value="",$record_number=0){        
        $default_value = $this->determineDefaultValue($default_value, $record_number);
        $text_area = new SmartTextarea();
        $text_area->set_name($this->getFinalName());
        $text_area->add_child($default_value);
        return $text_area;
    }
    public function toPasswordInput($default_value=''){
        return $this->setUpField(new PasswordInput(), $default_value);
    }
    public function toHiddenInput($default_value=''){
        return $this->setUpField(new HiddenInput(), $default_value);
    }
    public function toRadioInput($default_value=''){
        return $this->setUpField(new RadioInput(), $default_value);
    }
    public function toSearchInput($default_value=''){
        return $this->setUpField(new SearchInput(), $default_value);
    }

    private function setUpField($text_input_box, $default_value)
    {
        $text_input_box->set_name($this->getFinalName());
        $text_input_box->set_value($default_value);
        return $text_input_box;
    }

    public function readValueFromArray($array){
        if(!is_array($array)){
            throw new Exception("expects array as input");
        }
        return $this->read_key($this->getName(),$array);

    }
    protected function read_key($key,$array){
        $value = array_key_exists($key,$array)? $array[$key]:"";
        $value = $this->should_escape_html_special_chars  ? $this->get_escaped_value($value): $value;
        $value = $this->should_decode_as_utf8 ? $this->get_utf8_decoded_value($value): $value;//todo:order matters: encoding should be last!!
        return $value;
    }
    protected function get_escaped_value($value){
        if(is_array($value)){
            $output = array();
            $count = count($value);
            for($i = 0; $i < $count; $i++){
                $output[] = $this->get_escaped_value($value[$i]);
            }
            return $output;
        }
        else{
            return htmlspecialchars($value."");
        }
    }
    protected function get_utf8_decoded_value($value){
        if(is_array($value)){
            $output = array();
            $count = count($value);
            for($i = 0; $i < $count; $i++){
                $output[] = $this->get_utf8_decoded_value($value[$i]);
            }
            return $output;
        }
        else{
            return utf8_decode($value."");
        }
    }
    protected function get_value_after_trim_whitespace($value){
        if(is_array($value)){
            $output = array();
            $count = count($value);
            for($i = 0; $i < $count; $i++){
                $output[] = $this->get_value_after_trim_whitespace($value[$i]);
            }
            return $output;
        }
        else{
            return trim($value."");
        }
    }
    protected function get_value_after_format_as_currency($value){
        if(is_array($value)){
            $output = array();
            $count = count($value);
            for($i = 0; $i < $count; $i++){
                $output[] = $this->get_value_after_format_as_currency($value[$i]);
            }
            return $output;
        }
        else{
            return SmartUtils::format_as_currency($value."");
        }
    }
    protected function get_value_after_format_as_comma_separated_value($value){
        if(is_array($value)){
            $output = array();
            $count = count($value);
            for($i = 0; $i < $count; $i++){
                $output[] = $this->get_value_after_format_as_comma_separated_value($value[$i]);
            }
            return $output;
        }
        else{
            return SmartUtils::createCommaSeparatedNumber($value."");
        }
    }
    public function valueAsCurrency(){
        return $this->get_value_after_format_as_currency($this->value());
    }
    public function valueAsCommaSeparated(){
        return $this->get_value_after_format_as_comma_separated_value($this->value());
    }
    private $should_escape_html_special_chars = true;
    private $should_decode_as_utf8 = false;

    private function writeValueToArray($value,$array){
        if(!is_array($array)){
            throw new Exception("expects array as input");
        }
        $array[$this->getFinalName()] = $value;
    }

    protected function trimWhiteSpaceBeforeReturnValue(){
        return false;
    }
    public function value(){
        $original_value =  $this->readValueFromArray($_REQUEST);
        $final_value = $this->trimWhiteSpaceBeforeReturnValue() ? $this->get_value_after_trim_whitespace($original_value) : $original_value;
        return $final_value;
    }

    public function writeToRequestArray($value){
        $this->writeValueToArray($value,$_REQUEST);
    }

    public function __toString()
    {
        return $this->value()."";
    }
}
class BrowserFieldForContent extends BrowserField{
    protected function getName()
    {
        return app::values()->content();
    }
}
class BrowserFieldForExtendedPostContent extends BrowserField{
    protected function getName()
    {
        return app::values()->extended_post_content();
    }
}

class BrowserFieldForKeywords extends BrowserField{
    protected function getName()
    {
        return app::values()->keywords();
    }
}
class BrowserFieldForCategory extends BrowserField{
    protected function getName()
    {
        return app::values()->category();
    }
}
class BrowserFieldForTitle extends BrowserField{
    protected function getName()
    {
        return app::values()->title();
    }
}

class BrowserFieldForYoutubeVideoId extends BrowserField{
    protected function getName()
    {
        return app::values()->youtube_video_id();
    }
}

class BrowserFieldForSectionId extends BrowserField{
    protected function getName()
    {
        return app::values()->section_id();
    }
}

class BrowserFieldForCarId extends BrowserField{
    protected function getName()
    {
        return app::values()->car_id();
    }
}

class BrowserFieldForCarExporterId extends BrowserField{
    protected function getName()
    {
        return app::values()->car_exporter_id();
    }
}

class BrowserFieldForTargetPageId extends BrowserField{
    protected function getName()
    {
        return app::values()->target_page_id();
    }
}

class BrowserFieldForFileToUpload extends BrowserField{
    protected function getName()
    {
        return app::values()->file_to_upload();
        
    }
}
class BrowserFieldForFileName extends BrowserField{
    protected function getName()
    {
        return app::values()->file_name();

    }
}

class BrowserFieldForEmailAddress extends BrowserField{
    protected function getName()
    {
        return app::values()->email_address();

    }
}
class BrowserFieldForPassword extends BrowserField{
    protected function getName()
    {
        return app::values()->password();

    }
}

class BrowserFieldForPage extends BrowserField{
    protected function getName()
    {
        return app::values()->page();
    }
    public function toPage(){
        $page = null;
        switch ($this->value()){
            case app::values()->login():
                $page = ui::pages()->login();
                break;
            
            case app::values()->admin():
                $page = ui::pages()->admin();
                break;
            case app::values()->admin_add_images():
                $page = ui::pages()->admin_add_images();
                break;
            case app::values()->admin_view_posts():
                $page = ui::pages()->admin_view_posts();
                break;
                
            case app::values()->admin_view_posts_published():
                $page = ui::pages()->admin_view_posts_published();
                break;
            case app::values()->admin_statistics():
                $page = ui::pages()->admin_statistics();
                break;
            case app::values()->admin_car_reviews():
                $page = ui::pages()->admin_car_reviews();
                break;
            case app::values()->admin_exporter_reviews():
                $page = ui::pages()->admin_exporter_reviews();
                break;
            case app::values()->admin_car_videos():
                $page = ui::pages()->admin_car_videos();
                break;
            case app::values()->admin_car_pictures():
                $page = ui::pages()->admin_car_pictures();
                break;

            case app::values()->admin_car_exporters():
                $page = ui::pages()->admin_car_exporters();
                break;
            case app::values()->admin_car_news():
                $page = ui::pages()->admin_news();
                break;
            case app::values()->admin_jobs():
                $page = ui::pages()->admin_jobs();
                break;
            case app::values()->admin_car_maintenance():
                $page = ui::pages()->admin_car_maintenance();
                break;
            case app::values()->admin_cars():
                $page = ui::pages()->admin_cars();
                break;
            case app::values()->admin_edit_post():
                $page = ui::pages()->admin_edit_post();
                break;
            case app::values()->admin_edit_car_review():
                $page = ui::pages()->admin_edit_car_review();
                break;
            case app::values()->admin_edit_post_title():
                $page = ui::pages()->admin_edit_post_title();
                break;
            case app::values()->admin_edit_post_content():
                $page = ui::pages()->admin_edit_post_content();
                break;
            case app::values()->admin_edit_extended_post_content():
                $page = ui::pages()->admin_edit_extended_post_content();
                break;
            case app::values()->admin_edit_post_picture():
                $page = ui::pages()->admin_edit_post_picture();
                break;

            case app::values()->admin_edit_post_car_selected():
                $page = ui::pages()->admin_edit_post_car_description();
                break;

            case app::values()->admin_edit_post_car_exporter_selected():
                $page = ui::pages()->admin_edit_post_car_exporter_selected();
                break;                
            
            case app::values()->get_post():
                $page = ui::pages()->get_post();
                break;
            case app::values()->attach_image_to_post():
                $page = ui::pages()->attach_image_to_post();
                break;

            case app::values()->news():
                $page = ui::pages()->news();
                break;
            case app::values()->reviews():
                $page = ui::pages()->reviews();
                break;
            case app::values()->videos():
                $page = ui::pages()->videos();
                break;
            case app::values()->gallery():
                $page = ui::pages()->gallery();
                break;
            case app::values()->tips():
                $page = ui::pages()->tips();
                break;
            case app::values()->jobs():
                $page = ui::pages()->jobs();
                break;
            
            case app::values()->about():
                $page = ui::pages()->about_us();
                break;            
            case app::values()->contact_us():
                $page = ui::pages()->contact_us();
                break;

            default:
                $page = ui::pages()->home();
                break;
        }
        return $page;

    }
}

class BrowserFieldForCmd extends BrowserField{
    protected function getName()
    {
        return app::values()->cmd();
    }
    protected function trimWhiteSpaceBeforeReturnValue(){
        return true;
    }
    public function toCmd(){
        $cmd = null;
        $value = $this->value();
        
        switch ($value){
            case "":
                $cmd = app::cmds()->notifyEmptyCmd();
                break;

            case app::values()->create_account():
                $cmd = app::cmds()->createAccount();
                break;
            case app::values()->login():
                $cmd = app::cmds()->login();
                break;
            case app::values()->logout():
                $cmd = app::cmds()->logout();
                break;
            
            case app::values()->start_new_post():
                $cmd = app::cmds()->startNewPost();
                break;
            case app::values()->start_new_car_review():
                $cmd = app::cmds()->start_new_car_review();
                break;
            case app::values()->start_new_car_video():
                $cmd = app::cmds()->start_new_car_video();
                break;
            case app::values()->start_new_car_news():
                $cmd = app::cmds()->start_new_car_news();
                break;

            case app::values()->start_new_car_picture():
                $cmd = app::cmds()->start_new_car_picture();
                break;
            case app::values()->start_new_exporter_review():
                $cmd = app::cmds()->start_new_exporter_review();
                break;
            case app::values()->create_multiple_posts():
                $cmd = app::cmds()->createMultiplePosts();
                break;
            case app::values()->delete_post():
                $cmd = app::cmds()->deletePost();
                break;
            case app::values()->publish_post():
                $cmd = app::cmds()->publishPost();
                break;
            case app::values()->unpublish_post():
                $cmd = app::cmds()->unpublish_post();
                break;
            case app::values()->publish_all_posts():
                $cmd = app::cmds()->publishAllPosts();
                break;
            case app::values()->admin_edit_post_title():
                $cmd = app::cmds()->edit_post_title();
                break;
            case app::values()->admin_edit_post_video():
                $cmd = app::cmds()->edit_post_video();
                break;
            case app::values()->admin_edit_post_content():
                $cmd = app::cmds()->edit_post_content();
                break;
            case app::values()->admin_edit_post_picture():
                $cmd = app::cmds()->edit_post_picture();
                break;
            case app::values()->admin_edit_post_car_selected():
                $cmd = app::cmds()->edit_car_selected();
                break;
            case app::values()->admin_edit_post_car_exporter_selected():
                $cmd = app::cmds()->admin_edit_car_exporter_selected();
                break;
            case app::values()->admin_edit_extended_post_content():
                $cmd = app::cmds()->edit_extended_post_content();
                break;
            case app::values()->add_post():
                $cmd = app::cmds()->addPost();
                break;
            case app::values()->add_image():
                $cmd = app::cmds()->addImage();
                break;
            case app::values()->attach_image_to_post():
                $cmd = app::cmds()->attachImageToPost();
                break;
            case app::values()->get_post():
                $cmd = app::cmds()->GetPost();
                break;
            case app::values()->get_cars():
                $cmd = app::cmds()->GetCars();
                break;
            case app::values()->get_posts():
                $cmd = app::cmds()->GetPosts();
                break;
            case app::values()->get_data_page():
                $cmd = app::cmds()->GetDataPage();
                break;

            case app::values()->like_the_post():
                $cmd = app::cmds()->like_the_post();
                break;
            case app::values()->register_the_view():
                $cmd = app::cmds()->register_the_view();
                break;

            case app::values()->post_comment():
                $cmd = app::cmds()->post_comment();
                break;
            case app::values()->approve_comment():
                $cmd = app::cmds()->approve_comment();
                break;
            case app::values()->move_comment_to_trash():
                $cmd = app::cmds()->move_comment_to_trash();
                break;
            case app::values()->move_comment_to_spam():
                $cmd = app::cmds()->move_comment_to_spam();
                break;
            
            default:
                $cmd = app::cmds()->doNothing();
                break;
        }
        return $cmd;
    }
}