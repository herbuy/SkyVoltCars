<?php
class SQLIdentifierForMotokaviews extends SQLIdentifier{
    public function toCurrentTimestamp(){
        return $this->toSQLValue(Db::computed_values()->current_timestamp());        
    }
}
class DbFieldFactory{
    public function file_name(){
        return new SQLIdentifierForMotokaviews(app::values()->file_name());
    }
    public function title()
    {
        return new SQLIdentifierForMotokaviews(app::values()->title());
    }
    public function content()
    {
        return new SQLIdentifierForMotokaviews(app::values()->content());
    }
    public function timestamp()
    {
        return new SQLIdentifierForMotokaviews(app::values()->timestamp());
    }
    public function year()
    {
        return new SQLIdentifierForMotokaviews(app::values()->year());
    }
    public function month()
    {
        return new SQLIdentifierForMotokaviews(app::values()->month());
    }
    public function day()
    {
        return new SQLIdentifierForMotokaviews(app::values()->day());
    }
    public function day_of_the_week()
    {
        return new SQLIdentifierForMotokaviews(app::values()->day_of_the_week());
    }

    public function entity_id()
    {
        return new SQLIdentifierForMotokaviews(app::values()->entity_id());
    }

    public function row_id()
    {
        return new SQLIdentifierForMotokaviews(app::values()->row_id());
    }

    public function category_id()
    {
        return new SQLIdentifierForMotokaviews(app::values()->category_id());
    }

    public function page_id()
    {
        return new SQLIdentifierForMotokaviews(app::values()->page_id());
    }

    public function section_id()
    {
        return new SQLIdentifierForMotokaviews(app::values()->section_id());
    }

    public function car_id()
    {
        return new SQLIdentifierForMotokaviews(app::values()->car_id());
    }
    public function car_exporter_id()
    {
        return new SQLIdentifierForMotokaviews(app::values()->car_exporter_id());
    }

    public function picture_file_name()
    {
        return new SQLIdentifierForMotokaviews(app::values()->picture_file_name());
    }

    public function content_type()
    {
        return new SQLIdentifierForMotokaviews(app::values()->content_type());
    }

    public function src()
    {
        return new SQLIdentifierForMotokaviews(app::values()->src());
    }
    public function alt()
    {
        return new SQLIdentifierForMotokaviews(app::values()->alt());
    }

    public function extended_post_content()
    {
        return new SQLIdentifierForMotokaviews(app::values()->extended_post_content());
    }

    public function href()
    {
        return new SQLIdentifierForMotokaviews(app::values()->href());
    }

    public function width()
    {
        return new SQLIdentifierForMotokaviews(app::values()->width());
    }
    public function height()
    {
        return new SQLIdentifierForMotokaviews(app::values()->height());
    }

    public function rating()
    {
        return new SQLIdentifierForMotokaviews(app::values()->rating());
    }

    #============ date related
    public function date_in_full()
    {
        return new SQLIdentifierForMotokaviews(app::values()->date_in_full());
    }
    public function year_number()
    {
        return new SQLIdentifierForMotokaviews(app::values()->year_number());
    }
    public function month_number()
    {
        return new SQLIdentifierForMotokaviews(app::values()->month_number());
    }
    public function month_name()
    {
        return new SQLIdentifierForMotokaviews(app::values()->month_name());
    }
    public function month_description()
    {
        return new SQLIdentifierForMotokaviews(app::values()->month_description());
    }
    public function week_of_the_year_number()
    {
        return new SQLIdentifierForMotokaviews(app::values()->week_of_the_year_number());
    }
    public function week_of_the_year_description()
    {
        return new SQLIdentifierForMotokaviews(app::values()->week_of_the_year_description());
    }
    public function day_name()
    {
        return new SQLIdentifierForMotokaviews(app::values()->day_name());
    }
    public function day_of_the_week_number()
    {
        return new SQLIdentifierForMotokaviews(app::values()->day_of_the_week_number());
    }
    public function day_of_the_month_number()
    {
        return new SQLIdentifierForMotokaviews(app::values()->day_of_the_month_number());
    }
    public function day_of_the_year_number()
    {
        return new SQLIdentifierForMotokaviews(app::values()->day_of_the_year_number());
    }
    public function day_of_the_year_description()
    {
        return new SQLIdentifierForMotokaviews(app::values()->day_of_the_year_description());
    }

    public function password_hash()
    {
        return new SQLIdentifierForMotokaviews(app::values()->password_hash());
    }
    public function email_address()
    {
        return new SQLIdentifierForMotokaviews(app::values()->email_address());
    }

    public function session_id()
    {
        return new SQLIdentifierForMotokaviews(app::values()->session_id());
    }

    public function youtube_video_id()
    {
        return new SQLIdentifierForMotokaviews(app::values()->youtube_video_id());
    }

    public function approval_status_code()
    {
        return new SQLIdentifierForMotokaviews(app::values()->approval_status_code());
    }
    public function hash_code()
    {
        return new SQLIdentifierForMotokaviews(app::values()->hash_code());
    }
    public function session_id_hash()
    {
        return new SQLIdentifierForMotokaviews(app::values()->session_id_hash());
    }
    public function full_name()
    {
        return new SQLIdentifierForMotokaviews(app::values()->full_name());
    }
    

}
