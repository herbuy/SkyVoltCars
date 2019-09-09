<?php
class ThrowSeleniumExceptionIfNot{
    public static function command($variable){
        if(!is_a($variable,"SeleniumCommand")){
            throw new Exception("expected a selenium command object");
        }
    }
}
class SeleniumCommand{
    private $name = "";
    private $target = "";
    private $value = "";

    public function __construct($name)
    {
        $this->name = $name;
    }
    protected function target($target){
        $this->target = $target;
        return $this;
    }
    public function value($value){
        $this->value = $value;
        return $this;
    }

    public function __toString()
    {
        $markup_for_command = $this->data_cell($this->name);
        $markup_for_target = $this->data_cell($this->target);
        $markup_for_value = $this->data_cell($this->value);
        return sprintf("<tr>%s %s %s</tr>",$markup_for_command,$markup_for_target,$markup_for_value);
        // TODO: Implement __toString() method.
    }


    private function data_cell($content)
    {
        return sprintf("<td>%s</td>",$content);
    }
}
class SeleniumCommandForOpen extends SeleniumCommand{
    public function __construct($url_as_string)
    {
        $this->target($url_as_string);
        parent::__construct("open");
    }
}
abstract class SeleniumCommandForClickAndWait extends SeleniumCommand{
    public function __construct($key,$value)
    {
        $this->target($key."=".$value);
        parent::__construct("clickAndWait");
    }
}

class SeleniumCommandForClickAndWaitLink extends SeleniumCommandForClickAndWait{
    public function __construct($link_text)
    {
        parent::__construct("link",$link_text);
    }
}
class SeleniumCommandForClickAndWaitCSS extends SeleniumCommandForClickAndWait{
    public function __construct($link_text)
    {
        parent::__construct("css",$link_text);
    }
}
class SeleniumCommandForTypeName extends SeleniumCommand{
    public function __construct($field_name)
    {
        $this->target(sprintf("name=%s",$field_name));
        parent::__construct("type");
    }
}

class SeleniumTestCase{
    private $commands = array();
    /** @var SeleniumCommand null  */
    private $current_command = null;

    /**adds a selenium command to the list of selenium commands for this test case, and sets it as the current command
     * @return SeleniumTestCase */
    public function add($selenium_command){
        ThrowSeleniumExceptionIfNot::command($selenium_command);
        $this->commands[] = $selenium_command;
        $this->current_command = $selenium_command;
        return $this;
    }

    public function value($value){
        $this->current_command->value($value);
        return $this;
    }
    public function __toString()
    {
        return join(" ",$this->commands);
    }
    public function open($url_as_target){
        return $this->add(new SeleniumCommandForOpen($url_as_target));
    }
    public function clickAndWaitLink($linkText)
    {
        return $this->add(new SeleniumCommandForClickAndWaitLink($linkText));
    }
    public function clickAndWaitCSS($selector)
    {
        return $this->add(new SeleniumCommandForClickAndWaitCSS($selector));
    }
    public function typeName($field_name)
    {
        return $this->add(new SeleniumCommandForTypeName($field_name));
    }
}