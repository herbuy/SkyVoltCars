<?php
//**** one of the reasons for using intefaces rather implementation is that:
//a) we might need to build each part e.g not every select expression will be built the way you are building it
//b) so we want to program to requirements i.e what kind needs to be passed as argument, etc, not how it is implemented.
//c) some implementatation may: a) build from others b) proxy others c) adapt others d) switch implementors
//e) augment the work of others [decorate them], f) etc

interface ISQLValue{
    /*public function inList($sql_value_list);
    public function isEqualTo($sql_value);
    public function addToNewValueList();*/
    public function __toString();
    public function as_($identifer);

}
abstract class SQLValueBase implements ISQLValue{

    /** @return SQLColumnToOrderBy */
    public function ascending()
    {
        return  new SQLColumnToOrderBy($this);
    }

    /** @return SQLColumnToOrderBy */
    public function descending()
    {
        $column = new SQLColumnToOrderBy($this);
        $column->shouldBeDescending();
        return $column;
    }
    
    public function append($value)
    {
        $concat_function = new SQLConcatFunction();
        $concat_function->add($this);
        $concat_function->add($value);
        return $concat_function;
    }
    
    public function if_($sql_test){
        //throw exception if not sql test        
        $this->throwExceptionIfNotSQLTest($sql_test);
        return SQLIFFunction::IfTrueThat($sql_test,$this,new SQLNull());
    }
    
    /**
     * @param $tableIdentifier
     * @param $message
     * @throws Exception
     */
    protected function throwExceptionIfNotTableIdentifier($tableIdentifier, $message)
    {
        if (!$this->isTableIdentifier($tableIdentifier)) {
            throw new Exception($message);
        }
    }
    /**
     * @param $sql_identifier
     * @param $message
     * @throws SQLBuilderException
     */
    protected function throwIfNotSQLIdentifier($sql_identifier, $message)
    {
        if (!$this->isSQLIdentifier($sql_identifier)) {
            throw new SQLBuilderException($message);
        }
    }

    protected function converToSQLValueIfNumberOrString($value_as_num_or_string_or_sqlvalue)
    {
        if(is_numeric($value_as_num_or_string_or_sqlvalue)){
            return new SQLInt($value_as_num_or_string_or_sqlvalue);
        }
        else if(is_string($value_as_num_or_string_or_sqlvalue)){
            return new SQLString($value_as_num_or_string_or_sqlvalue);
        }
        else{
            return $value_as_num_or_string_or_sqlvalue;
        }        
    }
    private $alias_identifier;
    protected function set_alias_identifier($identifier){        
        $identifier = ConvertToSQLIdentifier::if_string($identifier);        
        SQLBuilderException::throwIfNot($this->isSQLIdentifier($identifier),"expects SQLIdentifier for alias");
        $this->alias_identifier = $identifier;
    }
    /** @return SQLIdentifier */
    protected function get_alias_identifier(){
        return $this->alias_identifier;
    }
    protected function has_alias(){
        return null != $this->alias_identifier;
    }

    abstract public function as_($identifer);
    public static function isJoinedTable($table){
        return is_a($table,"SQLJoinedTable");
    }
    protected function isSQLIdentifierValuePair($column_value_pair)
    {
        return is_a($column_value_pair,"SQLIdentifierValuePair");
    }

    /**
     * @param $listOfColumnsToOrderBy
     * @return bool
     */
    protected function isSQLListOfColumnsToOrderBy($listOfColumnsToOrderBy)
    {
        return is_a($listOfColumnsToOrderBy, "SQLListOfColumnsToOrderBy");
    }

    /**
     * @param $sql_column_to_order_by
     * @return bool
     */
    protected function isSQLColumnToOrderBy($sql_column_to_order_by)
    {
        return is_a($sql_column_to_order_by, "SQLColumnToOrderBy");
    }
    /**
     * @param $sql_identifier
     * @return bool
     */
    protected function isSQLIdentifier($sql_identifier)
    {
        return is_a($sql_identifier, "ISQLIdentifier");        
    }


    protected function isSQLEverything($identifier_or_everything)
    {
        return is_a($identifier_or_everything,"SQLEverything");
    }

    /**
     * @param $test
     * @return bool
     */
    protected function isATest($test)
    {
        return self::isTest($test);
    }
    public static function isTest($test){
        return is_a($test, "ISQLTest");
    }
    
    
    protected function isTableIdentifier($table){
        return is_a($table,"SQLTableIdentifier");
    }
    protected function isTable($table){
        return is_a($table,"ISQLTable");
    }
    
    /**
     * @param $tableList
     * @return bool
     */
    protected function isTableList($tableList)
    {
        return is_a($tableList, "SQLTableList");
    }
    /**
     * @param $sql_value_or_value_list
     * @return bool
     */
    protected function isSQLValueList($sql_value_or_value_list)
    {
        return is_a($sql_value_or_value_list, "SQLValueList");
    }
    protected function isSelectStatement($sql_value){
        return is_a($sql_value, "SQLSelectExpression");
    }
    protected function isSelectStatementWithNoAlias($sql_value){
        return $this->isSelectStatement($sql_value) &&
        !$sql_value->has_alias();
    }
    protected function isInsertStatement($sql_value){
        return is_a($sql_value, "SQLInsertQuery");
    }
    protected function isUpdateQuery($sql_value){
        return is_a($sql_value, "SQLUpdateQuery");
    }
    protected function isDeleteQuery($sql_value){
        return is_a($sql_value, "SQLDeleteQuery");
    }

    /**
     * @param $sql_value_or_value_list
     * @return bool
     */
    protected function isSQLValue($sql_value_or_value_list)
    {
        return is_a($sql_value_or_value_list, "ISQLValue");
    }

    protected function treatAsSubQueryIfSelectQuery($sql_value){
        if($this->isSelectStatement($sql_value)){
            $sql_value->treatAsSubExpression();
        }
        return $sql_value;
    }
    public function isEqualTo($sql_value2){
        $sql_value2 = $this->converToSQLValueIfNumberOrString($sql_value2);
        $this->treatAsSubQueryIfSelectQuery($this);
        $this->treatAsSubQueryIfSelectQuery($sql_value2);
        return new SQLEqualityTest(
            $this,
            $sql_value2
        );
    }
    public function isTrue()
    {
        return $this->isEqualTo(new SQLTrue());
    }
    public function isFalse()
    {
        return $this->isEqualTo(new SQLFalse());
    }
    public function isEqualToZero()
    {
        return $this->isEqualTo(new SQLInt(0));
    }
    public function isGreaterThanZero()
    {
        return $this->isGreaterThanInt(0);
    }
    
    public function isNull()
    {
        return new SQLIsNullTest($this);
    }
    public function isNotNull()
    {
        return new SQLIsNotNullTest($this);
    }
    

    public function isNotEqualTo($sql_value2){
        $sql_value2 = ConvertToSQLValue::ifNotSQLValue($sql_value2);
        
        $this->treatAsSubQueryIfSelectQuery($this);
        $this->treatAsSubQueryIfSelectQuery($sql_value2);
        return new SQLNotEqualTest(
            $this,
            $sql_value2
        );
    }
    
    public function isGreaterThan($sql_value2){
        $sql_value2 = $this->converToSQLValueIfNumberOrString($sql_value2);
        $this->treatAsSubQueryIfSelectQuery($this);
        $this->treatAsSubQueryIfSelectQuery($sql_value2);
        return new SQLGreaterThanTest(
            $this,
            $sql_value2
        );
    }
    public function isGreaterOrEqualTo($sql_value2){
        $this->treatAsSubQueryIfSelectQuery($this);
        $this->treatAsSubQueryIfSelectQuery($sql_value2);
        return new SQLGreaterOrEqualTest(
            $this,
            $sql_value2
        );
    }
    public function isLessThan($sql_value2){
        $sql_value2 = ConvertToSQLValue::ifNotSQLValue($sql_value2);
        $this->treatAsSubQueryIfSelectQuery($this);
        $this->treatAsSubQueryIfSelectQuery($sql_value2);

        return $ouput = new SQLLessThanTest(
            $this,
            $sql_value2
        );
    }

    public function isLike($sql_value2){
        $this->treatAsSubQueryIfSelectQuery($this);
        $this->treatAsSubQueryIfSelectQuery($sql_value2);
        return new SQLLikeTest(
            $this,
            $sql_value2
        );
    }
    public function isBetween($sql_value_min,$sql_value_max){
        $this->treatAsSubQueryIfSelectQuery($this);
        $this->treatAsSubQueryIfSelectQuery($sql_value_min);
        $this->treatAsSubQueryIfSelectQuery($sql_value_max);
        return new SQLBetweenTest(
            $this,
            $sql_value_min,
            $sql_value_max
        );
    }
    //====== EXTENSION COMPARISONS ==============
    
    public function isEqualToInt($int){
        return $this->isEqualTo(new SQLInt($int));
    }
    public function isNotEqualToInt($int){
        return $this->isNotEqualTo(new SQLInt($int));
    }
    
    public function isEqualToString($string){
        return $this->isEqualTo(new SQLString($string));
    }

    public function isGreaterThanInt($int){
        return $this->isGreaterThan(new SQLInt($int));
    }
    public function isGreaterOrEqualToInt($int){
        return $this->isGreaterOrEqualTo(new SQLInt($int));
    }
    public function isLessThanInt($int){
        return $this->isLessThan(new SQLInt($int));
    }
    public function isLessOrEqualToInt($int){
        return $this->isLessOrEqualTo(new SQLInt($int));
    }

    
    //======== end of extension comparisons
    public function inList($sql_value_list){
        $in_list_test = new SQLInListTest();
        $in_list_test->setValueToTest($this);
        $in_list_test->setPossibleMatches($sql_value_list);
        return $in_list_test;
    }
    public function addToNewValueList(){
        $sql_value_list = new SQLValueList();
        $sql_value_list->add($this);
        return $sql_value_list;
    }

    public function isLessOrEqualTo($sql_value2){
        $this->treatAsSubQueryIfSelectQuery($this);
        $this->treatAsSubQueryIfSelectQuery($sql_value2);
        return new SQLLessOrEqualTest(
            $this,
            $sql_value2
        );
    }
    public function isLikeString($string){
        return $this->isLike(new SQLString($string));
    }
    public function isBetweenInt($int_min, $int_max){
        return $this->isBetween(new SQLInt($int_min), new SQLInt($int_max));
    }
    
    //=========== arithmetic
    public function plus($sql_value2){        
        return new SQLAdditionExpression(
            $this,
            $sql_value2
        );
    }
    public function minus($sql_value2){
        return new SQLSubtractionExpression(
            $this,
            $sql_value2
        );
    }
    public function multiply_by($sql_value2){
        return new SQLMultiplicationExpression(
            $this,
            $sql_value2
        );
    }
    /** @return SQLDivisionExpression */
    public function divide_by($sql_value2){
        return new SQLDivisionExpression(
            $this,
            $sql_value2
        );
    }
    public function modulo($sql_value2){
        return new SQLModulusExpression(
            $this,
            $sql_value2
        );
    }
    
    public function plus_int($number){
        return $this->plus(new SQLInt($number));
    }
    public function minus_int($number){
        return $this->minus(new SQLInt($number));
    }
    public function multiply_by_int($number){
        return $this->multiply_by(new SQLInt($number));
    }
    public function divide_by_int($number){
        return $this->divide_by(new SQLInt($number));
    }
    public function floor2(){
        return SQLFunction::floor($this);
    }
    public function ceiling2(){
        return SQLFunction::ceiling($this);
    }
    public function round2($num_decimal_places){
        return SQLFunction::round($this,$num_decimal_places);
    }
    public function pow2($power){
        return SQLFunction::pow($this,$power);
    }
    public function sqrt2(){
        return SQLFunction::sqrt($this);
    }
    
    public function modulo_int($number){
        return $this->modulo(new SQLInt($number));
    }
    public function length(){
        return new SQLLengthFunction($this);
    }
    public function trim(){
        return new SQLTrimFunction($this);
    }
    public function trim_leading($value_or_sql){
        return new SQLTrimLeadingFunction($this,$value_or_sql);
    }
    public function trim_trailing($value_or_sql){
        return new SQLTrimTrailingFunction($this,$value_or_sql);
    }
    public function trim_both($value_or_sql){
        return new SQLTrimBothFunction($this,$value_or_sql);
    }
    public function log10(){
        return new SQLLogFunction(10,$this);
    }
    public function log($base){
        return new SQLLogFunction($base,$this);
    }
    public function log_plus_number($base,$plus_number){
        $plus_number = ConvertToSQLValue::ifNotSQLValue($plus_number);
        return new SQLLogFunction($base,$this->plus($plus_number));
    }
    public function log_plus_1($base){
        return new SQLLogFunction($base,$this->plus_int(1));
    }
    public function log10_plus_1(){
        return new SQLLogFunction(10,$this->plus_int(1));
    }

    /**
     * @param $sql_test
     * @throws SQLBuilderException
     */
    protected function throwExceptionIfNotSQLTest($sql_test)
    {
        if (!$this->isTest($sql_test)) {
            throw new SQLBuilderException("expected a SQL test");
        }
    }

    //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@22222222222222222222222
    public function concat2()
    {
        return new SQLConcatFunction($this);
    }
    public function group_concat2()
    {
        return new SQLGroupConcatFunction($this);
    }

    public function rpad2($length,$pad_string){
        $function = new SQLRPadFunction($this,$length,$pad_string);
        return $function;
    }
    public function date_format2($sql_format_string){
        $function = new SQLDateFormatFunction($this,$sql_format_string);
        return $function;
    }
    
    public function md52(){
        $function = new SQLMD5Function($this);
        return $function;
    }
    public function sha12(){
        $function = new SQLSha1Function($this);
        return $function;
    }

    public function date2()
    {
        return new SQLFunctionForDate($this);
    }
    public function cur_date2()
    {
        return new SQLFunctionForCurDate($this);
    }

    public function year2()
    {
        return new SQLFunctionForYear($this);
    }

    public function month2()
    {
        return new SQLFunctionForMonth($this);
    }

    public function month_name2()
    {
        return new SQLFunctionForMonthName($this);
    }
    public function month_description2()
    {
        return SQLFunction::month_name($this)->append(
            " "
        )->
        append(
            SQLFunction::year($this)
        );
    }

    public function week2()
    {
        return new SQLFunctionForWeek($this);
    }
    public function week_of_year2()
    {
        return new SQLFunctionForWeekOfYear($this);
    }
    public function week_of_year_description2()
    {
        return
            (new SQLString("Week "))->
            append(
                SQLFunction::week_of_year($this)
            )->
            append(" of ")->
            append(
                SQLFunction::year($this)
            );
    }
    public function day_of_year_description2()
    {
        return
            (new SQLString("Day "))->
            append(
                SQLFunction::day($this)
            )->
            append(" of ")->
            append(
                SQLFunction::month_name($this)
            )->
            append(" ")->
            append(
                SQLFunction::year($this)
            );
    }

    public function day2()
    {
        return new SQLFunctionForDay($this);
    }
    public function day_of_week2()
    {
        return new SQLFunctionForDayOfWeek($this);
    }
    public function day_of_year2()
    {
        return new SQLFunctionForDayOfYear($this);
    }
    public function day_name2()
    {
        return new SQLFunctionForDayName($this);
    }

    public function from_unixtime2()
    {
        return new SQLFunctionForFromUnixTime($this);
    }

}

abstract class SQLValue{
    /**
     * @param $value
     * @return bool
     */
    public static function isValid($value)
    {
        return is_a($value, "ISQLValue");
    }

    public static function isATest($var){
        return is_a($var,"ISQLTest");
    }

    public static function throwExceptionIfNotValid($value){
        self::throwExceptionIf(!self::isValid($value),sprintf("invalid value %s",$value));
    }
    public static function throwExceptionIf($booleanValue,$message){
        if($booleanValue){
            throw new SQLBuilderException($message);
        }
    }
    public static function throwExceptionIfNot($booleanValue,$message){
        self::throwExceptionIf(!$booleanValue,$message);
    }
}

abstract class QuottedValue extends SQLValueBase{

    private $value;
    private $quote = "";
    protected function quote()
    {
        return $this->quote;
    }

    protected function getValue(){
        return $this->value;
    }

