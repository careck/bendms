<?php
function addcategory_GET(Web $w) {
	list($parent_id) = $w->pathMatch("a");
	$parent = $w->Bend->getWorkCategoryForId($parent_id);
	
	$form = array("Category" => array(
			array(
					array("Title", "text", "title", "")
			),
			array(
					array("Description", "text", "description", "")
			),
	));
	
	$w->out(Html::multiColForm($form, "/bend-workhours/addcategory/{$parent_id}", "POST", "Save"));
	
}

function addcategory_POST(Web $w) {
	list($parent_id) = $w->pathMatch("a");
	$cat = new BendWorkCategory($w);
	$cat->fill($_POST);
	$cat->parent_id = $parent_id;
	$cat->insert();
	
	$w->msg("Category created","/bend-workhours/admin#categories");
}