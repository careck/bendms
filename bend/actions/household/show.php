<?php
function show_GET($w) {
	list($lotid,$householdid) = $w->pathMatch("lotid","householdid");
	
	if (empty($lotid)) $w->error("Need a Lot ID");
	if (empty($householdid)) $w->error("Need a household ID");
	
	$lot = $w->Bend->getLotForId($lotid);
	if (empty($lot)) $w->error("Lot {$lotid} does not exist");
	$household = $w->Bend->getHouseholdForId($householdid);
	if (empty($household)) $w->error("Household {$householdid} does not exist");
	
	History::add("Bend Household: ".$household->streetnumber);
	
	$lotTable = array();
	$lotTable["Household"] = array(
			array(
					array("Lot Number", "static", "", $lot->lot_number),
					array("Occupancy", "static", "", $lot->occupancy)),
			array(
					array("Street Number", "static", "", $household->streetnumber),
					array("Is CHL?", "static", "", $household->is_chl?"yes":"no"),
					array("Is Occupied?", "static", "", $household->is_occupied?"yes":"no"),
					array("Number of Occupants", "static", "", $household->num_occupants),
						
			),
	);
	
	$w->ctx("lot",$lot);
	$w->ctx("table",Html::multiColTable($lotTable));
	$w->ctx("household",$household);
	$w->ctx("currentOccupants",$household->getCurrentOccupants());
	$w->ctx("pastOccupants",$household->getPastOccupants());
	
}