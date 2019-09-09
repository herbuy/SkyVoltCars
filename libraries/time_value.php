<?php
abstract class TimeValueAsObject{

    private $time;
    private $to_append = "";

    public function append_ago(){
        $this->to_append = " ago";
        return $this;
    }


    private $should_round_off = false;

    public function round_off(){
        $this->should_round_off = true;
        return $this;
    }

    public function value(){
        return $this->should_round_off ? round($this->time,0): $this->time;
    }
    
    public function __construct($time)
    {
        $this->time = $time;
    }


    public function __toString()
    {
        $label_in_plural = trim($this->getLabelAsPlural());
        $label_in_plural = strlen($label_in_plural) > 0 ? " $label_in_plural":"";

        $label_in_singular = trim($this->getLabelAsSingular());
        $label_in_singular = strlen($label_in_singular) > 0 ? " $label_in_singular":"";

        $value = $this->value();
        $final_label = $value == 1 ? $label_in_singular : $label_in_plural;

        return sprintf("%s%s%s",$value,$final_label,$this->to_append);
    }
    
    public function multiply_by($number){
        return $this->time * $number;
    }
    
    public function divide_by($number){
        return $this->time / $number;
    }

    protected function times_24()
    {
        return $this->multiply_by(24);
    }
    protected function divide_by_24()
    {
        return $this->divide_by(24);
    }

    protected function times_60(){
        return $this->multiply_by(60);
    }
    protected function divide_by_60(){
        return $this->divide_by(60);
    }

    protected function times_1440()
    {
        return $this->multiply_by(1440);
    }
    protected function divide_by_1440()
    {
        return $this->divide_by(1440);
    }

    protected function times_3600(){
        return $this->multiply_by(3600);
    }
    protected function divide_by_3600(){
        return $this->divide_by(3600);
    }
    protected function times_86400(){
        return $this->multiply_by(86400);
    }
    protected function divide_by_86400(){
        return $this->divide_by(86400);
    }
    
    protected function times_604800(){
        return $this->multiply_by(604800);
    }
    protected function divide_by_604800(){
        return $this->divide_by(604800);
    }
    protected function times_2592000(){
        return $this->multiply_by(2592000);
    }
    protected function divide_by_2592000(){
        return $this->divide_by(2592000);
    }
    protected function times_31536000(){
        return $this->multiply_by(31536000);
    }
    protected function divide_by_31536000(){
        return $this->divide_by(31536000);
    }
    //====

    protected function times_10080(){
        return $this->multiply_by(10080);
    }
    protected function divide_by_10080(){
        return $this->divide_by(10080);
    }
    protected function times_302400(){
        return $this->multiply_by(302400);
    }
    protected function divide_by_302400(){
        return $this->divide_by(302400);
    }
    protected function times_525600(){
        return $this->multiply_by(525600);
    }
    protected function divide_by_525600(){
        return $this->divide_by(525600);
    }
    //====
    //====

    protected function times_168(){
        return $this->multiply_by(168);
    }
    protected function divide_by_168(){
        return $this->divide_by(168);
    }
    protected function times_720(){
        return $this->multiply_by(720);
    }
    protected function divide_by_720(){
        return $this->divide_by(720);
    }
    protected function times_8760(){
        return $this->multiply_by(8760);
    }
    protected function divide_by_8760(){
        return $this->divide_by(8760);
    }
    //====
    //====

    protected function times_7(){
        return $this->multiply_by(7);
    }
    protected function divide_by_7(){
        return $this->divide_by(7);
    }
    protected function times_30(){
        return $this->multiply_by(30);
    }
    protected function divide_by_30(){
        return $this->divide_by(30);
    }
    protected function times_365(){
        return $this->multiply_by(365);
    }
    protected function divide_by_365(){
        return $this->divide_by(365);
    }
    //====
    //====
    protected function times_4(){
        return $this->multiply_by(4);
    }
    protected function divide_by_4(){
        return $this->divide_by(4);
    }

    protected function times_12(){
        return $this->multiply_by(12);
    }
    protected function divide_by_12(){
        return $this->divide_by(12);
    }
    protected function times_52(){
        return $this->multiply_by(52);
    }
    protected function divide_by_52(){
        return $this->divide_by(52);
    }
    //====
    public function greaterThan($number){
        return $this->value() > $number;
    }
    public function LessThan($number){
        return $this->value() < $number;
    }
    public function equals($number){
        return $this->value() == $number;
    }

    //default implementations    
    abstract protected function getLabelAsPlural();
    abstract protected function getLabelAsSingular();

    abstract public function toSeconds();
    abstract public function toMinutes();
    abstract public function toHours();
    abstract public function toDays();
    abstract public function toWeeks();
    abstract public function toMonths();
    abstract public function toYears();
}

class TimeInSeconds extends TimeValueAsObject{
    
    protected function getLabelAsPlural()
    {
        return "seconds";
    }
    protected function getLabelAsSingular()
    {
        return "second";
    }

    public function toSecondsAgo(){
        return new TimeInSeconds(time() - $this->value());
    }
    public function toSeconds(){
        return new TimeInSeconds($this->value());
    }
    public function toMinutes(){
        return new TimeInMinutes($this->divide_by_60());
    }
    public function toHours(){
        return  new TimeInHours($this->divide_by_3600());
    }
    public function toDays(){
        return  new TimeInDays($this->divide_by_86400());
    }

    public function toWeeks(){
        return  new TimeInWeeks($this->divide_by_604800());
    }

    public function toMonths(){
        return  new TimeInMonths($this->divide_by_2592000());
    }
    public function toYears(){
        return  new TimeInYears($this->divide_by_31536000());
    }

