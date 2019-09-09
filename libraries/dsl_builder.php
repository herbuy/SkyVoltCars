<?php

class DSLBuilder{
    private $array_of_content = array();
    protected function add($preposition,$noun){
        $string = sprintf("%s %s", $preposition,$noun);
        $this->array_of_content[] = $string;
        return $this;
    }
    public function are($entity){
        return $this->add("are", $entity);
    }
    public function of($entity){
        return $this->add("of", $entity);
    }
    public function for_($entity){
        return $this->add("for", $entity);
    }
    public function on($entity){
        return $this->add("on", $entity);
    }
    public function to($entity){
        return $this->add("to", $entity);
    }
    public function from($entity){
        return $this->add("from", $entity);
    }
    public function by($entity){
        return $this->add("by", $entity);
    }
    public function at($entity){
        return $this->add("at", $entity);
    }
    public function subject($string)
    {
        $this->array_of_content[] = $string;
        return $this;
    }
    public function verb($string)
    {
        $this->array_of_content[] = $string;
        return $this;
    }
    public function object($string)
    {
        $this->array_of_content[] = $string;
        return $this;
    }

    public function __toString()
    {
        return join(" ",$this->array_of_content);
    }
}