<?php
class PageLayoutFactory{
    public function home_obsolete(){
        return new LayoutForHomePage_Obsolete();
    }
    public function home(){
        return new LayoutForHomePage();
    }
    public function other_page(){
        return new LayoutForOtherPage();
    }
    public function home_page_of_post(){
        return new LayoutForHomePageOfPost();
    }
    public function admin(){
        return new LayoutForAdminPage();
    }

    public function admin_edit_post()
    {
        return new PageLayoutForAdminEditPost();
    }
}

class RowLayoutFactory{
    public function _50_50(){
        return new RowLayoutFor50_50();
    }
    public function _33_33_33(){
        return new RowLayoutFor33_33_33();
    }
    public function _100_percent(){
        return new LayoutFor100Percent();
    }

    public function _33_66()
    {
        return new RowLayoutFor33_66();
    }
}

class RowLayoutFor50_50 extends LayoutForTwoColumns{
    public function __construct()
    {
        parent::__construct();
        $this->leftColumn()->width("50%");
        $this->rightColumn()->width("50%");
    }
}

class RowLayoutFor33_66 extends LayoutForTwoColumns{
    public function __construct()
    {
        parent::__construct();
        $this->leftColumn()->width("33%");
        $this->rightColumn()->width("66%");
    }
}

class LayoutFor100Percent extends SmartDiv{
    
}

class RowLayoutFor33_33_33 extends LayoutForThreeColumns{
    public function __construct()
    {
        parent::__construct();
        $this->leftColumn()->width("33%");
        $this->middleColumn()->width("33%");
        $this->rightColumn()->width("33%");
    }
}