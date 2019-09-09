<?php
abstract class FormForPerformingAction extends SmartForm{
    abstract public function getFormAction();
    abstract protected function onAddChildren();
    
    private $enable_cmd = true;
    public function disableCmd(){
        $this->enable_cmd = false;
        return $this;
    }
    public function __construct()
    {
        parent::__construct();
        $this->addClassForBoxShadow();
    }

    public function __toString()
    {

        $this->onAddChildren();
        $this->add_child_if($this->enable_cmd,(new HiddenInput())->set_name(app::values()->cmd())->set_value($this->getFormAction()));

        //=== add some styling
        $this->applyStyleForAdminPage();
        return parent::__toString()."";
    }

    protected function getFieldLayout()
    {
        $layout = new LayoutForTwoColumns();
        $layout->leftColumn()->width("20%")->display_inline_block()->vertical_align_top();
        $layout->rightColumn()->width("80%")->display_inline_block()->vertical_align_top();
        return $layout;
    }

    private function applyStyleForAdminPage()
    {   
        //$this->border_radius("16px");
    }

    protected function addClassForBoxShadow()
    {
        $this->add_class(ui::css_classes()->element_with_box_shadow());
    }

}
class FormForAddPost extends FormForPerformingAction {

    /** @var PickerForCategory $category_picker */
    private $category_picker;
    private $page_picker;
    private $section_picker;

    public function __construct($reader_for_categories,$reader_for_pages,$reader_for_sections)
    {
        $this->category_picker = ui::pickers()->category($reader_for_categories);
        $this->page_picker = ui::pickers()->page($reader_for_pages);
        $this->section_picker = ui::pickers()->section($reader_for_sections);

        parent::__construct();
    }

    protected function onAddChildren()
    {
        $layout = new LayoutForNRows();
        $layout->addNewRow()->add_child(ui::form_feedback()->addPost());
        $layout->addNewRow()->add_child($this->fieldForCategory())->add_class(ui::css_classes()->form_field_host());
        $layout->addNewRow()->add_child($this->fieldForPage())->add_class(ui::css_classes()->form_field_host());
        $layout->addNewRow()->add_child($this->fieldForSection())->add_class(ui::css_classes()->form_field_host());
        $layout->addNewRow()->add_child($this->fieldForTitle())->add_class(ui::css_classes()->form_field_host());
        $layout->addNewRow()->add_child($this->fieldForContent())->add_class(ui::css_classes()->form_field_host());
        $layout->addNewRow()->add_child($this->fieldForKeywords())->add_class(ui::css_classes()->form_field_host());
        $layout->addNewRow()->add_child($this->sectionForActions()->margin_bottom("0.5em"));


        //=====
        $layout->add_class(ui::css_classes()->form_items_host());

        $this->add_child($layout);
    }
    public function getFormAction()
    {
        return app::values()->add_post();
    }

    private function fieldForTitle()
    {
        $layout = $this->getFieldLayout();
        $layout->leftColumn()->add_child("Title");
        $layout->rightColumn()->add_child(ui::browser_fields()->title()->toTextInput()->width_100percent()->height("3.0em"));
        return $layout;
    }

    private function fieldForContent()
    {
        $layout = $this->getFieldLayout();
        $layout->leftColumn()->add_child("Content");
        $layout->rightColumn()->add_child(ui::browser_fields()->content()->toTextArea()->width_100percent()->height("36.0em"));
        return $layout;
    }

    private function fieldForKeywords()
    {
        $layout = $this->getFieldLayout();
        $layout->leftColumn()->add_child("Keywords");
        $layout->rightColumn()->add_child(ui::browser_fields()->keywords()->toTextInput()->width_100percent()->height("3.0em"));
        return $layout;
    }
    private function fieldForCategory()
    {
        $layout = $this->getFieldLayout();
        $layout->leftColumn()->add_child("Category")->font_weight_bold();
        $layout->rightColumn()->add_child($this->category_picker->width_100percent()->height("3.0em"));
        return $layout;
    }
    private function fieldForPage()
    {
        $layout = $this->getFieldLayout();
        $layout->leftColumn()->add_child("Page");
        $layout->rightColumn()->add_child($this->page_picker->width_100percent()->height("3.0em"));
        return $layout;
    }
    
