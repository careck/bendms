<?php
function showlot_GET(Web $w) {
	list($id) = $w->pathMatch("id");
	if (empty($id)) $w->error("Need a Lot ID");

	$lot = $w->Bend->getLotForId($id);
	if (empty($lot)) $w->error("Lot {$id} does not exist");
	
	History::add("Bend Lot: ".$lot->lot_number);
	$lotTable = array();
	$lotTable["Lot"] = array(
			array(
					array("Lot Number", "static", "", $lot->lot_number),
					array("Occupancy", "static", "", $lot->occupancy)),
	);
	
	$w->ctx("lot",$lot);
	$w->ctx("lotTable",Html::multiColTable($lotTable));
	$w->ctx("owners",$lot->getAllOwners());
	$w->ctx("households",$lot->getAllHouseholds());
	
}