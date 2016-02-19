<?php

use Carbon\Carbon;

class BendWorkPeriod extends DbObject {
    public $d_start;
    public $d_end;
    public $is_closed;
    public $monthly_person_hours;
    
    function getSelectOptionTitle() {
    	return formatDate($this->d_start)." - ".formatDate($this->d_end);
    }
    
    function getWorkhours() {
    	return $this->Bend->getWorkhoursForPeriod($this);
    }
    
    function getNumberOfMonths() {
    	$start = Carbon::createFromTimestamp($this->d_start,"Australia/Sydney");
    	$end = Carbon::createFromTimestamp($this->d_end,"Australia/Sydney");
    	return $start->diffInMonths($end) + 1;
    }
    
    function getSinglePersonBudget() {
    	return $this->getNumberOfMonths() * $this->monthly_person_hours;	
    }
    
    /**
     * return a list of occupants which have been active during this work period
     * @return NULL
     */
    function getOccupants() {
    	$occupants = $this->getObjects("BendHouseholdOccupant",["is_deleted"=>0,"does_workhours"=>1]);
    	return empty($occupants) ? null :
    		array_filter($occupants,function ($oc) {
    			return $oc->d_start < $this->d_end && (empty($oc->d_end) || $oc->d_end > $this->d_start);
    		})	
    	;
    }
    	
    function getAllPersonsBudget() {
    	// the budget is the sum of all eligible persons 
    	// times the monthly person budget for
    	// each month of this period
    	$budget = 0;
    	$occupants = $this->getOccupants();
    	if (!empty($occupants)) {    		
    		return sizeof($occupants) * $this->getSinglePersonBudget();
    	}
    	return $budget;
    }
    
    function getTotalHoursLogged() {
    	$hours = 0;
    	$workhours = $this->getWorkhours();
    	if (!empty($workhours)) {
    		foreach ($workhours as $wh) {
    			$hours += $wh->hours;
    		}
    	}
    }
}

