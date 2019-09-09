<?php
class ArgumentFactory{
    public function function_names()
    {
        return new InputForFunctionNames();
    }

    public function file_name()
    {
        return new InputForFileName();
    }

    public function entity_id()
    {
        return new InputForEntityId();
    }

    public function title()
    {
        return new InputForTitle();
    }
    public function title_array()
    {
        return new InputForTitleArray();
    }

    public function content()
    {
        return new InputForContent();
    }
    public function content_array()
    {
        return new InputForContentArray();
    }
    public function extended_post_content()
    {
        return new InputForExtendedPostContent();
    }
    public function extended_post_content_array()
    {
        return new InputForExtendedPostContentArray();
    }

    public function category_id()
    {
        return new InputForCategoryId();
    }
    public function target_page_id()
    {
        return new InputForTargetPageId();
    }
    public function section_id()
    {
        return new InputForSectionId();
    }
    public function youtube_video_id()
    {
        return new InputForYoutubeVideoId();
    }

    public function section_id_array()
    {
        return new InputForSectionIdArray();
    }

    public function keywords()
    {
        return new InputForKeywords();
    }

    public function car_id()
    {
        return new InputForCarId();
    }

    public function car_exporter_id()
    {
        return new InputForCarExporterId();
    }

    public function email_address()
    {
        return new InputForEmailAddress();
    }
    public function password()
    {
        return new InputForPassword();
    }

    public function full_name()
    {
        return new InputForFullName();
    }


}