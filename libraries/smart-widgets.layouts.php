<?php

class ALayoutFor{
    //================ cross project
    /** @return SmartDiv */
    public static function block($content = "")
    {
        $div = new SmartDiv();
        $div->set_inner_html($content);
        return $div;
    }
    
    /** @return SmartSpan */
    public static function inLineBlock($content = ""){
        $span = new SmartSpan();
        $span->display_inline_block();
        $span->vertical_align_top();
        $span->add_child($content);
        return $span;
    }
    public static function inLineBlockBottomAligned($content = ""){
        $span = new SmartSpan();
        $span->display_inline_block();
        $span->vertical_align_bottom();
        $span->add_child($content);
        return $span;
    }
    public static function inLineBlockMiddleAligned($content = ""){
        $span = new SmartSpan();
        $span->display_inline_block();
        $span->vertical_align_middle();
        $span->add_child($content);
        return $span;
    }

    public static function oneRow($content = ""){
        return self::block($content);
    }
    public static function oneColumn($content = ""){
        return self::inLineBlock($content);
    }
    public static function oneColumnBottomAligned($content = ""){
        return self::inLineBlockBottomAligned($content);
    }
    public static function oneColumnMiddleAligned($content = ""){
        return self::inLineBlockMiddleAligned($content);
    }
    public static function twoColumns($column1_content,$column2_content)
    {
        $div = ALayoutFor::inLineBlock();
        $div->add_child(ALayoutFor::inLineBlock($column1_content));
        $div->add_child(ALayoutFor::inLineBlock($column2_content));
        return $div;
    }
    public static function threeColumns($column1_content,$column2_content,$column3_content)
    {
        $div = ALayoutFor::inLineBlock();
        $div->add_child(ALayoutFor::inLineBlock($column1_content));
        $div->add_child(ALayoutFor::inLineBlock($column2_content));
        $div->add_child(ALayoutFor::inLineBlock($column3_content));
        return $div;
    }
}

class LayoutForTwoRows extends SmartDiv{
    private $firstRow;
    private $secondRow;
    public function __construct()
    {
        parent::__construct();
        $this->firstRow = new SmartDiv();
        $this->secondRow = new SmartDiv();
    }

    public function firstRow(){
        return $this->firstRow;
    }
    public function secondRow(){
        return $this->secondRow;
    }

    public static function instance(){
        return new self();
    }

    public function __toString()
    {
        $this->add_child($this->firstRow);
        $this->add_child($this->secondRow);
        return parent::__toString();
    }
}

class LayoutForTwoColumns extends SmartDiv{
    private $leftColumn;
    private $rightColumn;
    public function __construct()
    {
        parent::__construct();
        $this->leftColumn = new SmartSpan();
        $this->rightColumn = new SmartSpan();
    }

    public function leftColumn(){
        return $this->leftColumn;
    }
    public function rightColumn(){
        return $this->rightColumn;
    }
    
    public static function instance(){
        return new self();
    }
    
    public function __toString()
    {
        $this->add_child($this->leftColumn);
        $this->add_child($this->rightColumn);
        return parent::__toString();
    }
}


class LayoutForThreeColumns extends SmartDiv{
    private $leftColumn;
    private $rightColumn;
    private $middleColumn;
    public function __construct()
    {
        parent::__construct();
        $this->leftColumn = new SmartSpan();
        $this->rightColumn = new SmartSpan();
        $this->middleColumn = new SmartSpan();
    }

    public function leftColumn(){
        return $this->leftColumn;
    }
    public function rightColumn(){
        return $this->rightColumn;
    }
    public function middleColumn(){
        return $this->middleColumn;
    }

    public static function instance(){
        return new self();
    }

    public function __toString()
    {
        $this->add_child($this->leftColumn);
        $this->add_child($this->middleColumn);
        $this->add_child($this->rightColumn);
        return parent::__toString();
    }
}

class LayoutForThreeRows extends SmartDiv{
    private $firstRow;
    private $secondRow;
    private $thirdRow;
    
    public function __construct()
    {
        parent::__construct();
        $this->firstRow = new SmartDiv();
        $this->secondRow = new SmartDiv();
        $this->thirdRow = new SmartDiv();
    }

    public function firstRow(){
        return $this->firstRow;
    }
    public function secondRow(){
        return $this->secondRow;
    }
    public function thirdRow(){
        return $this->thirdRow;
    }

    public static function instance(){
        return new self();
    }

    public function __toString()
    {
        $this->add_child_if($this->firstRow->hasChildren(),$this->firstRow);
        $this->add_child_if($this->secondRow->hasChildren(),$this->secondRow);
        $this->add_child_if($this->thirdRow->hasChildren(),$this->thirdRow);
        return parent::__toString();
    }
}