    private function fieldForSection()
    {
        $layout = $this->getFieldLayout();
        $layout->leftColumn()->add_child("Section");
        $layout->rightColumn()->add_child($this->section_picker->width_100percent()->height("3.0em"));
        return $layout;
    }
   
    private function sectionForActions()
    {
        return ui::buttons()->submit()->set_value("Publish")->margin_top("-2px");
    }


}

class FormForInsertBulkPosts extends FormForPerformingAction{
    private $field_set;
    private $reader_for_sections;

    /** @param \SmartForm $form */
    public function __construct($total_records_to_insert,$reader_for_sections)
    {
        ui::exception()->throwIfNotReader($reader_for_sections);
        $this->reader_for_sections = $reader_for_sections;

        parent::__construct();

        $field_set = new LayoutForNRows();
        for($record_number = 0; $record_number < $total_records_to_insert;$record_number++){
            $field_set->addNewRow()->add_child(
                $this->newRecord($record_number)
            )->
            border_bottom("8px solid #444")->padding("8px")->margin_bottom("4px");
        }

        $this->field_set = $field_set;
    }

    protected function onAddChildren()
    {
        $layout = new LayoutForNRows();
        $layout->addNewRow()->add_child(ui::form_feedback()->createMultiplePosts());
        $layout->addNewRow()->add_child($this->field_set);
        $layout->addNewRow()->add_child($this->newSubmitButton()->set_value("Submit")->width_auto());

        $this->add_child($layout);
    }
    public function getFormAction()
    {
        return app::values()->create_multiple_posts();
    }

    private function newTextBox(){
        return new TextInput();
    }

    private function newSubmitButton()
    {
        return new SubmitInput();
    }

    private function newRecord($record_number)
    {
        $section_id_picker = ui::pickers()->section_as_multi_input($this->reader_for_sections,1,$record_number);

        $layout = new LayoutForNColumns();

        //add field for title
        $layout->addNewColumn()->add_child($this->fieldForAttribute($record_number,"title"))->max_width("200px");
        $layout->addNewColumn()->add_child($this->fieldForAttribute($record_number,"content"))->max_width("200px");
        $layout->addNewColumn()->add_child($this->fieldForAttribute($record_number,"extended_post_content"))->max_width("200px");
        $layout->addNewColumn()->add_child($section_id_picker)->max_width("200px");
        return $layout;
    }

    private function fieldForAttribute($record_number,$attribute)
    {
        $layout = new LayoutForNRows();
        $layout->addNewRow()->add_child($attribute);
        $layout->addNewRow()->add_child(
            $this->newTextBox()->
            set_name($attribute."[]")->
            set_value(@$_REQUEST[$attribute][$record_number])
        );
        return $layout;
    }
}


class FormForLogin extends FormForPerformingAction {

    public function __construct()
    {
        parent::__construct();
    }

    protected function onAddChildren()
    {
        $layout = new LayoutForNRows();
        $layout->addNewRow()->add_child(ui::form_feedback()->login());
        $layout->addNewRow()->add_child($this->fieldForEmailAddress())->add_class(ui::css_classes()->form_field_host());
        $layout->addNewRow()->add_child($this->fieldForPassword())->add_class(ui::css_classes()->form_field_host());
        $layout->addNewRow()->add_child($this->sectionForActions()->margin_bottom("0.5em"));


        //=====
        $layout->add_class(ui::css_classes()->form_items_host());

        $this->add_child($layout);
    }
    public function getFormAction()
    {
        return app::values()->login();
    }

