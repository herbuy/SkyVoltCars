<?php

class ColorFactory{

    public function link_complement(){
        //return "#f80";
        return $this->header_bg();
    }
    //===========================
    public function card_bg(){
        return new HexColor("#FFFFFF");
    }

    private function get_random_color(){
        $colors = array(
            //'chocolate'=>"d2691e", //very nice
            //'chartreuse'=>"7fff00",
            //'cornflowerblue'=>"6495ed",//very nice
            //'darkcyan'=>"008b8b",
            //'darkgoldenrod'=>"b8860b",
            //'darkgreen'=>"006400",
            //'darkkhaki'=>"bdb76b",
            //'darkmagenta'=>"8b008b",
            //'darkolivegreen'=>"556b2f",
            //'darkorange'=>"ff8c00",
            //'deeppink'=>"ff1493",

            //'dodgerblue'=>"1e90ff"//,//very good
            //'aaaaaa'=>'4267B2',
            //'bbbb'=>'1EBEA5',
            //'bbbb'=>'075E54',
            'cccc'=>'4FA885',
            //'tomato'=>'FF6347',
            'gold'=>'FFD700',
            'olive drab'=>'ADCC00',
            //'fb'=>'3B5998'
            //'black'=>'000000'

        );
        $index = count($colors) - 1;
        return array_values($colors)[$index];
    }

    public function header_bg()
    {
        return (new HexColor($this->get_random_color()))->L(0.05);//*->HPlus(-30)*/;
    }
    public function complement(){
        return $this->header_bg()->HPlus(180);
    }

    public function orange(){
        return (new HexColor("#ffff00"))->HPlus(-20);
    }

    public function section_descriptor(){
        return $this->header_bg()->STimes(0.7);
    }
    public function bg(){

        return $this->white();
        //return $this->header_bg()->S(0.1)->mix($this->white(),50);
        //return new HexColor("A9DBD8");
        return $this->footer_bg()->mix($this->white(),90);
        //return new HexColor("ffffff");
        //return $this->form_bg()->HPlus(15)->LTimes(0.8)/*->STimes(0.7)*/->STimes(0.3);
    }
    public function primary_engagement(){
        return $this->bg()->mix(ui::colors()->white(),50);
    }
    public function panel_border(){
        return $this->bg()->LTimes(0.9);
    }

    public function admin_page_bg()
    {
        return $this->bg();
    }

    public function border(){
        return $this->header_bg()->STimes(0.3)->L(0.7);
    }

    public function footer_bg()
    {
        //was:#4B4F56
        $base_color = $this->header_bg()->ToHsl();
        return $base_color->Stimes(0.3)->LTimes(0.5)->ToHex();
    }
    public function footer_fg()
    {
        //was:#f6f7f9
        $base_color = $this->header_bg()->ToHsl();
        return $base_color->L(0.9)->ToHex();
    }

    public function primary_button_bg()
    {
        return $this->header_bg()->STimes(0.7);
    }
    public function secondary_button_bg()
    {
        //was #0084FF
        return $this->header_bg()->ToHsl()->Stimes(0.5)->ToHex();
    }
    public function link_fg()
    {
        //return $this->header_bg();
        //return new HexColor("#3B5998");
        return $this->header_bg()->L(0.1);
    }

    public function seller()
    {
        $base_color = $this->header_bg()->ToHsl();
        return $base_color->Stimes(0.5)->ToHex();
    }

    public function secondary_text()
    {
        return $this->footer_bg()->LTimes(1.5);
    }

    public function body_color()
    {
        return $this->link_fg()->LTimes(0.5);
    }

    public function create_button()
    {
        $base_color = $this->header_bg()->ToHsl();
        $final_color = $base_color->HPlus(-100)->Stimes(2.0);
        return $final_color->ToHex();
    }

    public function price()
    {
        return $this->header_bg()->ToHsl()->H(348)->ToHex();
    }

    public function panel_header_bg()
    {
        return $this->bg()->mix(ui::colors()->white(),90);
    }
    public function comment_wrapper(){
        return $this->panel_header_bg();
    }

    public function panel_header_border_bottom()
    {
        return $this->panel_header_bg()->mix($this->header_bg(),20)->STimes(0.3);
    }

    public function menu_link_border_left()
    {
        return $this->panel_header_border_bottom()->LTimes(1.1);
    }

    public function gray()
    {
        return $this->body_color()->mix($this->card_bg(),30)/*->H(190)*/;
    }

    public function timeago()
    {
        return $this->gray();
    }

    public function contrast_text1()
    {
        return $this->header_bg()->ToHsl()->HPlus(-20)->ToHex();
    }

    public function black()
    {
        return new HexColor("#000000");
    }

    public function white()
    {
        return new HexColor("#ffffff");
    }

    public function form_bg()
    {
        return $this->header_bg()->mix($this->white(),100)->HPlus(10);
    }

    public function form_border()
    {
        return $this->form_bg()->LTimes(0.7)->STimes(0.2);
    }

    public function input_field_bg()
    {
        return $this->form_bg()->mix(ui::colors()->white(),50)->HPlus(10);
    }

    public function h1()
    {
        return $this->footer_bg()->LTimes(0.8);
    }

}