    public function __construct($string, $quote)
    {
        $string = utf8_encode($string);
        $this->value = addslashes($string);
        $this->quote = $quote;
    }

    public function __toString()
    {
        $result = join(array($this->quote(),$this->value,$this->quote()));
        if($this->has_alias()){
            $result = sprintf("%s AS %s",$result, $this->get_alias_identifier());
        }
        return $result;
    }

}

interface ISQLString extends ISQLValue{
    
}
class SQLString extends QuottedValue implements ISQLString{
    public function __construct($string)
    {
        parent::__construct("".$string,"'");
    }
    /** return SQLString */
    public function as_($identifier)
    {
        $sql_value = new SQLString($this->getValue());
        $sql_value->set_alias_identifier($identifier);
        return $sql_value;
    }
}
interface ISQLInt{

}
class SQLNull extends SQLValueBase{
    public function as_($identifer)
    {
        $this->set_alias_identifier($identifer);
        return $this;
    }
    public function __toString()
    {
        $alias_string = $this->has_alias() ? sprintf(" AS %s",$this->get_alias_identifier()):"";
        return sprintf("NULL%s",$alias_string);
    }
}
class SQLInt extends QuottedValue implements ISQLInt{
    public function __construct($number)
    {        
        $number = trim($number);
        SQLValue::throwExceptionIfNot(is_numeric($number),"expects an integer");
        parent::__construct(trim($number),"");
    }

    public function and_($sql_test){
        return SQLUtils::and_($this,$sql_test);
    }
    /** return SQLInt */
    public function as_($identifier)
    {
        $sql_value = new SQLInt($this->getValue());
        $sql_value->set_alias_identifier($identifier);
        return $sql_value;
    }
    
    
}
class SQLTrue extends SQLInt{
    public function __construct()
    {
        parent::__construct(1);
    }
}
class SQLFalse extends SQLInt{
    public function __construct()
    {
        parent::__construct(0);
    }
}

class SQLUtils{
    
    /** @param \SQLListOfColumnsToOrderBy $listOfColumnsToOrderBy
     *@return SQLListOfColumnsToOrderBy 
     */    
    public static function then_by($listOfColumnsToOrderBy, $sql_value_to_order_by){
        $listOfColumnsToOrderBy->add($sql_value_to_order_by);
        return $listOfColumnsToOrderBy;
    }
    
    public static function isEqualTo($sql_value1,$sql_value2){
        return new SQLEqualityTest(
            $sql_value1,
            $sql_value2
        );
    }

    public static function isGreaterThan($sql_value1,$sql_value2){
        return new SQLGreaterThanTest(
            $sql_value1,
            $sql_value2
        );
    }
    public static function isGreaterOrEqualTo($sql_value1,$sql_value2){
        return new SQLGreaterOrEqualTest(
            $sql_value1,
            $sql_value2
        );
    }
    public static function isLessThan($sql_value1,$sql_value2){
        return new SQLLessThanTest(
            $sql_value1,
            $sql_value2
        );
    }
    public static function inList($sql_value,$sql_value_list){
        $in_list_test = new SQLInListTest();
        $in_list_test->setValueToTest($sql_value);
        $in_list_test->setPossibleMatches($sql_value_list);
        return $in_list_test;
    }
    public static function addToNewValueList($sql_value){
        $sql_value_list = new SQLValueList();
        $sql_value_list->add($sql_value);
        return $sql_value_list;
    }
    
    public static function isLessOrEqualTo($sql_value1,$sql_value2){
        return new SQLLessOrEqualTest(
            $sql_value1,
            $sql_value2
        );
    }

    public static function and_($sql_test, $sql_test2){
        $all_true_test = new SQLAllTrueTest();
        $all_true_test->add($sql_test);
        $all_true_test->add($sql_test2);
        return $all_true_test;
    }
    public static function or_($sql_test, $sql_test2){
        $any_true_test = new SQLAnyTrueTest();
        $any_true_test->add($sql_test);
        $any_true_test->add($sql_test2);
        return $any_true_test;
    }

    public static function and_not($sql_test, $sql_test2){
        $all_true_test = new SQLAndNotTest();
        $all_true_test->add($sql_test);
        $all_true_test->add($sql_test2);
        return $all_true_test;
    }

    

}

interface ISQLIdentifier extends ISQLValue{
    
}
class SQLIdentifier extends QuottedValue implements ISQLIdentifier{

    //=====
    private $table_name;
    protected $table_identifier;

        /** return SQLIdentifier */
    public function as_($identifier)
    {
        $sql_value = new SQLIdentifier($this->getValue(),$this->table_name);
        $sql_value->set_alias_identifier($identifier);
        return $sql_value;
    }

    /**  
     * @return SQLIdentifierWithTableName      
     */
    public function inTable($tableIdentifier)
    {
        $tableIdentifier = ConvertToSQLTableIdentifier::if_string($tableIdentifier);
        $this->throwExceptionIfNotTableIdentifier($tableIdentifier, "expected table identifier");
       return $tableIdentifier->columnIdentifier($this); 
    }
    public function inNewRow(){
        $column = new SQLColumnInNewRow();
        $column->columnIdentifier($this);
        return $column;
    }
    public function inOldRow(){
        $column = new SQLColumnInOldRow();
        $column->columnIdentifier($this);
        return $column;
    }
    
    protected function getQuotationMark(){
        return "`";
    }

    public function __construct($string, $table_name_or_identifier=null)
    {
        if(trim($string) == ""){
            throw new SQLBuilderException("empty value for identifier");
        }        
        parent::__construct($string,$this->getQuotationMark());

        if(is_string($table_name_or_identifier)){
            $table_name_or_identifier = trim($table_name_or_identifier);
            $this->table_identifier = ConvertToSQLTableIdentifier::if_string($table_name_or_identifier);
            $this->table_name = $this->table_identifier->getValue();
        }
        else if ($this->isTableIdentifier($table_name_or_identifier)){
            $this->table_identifier = $table_name_or_identifier;
            $this->table_name = $this->table_identifier->getValue();
        }
        else{
            
        }
    }
    
    public function toFalse(){
        return $this->toSQLValue(new SQLFalse());
    }
    public function toTrue(){
        return $this->toSQLValue(new SQLTrue());
    }

    public function toInt($int){
        return $this->toSQLValue(new SQLInt($int));
    }
    public function toStringValue($string){
        return $this->toSQLValue(new SQLString($string));
    }
    public function toSQLValue($sql_value)
    {
        $sql_value = ConvertToSQLValue::ifNotSQLValue($sql_value);
        SQLBuilderException::throwIfNot($this->isSQLValue($sql_value),"expects a SQLValue");
        return new SQLIdentifierValuePair($this,$sql_value);
    }

    public function toMonthDescription($date_in_full)
    {                
        return new SQLIdentifierValuePair($this,SQLFunction::month_description($date_in_full));
    }
    
    public function toConditionalValue($sqlif_function)
    {
        if(!is_a($sqlif_function, "SQLIFFunction")){
            throw new SQLBuilderException("expected a SQLIf function");
        }
        return new SQLIdentifierValuePair($this,$sqlif_function);        
    }
    
    
    public function __toString()
    {
        return $this->table_identifier ? join(".",array($this->table_identifier,parent::__toString())): parent::__toString();
    }
    
    //extendsions
    public function sum(){
        return new SQLSUMFunction($this);
    }
    public function max(){
        return new SQLMaxFunction($this);
    }
    public function min(){
        return new SQLMinFunction($this);
    }
    public function count(){
        return new SQLCountFunction($this);
    }
    public function average(){
        return new SQLAverageFunction($this);
    }

}
class SQLIdentifierWithTableName extends SQLIdentifier{

    public function __construct($string_or_sql_identifier, $table_name)
    {
        SQLBuilderException::throwIfNotStringOrSQLIdentifier($string_or_sql_identifier);
        $value = is_a($string_or_sql_identifier,"SQLIdentifier") ? $string_or_sql_identifier->getValue() : $string_or_sql_identifier;
        parent::__construct($value,$table_name);
    }
}

class SQLColumnToOrderBy extends SQLValueBase{
    public function as_($identifier)
    {
        return $this;
    }
    private $order = " ASC";
    private $identifier;
    public function __construct($sql_value)
    {
        SQLBuilderException::throwIfNot($this->isSQLValue($sql_value),"expects a SQLValue for an order by clause");
        $this->treatAsSubQueryIfSelectQuery($sql_value);
        $this->identifier = $sql_value;
    }
    public function shouldBeDescending(){
        $this->order = " DESC";
    }
    public function __toString()
    {
        return $this->identifier.$this->order;
    }

    /** @return SQLListOfColumnsToOrderBy */
    public function then_by($sql_value_to_order_by)
    {
        $list = SQLUtils::then_by(new SQLListOfColumnsToOrderBy(), $this);
        $list = SQLUtils::then_by($list, $sql_value_to_order_by);
        return $list;
    }
    
}

class SQLBuilderException extends Exception{
    public static function throw_($message_as_string){
        throw new SQLBuilderException($message_as_string);
    }
    public static function throwIf($condition, $message_as_string){
        if(!$condition){
            return;
        }
        throw new SQLBuilderException($message_as_string);
    }
    public static function throwIfNot($condition, $message_as_string){
        self::throwIf(!$condition,$message_as_string);
    }
    public static function throwIfNotTableIdentifier($object,$message){
        self::throwIfNot(is_a($object,"SQLTableIdentifier"),$message);
    }
    public static function throwIfNotSQLIdentifier($object,$message){
        self::throwIfNot(is_a($object,"SQLIdentifier"),$message);
    }
    public static function throwIfNotSQLIdentifierValuePair($object,$message){
        self::throwIfNot(is_a($object,"SQLIdentifierValuePair"),$message);
    }
    public static function throwIfNotSQLIdentifierList($object,$message){
        self::throwIfNot(is_a($object, "SQLIdentifierList"),$message);
    }
    public static function throwIfNotSQLTest($object){
        self::throwIfNot(is_a($object,"ISQLTest"),"sql test expected");
    }
    public static function throwIfNotSQLCommandOrCommandList($object){
        self::throwIfNot(
            is_a($object, "ISQLCommand") ||
            is_a($object, "SQLCommandList"),
            "expected sql command or command list"
        );
    }
    public static function throwIfNotStringOrSQLIdentifier($object){
        self::throwIfNot(
            is_a($object, "SQLIdentifier") ||
            is_string($object),
            "expected string or sql indentifier"
        );
    }
}

interface SQLList{
    public function add($item);
    public function isEmpty();
}

interface ISQLValueList extends SQLList{
    
}

abstract class SQLListBase extends SQLValueBase implements SQLList{
    private $identifier_array = array();
    protected $delimiter = ",";

    public function isEmpty(){
        return $this->count() < 1;
    }
    public function count(){
        return count($this->identifier_array);
    }

    private $position = 0;
    public function reset(){
        $this->position = 0;
    }
    public function hasNext(){
        return $this->position < count($this->identifier_array);
    }
    public function getNext(){
        if(!$this->hasNext()){
            throw new SQLBuilderException("end of sql value list");
        }
        $next = $this->identifier_array[$this->position];
        $this->position++;
        return $next;
    }

    /** @param /ISQLIdentifier $identifier */
    public function add($item){
        $item = ConvertToSQLValue::ifNotSQLValue($item);

        SQLBuilderException::throwIfNot(is_a($item, $this->getItemClass()),"expected ".$this->getItemClass());

        $this->treatAsSubQueryIfSelectQuery($item);
        $this->identifier_array[] = $item;        
        return $this;
    }
    public function add_if($condition,$item){
        if($condition){
            $this->add($item);
        }                
        return $this;
    }
    /** @param \SQLListBase $list
     * */
    public function add_item_or_list($list){
        $list = ConvertToSQLValue::ifNotSQLValue($list);

        if(is_a($list,$this->getItemClass())){
            $this->add($list);
        }
        else if(is_a($list,__CLASS__)){
            $list->reset();
            while($list->hasNext()){
                $item = $list->getNext();
                if(is_a($item,__CLASS__)){
                    $this->add_item_or_list($item);
                }
                else{
                    $this->add_if($item,$item);
                }
            }

        }
        else{
            throw new SQLBuilderException("expected a SQLListBase");
        }
        return $this;
    }
    /** @param \SQLListBase $list
     * */
    public function add_item_or_list_if($condition, $list){
        if($condition){
            $this->add_item_or_list($list);
        }
        return $this;
    }
    private function newLineChar(){
        return chr(10).chr(13);
    }
    public function __toString()
    {

        $delimiter = $this->getListItemDelimiter();
        $delimiter = $this->startEachItemOnANewLine() ? $delimiter.$this->newLineChar() : $delimiter;

        $result = join($delimiter,$this->identifier_array);
        $result = $this->startFirstItemOnANewLine() ? $this->newLineChar().$result : $result;
        $result = $this->addDelimiterAtTheEnd() ? $result.$this->getListItemDelimiter() : $result;
        return $result;
    }
    protected function startFirstItemOnANewLine(){
        return false;
    }
    protected function startEachItemOnANewLine(){
        return false;
    }
    protected function addDelimiterAtTheEnd(){
        return false;
    }
    
    abstract  protected function getItemClass();

    /**
     * @return string
     */
    protected function getListItemDelimiter()
    {
        return $this->delimiter;
    }


}

abstract class SQLValueListBase extends SQLListBase implements ISQLValueList{
    public function as_($identifier)
    {
        return $this;
    }
    /**
     * @return string
     */
    protected function getItemClass()
    {
        return "ISQLValue";
    }
}

interface ISQLIdentifierList{

}

abstract class SQLIdentifierListBase extends SQLValueListBase implements ISQLIdentifierList{
    /**
     * @return string
     */
    protected function getItemClass()
    {
        return "ISQLIdentifier";
    }
}
class SQLListOfColumnsToOrderBy extends SQLValueListBase{
    /**
     * @return string
     */
    protected function getItemClass()
    {
        //return "SQLColumnToOrderBy";
        return "ISQLValue";
    }    
    public function then_by($sql_value_to_order_by){
        return SQLUtils::then_by($this,$sql_value_to_order_by);
    }
}

class SQLIdentifierList extends SQLIdentifierListBase{
}

class SQLIdentifierListInParentheses extends SQLIdentifierListBase{
    public function __toString()
    {
        return "(". parent::__toString() .")";
    }
}


class SQLValueList extends SQLValueListBase{

}
class SQLValueListInParentheses extends SQLValueListBase{
    public function __toString()
    {
        return "(". parent::__toString() . ")";
    }
}

interface ISQLIdentifierValuePair extends ISQLValue{
    public function getIdentifier();
    public function getValue();
}
class SQLIdentifierValuePair extends SQLValueBase implements ISQLIdentifierValuePair{

    public function as_($identifier)
    {
        return $this;
    }
    private $identifier;
    private $value;
    
    public function getIdentifier(){
        return $this->identifier;
    }
    public function getValue(){
        return $this->value;
    }
    
    public function __construct($identifier,$value)
    {
        if(!is_a($identifier,"ISQLIdentifier")){
            throw new SQLBuilderException("expected a SQLIdentifier");
        }
        if(!SQLValue::isValid($value)){
            throw new SQLBuilderException("expected a SQLValue");
        }
        $this->treatAsSubQueryIfSelectQuery($value);        
        $this->identifier = $identifier;
        $this->value = $value;
    }

    public function __toString()
    {
        return join("=",array($this->identifier,$this->value));
    }

}
class SQLIdentifierValuePairList extends SQLValueListBase{
    public function add($item)
    {        
        SQLBuilderException::throwIfNotSQLIdentifierValuePair($item,"expected ISQLIdentifierValuePair");
        parent::add($item); 
    }


}

interface ISQLCommand{
    public function __toString();
}

class SQLInsertQuery extends SQLValueBase implements ISQLCommand{
    public function as_($identifier)
    {
        return $this;
    }

    public function inList($sql_value_list)
    {
        return SQLUtils::inList($this,$sql_value_list)->enclosed_in_parentheses();
    }
    public function addToNewValueList(){
        return SQLUtils::addToNewValueList($this);
    }
    private $column_list;
    private $value_list;
    private $list_of_column_value_pairs;
    private $tableIdentifier;

