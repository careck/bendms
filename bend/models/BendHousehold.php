<?php

class BendHousehold extends DbObject {

    public $bend_lot_id;
    public $streetnumber;
    public $is_chl;
    public $is_occupied;
    public $is_deleted;

    public function getAllOccupants() {
    	return $this->getObjects("BendHouseholdOccupant",["bend_household_id"=>$this->id, "is_deleted"=>0]);
    }
    
    public function getCurrentOccupants() {
    	$occupants = $this->getAllOccupants();
    	return !empty($occupants) ? array_filter($occupants,function ($oc) {
    		return $oc->isCurrent();
    	}) : null;
    }
    
    public function getPastOccupants() {
    	$occupants = $this->getAllOccupants();
    	return !empty($occupants) ? array_filter($occupants,function ($oc) {
    		return !$oc->isCurrent();
    	}) : null;
    }
    
    public function getLot() {
    	return $this->Bend->getLotForId($this->bend_lot_id);
    }
}
