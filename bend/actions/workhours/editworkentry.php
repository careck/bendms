<?php
function editworkentry_GET(Web $w) {
	list($workentry_id) = $w->pathMatch("id");
	if (empty($workentry_id)) {
		$workentry = new BendWorkEntry($w);
		if (!empty($_SESSION["work_period_entry_error_object_data"])) {
			$workentry->fill($_SESSION["work_period_entry_error_object_data"]);
		}
	} else {
		$workentry = $w->Bend->getWorkEntryForId($workentry_id);
	}
	if (empty($workentry)) {
		$w->error("No work entry found for this id: ".$workentry_id);
	}
	$category = $workentry->getWorkCategory();
	if (!empty($category)) {
		$path = $category->getPath();
		if (!empty($path[0])) {
			$category_1 = $path[0];
		}
		if (!empty($path[1])) {
			$category_2 = $path[1];
		}
		if (!empty($path[2])) {
			$category_3 = $path[2];
		}
	}
	$this_user = empty($workentry->user_id) ? $w->Auth->user()->id : $workentry->user_id;
	$this_accredited = empty($workentry->attributed_user_id) ? $w->Auth->user()->id : $workentry->attributed_user_id;
	$households = $w->Bend->getHouseholdsForOccupantId($this_accredited);
	$this_date = empty($workentry->d_date) ? time() : $workentry->d_date;

	$form["Work Hours"]=array(
			array(
					array("Who did the work?",  "select", "user_id", $this_user, $w->Bend->getOccupantUsers()),
					array("Who to credit to",  "select", "attributed_user_id", $this_accredited, $w->Bend->getOccupantUsers()),
					array("Household",  "select", "bend_household_id", $workentry->bend_household_id, $households,"null"),
					array("Date", "date", "d_date", formatDate($this_date)),
					array("Hours","text","hours",$workentry->hours),
			),
			array(
					array("Focus Group","select","category_1",!empty($category_1) ? $category_1->id : null,$w->Bend->getTopLevelWorkCategories()),
					array("Team or Activity","select","category_2",!empty($category_2) ? $category_2->id : null,!empty($category_1) ? $category_1->getChildren() : null), 
					array("Activity","select","category_3",!empty($category_3) ? $category_3->id : null,!empty($category_2) ? $category_2->getChildren() : null),
			),
			array(
					array("Description","text","description",$workentry->description),
			),
	);
	$w->ctx("form",Html::multiColForm($form, "/bend-workhours/editworkentry/{$workentry_id}", "POST", "Save"));
	
}

function editworkentry_POST(Web $w) {
	list($workentry_id) = $w->pathMatch("id");
	if (empty($workentry_id)) {
		$we = new BendWorkEntry($w);	
	} else {
		$we = $w->Bend->getWorkEntryForId($workentry_id);
	}
	if (empty($we)) {
		$w->error("No work entry found for this id: ".$workentry_id);
	}
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
	try {
		$we->insertOrUpdate();
		unset($_SESSION["work_period_entry_error_object_data"]);
	} catch (WorkPeriodClosedException $e) {
		$_SESSION["work_period_entry_error_object_data"] = $we->toArray();
		$w->error("You cannot add hours on this date. This workperiod is closed.","/bend-workhours/editworkentry/".$we->id);
	} catch (NoMatchingWorkPeriodException $e) {
		$_SESSION["work_period_entry_error_object_data"] = $we->toArray();
		$w->error("No matching workperiod found for this date.","/bend-workhours/editworkentry/".$we->id);
	}
	
	
	$w->msg("Work hours recorded","/bend-workhours/list/".$we->user_id."/".$we->bend_workperiod_id);
}