    private function fieldForEmailAddress()
    {
        $layout = $this->getFieldLayout();
        $layout->leftColumn()->add_child("Email Address");
        $layout->rightColumn()->add_child(ui::browser_fields()->email_address()->toTextInput()->width_100percent()->height("3.0em"));
        return $layout;
    }

    private function fieldForPassword()
    {
        $layout = $this->getFieldLayout();
        $layout->leftColumn()->add_child("Password");
        $layout->rightColumn()->add_child(ui::browser_fields()->password()->toPasswordInput()->width_100percent()->height("3.0em"));
        return $layout;
    }


    protected function sectionForActions()
    {
        return ui::buttons()->submit()->set_value("Login");
    }

}
class FormForLogout extends FormForPerformingAction {

    public function __construct()
    {
        parent::__construct();
        //$this->set_url(ui::form_feedback()->logout()->urlToFeedback());
        //$this->set_url(ui::urls()->home());

        $this->add_class(ui::css_classes()->form_with_only_button());
    }


    protected function onAddChildren()
    {
        $layout = new LayoutForNRows();
        
        $layout->addNewRow()->add_child(ui::form_feedback()->logout());
        $layout->addNewRow()->add_child($this->sectionForActions());
        $this->add_child($layout);
    }
    public function getFormAction()
    {
        return app::values()->logout();
    }
    protected function sectionForActions()
    {
        return ui::buttons()->submit()->set_value("Logout");
    }
}


class FormForStartNewPost extends FormForPerformingAction {

    private $reader_for_sections;
    
    public function __construct($reader_for_sections)
    {
        ui::exception()->throwIfNotReader($reader_for_sections);
        $this->reader_for_sections = $reader_for_sections;
        
        parent::__construct();
        
    }

    protected function onAddChildren()
    {
        $layout = new LayoutForNRows();
        $layout->addNewRow()->add_child(ui::form_feedback()->start_new_post());
        $layout->addNewRow()->add_child($this->fieldForSection())->add_class(ui::css_classes()->form_field_host());
        $layout->addNewRow()->add_child($this->fieldForTitle())->add_class(ui::css_classes()->form_field_host());
        $layout->addNewRow()->add_child($this->sectionForActions()->margin_bottom("0.5em"));


        //=====
        $layout->add_class(ui::css_classes()->form_items_host());

        $this->add_child($layout);
    }
    public function getFormAction()
    {
        return app::values()->start_new_post();
    }

    private function fieldForSection()
    {
        $layout = $this->getFieldLayout();
        $layout->leftColumn()->add_child("What are you posting?");
        $layout->rightColumn()->add_child(ui::pickers()->section($this->reader_for_sections));
        return $layout;
    }

    private function fieldForTitle()
    {
        $layout = $this->getFieldLayout();
        $layout->leftColumn()->add_child("Title");
        $layout->rightColumn()->add_child(ui::browser_fields()->title()->toTextInput()->width_100percent()->height("3.0em"));
        return $layout;
    }

    

    protected function sectionForActions()
    {
        return ui::buttons()->submit()->set_value("Create New Post");
    }


}

abstract class FormForStartOtherTypeOfPost extends FormForPerformingAction {

    protected function onAddChildren()
    {
        $layout = new LayoutForNRows();
        $layout->addNewRow()->add_child($this->formFeedbackObject());
        $layout->addNewRow()->add_child($this->fieldForSection());
        $layout->addNewRow()->add_child($this->fieldForTitle())->add_class(ui::css_classes()->form_field_host());
        $layout->addNewRow()->add_child($this->sectionForActions()->margin_bottom("0.5em"));


        //=====
        $layout->add_class(ui::css_classes()->form_items_host());

        $this->add_child($layout);
    }
    public function getFormAction()
    {
        return app::values()->start_new_post();
    }

    private function fieldForSection()
    {
        return (new HiddenInput())->set_name(app::values()->section_id())->set_value($this->get_section_id());

    }

