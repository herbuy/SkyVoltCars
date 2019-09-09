<?php

class EntityIdFromFileName{
    public static function get($file_name){
        return substr($file_name,0, stripos($file_name,"-"));
    }
}
class SelectQueryForApplication extends SQLSelectExpression{
    public function result(){
        return Db::results()->single_query($this);
    }
}
abstract class UpdateQueryForApp extends SQLUpdateQuery{
    public function result(){
        return Db::results()->single_query($this);
    }
}
abstract class DeleteQueryForMotokaviews extends SQLDeleteQuery{
    public function result(){
        return Db::results()->single_query($this);
    }
}

abstract class SQLCommandListForMotokaviews extends SQLCommandList{
    public function result(){
        return Db::results()->multi_query($this);
    }
}


#==========================

class InsertQueryForApp extends SQLInsertQuery{
    public function result(){
        return Db::results()->single_query($this);
    }
}
class QueryForCreateAccount extends InsertQueryForApp{
    public function __construct($email,$password)
    {
        //todo: car and section must exist
        parent::__construct();

        $this->insert_into(app::values()->users())->
        use_set_format()->
        set(Db::fields()->email_address()->toStringValue($email))->
        set(Db::fields()->password_hash()->toStringValue($password))->
        set(Db::fields()->entity_id()->toInt(EntityIdGenerator::newId()))->
        set(Db::fields()->timestamp()->toCurrentTimestamp())
        ;
    }

    

}


class QueryForLogin extends InsertQueryForApp{
    public function __construct($email,$password)
    {
        //todo: car and section must exist
        parent::__construct();
        
        $email = ConvertToSQLValue::ifNotSQLValue($email);
        $password = ConvertToSQLValue::ifNotSQLValue($password);
                
        $this->
        insert_into(app::values()->sessions())->
        on_duplicate_key_ignore()->
        set(Db::fields()->email_address()->toSQLValue($email))->
        set(Db::fields()->password_hash()->toSQLValue($password))->

        set(Db::fields()->entity_id()->toInt(EntityIdGenerator::newId()))->
        set(Db::fields()->session_id()->toStringValue(SecureSession::getIdAfterSha1()))->
        set(Db::fields()->timestamp()->toCurrentTimestamp())
        ;
    }
    
}


class QueryForLogout extends DeleteQueryForMotokaviews{
    public function __construct()
    {
        $this->
        delete_from(app::values()->sessions())->
        where(Db::fields()->session_id()->isEqualToString(SecureSession::getIdAfterSha1()))
        ;
    }

    public function result(){
        return Db::results()->single_query($this);
    }

}
class QueryForCurrentUser extends SelectQueryForApplication{
    public function __construct()
    {
        parent::__construct();

        $this->from(app::values()->sessions())->
        where(Db::fields()->session_id()->isEqualToString(SecureSession::getIdAfterSha1()))->
        select(Db::fields()->email_address())
        ;
    }
}

#================================================

class QueryForDeleteDraftPost extends DeleteQueryForMotokaviews{
    public function __construct($file_name)
    {
        $file_name = ConvertToSQLValue::ifNotSQLValue($file_name);
        
        $this->delete_from(app::values()->draft_posts())->
        where(Db::fields()->file_name()->isEqualTo($file_name));
    }
}

abstract class QueryForMovePost extends SQLCommandListForMotokaviews{
    protected $file_name;

    public function __construct($file_name)
    {
        parent::__construct();
        $file_name = ConvertToSQLValue::ifNotSQLValue($file_name);
        $this->file_name = $file_name;

        $this->update_its_timestamp_to_current();
        $this->recalculate_the_date_fields();
        $this->add_post_to_destination_table();
        $this->remove_post_from_source_table();
    }

    private function update_its_timestamp_to_current()
    {
        $this->add(
            (new SQLUpdateQuery())->
            update($this->source_table())->
            where($this->SQLTest())->
            set(Db::fields()->timestamp()->toCurrentTimestamp())
        );
    }

    private function recalculate_the_date_fields()
    {
        $this->add(
            new QueryForUpdateDateInformationForTableRecord($this->source_table(),$this->SQLTest())
        );
    }

    private function add_post_to_destination_table()
    {
        $this->add(
            (new SQLSelectExpression())->
            from($this->source_table())->
            where($this->SQLTest())->

            select(new SQLNull())->
            select(Db::fields()->entity_id())->
            select(Db::fields()->timestamp())->
            select(Db::fields()->file_name())->
            select(Db::fields()->title())->
            select(Db::fields()->content())->
            select(Db::fields()->category_id())->
            select(Db::fields()->page_id())->
            select(Db::fields()->section_id())->
            select(Db::fields()->picture_file_name())->
            select(Db::fields()->extended_post_content())->
            select(Db::fields()->car_id())->
            select(Db::fields()->car_exporter_id())->
            select(Db::fields()->youtube_video_id())->

            select(Db::fields()->date_in_full())->
            select(Db::fields()->year_number())->
            select(Db::fields()->month_number())->
            select(Db::fields()->month_name())->
            select(Db::fields()->month_description())->

            select(Db::fields()->week_of_the_year_number())->
            select(Db::fields()->week_of_the_year_description())->

            select(Db::fields()->day_name())->
            select(Db::fields()->day_of_the_week_number())->
            select(Db::fields()->day_of_the_month_number())->
            select(Db::fields()->day_of_the_year_number())->
            select(Db::fields()->day_of_the_year_description())->

            into($this->destination_table())->on_duplicate_key_ignore()
        );
    }    

    private function remove_post_from_source_table()
    {        
        $this->addNewDeleteQuery()->
        delete_from($this->source_table())->
        where($this->SQLTest());
    }

    protected function SQLTest()
    {
        return Db::fields()->file_name()->
        isEqualTo($this->file_name);
    }

    abstract protected function source_table();
    abstract protected function destination_table();
}

class QueryForPublishPost extends QueryForMovePost{
    protected function source_table()
    {
        return app::values()->draft_posts();
    }

    protected function destination_table()
    {
        return app::values()->posts();
    }
}

class QueryForUnPublishPost extends QueryForMovePost{
    protected function source_table()
    {
        return app::values()->posts();
    }

    protected function destination_table()
    {
        return app::values()->draft_posts();
    }
}

class QueryForPublishAllPosts extends QueryForPublishPost{
    public function __construct()
    {
        parent::__construct("");
    }
    protected function SQLTest()
    {
        return (new SQLInt(1))->isEqualToInt(1);
    }
}