    public function __construct()
    {
        $this->resetColumnsAndValues();
    }
    private function resetColumnsAndValues(){
        $this->column_list = new SQLIdentifierListInParentheses();
        $this->value_list = new SQLValueListInParentheses();
        $this->list_of_column_value_pairs = new SQLIdentifierValuePairList();
    }
    public function setTableName($tableName){
        $this->tableIdentifier = new SQLIdentifier($tableName);
    }
    /** @param /ISQLIdentifierValuePair $columnValuePair */
    public function addColumnValuePair($columnValuePair){
        SQLValue::throwExceptionIfNot(is_a($columnValuePair,"ISQLIdentifierValuePair"),"expected column value pair");
        $identifier = $columnValuePair->getIdentifier();
        $value = $columnValuePair->getValue();
        if($this->isSelectStatement($value)){
            $value->treatAsSubExpression();
        }
        
        $this->column_list->add($identifier);
        $this->value_list->add($value);
        $this->list_of_column_value_pairs->add($columnValuePair);
    }
    /** @param SQLIdentifierValuePairList $columnValuePairList */
    public function setColumnValuePairList($columnValuePairList){
        SQLValue::throwExceptionIfNot(is_a($columnValuePairList,"SQLIdentifierValuePairList"),"expected a SQLIdentifierValuePairList");
        $this->resetColumnsAndValues();
        while($columnValuePairList->hasNext()){
            $this->addColumnValuePair($columnValuePairList->getNext());
        }
    }

    private function detectAndLogBugs(){
        try{
            if(!$this->tableIdentifier){
                throw new Exception("table not specified");
            }
        }
        catch(Exception $ex){
            file_put_contents("exceptions.from_sql_insert", sprintf("%s in %s on line %s \n\n%s",$ex->getMessage(),$ex->getFile(),$ex->getLine(),$ex->getTraceAsString()));
        }
        
    }

    private $use_set_format = false;
    public function use_set_format(){
        $this->use_set_format = true;
        return $this;
    }
    public function __toString()
    {
        $this->detectAndLogBugs();
        
        $output = $this->use_set_format ? 
            sprintf("%s %s SET %s",$this->command_key_word,$this->tableIdentifier,$this->list_of_column_value_pairs)
            : sprintf("%s %s %s VALUES %s",$this->command_key_word,$this->tableIdentifier,$this->column_list,$this->value_list);
        return $output;
    }

    private $command_key_word = "INSERT INTO";
    public function on_duplicate_key_ignore(){
        $this->command_key_word = "INSERT IGNORE INTO";
        return $this;
    }
    public function on_duplicate_key_replace(){
        $this->command_key_word = "REPLACE INTO";
        return $this;
    }

    /** @return SQLInsertQuery */
    public function insert_into($table_identifier)
    {
        $table_identifier = ConvertToSQLTableIdentifier::if_string($table_identifier);
        
        SQLBuilderException::throwIfNot($this->isTableIdentifier($table_identifier),"expects a SQLTableIdentifier for insert into clause");
        $this->tableIdentifier = $table_identifier;
        return $this;
    }

    /** @return SQLInsertQuery */
    public function set($sql_column_value_pair)
    {
        $this->addColumnValuePair($sql_column_value_pair);
        return $this;
    }

}

class SQLInsertIgnoreQuery extends SQLInsertQuery{
    public function __construct()
    {
        parent::__construct();
        $this->on_duplicate_key_ignore();
    }
}

class SQLReplaceQuery extends SQLInsertQuery{
    public function __construct()
    {
        parent::__construct();
        $this->on_duplicate_key_replace();
    }
}


//==================== NOW WE NEED THE WHERE CLOSE FOR OTHER SQL STATEMENTS LIKE DELETE, UPDATE, SELECT

interface ISQLTest extends ISQLValue{
    public function and_($sql_test);    
    public function and_not($sql_test);
    public function or_($sql_test);
}

class SQLIsNotNullTest extends SQLValueBase implements ISQLTest{
    private $sql_value;
    protected $not_keyword = " NOT";
    public function __construct($sql_value)
    {
        SQLBuilderException::throwIfNot($this->isSQLValue($sql_value),"expected a SQL value to test IS NOT NULL");
        $this->treatAsSubQueryIfSelectQuery($sql_value);
        $this->sql_value = $sql_value;        
    }

    public function as_($identifer)
    {        
    }
    public function __toString()
    {
        return sprintf("%s IS%s NULL",$this->sql_value,$this->not_keyword);
    }
    public function and_($sql_test)
    {
        return SQLUtils::and_($this,$sql_test);
    }
    public function and_not($sql_test)
    {
        return SQLUtils::and_not($this,$sql_test);
    }
    public function or_($sql_test){
        return SQLUtils::or_($this,$sql_test);
    }

}
class SQLIsNullTest extends SQLIsNotNullTest{
    public function __construct($sql_value)
    {
        parent::__construct($sql_value);
        $this->not_keyword = "";
    }
}

abstract class SQLArithmeticOrComparisonExpression extends SQLValueBase implements ISQLTest{

    public function and_($sql_test){
        return SQLUtils::and_($this,$sql_test);
    }
    public function or_($sql_test){
        return SQLUtils::or_($this,$sql_test);
    }
    public function and_not($sql_test)
    {
        return SQLUtils::and_not($this,$sql_test);
    }

    private $sqlvalue1;
    private $sqlvalue2;
    public function firstValue(){
        return $this->sqlvalue1;
    }
    public function secondValue(){
        return $this->sqlvalue2;
    }
    abstract protected function getOperator();

    public function __construct($sqlvalue1,$sqlvalue2)
    {
        $sqlvalue1 = ConvertToSQLValue::ifNotSQLValue($sqlvalue1);
        $sqlvalue2 = ConvertToSQLValue::ifNotSQLValue($sqlvalue2);
        
        if(!SQLValue::isValid($sqlvalue1) || !SQLValue::isValid($sqlvalue2)){
            throw new SQLBuilderException("invalid argument in constuctor");
        }

        $this->prepare_values($sqlvalue1, $sqlvalue2);
        $this->sqlvalue1 = $sqlvalue1;
        $this->sqlvalue2 = $sqlvalue2;
    }
    public function __toString()
    {
        $result = join($this->getOperator(),array($this->sqlvalue1,$this->sqlvalue2));
        return $result;
    }

    /**
     * @param $sqlvalue1
     * @param $sqlvalue2
     */
    private function prepare_values($sqlvalue1, $sqlvalue2)
    {
        $this->treatAsSubQueryIfSelectQuery($sqlvalue1);
        $this->treatAsSubQueryIfSelectQuery($sqlvalue2);
    }

}

abstract class ComparisonTest extends SQLArithmeticOrComparisonExpression{
    public function as_($identifier)
    {
        $this->set_alias_identifier($identifier);
        return $this;
    }
    public function __toString()
    {
        $result = $this->should_enclose ? sprintf("(%s)",parent::__toString()): parent::__toString();
        $result = $this->has_alias() ? $result ." AS ".$this->get_alias_identifier() : $result;
        return $result;
    }
    private $should_enclose = false;
    public function enclose(){
        $this->should_enclose = true;
        return $this;
    }
}

abstract class SQLArithmeticExpression extends SQLArithmeticOrComparisonExpression{
    public function as_($identifier)
    {
        $this->set_alias_identifier($identifier);
        return $this;
    }
    public function __toString()
    {
        $result = $this->should_enclose ? sprintf("(%s)",parent::__toString()): parent::__toString();
        $result = $this->has_alias() ? $result ." AS ".$this->get_alias_identifier() : $result;
        return $result;
    }
    private $should_enclose = false;
    public function enclose(){
        $this->should_enclose = true;
        return $this;
    }
}

class SQLLikeTest extends ComparisonTest{

    protected function getOperator()
    {
        return " REGEXP ";
    }
}
class ConvertToSQLValue{
    public static function ifNotSQLValue($value_as_int_or_string_or_sql_value)
    {
        if(is_numeric($value_as_int_or_string_or_sql_value) ){
            $value_as_int_or_string_or_sql_value = new SQLInt($value_as_int_or_string_or_sql_value);
        }
        else if(is_string($value_as_int_or_string_or_sql_value)){
            $value_as_int_or_string_or_sql_value = new SQLString($value_as_int_or_string_or_sql_value);
        }
        else if(SQLValue::isValid($value_as_int_or_string_or_sql_value)){
            return $value_as_int_or_string_or_sql_value;
        }
        else if(is_a($value_as_int_or_string_or_sql_value,"ISQLCommand")){
            return $value_as_int_or_string_or_sql_value;
        }
        else{
            return $value_as_int_or_string_or_sql_value;
            //throw new Exception("can not convert to sql value");
        }
        return $value_as_int_or_string_or_sql_value;
    }    
}
class ConvertToSQLPrimaryKey{
    public static function if_column_name($column_name){
        if(is_string($column_name)){
            $column_name = (new SQLPrimaryKey())->addColumn($column_name);
        }
        return $column_name;
    }
}
class ConvertToSQLUniqueKey{
    public static function if_column_name($column_name){
        if(is_string($column_name)){
            $column_name = (new SQLUniqueKey($column_name))->addColumn($column_name);
        }
        return $column_name;
    }
}
class ConvertToSQLKey{
    public static function if_column_name($column_name){
        if(is_string($column_name)){
            $column_name = (new SQLKey($column_name))->addColumn($column_name);
        }
        return $column_name;
    }
}
class ConvertToSQLIdentifier{
    public static function if_string($value){
        if(is_string($value)){
            $value = new SQLIdentifier( $value);
        }
        return $value;
    }
}

class ConvertToSQLTableIdentifier{
    public static function if_string($value){
        if(is_string($value)){
            $value = new SQLTableIdentifier( $value);
        }
        return $value;
    }
}
class ConvertToSQLString{
    public static function if_not_sql_string($value){
        if(!is_a($value,"SQLString")){
            if(!SQLValue::isValid($value)){
                $value = new SQLString("".$value);
            }
        }
        return $value;
    }
}

class SQLMinAndMaxValuesForBetween implements ISQLValue{
    private $min_value_as_sqlvalue;
    private $max_value_as_sqlvalue;
    public function __construct($min_value_as_num_or_sqlvalue, $max_value_as_num_or_sqlvalue)
    {
        $min_value_as_num_or_sqlvalue = ConvertToSQLValue::ifNotSQLValue($min_value_as_num_or_sqlvalue);
        $max_value_as_num_or_sqlvalue = ConvertToSQLValue::ifNotSQLValue($max_value_as_num_or_sqlvalue);
        $this->min_value_as_sqlvalue = $min_value_as_num_or_sqlvalue;
        $this->max_value_as_sqlvalue = $max_value_as_num_or_sqlvalue;

    }

    public function __toString()
    {
        return sprintf("%s AND %s",$this->min_value_as_sqlvalue,$this->max_value_as_sqlvalue);
    }
    public function as_($identifer)
    {
        return $this;
    }
}
class SQLBetweenTest extends ComparisonTest{
    protected function getOperator()
    {
        return " BETWEEN ";
    }

    public function __construct($sqlvalue1, $sqlvalue2,$sqlvalue3)
    {
        parent::__construct($sqlvalue1, new SQLMinAndMaxValuesForBetween($sqlvalue2,$sqlvalue3));
    }
}

class SQLEqualityTest extends ComparisonTest{
    protected function getOperator()
    {
        return "=";
    }
}
class SQLNotEqualTest extends ComparisonTest{
    protected function getOperator()
    {
        return "<>";
    }
}

class SQLGreaterThanTest extends ComparisonTest{
    protected function getOperator()
    {
        return ">";
    }
}
class SQLGreaterOrEqualTest extends ComparisonTest{
    protected function getOperator()
    {
        return ">=";
    }
}
class SQLLessThanTest extends ComparisonTest{
    protected function getOperator()
    {
        return "<";
    }
}
class SQLLessOrEqualTest extends ComparisonTest{
    protected function getOperator()
    {
        return "<";
    }
}

class SQLAdditionExpression extends SQLArithmeticExpression{
    protected function getOperator()
    {
        return "+";
    }
}
class SQLSubtractionExpression extends SQLArithmeticExpression{
    protected function getOperator()
    {
        return "-";
    }
}
class SQLMultiplicationExpression extends SQLArithmeticExpression{
    protected function getOperator()
    {
        return "*";
    }
}
class SQLDivisionExpression extends SQLArithmeticExpression{
    protected function getOperator()
    {
        return "/";
    }
}
class SQLModulusExpression extends SQLArithmeticExpression{
    protected function getOperator()
    {
        return "%";
    }
}

class SQLInListTest extends SQLValueBase implements ISQLTest{
    public function as_($identifier)
    {
        return $this;
    }
    public function inList($sql_value_list)
    {
        return SQLUtils::inList($this,$sql_value_list)->enclosed_in_parentheses();
    }
    public function addToNewValueList(){
        return SQLUtils::addToNewValueList($this);
    }
    
    public function and_($sql_test){
        return SQLUtils::and_($this,$sql_test);
    }

    public function and_not($sql_test)
    {
        return SQLUtils::and_not($this,$sql_test);
    }
    public function or_($sql_test){
        return SQLUtils::or_($this,$sql_test);
    }

    private $sql_value;
    private $sql_value_list;
    public function __construct()
    {
        $this->sql_value_list = new SQLValueList();
    }

    public function setValueToTest($sqlValue)
    {
        SQLValue::throwExceptionIfNotValid($sqlValue);
        $this->sql_value = $sqlValue;
    }
    public function addPossibleMatch($sqlValue)
    {
        SQLValue::throwExceptionIfNotValid($sqlValue);
        $this->sql_value_list->add($sqlValue);
    }
    public function setPossibleMatches($sql_value_list)
    {
        //SQLValue::throwExceptionIfNot($this->isSQLValueList($sql_value_list) || $this->isSelectStatement($sql_value_list),"expects a SQLValueList or select expression for possible matches");
        if(!$this->isSQLValueList($sql_value_list)){
            $this->sql_value_list = new SQLValueList();
            $this->sql_value_list->add($sql_value_list);
        }
        else{
            $this->sql_value_list = $sql_value_list;
        }

    }
    public function __toString()
    {
        $result = join(" IN ",array($this->sql_value,"(".$this->sql_value_list.")"));
        if($this->enclose){
            $result = "($result)";
        }
        return $result;
    }
    private $enclose = false;
    public function enclosed_in_parentheses(){
        $this->enclose = true;
        return $this;
    }
}

abstract class SQLTestList extends SQLValueList{
    public function  add($value)
    {
        SQLValue::throwExceptionIfNot(SQLValue::isATest($value), sprintf("invalid test %s",$value));
        parent::add($value);
    }
}
class SQLAllTrueTest extends SQLTestList implements ISQLTest{
    public function and_($sql_test){
        return SQLUtils::and_($this,$sql_test);
    }

    public function and_not($sql_test)
    {
        return SQLUtils::and_not($this,$sql_test);
    }
    public function or_($sql_test){
        return SQLUtils::or_($this,$sql_test);
    }

    public function __construct()
    {
        $this->delimiter = " AND ";
    }
}

class SQLAndNotTest extends SQLTestList implements ISQLTest{
    public function and_($sql_test){
        return SQLUtils::and_($this,$sql_test);
    }
    public function and_not($sql_test)
    {
        return SQLUtils::and_not($this,$sql_test);
    }
    public function or_($sql_test){
        return SQLUtils::or_($this,$sql_test);
    }

    public function __construct()
    {
        $this->delimiter = " AND NOT ";
    }
}

class SQLAnyTrueTest extends SQLTestList implements ISQLTest{
    public function and_($sql_test){
        
        return SQLUtils::and_($this,$sql_test);
    }
    public function or_($sql_test){
        return SQLUtils::or_($this,$sql_test);
    }

    public function __construct()
    {
        $this->delimiter = " OR ";
    }
    public function and_not($sql_test)
    {
        return SQLUtils::and_not($this,$sql_test);
    }

    public function __toString()
    {
        return "(". parent::__toString().")";
    }

}

class SQLUpdateQuery extends SQLValueBase implements ISQLCommand{
    public function as_($identifier)
    {
        return $this;
    }
    public function inList($sql_value_list)
    {
        return SQLUtils::inList($this,$sql_value_list)->enclosed_in_parentheses();
    }
    public function addToNewValueList(){
        return SQLUtils::addToNewValueList($this);
    }
    