    private function fieldForTitle()
    {
        $layout = $this->getFieldLayout();
        $layout->leftColumn()->add_child("Title");
        $layout->rightColumn()->add_child(ui::browser_fields()->title()->toTextInput()->autofocus()->width_100percent()->height("3.0em"));
        return $layout;
    }



    protected function sectionForActions()
    {
        return ui::buttons()->submit()->set_value($this->textForSubmitButton());
    }

    protected function formFeedbackObject()
    {
        return ui::form_feedback()->start_new_post();
    }

    abstract protected function textForSubmitButton();

    protected function get_section_id()
    {
        return app::section_ids()->cars();
    }


}

class FormForStartNewCar extends FormForStartOtherTypeOfPost{
    protected function formFeedbackObject()
    {
        return ui::form_feedback()->start_new_car();
    }
    protected function textForSubmitButton(){
        return "Register";
    }
    protected function get_section_id()
    {
        return app::section_ids()->cars();
    }
}
class FormForStartNewCarExporter extends FormForStartOtherTypeOfPost{
    protected function formFeedbackObject()
    {
        return ui::form_feedback()->start_new_car_exporter();
    }
    protected function textForSubmitButton(){
        return "Register";
    }
    protected function get_section_id()
    {
        return app::section_ids()->car_exporters();
    }

}
class FormForStartNewCarNews extends FormForStartOtherTypeOfPost{
    protected function formFeedbackObject()
    {
        return ui::form_feedback()->start_new_car_news();
    }
    protected function textForSubmitButton(){
        return "Share";
    }
    protected function get_section_id()
    {
        return app::section_ids()->news();
    }

}
class FormForStartNewJobOpportunity extends FormForStartOtherTypeOfPost{
    protected function formFeedbackObject()
    {
        return ui::form_feedback()->start_new_job_opportunity();
    }
    protected function textForSubmitButton(){
        return "Share";
    }
    protected function get_section_id()
    {
        return app::section_ids()->careers();
    }

}
class FormForStartNewCarMaintenance extends FormForStartOtherTypeOfPost{
    protected function formFeedbackObject()
    {
        return ui::form_feedback()->start_new_car_maintenance();
    }
    protected function textForSubmitButton(){
        return "Share";
    }
    protected function get_section_id()
    {
        return app::section_ids()->car_maintenance();
    }
}

class FormForStartNewCarReview extends FormForPerformingAction {

    private $reader_for_objects;

    public function __construct($reader_for_objects)
    {
        ui::exception()->throwIfNotReader($reader_for_objects);
        $this->reader_for_objects = $reader_for_objects;

        parent::__construct();

    }

    protected function onAddChildren()
    {
        $layout = new LayoutForNRows();

        $layout->addNewRow()->add_child($this->formFeedbackObject());

        $layout->addNewRow()->add_child($this->fieldForSelectObject())->add_class(ui::css_classes()->form_field_host());
        $layout->addNewRow()->add_child($this->fieldForTitle())->add_class(ui::css_classes()->form_field_host());
        $layout->addNewRow()->add_child($this->sectionForActions()->margin_bottom("0.5em"));

        //=====
        $layout->add_class(ui::css_classes()->form_items_host());

        $this->add_child(
            $layout
        );
    }
   
    private function fieldForSelectObject()
    {
        $layout = $this->getFieldLayout();
        $layout->leftColumn()->add_child($this->labelForSelectObject());
        $layout->rightColumn()->add_child($this->get_picker($this->reader_for_objects));
        return $layout;
    }

    private function fieldForTitle()
    {
        $layout = $this->getFieldLayout();
        $layout->leftColumn()->add_child("Title");
        $layout->rightColumn()->add_child(ui::browser_fields()->title()->toTextInput()->autofocus()->width_100percent()->height("3.0em"));
        return $layout;
    }



    protected function sectionForActions()
    {
        return ui::buttons()->submit()->set_value($this->textForSubmitButton());
    }