/*
class QueryForPublishAllPosts extends SQLCommandListForMotokaviews{
    public function __construct()
    {
        parent::__construct();
       
        $this->add_all_posts_to_published_posts();
        $this->queryForDeleteAllDrafts();
    }

    private function add_all_posts_to_published_posts()
    {
        $this->add(
            (new SQLSelectExpression())->
            select_everything()->
            from(app::values()->draft_posts())->            
            into(app::values()->posts())->on_duplicate_key_ignore()
        );

    }

    private function queryForDeleteAllDrafts()
    {
        $this->addNewDeleteQuery()->
        delete_from(app::values()->draft_posts());
    }
}
*/
class QueryForUpdatePostPicture extends UpdateQueryForApp{
    public function __construct($post_file_name,$image_file_name)
    {
        parent::__construct();
        $this->update(app::values()->posts())->
        where(Db::fields()->file_name()->isEqualToString($post_file_name.""))->
        set(Db::fields()->picture_file_name()->toStringValue($image_file_name.""));
    }
}

class QueryForEditPostPicture extends UpdateQueryForApp{
    public function __construct($post_file_name,$image_file_name)
    {
        parent::__construct();
        $this->update(app::values()->draft_posts())->
        where(Db::fields()->file_name()->isEqualToString($post_file_name.""))->
        set(Db::fields()->picture_file_name()->toStringValue($image_file_name.""));
    }
}

class QueryForEditPostTitle extends UpdateQueryForApp{
    public function __construct($file_name, $new_title,$section_id)
    {
        parent::__construct();
        $this->update(app::values()->draft_posts())->
        where(Db::fields()->file_name()->isEqualToString($file_name.""))->
        set(Db::fields()->title()->toStringValue($new_title.""))->
        set(Db::fields()->section_id()->toInt($section_id));
    }
}

class QueryForEditPostVideo extends UpdateQueryForApp{
    public function __construct($file_name, $new_youtube_video_id)
    {
        parent::__construct();
        $this->update(app::values()->draft_posts())->
        where(Db::fields()->file_name()->isEqualToString($file_name.""))->        
        set(Db::fields()->youtube_video_id()->toStringValue($new_youtube_video_id));
    }
}

class QueryForEditPostCarId extends UpdateQueryForApp{
    public function __construct($file_name, $new_car_id)
    {
        parent::__construct();
        $this->update(app::values()->draft_posts())->
        where(Db::fields()->file_name()->isEqualToString($file_name.""))->        
        set(Db::fields()->car_id()->toInt($new_car_id));;
    }
}
class QueryForEditPostCarExporterId extends UpdateQueryForApp{
    public function __construct($file_name, $new_car_exporter_id)
    {
        parent::__construct();
        $this->update(app::values()->draft_posts())->
        where(Db::fields()->file_name()->isEqualToString($file_name.""))->
        set(Db::fields()->car_exporter_id()->toInt($new_car_exporter_id));;
    }
}

class QueryForEditPostContent extends UpdateQueryForApp{
    public function __construct($file_name, $new_title)
    {
        parent::__construct();
        $this->update(app::values()->draft_posts())->
        where(Db::fields()->file_name()->isEqualToString($file_name.""))->
        set(Db::fields()->content()->toStringValue($new_title.""));
    }
}


class QueryForGetCategories  extends SelectQueryForApplication{
    public function __construct()
    {
        parent::__construct();
        $this->from(app::values()->categories());
        $this->order_by(Db::fields()->title()->ascending());
    }
}
class QueryForGetSections  extends SelectQueryForApplication{
    public function __construct()
    {
        parent::__construct();
        $this->from(app::values()->sections());
        $this->order_by(Db::fields()->title()->ascending());
    }
}
class QueryForGetPages  extends SelectQueryForApplication{
    public function __construct()
    {
        parent::__construct();
        $this->from(app::values()->pages());
        $this->order_by(Db::fields()->title()->ascending());
    }
}
abstract class BaseClassForQueryForGetPosts extends SelectQueryForApplication{
    protected $sql_test;
    protected function getSQLTest()
    {
        return $this->sql_test;
    }
    
    public function __construct()
    {
        parent::__construct();
        $this->from($this->getTableName());
        $this->order_by(Db::fields()->timestamp()->descending());
        $this->selectFields();

        $test = $this->getSQLTest();
        if($test){
            $this->where($test);
        }
    }

    abstract protected function getTableName();

    protected function selectFields()
    {
        $this->select_everything();
        $this->select_views();
        $this->select_likes();
        $this->select_comments();
        //$this->select_author_name();
    }

    private function select_views()
    {
        $this->select(
            Db::computed_values()->total_views_on_post(
                Db::fields()->file_name()->inTable($this->getTableName())
            )->as_(app::values()->views())
        );
    }
    private function select_likes()
    {
        $this->select(
            Db::computed_values()->total_likes_on_post(
                Db::fields()->file_name()->inTable($this->getTableName())
            )->as_(app::values()->likes())
        );
    }
    private function select_comments()
    {
        $this->select(
            Db::computed_values()->total_comments_on_post(
                Db::fields()->file_name()->inTable($this->getTableName())
            )->as_(app::values()->comments())
        );
    }
    private function select_author_name()
    {
        $this->select(
            Db::computed_values()->full_name_for_id(
                Db::fields()->author_id()->inTable($this->getTableName())
            )->as_(app::values()->author_name())
        );
    }

}
class QueryForGetPosts extends BaseClassForQueryForGetPosts{
    protected function getTableName()
    {
        return app::values()->posts();
    }
}

class QueryForGetFileContent extends QueryForGetPosts{
    protected function selectFields()
    {
        $this->select(Db::fields()->file_name());
    }
}
abstract class QueryForGetPostsInSection extends QueryForGetPosts{
    public function __construct()
    {
        $this->sql_test = Db::fields()->section_id()->isEqualToInt($this->section_id());
        $this->limit(0,24);
        parent::__construct();
    }

    abstract protected function section_id();
}
class QueryForGetPostsInMainSection extends QueryForGetPostsInSection{
    protected function section_id()
    {
        return Db::section_ids()->car_reviews();
    }
}
class QueryForGetPostsForExporterReviews extends QueryForGetPostsInSection{
    protected function section_id()
    {
        return Db::section_ids()->exporter_reviews();
        //return Db::section_ids()->car_reviews();
    }
}

