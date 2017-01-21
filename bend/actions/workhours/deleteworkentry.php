<?php
function deleteworkentry_GET(Web $w) {
	list($id) = $w->pathMatch("id");
	if (!empty($id)) {
		$entry = $w->Bend->getWorkEntryForId($id);
		if (!empty($entry) && $entry->canDelete($w->Auth->user())) {
			try {
				$entry->delete();
			} catch (Exception $ex) {
				$_SESSION['error'] = $ex->getMessage();
				$w->ctx('error', $_SESSION['msg']);
				$w->redirect($_SERVER['HTTP_REFERER']);	
			}
		}
	}
	$_SESSION['msg'] = "Work Entry Deleted";
	$w->ctx('msg', $_SESSION['msg']);
	$w->redirect($_SERVER['HTTP_REFERER']);	
}