    #==================
    protected function textForSubmitButton()
    {
        return "Add";
    }

    protected function labelForSelectObject()
    {
        return "for";
    }

    protected function formFeedbackObject()
    {
        return ui::form_feedback()->start_new_car_review();
    }

    protected function get_picker($reader_for_objects)
    {
        return ui::pickers()->cars($reader_for_objects);
    }

    public function getFormAction()
    {
        return app::values()->start_new_car_review();
    }
    
}

class FormForStartNewCarVideo extends FormForStartNewCarReview{
    protected function textForSubmitButton()
    {
        return "Add";
    }

    protected function formFeedbackObject()
    {
        return ui::form_feedback()->start_new_car_video();
    }
    protected function get_picker($reader_for_objects)
    {
        return ui::pickers()->cars($reader_for_objects);
    }
    public function getFormAction()
    {
        return app::values()->start_new_car_video();
    }
}

class FormForStartNewCarPicture extends FormForStartNewCarReview{
    protected function textForSubmitButton()
    {
        return "Add";
    }

    protected function formFeedbackObject()
    {
        return ui::form_feedback()->start_new_car_picture();
    }
    protected function get_picker($reader_for_objects)
    {
        return ui::pickers()->cars($reader_for_objects);
    }
    public function getFormAction()
    {
        return app::values()->start_new_car_picture();
    }
}

class FormForStartNewExporterReview extends FormForStartNewCarReview{
    protected function textForSubmitButton()
    {
        return "Add";
    }

    protected function formFeedbackObject()
    {
        return ui::form_feedback()->start_new_exporter_review();
    }
    protected function get_picker($reader_for_objects)
    {
        return ui::pickers()->car_exporters($reader_for_objects);
    }
    public function getFormAction()
    {
        return app::values()->start_new_exporter_review();
    }
}

class FormForDeletePost extends FormForPerformingAction {
    public function __construct($file_name)
    {
        parent::__construct();
        $this->add_child(ui::form_feedback()->delete_post());
        $this->add_child(ui::browser_fields()->file_name()->toHiddenInput($file_name));

        $this->add_class(ui::css_classes()->form_with_only_button());
    }

    protected function onAddChildren()
    {
        $this->add_child(
            ui::buttons()->submit()->set_value("Delete Post")
        );
    }
    public function getFormAction()
    {
        return app::values()->delete_post();
    }

}
abstract class FormForPerformingActionOnAPost extends FormForPerformingAction {
    public function __construct($file_name)
    {
        parent::__construct();
        $this->add_child($this->get_form_feedback());
        $this->add_child(ui::browser_fields()->file_name()->toHiddenInput($file_name));

        $this->add_class(ui::css_classes()->form_with_only_button());
    }

    protected function onAddChildren()
    {
        $container = ui::html()->div();

        $submit_button = ui::buttons()->submit()->set_value(
            $this->getButtonText()
        );

        $dummy = ui::html()->div()->add_child($this->getButtonText())->text_align_center()->background_color("transparent");

        $container->add_child($submit_button->opacity("0")->position_relative()->z_index(5));
        $container->add_child($dummy->position_absolute()->left("0px")->top("0px")->width("94%")->padding("2% 3%"));

        $container->border("1px solid #ddd")->font_weight_bold()->position_relative()->overflow_hidden()->border_radius("5px");
        
        $container->
        background_color("#eee")/*->
        background_image_url("http://localhost/motokaviews/libs_experimental/fb_gallery/img.jpg")*/;
        $this->add_child($container);

        $this->margin_bottom("0.5em");

        /*$this->add_child(
            ui::buttons()->submit()->set_value(
                $this->getButtonText()
            )->opacity("1.0")

        )->add_child(
            ui::html()->div()->add_child("pub")->position_absolute()->top("0px")->text_align_center()->background_color("#eee")
        )->position_relative();*/
    }
    