//=========================
class QueryForGetPostsForCarExporters extends QueryForGetPostsInSection{
    protected function section_id()
    {
        return Db::section_ids()->car_exporters();
        //return Db::section_ids()->car_reviews();
    }
}
class QueryForGetPostsForNews extends QueryForGetPostsInSection{
    protected function section_id()
    {
        return Db::section_ids()->news();
        //return Db::section_ids()->car_reviews();
    }
}
class QueryForGetPostsForCareers extends QueryForGetPostsInSection{
    protected function section_id()
    {
        return Db::section_ids()->careers();
        //return Db::section_ids()->car_reviews();
    }
}
class QueryForGetPostsForCarMaintenance extends QueryForGetPostsInSection{
    protected function section_id()
    {
        return Db::section_ids()->car_maintenance();
        //return Db::section_ids()->car_reviews();
    }
}

class QueryForGetCars extends QueryForGetPostsInSection{
    protected function section_id()
    {        
        return Db::section_ids()->cars();
    }
}
class QueryForGetCarPictures extends QueryForGetPostsInSection{
    protected function section_id()
    {
        return Db::section_ids()->car_pictures();
        //return Db::section_ids()->car_reviews();
    }
}
class QueryForGetCarVideos extends QueryForGetPostsInSection{
    protected function section_id()
    {
        return Db::section_ids()->car_videos();
    }
}

class QueryForGetExtendedPostContent extends SelectQueryForApplication{
    public function __construct($file_name)
    {
        parent::__construct();

        $entity_id = ConvertToSQLValue::ifNotSQLValue( EntityIdFromFileName::get($file_name) );
        $this->from(app::values()->draft_posts())->
        where(Db::fields()->entity_id()->isEqualTo($entity_id))->
        select(Db::fields()->extended_post_content())->
        limit(0,1);
    }
}

class QueryForGetExtendedPostTokens extends SelectQueryForApplication{
    public function __construct($file_name)
    {
        parent::__construct();

        $entity_id = ConvertToSQLValue::ifNotSQLValue( EntityIdFromFileName::get($file_name) );
        $this->from(app::values()->extended_post_content())->
        where(Db::fields()->entity_id()->isEqualTo($entity_id))->
        select_everything();
    }
}
//==========================

class QueryForGetMostRecentPostPerCategory extends BaseClassForQueryForGetPosts{
    public function __construct()
    {
        parent::__construct();
        $this->select(
            Db::computed_values()->
            category_name_from_id(
                Db::fields()->category_id()->inTable(app::values()->most_recent_post_per_category())
            )->as_(app::values()->category())
        );
    }

    protected function getTableName()
    {
        return app::values()->most_recent_post_per_category();
    }
}

class QueryForGetPost extends QueryForGetPosts{
    
    public function __construct($file_name)
    {

        $file_name = ConvertToSQLValue::ifNotSQLValue($file_name."");
        $this->sql_test = Db::fields()->file_name()->isEqualTo($file_name); 
        parent::__construct();
    }
    
}

class QueryForGetDraftPosts extends BaseClassForQueryForGetPosts{
    protected function getTableName()
    {
        return app::values()->draft_posts();
    }
}

class QueryForGetDraftPost extends QueryForGetDraftPosts{

    public function __construct($file_name)
    {

        $file_name = ConvertToSQLValue::ifNotSQLValue($file_name."");
        $this->sql_test = Db::fields()->file_name()->isEqualTo($file_name);

        parent::__construct();

    }

}

class QueryForGetMostRecentPost extends QueryForGetPosts{
    public function __construct()
    {
        parent::__construct();
        $this->limit(0,1);
    }
}

class QueryForAddPost extends InsertQueryForApp{
    public function __construct($file_name, $entity_id, $title, $content,$category_id,$page_id,$section_id)
    {        
        parent::__construct();

        $this->insert_into(app::values()->posts())->
        use_set_format()->
        set(Db::fields()->file_name()->toStringValue($file_name))->
        set(Db::fields()->entity_id()->toStringValue($entity_id))->
        set(Db::fields()->timestamp()->toCurrentTimestamp())->
        set(Db::fields()->title()->toStringValue($title))->
        set(Db::fields()->content()->toStringValue($content))->
        set(Db::fields()->category_id()->toInt($category_id))->
        set(Db::fields()->section_id()->toInt($section_id))->
        set(Db::fields()->page_id()->toInt($page_id));
    }

}

class QueryForStartNewPost extends InsertQueryForApp{
    public function __construct($file_name, $entity_id, $title,$content="No content in this post",$extended_post_content='',$section_id=0,$car_id=0,$car_exporter_id=0)
    {
        //todo: car and section must exist
        parent::__construct();

        $this->insert_into(app::values()->draft_posts())->
        use_set_format()->
        set(Db::fields()->file_name()->toStringValue($file_name))->
        set(Db::fields()->entity_id()->toStringValue($entity_id))->
        set(Db::fields()->timestamp()->toCurrentTimestamp())->
        set(Db::fields()->title()->toStringValue($title))->
        set(Db::fields()->content()->toStringValue($content))->
        set(Db::fields()->extended_post_content()->toStringValue($extended_post_content))->
        set(Db::fields()->section_id()->toInt($section_id))->
        set(Db::fields()->car_id()->toInt($car_id))->
        set(Db::fields()->car_exporter_id()->toInt($car_exporter_id));
        ;

        //compute date values
        $this->addDateInformation();
    }
    
    private function addDateInformation()
    {
        $date_in_full = date("Y-m-d");
        $this
            ->set(Db::fields()->date_in_full()->toStringValue(
                $date_in_full)
            )
            ->set(Db::fields()->year_number()->toSQLValue(
                SQLFunction::year($date_in_full)
            ))
            ->set(Db::fields()->month_number()->toSQLValue(
                SQLFunction::month($date_in_full)
            ))
            ->set(Db::fields()->month_name()->toSQLValue(
                SQLFunction::month_name($date_in_full)
            ))
            ->set(Db::fields()->month_description()->toSQLValue(
            //Db::fields()->car_id()->append(Db::fields()->alt())
                SQLFunction::month_name($date_in_full)->append(
                    " "
                )->
                append(
                    SQLFunction::year($date_in_full)
                )
            ))
            ->set(Db::fields()->week_of_the_year_number()->toSQLValue(
                SQLFunction::week_of_year($date_in_full)
            ))
            ->set(Db::fields()->week_of_the_year_description()->toSQLValue(
                SQLFunction::week_of_year_description($date_in_full)
            ))
            ->set(Db::fields()->day_name()->toSQLValue(
                SQLFunction::day_name($date_in_full)
            ))
            ->set(Db::fields()->day_of_the_week_number()->toSQLValue(
                SQLFunction::day_of_week($date_in_full)
            ))
            ->set(Db::fields()->day_of_the_month_number()->toSQLValue(
                SQLFunction::day($date_in_full)
            ))
            ->set(Db::fields()->day_of_the_year_number()->toSQLValue(
                SQLFunction::day_of_year($date_in_full)
            ))
            ->set(Db::fields()->day_of_the_year_description()->toSQLValue(
                SQLFunction::day_of_year_description($date_in_full)
            ));
    }
}
class SQLAlterTablesWithPosts extends SQLCommandList{
    public function __construct()
    {
        parent::__construct();
        $this->add(
            $this->dateRelatedColumnsForTable(app::values()->draft_posts())
        );
        $this->add(
            $this->dateRelatedColumnsForTable(app::values()->posts())
        );
        $this->add(
            $this->dateRelatedColumnsForTable(app::values()->most_recent_post_per_category())
        );

    }