    private $table;
    private $columnValuePairList;
    private $where_condition;
    
    public function __construct()
    {
        $this->columnValuePairList = new SQLIdentifierValuePairList();
    }
    public function setTable($string){
        SQLValue::throwExceptionIfNot(is_string($string),"expected a string");
        $this->table = new SQLIdentifier($string);
    }
    public function setColumnValue($columnValuePair){
        SQLValue::throwExceptionIfNot(is_a($columnValuePair,"ISQLIdentifierValuePair"), "expected ISQLIdentifierValuePair");
        $this->columnValuePairList->add($columnValuePair);        
    }
    public function setTest($test){
        SQLValue::throwExceptionIfNot(is_a($test,"ISQLTest"),"expected a SQLTest");
        $this->where_condition = $test;
    }

    public function __toString()
    {
        return sprintf("UPDATE %s SET %s%s",$this->table,$this->columnValuePairList,$this->getWhereClause());
    }

    private function getWhereClause()
    {
        return $this->where_condition ? " WHERE ".$this->where_condition : "";
    }

    /** @return SQLUpdateQuery */
    public function update($table_identifier)
    {
        if(is_string($table_identifier)){
            $table_identifier = new SQLTableIdentifier($table_identifier);
        }
        SQLBuilderException::throwIfNot($this->isTableIdentifier($table_identifier),"expects a SQLTableIdentifier for update");
        $this->table = $table_identifier;
        return $this;
    }

    /**
     *@param \SQLIdentifierValuePair $column_value_pair
     * @return SQLUpdateQuery */
    public function set($column_value_pair)
    {
        SQLBuilderException::throwIfNot($this->isSQLIdentifierValuePair($column_value_pair),"expects a SQLIdentifierValuePair");
        $this->treatAsSubQueryIfSelectQuery($column_value_pair->getValue());
        $this->setColumnValue($column_value_pair);
        return $this;
    }

    /** @return SQLUpdateQuery */
    public function where($test)
    {
        $this->setTest($test);
        return $this;
    }


}

class SQLDeleteQuery extends SQLValueBase implements ISQLCommand{
    public function as_($identifier)
    {
        return $this;
    }
    public function inList($sql_value_list)
    {
        return SQLUtils::inList($this,$sql_value_list)->enclosed_in_parentheses();
    }
    public function addToNewValueList(){
        return SQLUtils::addToNewValueList($this);
    }
    private $table;    
    private $where_condition;

    public function setTable($string){
        SQLValue::throwExceptionIfNot(is_string($string),"expected a string");
        $this->table = new SQLIdentifier($string);
    }
    
    public function setTest($test){
        SQLValue::throwExceptionIfNot(is_a($test,"ISQLTest"),"expected a SQLTest");
        $this->where_condition = $test;
    }

    public function __toString()
    {
        return sprintf("DELETE FROM %s%s",$this->table,$this->getWhereClause());
    }

    private function getWhereClause()
    {
        return $this->where_condition ? " WHERE ".$this->where_condition : "";
    }

    /** @return SQLDeleteQuery */
    public function delete_from($table_identifier)
    {
        $table_identifier = ConvertToSQLTableIdentifier::if_string($table_identifier);
        SQLBuilderException::throwIfNot($this->isTableIdentifier($table_identifier),"expects a SQLTableIdentifier for delete from");
        $this->table = $table_identifier;
        return $this;
    }

    /** @return SQLDeleteQuery */
    public function where($test)
    {
        $this->setTest($test);
        return $this;
    }

}

//================= tested upto to here =====

//================== now, we need concept of 'table' to handle select statements
//we shall need a list of tables
interface ISQLTable extends ISQLValue{

}
class SQLTableIdentifier extends SQLIdentifier implements ISQLTable{
    public function __construct($string)
    {
        parent::__construct($string);
    }

    /** @param \SQLIdentifier $identifier */
    public function columnIdentifier($identifier){
        return new SQLIdentifierWithTableName($identifier->getValue(),$this->getValue());
    }
    
    /** return SQLTableIdentifier */
    public function as_($identifier)
    {
        $sql_value = new SQLTableIdentifier($this->getValue());
        $sql_value->set_alias_identifier($identifier);
        return $sql_value;
    }

    /** @return SQLLeftJoinedTable */
    public function left_join($table)
    {
        $joined_table = new SQLLeftJoinedTable();
        return $this->setUpJoinedTable($joined_table,$table);        
    }
    /** @return SQLRightJoinedTable */
    public function right_join($table)
    {
        $joined_table = new SQLRightJoinedTable();
        return $this->setUpJoinedTable($joined_table,$table);
    }
    /** @return SQLInnerJoinedTable */
    public function inner_join($table)
    {
        $joined_table = new SQLInnerJoinedTable();
        return $this->setUpJoinedTable($joined_table,$table);
    }    

    /**
     * @param \SQLJoinedTable $joined_table
     * @param \ISQLTable $table   
     * @return SQLJoinedTable 
     */
    private function setUpJoinedTable($joined_table,$table)
    {
        $joined_table->setTable1($this);
        $joined_table->setTable2($table);
        return $joined_table;
    }
}
class SQLColumnInNewRow extends QuottedValue{
    private $column;

    public function __construct()
    {
        parent::__construct($this->rowName(),'');
    }
    protected function quote()
    {
        return "";
    }
    public function as_($identifer)
    {
        $this->set_alias_identifier($identifer);
        return $this;
    }
    public function columnIdentifier($identifier){
        $this->column = ConvertToSQLIdentifier::if_string($identifier);
        return $this;
    }
    public function __toString()
    {
        return sprintf("%s.%s",parent::__toString(),$this->column);
    }

    protected function rowName()
    {
        return "NEW";
    }
}
class SQLColumnInOldRow extends SQLColumnInNewRow{
    protected function rowName()
    {
        return "OLD";
    }
}
abstract class SQLJoinedTable extends SQLValueBase implements ISQLTable{
    protected $table1;
    protected $table2;
    protected $column1;
    protected $column2;

    /**
     * @param $identifier
     * @param $new_joined_table
     */
    protected function setUPClonedTable($identifier, $new_joined_table)
    {
        $new_joined_table->setTable1($this->table1);
        $new_joined_table->setTable2($this->table2);
        $new_joined_table->setColumn1($this->column1);
        $new_joined_table->setColumn2($this->column2);
        $new_joined_table->set_alias_identifier($identifier);
    }


    
    /** @return SQLJoinedTable */
    public function left_join($table){
        return $this->setUpResultTable(new SQLLeftJoinedTable(),$table);
    }

    /** @return SQLJoinedTable */
    public function right_join($table){
        return $this->setUpResultTable(new SQLRightJoinedTable(),$table);
    }

    /** @return SQLJoinedTable */
    public function inner_join($table){
        return $this->setUpResultTable(new SQLInnerJoinedTable(),$table);
    }

    /** @param \SQLJoinedTable $result_table */
    private function setUpResultTable($result_table, $table_to_join_with){
        SQLBuilderException::throwIfNot($this->isTable($table_to_join_with),"expected table for join operation");
        $result_table->setTable1($this);
        $result_table->setTable2($table_to_join_with);
        return $result_table;
    }
    /** @param \SQLEqualityTest $sql_equality_test */
    public function on($sql_equality_test){        
        SQLBuilderException::throwIfNot($this->isATest($sql_equality_test),"expected a SQLEqualityTest for the ON clause");
        $this->setColumn1($sql_equality_test->firstValue());
        $this->setColumn2($sql_equality_test->secondValue());
        return $this;
    }
    
    public function setTable1($table1){
        $table1 = ConvertToSQLTableIdentifier::if_string($table1);
        SQLBuilderException::throwIfNot($this->isTable($table1),"table1 for join expression expected to be ISQLTable and not ".get_class($table1));
        $this->table1 = $table1;
    }
    public function setTable2($table2){
        $table2 = ConvertToSQLTableIdentifier::if_string($table2);
        SQLBuilderException::throwIfNot($this->isTable($table2),"table2 for join expression expected to be ISQLTable");
        $this->table2 = $table2;
    }
    public function setColumn1($column1){
        //$column1 = ConvertToSQLIdentifier::if_string($column1);
        $column1 = ConvertToSQLValue::ifNotSQLValue($column1);
        SQLBuilderException::throwIfNot($this->isSQLValue($column1),"column1 for join expression expected to be SQLValue");
        //SQLBuilderException::throwIfNot($this->isSQLIdentifier($column1),"column1 for join expression expected to be SQLIdentifier");
        $this->column1 = $column1;
    }

    public function setColumn2($column2){
        //$column2 = ConvertToSQLIdentifier::if_string($column2);
        $column2 = ConvertToSQLValue::ifNotSQLValue($column2);
        SQLBuilderException::throwIfNot($this->isSQLValue($column2),"column2 for join expression expected to be SQLValue");
        //SQLBuilderException::throwIfNot($this->isSQLIdentifier($column2),"column2 for join expression expected to be SQLIdentifier");
        $this->column2 = $column2;
    }


    public function __toString()
    {
        $table1 = $this->isTableIdentifier($this->table1) || $this->table1->has_alias() ? $this->table1."" : "($this->table1)";
        $table2 = $this->isTableIdentifier($this->table2) || $this->table2->has_alias() ?  $this->table2."" : "($this->table2)";

        $result = sprintf("%s ". $this->getJoinVerb() ." JOIN %s ON %s = %s",$table1,$table2,$this->column1,$this->column2);
        if($this->has_alias()){
            //$result = sprintf("(%s) AS %s",$result, $this->get_alias_identifier());
        }
        return $result;
    }


    /**
     * @return string
     */
    abstract protected function getJoinVerb();


}
class SQLInnerJoinedTable extends SQLJoinedTable{
    /** return SQLInnerJoinedTable */
    public function as_($identifier)
    {
        $joinedTable = new SQLInnerJoinedTable();
        $this->setUPClonedTable($identifier, $joinedTable);
        return $joinedTable;
    }
    protected function getJoinVerb()
    {
        return "INNER";
    }


}
class SQLLeftJoinedTable extends SQLJoinedTable{
    /** return SQLLeftJoinedTable */
    public function as_($identifier)
    {
        $joinedTable = new SQLLeftJoinedTable();
        $this->setUPClonedTable($identifier, $joinedTable);
        return $joinedTable;
    }
    protected function getJoinVerb()
    {
        return "LEFT";
    }
}


class SQLRightJoinedTable extends SQLJoinedTable{
    /** return SQLRightJoinedTable */
    public function as_($identifier)
    {
        return $this;
    }
    protected function getJoinVerb()
    {
        return "RIGHT";
    }
}


class SQLTableList extends SQLValueListBase{
    public function as_($identifier)
    {
        return $this;
    }
    public function add($item)
    {
        if(is_string($item)){
            $item = new SQLTableIdentifier($item);
        }
        SQLValue::throwExceptionIfNot($this->isTable($item),"expects a valid table or table name");
        parent::add($item); 
    }
}

class SQLMultiQuery extends SQLListBase{
    
    public function as_($identifier)
    {
        return $this;
    }
    
    protected function getItemClass()
    {
        return "ISQLCommand";
    }
    protected function getListItemDelimiter()
    {
        return ";";
    }
}

interface ISQLSelectExpression extends ISQLValue{

}
class SQLSelectExpression extends SQLValueBase implements ISQLSelectExpression, ISQLTable,ISQLCommand
{
    private $valuesToSelected;
    private $tableListToSelectFrom;
    private $testToUseAsFilter;
    /** @var  SQLListOfColumnsToOrderBy */
    private $columnsToOrderBy;
    private $identifier_to_group_by;
    //============
    public function exists()
    {
        $function =  new SQLExistsFunction();
        $function->setQueryToCheck($this);
        return $function;
    }
    public function not_exists()
    {
        $function =  new SQLNotExists();
        $function->setQueryToCheck($this);
        return $function;
    }
    //==============================
    public function as_($identifier)
    {
        $this->set_alias_identifier($identifier);
        return $this;
    }
    public function set_alias_identifier($identifier){
        parent::set_alias_identifier($identifier);
    }
    public function inList($sql_value_list)
    {
        return SQLUtils::inList($this, $sql_value_list)->enclosed_in_parentheses();
    }

    public function addToNewValueList()
    {
        return SQLUtils::addToNewValueList($this);
    }
    

    public function __construct()
    {
        $this->clearValueList();
        $this->clearTableList();
        $this->clearOrderByColumns();
    }

    public function addTable($table)
    {
        SQLBuilderException::throwIfNot($this->isTable($table), "expected ISQLTable");
        $this->tableListToSelectFrom->add($table);
    }

    public function setTableList($tableList)
    {
        SQLValue::throwExceptionIfNot($this->isTableList($tableList), "expects a table list");
        $this->tableListToSelectFrom = $tableList;
    }

    public function selectValue($sqlValue)
    {
        $sqlValue = ConvertToSQLValue::ifNotSQLValue($sqlValue);
        SQLBuilderException::throwIfNot($this->isSQLValue($sqlValue), "expects a SQLValue");
        $this->treatAsSubQueryIfSelectQuery($sqlValue);
        $this->valuesToSelected->add($sqlValue);
    }

    public function setTest($test)
    {
        SQLValue::throwExceptionIfNot($this->isATest($test), "expects a test for the select statement");
        $this->testToUseAsFilter = $test;
    }

    public function setValueListToSelect($valueList)
    {
        SQLValue::throwExceptionIfNot($this->isSQLValueList($valueList), "expects a value list");
        $this->valuesToSelected = $valueList;
    }

    private $distinct_keyword = "";
    public function __toString()
    {
        $result = sprintf("SELECT%s %s%s",$this->distinct_keyword, $this->getSelectedValues(), $this->getOtherQueryParts());
        return $this->wrap($result);
    }
    

    /**
     * @return string
     */
    protected function getSelectedValues()
    {
        return $this->valuesToSelected->isEmpty() ? "*" : $this->valuesToSelected->__toString();
    }

    private function getOtherQueryParts()
    {


        if ($this->tableListToSelectFrom->isEmpty()) {
            return "";
        }

        $final_string = sprintf(" FROM %s", $this->tableListToSelectFrom->__toString());
        if ($this->testToUseAsFilter) {
            $final_string = sprintf("%s WHERE %s", $final_string, $this->testToUseAsFilter . "");
        }

        if (!$this->columnsToOrderBy->isEmpty()) {
            $final_string = sprintf("%s ORDER BY %s", $final_string, $this->columnsToOrderBy->__toString());
        }

        if ($this->identifier_to_group_by) {
            $final_string = sprintf("%s GROUP BY %s", $final_string, $this->identifier_to_group_by);
        }

        if($this->limit){
            $final_string = sprintf("%s LIMIT %s",$final_string,$this->limit);
        }
        return $final_string;
    }

    private $treat_as_subexpression = false;

    public function treatAsSubExpression()
    {
        $this->treat_as_subexpression = true;
        return $this;
    }

    //changed method such that if has alias, we dont enclose in parens coz alais will enclose it
    private function wrap($result)
    {
        $final_string = $this->treat_as_subexpression && !$this->has_alias() ? sprintf("(%s)", $result) : $result;
        if($this->has_alias()){
            $final_string = sprintf("(%s) AS %s",$final_string,$this->get_alias_identifier());
        }
        return $final_string;
    }

    /**
     * @return SQLSelectExpression */
    public function select($sql_value_or_value_list)
    {
        $sql_value_or_value_list = ConvertToSQLValue::ifNotSQLValue($sql_value_or_value_list);
        SQLBuilderException::throwIfNot($this->isSQLValue($sql_value_or_value_list), "expected a SQLValue or SQLValueList");
        if ($this->isSQLValueList($sql_value_or_value_list)) {
            $this->setValueListToSelect($sql_value_or_value_list);
        } else {
            $this->selectValue($sql_value_or_value_list);
        }
        return $this;
    }
    
    public function select_field($sql_identifier_or_string){
        return $this->select(
            ConvertToSQLIdentifier::if_string($sql_identifier_or_string)
        );
    }

    public function select_distinct(){
        $this->distinct_keyword = " DISTINCT";
        return $this;
    }
    
