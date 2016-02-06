<?php
function remove_owner_ALL(Web $w) {
	$p = $w->pathMatch(["id"]);
	$lotOwner = $w->Bend->getBendLotOwnerById($p['id']);
	if (!empty($lotOwner)) {
		$lotOwner->delete();
	}
	$w->msg(!empty($lotOwner) ? "Object deleted." : "Nothing found.", "/bend-lot/index");
}