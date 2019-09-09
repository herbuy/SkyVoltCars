<?php

abstract class PickerForMotoka extends SmartSelect{

    /** @param \ReaderForValuesStoredInArray $reader */
    public function __construct($reader,$default_value='')
    {
        ui::exception()->throwIfNotReader($reader);
        
        parent::__construct();
        
        $this->set_name($this->getFinalName());
        $count = $reader->count();
        for($i = 0; $i < $count;$i++){
            $reader_for_item = $reader->get_reader_for_item_at($i);

            $option = new SmartOption();
            $option->add_child($reader_for_item->title());
            $option->set_value($reader_for_item->entity_id());

            $browser_value = $this->value_from_browser();
            
            if($reader_for_item->entity_id() == $browser_value){
                $option->set_attribute("selected","selected");
            }
            else if($reader_for_item->entity_id() == $default_value){
                $option->set_attribute("selected","selected");
            }
            $this->add_child($option);
        }

    }
    protected function is_array()
    {
        return false;
    }
    private function getFinalName(){
        return $this->is_array() ? $this->get_name()."[]" : $this->get_name();
    }

    abstract protected function get_name();
    abstract protected function value_from_browser();
}
class PickerForCategory extends PickerForMotoka{
    protected function get_name()
    {
        return app::values()->category_id();
    }
    protected function value_from_browser()
    {
        return ui::browser_fields()->category()->value();
    }
}

class PickerForCar extends PickerForMotoka{
    protected function get_name()
    {
        return app::values()->car_id();
    }
    protected function value_from_browser()
    {
        return ui::browser_fields()->car_id()->value();
    }
}
class PickerForCarExporters extends PickerForMotoka{
    protected function get_name()
    {
        return app::values()->car_exporter_id();
    }
    protected function value_from_browser()
    {
        return ui::browser_fields()->car_exporter_id()->value();
    }
}

class PickerForPage extends PickerForMotoka{
    protected function get_name()
    {
        return app::values()->target_page_id();
    }
    protected function value_from_browser()
    {
        return ui::browser_fields()->target_page_id()->value();
    }
}
class PickerForSection extends PickerForMotoka{
    
    protected function get_name()
    {
        return app::values()->section_id();
    }
    protected function value_from_browser()
    {
        return ui::browser_fields()->section_id()->value();
    }
}
class PickerForSectionAsMultiInput extends PickerForSection{
    private $record_number;

    public function __construct(ReaderForValuesStoredInArray $reader, $default_value, $record_number)
    {
        $this->record_number = $record_number;
        parent::__construct($reader, $default_value);
    }

    protected function is_array()
    {
        return true;
    }
    protected function value_from_browser()
    {
        return @parent::value_from_browser()[$this->record_number];
    }
}