<?php
class BrowserFieldFactory{
    public function page(){
        return new BrowserFieldForPage();
    }

    public function title()
    {
        return new BrowserFieldForTitle();
    }

    public function youtube_video_id()
    {
        return new BrowserFieldForYoutubeVideoId();
    }

    public function content()
    {
        return new BrowserFieldForContent();
    }

    public function extended_post_content()
    {
        return new BrowserFieldForExtendedPostContent();
    }

    public function cmd()
    {
        return new BrowserFieldForCmd();
    }
    public function keywords()
    {
        return new BrowserFieldForKeywords();
    }
    public function category()
    {
        return new BrowserFieldForCategory();
    }
    public function car_id()
    {
        return new BrowserFieldForCarId();
    }
    public function car_exporter_id()
    {
        return new BrowserFieldForCarExporterId();
    }
    public function section_id()
    {
        return new BrowserFieldForSectionId();
    }
    public function target_page_id()
    {
        return new BrowserFieldForTargetPageId();
    }
    public function file_to_upload()
    {
        return new BrowserFieldForFileToUpload();
    }
    public function file_name()
    {
        return new BrowserFieldForFileName();
    }
    public function email_address()
    {
        return new BrowserFieldForEmailAddress();
    }
    public function password()
    {
        return new BrowserFieldForPassword();
    }
}