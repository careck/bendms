<?php
function ajax_getchildcategories_GET(Web $w) {
	$w->setLayout(null);
	list($id) = $w->pathMatch("a");
	$cat = $w->Bend->getWorkCategoryForId($id);
	$out = [];
	if (!empty($cat)) {
		$children = $cat->getChildren();
		if (!empty($children)) {
			foreach ($children as $ch) {
				$out[] = $ch->toArray();
			}
		}
	}
	$w->out(json_encode($out));
}