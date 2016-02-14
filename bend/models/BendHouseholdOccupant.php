<?php
class BendHouseholdOccupant extends DbObject {
    public $bend_household_id;
    public $user_id;
    public $d_start;
    public $d_end;
    public $pays_electricity;
    public $does_workhours;
    
    public function isCurrent(){
    	return $this->d_start <= time() && (empty($this->d_end) || $this->d_end >= time());
    }
    
    function getFullname() {
    	if (!empty($this->user_id)) {
    		return $this->getUser()->getFullName();
    	}
    }
    
    function getUser() {
    	return $this->Auth->getUser($this->user_id);
    }
    
    function getContact() {
    	$user = $this->getUser();
    	return !empty($user) ? $user->getContact() : null;
    }
    
    /**
     * Return all work entries for this household occupant
     * 
     * @param mixed $period either an object of BendWorkPeriod or integer of an id
     * @return array of BendWorkEntry objects
     */
    function getWorkhours($period = null) {
    	return $this->Bend->getWorkhoursForUser($this->user_id,$period);
    }
}