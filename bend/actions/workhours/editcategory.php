<?php
function editcategory_GET(Web $w) {
	list($id) = $w->pathMatch("a");
	$cat = $w->Bend->getWorkCategoryForId($id);
	if (empty($cat)) {
		$w->error("no category found","/bend-workhours/admin");
	}
	$form = array("Category" => array(
			array(
					array("Title", "text", "title", $cat->title)
			),
			array(
					array("Description", "text", "description",  $cat->description)
			),
	));
	
	$w->out(Html::multiColForm($form, "/bend-workhours/editcategory/{$id}", "POST", "Save"));
	
}

function editcategory_POST(Web $w) {
	list($id) = $w->pathMatch("a");
	$cat = $w->Bend->getWorkCategoryForId($id);
	if (empty($cat)) {
		$w->error("no category found","/bend-workhours/admin#categories");
	}
	$cat->fill($_POST);
	$cat->update();
	
	$w->msg("Category updated","/bend-workhours/admin#categories");
}