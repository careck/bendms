<?php
function editperiod_GET(Web $w){
	list($periodid) = $w->pathMatch("a");
	$period = new BendWorkPeriod($w);
	if (!empty($periodid)) {
		$period = $w->Bend->getWorkPeriodForId($periodid);
	}
	
	$form["Work Period"]=array(
			array(
					array("Date From","date","d_start",!empty($period->d_start) ? formatDate($period->d_start) : ""),
					array("Date To","date","d_end",!empty($period->d_end) ? formatDate($period->d_end) : ""),
			),
			array(
					array("Monthly Person Hours","text","monthly_person_hours",$period->monthly_person_hours),
					array("Sapphs per Hour","text","sapphs_per_hour",$period->sapphs_per_hour),
					array("Is Closed?","select", "is_closed", $period->is_closed,[["Yes", "1"], ["No","0"]]),
			),
	);
		
	$w->setLayout(null);
	$w->out(Html::multiColForm($form, "/bend-workhours/editperiod/{$periodid}", "POST", "Save"));
}

function editperiod_POST(Web $w){
	list($periodid) = $w->pathMatch("a");
	$period = new BendWorkPeriod($w);
	if (!empty($periodid)) {
		$period = $w->Bend->getWorkPeriodForId($periodid);
	}
	
	$period->fill($_POST);
	$period->insertOrUpdate();
	
	$w->msg("Work Period updated","/bend-workhours/admin#workperiods");
}