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

	// get workperiod
	if (!empty($periodid)) {
		$workperiod = $w->Bend->getWorkPeriodForId($periodid);
	} else {
		$workperiod = $w->Bend->getWorkPeriodForDate(time());
	}
	
	// calculate total work hours for this period
	$workentries = $w->Bend->getWorkhoursForUser($user,$workperiod->id);
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

	$workentries_attributed_raw = $w->Bend->getAttributedWorkhoursForUser($user,$workperiod->id);
	$workentries_attributed = [];
	$total_attributed = 0;
	if (!empty($workentries_attributed_raw)) {
		foreach ($workentries_attributed_raw as $wea) {
			if ($wea->user_id != $wea->attributed_user_id) {
				$workentries_attributed[] = $wea;
				$total_attributed += $wea->hours;
			}
		}
	}
	$w->ctx("total_worked",$total_worked);
	$w->ctx("total_accredited",$total_accredited);
	$w->ctx("total_attributed",$total_attributed);
	$w->ctx("user",$user);
	$w->ctx("workentries",$workentries);
	$w->ctx("workentries_attributed",$workentries_attributed);
	$w->ctx("workPeriod",$workperiod);
	$w->ctx("allWorkPeriods",$w->Bend->getAllWorkPeriods());
	
}