    private function dateRelatedColumnsForTable($table_name)
    {
        $sql = new SQLAlterTable($table_name);
        $sql
            #------- ADD DATE COLUMNS ---------
            ->add_column(SQLCreate::column(app::values()->date_in_full())->varchar(Db::max_length()->date_in_full())->not_null())
            ->add_column(SQLCreate::column(app::values()->year_number())->int(Db::max_length()->year_number())->not_null()->unsigned())
            ->add_column(SQLCreate::column(app::values()->month_number())->int(Db::max_length()->month_number())->not_null()->unsigned())
            ->add_column(SQLCreate::column(app::values()->month_name())->varchar(Db::max_length()->month_name())->not_null())
            ->add_column(SQLCreate::column(app::values()->month_description())->varchar(Db::max_length()->month_description())->not_null())
            ->add_column(SQLCreate::column(app::values()->week_of_the_year_number())->int(Db::max_length()->week_of_the_year_number())->not_null()->unsigned())
            ->add_column(SQLCreate::column(app::values()->week_of_the_year_description())->varchar(Db::max_length()->week_of_the_year_description())->not_null())
            ->add_column(SQLCreate::column(app::values()->day_name())->varchar(Db::max_length()->day_name())->not_null())
            ->add_column(SQLCreate::column(app::values()->day_of_the_week_number())->int(Db::max_length()->day_of_the_week_number())->not_null()->unsigned())
            ->add_column(SQLCreate::column(app::values()->day_of_the_month_number())->int(Db::max_length()->day_of_the_month_number())->not_null()->unsigned())
            ->add_column(SQLCreate::column(app::values()->day_of_the_year_number())->int(Db::max_length()->day_of_the_year_number())->not_null()->unsigned())
            ->add_column(SQLCreate::column(app::values()->day_of_the_year_description())->varchar(Db::max_length()->day_of_the_year_description())->not_null());

        return $sql;
    }
}
class QueryForUpdateDateInformationForTable extends UpdateQueryForApp{
    public function __construct($table)
    {
        parent::__construct();


        $date_in_full = SQLFunction::from_unixtime(Db::fields()->timestamp());
        
        $this->update($table);

        $this
            ->set(Db::fields()->date_in_full()->toSQLValue(
                $date_in_full)
            )
            ->set(Db::fields()->year_number()->toSQLValue(
                SQLFunction::year($date_in_full)
            ))
            ->set(Db::fields()->month_number()->toSQLValue(
                SQLFunction::month($date_in_full)
            ))
            ->set(Db::fields()->month_name()->toSQLValue(
                SQLFunction::month_name($date_in_full)
            ))
            ->set(Db::fields()->month_description()->toSQLValue(
            //Db::fields()->car_id()->append(Db::fields()->alt())
                SQLFunction::month_name($date_in_full)->append(
                    " "
                )->
                append(
                    SQLFunction::year($date_in_full)
                )
            ))
            ->set(Db::fields()->week_of_the_year_number()->toSQLValue(
                SQLFunction::week_of_year($date_in_full)
            ))
            ->set(Db::fields()->week_of_the_year_description()->toSQLValue(
                SQLFunction::week_of_year_description($date_in_full)
            ))
            ->set(Db::fields()->day_name()->toSQLValue(
                SQLFunction::day_name($date_in_full)
            ))
            ->set(Db::fields()->day_of_the_week_number()->toSQLValue(
                SQLFunction::day_of_week($date_in_full)
            ))
            ->set(Db::fields()->day_of_the_month_number()->toSQLValue(
                SQLFunction::day($date_in_full)
            ))
            ->set(Db::fields()->day_of_the_year_number()->toSQLValue(
                SQLFunction::day_of_year($date_in_full)
            ))
            ->set(Db::fields()->day_of_the_year_description()->toSQLValue(
                SQLFunction::day_of_year_description($date_in_full)
            ));
        
    }
}
class QueryForUpdateDateInformationForTableRecord extends QueryForUpdateDateInformationForTable{
    public function __construct($table, $sql_test)
    {
        SQLBuilderException::throwIfNotSQLTest($sql_test);

        parent::__construct($table);        
        $this->where($sql_test);
    }
}

class QueryForUpdateOldPostsWithDateInformationFromTimestamp extends SQLCommandList{
    public function __construct()
    {
        parent::__construct();

        $this->add( new QueryForUpdateDateInformationForTable(app::values()->draft_posts()) );
        $this->add( new QueryForUpdateDateInformationForTable(app::values()->posts()) );
        $this->add( new QueryForUpdateDateInformationForTable(app::values()->most_recent_post_per_category()) );
    }
}
abstract class QueryForGetStatsPerAttribute extends SelectQueryForApplication{
    public function __construct()
    {
        parent::__construct();

        $this->from(
            //this first query is simply for sorting using descending order of timestamp
            (new SQLSelectExpression())->
            select_everything()->
            from($this->getTableName())->
            order_by($this->order_by_clause())->as_("my_table")
        );
        //$this->where(Db::fields()->timestamp()->isNotNull());
        $this->group_by($this->columnToGroupBy());
        
        $this->select_field($this->columnToGroupBy());
        $this->select(Db::fields()->entity_id()->count()->as_(app::values()->total_posts()));
        $this->do_additional_select();

        //print $this;exit;
    }

    #can be overridden
    protected function getTableName()
    {
        return app::values()->posts();
    }

    abstract protected function columnToGroupBy();
    protected function do_additional_select(){        
    }

