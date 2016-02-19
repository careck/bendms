<?php
class BendWorkCategory extends DbObject {
    public $parent_id;
    public $title;
    public $description;
    
    function getChildren() {
    	return $this->getObjects("BendWorkCategory",["is_deleted"=>0,"parent_id"=>$this->id]);
    }
}

