<?php
class QueryForEditExtendedPostContent extends SQLCommandListForMotokaviews{
    private $input_text;

    private $sql_command_list;
    private $entity_id;

    public function __construct($file_name,$input_text)
    {
        parent::__construct();
        $this->input_text = $input_text;
        $this->sql_command_list = $this;
        $this->entity_id = ConvertToSQLValue::ifNotSQLValue(
            EntityIdFromFileName::get($file_name)
        );


        $this->add($this->sql_for_delete_current_post_content());
        $this->set_new_post_content();
        $this->add(
            $this->sqlForUpdateExtendedPostContentForDraftPost($input_text)
        );

    }

    private function set_new_post_content()
    {
        $this->process($this->input_text);
    }

    private function process($input_text)
    {
        $arr_tokens = (new RichTextTokens($input_text))->get_array();
        if(is_array($arr_tokens)){
            $total_tokens = count($arr_tokens);

            for($i = 0;$i < $total_tokens;$i++){
                //switch beteen possibilities
                $token = $arr_tokens[$i];

                if($token == "\r"){
                    $this->sql_command_list->add(
                        $this->getSQLBuilder()->
                        set(Db::fields()->content_type()->toStringValue("br"))
                    );
                }
                else if($token == "@@"){
                    $this->processCommandForOrdinaryText("@");
                }
                else if($token == "@"){
                    if($i < $total_tokens - 1){
                        $i += 1;
                        $token = $arr_tokens[$i];

                        switch ($token){
                            case app::content_type_id()->img():
                                $command = new CommandToAddSQLForInsertImage($this,$arr_tokens, $i);
                                $i+=$command->total_skips();
                                break;
                            case app::content_type_id()->video():
                                $command = new CommandToAddSQLForInsertYoutubeVideo($this,$arr_tokens, $i);
                                $i+=$command->total_skips();
                                break;
                            case app::content_type_id()->link():
                                $command = new CommandToAddSQLForInsertLink($this,$arr_tokens, $i);
                                $i+=$command->total_skips();
                                break;
                            case app::content_type_id()->space():
                                $command = new CommandToAddSQLForInsertSpace($this,$arr_tokens, $i);
                                $i+=$command->total_skips();
                                break;
                            case app::content_type_id()->tintscreen():
                                $command = new CommandToAddSQLForInsertTintScreen($this,$arr_tokens, $i);
                                $i+=$command->total_skips();
                                break;
                            case app::content_type_id()->subhead():
                                $command = new CommandToAddSQLForInsertSubhead($this,$arr_tokens, $i);
                                $i+=$command->total_skips();
                                break;
                            case app::content_type_id()->columnrule():
                                $command = new CommandToAddSQLForInsertColumnRule($this,$arr_tokens, $i);
                                $i+=$command->total_skips();
                                break;
                            case app::content_type_id()->quote():
                                $command = new CommandToAddSQLForInsertQuote($this,$arr_tokens, $i);
                                $i+=$command->total_skips();
                                break;
                            case app::content_type_id()->bold():
                                $command = new CommandToAddSQLForInsertBold($this,$arr_tokens, $i);
                                $i+=$command->total_skips();
                                break;
                            case app::content_type_id()->italics():
                                $command = new CommandToAddSQLForInsertItalics($this,$arr_tokens, $i);
                                $i+=$command->total_skips();
                                break;

                            //tags related to CAR REVIEWS
                            #=============================
                            case app::content_type_id()->rating():
                                $command = new CommandToAddSQLForInsertRating($this,$arr_tokens, $i);
                                $i+=$command->total_skips();
                                break;

                            default:
                                $this->processCommandForOrdinaryText($token);
                                break;
                        }
                    }
                }
                else if(strlen($token) > 0 && $token[0] == '"'){
                    //remove the quotes and replace any slashed quotes
                    $token = trim($token,'"');
                    $token = str_replace('\"','"',$token);
                    $this->processCommandForOrdinaryText($token);
                }
                else if(strlen($token) > 0 && $token[0] == "'"){
                    //remove the quotes and replace any slashed quotes
                    $token = trim($token,"'");
                    $token = str_replace("\'","'",$token);
                    $this->processCommandForOrdinaryText($token);
                }
                else{
                    $this->processCommandForOrdinaryText($token);
                }
            }
        }
        else{
            return "";
        }

        return json_encode($arr_tokens);
    }