class LayoutForNColumns extends SmartDiv{
    private $list_of_columns = array();

    public function count(){
        return count($this->list_of_columns);
    }

    public function addNewColumn(){
        $column = new SmartSpan();
        $this->list_of_columns[] = $column;
        return $column;
    }
    public function addNewColumnIf($condition){
        if(!$condition){
            return new SmartSpan();
        }
        return $this->addNewColumn();
    }
    public function __toString()
    {
        $this->set_inner_html(join("",$this->list_of_columns));
        return parent::__toString();
    }
    public function columnAtIndex($index){
        return array_key_exists($index,$this->list_of_columns) ? $this->list_of_columns[$index]: new SmartSpan();
    }
}

class LayoutForNSpans extends SmartSpan{
    private $list_of_columns = array();

    public function count(){
        return count($this->list_of_columns);
    }

    public function addNewColumn(){
        $column = new SmartSpan();
        $this->list_of_columns[] = $column;
        return $column;
    }
    public function addNewColumnIf($condition){
        if(!$condition){
            return new SmartSpan();
        }
        return $this->addNewColumn();
    }
    public function __toString()
    {
        $this->set_inner_html(join("",$this->list_of_columns));
        return parent::__toString();
    }
    public function columnAtIndex($index){
        return array_key_exists($index,$this->list_of_columns) ? $this->list_of_columns[$index]: new SmartSpan();
    }
}

class LayoutForNRows extends SmartDiv{
    private $list_of_rows = array();
    
    public function count(){
        return count($this->list_of_rows);
    }

    public function addNewRow(){
        $row = new SmartDiv();
        $this->list_of_rows[] = $row;
        return $row;
    }
    public function addNewRowIf($condition){
        if($condition){
            return $this->addNewRow();
        }
        return new SmartDiv();
    }
    public function __toString()
    {
        $this->set_inner_html(join("",$this->list_of_rows));
        return parent::__toString();
    }
    public function columnAtIndex($index){
        return array_key_exists($index,$this->list_of_rows) ? $this->list_of_rows[$index]: new SmartDiv();
    }
}

class LayoutForStatusAndAction extends SmartDiv{
    private $leftColumn;
    private $rightColumn;
    public function __construct()
    {
        parent::__construct();
        $this->leftColumn = new SmartSpan();
        $this->rightColumn = new SmartSpan();
    }

    public function statusColumn(){
        return $this->leftColumn;
    }
    public function actionColumn(){
        return $this->rightColumn;
    }

    public static function instance(){
        return new self();
    }

    public function __toString()
    {
        $this->add_child($this->leftColumn);
        $this->add_child($this->rightColumn);
        return parent::__toString();
    }
}
//============================
class LayoutForListItemInHomogeneousScannableList extends LayoutForNColumns{    
    public function __construct()
    {
        parent::__construct();
        $this->primaryActionOrInfo = new SmartSpan();
        $this->supplementalActionOrInfo = new SmartSpan();
    }

    public function primaryActionOrInfo(){
        return $this->addNewColumn();
    }
    public function supplementalActionOrInfo(){
        return $this->addNewColumn();
    }
    public static function instance(){
        return new self();
    }
}
class LayoutForSplitScreen extends LayoutForTwoColumns{
    
}
class LayoutForBottomBar extends LayoutForNColumns{
    
}
abstract class LayoutForButton extends SmartDiv{
    private $action_that_will_take_place;
    private $capitalize = true;

    public function setActionThatWillTakePlace($content){
        $this->action_that_will_take_place = $content;
    }
    public function __toString()
    {
        $text = $this->action_that_will_take_place;
        $text = $this->capitalizeTextIfNecessary($text);
        $this->add_child($text);
        return parent::__toString();
    }

    /**
     * @param $text
     */
    private function capitalizeTextIfNecessary($text)
    {
        if ($this->capitalize) {
            $text = strtoupper($text);
        }
        return $text;
    }
}
abstract class LayoutForFlatButton extends LayoutForButton{
    
}
abstract class LayoutForRaisedButton extends LayoutForButton{

}
class LayoutForRaisedAffirmativeButton extends LayoutForRaisedButton{
    
}
class LayoutForRaisedDismissiveButton extends LayoutForRaisedButton{

}
class LayoutForFlatAffirmativeButton extends LayoutForFlatButton{

}
class LayoutForFlatDismissiveButton extends LayoutForFlatButton{

}
class LayoutForPromotedAction extends LayoutForFlatButton{

}
//----- these next three should have same color
class LayoutForLabel extends SmartSpan{

}
class LayoutForTextField extends TextInput{

}

