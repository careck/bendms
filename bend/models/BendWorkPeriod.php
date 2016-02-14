<?php
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
    
    function getHoursBudget() {
    	// the budget is the sum of all eligible persons 
    	// times the monthly person budget for
    	// each month of this period
    	return 0;
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

