<?php
function ajax_getcontact_GET(Web $w) {
	$w->setLayout(null);
	list($userid) = $w->pathMatch("userid");
	
	if (!empty($userid)) {
		$user = $w->Auth->getUser($userid);
		if (!empty($user)) {
			$w->out(json_encode($user->getContact()->toArray()));
		}
	}
}