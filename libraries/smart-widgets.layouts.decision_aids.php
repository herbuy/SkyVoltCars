<?php

class LayoutForConsequences extends LayoutForNRows{

    private $content;   
    private $header;

    public function __construct()
    {
        parent::__construct();
        $this->content = new LayoutForNRows();
        $this->header = new SmartDiv();
    }
    public function heading(){
        return $this->header;
    }
    public function __toString()
    {
        $this->addNewRow()->add_child($this->header);
        //$this->addNewRow()->add_child($this->content);
        $this->addNewRow()->add_child($this->getContentAsHtml());
        return parent::__toString();
    }
    private $item_padding_left = "0px";
    private $item_padding_right = "0px";
    public function setItemPaddingLeftRight($padding_left,$padding_right){
        $this->item_padding_left = $padding_left;
        $this->item_padding_right = $padding_right;
    }
    private $content_dictionary = array();
    private function getContentAsHtml(){
        foreach ($this->content_dictionary as $subject=>$explanation){
            $layout = new LayoutForNRows();
            $layout->padding(sprintf("%s %s",$this->item_padding_left,$this->item_padding_right));

            $layout->addNewRow()->add_child($subject)->font_weight_bold();
            $layout->addNewRow()->add_child($explanation)->color_gray();
            $layout->border_bottom("1px solid #ddd")->margin_bottom("4px")->padding_bottom("4px");
            $this->content->addNewRow()->add_child($layout);
        }
        return $this->content;
    }

    protected function explainConsequence($subject, $explanation){

        $this->content_dictionary[$subject] = $explanation;
        return;
        //=====
        $layout = new LayoutForNRows();
        $layout->addNewRow()->add_child($subject)->font_weight_bold();
        $layout->addNewRow()->add_child($explanation)->color_gray();
        $layout->border_bottom("1px solid #ddd")->margin_bottom("4px")->padding_bottom("4px");
        $this->content->addNewRow()->add_child($layout);
    }

    //===== me
    //1
    public function addWhatGoodWillHappenToMeNowIfIdo($subject, $explanation)
    {
        $this->explainConsequence($subject,$explanation);
        return $this;
    }
    //2
    public function addWhatGoodWillHappenToMeNowIfIdont($subject, $explanation)
    {
        $this->explainConsequence($subject,$explanation);
        return $this;
    }

    //3
    public function addWhatGoodWillHappenToMeLaterIfIdo($subject, $explanation)
    {
        $this->explainConsequence($subject,$explanation);
        return $this;
    }
    //4
    public function addWhatGoodWillHappenToMeLaterIfIdont($subject, $explanation)
    {
        $this->explainConsequence($subject,$explanation);
        return $this;
    }


    //1
    public function addWhatGoodWillHappenToOthersNowIfIdo($subject, $explanation)
    {
        $this->explainConsequence($subject,$explanation);
        return $this;
    }
    //2
    public function addWhatGoodWillHappenToOthersNowIfIdont($subject, $explanation)
    {
        $this->explainConsequence($subject,$explanation);
        return $this;
    }

    //3
    public function addWhatGoodWillHappenToOthersLaterIfIdo($subject, $explanation)
    {
        $this->explainConsequence($subject,$explanation);
        return $this;
    }
    //4
    public function addWhatGoodWillHappenToOthersLaterIfIdont($subject, $explanation)
    {
        $this->explainConsequence($subject,$explanation);
        return $this;
    }

    //1
    public function addWhatGoodWontHappenToMeNowUnlessIdont($subject, $explanation)
    {
        $this->explainConsequence($subject,$explanation);
        return $this;
    }
    //2
    public function addWhatGoodWontHappenToMeNowUnlessIdo($subject, $explanation)
    {
        $this->explainConsequence($subject,$explanation);
        return $this;
    }

    //3
    public function addWhatGoodWontHappenToMeLaterUnlessIdont($subject, $explanation)
    {
        $this->explainConsequence($subject,$explanation);
        return $this;
    }
    //4
    public function addWhatGoodWontHappenToMeLaterUntilIdo($subject, $explanation)
    {
        $this->explainConsequence($subject,$explanation);
        return $this;
    }


    //1
    public function addWhatGoodWontHappenToOthersNowUnlessIdont($subject, $explanation)
    {
        $this->explainConsequence($subject,$explanation);
        return $this;
    }
    //2
    public function addWhatGoodWontHappenToOthersNowIfIdont($subject, $explanation)
    {
        $this->explainConsequence($subject,$explanation);
        return $this;
    }

    //3
    public function addWhatGoodWontHappenToOthersLaterIfIdo($subject, $explanation)
    {
        $this->explainConsequence($subject,$explanation);
        return $this;
    }
    //4
    public function addWhatGoodWontHappenToOthersLaterIfIdont($subject, $explanation)
    {
        $this->explainConsequence($subject,$explanation);
        return $this;
    }


//1
    public function addWhatBadWillHappenToMeNowIfIdo($subject, $explanation)
    {
        $this->explainConsequence($subject,$explanation);
        return $this;
    }
//2
    public function addWhatBadWillHappenToMeNowIfIdont($subject, $explanation)
    {
        $this->explainConsequence($subject,$explanation);
        return $this;
    }

//3
    public function addWhatBadWillHappenToMeLaterIfIdo($subject, $explanation)
    {
        $this->explainConsequence($subject,$explanation);
        return $this;
    }
//4
    public function addWhatBadWillHappenToMeLaterIfIdont($subject, $explanation)
    {
        $this->explainConsequence($subject,$explanation);
        return $this;
    }


//1
    public function addWhatBadWillHappenToOthersNowIfIdo($subject, $explanation)
    {
        $this->explainConsequence($subject,$explanation);
        return $this;
    }
//2
    public function addWhatBadWillHappenToOthersNowIfIdont($subject, $explanation)
    {
        $this->explainConsequence($subject,$explanation);
        return $this;
    }

//3
    public function addWhatBadWillHappenToOthersLaterIfIdo($subject, $explanation)
    {
        $this->explainConsequence($subject,$explanation);
        return $this;
    }
//4
    public function addWhatBadWillHappenToOthersLaterIfIdont($subject, $explanation)
    {
        $this->explainConsequence($subject,$explanation);
        return $this;
    }

//1
    public function addWhatBadWontHappenToMeNowIfIdo($subject, $explanation)
    {
        $this->explainConsequence($subject,$explanation);
        return $this;
    }
//2
    public function addWhatBadWontHappenToMeNowIfIdont($subject, $explanation)
    {
        $this->explainConsequence($subject,$explanation);
        return $this;
    }

//3
    public function addWhatBadWontHappenToMeLaterIfIdo($subject, $explanation)
    {
        $this->explainConsequence($subject,$explanation);
        return $this;
    }
//4
    public function addWhatBadWontHappenToMeLaterIfIdont($subject, $explanation)
    {
        $this->explainConsequence($subject,$explanation);
        return $this;
    }


//1
    public function addWhatBadWontHappenToOthersNowIfIdo($subject, $explanation)
    {
        $this->explainConsequence($subject,$explanation);
        return $this;
    }
//2
    public function addWhatBadWontHappenToOthersNowIfIdont($subject, $explanation)
    {
        $this->explainConsequence($subject,$explanation);
        return $this;
    }

//3
    public function addWhatBadWontHappenToOthersLaterIfIdo($subject, $explanation)
    {
        $this->explainConsequence($subject,$explanation);
        return $this;
    }
//4
    public function addWhatBadWontHappenToOthersLaterIfIdont($subject, $explanation)
    {
        $this->explainConsequence($subject,$explanation);
        return $this;
    }
}