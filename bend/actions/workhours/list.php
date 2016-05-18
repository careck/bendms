<?php
function list_GET(Web $w) {
	History::add("List Workhours");
	list($userid,$periodid) = $w->pathMatch("a","b");
	
	// get the user
	if (!empty($userid)) {
		$user = $w->Auth->getUser($userid);
	} else {
		$user = $w->Auth->user();
	}
	
	// calculate total work hours for this period
	$workentries = $w->Bend->getWorkhoursForUser($user,$periodid);
	$total_worked = 0;
	$total_accredited = 0;
	if (!empty($workentries)) {
		foreach ($workentries as $we) {
			$total_worked += $we->hours;
			if ($we->user_id == $we->attributed_user_id) {
				$total_accredited += $we->hours;
			}
		}
	}
	$w->ctx("total_worked",$total_worked);
	$w->ctx("total_accredited",$total_accredited);
	$w->ctx("user",$user);
	$w->ctx("workentries",$workentries);
	$w->ctx("workPeriod",$w->Bend->getWorkPeriodForId($periodid));
	$w->ctx("allWorkPeriods",$w->Bend->getAllWorkPeriods());
	
}