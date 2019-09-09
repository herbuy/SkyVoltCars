<?php


abstract class LinkToYoutubeVideo extends SmartCustomTag{
    public function __construct($videoIdOrUrlToEmbed, $width='', $height='')
    {
        parent::__construct($this->getNameOfCustomTag());        
        $this->set_attribute(
            $this->attributeToSetGivenVideoId(),
            $this->getFullUrl($videoIdOrUrlToEmbed)
        );

        if(strlen($width) > 0){
            $this->set_attribute("width",$width);
        }
        if(strlen($height) > 0){
            $this->set_attribute("height",$height);
        }        
    }

    protected function getNameOfCustomTag()
    {
        return "embed";
    }
    protected function setVideoId($videoIdOnYoutube){

    }

    protected function attributeToSetGivenVideoId()
    {
        return "src";
    }

    private function getFullUrl($videoIdOnYoutube)
    {
        $needle = "https://www.youtube.com/embed/";
        $videoIdOnYoutube = str_replace($needle,"",$videoIdOnYoutube);
        return $needle. $videoIdOnYoutube;
    }
}
class LinkToYoutubeVideoUsingEmbed extends LinkToYoutubeVideo{

}
class LinkToYoutubeVideoUsingObject extends LinkToYoutubeVideo{
    protected function getNameOfCustomTag()
    {
        return "object";
    }
    protected function attributeToSetGivenVideoId()
    {
        return "data";
    }
}

class LinkToYoutubeVideoUsingIFrame extends LinkToYoutubeVideo{
    protected function getNameOfCustomTag()
    {
        return "iframe";
    }
}