<?php

/**
 * ExampleService class for demonstration purposes
 * 
 * @author Carsten Eckelmann, May 2014
 */
class BendService extends DbService {

    function getAllLots() {
        return $this->getObjects("BendLot", ["is_deleted" => 0],false, true,["lot_number asc"]);
    }

    function getLotForId($id) {
        return $this->getObject("BendLot", $id);
    }

    function getAllHouseholds() {
        return $this->getObjects("BendHousehold", ["is_deleted" => 0],false, true,["streetnumber asc"]);
    }

    function getHouseholdForId($id) {
        return $this->getObject("BendHousehold", $id);
    }
    
    function getBendLotOwnerForId($id) {
    	return $this->getObject("BendLotOwner", $id);
    }
    
    function getHouseholdOccupantForId($id) {
    	return $this->getObject("BendHouseholdOccupant", $id);
    }
    
    function getAllWorkPeriods() {
    	return $this->getObjects("BendWorkPeriod",["is_deleted"=>0],null,false,["d_start asc"]);
    }
    
    function getWorkPeriodForId($id) {
    	return $this->getObject("BendWorkPeriod",$id);
    }
    
    function getWorkEntryForId($id) {
    	return $this->getObject("BendWorkEntry",$id);
    }
    
    function getWorkPeriodForDate($timestamp) {
    	$wps = $this->getAllWorkPeriods();
    	foreach ($wps as $wp) {
    		if (!$wp->is_closed  && $wp->d_start <= $timestamp && $timestamp <= $wp->d_end) {
    			return $wp;
    		}
    	}
    }
    /**
     * Return all work (not deleted)
     *
     * @param mixed $period either an object of BendWorkPeriod or integer of an id
     * @param mixed $user either an object of User or integer of an id
     * @return array of BendWorkEntry objects
     */
    function getWorkhours($user = null, $period = null) {
    	$where = ['is_deleted'=>0];
    	if (!empty($user)) {
    		$userid = is_a($user,"User") ? $user->id : $user;
    		$where['user_id']=$userid;
    	}
    	if (!empty($period)) {
    		$id = is_a($period, "BendWorkPeriod") ? $period->id : $period;
    		$where['bend_workperiod_id']=$id;
    	}
    	return $this->getObjects("BendWorkEntry",$where,false,true,["d_date desc"]);
    }

    /**
     * Return all work (not deleted)
     *
     * @param mixed $period either an object of BendWorkPeriod or integer of an id
     * @param mixed $user either an object of User or integer of an id
     * @return array of BendWorkEntry objects
     */
    function getAttributedWorkhours($user = null, $period = null) {
        $where = ['is_deleted'=>0];
        if (!empty($user)) {
            $userid = is_a($user,"User") ? $user->id : $user;
            $where['attributed_user_id']=$userid;
        }
        if (!empty($period)) {
            $id = is_a($period, "BendWorkPeriod") ? $period->id : $period;
            $where['bend_workperiod_id']=$id;
        }
        return $this->getObjects("BendWorkEntry",$where,false,true,["d_date desc"]);
    }

    
    /**
     * Return all work entries for this user
     *
     * @param mixed $period either an object of BendWorkPeriod or integer of an id
     * @return array of BendWorkEntry objects
     */
    function getWorkhoursForUser($user, $period = null) {
    	if (empty($user)) {
    		return null;
    	}
    	return $this->getWorkhours($user,$period);
    }    

    /**
     * Return all work entries for this user
     *
     * @param mixed $period either an object of BendWorkPeriod or integer of an id
     * @return array of BendWorkEntry objects
     */
    function getAttributedWorkhoursForUser($user, $period = null) {
        if (empty($user)) {
            return null;
        }
        return $this->getAttributedWorkhours($user,$period);
    }    


    function getWorkEntries($period=null) {
    	$where = ["is_deleted"=>0];
    	if (!empty($period)) {
    		$id = is_a($period, "BendWorkPeriod") ? $period->id : $period;
    		$where["bend_workperiod_id"]=$id;
    	}
    	return $this->getObjects("BendWorkEntry",$where);
    }
    
    /**
     * Return all work entries for this period
     *
     * @param mixed $period either an object of BendWorkPeriod or integer of an id
     * @return array of BendWorkEntry objects
     */
    function getWorkentriesForPeriod($period) {
    	if (empty($period)) {
    		return null;
    	} else {
    		return $this->getWorkEntries($period);
    	}
    }
    
    function getWorkCategoryForId($id) {
    	return $this->getObject("BendWorkCategory",$id);
    }
    
    function getTopLevelWorkCategories() {
    	return $this->getObjects("BendWorkCategory",["is_deleted"=>0,"parent_id"=>null],false,true,["title asc"]);
    }
    
    function getAllOccupants($does_workhours=false) {
    	$where["is_deleted"] = 0;
    	if ($does_workhours) {
    		$where["does_workhours"] = 1;
    	}
    	return $this->getObjects("BendHouseholdOccupant",$where);
    }
    
    /**
     * Returns only those occupant users that do workhours
     * 
     * @return User[]
     */
    function getOccupantUsers() {
    	$occupants = $this->getAllOccupants(true);
    	$users = [];
    	if (!empty($occupants)) {
    		foreach ($occupants as $oc) {
    			$users[] = $this->Auth->getUser($oc->user_id);
    		}
    	}
    	usort($users,function ($a, $b) {
    		return strcmp($a->getSelectOptionTitle(),$b->getSelectOptionTitle());
    	});
    	return $users;
    }
 
     /**
     * Returns only those occupant users that do workhours
     * 
     * @return User[]
     */
    function getOccupants() {
        $occupants = $this->getAllOccupants(true);
        if (!empty($occupants)){
            usort($occupants,function ($a, $b) {
                return strcmp($a->getSelectOptionTitle(),$b->getSelectOptionTitle());
            });
        }            
        return $occupants;
    }
 

    function getUsersForHousehold($household) {
    	$occupants = $household->getAllOccupants();
    	$users = [];
    	if (!empty($occupants)) {
    		foreach ($occupants as $oc) {
    			$users[] = $this->Auth->getUser($oc->user_id);
    		}
    	}
    	usort($users,function ($a, $b) {
    		return strcmp($a->firstname,$b->firstname);
    	});
    	return $users;
    }
    
    function getWorkEntriesForHousehold($household, $period=null) {
    	$entries = [];
    	$occupants = $household->getAllOccupants();
    	if (empty($occupants)) return null;
    	foreach ($occupants as $occupant) {
    		if ($occupant->does_workhours) {
	    		$household_entries = $this->getAttributedWorkhoursForUser($occupant->getUser(),$period);
	    		if (!empty($household_entries)) {
	    			$entries = array_merge($entries,$household_entries);
	    		}
    		}
    	}
    	return $entries;
    }
}
