<?php

abstract class BaseClassForFormFeedback{
    /** @return CmdBaseClass2 $cmd */
    abstract protected function getCmd();
    abstract protected function initialFeeback();
    abstract protected function successFeeback();
    abstract protected function textForNextActions();

    public function __toString()
    {
        $layout = (new SmartDiv())->add_child( $this->initialFeeback() );
        if($this->getCmd()->lastErrorNotEmpty()){
            $layout = ui::error_html()->add_child($this->getCmd()->lastError());
        }
        else if($this->getCmd()->readerForlastResponse()->count() > 0){
            $layout = new SmartDiv();
            $layout->add_child($this->successFeeback(). ".&nbsp;");
            $layout->add_child($this->textForNextActions());
            $layout->background_color("#ffc")->border("1px solid #ee6")->padding("8px 16px");
        }
        
        $layout->set_id($this->getHtmlFragmentId());

        return $layout."";
    }

    public function urlToFeedback(){
        return UrlOfCurrentRequest::get()->seeksToElementId($this->getHtmlFragmentId());
    }

    private function getHtmlFragmentId()
    {
        return join("",array(
            "_", md5( __CLASS__)
        ));
    }
}
class FormFeedbackForAddImage extends BaseClassForFormFeedback{
    protected function getCmd()
    {
        return app::cmds()->addImage();
    }
    protected function initialFeeback()
    {
        return "Add Image";
    }
    protected function successFeeback()
    {
        return "image posted successfully";
    }
    protected function textForNextActions()
    {
        return ui::links()->view_picture($this->getCmd()->readerForlastResponse()->file_name())->add_child("View image");
    }
}


class FormFeedbackForAttachImageToPost extends BaseClassForFormFeedback{
    protected function getCmd()
    {
        return app::cmds()->attachImageToPost();
    }
    protected function initialFeeback()
    {
        return "Attach an image to your post";
    }
    protected function successFeeback()
    {
        return "image attached successfully";
    }
    protected function textForNextActions()
    {
        return ui::links()->view_post($this->getCmd()->readerForlastResponse()->file_name())->add_child("View Post");
    }
}

class FormFeedbackForAddPost extends BaseClassForFormFeedback{
    

    protected function getCmd()
    {
        return app::cmds()->addPost();
    }
    protected function initialFeeback()
    {
        return ui::html()->heading2()->add_child(ui::text_with_contrast_colors("Write","your post"));
    }
    protected function successFeeback()
    {
        return "Post submitted successfully";
    }
    protected function textForNextActions()
    {
        return sprintf("%s",
            /*ui::links()->view_post($this->getCmd()->lastResponse()->file_name())->add_child("View Post"),*/
            ui::links()->attach_picture_to_post($this->getCmd()->readerForlastResponse()->file_name())->add_child("Attach a picture")
        );
    }
}



class FormFeedbackForCreateMultiplePosts extends BaseClassForFormFeedback{
    
    protected function getCmd()
    {
        return app::cmds()->createMultiplePosts();
    }
    protected function initialFeeback()
    {
        return ui::html()->heading2()->add_child(ui::text_with_contrast_colors("Create multiple posts","at once"));
    }
    protected function successFeeback()
    {
        return "Your posts were created successfully";
    }
    protected function textForNextActions()
    {
        return sprintf("%s",            
            ui::links()->adminPage()->add_child("See posts here")
        );
    }
}

class FormFeedbackForLogin extends BaseClassForFormFeedback{

    public function __construct()
    {
    }

    protected function getCmd()
    {
        return app::cmds()->login();
    }
    protected function initialFeeback()
    {
        return ui::html()->heading2()->add_child(ui::text_with_contrast_colors("Log","In"));
    }
    protected function successFeeback()
    {
        return "You are successfully logged in";
    }
    protected function textForNextActions()
    {
        return sprintf("%s",
            ui::links()->adminPage()->add_child("Go to control panel")
        );
    }     
}

class FormFeedbackForLogout extends BaseClassForFormFeedback{

    public function __construct()
    {
    }

    protected function getCmd()
    {
        return app::cmds()->logout();
    }
    protected function initialFeeback()
    {
        return "";
    }
    protected function successFeeback()
    {
        return "You are successfully logged out";
    }
    protected function textForNextActions()
    {
        return sprintf("%s",
            ui::links()->home()->add_child("Go home")
        );
    }
}

class FormFeedbackForStartNewPost extends BaseClassForFormFeedback{

