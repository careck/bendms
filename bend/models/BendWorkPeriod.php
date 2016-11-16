<?php

use Carbon\Carbon;

class BendWorkPeriod extends DbObject {
    public $d_start;
    public $d_end;
    public $is_closed;
    public $monthly_person_hours;

    /**
     * Calculate the difference in months between two dates (v1 / 18.11.2013)
     *
     * @param \DateTime $date1
     * @param \DateTime $date2
     * @return int
     */
    public static function diffInMonths(\DateTime $date1, \DateTime $date2)
    {
        $diff =  $date1->diff($date2);

        $months = $diff->y * 12 + $diff->m + $diff->d / 30;

        return (int) round($months);
    }
    
    function getSelectOptionTitle() {
    	return formatDate($this->d_start)." - ".formatDate($this->d_end);
    }
    
    function getWorkhours() {
    	return $this->Bend->getWorkentriesForPeriod($this);
    }
    
    function getNumberOfMonths() {
    	//$start = Carbon::createFromTimestamp($this->d_start);
    	//$end = Carbon::createFromTimestamp($this->d_end);
    	//return $start->diffInMonths($end);
        return $this->diffInMonths(new DateTime(formatDate($this->d_start,"Y-m-d")), new DateTime(formatDate($this->d_end,"Y-m-d")));
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
    	return $hours;
    }

    
}

