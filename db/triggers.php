<?php

class TriggerAfterAddPost extends SQLCommandForCreateTrigger{
    public function __construct()
    {
        parent::__construct();

        $this->
        trigger_name(app::values()->on_add_post())->
        after_insert_on(app::values()->posts());

        $this->for_each_row()->
        begin()->
        add($this->queryForReplaceMostRecentPostPerCategory());
    }

    private function queryForReplaceMostRecentPostPerCategory()
    {
        return (new SQLSelectExpression())->
        select_everything()->
        from(app::values()->posts())->
        where(
            Db::fields()->row_id()->inTable(app::values()->posts())->isEqualTo(Db::fields()->row_id()->inNewRow())
        )->
        into(app::values()->most_recent_post_per_category())->
        on_duplicate_key_replace();
    }
}

class TriggerBeforeAddPost extends SQLCommandForCreateTrigger{
    public function __construct()
    {
        parent::__construct();

        $this->
        trigger_name(app::values()->before_add_post())->
        before_insert_on(app::values()->posts());

        /*$this->for_each_row()->begin()->
        if_(
            (new SQLSelectExpression())->
            select(Db::fields()->entity_id())->
            from(app::values()->categories())->
            where(
                Db::fields()->entity_id()->inTable(app::values()->categories())->
                isEqualTo(Db::fields()->category_id()->inNewRow())
            )->
            not_exists()
        )->
        then((new SQLCommandList())->signal_sqlstate_45000("category not found"))->
        else_if(
            (new SQLSelectExpression())->
            select(Db::fields()->entity_id())->
            from(app::values()->pages())->
            where(
                Db::fields()->entity_id()->inTable(app::values()->pages())->
                isEqualTo(Db::fields()->page_id()->inNewRow())
            )->
            not_exists(),
            (new SQLCommandList())->signal_sqlstate_45000("page not found")
        )->
        else_if(
            (new SQLSelectExpression())->
            select(Db::fields()->entity_id())->
            from(app::values()->sections())->
            where(
                Db::fields()->entity_id()->inTable(app::values()->sections())->
                isEqualTo(Db::fields()->section_id()->inNewRow())
            )->
            not_exists(),
            (new SQLCommandList())->signal_sqlstate_45000("section not found")
        );*/

        $this->for_each_row()->begin()->
        if_(
            (new SQLSelectExpression())->
            select(Db::fields()->entity_id())->
            from(app::values()->sections())->
            where(
                Db::fields()->entity_id()->inTable(app::values()->sections())->
                isEqualTo(Db::fields()->section_id()->inNewRow())
            )->
            not_exists()
        )->
        then(
            (new SQLCommandList())->signal_sqlstate_45000("section not found")
        );

    }
}


class TriggerBeforeAddUser extends SQLCommandForCreateTrigger{
    public function __construct()
    {
        parent::__construct();

        $this->
        trigger_name(app::values()->before_add_user())->
        before_insert_on(app::values()->users());

        $this->for_each_row()->begin()->
        if_(
            (new SQLSelectExpression())->
            select(Db::fields()->entity_id())->
            from(app::values()->users())->
            where(
                Db::fields()->email_address()->inTable(app::values()->users())->
                isEqualTo(Db::fields()->email_address()->inNewRow())
            )->
            exists()
        )->
        then(
            (new SQLCommandList())->signal_sqlstate_45000("someone already using the specified email address")
        );

    }
}

class TriggerBeforeAddSession extends SQLCommandForCreateTrigger{
    public function __construct()
    {
        parent::__construct();

        $this->
        trigger_name(app::values()->before_add_session())->
        before_insert_on(app::values()->sessions());

        $this->for_each_row()->begin()->
        if_(
            (new SQLSelectExpression())->
            select(Db::fields()->entity_id())->
            from(app::values()->users())->
            where(
                Db::fields()->email_address()->inTable(app::values()->users())->
                isEqualTo(Db::fields()->email_address()->inNewRow())->
                and_(
                    Db::fields()->password_hash()->inTable(app::values()->users())->
                    isEqualTo(Db::fields()->password_hash()->inNewRow())
                )
            )->
            not_exists()
        )->
        then(
            (new SQLCommandList())->signal_sqlstate_45000("invalid email address or password")
        );

    }
}

#==========================================
class TriggerBeforeUpdatePostPicture extends SQLCommandForCreateTrigger{
    public function __construct()
    {
        parent::__construct();

        $this->
        trigger_name(app::values()->before_update_post_picture())->
        before_update_on(app::values()->posts());

        $this->for_each_row()->begin()->
        if_(
            (new SQLSelectExpression())->
            select(Db::fields()->file_name())->
            from(app::values()->posts())->
            where(
                Db::fields()->file_name()->inTable(app::values()->posts())->
                isEqualTo(Db::fields()->file_name()->inNewRow())
            )->
            not_exists()
        )->
        then((new SQLCommandList())->signal_sqlstate_45000("post not found"));
    }
}


class TriggerAfterUpdatePostPicture extends SQLCommandForCreateTrigger{
    public function __construct()
    {
        parent::__construct();

        $this->
        trigger_name(app::values()->after_update_post_picture())->
        after_update_on(app::values()->posts());

        $this->for_each_row()->begin()->add(
            (new SQLUpdateQuery())->
            update(app::values()->most_recent_post_per_category())->
            where(
                Db::fields()->file_name()->inTable(app::values()->most_recent_post_per_category())->
                isEqualTo(Db::fields()->file_name()->inNewRow())
            )->
            set(Db::fields()->picture_file_name()->toSQLValue(Db::fields()->picture_file_name()->inNewRow()))
        );
        
    }
}
