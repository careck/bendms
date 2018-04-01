<?php

class BendHousehold extends DbObject {

    public $bend_lot_id;
    public $streetnumber;
    public $is_chl;
    public $is_occupied;
    public $is_deleted;
    public $num_occupants;

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
    
    public function getTitle() {
    	return $this->streetnumber;
    }
    
    public function getSelectOptionTitle() {
    	return "#".$this->getTitle();
    }
    
    /**
     * This returns current or past occupants that
     * lived at a household during the workperiod
     * 
     * @param BendWorkPeriod $wp
     * @return <BendHouseholdOccupant>[]
     */
    public function getOccupantsForWorkperiod(BendWorkPeriod $wp) {
    	// get all occupants for this household
    	$all_occupants = $this->getAllOccupants();
    	$occupants = [];
    	if (!empty($all_occupants)) {
	    	foreach ($all_occupants as $occ) {
	    		if (!$occ->does_workhours) continue;
	    		if (!empty($occ->d_end) && $occ->d_end <= $wp->d_start) continue;
	    		// check if $occ resided in the household during the work period
	    		if ($occ->d_start < $wp->d_end) {
	    			$occupants[] = $occ;
	    		}
	    	}
    	}
    	return $occupants;
    }
    
    public function getWorkhoursLevyForWorkperiod(BendWorkPeriod $workperiod) {
    	$occupants = $this->getOccupantsForWorkperiod($workperiod);
    	$levy = 0;
    	if (!empty($occupants)) {
	    	foreach ($occupants as $occ) {
	    		$levy += $occ->getWorkhoursLevyForWorkperiod($workperiod);
	    	}
    	}
    	return $levy;
    }
}
