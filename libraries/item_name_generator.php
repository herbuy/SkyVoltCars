<?php
class TradableItem{
    private $attribute_array;
    public function __construct()
    {
        $this->attribute_array = array();
    }

    public function __toString(){
        return join(" ",$this->attribute_array);
    }

    public function size_or_capacity($string)
    {
        $this->attribute_array[__FUNCTION__] = $string;
        return $this;
    }
    public function color($string)
    {
        $this->attribute_array[__FUNCTION__] = $string;
        return $this;
    }
    public function brand_or_manufacturer_and_model_or_series($string)
    {
        $this->attribute_array[__FUNCTION__] = $string;
        return $this;
    }
    public function item($string)
    {
        $this->attribute_array[__FUNCTION__] = $string;
        return $this;
    }
    public function location($string)
    {
        $this->attribute_array[__FUNCTION__] = $string;
        return $this;
    }
    public function series($string)
    {
        $this->attribute_array[__FUNCTION__] = $string;
        return $this;
    }
    public function used($string)
    {
        $this->attribute_array[__FUNCTION__] = $string;
        return $this;
    }
    public function bedrooms($string)
    {
        $this->attribute_array[__FUNCTION__] = $string;
        return $this;
    }
    public function country_of_origin($string)
    {
        $this->attribute_array[__FUNCTION__] = $string;
        return $this;
    }
    public function material($string)
    {
        $this->attribute_array[__FUNCTION__] = $string;
        return $this;
    }
    public function number_in_pack($string)
    {
        $this->attribute_array[__FUNCTION__] = $string;
        return $this;
    }

}
class SampleApplicationForBuildingItemNames{
    private $output;
    public function __construct()
    {
        
        //handbag and wearables
        $this->output .= sprintf("%s<br/>", (new TradableItem())->color("blue")->brand_or_manufacturer_and_model_or_series("bjk 2424")->item("hand bag")->size_or_capacity("- medium size"));

        //residential house
        $this->output .= sprintf("%s<br/>", (new TradableItem())->item("residential house")->location("in muyenga or kampala or uganda")->bedrooms("- 3 bedrooms, I kitchen and a sitting room"));

        //vehicle diagnostic tools
        $this->output .= sprintf("%s<br/>", (new TradableItem())->brand_or_manufacturer_and_model_or_series("genysis")->item("scan tool"));

        //musical instrument
        $this->output .= sprintf("%s<br/>", (new TradableItem())->brand_or_manufacturer_and_model_or_series("zildijan A series")->item("convertible crash cymbal")->size_or_capacity("- 4x4")->color("- intermediate yellow")->used("(used)"));

        //electrical appliances
        $this->output .= sprintf("%s<br/>", (new TradableItem())->brand_or_manufacturer_and_model_or_series("phillips A series")->item("flat iron |steam iron or dry iron")->size_or_capacity("- 4x4")->color("- intermediate yellow")->used("(used)"));

        //kitchen appliances
        $this->output .= sprintf("%s<br/>", (new TradableItem())->brand_or_manufacturer_and_model_or_series("phillips A series")->item("cooking box | value flask | tray set | sauce pan")->size_or_capacity("- 4x4")->color("- intermediate yellow")->used("(used)"));

        //scales and measuring devices
        $this->output .= sprintf("%s<br/>", (new TradableItem())->brand_or_manufacturer_and_model_or_series("phillips A series")->item("digital platform | counter balance |  pedestral scale")->size_or_capacity("up to 100kgs")->country_of_origin("- England")->used("(used)"));
        //scales and measuring devices

        //computers and phones
        $this->output .= sprintf("%s<br/>", (new TradableItem())->brand_or_manufacturer_and_model_or_series("Dell latitude d630")->item("laptop | notebook | computer | think pad | smartphone")->size_or_capacity("- 8gb ram, 2gb hard disk")->country_of_origin("- England")->used("(used)"));

        //building materials
        $this->output .= sprintf("%s<br/>", (new TradableItem())->material("wooden | leather |ceramics | polythene")->item("floor tiles | wall tiles | computer | think pad | smartphone")->size_or_capacity("- small size 30x30")->country_of_origin("- Made from Spain")->number_in_pack("- 21 pieces"));

        //handbag
        $this->output .= sprintf("%s<br/>", (new TradableItem()));
        //========= if all goes well, print the item name =============

    }
    public function __toString()
    {
        return $this->output;
    }

}