    public function select_everything($table_name_or_identifier=null){
        $this->select(new SQLEverything($table_name_or_identifier));
        return $this;
    }
    public function select_if($test,$on_true_sql_value,$on_false_sql_value,$alias_identifier = null){
        $select_if_function = new SQLIFFunction($test,$on_true_sql_value,$on_false_sql_value); 
        if($alias_identifier){
            $select_if_function->as_($alias_identifier);
        }
        $this->select($select_if_function);
        return $this;
    }
    private function select_count($sql_identifier,$as_identifier = null){
        $sql_count = new SQLCountFunction($sql_identifier,$as_identifier);        
        $this->select($sql_count);
        return $this;
    }
    public function select_count_everything($as_identifier = null){
        $this->select_count(new SQLEverything(),$as_identifier);
        return $this;
    }
    
    /** @return SQLSelectExpression */
    public function from($identifier_or_joined_table_select_expression)
    {
        SQLBuilderException::throwIf(
            $this->isSelectStatementWithNoAlias($identifier_or_joined_table_select_expression),
            "specify alias for derived table"
        );

        $identifier_or_joined_table_select_expression = ConvertToSQLTableIdentifier::if_string($identifier_or_joined_table_select_expression);
        //preprocessing: if a string, we convert to a table name
        /*if(is_string($identifier_or_joined_table_select_expression)){
            $identifier_or_joined_table_select_expression = new SQLTableIdentifier($identifier_or_joined_table_select_expression);
        }*/

        if ($this->isTableList($identifier_or_joined_table_select_expression)) {
            $this->setTableList($identifier_or_joined_table_select_expression);
        } else if ($this->isTable($identifier_or_joined_table_select_expression)) {
            $this->clearTableList();
            $this->addTable($identifier_or_joined_table_select_expression);
        } else {
            throw new SQLBuilderException("expected a SQLTableIdentifier or SQLTableList");
        }
        return $this;
    }

    /** @return SQLSelectExpression */
    public function where($test)
    {
        $this->setTest($test);
        return $this;
    }
    public function where_if($condition,$test)
    {
        if($condition){
            $this->where($test);
        }
        return $this;
    }
    
    public function clearSelection(){
        $this->clearValueList();
        return $this;
    }

    private function clearValueList()
    {
        $this->valuesToSelected = new SQLValueList();
    }    

    private function clearTableList()
    {
        $this->tableListToSelectFrom = new SQLTableList();
    }

    /** @return SQLSelectExpression */
    public function order_by($sql_column_or_list_to_order_by)
    {
        if ($this->isSQLColumnToOrderBy($sql_column_or_list_to_order_by) || $this->isSQLValue($sql_column_or_list_to_order_by)) {
            $this->clearOrderByColumns();
            $this->columnsToOrderBy->add($sql_column_or_list_to_order_by);
        } else if ($this->isSQLListOfColumnsToOrderBy($sql_column_or_list_to_order_by)) {
            $this->columnsToOrderBy = $sql_column_or_list_to_order_by;
        } else {
            throw new SQLBuilderException("expects a SQLColumnToOrderBy or SQLListOfColumnsToOrderBy or SQLValue");
        }
        return $this;
    }
    /** @return SQLSelectExpression */
    public function order_by_if($condition,$sql_column_or_list_to_order_by){
        if($condition){
            return $this->order_by($sql_column_or_list_to_order_by);
        }
        return $this;
    }

    /**
     * @return SQLListOfColumnsToOrderBy
     */
    private function clearOrderByColumns()
    {
        return $this->columnsToOrderBy = new SQLListOfColumnsToOrderBy();
    }

    /** @return SQLSelectExpression */
    public function group_by($sql_identifier)
    {
        $sql_identifier = ConvertToSQLIdentifier::if_string($sql_identifier);        
        SQLBuilderException::throwIfNot($this->isSQLIdentifier($sql_identifier), "expects a SQLIdentifier for the group by clause");
        $this->identifier_to_group_by = $sql_identifier;
        return $this;
    }

    private $limit;
    public function limit($start_index, $number_of_results)
    {
        $this->limit = new SQLLimit($start_index,$number_of_results);
        return $this;
    }
    public function limit_if($condition,$start_index, $number_of_results){
        if($condition){
            $this->limit($start_index,$number_of_results);
        }
        return $this;
    }
    public function max_number($int){
        $this->limit(0,$int);
        return $this;
    }

    public function into($table_name_as_string_or_identifier)
    {
        return new SQLCommandForInsertFromSelect($this,$table_name_as_string_or_identifier);
    }
    
    //----
    public function inner_join($table2){
        $joined_table = new SQLInnerJoinedTable();
        $joined_table->setTable1($this);
        $joined_table->setTable2($table2);
        return $joined_table;
    }
    public function left_join($table2){
        $joined_table = new SQLLeftJoinedTable();
        $joined_table->setTable1($this);
        $joined_table->setTable2($table2);
        return $joined_table;
    }
    public function right_join($table2){
        $joined_table = new SQLRightJoinedTable();
        $joined_table->setTable1($this);
        $joined_table->setTable2($table2);
        return $joined_table;
    }
}
class SQLCommandForInsertFromSelect implements ISQLCommand{
    private $table_identifier;
    private $sql_select_expression;
    private $identifier_list;
    
    public function __construct($select_expression,$table_name_as_string_or_identifier)
    {
        $this->throwExceptionIfNotSelectExpression($select_expression);
        $this->sql_select_expression = $select_expression;
        
        
        $table_name_as_string_or_identifier = $this->convertToTableIdentifierIfString($table_name_as_string_or_identifier);
        $this->throwExceptionIfNotTableIdentifier($table_name_as_string_or_identifier);
        $this->table_identifier = $table_name_as_string_or_identifier;
        
        $this->identifier_list = new SQLIdentifierList();
        
    }
    /** @return SQLCommandForInsertFromSelect */
    public function field($sql_identifier_or_string){
        $sql_identifier_or_string = $this->convertToSQLIdentifierIfString($sql_identifier_or_string);
        $this->identifier_list->add($sql_identifier_or_string);        
        return $this;
    }

    private $command_key_word = "INSERT";
    public function on_duplicate_key_ignore(){
        $this->command_key_word = "INSERT IGNORE";
        return $this;
    }
    public function on_duplicate_key_replace(){
        $this->command_key_word = "REPLACE";
        return $this;
    }
    public function __toString()
    {
        $num_fields = $this->identifier_list->count();
        $field_list_as_string = $num_fields > 0 ? join("",array("(",$this->identifier_list,")")) : "";

        return sprintf("%s INTO %s%s %s",$this->command_key_word,$this->table_identifier,$field_list_as_string,$this->sql_select_expression);
    }
   
    private function throwExceptionIfNotTableIdentifier($table_name_as_string_or_identifier)
    {
        if (!is_a($table_name_as_string_or_identifier, "SQLTableIdentifier")) {
            throw new Exception("expects a string or table identifier");
        }
    }
    

    private function convertToTableIdentifierIfString($table_name_as_string_or_identifier)
    {
        if (is_string($table_name_as_string_or_identifier)) {
            $table_name_as_string_or_identifier = new SQLTableIdentifier($table_name_as_string_or_identifier);
            return $table_name_as_string_or_identifier;
        }
        return $table_name_as_string_or_identifier;
    }

    private function throwExceptionIfNotSelectExpression($select_expression)
    {
        if (!is_a($select_expression, "SQLSelectExpression")) {
            throw new Exception("expects a SQLSelectExpression");
        }
    }

    /**
     * @param $sql_identifier_or_string
     * @return SQLIdentifier
     */
    private function convertToSQLIdentifierIfString($sql_identifier_or_string)
    {
        if (is_string($sql_identifier_or_string)) {
            $sql_identifier_or_string = new SQLIdentifier($sql_identifier_or_string);
            return $sql_identifier_or_string;
        }
        return $sql_identifier_or_string;
    }
}
class SQLLimit{
    private $start_index;
    private $number_of_results;
    public function __construct($start_index, $number_of_results)
    {
        $this->start_index = new SQLInt($start_index);
        $this->number_of_results = new SQLInt($number_of_results);
    }
    public function __toString()
    {
        return $this->start_index.",".$this->number_of_results;
    }

}
class SQLEverything extends SQLIdentifier implements ISQLValue{
    public function __construct($table_name_or_identifier=null)
    {
        parent::__construct("*", $table_name_or_identifier);
    }

    protected function getQuotationMark(){
        return "";
    }

    /*public function __toString()
    {
        return "*";
    }*/
    public function as_($identifer)
    {
        return $this;
    }
}

abstract class SQLFunction extends SQLValueBase{
    public function __construct($sql_alias_identifier = null)
    {
        if($sql_alias_identifier){
            $this->as_($sql_alias_identifier);
        }
    }

    public function as_($identifer)
    {
        $this->set_alias_identifier($identifer);
        return $this;
    }

    public static function concat($sql_value_list)
    {
        return new SQLConcatFunction($sql_value_list);
    }
    public static function group_concat($sql_value)
    {
        return new SQLGroupConcatFunction($sql_value);
    }
    
    public static function rand(){
        $function = new SQLRandomFunction();        
        return $function;
    }
    public static function now(){
        $function = new SQLNowFunction();
        return $function;
    }
    public static function sleep($total_seconds_as_int_or_sql_value){
        $function = new SQLSleepFunction($total_seconds_as_int_or_sql_value);
        return $function;
    }

    public static function rpad($sql_value,$length,$pad_string){
        $function = new SQLRPadFunction($sql_value,$length,$pad_string);
        return $function;
    }
    public static function date_format($sql_date, $sql_format_string){
        $function = new SQLDateFormatFunction($sql_date,$sql_format_string);
        return $function;
    }

    public static function floor($sqlvalue){
        $function = new SQLFloorFunction();
        $function->set_argument($sqlvalue);
        return $function;
    }
    public static function ceiling($sqlvalue){
        $function = new SQLCeilingFunction();
        $function->set_argument($sqlvalue);
        return $function;
    }
    public static function round($sqlvalue,$num_decimals){
        $function = new SQLRoundFunction();
        $function->set_number($sqlvalue);
        $function->set_decimals($num_decimals);        
        return $function;
    }
    
    public static function md5($sqlvalue){
        $function = new SQLMD5Function($sqlvalue);        
        return $function;
    }
    
    public static function date($date_in_full)
    {
        return new SQLFunctionForDate($date_in_full);
    }
    public static function cur_date($date_in_full)
    {
        return new SQLFunctionForCurDate($date_in_full);
    }

    public static function year($date_in_full)
    {
        return new SQLFunctionForYear($date_in_full);
    }

    public static function month($date_in_full)
    {
        return new SQLFunctionForMonth($date_in_full);
    }

    public static function month_name($date_in_full)
    {
        return new SQLFunctionForMonthName($date_in_full);
    }
    public static function month_description($date_in_full)
    {        
        return SQLFunction::month_name($date_in_full)->append(
            " "
        )->
        append(
            SQLFunction::year($date_in_full)
        );
    }
    
    public static function week($date_in_full)
    {
        return new SQLFunctionForWeek($date_in_full);
    }
    public static function week_of_year($date_in_full)
    {
        return new SQLFunctionForWeekOfYear($date_in_full);
    }
    public static function week_of_year_description($date_in_full)
    {
        return 
            (new SQLString("Week "))->
            append(
                SQLFunction::week_of_year($date_in_full)
            )->
            append(" of ")->
            append(
                SQLFunction::year($date_in_full)
            );
    }
    public static function day_of_year_description($date_in_full)
    {
        return
            (new SQLString("Day "))->
            append(
                SQLFunction::day($date_in_full)
            )->
            append(" of ")->
            append(
                SQLFunction::month_name($date_in_full)
            )->
            append(" ")->
            append(
                SQLFunction::year($date_in_full)
            );
    }

    public static function day($date_in_full)
    {
        return new SQLFunctionForDay($date_in_full);
    }
    public static function day_of_week($date_in_full)
    {
        return new SQLFunctionForDayOfWeek($date_in_full);
    }
    public static function day_of_year($date_in_full)
    {
        return new SQLFunctionForDayOfYear($date_in_full);
    }
    public static function day_name($date_in_full)
    {
        return new SQLFunctionForDayName($date_in_full);
    }

    public static function from_unixtime($timestamp)
    {
        return new SQLFunctionForFromUnixTime($timestamp);
    }

    public static function pow($base_value, $power)
    {
        $fn = new SQLFunctionForPower();
        $fn->setBase($base_value);
        $fn->setPower($power);
        return $fn;
    }
    public static function sqrt($number)
    {
        $fn = new SQLFunctionForSquareRoot();
        $fn->setNumber($number);        
        return $fn;
    }

    public function __toString()
    {
        return sprintf("%s(%s)%s",$this->getFunctionKeyWord(),$this->getFunctionContent(),$this->getAliasString());
    }
    abstract protected function getFunctionKeyWord();
    abstract protected function getFunctionContent();

    private function getAliasString()
    {
        return $this->has_alias() ? " AS ".$this->get_alias_identifier():"";
    }
    
    public static function IfTrueThat($test, $on_true_sql_value, $on_false_sql_value){
        return new SQLIFFunction($test,$on_true_sql_value,$on_false_sql_value);
    }

}

class SQLFunctionForPower extends SQLFunction{
    private $base_val,$power;
   
    public function setBase($base_value)
    {
        $this->base_val = ConvertToSQLValue::ifNotSQLValue($base_value);
        return $this;
    }

    public function setPower($power)
    {
        $this->power = ConvertToSQLValue::ifNotSQLValue($power);
        return $this;
    }

    protected function getFunctionKeyWord()
    {
        return "POW";
    }
    protected function getFunctionContent()
    {
        return join(",",[$this->base_val,$this->power]);
    }
}

class SQLFunctionForSquareRoot extends SQLFunction{
    private $number;

    public function setNumber($number)
    {
        $this->number = ConvertToSQLValue::ifNotSQLValue($number);
        return $this;
    }
    protected function getFunctionKeyWord()
    {
        return "SQRT";
    }
    protected function getFunctionContent()
    {
        return $this->number;
    }
}

class SQLIFFunction extends SQLFunction{
    private $test,$on_true_sql_value,$on_false_sql_value;
    public function __construct($test, $on_true_sql_value, $on_false_sql_value, $sql_alias_identifier=null)
    {
        $on_true_sql_value = ConvertToSQLValue::ifNotSQLValue($on_true_sql_value);
        $on_false_sql_value = ConvertToSQLValue::ifNotSQLValue($on_false_sql_value);
            
        $this->validate($test, $on_true_sql_value, $on_false_sql_value);
        $this->prepare_values($on_true_sql_value, $on_false_sql_value);
        $this->setValues($test, $on_true_sql_value, $on_false_sql_value);
        parent::__construct($sql_alias_identifier);        
    }
    /** sets the value to return if condition false */
    public function else_($sql_value_on_false){
        $sql_value_on_false = ConvertToSQLValue::ifNotSQLValue($sql_value_on_false);
        SQLBuilderException::throwIfNot($this->isSQLValue($sql_value_on_false),"expects a SQL Value");
        $this->treatAsSubQueryIfSelectQuery($sql_value_on_false);
        $this->on_false_sql_value = $sql_value_on_false;
        return $this;
        
    }
    protected function getFunctionKeyWord()
    {
        return "IF";
    }
    protected function getFunctionContent()
    {
        return join(",",array($this->test,$this->on_true_sql_value,$this->on_false_sql_value));
    }

    /**
     * @param $test
     * @param $on_true_sql_value
     * @param $on_false_sql_value
     */
    private function validate($test, $on_true_sql_value, $on_false_sql_value)
    {
        SQLBuilderException::throwIfNot($this->isTest($test), "expects a SQLTest for the 1st parameter of the IF function");
        SQLBuilderException::throwIfNot($this->isSQLValue($on_true_sql_value), "expects a SQLValue for the 2nd parameter of the IF function");
        SQLBuilderException::throwIfNot($this->isSQLValue($on_false_sql_value), "expects a SQLValue for the 3rd parameter of the IF function");
    }

    /**
     * @param $on_true_sql_value
     * @param $on_false_sql_value
     */
    private function prepare_values($on_true_sql_value, $on_false_sql_value)
    {
        $this->treatAsSubQueryIfSelectQuery($on_true_sql_value);
        $this->treatAsSubQueryIfSelectQuery($on_false_sql_value);
    }

