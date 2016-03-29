<?php
function editworkentry_GET(Web $w) {
	list($workentry_id) = $w->pathMatch("id");
	if (empty($workentry_id)) {
		$w->error("Missing an ID");
	}
	$workentry = $w->Bend->getWorkEntryForId($workentry_id);
	if (empty($workentry)) {
		$w->error("No work entry found for this id: ".$workentry_id);
	}
	$category = $workentry->getWorkCategory();
	list($category_1, $category_2, $category_3) = $category->getPath();
	
	$form["Work Hours"]=array(
			array(
					$w->Auth->hasRole("bend_admin") ?
						array("User",  "select", "user_id", $workentry->user_id, $w->Bend->getOccupantUsers()) :
						array("User",  "static", "", $w->Auth->getUser($workentry->user_id)->getFullName()),
					array("Date", "date", "d_date", formatDate($workentry->d_date)),
					array("Hours","text","hours",$workentry->hours),
			),
			array(
					array("Focus Group","select","category_1",defaultVal($category_1->id),$w->Bend->getTopLevelWorkCategories()),
					array("Team or Activity","select","category_2",defaultVal($category_2->id),!empty($category_1) ? $category_1->getChildren() : null), 
					array("Activity","select","category_3",defaultVal($category_3->id),!empty($category_2) ? $category_2->getChildren() : null),
			),
			array(
					array("Description","text","description",$workentry->description),
			),
	);
	$w->ctx("form",Html::multiColForm($form, "/bend-workhours/editworkentry/{$workentry_id}", "POST", "Save"));
	
}

function editworkentry_POST(Web $w) {
	$we = new BendWorkEntry($w);
	$we->fill($_POST);
	if (empty($we->user_id)) {
		$we->user_id = $w->Auth->user()->id;
	}
	// now get the category
	if (!empty($_POST['category_3'])) {
		$we->bend_work_category_id = $_POST['category_3']; 
	} else if (!empty($_POST['category_2'])) {
		$we->bend_work_category_id = $_POST['category_2']; 
	} else if (!empty($_POST['category_1'])) {
		$we->bend_work_category_id = $_POST['category_1']; 
	}
	
	// TODO check work period, etc.
	$we->insert();
	
	$w->msg("Work hours recorded","/bend-workhours/list");
}