    public function __construct()
    {
    }

    protected function getCmd()
    {
        return app::cmds()->start_new_post();
    }
    protected function initialFeeback()
    {
        return ui::html()->heading2()->add_child(ui::text_with_contrast_colors("Other","post"));
    }
    protected function successFeeback()
    {
        return "New post created";
    }
    protected function textForNextActions()
    {
        return sprintf("%s",            
            ui::links()->adminEditPost($this->getCmd()->readerForlastResponse()->file_name())->add_child("Start Editing Post")
        );
    }
}

class FormFeedbackForStartNewCarExporter extends BaseClassForFormFeedback{

    public function __construct()
    {
    }

    protected function getCmd()
    {
        return app::cmds()->start_new_post();
    }
    protected function initialFeeback()
    {
        return ui::html()->heading2()->add_child(ui::text_with_contrast_colors("REGISTER NEW","CAR EXPORTER"));
    }

    protected function successFeeback()
    {
        return "Car exporter added to database";
    }
    protected function textForNextActions()
    {
        return sprintf("%s",
            ui::links()->adminEditPost($this->getCmd()->readerForlastResponse()->file_name())->add_child("Start Editing exporter information")
        );
    }
}

class FormFeedbackForStartNewCarNews extends BaseClassForFormFeedback{

    public function __construct()
    {
    }

    protected function getCmd()
    {
        return app::cmds()->start_new_post();
    }
    protected function initialFeeback()
    {
        return ui::html()->heading2()->add_child(ui::text_with_contrast_colors("SHARE","CAR NEWS"));
    }
    protected function successFeeback()
    {
        return "Car news uploaded successfully";
    }
    protected function textForNextActions()
    {
        return sprintf("%s",
            ui::links()->adminEditPost($this->getCmd()->readerForlastResponse()->file_name())->add_child("Start Editing the news")
        );
    }
}
class FormFeedbackForStartNewJobOpportunity extends BaseClassForFormFeedback{

    public function __construct()
    {
    }

    protected function getCmd()
    {
        return app::cmds()->start_new_post();
    }
    protected function initialFeeback()
    {
        return ui::html()->heading2()->add_child(ui::text_with_contrast_colors("SHARE","JOB OPPORTUNITY"));
    }
    protected function successFeeback()
    {
        return "Job opportunity uploaded successfully";
    }
    protected function textForNextActions()
    {
        return sprintf("%s",
            ui::links()->adminEditPost($this->getCmd()->readerForlastResponse()->file_name())->add_child("Provide more details")
        );
    }
}
class FormFeedbackForStartNewCarMaintenance extends BaseClassForFormFeedback{

    public function __construct()
    {
    }

    protected function getCmd()
    {
        return app::cmds()->start_new_post();
    }
    protected function initialFeeback()
    {
        return ui::html()->heading2()->add_child(ui::text_with_contrast_colors("SHARE","CAR MAINTENANCE INFORMATION"));
    }
    protected function successFeeback()
    {
        return "Car maintenance information uploaded successfully";
    }
    protected function textForNextActions()
    {
        return sprintf("%s",
            ui::links()->adminEditPost($this->getCmd()->readerForlastResponse()->file_name())->add_child("Share the details here")
        );
    }
}
class FormFeedbackForStartNewCar extends BaseClassForFormFeedback{

    public function __construct()
    {
    }

    protected function getCmd()
    {
        return app::cmds()->start_new_post();
    }
    protected function initialFeeback()
    {
        return ui::html()->heading2()->add_child(ui::text_with_contrast_colors("REGISTER A","CAR"));
    }
    protected function successFeeback()
    {
        return "Car added to database successfully";
    }
    protected function textForNextActions()
    {
        return sprintf("%s",
            ui::links()->adminEditPost($this->getCmd()->readerForlastResponse()->file_name())->add_child("Provide more information about it")
        );
    }
}
#================
class FormFeedbackForStartNewCarReview extends BaseClassForFormFeedback{

    public function __construct()
    {
    }

    protected function getCmd()
    {
        return app::cmds()->start_new_car_review();
    }
    protected function initialFeeback()
    {
        return ui::html()->heading2()->add_child(ui::text_with_contrast_colors("ADD","CAR REVIEW"));
    }

    protected function successFeeback()
    {
        return "New car review added successfully";
    }
    protected function textForNextActions()
    {
        return sprintf("%s",
            ui::links()->adminEditPost($this->getCmd()->readerForlastResponse()->file_name())->add_child("Start Editing car review")
        );
    }
}

