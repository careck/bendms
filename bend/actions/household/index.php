<?php
function index_ALL(Web $w) {
	History::add("Bend Households");
	$w->ctx("households",$w->Bend->getAllHouseholds());
}