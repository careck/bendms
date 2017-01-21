<?php
function deletehousehold_GET(Web $w) {
	list($lotid,$householdid) = $w->pathMatch("lotid","housholdid");
	if (!empty($lotid)) {
		$lot = $w->Bend->getLotForId($lotid);
	}
	if (empty($lot)) {
		$w->error("lot not found");
	}
	if (!empty($householdid)) {
		$household = $w->Bend->getHouseholdForId($householdid);
	}
	if (empty($household)) {
		$w->error("lot owner not found");
	}
	
	$household->delete();
	$w->msg("Household removed.","bend-lot/showlot/{$lotid}#households");
}