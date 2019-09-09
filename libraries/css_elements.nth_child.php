<?php
require_once ("css_elements.php");

class ComponentInCSSQueryForNthChild{
    protected $parent_class;
    protected $parent_indices_as_arr_or_int;
    
    protected $child_class;
    protected $child_indices_as_arr_or_int;
}
class CSSQueryForNthChild extends ComponentInCSSQueryForNthChild{

    public function parent($string)
    {
        $obj = new ParentClassInCSSQueryForNthChild();
        $obj->parent_class = $string;
        return $obj;
    }
}
class ParentClassInCSSQueryForNthChild extends ComponentInCSSQueryForNthChild{

    public function indexes($arr_or_number)
    {
        $arr_or_number = is_array($arr_or_number) ? $arr_or_number : array($arr_or_number);

        $obj = new ParentIndicesInCSSQueryForNthChild();
        $obj->parent_class = $this->parent_class;
        $obj->parent_indices_as_arr_or_int = $arr_or_number;
        return $obj;
    }
    public function all(){
        return $this->indexes(array("n+1"));
    }
    public function first(){
        return $this->indexes(1);
    }
    public function after_index($number){
        return $this->indexes(array("n+".$number));
    }
    public function before_index($number){
        return $this->indexes(array("n-".$number));
    }
}

class ParentIndicesInCSSQueryForNthChild extends ComponentInCSSQueryForNthChild{
    public function child($class){
        $obj = new ChildClassInCSSQueryForNthChild();
        $obj->parent_class = $this->parent_class;
        $obj->parent_indices_as_arr_or_int = $this->parent_indices_as_arr_or_int;
        $obj->child_class = $class;
        return $obj;
    }

    public function css(){
        $counter = 0;
        $elmt = null;
        foreach ($this->parent_indices_as_arr_or_int as $parent_index){
            $counter += 1;
            if($counter == 1){ $elmt =
                CSSElementOfClassAndNthChild($this->parent_class,$parent_index);
            }
            else{$elmt = $elmt->or_(
                CSSElementOfClassAndNthChild($this->parent_class,$parent_index)
            );

            }
        }
        return $elmt;
    }
}

class ChildClassInCSSQueryForNthChild extends ComponentInCSSQueryForNthChild{

    public function indexes($arr_or_number)
    {
        $arr_or_number = is_array($arr_or_number) ? $arr_or_number : array($arr_or_number);

        $obj = new ChildIndicesInCSSQueryForNthChild();
        $obj->parent_class = $this->parent_class;
        $obj->parent_indices_as_arr_or_int = $this->parent_indices_as_arr_or_int;
        $obj->child_class = $this->child_class;
        $obj->child_indices_as_arr_or_int = $arr_or_number;
        return $obj;
    }

    public function all(){
        return $this->indexes(array("n+1"));
    }
    public function first(){
        return $this->indexes(1);
    }
    public function after_index($number){
        return $this->indexes(array("n+".$number));
    }
    public function before_index($number){
        return $this->indexes(array("n-".$number));
    }
}

class ChildIndicesInCSSQueryForNthChild extends ComponentInCSSQueryForNthChild{
    /** @return CSSElement */
    public function css(){        
        $counter = 0;
        $elmt = null;
        foreach ($this->parent_indices_as_arr_or_int as $parent_index){
            $counter += 1;
            if($counter == 1){ $elmt =
                CSSElementOfClassAndNthChild(
                    $this->child_class,$this->child_indices_as_arr_or_int
                )->
                inside(
                    CSSElementOfClassAndNthChild(
                        $this->parent_class,
                        $parent_index
                    )
                );
            }
            else{$elmt = $elmt->or_(
                    CSSElementOfClassAndNthChild(
                        $this->child_class,$this->child_indices_as_arr_or_int
                    )->
                    inside(
                        CSSElementOfClassAndNthChild(
                            $this->parent_class,
                            $parent_index
                        )
                    )
                );

            }
        }
        return $elmt;
    }
}
/*
//==========
printf(
    "%s<hr/>%s<hr/>%s<hr/>%s<hr/>%s<hr/>%s<hr/>%s<hr/>%s<hr/>",

    (new CSSQueryForNthChild())->
    parent("my_ancestor")->
    indexes(3)->
    child("my_child")->
    indexes(1)->
    css()->width("500")->height("200")->
    getFullDeclarationAsString()

    ,
    (new CSSQueryForNthChild())->
    parent("my_ancestor")->all()->
    child("my_child")->
    indexes(1)->
    css()->width("500")->height("200")->
    getFullDeclarationAsString()

    ,
    (new CSSQueryForNthChild())->
    parent("my_ancestor")->first()->
    child("my_child")->
    indexes(1)->
    css()->width("500")->height("200")->
    getFullDeclarationAsString()

    ,
    (new CSSQueryForNthChild())->
    parent("my_ancestor")->after_index(7)->
    child("my_child")->
    indexes(1)->
    css()->width("500")->height("200")->
    getFullDeclarationAsString()

    ,
    (new CSSQueryForNthChild())->
    parent("my_ancestor")->before_index(10)->
    child("my_child")->
    indexes(1)->
    css()->width("500")->height("200")->
    getFullDeclarationAsString()

    //==========================
    ,
    (new CSSQueryForNthChild())->
    parent("my_ancestor")->first()->
    child("my_child")->
    first()->
    css()->width("500")->height("200")->
    getFullDeclarationAsString()

    ,
    (new CSSQueryForNthChild())->
    parent("my_ancestor")->after_index(7)->
    child("my_child")->
    after_index(4)->
    css()->width("500")->height("200")->
    getFullDeclarationAsString()

    ,
    (new CSSQueryForNthChild())->
    parent("my_ancestor")->before_index(10)->
    child("my_child")->
    before_index(29)->
    css()->width("500")->height("200")->
    getFullDeclarationAsString()
);


*/

    