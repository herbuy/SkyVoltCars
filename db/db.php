<?php

class Db{    
    public static function queries(){
        return new QueryFactory();
    }
    public static function results(){
        return new ResultFactory();
    }

    public static function fields()
    {
        return new DbFieldFactory();
    }
    public static function computed_values()
    {
        return new ComputedValueFactory();
    }

    public static function max_length()
    {
        return new MaxLengthFactory();
    }

    public static function triggers()
    {
        return new TriggerFactory();
    }

    public static function page_ids()
    {
        return new PageIdFactory();
    }
    public static function section_ids()
    {
        return new SectionIdFactory();
    }
}

//========
class ResultFactory{
    public function single_query($query){
        return new ResultForSingleQueryOnSmartcashDb($query);
    }
    public function multi_query($query){
        return new ResultForMultiQueryOnSmartcashDb($query);
    }
}
class ResultForSingleQueryOnSmartcashDb extends ResultForSingleQuery{
    public function __construct($query)
    {
        parent::__construct($query,  WebsiteSettings::db_host(), WebsiteSettings::db_username(), WebsiteSettings::db_password(), WebsiteSettings::db_name());
    }
}

class ResultForMultiQueryOnSmartcashDb extends ResultForMultiQuery{
    public function __construct($query)
    {
        parent::__construct($query,  WebsiteSettings::db_host(), WebsiteSettings::db_username(), WebsiteSettings::db_password(), WebsiteSettings::db_name());
    }
}

