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
        return $this->getObject("BendLot", ["id" => $id, "is_deleted" => 0]);
    }

    function getAllHouseholds() {
        return $this->getObjects("BendHousehold", ["is_deleted" => 0],false, true,["bend_lot_id asc"]);
    }

    function getHouseholdForId($id) {
        return $this->getObject("BendHousehold", ["id" => $id, "is_deleted" => 0]);
    }
    
    function getBendLotOwnerForId($id) {
    	return $this->getObject("BendLotOwner", ["id" => $id, "is_deleted" => 0]);
    }
    
    function getHouseholdOccupantForId($id) {
    	return $this->getObject("BendHouseholdOccupant", ["id" => $id, "is_deleted" => 0]);
    }
    
    function getAllWorkPeriods() {
    	return $this->getObjects("BendWorkPeriod",["is_deleted"=>0],null,false,["d_start desc"]);
    }
    
    function getWorkPeriodForId($id) {
    	return $this->getObject("BendWorkPeriod",["id" => $id, "is_deleted" => 0]);
    }
    
    function getWorkEntryForId($id) {
    	return $this->getObject("BendWorkEntry",["id" => $id, "is_deleted" => 0]);
    }
    
    /**
     * return household objects where a user is an occupant
     * one user can be an occupant in more than one household!
     * 
     * @param int $id
     * @return <BendHousehold>[]
     */
    function getHouseholdsForOccupantId($id) {
    	$occupants = $this->getObjects("BendHouseholdOccupant",["is_deleted" => 0,"does_workhours"=>1,"user_id" => $id]);
		$households = [];
		if (!empty($occupants)) {
			foreach ($occupants as $occupant) {
				$households[]= $this->getHouseholdForId($occupant->bend_household_id);
			}
		}
		return $households;
    }
    
    function getWorkPeriodForDate($timestamp) {
    	$wps = $this->getAllWorkPeriods();
    	foreach ($wps as $wp) {
    		if ($wp->d_start <= $timestamp && $timestamp <= $wp->d_end) {
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
    function getWorkhours($user = null, $period = null, $household) {
    	$where = ['is_deleted'=>0];
    	if (!empty($user)) {
    		$userid = is_a($user,"User") ? $user->id : $user;
    		$where['user_id']=$userid;
    	}
    	if (!empty($period)) {
    		$id = is_a($period, "BendWorkPeriod") ? $period->id : $period;
    		$where['bend_workperiod_id']=$id;
    	}
    	if (!empty($household)) {
    		$id = is_a($household, "BendHousehold") ? $household->id : $household;
    		$where['bend_household_id']=$id;
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
    function getAttributedWorkhours($user = null, $period = null, $household = null) {
        $where = ['is_deleted'=>0];
        if (!empty($user)) {
            $userid = is_a($user,"User") ? $user->id : $user;
            $where['attributed_user_id']=$userid;
        }
        if (!empty($period)) {
            $id = is_a($period, "BendWorkPeriod") ? $period->id : $period;
            $where['bend_workperiod_id']=$id;
        }
        if (!empty($household)) {
        	$id = is_a($household, "BendHousehold") ? $household->id : $household;
        	$where['bend_household_id']=$id;
        }
        
        return $this->getObjects("BendWorkEntry",$where,false,true,["d_date desc"]);
    }

    
    /**
     * Return all work entries for this user
     *
     * @param mixed $period either an object of BendWorkPeriod or integer of an id
     * @return array of BendWorkEntry objects
     */
    function getWorkhoursForUser($user, $period = null, $household = null) {
    	if (empty($user)) {
    		return null;
    	}
    	return $this->getWorkhours($user,$period,$household);
    }    

    /**
     * Return all work entries for this user
     *
     * @param mixed $period either an object of BendWorkPeriod or integer of an id
     * @return array of BendWorkEntry objects
     */
    function getAttributedWorkhoursForUser($user, $period = null, $household = null) {
        if (empty($user)) {
            return null;
        }
        return $this->getAttributedWorkhours($user,$period,$household);
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
	    		$household_entries = $this->getAttributedWorkhoursForUser($occupant->getUser(),$period,$household);
	    		if (!empty($household_entries)) {
	    			$entries = array_merge($entries,$household_entries);
	    		}
    		}
    	}
    	return $entries;
    }
    
    function getWorkhoursFiltered($userid=null, $householdid=null, $workperiodid=null) {
    	$where["is_deleted"] = 0;
    	if (!empty($userid)) {
    		$where["attributed_user_id"] = $userid;
    	}
    	if (!empty($householdid)) {
    		$where["bend_household_id"] = $householdid;
    	}
    	if (!empty($workperiodid)) {
    		$where["bend_workperiod_id"] = $workperiodid;
    	}
    	return $this->getObjects("BendWorkEntry",$where);
    }
    
    public function navigation(Web $w, $title = null, $nav = null) {
    	if ($title) {
    		$w->ctx("title", $title);
    	}
    
    	$nav = $nav ? $nav : array();
    
    	if ($w->Auth->loggedIn()) {
	    	$w->menuLink("bend-workhours", "Workhours", $nav);
	    	$w->menuLink("bend-electricity", "Electricity", $nav);
	    	if ($w->Auth->hasRole("bend_admin")) {
	    		$w->menuLink("bend-workhours/admin", "Admin - Workhours", $nav);
	    		$w->menuLink("bend-household", "Admin - Households", $nav);
	    		$w->menuLink("bend-lot", "Admin - Lots", $nav);
	    		$w->menuLink("bend-electricity/admin", "Admin - Electricity", $nav);
    		}
    	}
    
    	$w->ctx("navigation", $nav);
    	return $nav;
    }
    
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
    
}

class WorkPeriodClosedException extends Exception {}
class NoMatchingWorkPeriodException extends Exception{}