class FormFeedbackForStartNewCarVideo extends BaseClassForFormFeedback{

    public function __construct()
    {
    }

    protected function getCmd()
    {
        return app::cmds()->start_new_car_video();
    }
    protected function initialFeeback()
    {
        return ui::html()->heading2()->add_child(ui::text_with_contrast_colors("ADD","CAR VIDEO"));
    }

    protected function successFeeback()
    {
        return "Car video added successfully";
    }
    protected function textForNextActions()
    {
        return sprintf("%s",
            ui::links()->adminEditPost($this->getCmd()->readerForlastResponse()->file_name())->add_child("Start Editing car video")
        );
    }
}

class FormFeedbackForStartNewCarPicture extends BaseClassForFormFeedback{

    public function __construct()
    {
    }

    protected function getCmd()
    {
        return app::cmds()->start_new_car_picture();
    }
    protected function initialFeeback()
    {
        return ui::html()->heading2()->add_child(ui::text_with_contrast_colors("ADD","GALLERY PICTURE"));
    }

    protected function successFeeback()
    {
        return "Gallery picture added successfully";
    }
    protected function textForNextActions()
    {
        return sprintf("%s",
            ui::links()->adminEditPost($this->getCmd()->readerForlastResponse()->file_name())->add_child("Start Editing gallery picture")
        );
    }
}

class FormFeedbackForStartNewExporterReview extends BaseClassForFormFeedback{

    public function __construct()
    {
    }

    protected function getCmd()
    {
        return app::cmds()->start_new_exporter_review();
    }
    protected function initialFeeback()
    {
        return ui::html()->heading2()->add_child(ui::text_with_contrast_colors("ADD","EXPORTER REVIEW"));
    }
    protected function successFeeback()
    {
        return "Exporter review added successfully";
    }
    protected function textForNextActions()
    {
        return sprintf("%s",
            ui::links()->adminEditPost($this->getCmd()->readerForlastResponse()->file_name())->add_child("Start Editing Exporter review")
        );
    }
}

class FormFeedbackForEditPostTitle extends BaseClassForFormFeedback{

    public function __construct()
    {
    }

    protected function getCmd()
    {
        return app::cmds()->edit_post_title();
    }
    protected function initialFeeback()
    {
        return "";//return ui::html()->heading2()->add_child(ui::text_with_contrast_colors("Change title","or section"));
    }
    protected function successFeeback()
    {
        return "Title changed successfully";
    }
    protected function textForNextActions()
    {
        return sprintf("%s",
            ui::links()->adminEditPost($this->getCmd()->readerForlastResponse()->file_name())->add_child("See Result")
        );
    }
}

class FormFeedbackForDeletePost extends BaseClassForFormFeedback{

    public function __construct()
    {
    }

    protected function getCmd()
    {
        return app::cmds()->deletePost();
    }
    protected function initialFeeback()
    {
        return "";//ui::html()->heading1()->add_child(ui::text_with_contrast_colors("You may","delete this post"));
    }
    protected function successFeeback()
    {
        return "Post deleted successfully";
    }
    protected function textForNextActions()
    {
        return sprintf("%s",
            ui::links()->adminViewPosts()->add_child("See list of posts")
        );
    }
}

class FormFeedbackForPublishPost extends BaseClassForFormFeedback{

    public function __construct()
    {
    }

    protected function getCmd()
    {
        return app::cmds()->publishPost();
    }
    protected function initialFeeback()
    {
        return ""; //ui::html()->heading1()->add_child(ui::text_with_contrast_colors("Publish","this post"));
    }
    protected function successFeeback()
    {
        return "Post published";
    }

    protected function textForNextActions()
    {
        return sprintf("%s",
            ui::links()->adminViewPosts()->add_child("See list of posts")
        );
    }
    
}

class FormFeedbackForUnPublishPost extends BaseClassForFormFeedback{

    public function __construct()
    {
    }

    protected function getCmd()
    {
        return app::cmds()->unpublish_post();
    }
    protected function initialFeeback()
    {
        return ""; //ui::html()->heading1()->add_child(ui::text_with_contrast_colors("Publish","this post"));
    }
    protected function successFeeback()
    {
        return "Post Unpublished";
    }

    protected function textForNextActions()
    {
        return sprintf("%s",
            ui::links()->adminViewPosts()->add_child("See list of un-published posts")
        );
    }
}

