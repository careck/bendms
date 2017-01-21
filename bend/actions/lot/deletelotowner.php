<?php
function deletelotowner_GET(Web $w) {
	list($lotid,$ownerid) = $w->pathMatch("lotid","ownerid");
	if (!empty($lotid)) {
		$lot = $w->Bend->getLotForId($lotid);
	}
	if (empty($lot)) {
		$w->error("lot not found");
	}
	if (!empty($ownerid)) {
		$owner = $w->Bend->getBendLotOwnerForId($ownerid);
	}
	if (empty($owner)) {
		$w->error("lot owner not found");
	}
	
	$owner->delete();
	$w->msg("Owner removed.","bend-lot/showlot/{$lotid}#owners");
}