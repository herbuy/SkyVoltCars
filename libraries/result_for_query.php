<?php


abstract class ResultForQuery{

    private $mysqli;
    private $query;
    private $donot_commit = false;

    //connection paramters
    private $db_host;
    private $db_username;
    private $db_password;
    private $db_name;

    public function do_not_commit(){
        $this->donot_commit = true;
        return $this;
    }

    protected function mysqli(){
        return $this->mysqli;
    }
    protected function query_string(){

        return $this->query;
    }

    /**
     * @param $result
     * @return bool
     */
    protected function is_mysqli_result($result)
    {
        return is_a($result, "mysqli_result");
    }

    public function __construct($query, $db_host = 'localhost',$db_username='root',$db_password='usbw',$db_name='smartercash_db')
    {
        $this->db_host = $db_host;
        $this->db_username = $db_username;
        $this->db_password = $db_password;
        $this->db_name = $db_name;
        //-------
        $this->query = $query."";
        $this->mysqli = new mysqli();
    }
    private $query_invoked = false;
    public function __destruct()
    {
        if(!$this->query_invoked){
            return;
        }
        
        if($this->mysqli->connect_error || $this->mysqli->error || $this->donot_commit){
            $this->mysqli->rollback();
        }
        else{
            $this->mysqli->commit();
        }
        $this->mysqli->close();
    }

    public function to_array(){
        $this->query_invoked = true;

        $this->connectToDatabase();
        $this->log_query();
        $this->beginTransaction();
        $result = $this->getResult();
        $result_array = $this->convertToArray($result);

        //--------- todo: make it optional since it slows returning of results as file gets bigger
        //$this->log_query();
        return $result_array;
    }
    public function children_starting_at($n){
        $result_array = $this->to_array();
        if(is_array($result_array)){
            $result_array = array_slice($result_array,$n);
            $result_array = is_array($result_array) ? $result_array : array();
        }
        return $result_array;
    }
    public function child_at_index($index){
        $result_array = $this->to_array();
        $value = array_key_exists($index,$result_array) ? $result_array[$index] : array();
        return $value;
    }

    private function connectToDatabase()
    {
        $this->mysqli->connect($this->db_host,$this->db_username,$this->db_password,$this->db_name);
        if ($this->mysqli->connect_error) {
            throw new Exception($this->mysqli->connect_error);
        }
    }

    private function beginTransaction()
    {
        $this->mysqli->autocommit(false);
    }

    abstract protected function getResult();
    /** @param \mysqli_result $result */
    abstract protected function convertToArray($result);

    protected function throw_exception_if_mysqli_error()
    {
        if ($this->mysqli()->error) {
            throw new Exception($this->mysqli()->error);
        }
    }

    private function log_query()
    {
        $fp = fopen("queries.txt","a");
        fwrite($fp, sprintf("thread_id:%s#%s",$this->mysqli()->thread_id.chr(10).chr(13)."---------".chr(10).chr(13), $this->query_string().chr(10).chr(13)));
        fclose($fp);
    }

}


class ResultForSingleQuery extends ResultForQuery{
    protected function getResult()
    {
        $result = $this->mysqli()->query($this->query_string());
        parent::throw_exception_if_mysqli_error();
        return $result;
    }
    protected function convertToArray($result)
    {
        $array = array();
        if($this->is_mysqli_result($result)){
            $this->mysqli()->store_result();
            parent::throw_exception_if_mysqli_error();
            while ($assoc = $result->fetch_assoc()) {
                $array[] = $assoc;
            }
            $result->free();
        }
        return $array;
    }

}

class ResultForMultiQuery extends ResultForQuery{

    protected function getResult()
    {
        $result = $this->mysqli()->multi_query($this->query_string());
        parent::throw_exception_if_mysqli_error();
        return $result;
    }

    protected function convertToArray($result)
    {
        $array = array();
        do{
            $array[] = $this->turn_current_result_into_array();
        }
        while($this->mysqli()->more_results() && $this->mysqli()->next_result());
        $this->throw_exception_if_mysqli_error();//this line helps detect errors in multi-query - case where next_result returns false
        return $array;
    }

    private function turn_current_result_into_array()
    {
        $result2 = $this->mysqli()->store_result();
        parent::throw_exception_if_mysqli_error();
        $array = array();
        if($this->is_mysqli_result($result2)){
            while ($assoc = $result2->fetch_assoc()) {
                $array[] = $assoc;
            }
            $result2->free();
        }
        return $array;
    }
}


