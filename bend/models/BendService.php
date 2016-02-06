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

}