    abstract protected function order_by_clause();
}
class QueryForGetStatsPerSection extends QueryForGetStatsPerAttribute{
    protected function columnToGroupBy()
    {
        return app::values()->section_id();
    }
    protected function do_additional_select()
    {
        $this->select($this->queryForSectionTitle());                
    }

    private function queryForSectionTitle()
    {
        return (new SelectQueryForApplication())->
        select(Db::fields()->title())->
        from(app::values()->sections())->
        where(Db::fields()->entity_id()->isEqualTo(Db::fields()->section_id()))->
        as_(Db::fields()->title());
    }
    protected function order_by_clause()
    {
        return Db::fields()->timestamp()->descending();
    }
}
class QueryForGetStatsPerYear extends QueryForGetStatsPerAttribute{
    protected function columnToGroupBy()
    {
        return app::values()->year_number();
    }
    protected function order_by_clause()
    {
        return Db::fields()->year_number()->descending();
    }
}
class QueryForGetStatsPerMonth extends QueryForGetStatsPerAttribute{
    protected function columnToGroupBy()
    {
        return app::values()->month_description();
    }
    protected function order_by_clause()
    {
        return Db::fields()->year_number()->descending()->then_by(Db::fields()->month_number()->descending());
    }
}
class QueryForGetStatsPerWeek extends QueryForGetStatsPerAttribute{
    protected function columnToGroupBy()
    {
        return app::values()->week_of_the_year_description();
    }
    protected function order_by_clause()
    {
        return Db::fields()->year_number()->descending()->then_by(Db::fields()->week_of_the_year_number()->descending());
    }
}
class QueryForGetStatsPerDay extends QueryForGetStatsPerAttribute{
    protected function columnToGroupBy()
    {
        return app::values()->day_of_the_year_description();
    }
    protected function order_by_clause()
    {
        return Db::fields()->year_number()->descending()->then_by(Db::fields()->day_of_the_year_number()->descending());
    }
}

class QueryForCreateMultiplePosts extends SQLCommandListForMotokaviews{
    
    public function __construct($title_array,$content_array,$extended_post_content_array,$section_id_array)
    {
        parent::__construct();

        if(
            count($title_array) == count($content_array) &&
            count($content_array) == count($extended_post_content_array)
        ){

            $total_records_submitted = count($title_array);
            for($record_number = 0; $record_number < $total_records_submitted;$record_number++){
                $title = $title_array[$record_number];
                $content = $content_array[$record_number];
                $extended_post_content = $extended_post_content_array[$record_number]; 
                $section_id = $section_id_array[$record_number];
                
                $entity_id = EntityIdGenerator::newId();
                
                $this->add(
                    Db::queries()->start_new_post(
                        FileNameGenerator::generate($entity_id,$title,""),
                        $entity_id,$title,$content,$extended_post_content,$section_id
                    )
                    /*
                    (new SQLInsertQuery())->
                    use_set_format()->
                    insert_into("draft_posts")->
                    set($this->printField("title", $record_number))->
                    set($this->printField("content", $record_number))->
                    set($this->printField("extended_post_content", $record_number))
                    */

                );
            }
        }
        else{
            throw new Exception("mismatch in number of inputs provided for title, introduction and full details");
        }
    }

    /*
    private function getValueSubmitted($fieldName, $record_number)
    {
        return @$_REQUEST[$fieldName][$record_number];
    }

    private function printField($field_name, $record_number)
    {
        $db_field = new SQLIdentifier($field_name);
        return $db_field->toSQLValue(
            ConvertToSQLValue::ifNotSQLValue(
                $this->getValueSubmitted($field_name, $record_number)
            )
        );
    }*/
}

//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@

class QueryForPostComment extends InsertQueryForApp{
    //todo: all apps use this query to return the tokens [parts] of the extended content. Useful, for instance, to display the rich text on the post details page.

    public function __construct($file_name,$content,$full_name,$email)
    {
        parent::__construct();

        $entity_id = ConvertToSQLValue::ifNotSQLValue(EntityIdFromFileName::get($file_name.""));

        $this->insert_into(app::values()->comments())->
        set(Db::fields()->entity_id()->toSQLValue(EntityIdFromFileName::get($file_name)))->
        set(Db::fields()->timestamp()->toCurrentTimestamp())->
        set(Db::fields()->session_id_hash()->toStringValue(SecureSession::getIdAfterSha1()))->
        set(Db::fields()->full_name()->toStringValue($full_name))->
        set(Db::fields()->email_address()->toStringValue($email))->
        set(Db::fields()->file_name()->toStringValue($file_name))->
        set(Db::fields()->content()->toStringValue($content))

        ;
    }
}

class QueryForGetComments extends SelectQueryForApplication{

    public function __construct()
    {
        parent::__construct();
        $this->from(app::values()->comments())->
        select_everything();
    }
}
class QueryForGetCommentForPost extends SelectQueryForApplication{
    //todo: all apps use this query to return the tokens [parts] of the extended content. Useful, for instance, to display the rich text on the post details page.

    public function __construct($file_name)
    {
        parent::__construct();

        $entity_id = ConvertToSQLValue::ifNotSQLValue(EntityIdFromFileName::get($file_name.""));

        $this->from(app::values()->comments())->
        where(Db::fields()->entity_id()->isEqualTo($entity_id))->
        select_everything();
    }
}
abstract class QueryForUpdateCommentApprovalStatus extends UpdateQueryForApp{
    public function __construct($entity_id)
    {
        parent::__construct();
        $this->
        update(app::values()->comments())->
        where(Db::fields()->entity_id()->isEqualTo($entity_id))->
        set(Db::fields()->approval_status_code()->toStringValue($this->new_approval_status()));
    }

    abstract protected function new_approval_status();
}
class QueryForApproveComment extends QueryForUpdateCommentApprovalStatus{
    protected function new_approval_status()
    {
        return app::approval_status_codes()->approved();
    }
}

class QueryForMoveCommentToTrash extends QueryForUpdateCommentApprovalStatus{
    protected function new_approval_status()
    {
        return app::approval_status_codes()->trashed();
    }
}

class QueryForMoveCommentToSpam extends QueryForUpdateCommentApprovalStatus{
    protected function new_approval_status()
    {
        return app::approval_status_codes()->spam();
    }
}

