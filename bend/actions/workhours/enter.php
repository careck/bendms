<?php
function enter_GET(Web $w) {
	History::add("Enter Workhours");
	$form["Work Hours"]=array(
			array(
					$w->Auth->hasRole("bend_admin") ?
						array("Who did the work?",  "select", "user_id", $w->Auth->user()->id, $w->Bend->getOccupantUsers()) :
						array("Who did the work?",  "static", "", $w->Auth->user()->getFullName()),
					array("Who to credit to",  "select", "attributed_user_id", $w->Auth->user()->id, $w->Bend->getOccupants()),
					array("Date", "date", "d_date"),
					array("Hours","text","hours"),
			),
			array(
					array("Focus Group","select","category_1",null,$w->Bend->getTopLevelWorkCategories()),
					array("Team or Activity","select","category_2",null),
					array("Activity","select","category_3",null),
			),
			array(
					array("Description","text","description",""),
			),
	);
	$w->ctx("form",Html::multiColForm($form, "/bend-workhours/enter", "POST", "Save"));
	
}

function enter_POST(Web $w) {
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
	
	//print_r($we);exit;
	// TODO check work period, etc.
	$we->insert();
	
	$w->msg("Work hours recorded","/bend-workhours/");
}