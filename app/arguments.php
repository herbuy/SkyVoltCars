<?php
class InputForFunctionNames extends CommaSeparatedEnum{
    public function getName()
    {
        return app::values()->function_names();
    }

    protected function getArrayOfAcceptableValues()
    {
        return array(
            app::values()->get_posts(),
            app::values()->get_post(),
            app::values()->get_most_recent_post(),
            app::values()->get_most_recent_post_per_category(),
            app::values()->get_categories(),
            app::values()->get_sections(),
            app::values()->get_pages(),

            app::values()->get_posts_for_reviews(),
            app::values()->get_posts_for_exporter_reviews(),
            app::values()->get_posts_for_car_exporters(),
            app::values()->get_posts_for_news(),
            app::values()->get_posts_for_careers(),
            app::values()->get_posts_for_car_maintenance(),
            app::values()->get_cars(),
            app::values()->get_posts_for_car_videos(),
            app::values()->get_posts_for_car_pictures(),

            app::values()->get_current_user(),

            #====content mgt related
            app::values()->get_draft_post(),
            app::values()->get_draft_posts(),

            app::values()->get_extended_post_content(),
            app::values()->get_extended_post_tokens(),
            app::values()->get_comments(),
            app::values()->get_comments_for_post(),

            
            app::values()->get_file_content(),

            app::values()->admin_get_stats_per_section(),
            app::values()->admin_get_stats_per_year(),
            app::values()->admin_get_stats_per_month(),
            app::values()->admin_get_stats_per_week(),
            app::values()->admin_get_stats_per_day(),


            //records
            app::values()->most_views(),
            app::values()->most_likes(),
            app::values()->most_comments(),
            app::values()->most_recent(),
            app::values()->oldest_pages()
            
        );
    }
    protected function defaultValue()
    {
        return "";
    }

}

class InputForFileName extends TextDefinition{
    public function getName()
    {
        return app::values()->file_name();
    }
    protected function maxLengthInChars()
    {
        return Db::max_length()->file_name();
    }
}

class InputForEntityId extends BigIntValueDefinition{
    public function getName()
    {
        return app::values()->entity_id();
    }
    protected function maxLengthInChars()
    {
        return Db::max_length()->entity_id();
    }
}

class InputForTitle extends TextDefinition{
    public function getName()
    {
        return app::values()->title();
    }
    protected function maxLengthInChars()
    {
        return Db::max_length()->title();
    }
}
class InputForYoutubeVideoId extends TextDefinition{
    public function getName()
    {
        return app::values()->youtube_video_id();
    }
    protected function maxLengthInChars()
    {
        return Db::max_length()->youtube_video_id();
    }
}

class InputForTitleArray extends InputForTitle{
    protected function should_be_array()
    {
        return true;
    }
    protected function max_number_of_items_if_array()
    {
        return app::variables()->max_num_items_for_multi_upload_of_posts();
    }
}

class InputForContent extends TextDefinition{
    public function getName()
    {
        return app::values()->content();
    }
    protected function maxLengthInChars()
    {
        return Db::max_length()->content();
    }
}
class InputForContentArray extends InputForContent{
    protected function should_be_array()
    {
        return true;
    }
    protected function max_number_of_items_if_array()
    {
        return app::variables()->max_num_items_for_multi_upload_of_posts();
    }
}

class InputForExtendedPostContent extends TextDefinition{
    public function getName()
    {
        return app::values()->extended_post_content();
    }
    protected function maxLengthInChars()
    {
        return Db::max_length()->extended_post_content();
    }
}
class InputForExtendedPostContentArray extends InputForExtendedPostContent{
    protected function should_be_array()
    {
        return true;
    }
    protected function max_number_of_items_if_array()
    {
        return app::variables()->max_num_items_for_multi_upload_of_posts();
    }
}


class InputForCategoryId extends BigIntValueDefinition{
    public function getName()
    {
        return app::values()->category_id();
    }
    protected function maxLengthInChars()
    {
        return Db::max_length()->entity_id();
    }
}
class InputForTargetPageId extends BigIntValueDefinition{
    public function getName()
    {
        return app::values()->target_page_id();
    }
    protected function maxLengthInChars()
    {
        return Db::max_length()->entity_id();
    }
}
class InputForSectionId extends BigIntValueDefinition{
    public function getName()
    {
        return app::values()->section_id();
    }
    protected function maxLengthInChars()
    {
        return Db::max_length()->entity_id();
    }
}


class InputForCarId extends BigIntValueDefinition{
    public function getName()
    {
        return app::values()->car_id();
    }
    protected function maxLengthInChars()
    {
        return Db::max_length()->entity_id();
    }
}

class InputForCarExporterId extends BigIntValueDefinition{
    public function getName()
    {
        return app::values()->car_exporter_id();
    }
    protected function maxLengthInChars()
    {
        return Db::max_length()->entity_id();
    }
}
class InputForSectionIdArray extends InputForSectionId{
    public function should_be_array()
    {
        return true;
    }
    protected function max_number_of_items_if_array()
    {
        return app::variables()->max_num_items_for_multi_upload_of_posts();
    }
}
class InputForKeywords extends TextDefinition{
    public function getName()
    {
        return app::values()->keywords();
    }
    protected function maxLengthInChars()
    {
        return Db::max_length()->keywords();
    }
}

class InputForEmailAddress extends TextDefinition{
    public function getName()
    {
        return app::values()->email_address();
    }
    protected function getPattern()
    {
        $name = "[\w_]+";
        $at = "[@]";
        $domain = "([.]$name)+";
        return join("",array($name,$at,$name,$domain));
    }

}
class InputForPassword extends Sha1HashedInput{
    public function getName()
    {
        return app::values()->password();
    }
    protected function minLengthInChars()
    {
        return 6;
    }
}


class InputForFullName extends TextDefinition{
    public function getName()
    {
        return app::values()->full_name();
    }
    protected function minLengthInChars()
    {
        return 3;
    }
}


/*
class CaptionArgument extends TextDefinition{
    public function getName()
    {
        return app::values()->caption();
    }
}

class UserIdArgument extends BigIntValueDefinition{
    public function getName()
    {
        return app::values()->user_id();
    }
}

class FullName extends TextDefinition
{
    public function getName()
    {
        return app::values()->full_name();
    }
}

class MobileNumber extends BigIntValueDefinition{
    public function getName()
    {
        return app::values()->mobile_number();
    }

}


class Username extends TextDefinition{
    public function getName()
    {
        return app::values()->file_name();
    }
    protected function minLengthInChars()
    {
        return 6;
    }
}


class PictureIdArgument extends BigIntValueDefinition{
    public function getName()
    {
        return ValueFor::picture_id();
    }
}
class PictureSizeArgument extends EnumVariable{
    public function getName()
    {
        return ValueFor::picture_size();
    }

    protected function getArrayOfAcceptableValues()
    {
        return array(ValueFor::icon(),ValueFor::small(),ValueFor::medium(),ValueFor::large());
    }
    protected function defaultValue()
    {
        return ValueFor::icon();
    }

}
*/