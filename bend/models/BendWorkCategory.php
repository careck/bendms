<?php
class BendWorkCategory extends DbObject {
    public $parent_id;
    public $title;
    public $description;
    
    function getChildren() {
    	return $this->getObjects("BendWorkCategory",["is_deleted"=>0,"parent_id"=>$this->id],false,true,['title asc']);
    }
    
    /**
     * This returns a list with this category as the last object, with the
     * parents and granparent objects preceding.
     * 
     * @return array of BendWorkCategory objects
     */
    function getPath() {
    	if (empty($this->parent_id)) {
    		return [$this];
    	} else {
    		$parent = $this->Bend->getWorkCategoryForId($this->parent_id);
    		return array_merge($parent->getPath(),[$this]);
    	}
    }
    /**
     * This returns either only work entries in this
     * category or all entries from child categories for
     * a given period.
     * 
     * @param unknown $period
     */
    function getWorkentriesForPeriod($period,$includeChildren=false) {
    	$wes = $this->getObjects("BendWorkEntry",["is_deleted"=>0,"bend_workperiod_id"=>$period->id,"bend_work_category_id"=>$this->id]);
    	if (empty($wes)) {
    		$wes = [];
    	}
		if ($includeChildren) {
			$children = $this->getChildren();
			if (!empty($children)) {
				foreach ($children as $wc) {
					$wes2 = $wc->getWorkentriesForPeriod($period,true);
					if (!empty($wes2)) {
						$wes = array_merge($wes,$wes2);
					}
				}
			}
		}
		return $wes;
    }
    
    function getHoursLoggedForPeriod($period,$includeChildren=false) {
    	$hours = 0;
    	$wes = $this->getWorkentriesForPeriod($period,$includeChildren);
    	if (!empty($wes)) {
    		foreach ($wes as $we) {
    			$hours += $we->hours;
    		}
    	}
    	return $hours;
    }
}