    public function getSQLBuilder()
    {
        $sql = new SQLInsertQuery();
        $sql->use_set_format();
        $sql->insert_into(app::values()->extended_post_content());
        $sql->set(Db::fields()->entity_id()->toSQLValue($this->entity_id));
        $sql->set(Db::fields()->timestamp()->toInt(Db::computed_values()->current_timestamp()));
        return $sql;
    }
    
    private function processCommandForOrdinaryText($token)
    {
        $this->sql_command_list->add(
            $this->getSQLBuilder()->
            set(Db::fields()->content_type()->toStringValue("span"))->
            set(Db::fields()->content()->toStringValue($token))
        );
    }

    private function sql_for_delete_current_post_content()
    {
        return (new SQLDeleteQuery())->
        delete_from(app::values()->extended_post_content())->
        where(Db::fields()->entity_id()->isEqualTo($this->entity_id));
    }

    private function sqlForUpdateExtendedPostContentForDraftPost($input_text)
    {
        return (new SQLUpdateQuery())->
        update(app::values()->draft_posts())->
        where(Db::fields()->entity_id()->isEqualTo($this->entity_id))->
        set(Db::fields()->extended_post_content()->toStringValue($input_text));
    }
}

abstract class CommandToAddSQLForInsertContentType{
    /** @param \QueryForEditExtendedPostContent $sql_command_list */
    abstract protected function execute($sql_command_list,$command_tokens, $i);
    public function __construct($sql_command_list,$command_tokens, $i)
    {
        $this->execute($sql_command_list,$command_tokens, $i);
    }
    private $keywords = array(
        'image'=>0,'link'=>0
    );
    protected function is_keyword($url)
    {
        return array_key_exists($url,$this->keywords);
    }

    //to be used by subclasses
    protected function getParam1($command_tokens, $i)
    {
        return @$command_tokens[$i + 2];
    }

    protected function getParam2($command_tokens, $i)
    {
        return @$command_tokens[$i + 4];
    }
    protected function getParam3($command_tokens, $i)
    {
        return @$command_tokens[$i + 6];
    }

    abstract function total_skips();
    protected function one_skip()
    {
        return 1;
    }
    protected function two_skips(){
        return 2;
    }
    protected function four_skips(){
        return 4;
    }
    protected function six_skips(){
        return 6;
    }
    
    protected function throwExceptionIfNot($condition, $message)
    {
        $this->throwExceptionIf(!$condition,$message);
    }
    protected function throwExceptionIf($condition, $message)
    {
        if($condition){
            throw new Exception($message);
        }
    }
    protected function validateMaxLengthOfValueForWidth($value, $message)
    {
        $this->throwExceptionIf(strlen($value) > Db::max_length()->width_in_tbl_for_extended_content(),$message);
        return $value;
    }

    protected function validateRating($value, $message)
    {        
        $this->throwExceptionIfNot(
            array_key_exists($value,array(
                app::possible_ratings()->poor()=>0,
                app::possible_ratings()->below_average()=>0,
                app::possible_ratings()->average()=>0,
                app::possible_ratings()->above_average()=>0,
                app::possible_ratings()->excellent()=>0
            )),$message
        );
        return $value;
    }

    protected function validateTitleLengthAgainstMaxTitleLength($value, $message)
    {
        $this->throwExceptionIf(strlen($value) > Db::max_length()->title(),$message);
        return $value;
    }
}

class CommandToAddSQLForInsertImage extends CommandToAddSQLForInsertContentType{