    public function asTimeDuration(){
        return $this->asTimeDuration_(new TimeInSeconds($this->value()));
    }

    public function asTimeAgo(){
        $time = $this->asTimeDuration_($this->toSecondsAgo());
        return $time->append_ago();
    }

    /**
     * @param TimeInSeconds $time
     * @return mixed
     */
    private function asTimeDuration_($time)
    {
        if ($time->greaterThan(31536000 - 1)) {
            $time = $time->toYears();
        } else if ($time->greaterThan(2592000 - 1)) {
            $time = $time->toMonths();
        } else if ($time->greaterThan(604800 - 1)) {
            $time = $time->toWeeks();
        } else if ($time->greaterThan(86400 - 1)) {
            $time = $time->toDays();
        } else if ($time->greaterThan(3600 - 1)) {
            $time = $time->toHours();
        } else if ($time->greaterThan(60 - 1)) {
            $time = $time->toMinutes();
        } else {
            $time = $time->toSeconds();
        }
        $time->round_off();
        return $time;
    }


}
class TimeInMinutes extends TimeValueAsObject{
    
    protected function getLabelAsPlural()
    {
        return "minutes";
    }
    protected function getLabelAsSingular()
    {
        return "minute";
    }
    public function toSeconds(){
        return new TimeInSeconds($this->times_60());
    }
    public function toMinutes(){
        return new TimeInMinutes($this->value());
    }
    public function toHours(){
        return  new TimeInHours($this->divide_by_60());
    }
    public function toDays(){
        return  new TimeInDays($this->divide_by_1440());
    }

    public function toWeeks(){
        return  new TimeInWeeks($this->divide_by_10080());
    }
    public function toMonths(){
        return  new TimeInMonths($this->divide_by_302400());
    }
    public function toYears(){
        return  new TimeInYears($this->divide_by_525600());
    }


}
class TimeInHours extends TimeValueAsObject{
    
    protected function getLabelAsPlural()
    {
        return "hours";
    }
    protected function getLabelAsSingular()
    {
        return "hour";
    }
    public function toSeconds(){
        return new TimeInSeconds($this->times_3600());
    }
    public function toMinutes(){
        return new TimeInMinutes($this->times_60());
    }
    public function toHours(){
        return  new TimeInHours($this->value());
    }
    public function toDays(){
        return  new TimeInDays($this->divide_by_24());
    }
    public function toWeeks(){
        return  new TimeInWeeks($this->divide_by_168());
    }
    public function toMonths(){
        return  new TimeInMonths($this->divide_by_720());
    }
    public function toYears(){
        return  new TimeInYears($this->divide_by_8760());
    }
}
class TimeInDays extends TimeValueAsObject{
    
    protected function getLabelAsPlural()
    {
        return "days";
    }
    protected function getLabelAsSingular()
    {
        return "day";
    }
    public function toSeconds(){
        return new TimeInSeconds($this->times_86400());
    }
    public function toMinutes(){
        return new TimeInMinutes($this->times_1440());
    }
    public function toHours(){
        return  new TimeInHours($this->times_24());
    }
    public function toDays(){
        return  new TimeInDays($this->value());
    }
    public function toWeeks(){
        return  new TimeInWeeks($this->divide_by_7());
    }
    public function toMonths(){
        return  new TimeInMonths($this->divide_by_30());
    }
    public function toYears(){
        return  new TimeInYears($this->divide_by_365());
    }
}

class TimeInWeeks extends TimeValueAsObject{

    protected function getLabelAsPlural()
    {
        return "weeks";
    }
    protected function getLabelAsSingular()
    {
        return "week";
    }
    public function toSeconds(){
        return new TimeInSeconds($this->times_604800());
    }
    public function toMinutes(){
        return new TimeInMinutes($this->times_10080());
    }
    public function toHours(){
        return  new TimeInHours($this->times_168());
    }
    public function toDays(){
        return  new TimeInDays($this->times_7());
    }

    public function toWeeks(){
        return  new TimeInWeeks($this->value());
    }
    public function toMonths(){
        return  new TimeInMonths($this->divide_by_4());
    }
    public function toYears(){
        return  new TimeInYears($this->divide_by_52());
    }

    
}

class TimeInMonths extends TimeValueAsObject{

    protected function getLabelAsPlural()
    {
        return "months";
    }
    protected function getLabelAsSingular()
    {
        return "month";
    }
    public function toSeconds(){
        return new TimeInSeconds($this->times_2592000());
    }
    public function toMinutes(){
        return new TimeInMinutes($this->times_302400());
    }
    public function toHours(){
        return  new TimeInHours($this->times_720());
    }
    public function toDays(){
        return  new TimeInDays($this->times_30());
    }

    public function toWeeks(){
        return  new TimeInWeeks($this->times_4());
    }
    public function toMonths(){
        return  new TimeInMonths($this->value());
    }
    public function toYears(){
        return  new TimeInYears($this->divide_by_12());
    }

}

class TimeInYears extends TimeValueAsObject{

    protected function getLabelAsPlural()
    {
        return "years";
    }
    protected function getLabelAsSingular()
    {
        return "year";
    }
    public function toSeconds(){
        return new TimeInSeconds($this->times_31536000());
    }
    public function toMinutes(){
        return new TimeInMinutes($this->times_525600());
    }
    public function toHours(){
        return  new TimeInHours($this->times_8760());
    }
    public function toDays(){
        return  new TimeInDays($this->times_365());
    }
    public function toWeeks(){
        return  new TimeInWeeks($this->times_52());
    }
    public function toMonths(){
        return  new TimeInMonths($this->times_12());
    }
    public function toYears(){
        return  new TimeInYears($this->value());
    }

}

