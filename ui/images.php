<?php
class ImageFactory{
    public function logo(){
        return ui::urls()->asset("motokaviews.jpg")->toImage();
    }
    public function fb_icon(){
        return ui::urls()->asset(__FUNCTION__.".png")->toImage();
    }
    public function twitter_icon(){
        return ui::urls()->asset(__FUNCTION__.".png")->toImage();
    }
    public function youtube_icon(){
        return ui::urls()->asset(__FUNCTION__.".png")->toImage();
    }
    public function about_icon_white(){
        return ui::urls()->asset(__FUNCTION__.".png")->toImage();
    }

    public function edit_icon(){
        return ui::urls()->asset(__FUNCTION__.".png")->toImage();
    }

    public function contact_icon_white(){
        return ui::urls()->asset(__FUNCTION__.".png")->toImage();
    }

    public function home_icon_white()
    {
        return ui::urls()->asset(__FUNCTION__.".png")->toImage();
    }

}