    public function execute($sql_command_list,$command_tokens, $i)
    {
        $url = $this->getParam1($command_tokens,$i);
        $alt = $this->getParam2($command_tokens,$i);

        if ($this->is_keyword($url)) {
            throw new Exception("expected url after the keyword @image, found @" . $url);
        } else if ($this->is_keyword($alt)) {
            throw new Exception("expected @alt after @url");
        }

        $sql_command_list->add(
            $sql_command_list->getSQLBuilder()->
            set(Db::fields()->content_type()->toStringValue(app::content_type_id()->img()))->
            set(Db::fields()->src()->toStringValue($url))->
            set(Db::fields()->alt()->toStringValue($alt))
        );
   
    }
    public function total_skips()
    {
        return $this->four_skips();
    }
}

class CommandToAddSQLForInsertYoutubeVideo extends CommandToAddSQLForInsertContentType{

    public function execute($sql_command_list,$command_tokens, $i)
    {
        $video_id_or_url = $this->getParam1($command_tokens,$i);

        $width = $this->validateMaxLengthOfValueForWidth(
            $this->getParam2($command_tokens,$i),"value for width of video too long"
        );
        $height = $this->getParam3($command_tokens,$i);

        if ($this->is_keyword($video_id_or_url)) {
            throw new Exception("expected video id after the keyword @video, found @" . $video_id_or_url);
        } 
        else if ($this->is_keyword($width)) {
            throw new Exception("expected width of video after video id");
        }

        else if ($this->is_keyword($height)) {
            throw new Exception("expected height of video after width");
        }

        //validate things that might throw database error e.g. empty, type, length, sign

        $sql_command_list->add(
            $sql_command_list->getSQLBuilder()->
            set(Db::fields()->content_type()->toStringValue(app::content_type_id()->youtube_video()))->
            set(Db::fields()->src()->toStringValue($video_id_or_url))->
            set(Db::fields()->width()->toStringValue($width))->
            set(Db::fields()->height()->toStringValue($height))
        );

    }

    public function total_skips()
    {
        return $this->six_skips();
    }
}

class CommandToAddSQLForInsertRating extends CommandToAddSQLForInsertContentType{

    public function execute($sql_command_list,$command_tokens, $i)
    {
        $title = $this->validateTitleLengthAgainstMaxTitleLength(
            $this->getParam1($command_tokens,$i),
            sprintf(
                "title should not be longer than %s characters",
                Db::max_length()->title()
            )

        );
        //======

        $rating_or_perception = $this->validateRating(
            $this->getParam2($command_tokens,$i),
            sprintf(
                "expected rating to be any of these values: %s but found %s",
                app::possible_ratings()->as_ORList(), $this->getParam2($command_tokens,$i)
            )

        );
        $justification = $this->getParam3($command_tokens,$i);

        //validate things that might throw database error e.g. empty, type, length, sign
        $sql_command_list->add(
            $sql_command_list->getSQLBuilder()->
            set(Db::fields()->content_type()->toStringValue(app::content_type_id()->rating()))->
            set(Db::fields()->title()->toStringValue($title))->
            set(Db::fields()->rating()->toStringValue($rating_or_perception))->
            set(Db::fields()->content()->toStringValue($justification))
        );

    }
    public function total_skips()
    {
        return $this->six_skips();
    }
}

class CommandToAddSQLForInsertLink extends CommandToAddSQLForInsertContentType{

