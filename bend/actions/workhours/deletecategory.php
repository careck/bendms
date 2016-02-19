<?php
function deletecategory_GET(Web $w) {
	list($id) = $w->pathMatch("id");
	if (!empty($id)) {
		$cat = $w->Bend->getWorkCategoryForId($id);
		if (!empty($cat)) {
			$cat->delete();
		}
	}
	$w->msg("Category deleted","/bend-workhours/admin");
}