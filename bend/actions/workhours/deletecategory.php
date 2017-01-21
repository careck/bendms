<?php
function deletecategory_GET(Web $w) {
	list($id) = $w->pathMatch("id");
	if (!empty($id)) {
		$cat = $w->Bend->getWorkCategoryForId($id);
		if (!empty($cat)) {
			try {
				$cat->delete();
			} catch (Exception $ex) {
				$w->error("The Top Level Category can only be deleted when no work entries are attached to it.","/bend-workhours/admin#categories");
			}
		}
	}
	$w->msg("Category deleted. All attached hours have been moved to its parent category.","/bend-workhours/admin#categories");
}