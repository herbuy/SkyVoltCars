<?php
//----- makes some settings
//ini_set("memory_limit","128M");
//ini_set("upload_max_filesize","24M");


//require_once ("libraries/smart-widgets.php");
//require_once ("libraries/smart-widgets.layouts.php");
//require_once ("libraries/smart_utils.php");
//require_once ("libraries/web_address_builder.php");
//require_once ("libraries/security_builder.php");

//require_once ("libraries/sitemap_builder.php");

//load libraries for dealing with database
require_once ("libraries/result_for_query.php");
require_once ("libraries/sqlbuilder.php");
require_once ("libraries/security_builder.php");
require_once ("libraries/input_builder.php");
require_once ("libraries/smart_utils.php");
require_once ("libraries/file_upload_modules.php");
require_once ("libraries/ReaderForDataStoredInArray.BaseClass.php");
require_once ("libraries/rich_text.tokenizer.php");

require_once ("libraries/uploaded_picture_delegate.php");

//load modules that make up the database
require_once ("db/computed_value_factory.php");

require_once ("db/field_factory.php");

require_once ("db/queries.php");
require_once ("db/query_factory.php");

require_once ("db/triggers.php");
require_once ("db/trigger_factory.php");

require_once ("db/max_length_factory.php");
require_once ("db/page_id_factory.php");

require_once ("db/queries.extended_post_content.php");

require_once ("db/db.php");

//load other modules
require_once("app/arguments.php");
require_once("app/argument_factory.php");

require_once("app/result_arrays.php");
require_once("app/result_array_factory.php");

require_once("app/cmds.php");
require_once("app/cmd_factory.php");

require_once("app/browser_fields.php");
require_once("app/browser_field_factory.php");

require_once ("app/value_factory.php");
require_once ("app/settings.php");
require_once ("app/section_id_factory.php");
require_once ("app/app.php");


//include("app/text_to_sql_translater.php");


/*
print "<pre>";
print Db::queries()->create_tables();
print "</pre>";
exit;
*/

/*
print "<pre>";
print join("\$\$<br/><br/><br/>",array(
    Db::triggers()->after_add_post(),
    Db::triggers()->before_add_post(),
    Db::triggers()->before_update_post_picture(),
    Db::triggers()->after_update_post_picture()
));
print "</pre>";
exit;
*/

//==================
$final_output = array(
    app::values()->error()=>"",
    app::values()->content()=>array()
);

try{
    $cmd = app::browser_fields()->cmd()->toCmd();// DbProcedureList::lookupRemoteProcedureByName($remoteProcedureName->getValue());
    $result_array = $cmd->result_array();
    //print get_class($result_array);exit;
    $result = $result_array->get();
    if(is_array($result)){
        $final_output["content"] = $result;
    }
    else{
        throw new Exception("unsupported result type - expected array");
    }

}
catch(InvalidInputException $ex){
    $final_output['error']  = $ex->getMessage();
}
catch (Exception $ex){
    //print $ex->getMessage();
    //print $ex->getTraceAsString();
    //exit;
    $final_output['error']  = $ex->getMessage();
}
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: *");
header("Access-Control-Max-age: 1728000");
header("Access-Control-Allow-Headers: *");
print json_encode($final_output);