    /**
     * @param $test
     * @param $on_true_sql_value
     * @param $on_false_sql_value
     */
    private function setValues($test, $on_true_sql_value, $on_false_sql_value)
    {
        $this->test = $test;
        $this->on_true_sql_value = $on_true_sql_value;
        $this->on_false_sql_value = $on_false_sql_value;
    }
}

class SQLConcatFunction extends SQLFunction{
    private $sql_value_list;
    public function __construct($sql_value_or_value_list=null, $sql_alias_identifier = null)
    {
        $this->sql_value_list = new SQLValueList();
        
        if(!is_null($sql_value_or_value_list)){
            //preprocess
            $sql_value_or_value_list = ConvertToSQLValue::ifNotSQLValue($sql_value_or_value_list);

            if(!$this->isSQLValueList($sql_value_or_value_list) || !$this->isSQLValue($sql_value_or_value_list)){
                SQLBuilderException::throw_("expected sql value or value list");
            }
            else{
                $this->sql_value_list->add_item_or_list($sql_value_or_value_list);
            }
        }
        
        parent::__construct($sql_alias_identifier);
    }

    protected function getFunctionKeyWord()
    {
        return "CONCAT";
    }
    protected function getFunctionContent()
    {
        return $this->sql_value_list;
    }
    public function add($sql_value){
        $this->sql_value_list->add_item_or_list($sql_value);
        return $this;
    }
    public function append($value)
    {        
        $this->add($value);
        return $this;
    }
}
class SQLGroupConcatFunction extends SQLFunction{
    private $sql_value;
    public function __construct($sql_value)
    {
        $sql_value = ConvertToSQLValue::ifNotSQLValue($sql_value);
        SQLBuilderException::throwIfNot(SQLValue::isValid($sql_value),"expected sql value");  
        $this->sql_value = $sql_value;
        parent::__construct();
    }

    protected function getFunctionKeyWord()
    {
        return "GROUP_CONCAT";
    }
    
    private $distinct = "";
    public function distinct(){
        $this->distinct = "DISTINCT ";
        return $this;
    }
    
    private $order_by_clause = "";
    public function order_by($order_by_clause){
        if(!is_a($order_by_clause, "SQLColumnToOrderBy")){
            throw new Exception("expected an order by clause");
        }
        $this->order_by_clause = sprintf(" ORDER BY %s", $order_by_clause);
        return $this;
    }
    private $separator = "";
    public function separator($sql_value){
        $sql_value = ConvertToSQLValue::ifNotSQLValue($sql_value);
        $this->separator = sprintf(" SEPARATOR %s",$sql_value);
        return $this;
    }
    protected function getFunctionContent()
    {
        return sprintf("%s%s%s%s",$this->distinct,$this->sql_value,$this->order_by_clause,$this->separator);
    }    
}

class SQLRandomFunction extends SQLFunction{
    private $optional_seed_number = "";
    
    protected function getFunctionKeyWord()
    {
        return "RAND";
    }
    protected function getFunctionContent()
    {
        return $this->optional_seed_number;
    }
}

class SQLNowFunction extends SQLFunction{

    protected function getFunctionKeyWord()
    {
        return "NOW";
    }
    protected function getFunctionContent()
    {
        return "";
    }
}
class SQLSleepFunction extends SQLFunction{
    private $sql_value;
    public function __construct($total_seconds_as_int_or_sql_value)
    {
        $total_seconds_as_int_or_sql_value = ConvertToSQLValue::ifNotSQLValue($total_seconds_as_int_or_sql_value);
        SQLBuilderException::throwIfNot(SQLValue::isValid($total_seconds_as_int_or_sql_value),"expected sql value or number");
        $this->sql_value = $total_seconds_as_int_or_sql_value;
        parent::__construct();
    }

    protected function getFunctionKeyWord()
    {
        return "SLEEP";
    }
    protected function getFunctionContent()
    {
        return $this->sql_value;
    }
}

class SQLMD5Function extends SQLFunction{
    private $sql_value;
    public function __construct($sql_value)
    {
        $sql_value = ConvertToSQLValue::ifNotSQLValue($sql_value);
        SQLBuilderException::throwIfNot(SQLValue::isValid($sql_value),"expected sql value");
        $this->sql_value = $sql_value;
        parent::__construct();
    }

    protected function getFunctionKeyWord()
    {
        return "MD5";
    }
    protected function getFunctionContent()
    {
        return $this->sql_value;
    }
}

class SQLSha1Function extends SQLFunction{
    private $sql_value;
    public function __construct($sql_value)
    {
        $sql_value = ConvertToSQLValue::ifNotSQLValue($sql_value);
        SQLBuilderException::throwIfNot(SQLValue::isValid($sql_value),"expected sql value");
        $this->sql_value = $sql_value;
        parent::__construct();
    }

    protected function getFunctionKeyWord()
    {
        return "SHA1";
    }
    protected function getFunctionContent()
    {
        return $this->sql_value;
    }
}

#=========== date related functions
abstract class SQLFunctionTakingOneArgument extends SQLFunction{
    private $sql_value;
    public function __construct($sql_value)
    {
        $sql_value = ConvertToSQLValue::ifNotSQLValue($sql_value);
        SQLBuilderException::throwIfNot(SQLValue::isValid($sql_value),"expected sql value");
        $this->sql_value = $sql_value;
        parent::__construct();
    }

    /*protected function getFunctionKeyWord()
    {
        return "FROM_UNIXTIME";
    }*/
    protected function getFunctionContent()
    {
        return $this->sql_value;
    }
}
class SQLFunctionForFromUnixTime extends SQLFunctionTakingOneArgument{
    protected function getFunctionKeyWord()
    {
        return "FROM_UNIXTIME";
    }

}
class SQLFunctionForDate extends SQLFunctionTakingOneArgument{

    protected function getFunctionKeyWord()
    {
        return "DATE";
    }

}
class SQLFunctionForCurDate extends SQLFunctionTakingOneArgument{

    protected function getFunctionKeyWord()
    {
        return "CURDATE";
    }

}
class SQLFunctionForYear extends SQLFunctionTakingOneArgument{

    protected function getFunctionKeyWord()
    {
        return "YEAR";
    }

}
class SQLFunctionForMonth extends SQLFunctionTakingOneArgument{

    protected function getFunctionKeyWord()
    {
        return "MONTH";
    }

}

class SQLFunctionForMonthName extends SQLFunctionTakingOneArgument{

    protected function getFunctionKeyWord()
    {
        return "MONTHNAME";
    }

}
class SQLFunctionForWeek extends SQLFunctionTakingOneArgument{

    protected function getFunctionKeyWord()
    {
        return "WEEK";
    }

}
class SQLFunctionForWeekOfYear extends SQLFunctionTakingOneArgument{

    protected function getFunctionKeyWord()
    {
        return "WEEKOFYEAR";
    }

}
class SQLFunctionForDay extends SQLFunctionTakingOneArgument{

    protected function getFunctionKeyWord()
    {
        return "DAY";
    }

}
class SQLFunctionForDayName extends SQLFunctionTakingOneArgument{

    protected function getFunctionKeyWord()
    {
        return "DAYNAME";
    }

}
class SQLFunctionForDayOfWeek extends SQLFunctionTakingOneArgument{

    protected function getFunctionKeyWord()
    {
        return "DAYOFWEEK";
    }

}
class SQLFunctionForDayOfYear extends SQLFunctionTakingOneArgument{

    protected function getFunctionKeyWord()
    {
        return "DAYOFYEAR";
    }
}

#==============

class SQLExistsFunction extends SQLFunction implements ISQLTest{
    private $select_query = "";
    public function setQueryToCheck($query){        
        SQLBuilderException::throwIfNot($this->isSelectStatement($query),"expected select query");
        $this->select_query = $query;
        return $this;
    }
    public function and_($sql_test)
    {
        return SQLUtils::and_($this,$sql_test);
    }
    public function and_not($sql_test)
    {
        return SQLUtils::and_not($this,$sql_test);
    }
    public function or_($sql_test)
    {
        return SQLUtils::or_($this,$sql_test);
    }

    protected function getFunctionKeyWord()
    {
        return "EXISTS";
    }
    protected function getFunctionContent()
    {
        return $this->select_query;
    }
}
class SQLNotExists extends SQLExistsFunction{
    protected function getFunctionKeyWord()
    {
        return "NOT EXISTS";
    }
}


class SQLFloorFunction extends SQLFunction{
    private $sql_value_to_round;

    protected function getFunctionKeyWord()
    {
        return "FLOOR";
    }
    protected function getFunctionContent()
    {
        return $this->sql_value_to_round;
    }
    public function set_argument($sql_Value){
        
        $sql_Value = ConvertToSQLValue::ifNotSQLValue($sql_Value);
        SQLBuilderException::throwIfNot($this->isSQLValue($sql_Value),"expects a SQL value");
        $this->sql_value_to_round = $sql_Value;
        return $this;
    }
}

class SQLCeilingFunction extends SQLFunction{
    private $sql_value_to_round;

    protected function getFunctionKeyWord()
    {
        return "CEILING";
    }
    protected function getFunctionContent()
    {
        return $this->sql_value_to_round;
    }
    public function set_argument($sql_Value){

        $sql_Value = ConvertToSQLValue::ifNotSQLValue($sql_Value);
        SQLBuilderException::throwIfNot($this->isSQLValue($sql_Value),"expects a SQL value");
        $this->sql_value_to_round = $sql_Value;
        return $this;
    }
}

class SQLRoundFunction extends SQLFunction{
    private $number, $num_decimals;
   

    protected function getFunctionKeyWord()
    {
        return "ROUND";
    }
    protected function getFunctionContent()
    {
        return join(",", [$this->number, $this->num_decimals]);
    }
    public function set_number($sql_Value){

        $sql_Value = ConvertToSQLValue::ifNotSQLValue($sql_Value);
        SQLBuilderException::throwIfNot($this->isSQLValue($sql_Value),"expects a SQL value");
        $this->number = $sql_Value;
        return $this;
    }
    public function set_decimals($sql_Value){

        $sql_Value = ConvertToSQLValue::ifNotSQLValue($sql_Value);
        SQLBuilderException::throwIfNot($this->isSQLValue($sql_Value),"expects a SQL value");
        $this->num_decimals = $sql_Value;
        return $this;
    }
}


class SQLRPadFunction extends SQLFunction{
    private $sql_value;
    private $to_length;
    private $character;

    public function __construct($sql_value,$to_length,$character)
    {
        parent::__construct();
        $this->sql_value = ConvertToSQLValue::ifNotSQLValue($sql_value);
        $this->to_length = ConvertToSQLValue::ifNotSQLValue($to_length);
        $this->character = ConvertToSQLValue::ifNotSQLValue($character);
    }

    protected function getFunctionKeyWord()
    {
        return "RPAD";
    }
    protected function getFunctionContent()
    {
        return join(",",array($this->sql_value,$this->to_length,$this->character));
    }
}


class SQLDateFormatFunction extends SQLFunction{
    private $sql_date_value;
    private $sql_date_format_string;
    private $character;

    public function __construct($sql_date_value, $sql_date_format_string)
    {
        parent::__construct();
        $this->sql_date_value = ConvertToSQLValue::ifNotSQLValue($sql_date_value);
        $this->sql_date_format_string = ConvertToSQLValue::ifNotSQLValue($sql_date_format_string);
    }

    protected function getFunctionKeyWord()
    {
        return "DATE_FORMAT";
    }
    protected function getFunctionContent()
    {
        return join(",",array($this->sql_date_value,$this->sql_date_format_string));
    }
}
class SQLLogFunction extends SQLFunction{
    private $base,$number;
    public function __construct($base,$number)
    {
        $this->base = ConvertToSQLValue::ifNotSQLValue($base);
        $this->number = ConvertToSQLValue::ifNotSQLValue($number);

        $this->treatAsSubQueryIfSelectQuery($this->base);
        $this->treatAsSubQueryIfSelectQuery($this->number);
        
        parent::__construct();
        
    }

    protected function getFunctionKeyWord()
    {
        return "LOG";
    }
    protected function getFunctionContent()
    {
        return (new SQLValueList())->add($this->base)->add($this->number);
    }
}

class SQLLengthFunction extends SQLFunction{
    private $string;
    public function __construct($string)
    {
        $this->string = ConvertToSQLValue::ifNotSQLValue($string);
        $this->treatAsSubQueryIfSelectQuery($this->string);
        parent::__construct();

    }

    protected function getFunctionKeyWord()
    {
        return "LENGTH";
    }
    protected function getFunctionContent()
    {
        return $this->string;
    }
}

class SQLTrimFunction extends SQLFunction{
    protected $string;
    public function __construct($string)
    {
        $this->string = ConvertToSQLValue::ifNotSQLValue($string);
        $this->treatAsSubQueryIfSelectQuery($this->string);
        parent::__construct();

    }


    protected function getFunctionKeyWord()
    {
        return "TRIM";
    }
    
    protected function getFunctionContent()
    {
        return $this->string;
    }
}
class SQLTrimLeadingFunction extends SQLTrimFunction{
    private $string_or_sql_to_remove;    

    public function __construct($string, $string_or_sql_to_remove)
    {
        $this->string_or_sql_to_remove = ConvertToSQLValue::ifNotSQLValue($string_or_sql_to_remove);
        parent::__construct($string);
    }

    protected function getFunctionContent()
    {
        return join(" ", [$this->getLocationKeyWord(), $this->string_or_sql_to_remove, "FROM", $this->string]);
    }

    protected function getLocationKeyWord()
    {
        return "LEADING";
    }
}
class SQLTrimTrailingFunction extends SQLTrimLeadingFunction{
    protected function getLocationKeyWord()
    {
        return "TRAILING";
    }
}
class SQLTrimBothFunction extends SQLTrimLeadingFunction{
    protected function getLocationKeyWord()
    {
        return "BOTH";
    }
}

abstract class SQLAggregateFunction extends SQLFunction{
    private $identifier;
    private $distinct_keyword = "";
    
    public function __construct($sql_identifier,$sql_alias_identifier=null)
    {        
        SQLBuilderException::throwIfNot($this->getTest($sql_identifier), $this->getExceptionMessage());
        $this->identifier = $sql_identifier;
        parent::__construct($sql_alias_identifier);
    }    
    protected function getFunctionContent()
    {
        return sprintf("%s%s",$this->distinct_keyword,$this->identifier);
    }
    public function distinct(){
        $this->distinct_keyword = "DISTINCT ";
        return $this;
    }

    /**
     * @param $sql_identifier
     * @return bool
     */
    protected function getTest($sql_identifier)
    {
        return $this->isSQLIdentifier($sql_identifier);
    }

    /**
     * @return string
     */
    protected function getExceptionMessage()
    {
        return "expects column identifier for " . get_class($this);
    }
}

class SQLCountFunction extends SQLAggregateFunction{

    protected function getFunctionKeyWord()
    {
        return "COUNT";
    }

    protected function getTest($sql_identifier)
    {
        return parent::getTest($sql_identifier) || is_a($sql_identifier, "SQLEverything");
    }

    protected function getExceptionMessage()
    {
        return "expects column identifier or * for " . get_class($this);
    }
}

class SQLMaxFunction extends SQLAggregateFunction{    
    
    protected function getFunctionKeyWord()
    {
        return "MAX";
    }    
}
class SQLMinFunction extends SQLAggregateFunction{    
    protected function getFunctionKeyWord()
    {
        return "MIN";
    }    
}
class SQLSUMFunction extends SQLAggregateFunction{
    protected function getFunctionKeyWord()
    {
        return "SUM";
    }
}
class SQLAverageFunction extends SQLAggregateFunction{
    protected function getFunctionKeyWord()
    {
        return "AVG";
    }
}

################################################################# DATA DEFINITION LANGUAGE
class SQLCommandList extends SQLListBase{
    public function dropTrigger($name){
        $command = new SQLCommandForDropTrigger();
        $command->trigger_name($name);
        $this->add($command);
        return $command;
    }
    public function dropTriggerIfExists($name){
        $command = $this->dropTrigger($name);
        $command->if_exists();
        return $command;
    }