class QueryForLikeThePost extends InsertQueryForApp{
    public function __construct($file_name)
    {
        parent::__construct();
        $hash_code = sha1(
            ConvertToSQLValue::ifNotSQLValue(SecureSession::getIdAfterSha1())->
            append("-")->
            append(EntityIdFromFileName::get($file_name))
        );

        $this->insert_into(app::values()->likes())->
        set(Db::fields()->hash_code()->toSQLValue($hash_code))->
        set(Db::fields()->timestamp()->toCurrentTimestamp())->
        set(Db::fields()->file_name()->toSQLValue($file_name))->
        set(Db::fields()->session_id_hash()->toSQLValue(SecureSession::getIdAfterSha1()))
        ;
    }
}
class QueryForRegisterViewForThePost extends InsertQueryForApp{
    public function __construct($file_name)
    {
        parent::__construct();
        $this->insert_into(app::values()->views())->
        set(Db::fields()->timestamp()->toCurrentTimestamp())->
        set(Db::fields()->file_name()->toSQLValue($file_name))->
        set(Db::fields()->session_id_hash()->toSQLValue(SecureSession::getIdAfterSha1()))
        ;
    }
}
class QueryForPagesWithMostViews extends SelectQueryForApplication{
    public function __construct()
    {
        parent::__construct();
        $this->from(app::values()->posts());
        $this->limit(0,6);
    }
}
class QueryForPagesWithMostLikes extends SelectQueryForApplication{
    public function __construct()
    {
        parent::__construct();
        $this->from(app::values()->posts());
        $this->limit(0,6);
    }
}
class QueryForPagesWithMostComments extends SelectQueryForApplication{
    public function __construct()
    {
        parent::__construct();
        $this->from(app::values()->posts());
        $this->limit(0,6);
    }
}
class QueryForOldestPages extends SelectQueryForApplication{
    public function __construct()
    {
        //useful for retiring content
        parent::__construct();
        $this->from(app::values()->posts());
        $this->limit(0,6);
    }
}

class QueryForMostRecentPages extends SelectQueryForApplication{
    public function __construct()
    {
        parent::__construct();
        $this->from(app::values()->posts());
        $this->limit(0,6);
    }
}


class QueryForCreateTables extends SQLCommandList{
    public function __construct()
    {
        parent::__construct();

        $this->create_tables();
        $this->insert_default_data();
    }

    public function result(){
        return Db::results()->multi_query($this);
    }

    private function tableForPosts()
    {
        return $this->baseTableForPosts(app::values()->posts());
    }

    private function tableForMostRecentPostPerCategory()
    {
        $table = $this->baseTableForPosts(app::values()->most_recent_post_per_category());
        $table->add_unique_key(SQLCreate::unique_key(app::values()->category_id())->addColumn(app::values()->category_id()));
        return $table;
    }
    private function tableForDraftPosts()
    {
        $table = $this->baseTableForPosts(app::values()->draft_posts());
        return $table;
    }

    private function tableForCategories()
    {
        return SQLCreate::table_if_not_exists(app::values()->categories())          
            ->add_primary_key(SQLCreate::primary_key()->addColumn(app::values()->row_id()))
            ->addColumn(SQLCreate::column(app::values()->row_id())->bigint(Db::max_length()->row_id())->unsigned()->not_null()->auto_increment())
            ->addColumn(SQLCreate::column(app::values()->entity_id())->bigint(Db::max_length()->entity_id())->unsigned()->not_null())
            ->addColumn(SQLCreate::column(app::values()->timestamp())->int(Db::max_length()->timestamp())->unsigned()->not_null())
            ->addColumn(SQLCreate::column(app::values()->title())->varchar(Db::max_length()->title())->not_null());
    }
    private function insert_default_category($title){
        $query = new SQLInsertQuery();
        $query->use_set_format();

        $query->insert_into(app::values()->categories())->
        set(Db::fields()->entity_id()->toInt(EntityIdGenerator::newId()))->
        set(Db::fields()->timestamp()->toCurrentTimestamp())->
        set(Db::fields()->title()->toStringValue($title));
        return $query;
    }

    private function insert_default_page($page_id,$title){
        $query = new SQLInsertQuery();
        $query->use_set_format();

        $query->insert_into(app::values()->pages())->
        set(Db::fields()->entity_id()->toInt($page_id))->
        set(Db::fields()->timestamp()->toCurrentTimestamp())->
        set(Db::fields()->title()->toStringValue($title));
        return $query;
    }

    private function insert_default_section($section_id,$title){
        $query = new SQLInsertQuery();
        $query->use_set_format();

        $query->insert_into(app::values()->sections())->
        set(Db::fields()->entity_id()->toInt($section_id))->
        set(Db::fields()->timestamp()->toCurrentTimestamp())->
        set(Db::fields()->title()->toStringValue($title));
        return $query;
    }

    private function insert_default_user($user_id, $email,$password){
        $query = new SQLInsertQuery();
        $query->use_set_format();

        $query->insert_into(app::values()->users())->
        set(Db::fields()->entity_id()->toInt($user_id))->
        set(Db::fields()->timestamp()->toCurrentTimestamp())->
        set(Db::fields()->email_address()->toStringValue($email))->
        set(Db::fields()->password_hash()->toStringValue($password))
        ;
        return $query;
    }

    private function tableForPages()
    {
        return SQLCreate::table_if_not_exists(app::values()->pages())
            ->add_primary_key(SQLCreate::primary_key()->addColumn(app::values()->row_id()))
            ->addColumn(SQLCreate::column(app::values()->row_id())->bigint(Db::max_length()->row_id())->unsigned()->not_null()->auto_increment())
            ->addColumn(SQLCreate::column(app::values()->entity_id())->bigint(Db::max_length()->entity_id())->unsigned()->not_null())
            ->addColumn(SQLCreate::column(app::values()->timestamp())->int(Db::max_length()->timestamp())->unsigned()->not_null())
            ->addColumn(SQLCreate::column(app::values()->title())->varchar(Db::max_length()->title())->not_null());
    }

    private function tableForSections()
    {
        return SQLCreate::table_if_not_exists(app::values()->sections())
            ->add_primary_key(SQLCreate::primary_key()->addColumn(app::values()->row_id()))
            ->addColumn(SQLCreate::column(app::values()->row_id())->bigint(Db::max_length()->row_id())->unsigned()->not_null()->auto_increment())
            ->addColumn(SQLCreate::column(app::values()->entity_id())->bigint(Db::max_length()->entity_id())->unsigned()->not_null())
            ->addColumn(SQLCreate::column(app::values()->timestamp())->int(Db::max_length()->timestamp())->unsigned()->not_null())
            ->addColumn(SQLCreate::column(app::values()->title())->varchar(Db::max_length()->title())->not_null());
    }


