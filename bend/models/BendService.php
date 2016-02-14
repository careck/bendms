<?php

/**
 * ExampleService class for demonstration purposes
 * 
 * @author Carsten Eckelmann, May 2014
 */
class BendService extends DbService {

    function getAllLots() {
        return $this->getObjects("BendLot", array("is_deleted" => 0));
    }

    function getLotForId($id) {
        return $this->getObject("BendLot", $id);
    }

    function getAllHouseholds() {
        return $this->getObjects("BendHousehold", array("is_deleted" => 0));
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
    	return $this->getObjects("BendWorkPeriod",["is_deleted"=>0],null,false,"d_start");
    }
    
    function getWorkPeriodForId($id) {
    	return $this->getObject("BendWorkPeriod",$id);
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
    	return $this->getObjects("BendWorkEntry",$where);
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
     * Return all work entries for this period
     *
     * @param mixed $period either an object of BendWorkPeriod or integer of an id
     * @return array of BendWorkEntry objects
     */
    function getWorkhoursForPeriod($period) {
    	if (empty($period)) {
    		return null;
    	} else {
    		$id = is_a($period, "BendWorkPeriod") ? $period->id : $period;
    		return $this->getObjects("BendWorkEntry",["is_deleted"=>0, "bend_workperiod_id"=>$id]);
    	}
    }
}
