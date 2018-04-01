<?php
class BendWorkEntry extends DbObject {
    
    public $bend_workperiod_id;
    
    //The user who entered the work hours
    public $user_id; 
    
    public $d_date;
    public $hours;
    public $description;
    public $bend_work_category_id;
    
    // the user who should benefit from those hours
    public $attributed_user_id;
    
    // the household which should benefit from those hours
    public $bend_household_id;

    /**
     * assign a workperiod to the workentry based on the date
     */
    private function setWorkperiod() {
    	$wp = $this->Bend->getWorkPeriodForDate($this->d2Time($this->d_date));
    	if (empty($wp)) {
    		throw NoMatchingWorkPeriodException();
    	}
    	if (!$wp->is_closed) {
    		$this->bend_workperiod_id = $wp->id;
    	} else {
    		// check whether household is unoccupied
    		$h = $this->getHousehold();
    		if (!empty($h)) {
    			if ($h->is_occupied) {
    				throw new WorkPeriodClosedException();
    			}
    		}
    	}
    }
    
    /**
     * tries to set the household for this workentry
     * as some users may be residents in more than one household
     * the system will assign to the first household that comes up!
     */
    private function setHousehold() {
    	$households = $this->Bend->getHouseholdsForOccupantId($this->attributed_user_id);
    	if (!empty($households)) {
    		$h = $households[0];
    		$this->bend_household_id = $h->id;
    	}
    }
    
    public function insert($force_validation=true) {
    	if (empty($this->bend_household_id)) {
    		$this->setHousehold();
    	}
    	if (empty($this->bend_workperiod_id)) {
    		$this->setWorkperiod();
    	}
    	parent::insert($force_validation);
    }
    
    public function update($force_null_values = false, $force_validation = true) {
    	if (empty($this->bend_household_id)) {
    		$this->setHousehold();
    	}
    	
    	$this->setWorkperiod();
    	parent::update($force_null_values,$force_validation);
    }

    
    public function getWorkCategory() {
    	return $this->Bend->getWorkCategoryForId($this->bend_work_category_id);
    }
    public function getFullCategoryTitle() {
    	$wc = $this->getWorkCategory();
    	return !empty($wc) ? $wc->title : "";
    }
    
    public function getUser() {
    	return $this->Auth->getUser($this->user_id);
    }
    
    public function getAccredited() {
    	return $this->Auth->getUser($this->attributed_user_id);
    }
    
    public function getHousehold() {
    	return $this->Bend->getHouseholdForId($this->bend_household_id);
    }
    
    public function getHouseholdTitle() {
    	$bh = $this->getHousehold();
    	return !empty($bh) ? $bh->getTitle() : "";
    }
    
    public function canDelete(User $user) {
    	return !empty($user) && ($user->id == $this->user_id || $user->hasRole("bend_admin"));
    }
    
    public function delete($force = true){
    	// check if workperiod is closed then deletion is forbidden
    	$wp = $this->Bend->getWorkPeriodForId($this->bend_workperiod_id);
    	if (!empty($wp)) {
    		if ($wp->is_closed) {
    			throw new Exception("Work entry cannot be deleted when its work period is closed!");
    		}
    	}
    	Parent::delete($force);
    }
}
