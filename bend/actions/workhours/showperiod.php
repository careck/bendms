<?php
function showperiod_GET(Web $w) {
	list($id) = $w->pathMatch("a");
	
	$wp = $w->Bend->getWorkperiodForId($id);
	if (empty($wp)) {
		$w->error("Workperiod does not exist","/bend-workhours/admin#workperiods");
	}
	History::add("Work Period: ".formatDate($wp->d_start));
	$w->ctx("workperiod",$wp);
	$w->ctx("categories", $w->Bend->getTopLevelWorkCategories());
	$w->ctx("households",$w->Bend->getAllHouseholds());
	
}