<?php
function fixworkentries_GET(Web $w) {
	$user = $w->Auth->user();
	
	// only super user can run this expensive action!!
	if (!$user->is_admin) {
		$w->error("access denied");
	}
	
	// find all work entries that have no household or no workperiod and fix them
	$sql = "select * from bend_work_entry where is_deleted = 0 and (bend_workperiod_id is null or bend_household_id is null)";
	$sql_results = $w->Auth->db->sql($sql)->fetch_all();
	if (!empty($sql_results)) {
		$workentries = $w->Auth->getObjectsFromRows("BendWorkEntry", $sql_results, true);
	}
	$total = 0;
	if (!empty($workentries)) {
		foreach ($workentries as $we) {
			// simply updating the object will fix all problems
			$we->update();
			$total++;
		}
	}
	$w->msg("Fixed <b>$total</b> entries.","/bend-workhours/admin");
}