    abstract protected function get_form_feedback();
    abstract protected function getButtonText();
}

class FormForPublishPost extends FormForPerformingActionOnAPost{
    public function getFormAction()
    {
        return app::values()->publish_post();
    }

    protected function get_form_feedback()
    {
        return ui::form_feedback()->publish_post();
    }

    protected function getButtonText()
    {
        return "Publish Post";
    }
}

class FormForUnPublishPost extends FormForPerformingActionOnAPost{
    public function getFormAction()
    {
        return app::values()->unpublish_post();
    }

    protected function get_form_feedback()
    {
        return ui::form_feedback()->unpublish_post();
    }

    protected function getButtonText()
    {
        return "UnPublish Post";
    }
}

class FormForPublishAllPosts extends FormForPerformingAction {
    public function __construct()
    {
        parent::__construct();
        $this->add_child(ui::form_feedback()->publish_all_posts());

        $this->add_class(ui::css_classes()->form_with_only_button());
    }

    protected function onAddChildren()
    {
        $this->add_child(
            ui::buttons()->submit()->set_value("Publish All")
        );
    }
    public function getFormAction()
    {
        return app::values()->publish_all_posts();
    }

}

abstract class FormForEditPostSection extends FormForPerformingAction {


    /** @var  ReaderForValuesStoredInArray $reader_for_post */
    private $reader_for_post;
    protected function reader(){
        return $this->reader_for_post;
    }

    public function __construct($reader_for_post)
    {
        ui::exception()->throwIfNotReader($reader_for_post);
        parent::__construct();
        $this->reader_for_post = $reader_for_post;
    }
}


class FormForEditPostTitle extends FormForEditPostSection {
    /** @var PickerForCategory $section_picker */
    private $section_picker;

    /** @param \ReaderForValuesStoredInArray $reader_for_post */
    public function __construct($reader_for_post,$reader_for_sections)
    {
        ui::exception()->throwIfNotReader($reader_for_sections);
        parent::__construct($reader_for_post);
        $this->section_picker = ui::pickers()->section($reader_for_sections,$reader_for_post->section_id());
    }

    protected function onAddChildren()
    {
        $layout = new LayoutForNRows();
        $layout->addNewRow()->add_child(ui::form_feedback()->edit_post_title());
        $layout->addNewRow()->add_child($this->fieldForTitle())->add_class(ui::css_classes()->form_field_host());
        $layout->addNewRow()->add_child($this->fieldForSection())->add_class(ui::css_classes()->form_field_host());
        $layout->addNewRow()->add_child($this->sectionForActions()->margin_bottom("0.5em"));


        //=====
        $layout->add_class(ui::css_classes()->form_items_host());

        $this->add_child($layout);
    }
    public function getFormAction()
    {
        return app::values()->admin_edit_post_title();
    }

    private function fieldForTitle()
    {
        return ui::browser_fields()->title()->toTextInput(
            $this->reader()->title()
        )->autofocus()->placeholder("Write the Title")->width_100percent()->height("3.0em");
    }

    private function fieldForSection()
    {
        return ui::html()->div()->add_child(
            ui::html()->div()->add_child("Change Section if necessary").
            ui::html()->div()->add_child($this->section_picker->width_100percent()->height("3.0em"))
        )->margin_top("1.0em");
    }


    protected function sectionForActions()
    {
        return ui::buttons()->submit()->set_value("Done");
    }


}
abstract class BaseFormForEditCarSelected extends FormForEditPostSection{
    /** @var PickerForMotoka $item_picker */
    private $item_picker;

    /** @param \ReaderForValuesStoredInArray $reader_for_post */
    public function __construct($reader_for_post, $reader_for_cars)
    {
        ui::exception()->throwIfNotReader($reader_for_cars);
        parent::__construct($reader_for_post);
        $this->item_picker = $this->itemPicker($reader_for_post, $reader_for_cars);
    }

