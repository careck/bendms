<?php
function list_GET(Web $w) {
	History::add("List Workhours");
	list($userid,$periodid) = $w->pathMatch("a","b");
	
	// get the user
	if (!empty($userid) && $w->Auth->hasRole("bend_admin")) {
		$user = $w->Auth->getUser($userid);
	} else {
		$user = $w->Auth->user();
	}

	if (empty($user)) {
		$w->error("No such user.");
	}
	// get workperiod
	if (!empty($periodid)) {
		$workperiod = $w->Bend->getWorkPeriodForId($periodid);
	} else {
		$workperiod = $w->Bend->getWorkPeriodForDate(time());
	}
	if (empty($workperiod)) {
		$w->error("No Workperiod found to display.");
	}
	
	// get all workperiods to find the previous and next
	$all_workperiods = $w->Bend->getAllWorkPeriods();
	if (empty($all_workperiods)) {
		$w->error("No Workperiods found.");
	}
	$previous_workperiod_id = null;
	$next_workperiod_id = null;
	$current_index=-1;
	for ($i =0; $i < sizeof($all_workperiods); $i++) {
		$wp = $all_workperiods[$i];
		if ($wp->id == $workperiod->id) {
			$current_index = $i; break;
		}
	}
	if ($current_index > 0) {
		$next_workperiod_id = $all_workperiods[$current_index-1]->id;
	}
	if ($current_index < sizeof($all_workperiods)-1) {
		$previous_workperiod_id = $all_workperiods[$current_index+1]->id;
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
	$w->ctx("previous_workperiod_id", $previous_workperiod_id);
	$w->ctx("next_workperiod_id", $next_workperiod_id);
	$w->ctx("allWorkPeriods",$w->Bend->getAllWorkPeriods());
	
}