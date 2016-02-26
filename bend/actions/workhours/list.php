<?php
function list_GET(Web $w) {
	History::add("List Workhours");
	list($userid,$periodid) = $w->pathMatch("a","b");
	if (!empty($userid)) {
		$user = $w->Auth->getUser($userid);
	} else {
		$user = $w->Auth->user();
	}
	
	$workentries = $w->Bend->getWorkEntries($periodid);
	
	$w->ctx("user",$user);
	$w->ctx("workentries",$workentries);
	
}