    private function baseTableForPosts($table_name)
    {
        return SQLCreate::table_if_not_exists($table_name)
            ->add_primary_key(SQLCreate::primary_key()->addColumn(app::values()->row_id()))
            ->addColumn(SQLCreate::column(app::values()->row_id())->bigint(Db::max_length()->row_id())->unsigned()->not_null()->auto_increment())
            ->addColumn(SQLCreate::column(app::values()->entity_id())->bigint(Db::max_length()->entity_id())->unsigned()->not_null())
            ->addColumn(SQLCreate::column(app::values()->timestamp())->int(Db::max_length()->timestamp())->unsigned()->not_null())
            ->addColumn(SQLCreate::column(app::values()->file_name())->varchar(Db::max_length()->file_name())->not_null())
            ->addColumn(SQLCreate::column(app::values()->title())->varchar(Db::max_length()->title())->not_null())
            ->addColumn(SQLCreate::column(app::values()->content())->varchar(Db::max_length()->content())->not_null())
            ->addColumn(SQLCreate::column(app::values()->category_id())->bigint(Db::max_length()->entity_id())->unsigned()->not_null())

            ->addColumn(SQLCreate::column(app::values()->page_id())->bigint(Db::max_length()->entity_id())->unsigned()->not_null())
            ->addColumn(SQLCreate::column(app::values()->section_id())->bigint(Db::max_length()->entity_id())->unsigned()->not_null())
            ->addColumn(SQLCreate::column(app::values()->picture_file_name())->varchar(Db::max_length()->file_name())->not_null())
            ->addColumn(SQLCreate::column(app::values()->extended_post_content())->varchar(Db::max_length()->extended_post_content())->not_null())

            ->addColumn(SQLCreate::column(app::values()->car_id())->bigint(Db::max_length()->entity_id())->unsigned()->not_null())
            ->addColumn(SQLCreate::column(app::values()->car_exporter_id())->bigint(Db::max_length()->entity_id())->unsigned()->not_null())
            ->addColumn(SQLCreate::column(app::values()->youtube_video_id())->varchar(Db::max_length()->youtube_video_id())->not_null())

            #------- ADD DATE COLUMNS ---------
            ->addColumn(SQLCreate::column(app::values()->date_in_full())->varchar(Db::max_length()->date_in_full())->not_null())
            ->addColumn(SQLCreate::column(app::values()->year_number())->int(Db::max_length()->year_number())->not_null()->unsigned())
            ->addColumn(SQLCreate::column(app::values()->month_number())->int(Db::max_length()->month_number())->not_null()->unsigned())
            ->addColumn(SQLCreate::column(app::values()->month_name())->varchar(Db::max_length()->month_name())->not_null())
            ->addColumn(SQLCreate::column(app::values()->month_description())->varchar(Db::max_length()->month_description())->not_null())
            ->addColumn(SQLCreate::column(app::values()->week_of_the_year_number())->int(Db::max_length()->week_of_the_year_number())->not_null()->unsigned())
            ->addColumn(SQLCreate::column(app::values()->week_of_the_year_description())->varchar(Db::max_length()->week_of_the_year_description())->not_null())
            ->addColumn(SQLCreate::column(app::values()->day_name())->varchar(Db::max_length()->day_name())->not_null())
            ->addColumn(SQLCreate::column(app::values()->day_of_the_week_number())->int(Db::max_length()->day_of_the_week_number())->not_null()->unsigned())
            ->addColumn(SQLCreate::column(app::values()->day_of_the_month_number())->int(Db::max_length()->day_of_the_month_number())->not_null()->unsigned())
            ->addColumn(SQLCreate::column(app::values()->day_of_the_year_number())->int(Db::max_length()->day_of_the_year_number())->not_null()->unsigned())
            ->addColumn(SQLCreate::column(app::values()->day_of_the_year_description())->varchar(Db::max_length()->day_of_the_year_description())->not_null())

            ;
    }

    private function tableForPostContent()
    {
        return SQLCreate::table_if_not_exists(app::values()->extended_post_content())
            ->add_primary_key(SQLCreate::primary_key()->addColumn(app::values()->row_id()))
            ->addColumn(SQLCreate::column(app::values()->row_id())->bigint(Db::max_length()->row_id())->unsigned()->not_null()->auto_increment())
            ->addColumn(SQLCreate::column(app::values()->entity_id())->bigint(Db::max_length()->entity_id())->unsigned()->not_null())
            ->addColumn(SQLCreate::column(app::values()->timestamp())->int(Db::max_length()->timestamp())->unsigned()->not_null())
            ->addColumn(SQLCreate::column(app::values()->content())->varchar(Db::max_length()->content())->not_null())
            ->addColumn(SQLCreate::column(app::values()->content_type())->varchar(Db::max_length()->content_type())->not_null())
            ->addColumn(SQLCreate::column(app::values()->src())->varchar(Db::max_length()->src())->not_null())
            ->addColumn(SQLCreate::column(app::values()->alt())->varchar(Db::max_length()->alt())->not_null())
            ->addColumn(SQLCreate::column(app::values()->href())->varchar(Db::max_length()->href())->not_null())
            ->addColumn(SQLCreate::column(app::values()->width())->varchar(Db::max_length()->width_in_tbl_for_extended_content())->not_null())
            ->addColumn(SQLCreate::column(app::values()->height())->varchar(Db::max_length()->height_in_tbl_for_extended_content())->not_null())
            ->addColumn(SQLCreate::column(app::values()->rating())->varchar(Db::max_length()->content())->not_null())
            ->addColumn(SQLCreate::column(app::values()->title())->varchar(Db::max_length()->title())->not_null())
            ;
    }

    private function tableForUsers()
    {
        return $this->baseTableForUsers(app::values()->users());
    }

    private function tableForSessions()
    {
        return $this->baseTableForUsers(app::values()->sessions())->
        addColumn(SQLCreate::column(app::values()->session_id())->varchar(Db::max_length()->session_id())->not_null())->
        add_unique_key(app::values()->session_id());
    }

    private function baseTableForUsers($table_name)
    {
        return SQLCreate::table_if_not_exists($table_name)
            ->add_primary_key(SQLCreate::primary_key()->addColumn(app::values()->row_id()))
            ->addColumn(SQLCreate::column(app::values()->row_id())->bigint(Db::max_length()->row_id())->unsigned()->not_null()->auto_increment())
            ->addColumn(SQLCreate::column(app::values()->entity_id())->bigint(Db::max_length()->entity_id())->unsigned()->not_null())
            ->addColumn(SQLCreate::column(app::values()->timestamp())->int(Db::max_length()->timestamp())->unsigned()->not_null())
            ->addColumn(SQLCreate::column(app::values()->email_address())->varchar(Db::max_length()->email_address())->not_null())
            ->addColumn(SQLCreate::column(app::values()->password_hash())->varchar(Db::max_length()->password_hash())->not_null());
    }

