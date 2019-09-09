<?php
class ClassForDebugging{
    public function __construct($file_to_open,$string,$class ='',$function = '')
    {
        $final_value = sprintf(
            "%s | %s / %s | %s",
            date("Y-M-d-h:i:s"),$class,$function,$string."\r\n"

        );
        $this->appendToFile($file_to_open, $final_value);
    }
    private function appendToFile($file_to_open, $final_value)
    {
        $fp = fopen($file_to_open, "a");
        fwrite($fp, $final_value);
        fclose($fp);
    }
}
class StatusMessage extends ClassForDebugging{
    public function __construct($string,$class ='',$function = '')
    {
        parent::__construct("status_messages.txt",$string,$class,$function);        
    }    
}

class TodoListItem extends ClassForDebugging{
    public function __construct($string,$class ='',$function = '')
    {
        parent::__construct("project.todo.list.txt",$string,$class,$function);
    }
}