class LayoutForFormAction extends SubmitInput{

}
class LayoutForStarterContentIfNoContent extends SmartDiv{
    
}
class LayoutForEducationalContentIfNoContent extends SmartDiv{

}
class LayoutForResssuranceIfNoContent extends SmartDiv{

}
class LayoutForPermissionRequest extends SmartDiv{

}
class LayoutForConfirmationRequest extends SmartDiv{

}
class LayoutForAcknowledgement extends SmartDiv{

}
class LayoutForErrorIfCantPerformAction extends SmartDiv{
    private $what_went_wrong = "";
    private $how_to_resolve_issue;

    public function setWhatWentWrong($content){
        $this->what_went_wrong = $content;
        return $this;
    }
    public function setHowToResolveIssue($content){
        $this->how_to_resolve_issue = $content;
        return $this;
    }
    public function __toString()
    {
        $this->add_child($this->what_went_wrong);
        $this->add_child($this->how_to_resolve_issue);
        return parent::__toString();
    }
}

class LayoutForNotificationAfterPerformingAction extends SmartDiv{
    private $content;

    public function setContent($content){
        $this->content = $content;
        return $this;
    }
    public function setCategory($content){
        $this->category = $content;
        return $this;
    }
    public function setPriority($content){
        $this->content = $content;
        return $this;
    }
    public function setNextAction($content){
        $this->content = $content;
        return $this;
    }
    public function optIn(){        
        return $this;
    }
    public function optOut(){        
        return $this;
    }
    public function __toString()
    {
        $this->add_child($this->content);
        return parent::__toString();
    }
}
class LayoutForCardShowcasingObject extends LayoutForNRows{

    private $action_row;
    public function optionalHeader()
    {
        return $this->addNewRow();
    }

    public function primaryTitle()
    {
        return $this->addNewRow();
    }

    public function photo()
    {
        return $this->addNewRow();
    }

    public function photoUsedToRepresentPrimaryContent()
    {
    }

    public function photoUsedToSupplementPrimaryContent()
    {
    }

    public function supportingTextOrDescription()
    {
        return $this->addNewRow();
    }

    public function primaryAction()
    {
        return $this->addNewRow();
    }

    public function supplementalActionOne()
    {
        return $this->addNewRow();
    }

    public function supplementalActionTwo()
    {
        return $this->addNewRow();
    }

    public function linkToContentDetails()
    {
        return $this->addNewRow();
    }

    public function truncateContentBeyondHeight($string)
    {
    }

    public function emphasizeNumbers()
    {
    }
}

class LayoutForChipOrCompactRepresentationOfObjectWhenPerfomingAction extends LayoutForTwoColumns{
}

class LayoutForConfirmationDialog extends LayoutForNRows{
    private $title;
    private $question;
    private $impact;
    private $action_row;

    public function __construct()
    {
        parent::__construct();
        $this->title = $this->addNewRow();
        $this->question = $this->addNewRow();
        $this->impact = $this->addNewRow();

        $this->action_row = new LayoutForNColumns();
    }

    public function titleRow()
    {
        return $this->title;
    }

    public function question()
    {
        return $this->question;
    }

    public function impact()
    {
        return $this->impact;
    }

    public function action_row()
    {
        return $this->action_row;
    }
    public function __toString()
    {
        $this->addNewRow()->add_child($this->action_row);
        return parent::__toString();
    }
}
class LayoutForWarningDialog extends SmartDiv{

}
class LayoutForInformationDialog extends SmartDiv{

}
class LayoutForNotificationDialog extends SmartDiv{
   
}

class LayoutForPicker extends SmartSelect{
    
}
class LayoutForToastOrSnackBar extends SmartDiv{
    
}

class LayoutForSubheader extends SmartSpan{
    public function __toString()
    {
        $this->opacity("0.38");
        $this->font_weight_bold();
        return parent::__toString();
    }
}
class LayoutForTextHint extends SmartSpan{
    public function __toString()
    {
        $this->opacity("0.38");
        return parent::__toString();
    }
}
class LayoutForHelperText extends SmartSpan{
    public function __toString()
    {
        $this->opacity("0.38");
        return parent::__toString();
    }
}
class LayoutForDisabledText extends SmartSpan{
    public function __toString()
    {
        $this->opacity("0.38");
        return parent::__toString();
    }
}
class LayoutForSecondaryText extends SmartSpan{
    public function __toString()
    {
        $this->opacity("0.54");
        return parent::__toString();
    }
}
class LayoutForPrimaryText extends SmartSpan{
    public function __toString()
    {
        $this->opacity("0.87");
        return parent::__toString();
    }
}

class LayoutForTooltip extends SmartDiv{

}
class LayoutForToolbar extends LayoutForNColumns{

}
class LayoutForWidget extends SmartDiv{    
}

