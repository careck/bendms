<?php
class BendWorkCategory extends DbObject {
    public $parent_id;
    public $title;
    public $description;
    
    function getChildren() {
    	return $this->getObjects("BendWorkCategory",["is_deleted"=>0,"parent_id"=>$this->id],false,true,['title asc']);
    }
    
    function getAllWorkEntries($period = null) {
    	if (!empty($period)){
    		return $this->getObjects("BendWorkEntry",["is_deleted"=>0,"bend_workperiod_id"=>$period->id,"bend_work_category_id"=>$this->id]);
    	} else {
    		return $this->getObjects("BendWorkEntry",["is_deleted"=>0,"bend_work_category_id"=>$this->id]);
    	}
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
    	$wes = $this->getAllWorkEntries($period);
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
    
    /**
     * Delete the WorkCategory. Move all child categories up to this parent level. 
     * If this is a top level category then it can only be deleted if it has no 
     * child categories and no work hours are attached to it!
     * {@inheritDoc}
     * @see DbObject::delete()
     */
    function delete($force = false) {
	    $children = $this->getChildren();
    	if (!empty($this->parent_id)) {
	    	// get all child categories and delete them!
	    	if (!empty($children)) {
	    		foreach ($children as $child) {
	    			$child->delete($force);
	    		}
	    	}
			// then get all hours that are directly attached to this category and move them one higher
			$sql = "update bend_work_entry set bend_work_category_id = " . $this->parent_id . " where bend_work_category_id = " . $this->id;
			$this->db->sql ( $sql );
	    	Parent::delete($force);
    	} else {
    		// we can only delete Top Level Categories which have no children
    		// and have no workhours attached
    		$workentries = $this->getAllWorkEntries();
    		if (empty($children) && empty($workentries)) {
    			Parent::delete();
    		} else {
    			throw new Exception("Top Level Category not empty");
    		}
    	}
    }
}

