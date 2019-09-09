<?php

abstract class BaseClassOfReaderForDataStoredInArray{
    private $array = array();
    public function read_key($key){
        $value = array_key_exists($key,$this->array)? $this->array[$key]:"";
        $value = $this->should_escape_html_special_chars && is_string($value) ? htmlspecialchars($value): $value;
        $value = is_string($value) && $this->should_decode_as_utf8 ? utf8_decode($value): $value;//todo:order matters: encoding should be last!!
        return $value;
    }
    private $should_escape_html_special_chars = true;
    private $should_decode_as_utf8 = false;

    public function dump_array(){
        print json_encode($this->array);
    }

    public function __construct($array)
    {
        $this->throwExceptionIfNotArray($array);
        $this->array = $array;
    }
    public function disable_escape_html_special_chars(){
        $this->should_escape_html_special_chars = false;
    }

    public function iterator(){
        return new ArrayIterator($this->array);
    }
    public function all(){
        return $this->array;
    }
    //todo: rename to all
    public function get_array(){
        return  $this->array;
    }
   
    public function count(){
        return count($this->array);
    }
     
    public function at($entity_index)
    {
        return $this->read_key($entity_index);
    }

    /**
     * @param $array
     * @throws Exception
     */
    private function throwExceptionIfNotArray($array)
    {
        if (!is_array($array)) {
            throw new Exception("expects array as input for reader constructor");
        }
    }

    //==============================
    abstract protected function getNewInstance($array);

    public function get_reader_for_item_at($entity_index){
        $item = $this->at($entity_index);
        $item = is_array($item) ? $item : [];
        return $this->getNewInstance($item);
    }
    public function get_reader_for_item_1(){
        return $this->get_reader_for_item_at(0);
    }
    public function get_reader_for_item_2(){
        return $this->get_reader_for_item_at(1);
    }
    public function get_reader_for_item_3(){
        return $this->get_reader_for_item_at(2);
    }
    public function get_reader_for_item_4(){
        return $this->get_reader_for_item_at(3);
    }
    public function get_reader_for_item_5(){
        return $this->get_reader_for_item_at(4);
    }
    public function get_reader_for_item_6(){
        return $this->get_reader_for_item_at(5);
    }
    public function get_reader_for_item_7(){
        return $this->get_reader_for_item_at(6);
    }
    public function get_reader_for_item_8(){
        return $this->get_reader_for_item_at(7);
    }

    public function get_reader_for_item_9(){
        return $this->get_reader_for_item_at(8);
    }

    public function get_reader_for_item_10(){
        return $this->get_reader_for_item_at(9);
    }
    public function get_reader_for_item_11(){
        return $this->get_reader_for_item_at(10);
    }
    public function get_reader_for_item_12(){
        return $this->get_reader_for_item_at(11);
    }
    public function get_reader_for_item_13(){
        return $this->get_reader_for_item_at(12);
    }
    public function get_reader_for_item_14(){
        return $this->get_reader_for_item_at(13);
    }
    public function get_reader_for_item_15(){
        return $this->get_reader_for_item_at(14);
    }
    public function get_reader_for_item_16(){
        return $this->get_reader_for_item_at(15);
    }
    public function get_reader_for_item_17(){
        return $this->get_reader_for_item_at(16);
    }
    public function get_reader_for_item_18(){
        return $this->get_reader_for_item_at(17);
    }
    public function get_reader_for_item_19(){
        return $this->get_reader_for_item_at(18);
    }
    public function get_reader_for_item_20(){
        return $this->get_reader_for_item_at(19);
    }

}