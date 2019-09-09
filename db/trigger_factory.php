<?php
class TriggerFactory{

    public function after_add_post()
    {
        return new TriggerAfterAddPost();
    }
    public function before_add_post()
    {
        return new TriggerBeforeAddPost();
    }
    public function before_update_post_picture()
    {
        return new TriggerBeforeUpdatePostPicture();
    }
    public function after_update_post_picture()
    {
        return new TriggerAfterUpdatePostPicture();
    }
}