class FormFeedbackForPublishAllPosts extends BaseClassForFormFeedback{

    public function __construct()
    {
    }

    protected function getCmd()
    {
        return app::cmds()->publishAllPosts();
    }
    protected function initialFeeback()
    {
        return ""; //ui::html()->heading2()->add_child(ui::text_with_contrast_colors("Publish","all posts"));
    }
    protected function successFeeback()
    {
        return "All your posts were published";
    }

    protected function textForNextActions()
    {
        return sprintf("%s",
            ui::links()->adminViewPostsPublished()->add_child("See published posts")
        );
    }
}
class FormFeedbackForEditPostContent extends BaseClassForFormFeedback{

    public function __construct()
    {
    }

    protected function getCmd()
    {
        return app::cmds()->edit_post_content();
    }
    protected function initialFeeback()
    {
        return ui::html()->heading2()->add_child(ui::text_with_contrast_colors("Edit","introduction"));
    }
    protected function successFeeback()
    {
        return "Introduction updated successfully";
    }
    protected function textForNextActions()
    {
        return sprintf("%s",
            ui::links()->adminEditPost($this->getCmd()->readerForlastResponse()->file_name())->add_child("See Result")
        );
    }
}



class FormFeedbackForEditExtendedPostContent extends BaseClassForFormFeedback{

    public function __construct()
    {
    }

    protected function getCmd()
    {
        return app::cmds()->edit_extended_post_content();
    }
    protected function initialFeeback()
    {
        return ui::html()->heading2()->add_child(ui::text_with_contrast_colors("Edit","full details"));
    }
    protected function successFeeback()
    {
        return "details updated successfully";
    }
    protected function textForNextActions()
    {
        return sprintf("%s",
            ui::links()->adminEditPost($this->getCmd()->readerForlastResponse()->file_name())->add_child("See Result")
        );
    }
}

class FormFeedbackForEditPostPicture extends BaseClassForFormFeedback{

    public function __construct()
    {
    }

    protected function getCmd()
    {
        return app::cmds()->edit_post_picture();
    }
    protected function initialFeeback()
    {
        return ""; //ui::html()->heading2()->add_child(ui::text_with_contrast_colors("Change","picture"));
    }
    protected function successFeeback()
    {
        return "Picture Changed successfully";
    }
    protected function textForNextActions()
    {
        return sprintf("%s",
            ui::links()->adminEditPost($this->getCmd()->readerForlastResponse()->file_name())->add_child("See Result")
        );
    }
}

class FormFeedbackForEditPostVideo extends BaseClassForFormFeedback{

    public function __construct()
    {
    }

    protected function getCmd()
    {
        return app::cmds()->edit_post_video();
    }
    protected function initialFeeback()
    {
        return ui::html()->heading2()->add_child(ui::text_with_contrast_colors("Enter","Youtube Id"));
    }
    protected function successFeeback()
    {
        return "Video Changed successfully";
    }
    protected function textForNextActions()
    {
        return sprintf("%s",
            ui::links()->adminEditPost($this->getCmd()->readerForlastResponse()->file_name())->add_child("See Result")
        );
    }
}

class FormFeedbackForEditCarSelected extends BaseClassForFormFeedback{

    public function __construct()
    {
    }

    protected function getCmd()
    {
        return app::cmds()->edit_car_selected();
    }
    protected function initialFeeback()
    {
        return ui::html()->heading2()->add_child(ui::text_with_contrast_colors("Select","a car"));
    }
    protected function successFeeback()
    {
        return "Car selection changed";
    }
    protected function textForNextActions()
    {
        return sprintf("%s",
            ui::links()->adminEditPost($this->getCmd()->readerForlastResponse()->file_name())->add_child("See Result")
        );
    }
}

class FormFeedbackForEditCarExporterSelected extends BaseClassForFormFeedback{

    public function __construct()
    {
    }

    protected function getCmd()
    {
        return app::cmds()->admin_edit_car_exporter_selected();
    }
    protected function initialFeeback()
    {
        return ui::html()->heading2()->add_child(ui::text_with_contrast_colors("Select","car exporter"));
    }
    
    protected function successFeeback()
    {
        return "Car exporter selection changed";
    }
    protected function textForNextActions()
    {
        return sprintf("%s",
            ui::links()->adminEditPost($this->getCmd()->readerForlastResponse()->file_name())->add_child("See Result")
        );
    }
}