    public function create_trigger($string)
    {
        $command = new SQLCommandForCreateTrigger();
        $command->trigger_name($string);
        $this->add($command);
        return $command;
    }
    /** @return SQLCommandForIf */
    public function if_($sql_test){
        $command = new SQLCommandForIf();
        $command->if_($sql_test);
        $this->add($command);
        return $command;       
    }
    public function do_($sql_value){
        $command = new SQLCommandForDo($sql_value);        
        $this->add($command);
        return $command;
    }
    public function do_sleep($total_seconds_as_int_or_sql_value){
        return $this->do_(SQLFunction::sleep($total_seconds_as_int_or_sql_value));
        
    }
    
    public function signal_sqlstate($state,$message_text){
        $command = new CommandForSignalSQLState();
        $command->state($state);
        $command->message_text($message_text);
        $this->add($command);
        return $this;
    }
    public function signal_sqlstate_45000($message_text){
        return $this->signal_sqlstate("45000",$message_text);
    }

    public function addDropPrimaryKey($table_name_as_string_or_identifier)
    {
        $command = new SQLAlterTableDropPrimaryKey($table_name_as_string_or_identifier);
        $this->add($command);
        return $this;
        //ALTER TABLE `__last_cash_transfer`  ADD `conversation_id` VARCHAR(48) NOT NULL ,  ADD   PRIMARY KEY  (`conversation_id`)
    }
    public function alterTable($table_name_as_string_or_identifier)
    {
        $command = new SQLAlterTable($table_name_as_string_or_identifier);
        $this->add($command);
        return $command;        
    }

    
    public function truncateTable($table_name_as_string_or_identifier)
    {
        $command = new SQLTruncateTable($table_name_as_string_or_identifier);
        $this->add($command);
        return $this;
    }

    public function drop_procedure_if_exists($string)
    {
        $command = new SQLCommandForDropProcedureIfExists($string);
        $this->add($command);
        return $this;
    }
    public function create_procedure($name){
        $command = new SQLCommandForCreateProcedure($name);
        $this->add($command);
        return $command;
    }

    //======
    protected function startFirstItemOnANewLine(){
        return true;
    }
    protected function startEachItemOnANewLine(){
        return true;
    }
    private $add_delimiter_at_the_end = true;
    public function removeDelimiterAtTheEnd(){
        $this->add_delimiter_at_the_end = false;
    }
    protected function addDelimiterAtTheEnd(){
        return $this->add_delimiter_at_the_end;
    }
    ///----- utility methods to build multi queries
    
    public function addNewInsertQuery(){
        $query = new SQLInsertQuery();
        $this->add($query);
        return $query;
    }
    public function addNewReplaceQuery(){
        $query = new SQLReplaceQuery();
        $this->add($query);
        return $query;
    }
    public function addNewDeleteQuery(){
        $query = new SQLDeleteQuery();
        $this->add($query);
        return $query;
    }
    //=============
    public function __construct()
    {
        $this->delimiter = ";";
    }
    public function set_delimiter($string)
    {
        $this->delimiter = $string;
    }
    
    public function as_($identifier)
    {
        return $this;
    }

    public function start_transaction()
    {
        $this->add(new SQLStartTransaction());
        return $this;
    }

    public function set_timezone($string)
    {
        $this->add(new SQLTimezone($string));
        return $this;
    }

    public function create_database($string)
    {
        $this->add(new SQLCreateDatabase($string));
        return $this;
    }

    public function use_database($string)
    {        
        $this->add(new SQLUseDatabase($string));
        return $this;
    }

    public function commit()
    {
        $this->add(new SQLCommit());
        return $this;
    }

    protected function getItemClass()
    {
        return "ISQLCommand";
    }


    public function set_sql_mode_to_no_auto_value_on_zero()
    {
        $this->add(new DDLSQLMode_NoAutoValueOnZero());
        return $this;
    }

    public function set_auto_commit_to_zero()
    {
        $this->add(new SQLAutoCommitZero());
        return $this;
    }
    
}

abstract class DDLCommand implements ISQLCommand{
    abstract public function __toString();

}
class DDLSQLMode_NoAutoValueOnZero extends DDLCommand{
    public function __toString(){
        return "SET SQL_MODE = ". new SQLString("NO_AUTO_VALUE_ON_ZERO");
    }
}

class SQLStartTransaction extends DDLCommand{
    public function __toString(){
        return "START TRANSACTION";
    }
}
class SQLCommit extends DDLCommand{
    public function __toString(){
        return "COMMIT";
    }
}
abstract class SQLAutoCommit extends DDLCommand{
    private $int;
    public function __construct($int)
    {
        $this->int = new SQLInt($int);
    }

    public function __toString(){
        return "SET AUTOCOMMIT = ".$this->int;
    }
}
class SQLAutoCommitZero extends SQLAutoCommit{
    public function __construct()
    {
        parent::__construct(0);
    }
}

class SQLTimezone implements ISQLCommand{
    private $value;
    public function __construct($string)
    {
        $this->value = new SQLString($string);
    }

    public function __toString(){
        return "SET time_zone = ".$this->value;
    }
}

class SQLCreate{
    public static function database($name){
        return new SQLCreateDatabase($name);
    }
    public static function table($name){
        return new SQLCreateTableName($name);
    }
    public static function table_if_not_exists($name){
        return new SQLCreateTableIfNotExists($name);
    }
    public static function column($name){
        return new SQLColumnDeclaration($name);
    }

    public static function primary_key(){
        return new SQLPrimaryKey();
    }
    public static function unique_key($name){
        return new SQLUniqueKey($name);
    }
    public static function key($name){
        return new SQLKey($name);
    }

    public static function trigger()
    {
        return new SQLCommandForCreateTrigger();
    }
}
class SQLColumn{
    public static function name($name){
        return new SQLColumnDeclaration($name);
    } 
}
class SQLCreateDatabase implements ISQLCommand{
    private $value, $if_not_exists = "", $def_char_set = "utf8",$collate = "utf8_general_ci";
    public function __construct($name)
    {
        $this->value = new SQLIdentifier($name);
    }
    public function if_not_exists(){
        $this->if_not_exists = " IF NOT EXISTS ";
        return $this;
    } 

    public function __toString(){
        return sprintf("CREATE DATABASE%s%s  DEFAULT CHARACTER SET %s COLLATE %s", $this->if_not_exists,$this->value,$this->def_char_set,$this->collate);
    }
}
class SQLUseDatabase implements ISQLCommand{
    private $value;
    public function __construct($name)
    {
        $this->value = new SQLIdentifier($name);
    }
    
    public function __toString(){
        return sprintf("USE %s",$this->value);
    }
}
class SQLCommandForDo implements ISQLCommand{
    private $sql_value;
    public function __construct($sql_value_for_command)
    {
        $this->sql_value = ConvertToSQLValue::ifNotSQLValue($sql_value_for_command);
    }

    public function __toString(){
        return sprintf("DO %s",$this->sql_value);
    }
}

class SQLDelimiterCommand implements ISQLCommand{
    private $value;
    public function __construct($name=';')
    {
        $this->value = $name;
    }

    public function __toString(){
        //todo: no need to pass name
        return "DELIMITER ";
    }
}
class SQLSingleLineCommentCommand implements ISQLCommand{
    private $value;
    public function __construct($name)
    {
        $this->value = preg_replace("/\\s/i"," ", $name);
    }

    public function __toString(){
        return sprintf("-- %s",$this->value);
    }
}

abstract class SQLDropTable implements ISQLCommand{
    private $value, $if_exists = "";
    protected function only_if_exists(){
        $this->if_exists = " IF EXISTS";
    }
    public function __construct($name)
    {
        $this->value = new SQLIdentifier($name);
    }

    public function __toString(){
        return sprintf("DROP TABLE%s %s",$this->if_exists,$this->value);
    }
    
    //=========
    public static function name($name){
        return new SQLDropTableName($name);
    }
    public static function if_exists($name){
        return new SQLDropTableIfExists($name);
    }
}

class SQLDropTableName  extends SQLDropTable{
    
}

class SQLDropTableIfExists  extends SQLDropTable{
    public function __construct($name)
    {
        parent::__construct($name);
        $this->only_if_exists();
    }
}

#create table
abstract class SQLCreateTable implements ISQLCommand{
    private $value, $if_not_exists = "";
    private $engine_name;
    private $default_charset;
    private $comment;

    private $list_of_col_declarations;
    private $keys = array();

    /** @return SQLCreateTable */
    public function set_comment($string)
    {
        $this->comment = new SQLString($string);
        return $this;
    }

    /** @return SQLCreateTable */
    public function addColumn($col_declaration)
    {
        $this->list_of_col_declarations->add($col_declaration);
        return $this;
    }

    protected function only_if_not_exists(){
        $this->if_not_exists = " IF NOT EXISTS";
    }
    public function __construct($name)
    {
        $this->value = new SQLIdentifier($name);
        $this->set_engine_to_inno_db();
        $this->set_default_charset_to_utf8();
        $this->set_comment("");
        
        $this->list_of_col_declarations = new SQLColumnDeclarationList();
    }

    public function __toString(){
        return sprintf("CREATE TABLE%s %s (".SQLNewLineChar::get()."%s%s".SQLNewLineChar::get().") Engine=%s DEFAULT CHARSET=%s COMMENT=%s",$this->if_not_exists,$this->value,$this->getColumnsAsString(),$this->keys_string(),$this->engine_name,$this->default_charset,$this->comment);
    }

    //=========
    public static function name($name){
        return new SQLCreateTableName($name);
    }
    public static function if_not_exists($name){
        return new SQLCreateTableIfNotExists($name);
    }
    //===================
    /** @return SQLCreateTable */
    public function set_engine_to_inno_db(){
        $this->engine_name = SQLValueFor::InnoDb();
        return $this;
    }
    /** @return SQLCreateTable */
    public function set_engine_to_my_isam(){
        $this->engine_name = SQLValueFor::MyISAM();
        return $this;
    }
    /** @return SQLCreateTable */
    public function set_default_charset_to_utf8(){
        $this->default_charset = SQLValueFor::utf8();
        return $this;
    }

    private function getColumnsAsString()
    {
        return $this->list_of_col_declarations->__toString();
    }


    /** @return SQLCreateTable */
    public function add_primary_key($sql_primary_key){
       $sql_primary_key = ConvertToSQLPrimaryKey::if_column_name($sql_primary_key);
        if(!is_a($sql_primary_key, "SQLPrimaryKey")){
            throw new Exception("expected primary key data struture");
        }
        $this->keys[] = $sql_primary_key;
        return $this;
    }
    public function add_unique_key($sql_unique_key){
        $sql_unique_key = ConvertToSQLUniqueKey::if_column_name($sql_unique_key);
        if(!is_a($sql_unique_key, "SQLUniqueKey")){
            throw new Exception("expected unique key data struture");
        }
        $this->keys[] = $sql_unique_key;
        return $this;
    }
    public function add_key($sql__key){
        $sql__key = ConvertToSQLKey::if_column_name($sql__key);
        if(!is_a($sql__key, "SQLKey")){
            throw new Exception("expected key data struture");
        }
        $this->keys[] = $sql__key;
        return $this;
    }
    
    private function keys_string()
    {
        $keys_string = count($this->keys) > 0 ? join(",".SQLNewLineChar::get(),$this->keys): "";
        $keys_string = $this->list_of_col_declarations->isEmpty() ? $keys_string : ", ".SQLNewLineChar::get().$keys_string;
        return $keys_string;
    }

}
class SQLNewLineChar{
    public static function get(){
        return chr(10).chr(13);
    }
}
class SQLCreateTableName  extends SQLCreateTable{

}

class SQLCreateTableIfNotExists  extends SQLCreateTable{
    public function __construct($name)
    {
        parent::__construct($name);
        $this->only_if_not_exists();
    }
}

class SQLValueFor{
    public static function InnoDb(){
        return "InnoDb";
    }
    public static function MyISAM(){
        return "MyISAM";
    }

    public static function utf8(){
        return "utf8";
    }
    
}

class SQLColumnDeclarationList extends SQLListBase implements ISQLValueList{
    public function __construct()
    {
        $this->delimiter = ",".SQLNewLineChar::get();
    }

    public function as_($identifier)
    {
        return $this;
    }
    /**
     * @return string
     */
    protected function getItemClass()
    {
        return "SQLColumnDeclaration";
    }
}
class SQLColumnDeclaration implements ParameterForAlterTableAdd{
    private $name_of_column;
    
    private $enum = '';
    
    private $type='';
    private $length='';
    private $unsigned = '';
    private $zero_fill='';
    private $not_null='';
    private $auto_increment='';
    
    private $default_value='';
    private $comment='';
    
    public function __construct($name_of_column)
    {
        $this->name_of_column = new SQLIdentifier($name_of_column);
    }
    public function __toString()
    {
        return sprintf("%s %s%s%s%s%s%s%s%s%s",$this->name_of_column,$this->enum,$this->type,$this->length,$this->unsigned,$this->zero_fill,$this->not_null,$this->default_value,$this->auto_increment,$this->comment);
    }
    private function set_type_and_length($type,$length=null){
        $this->type = $type;
        $this->length = is_numeric($length)? "($length)": "";
        return $this;
    }

    /** @return SQLColumnDeclaration */
    public function unsigned(){
        $this->unsigned = " unsigned";
        return $this;
    }
    /** @return SQLColumnDeclaration */
    public function zero_fill(){
        $this->zero_fill = " ZEROFILL";
        return $this;
    }
    /** @return SQLColumnDeclaration */
    public function not_null(){
        $this->not_null = " NOT NULL";
        return $this;
    }
    /** @return SQLColumnDeclaration */
    public function auto_increment(){
        $this->auto_increment = " AUTO_INCREMENT";
        return $this;
    }

    /** @return SQLColumnDeclaration */
    public function default_value($value){
        $this->default_value = " DEFAULT ". new SQLString($value);
        return $this;
    }

    /** @return SQLColumnDeclaration */
    public function int($length = null)
    {
        return $this->set_type_and_length("int",$length);
    }
    /** @return SQLColumnDeclaration */
    public function tinyint($length = null)
    {
        return $this->set_type_and_length("tinyint",$length);
    }
    /** @return SQLColumnDeclaration */
    public function smallint($length = null)
    {
        return $this->set_type_and_length("smallint",$length);
    }
    /** @return SQLColumnDeclaration */
    public function mediumint($length = null)
    {
        return $this->set_type_and_length("mediumint",$length);
    }
    /** @return SQLColumnDeclaration */
    public function bigint($length = null)
    {
        return $this->set_type_and_length("bigint",$length);
    }
    /** @return SQLColumnDeclaration */
    public function float()
    {
        return $this->set_type_and_length("float");
    }

    /** @return SQLColumnDeclaration */
    public function text($length = null)
    {
        return $this->set_type_and_length("text",$length);
    }
    /** @return SQLColumnDeclaration */
    public function tinytext()
    {
        return $this->set_type_and_length("tinytext",null);
    }
    /** @return SQLColumnDeclaration */
    public function char($length = null)
    {
        return $this->set_type_and_length("char",$length);
    }
    /** @return SQLColumnDeclaration */
    public function varchar($length = null)
    {
        return $this->set_type_and_length("varchar",$length);
    }

    /** @return SQLColumnDeclaration */
    public function date()
    {
        return $this->set_type_and_length("date");
    }
    /** @return SQLColumnDeclaration */
    public function time()
    {
        return $this->set_type_and_length("time");
    }
    /** @return SQLColumnDeclaration */
    public function datetime()
    {
        return $this->set_type_and_length("datetime");
    }

    /** @return SQLColumnDeclaration */
    public function comment($string)
    {
        $this->comment = sprintf(" COMMENT %s", new SQLString($string));
        return $this;
    }

    /** @return SQLColumnDeclaration */
    public function enum($array)
    {
        if(!is_array($array)){
            throw new Exception("expected array for enum");
        }
        
        $valueList = new SQLValueList();
        for($i = 0; $i < count($array);$i++){
            if(is_numeric($array[$i])){
                $valueList->add(new SQLInt($array[$i]));
            }
            else{
                $valueList->add(new SQLString($array[$i]));
            }
        }
        $this->enum = sprintf(" enum(%s)", $valueList->__toString());
        return $this;
    }
}

