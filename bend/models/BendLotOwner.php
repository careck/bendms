<?php

class BendLotOwner extends DbObject {

    public $bend_lot_id;
    public $user_id;
    public $d_start;
    public $d_end;

    public $address1;
    public $address2;
    public $town;
    public $postcode;
    public $state;
    public $country;
    
    public $notes;
    

    function getFullname() {
        if (!empty($this->user_id)) {
        	$user = $this->getUser(); 
            return !empty($user) ? $user->getFullName() : "";
        }
    }
    
    function getUser() {
    	return $this->Auth->getUser($this->user_id);
    }
    
    function getContact() {
    	$user = $this->getUser(); 
    	return !empty($user) ? $user->getContact() : null;
    }
    
    function isCurrent() {
    	return $this->d_start <= time() && (empty($this->d_end) || $this->d_end >= time());
    }
}
