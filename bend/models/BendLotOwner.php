<?php

class BendLotOwner extends DbObject {

    public $bend_lot_id;
    public $user_id;
    public $d_start;
    public $d_end;
    public $is_deleted;

    function getFullname() {
        if (!empty($this->user_id)) {
            return $this->getUser()->getFullName();
        }
    }
    
    function getUser() {
    	return $this->Auth->getUser($this->user_id);
    }
    
    function getContact() {
    	return $this->getUser()->getContact();
    }
}
