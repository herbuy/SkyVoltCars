<?php

class SmartHeader extends SmartNonReplacedHTMLElement{
    public function __construct()
    {
        $this->set_tag_name("header");
    }
}

class SmartFooter extends SmartNonReplacedHTMLElement{
    public function __construct()
    {
        $this->set_tag_name("footer");
    }
}
class SmartNavigation extends SmartNonReplacedHTMLElement{
    public function __construct()
    {
        $this->set_tag_name("nav");
    }
}
class SmartMain extends SmartNonReplacedHTMLElement{
    public function __construct()
    {
        $this->set_tag_name("main");
    }
}
class SmartArticle extends SmartNonReplacedHTMLElement{
    public function __construct()
    {
        $this->set_tag_name("article");
    }
}