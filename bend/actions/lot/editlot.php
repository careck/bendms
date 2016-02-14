<?php
function editlot_GET(Web $w) {
	list($id) = $w->pathMatch("id");
	$lot = new BendLot($w);
	if (!empty($id)) {
		$lot = $w->Bend->getLotForId($id);
	}
	
	$form = array("Lot" => array(
			array(
					array("Lot Number", "text", "lot_number", $lot->lot_number)
			),
			array(
					array("Occupancy", "select", "occupancy", $lot->occupancy, array("single","dual"))
			),
	));
	
	$w->out(Html::multiColForm($form, "/bend-lot/editlot/{$id}", "POST", "Save"));
	
}

function editlot_POST(Web $w) {
	list($id) = $w->pathMatch("id");
	$lot = new BendLot($w);
	if (!empty($id)) {
		$lot = $w->Bend->getLotForId($id);
	}
	
	$lot->fill($_POST);
	$lot->insertOrUpdate();
	
	$w->msg("Lot updated","/bend-lot");
}