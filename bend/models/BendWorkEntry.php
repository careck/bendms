<?php
class BendWorkEntry extends DbObject {
    
    public $bend_workperiod_id;
    public $user_id;
    public $d_date;
    public $hours;
    public $description;
    public $bend_work_category_id;
    
    public function insert($force_validation=true) {
    	if (empty($this->bend_workperiod_id)) {
    		$wp = $this->Bend->getWorkPeriodForDate($this->d_date);
    		if (!empty($wp)) {
    			$this->bend_workperiod_id = $wp->id;
    		}
    	}
    	parent::insert($force_validation);
    }
}