    public function execute($sql_command_list,$command_tokens, $i)
    {
        $url = $this->getParam1($command_tokens, $i);
        $inner_text = $this->getParam2($command_tokens, $i);
        if ($this->is_keyword($url)) {
            throw new Exception("expected url after the keyword @link, found @" . $url);
        } else if ($this->is_keyword($inner_text)) {
            throw new Exception("expected @inner-text after @url");
        }

        $sql_command_list->add(
            $sql_command_list->getSQLBuilder()->
            set(Db::fields()->content_type()->toStringValue(app::content_type_id()->link()))->
            set(Db::fields()->href()->toStringValue($url))->
            set(Db::fields()->content()->toStringValue($inner_text))
        );
    }
    public function total_skips()
    {
        return $this->four_skips();
    }

}
class CommandToAddSQLForInsertSubhead extends CommandToAddSQLForInsertContentType{
    public function execute($sql_command_list,$command_tokens, $i)
    {
        $text = $this->getParam1($command_tokens, $i);

        $sql_command_list->add(
            $sql_command_list->getSQLBuilder()->
            set(Db::fields()->content_type()->toStringValue(app::content_type_id()->subhead()))->
            set(Db::fields()->content()->toStringValue($text))
        );
    }
    public function total_skips()
    {
        return $this->two_skips();
    }
}
class CommandToAddSQLForInsertTintScreen extends CommandToAddSQLForInsertContentType{
    public function execute($sql_command_list,$command_tokens, $i)
    {
        $text = $this->getParam1($command_tokens, $i);

        $sql_command_list->add(
            $sql_command_list->getSQLBuilder()->
            set(Db::fields()->content_type()->toStringValue(app::content_type_id()->tintscreen()))->
            set(Db::fields()->content()->toStringValue($text))
        );
    }
    public function total_skips()
    {
        return $this->two_skips();
    }
}
class CommandToAddSQLForInsertColumnRule extends CommandToAddSQLForInsertContentType{
    public function execute($sql_command_list,$command_tokens, $i)
    {
        $text = $this->getParam1($command_tokens, $i);

        $sql_command_list->add(
            $sql_command_list->getSQLBuilder()->
            set(Db::fields()->content_type()->toStringValue(app::content_type_id()->columnrule()))->
            set(Db::fields()->content()->toStringValue($text))
        );
    }
    public function total_skips()
    {
        return $this->two_skips();
    }
}
class CommandToAddSQLForInsertQuote extends CommandToAddSQLForInsertContentType{
    public function execute($sql_command_list,$command_tokens, $i)
    {
        $text = $this->getParam1($command_tokens, $i);

        $sql_command_list->add(
            $sql_command_list->getSQLBuilder()->
            set(Db::fields()->content_type()->toStringValue(app::content_type_id()->quote()))->
            set(Db::fields()->content()->toStringValue($text))
        );
    }
    public function total_skips()
    {
        return $this->two_skips();
    }
}
class CommandToAddSQLForInsertBold extends CommandToAddSQLForInsertContentType{
    public function execute($sql_command_list,$command_tokens, $i)
    {
        $text = $this->getParam1($command_tokens, $i);

        $sql_command_list->add(
            $sql_command_list->getSQLBuilder()->
            set(Db::fields()->content_type()->toStringValue(app::content_type_id()->bold()))->
            set(Db::fields()->content()->toStringValue($text))
        );
    }
    public function total_skips()
    {
        return $this->two_skips();
    }
}

class CommandToAddSQLForInsertItalics extends CommandToAddSQLForInsertContentType{
    public function execute($sql_command_list,$command_tokens, $i)
    {
        $text = $this->getParam1($command_tokens, $i);

        $sql_command_list->add(
            $sql_command_list->getSQLBuilder()->
            set(Db::fields()->content_type()->toStringValue(app::content_type_id()->italics()))->
            set(Db::fields()->content()->toStringValue($text))
        );
    }
    public function total_skips()
    {
        return $this->two_skips();
    }
}

class CommandToAddSQLForInsertSpace extends CommandToAddSQLForInsertContentType{

    public function execute($sql_command_list,$command_tokens, $i)
    {
        $sql_command_list->add(
            $sql_command_list->getSQLBuilder()->
            set(Db::fields()->content_type()->toStringValue(app::content_type_id()->space()))->
            set(Db::fields()->content()->toStringValue($command_tokens[$i]))
        );
    }
    public function total_skips()
    {
        return $this->one_skip();
    }

}