    protected function onAddChildren()
    {
        $layout = new LayoutForNRows();
        $layout->addNewRow()->add_child($this->formFeedback());
        $layout->addNewRow()->add_child($this->fieldForItemPicker())->add_class(ui::css_classes()->form_field_host());
        $layout->addNewRow()->add_child($this->sectionForActions()->margin_bottom("0.5em"));


        //=====
        $layout->add_class(ui::css_classes()->form_items_host());

        $this->add_child($layout);
    }

    private function fieldForItemPicker()
    {
        $layout = $this->getFieldLayout();
        $layout->leftColumn()->add_child($this->fieldLabel());
        $layout->rightColumn()->add_child($this->item_picker->width_100percent()->height("3.0em"));
        return $layout;
    }


    protected function sectionForActions()
    {
        return ui::buttons()->submit()->set_value("Done")->margin_top("-2px");
    }

    abstract protected function fieldLabel();
    abstract protected function itemPicker($reader_for_post, $reader_for_items);
    abstract protected function formFeedback();
}
class FormForEditCarSelected extends BaseFormForEditCarSelected {


    public function getFormAction()
    {
        return app::values()->admin_edit_post_car_selected();
    }
    
    protected function fieldLabel()
    {
        return "Car";
    }

    protected function itemPicker($reader_for_post, $reader_for_items)
    {
        return ui::pickers()->cars($reader_for_items, $reader_for_post->entity_id());
    }

    protected function formFeedback()
    {
        return ui::form_feedback()->edit_post_car_selected();
    }

}
class FormForEditCarExporterSelected extends BaseFormForEditCarSelected{
    protected function fieldLabel()
    {
        return "Car Exporter";
    }

    public function getFormAction()
    {
        return app::values()->admin_edit_post_car_exporter_selected();
    }

    protected function itemPicker($reader_for_post, $reader_for_items)
    {
        return ui::pickers()->car_exporters($reader_for_items, $reader_for_post->entity_id());
    }

    protected function formFeedback()
    {
        return ui::form_feedback()->edit_post_car_exporter_selected();
    }
}

class FormForEditPostContent extends FormForEditPostSection {

    protected function onAddChildren()
    {
        $layout = new LayoutForNRows();
        $layout->addNewRow()->add_child($this->feedback_object());
        $layout->addNewRow()->add_child($this->fieldForContent())->add_class(ui::css_classes()->form_field_host());
        $layout->addNewRow()->add_child($this->sectionForActions()->margin_bottom("0.5em"));


        //=====
        $layout->add_class(ui::css_classes()->form_items_host());

        $this->add_child($layout);
    }
    public function getFormAction()
    {
        return app::values()->admin_edit_post_content();
    }

    private function fieldForContent()
    {
        return $this->browser_field()->toTextArea($this->defaultTextForTextArea())->set_attribute("autofocus","true")->width_100percent()->
        min_height($this->boxHeight());
    }



    protected function sectionForActions()
    {
        return ui::buttons()->submit()->set_value("Done");
    }

    protected function feedback_object()
    {
        return ui::form_feedback()->edit_post_content();
    }

    protected function browser_field()
    {
        return ui::browser_fields()->content();
    }

    protected function defaultTextForTextArea()
    {
        return $this->reader()->content();
    }

    protected function boxHeight()
    {
        return "12.0em";
    }

    protected function label()
    {
        return "Introduction";
    }


}
class FormForEditExtendedPostContent extends FormForEditPostContent{
    public function getFormAction()
    {
        return app::values()->admin_edit_extended_post_content();
    }
    protected function feedback_object()
    {
        return ui::form_feedback()->edit_extended_post_content();
    }
    protected function browser_field()
    {
        return ui::browser_fields()->extended_post_content();
    }
    protected function defaultTextForTextArea()
    {
        return $this->reader()->extended_post_content();
    }
    protected function boxHeight()
    {
        return "36.0em";
    }

    protected function label()
    {
        return "FULL DETAILS";
    }
}