class SQLPrimaryKey{
    protected $key_word = "PRIMARY KEY";
    private $column_name_array = array();
    protected $key_name;

    public function addColumn($column_name){
        $this->column_name_array[] = new SQLIdentifier($column_name."");
        return $this;
    }
    public function __toString()
    {
        return sprintf("%s%s (%s)",$this->key_word,$this->get_key_name(),join(",",$this->column_name_array));
    }

    private function get_key_name()
    {
        return $this->key_name ? " $this->key_name":"";
    }
}
class SQLUniqueKey extends SQLPrimaryKey{
    public function __construct($key_name)
    {
        $this->key_name = new SQLIdentifier($key_name);
        $this->key_word = "UNIQUE KEY";
    }
}
class SQLKey extends SQLPrimaryKey{
    public function __construct($key_name)
    {
        $this->key_name = new SQLIdentifier($key_name);
        $this->key_word = "KEY";
    }
}

//$ddl

class SQLTruncateTable implements ISQLCommand{
    protected $table_name;
    public function __construct($name)
    {
        $name = ConvertToSQLTableIdentifier::if_string($name);
        SQLBuilderException::throwIfNotTableIdentifier($name,"expected a table name or identifier");
        $this->table_name = $name;
    }

    public function __toString()
    {
        return sprintf("TRUNCATE TABLE %s",$this->table_name);
    }
    
}
abstract class SQLAlterTableBaseClass implements ISQLCommand{
    protected $table_name;
    public function __construct($name)
    {
        $name = ConvertToSQLTableIdentifier::if_string($name);
        SQLBuilderException::throwIfNotTableIdentifier($name,"expected a table name or identifier");
        $this->table_name = $name;
    }

    public function __toString()
    {
        $alter_command = sprintf("ALTER TABLE %s",$this->table_name);
        $specific_command = $this->getActionList();
        $final_string = sprintf("%s %s",$alter_command,$specific_command);
        return $final_string;
        //return sprintf("ALTER TABLE %s ADD %s",$this->table_name,join(",",$this->columns_to_add));
    }
    abstract protected function getActionList();
}
class SQLAlterTable extends SQLAlterTableBaseClass{
    private $value_list;
    public function __construct($name)
    {        
        parent::__construct($name);
        $this->value_list = new SQLValueList();        
    }

    protected function getActionList()
    {
        return $this->value_list;
    }
    /** @return SQLAlterTable */
    public function drop_primary_key(){
        $this->value_list->add(new SQLSubCommandForDropPrimarykey());
        return $this;
    }
    /** @return SQLAlterTable */
    public function add_primary_key($column_list){
        $subcommand = new SQLSubCommandForAddPrimarykey();
        $subcommand->setColumnList($column_list);
        $this->value_list->add($subcommand);
        return $this;
    }
    public function add_full_text($column_name_or_identifier){
        $subcommand = new SQLSubCommandForAddColumn();
        $subcommand->add_fulltext($column_name_or_identifier);
        $this->value_list->add($subcommand);
        return $this;
    }
    /** @return SQLAlterTable */
    public function add_column($column_declaration){
        $subcommand = new SQLSubCommandForAddColumn();
        $subcommand->add($column_declaration);
        $this->value_list->add($subcommand);
        return $this;
    }
}


class SQLAlterTableDropPrimaryKey extends SQLAlterTableBaseClass{


    protected function getActionList()
    {
        $value_list = new SQLValueList();
        $value_list->add(new SQLSubCommandForDropPrimarykey());
        return $value_list;
    }
}
class SQLAlterTableAddColumn extends SQLAlterTableBaseClass{
    private $sql_cols_to_add;
    public function __construct($name)
    {
        parent::__construct($name);
        $this->sql_cols_to_add = new SQLSubCommandForAddColumn();
    }    

    public static function name($name){
        return new self($name);
    }
    public function add($declaration_for_column_or_fulltext_index_columm){
        $this->sql_cols_to_add->add($declaration_for_column_or_fulltext_index_columm);
        return $this;        
    }
    public function add_fulltext($column_name_as_string_or_identifier){
        $this->sql_cols_to_add->add_fulltext($column_name_as_string_or_identifier);
        return $this;
    }


    protected function getActionList()
    {
        $value_list = new SQLValueList();
        $value_list->add($this->sql_cols_to_add);
        return $value_list;
    }
}
class SQLSubCommandForAddColumn extends SQLValueBase{
    public function as_($identifer)
    {
        return $this;
    }
    public function __toString()
    {
        return sprintf("ADD %s", join(",", $this->columns_to_add));
    }
    private $columns_to_add = array();

    public static function name($name){
        return new self($name);
    }
    public function add($declaration_for_column_or_fulltext_index_columm){
        if(!is_a($declaration_for_column_or_fulltext_index_columm, "ParameterForAlterTableAdd")){
            throw new Exception("expected column declaration or fulltext index column declaration for alter table add operation");
        }
        $this->columns_to_add[] = $declaration_for_column_or_fulltext_index_columm;
        return $this;
    }
    public function add_fulltext($column_name_as_string_or_identifier){
        return $this->add(new FullTextIndexDeclaration($column_name_as_string_or_identifier));
    }
}
class SQLSubCommandForDropPrimarykey extends SQLValueBase{
    public function as_($identifer)
    {
        return $this;
    }
    public function __toString()
    {
        return "DROP PRIMARY KEY";
    }
}
class SQLSubCommandForAddPrimarykey extends SQLValueBase{
    private $valueList;

    public function as_($identifer)
    {
        return $this;
    }
    public function __toString()
    {
        return sprintf("ADD PRIMARY KEY (%s)",$this->valueList);
    }
    public function __construct()
    {
        $this->valueList = new SQLIdentifierList();
    }

    public function setColumnList($column_list)
    {
        $column_list = ConvertToSQLIdentifier::if_string($column_list);
        if($this->isSQLIdentifier($column_list)){
            $column_list2 = new SQLIdentifierList();
            $column_list2->add($column_list);
            $column_list = $column_list2;
        }
        SQLBuilderException::throwIfNotSQLIdentifierList($column_list,"expected column name, column identifier or column list");
        $this->valueList = $column_list;        
    }

}


interface ParameterForAlterTableAdd{

}



class FullTextIndexDeclaration implements ParameterForAlterTableAdd{
    private $column_identifier;
    public function __construct($sql_identifier)
    {
        if(is_string($sql_identifier)){
            $sql_identifier = new SQLIdentifier($sql_identifier);
        }
        $this->throwExceptionIfNotSQLIdentifier($sql_identifier);
        $this->column_identifier = $sql_identifier;
    }

    public function __toString()
    {
        return sprintf("FULLTEXT (%s)",$this->column_identifier);
    }

    private function throwExceptionIfNotSQLIdentifier($sql_identifier)
    {
        if (!is_a($sql_identifier, "ISQLIdentifier")) {
            throw new exception ("SQLIdentifier expected for full text index declaration");
        }
    }
}

//ConvertToSQLValue::ifNotSQLValue($name);
class SQLCommandForDropTrigger implements ISQLCommand{
    private $name;
    private $if_exists = '';

    public function __toString()
    {
        return sprintf("DROP TRIGGER%s %s",$this->if_exists,$this->name);
    }
    public function if_exists(){
        $this->if_exists = " IF EXISTS";
        return $this;
    }

    public function trigger_name($name)
    {
        $name = ConvertToSQLIdentifier::if_string($name);
        SQLBuilderException::throwIfNotSQLIdentifier($name,"expects a sql identifier");
        
        $this->name = $name;
        return $this;
    }
}
class SQLCommandForEachRow extends SQLCommandList implements ISQLCommand{

    public function begin(){
        $begin = new SQLCommandForBegin();
        $this->add($begin);
        return $begin;
    }
    public function __toString()
    {
        $commands = parent::__toString();
        return sprintf("FOR EACH ROW%s",$commands);
    }
}
class SQLCommandForBegin extends SQLCommandList implements ISQLCommand{
    public function __toString()
    {
        $commands = parent::__toString();
        return sprintf("BEGIN%s%sEND",$commands,$this->newLineChar());
    }
    private function newLineChar(){
        return chr(10).chr(13);
    }
    public function set_var($col_value_pair){
        $this->add(new SQLCommandForSetVariable($col_value_pair));
        return $this;
    }
    public function dec_var($name){
        $command = new SQLCommandForCreateVariable($name);
        $this->add($command);
        return $command->variable_declaration();
    }
}
class SQLCommandForSetVariable implements ISQLCommand{
    private $identifier_value_pair;

    public function __construct($col_value_pair)
    {
        SQLBuilderException::throwIfNotSQLIdentifierValuePair(
            $col_value_pair, "expected identifier value pair"
        );
        $this->identifier_value_pair = $col_value_pair;
    }
    public function __toString()
    {
        return sprintf("SET %s", $this->identifier_value_pair);
    }
}
class SQLCommandForCreateVariable implements ISQLCommand{
    private $col_declaration;

    public function __construct($name)
    {        
        $this->col_declaration = new SQLColumnDeclaration($name);
    }
    public function __toString()
    {
        return sprintf("DECLARE %s", $this->col_declaration);
    }

    public function variable_declaration()
    {
        return $this->col_declaration;
    }
}


class CommandForSignalSQLState implements ISQLCommand{
    private $sql_state;
    private $message_text;

    public function __toString()
    {
        return sprintf("SIGNAL SQLSTATE %s SET MESSAGE_TEXT = %s",$this->sql_state,$this->message_text);
    }

    public function state($string)
    {
        $this->sql_state = ConvertToSQLString::if_not_sql_string($string);
        return $this;
    }
    /** @return  CommandForSignalSQLState*/
    public function message_text($string)
    {
        $this->message_text = ConvertToSQLString::if_not_sql_string($string);
        return $this;
    }

}
class SQLCommandForCreateTrigger implements ISQLCommand{
    private $name;
    private $trigger_type;
    private $table_name;
    private $commandlist_for_each_row;
    public function __construct()
    {
        $this->commandlist_for_each_row = new SQLCommandForEachRow();
    }

    private function newLine(){
        return chr(10).chr(13);
    }

    public function for_each_row()
    {
        return $this->commandlist_for_each_row;
    }

    public function __toString()
    {
        return sprintf(
            "CREATE TRIGGER %s %s %s%s%s",
            $this->name,$this->trigger_type,$this->table_name,
            $this->newLine(),$this->commandlist_for_each_row
        );
    }
    /** @return SQLCommandForCreateTrigger */
    public function trigger_name($name)
    {
        $name = ConvertToSQLIdentifier::if_string($name);
        SQLBuilderException::throwIfNotSQLIdentifier($name,"expects a sql identifier");
        
        $this->name = $name;
        return $this;
    }
    /** @return SQLCommandForCreateTrigger */
    public function before_insert_on($table_name){
        $this->set_type_and_table("BEFORE INSERT ON",$table_name);
        return $this;
    }
    /** @return SQLCommandForCreateTrigger */
    public function before_update_on($table_name){
        $this->set_type_and_table("BEFORE UPDATE ON",$table_name);
        return $this;
    }
    /** @return SQLCommandForCreateTrigger */
    public function before_delete_on($table_name){
        $this->set_type_and_table("BEFORE DELETE ON",$table_name);
        return $this;
    }
    /** @return SQLCommandForCreateTrigger */
    public function after_insert_on($table_name){
        $this->set_type_and_table("AFTER INSERT ON",$table_name);
        return $this;
    }
    /** @return SQLCommandForCreateTrigger */
    public function after_update_on($table_name){
        $this->set_type_and_table("AFTER UPDATE ON",$table_name);
        return $this;
    }
    /** @return SQLCommandForCreateTrigger */
    public function after_delete_on($table_name){
        $this->set_type_and_table("AFTER DELETE ON",$table_name);
        return $this;
    }
    
    private function set_type_and_table($type,$table_name)
    {
        $table_name = ConvertToSQLTableIdentifier::if_string($table_name);
        SQLBuilderException::throwIfNotTableIdentifier($table_name, "expects a table identifier");

        $this->trigger_type = $type;
        $this->table_name = $table_name;
        return $this;
    }
}

class SQLCommandForDropProcedureIfExists implements ISQLCommand{
    private $name;
    public function __construct($name)
    {
        $this->name = ConvertToSQLIdentifier::if_string($name);
        SQLBuilderException::throwIfNotSQLIdentifier($this->name,"expected SQL identifier");
    }

    public function __toString()
    {
        return sprintf("DROP PROCEDURE IF EXISTS %s",$this->name);
    }
}

class SQLCommandForCreateProcedure implements ISQLCommand{
    private $name;
    private $trigger_type;
    private $table_name;
    private $commandlist_for_begin;
    public function __construct($name)
    {
        $this->commandlist_for_begin = new SQLCommandForBegin();
        $this->name = ConvertToSQLIdentifier::if_string($name);
        SQLBuilderException::throwIfNotSQLIdentifier($this->name,"expected SQL identifier");
    }

    private function newLine(){
        return chr(10).chr(13);
    }

    public function begin()
    {
        return $this->commandlist_for_begin;
    }

    public function __toString()
    {
        return sprintf(
            "CREATE PROCEDURE %s()%s %s",
            $this->name,
            $this->newLine(),$this->commandlist_for_begin
        );
    }    
}

class SQLCommandForIf implements ISQLCommand{
    private $sql_test;
    private $sql_command_list_if_true;
    private $sql_command_list_if_false;
    private $else_if_statements = array();

    /** @return SQLCommandForIf */
    public function if_($sql_test){
        SQLBuilderException::throwIfNotSQLTest($sql_test);
        $this->sql_test = $sql_test;
        return $this;        
    }
    /** @return SQLCommandForIf */
    public function then($sql_command_or_command_list){
        $sql_command_or_command_list = $this->preprocess($sql_command_or_command_list);
        $this->sql_command_list_if_true = $sql_command_or_command_list;
        return $this;
    }
    /** @return SQLCommandForIf */
    public function else_if($sql_test,$sql_command_or_command_list){
        SQLBuilderException::throwIfNotSQLTest($sql_test);
        $sql_command_or_command_list = $this->preprocess($sql_command_or_command_list);
        $this->else_if_statements[] = array($sql_test,$sql_command_or_command_list);
        return $this;
    }
    /** @return SQLCommandForIf */
    public function else_($sql_command_or_command_list){
        $sql_command_or_command_list = $this->preprocess($sql_command_or_command_list);
        $this->sql_command_list_if_false = $sql_command_or_command_list;
        return $this;
    }
    private function newLineChar(){
        return chr(10).chr(13);
    }
    public function __toString(){
        return sprintf(
            "IF %s THEN %s%s%s%sEND IF",
            $this->sql_test,$this->sql_command_list_if_true,$this->getElseIfs(),$this->getElse(),$this->newLineChar());
    }
    private function getElseIfs(){
        $output = "";
        foreach($this->else_if_statements as $array){
            $condition = $array[0];
            $command_list = $array[1];
            $statement = sprintf("%sELSEIF %s THEN %s",$this->newLineChar(),$condition,$command_list);
            $output .= $statement;
        }
        return $output;
    }
    private function getElse(){
        return $this->sql_command_list_if_false ?
            sprintf("%sELSE%s",$this->newLineChar(),$this->sql_command_list_if_false) : "";
    }

    /**
     * @param $sql_command_or_command_list
     * @return $this
     */
    private function preprocess($sql_command_or_command_list)
    {
        SQLBuilderException::throwIfNotSQLCommandOrCommandList($sql_command_or_command_list);

        $sql_command_or_command_list =
            is_a($sql_command_or_command_list, "ISQLCommand") ?
                (new SQLCommandList())->add($sql_command_or_command_list) :
                $sql_command_or_command_list;
        return $sql_command_or_command_list;
    }
}


class SQLEntityIdGenerator{
    public static function newId(){
        $sql_date_format = new SQLDateFormatFunction(
            new SQLNowFunction(),
            "%d%i%Y%s%H%m"
        );

        $random_num = (new SQLRandomFunction())->multiply_by_int(99999);
        $rounded_num = (new SQLFloorFunction())->set_argument($random_num);
        $padded_num = new SQLRPadFunction($rounded_num,5,"0");
        
        $concat = new SQLConcatFunction();
        $concat->
        append($sql_date_format)->
        append(
            $padded_num
        );
        return $concat;
    }
}