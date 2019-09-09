<?php
class MaxLengthFactory{
    public function file_name(){
        return  1750;
    }

    public function title()
    {
        return 128;
    }
    public function youtube_video_id()
    {
        return 128;
    }

    public function content()
    {
        return 65000;
    }

    public function timestamp()
    {
        return 10;
    }
    public function row_id()
    {
        return 20;
    }
    public function entity_id()
    {
        return 20;
    }

    public function keywords()
    {
        return 64;
    }

    public function content_type()
    {
        return 64;
    }

    public function src()
    {
        return $this->file_name();
    }
    public function href()
    {
        return $this->file_name();
    }

    public function alt()
    {
        return 255;
    }

    public function extended_post_content()
    {
        return $this->content();
    }

    public function int()
    {
        return 10;
    }

    public function width_in_tbl_for_extended_content()
    {
        return 2000;
    }
    public function height_in_tbl_for_extended_content()
    {
        return 2000;
    }

    public function date_in_full()
    {
        return 10;
    }
    public function year_number()
    {
        return 4;
    }
    public function month_number()
    {
        return 2;
    }
    public function month_name()
    {
        return 16;
    }
    public function month_description()
    {
        return 32;
    }
    public function week_of_the_year_number()
    {
        return 2;
    }
    public function week_of_the_year_description()
    {
        return 32;
    }
    public function day_name()
    {
        return 16;
    }
    public function day_of_the_week_number()
    {
        return 1;
    }
    public function day_of_the_month_number()
    {
        return 2;
    }
    public function day_of_the_year_number()
    {
        return 3;
    }

    public function day_of_the_year_description()
    {
        return 32;
    }

    public function email_address()
    {
        return 128;
    }
    public function password()
    {
        return 4000;
    }
    public function password_hash()
    {
        return 256;
    }

    public function session_id()
    {
        return 64;
    }
    public function session_id_hash()
    {
        return 128;
    }
    public function full_name()
    {
        return 128;
    }
    public function comment()
    {
        return 800;
    }
    public function text_code()
    {
        return 32;
    }

}