    private function insert_default_categories()
    {
        $this->add($this->insert_default_category("Car Reviews"));
        $this->add($this->insert_default_category("Tips and Advice"));
        $this->add($this->insert_default_category("Quick Facts"));
        $this->add($this->insert_default_category("News"));
        $this->add($this->insert_default_category("Expert Opinions"));
    }

    private function insert_default_pages()
    {
        $this->add($this->insert_default_page(Db::page_ids()->home(), "Home page"));
        $this->add($this->insert_default_page(Db::page_ids()->post_details(), "Post details Page"));
    }

    private function insert_default_sections()
    {
        $this->add($this->insert_default_section(Db::section_ids()->car_reviews(), "Car Reviews"));
        $this->add($this->insert_default_section(Db::section_ids()->exporter_reviews(), "Exporter reviews"));
        $this->add($this->insert_default_section(Db::section_ids()->car_exporters(), "Car exporters"));
        $this->add($this->insert_default_section(Db::section_ids()->news(), "News & Events"));
        $this->add($this->insert_default_section(Db::section_ids()->careers(), "Job opportunities"));
        $this->add($this->insert_default_section(Db::section_ids()->car_maintenance(), "Car maintenance"));
        $this->add($this->insert_default_section(Db::section_ids()->cars(), "Cars"));
        $this->add($this->insert_default_section(Db::section_ids()->car_videos(), "Videos"));
        $this->add($this->insert_default_section(Db::section_ids()->car_pictures(), "Pictures"));
    }

    private function insert_default_users()
    {
        $this->add($this->insert_default_user(EntityIdGenerator::newId(), "motokaviews@gmail.com", sha1("10#11#12#")));
    }

    private function insert_default_data()
    {
        $this->insert_default_users();
        $this->insert_default_categories();
        $this->insert_default_pages();
        $this->insert_default_sections();
    }

    private function create_tables()
    {
        $this->create_reference_tables();
        $this->create_transactional_tables();
    }

    private function create_reference_tables()
    {
        $this->add($this->tableForUsers());
        $this->add($this->tableForSessions());
        $this->add($this->tableForCategories());
        $this->add($this->tableForPages());
        $this->add($this->tableForSections());
    }

    private function create_transactional_tables()
    {
        $this->add($this->tableForDraftPosts());
        $this->add($this->tableForPosts());
        $this->add($this->tableForMostRecentPostPerCategory());
        $this->add($this->tableForPostContent());
        $this->comments();
        $this->likes();
        $this->views();
    }
    private function horizontalLine($text)
    {
        return new SQLSingleLineCommentCommand("==================$text =============================================");
    }
    private function comments()
    {
        $this->add($this->horizontalLine("COMMENTS"));
        $this->add($this->baseTableForComments(app::values()->comments()));
    }

    private function likes()
    {
        $this->add($this->horizontalLine("LIKES"));
        $this->add($this->baseTableForLikes(app::values()->likes()));
    }

    private function views()
    {
        $this->add($this->horizontalLine("VIEWS"));
        $this->add($this->baseTableForViews(app::values()->views()));
    }


    private function baseTableForComments($table_name)
    {
        //todo: all users tables have a common structure
        return SQLCreate::table_if_not_exists($table_name)
            ->add_primary_key(SQLCreate::primary_key()->addColumn(app::values()->row_id()))
            ->addColumn(SQLCreate::column(app::values()->row_id())->bigint(Db::max_length()->row_id())->unsigned()->not_null()->auto_increment())
            ->addColumn(SQLCreate::column(app::values()->entity_id())->bigint(Db::max_length()->entity_id())->unsigned()->not_null())
            ->addColumn(SQLCreate::column(app::values()->timestamp())->int(Db::max_length()->timestamp())->unsigned()->not_null())
            ->addColumn(SQLCreate::column(app::values()->session_id_hash())->varchar(Db::max_length()->session_id_hash())->not_null())
            ->addColumn(SQLCreate::column(app::values()->full_name())->varchar(Db::max_length()->full_name())->not_null())
            ->addColumn(SQLCreate::column(app::values()->email_address())->varchar(Db::max_length()->email_address())->not_null())
            ->addColumn(SQLCreate::column(app::values()->file_name())->varchar(Db::max_length()->file_name())->not_null())
            ->addColumn(SQLCreate::column(app::values()->content())->varchar(Db::max_length()->comment())->not_null())
            ->addColumn(SQLCreate::column(app::values()->approval_status_code())->varchar(Db::max_length()->text_code())->not_null()->default_value(app::approval_status_codes()->pending_approval()))
            ;
    }
    private function baseTableForLikes($table_name)
    {

        return SQLCreate::table_if_not_exists($table_name)
            ->add_primary_key(SQLCreate::primary_key()->addColumn(app::values()->hash_code()))
            ->addColumn(SQLCreate::column(app::values()->hash_code())->varchar(Db::max_length()->session_id_hash())->not_null())
            ->addColumn(SQLCreate::column(app::values()->file_name())->varchar(Db::max_length()->file_name())->not_null())
            ->addColumn(SQLCreate::column(app::values()->session_id_hash())->varchar(Db::max_length()->session_id_hash())->not_null())
            ->addColumn(SQLCreate::column(app::values()->timestamp())->int(Db::max_length()->timestamp())->unsigned()->not_null())
            ;
    }

    private function baseTableForViews($table_name)
    {
        return SQLCreate::table_if_not_exists($table_name)
            ->add_primary_key(SQLCreate::primary_key()->addColumn(app::values()->row_id()))
            ->addColumn(SQLCreate::column(app::values()->row_id())->bigint(Db::max_length()->entity_id())->not_null()->auto_increment())
            ->addColumn(SQLCreate::column(app::values()->file_name())->varchar(Db::max_length()->file_name())->not_null())
            ->addColumn(SQLCreate::column(app::values()->session_id_hash())->varchar(Db::max_length()->session_id_hash())->not_null())
            ->addColumn(SQLCreate::column(app::values()->timestamp())->int(Db::max_length()->timestamp())->unsigned()->not_null())
            ;
    }

}
