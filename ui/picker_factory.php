<?php
class PickerFactory{
    public function category($reader_for_categories){
        return new PickerForCategory($reader_for_categories);
    }

    public function page($reader_for_pages)
    {
        return new PickerForPage($reader_for_pages);
    }

    public function section($reader_for_sections,$default_value='')
    {
        return new PickerForSection($reader_for_sections,$default_value);
    }
    public function cars($reader_for_cars, $default_value='')
    {
        return new PickerForCar($reader_for_cars,$default_value);
    }
    public function car_exporters($reader_for_car_exporters, $default_value='')
    {
        return new PickerForCarExporters($reader_for_car_exporters,$default_value);
    }


    public function section_as_multi_input($reader_for_sections,$default_value='',$record_number)
    {
        return new PickerForSectionAsMultiInput($reader_for_sections,$default_value,$record_number);
    }
}