class FormForEditPostPicture extends FormForEditPostSection {

    protected function addClassForBoxShadow()
    {
    }
    protected function onAddChildren()
    {
        $layout = new LayoutForNRows();
        $layout->addNewRow()->add_child(ui::form_feedback()->edit_post_picture());
        $layout->addNewRow()->add_child($this->fieldForChoosePicture());
        $layout->addNewRow()->add_child($this->sectionForActions());
        $this->add_child($layout);
    }
    public function getFormAction()
    {
        return app::values()->admin_edit_post_picture();
    }

    private function fieldForChoosePicture()
    {
        $this->set_id("frm_change_pic");

        return ui::html()->span()->add_child(
            ui::browser_fields()->file_to_upload()->toFileInput()->
            onchange("document.forms['frm_change_pic'].submit();")->
            opacity("0")->
            cursor_pointer()->position_absolute()
        )->add_child(
            ui::html()->span()->add_child("CLICK to Add/Change Photo")->padding("0.5em")
        )->
        position_relative()->
        background_color("#eee")->
        cursor_pointer()->border_radius("1.0em")->text_align_center()->width_auto();


    }



    protected function sectionForActions()
    {
        return ui::html()->custom_tag("noscript")->add_child(ui::buttons()->submit()->set_value("Done"));
    }

}

class FormForEditPostVideo extends FormForEditPostSection {

    protected function addClassForBoxShadow()
    {
    }
    protected function onAddChildren()
    {
        $layout = new LayoutForNRows();
        $layout->addNewRow()->add_child(ui::form_feedback()->edit_post_video());
        $layout->addNewRow()->add_child($this->fieldForYoutubeVideoId())->add_class(ui::css_classes()->form_field_host());
        $layout->addNewRow()->add_child($this->sectionForActions()->margin_bottom("0.5em"));


        //=====
        //$layout->add_class(ui::css_classes()->form_items_host());
        $layout->padding("0px 1.0em");

        $this->add_child($layout);
    }
    public function getFormAction()
    {
        return app::values()->admin_edit_post_video();
    }

    private function fieldForYoutubeVideoId()
    {
        return ui::browser_fields()->youtube_video_id()->toTextInput($this->reader()->youtube_video_id())->width_100percent()->height("3.0em");
    }

    protected function sectionForActions()
    {
        return ui::buttons()->submit()->set_value("Done");
    }

}

class FormForAddPicture extends FormForEditPostSection {

    protected function onAddChildren()
    {
        $layout = new LayoutForNRows();
        $layout->addNewRow()->add_child($this->getFormFeeback());
        $layout->addNewRow()->add_child($this->sectionForBrowsImage())->add_class(ui::css_classes()->form_field_host());
        $layout->addNewRow()->add_child($this->sectionForActions()->margin_bottom("0.5em"));
        
        //=====
        $layout->add_class(ui::css_classes()->form_items_host());

        $this->add_child($layout);

        $this->add_child(ui::browser_fields()->file_to_upload()->toHiddenInput());
    }
    public function getFormAction()
    {
        return app::values()->add_image();
    }

    private function sectionForBrowsImage()
    {
        $layout = $this->getFieldLayout();
        $layout->leftColumn()->add_child("Browse")->font_weight_bold();
        $layout->rightColumn()->add_child(ui::browser_fields()->file_to_upload()->toFileInput()->height("3.0em"));
        return $layout;
    }   

    private function sectionForActions()
    {
        return ui::buttons()->submit()->set_value("Submit")->margin("-2px 0px 0px -1px");
    }

    protected function getFormFeeback()
    {
        return ui::form_feedback()->addImage();
    }


}
class FormForAttactPictureToPost extends FormForAddPicture{
    public function getFormAction()
    {
        return app::values()->attach_image_to_post();
    }
    protected function getFormFeeback()
    {
        return ui::form_feedback()->attachImageToPost();
    }
}

