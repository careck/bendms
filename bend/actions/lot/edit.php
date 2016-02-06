<?php
function edit_GET(Web $w) {
	$p = $w->pathMatch(["id"]);
	$lot = new BendLot($w);
	if (!empty($p["id"])) {
		$lot = $w->Bend->getLotById($p["id"]);
	}
	
	$form = array("Lot" => array(
			array(
					array("Lot Number", "text", "lot_number", $lot->lot_number)
			),
			array(
					array("Occupancy", "select", "occupancy", $lot->occupancy, [1=>"single",2=>"dual"])
			),
	));
	
	$w->out(Html::multiColForm($form, "/bend-lot/edit/{$p["id"]}", "POST", "Save"));
	
}

function edit_POST(Web $w) {
	$p = $w->pathMatch(["id"]);
	$lot = new BendLot($w);
	if (!empty($p["id"])) {
		$lot = $w->Bend->getLotById($p["id"]);
	}
	
	$lot->fill($_GET);
	$lot->insertOrUpdate();
	
	$w->msg("Lot updated","/bend-lot");
}