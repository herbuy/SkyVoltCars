<?php

class app{
    public static function settings(){
        return new WebsiteSettings();
    }

    private static $site_map_in_memory = null;
    public static function sitemap()
    {

        if(self::$site_map_in_memory){
            return self::$site_map_in_memory;
        }
        else{
            $file_with_map = app::settings()->host_file_for_php_sitemap();
            if(file_exists($file_with_map)){
                $saved_map = Sitemap::unserializeFile($file_with_map);
                if($saved_map){
                    self::$site_map_in_memory = $saved_map;
                    return self::$site_map_in_memory;
                }
                else{
                    self::$site_map_in_memory = Sitemap::new_instance();
                    return self::$site_map_in_memory;
                }
            }
            else{
                self::$site_map_in_memory = Sitemap::new_instance();
                return self::$site_map_in_memory;
            }
        }

    }

    /** @var ValueFactory $value_factory_cache  */
    private static $value_factory_cache = null;
    public static function values()
    {
        if(!self::$value_factory_cache){
            self::$value_factory_cache = new ValueFactory(); 
        }
        return self::$value_factory_cache;
    }

    private static $browser_field_factory = null;
    public static function browser_fields()
    {
        if(!self::$browser_field_factory){
            self::$browser_field_factory = new BrowserFieldFactory();
        }
        return self::$browser_field_factory;
    }

    public static function result_array()
    {
        return new ResultArrayFactory();
    }

    public static function cmds()
    {
        return new CmdFactory();
    }
    public static function reader($array)
    {
        return new ReaderForValuesStoredInArray($array);
    }
    public static function argument()
    {
        return new ArgumentFactory();
    }

    public static function content_type_id()
    {
        return new ContentTypeIdFactory();
    }

    public static function start_index_for_hiding_home_items()
    {
        return new StartIndexForHidingHomeItems();
    }

    public static function variables()
    {
        return new GlobalVariablesForTheApp();
    }

    public static function section_ids()
    {
        return new SectionIdFactory();
    }

    public static function possible_ratings()
    {
        return new PossibleRatings();
    }

    public static function utils()
    {
        return new FactoryForUtils();
    }

    public static function approval_status_codes()
    {
        return new FactoryForApprovalStatusCodes();
    }

}
class FactoryForApprovalStatusCodes{
    public function pending_approval(){
        return __FUNCTION__;
    }
    public function approved(){
        return __FUNCTION__;
    }
    public function spam(){
        return __FUNCTION__;
    }
    public function trashed(){
        return __FUNCTION__;
    }
}

class PossibleRatings{
    public static function poor(){
        return "poor";
    }
    public static function below_average(){
        return "below-average";
    }
    public static function average(){
        return "average";
    }
    public static function above_average(){
        return "above-average";
    }
    public static function excellent(){
        return "excellent";
    }

    public function as_ORList()
    {
        return join(
            " or ",array(
                self::poor(),self::below_average(),
                self::average(),self::above_average(),self::excellent()
            )
        );
    }
}

class StartIndexForHidingHomeItems{

    public function reviews()
    {
        return 11;
    }
    public function exporter_reviews()
    {
        return 11;
    }
    public function news()
    {
        return $this->exporter_reviews();
    }

    public function car_exporters()
    {
        return 7;
    }

    public function car_maintenance()
    {
        return 15;
    }

    public function careers()
    {
        return 9;
    }

}
class GlobalVariablesForTheApp{

    public function max_num_items_for_multi_upload_of_posts()
    {
        return 20;
    }
}

class FactoryForUtils{

    public function replace_underscores($input_text)
    {
        return str_replace("_"," ",$input_text."");
    }

    public function createCommaSeparatedNumber($text){
        if(!is_numeric($text)){
            return $text;
        }
        //format and return new text object
        $number = strrev("". intval($text));
        $str_result = "";
        $last_index = strlen($number) - 1;
        $first_index = 0;
        for($current_index = $first_index; $current_index <= $last_index ;$current_index++){
            if($current_index % 3 == 0 && $current_index > $first_index){
                $str_result .= ",";
            }
            $str_result .= $number[$current_index];
        }
        //reverse the array
        $comma_separated_value = strrev($str_result);
        return $comma_separated_value;
    }
    public function format_as_currency($text){
        $text = $text ? $text : 0;
        return "Ushs.&nbsp;".self::createCommaSeparatedNumber($text);
    }

    public function format_as_proper_noun($text)
    {
        //replace extra spaces with single space
        $text = preg_replace("/\s+/i"," ",$text);

        //now split using space
        $parts = explode(" ",$text);
        $parts = is_array($parts) ? $parts : array();
        //capitalize each part and make the rest small case
        $resulting_words = array();
        foreach($parts as $part){
            $first_letter = substr($part,0,1);
            $other_letters = substr($part,1);

            $final_word = strtoupper($first_letter).strtolower($other_letters);
            $resulting_words[] = $final_word;
        }
        return join(" ",$resulting_words);
    }
    public function capitalize_first_letter($text)
    {
        $first_letter = strtoupper(substr($text,0,1));
        $other_letters = substr($text,1);
        return $first_letter.$other_letters;
    }
    public function limit_text_to_length($text,$length){
        return strlen($text) > $length ? substr($text,0,$length - 3)."...": $text;
    }
    public function add_label_to_total($total,$label_if_one,$label_otherwise){
        $total = "".$total;
        $output =  sprintf("%s %s",
            $total,
            $total == "1" ? $label_if_one : $label_otherwise
        );
        return $output;
    }

    public function choose_label_for_total($total, $label_if_one, $label_otherwise){
        return $total == 1 ? $label_if_one : $label_otherwise;
    }

    public function linear_interpolation($cur_window_size, $min_window_size=240, $max_window_size=1200, $min_value=12, $max_value=17, $num_decimal_points = 0)
    {
        $percent = ($cur_window_size - $min_window_size) / ($max_window_size - $min_window_size);
        $cur_font_size = $percent * ($max_value - $min_value) + $min_value;
        $cur_font_size = max($cur_font_size,$min_value);
        $cur_font_size = min($cur_font_size,$max_value);
        $cur_font_size = round($cur_font_size,$num_decimal_points);
        return $cur_font_size;
    }

    public function nulls_to_zero($value)
    {
        return $value ? $value : 0; 
    }
}