<?php

/**
 * Describes a single lot on Bend
 */
class BendLot extends DbObject {

    public $lot_number; // the number as per development plan
    public $occupancy; // the number of possible households, eg. 1 or 2

    /**
     * returns owners which are not deleted
     * 
     * @return array(BendLotOwners)
     */

    function getAllOwners() {
        if (!empty($this->id)) {
            return $this->getObjects("BendLotOwner", ["is_deleted" => 0, "bend_lot_id" => $this->id]);
        }
    }

    /**
     * returns owners which are not deleted and startdate &lt; today &lt; enddate
     * 
     * @return array(BendLotOwners)
     */
    function getCurrentOwners() {
        $owners = $this->getAllOwners();
        if (empty($owners)) {
            return;
        }

        return array_filter($owners, function($owner) {
            return $owner->isCurrent();
        });
    }

    /**
     * return all households for this lot which are not deleted
     * 
     * @return array(BendHousehold)
     */
    function getAllHouseholds() {
        if (!empty($this->id)) {
            return $this->getObjects("BendHousehold", ["is_deleted" => 0, "bend_lot_id" => $this->id],false,true,["streetnumber asc"]);
        }
    }

    
}
